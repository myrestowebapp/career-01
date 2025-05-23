<?php
// Simplified file - email functionality removed
require 'vendor/autoload.php';

// Define allowed MIME types for CV files
$allowed_cv_types = [
    'application/pdf',
    'application/msword', // .doc
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document' // .docx
];

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('error_log', 'php_error.log');

// Database connection
$conn = new mysqli('62.72.28.154', 'u144227799_career_user', 'Career1234@', 'u144227799_career_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate and retrieve form fields
$name = $_POST['name'] ?? '';
$native_location = $_POST['native_location'] ?? '';
$district = $_POST['district'] ?? '';
$skills = $_POST['skills'] ?? [];
$email = $_POST['email'] ?? '';
$mobile = $_POST['mobile'] ?? '';
$linkedin = $_POST['linkedin'] ?? '';
$experience_fields = $_POST['experience_field'] ?? [];
$experience_companies = $_POST['experience_company'] ?? [];
$experience_durations = $_POST['experience_duration'] ?? [];

// Validate `skills`
if (!is_array($skills) || empty($skills)) {
    die("Skills are required. Please go back and enter at least one skill.");
}

// Validate `experience`
if (empty($experience_fields) || empty($experience_companies) || empty($experience_durations)) {
    die("Experience is required. Please go back and enter at least one experience.");
}

// Handle Profile Image Upload
$profile_image_path = '';

// Check if we have a cropped image from the cropper
if (isset($_POST['croppedImage']) && !empty($_POST['croppedImage'])) {
    // The cropped image is sent as a base64 data URL
    $image_parts = explode(';base64,', $_POST['croppedImage']);
    $image_type_aux = explode('image/', $image_parts[0]);
    $image_type = $image_type_aux[1];
    $image_base64 = base64_decode($image_parts[1]);
    
    // Define upload directory and ensure it exists
    $uploadDir = 'uploads_profile/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    // Generate unique filename
    $profile_image_path = $uploadDir . time() . '_cropped.' . $image_type;
    
    // Save the file
    if (!file_put_contents($profile_image_path, $image_base64)) {
        die("Error saving cropped image. Please try again.");
    }
} 
// Fallback to traditional file upload if no cropped image is provided
elseif (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    // Define upload directory and ensure it exists
    $uploadDir = 'uploads_profile/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Get temporary file information
    $tmp_name = $_FILES['profile_image']['tmp_name'];
    if (!is_uploaded_file($tmp_name)) {
        die("Invalid file upload attempt.");
    }

    // Validate file type using multiple methods
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $detected_type = finfo_file($finfo, $tmp_name);
    finfo_close($finfo);

    if (!in_array($detected_type, $allowed_types)) {
        die("Invalid profile image format. Please upload a JPG, PNG, or GIF.");
    }

    // Validate file extension
    $extension = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($extension, $allowed_extensions)) {
        die("Invalid file extension. Please upload a JPG, PNG, or GIF file.");
    }

    // Generate unique filename and set path
    $profile_image_path = $uploadDir . time() . '_' . basename($_FILES['profile_image']['name']);
    
    // Move the file
    if (!move_uploaded_file($tmp_name, $profile_image_path)) {
        die("Error uploading profile image. Please try again.");
    }
}

// Check for duplicate email
$check_email_query = "SELECT email FROM career_applications WHERE email = ?";
$stmt = $conn->prepare($check_email_query);
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo "<h3 style='color:red; text-align:center;'>This email is already registered. Please use a different email.</h3>";
    exit();
}
$stmt->close();

// Process `skills` and `experience`
$skills_string = implode(',', $skills);
$experience = json_encode([
    'fields' => $experience_fields,
    'companies' => $experience_companies,
    'durations' => $experience_durations,
]);

// Handle CV upload
$cv_file = '';
if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
    $cv_tmp_name = $_FILES['cv']['tmp_name'];
    
    // Validate MIME type
    $cv_mime = mime_content_type($cv_tmp_name);
    if (!in_array($cv_mime, $allowed_cv_types)) {
        die("Invalid CV format. Only PDF and DOC files are allowed.");
    }

    // Validate file extension
    $cv_extension = strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION));
    $allowed_cv_extensions = ['pdf', 'doc', 'docx'];
    if (!in_array($cv_extension, $allowed_cv_extensions)) {
        die("Invalid file extension. Only PDF, DOC, and DOCX are allowed.");
    }

    // Check file size (5MB limit)
    if ($_FILES['cv']['size'] > 5 * 1024 * 1024) {
        die("CV file size exceeds the 5MB limit.");
    }

    // Create uploads_cv directory if it doesn't exist
    $upload_dir = 'uploads_cv/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Generate unique filename
    $cv_file = uniqid('cv_', true) . '.' . $cv_extension;
    $cv_target_path = $upload_dir . $cv_file;

    if (!move_uploaded_file($cv_tmp_name, $cv_target_path)) {
        die("Failed to upload CV. Please try again.");
    }
}

// Insert into database
$sql = "INSERT INTO career_applications (name, native_location, district, skills, email, mobile, linkedin, experience, profile_image, cv_file, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ssssssssss', $name, $native_location, $district, $skills_string, $email, $mobile, $linkedin, $experience, $profile_image_path, $cv_file);

if ($stmt->execute()) {
    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Application Submitted - myResto Today</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            body {
                font-family: "Open Sans", sans-serif;
                background-color: rgb(10, 5, 1);
                color: #fff;
                line-height: 1.6;
            }
            .container {
                max-width: 700px;
                margin: 40px auto;
                background: rgb(20, 12, 5);
                border-radius: 12px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
                border: 1px solid rgba(255,255,255,0.1);
                overflow: hidden;
            }
            .header {
                background: linear-gradient(135deg,rgb(49, 37, 4) 0%,rgb(48, 38, 6) 100%);
                padding: 30px 20px;
                text-align: center;
                color: white;
            }
            .header img {
                width: 250px;
                height: auto;
                margin-bottom: 15px;
                padding: 10px;
            }
            .header h1 {
                font-weight: 600;
                font-size: 28px;
                margin-bottom: 5px;
            }
            .header p {
                opacity: 0.9;
                font-weight: 300;
                font-size: 16px;
            }
            .content {
                padding: 30px;
                text-align: center;
                color: #fff;
            }
            .profile-section {
                margin: 20px 0;
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            .profile-image {
                width: 120px;
                height: 120px;
                border-radius: 50%;
                object-fit: cover;
                border: 4px solidrgb(20, 9, 1);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
            .message {
                margin: 25px 0;
                padding: 0 20px;
                font-size: 17px;
            }
            .message p {
                margin-bottom: 15px;
                color: #fff;
            }
            .message strong {
                color: #fff;
                font-weight: 600;
            }
            .next-steps {
                background-color: rgb(30, 20, 10);
                padding: 20px;
                border-radius: 8px;
                margin: 20px 0;
                text-align: left;
                border: 1px solid rgba(255,255,255,0.2);
            }
            .next-steps h3 {
                color: #fff;
                margin-bottom: 15px;
                font-size: 18px;
                font-weight: 600;
            }
            .next-steps ul {
                padding-left: 20px;
                color: #fff;
            }
            .next-steps li {
                color: #fff;
                margin-bottom: 10px;
            }
            .next-steps a {
                color: #f0f6f7;
                text-decoration: underline;
                transition: color 0.3s ease;
            }
            .next-steps a:hover {
                color: rgb(204, 129, 58);
            }
            .btn {
                display: inline-block;
                background-color:rgb(22, 17, 2);
                color: white;
                padding: 12px 25px;
                border-radius: 6px;
                text-decoration: none;
                font-weight: 600;
                margin-top: 20px;
                transition: all 0.3s ease;
            }
            .btn:hover {
                background-color:rgb(43, 13, 4);
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            }
            .footer {
                background-color: rgb(10, 5, 1);
                padding: 20px;
                text-align: center;
                border-top: 1px solid rgba(255,255,255,0.1);
                font-size: 14px;
            }
            .footer p {
                margin-bottom: 10px;
                color: #fff;
            }
            .social-icons {
                margin-top: 15px;
            }
            .social-icons a {
                color:rgb(204, 129, 58);
                font-size: 18px;
                margin: 0 10px;
                transition: color 0.3s ease;
            }
            .social-icons a:hover {
                color:rgb(63, 5, 5);
            }
            @media (max-width: 768px) {
                .container {
                    margin: 20px;
                    width: auto;
                }
                .header h1 {
                    font-size: 24px;
                }
                .message {
                    padding: 0;
                    font-size: 16px;
                }
                body {
                    background-color: rgb(10, 5, 1);
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <img src="https://myresto.today/03_images/logo.png" alt="myResto Today Logo">
                <h1>Application Submitted!</h1>
                <p>Thank you for your interest in joining our team</p>
            </div>
            
            <div class="content">
                <div class="profile-section">
                    <img src="' . htmlspecialchars($profile_image_path) . '" alt="Your Profile" class="profile-image">
                    <h2>Hello, ' . htmlspecialchars($name) . '!</h2>
                </div>
                
                <div class="message">
                    <p>We\'ve received your application and we\'re excited about the possibility of having you join our team at <strong>myResto Today</strong>.</p>
                    <p>Our team will carefully review your qualifications, experience, and skills to determine if there\'s a good match for our current openings.</p>
                </div>
                
                <div class="next-steps">
                    <h3><i class="fas fa-clipboard-list"></i> Next Steps</h3>
                    <ul>
                        <li>We\'ll review your application within the next 3-5 business days</li>
                        <li>Please Schedule an online meeting with our CEO using the link provided below</li>
                        <li>Follow our company on <a href="https://www.linkedin.com/company/myresto-today/" target="_blank">LinkedIn</a> and connect with our CEO on <a href="https://www.linkedin.com/in/harisimetpa/" target="_blank">LinkedIn</a></li>
                        <li>Prepare for a potential skills assessment in your area of expertise</li>
                        <li>The meeting will also include technical matters</li>
                        <li>Keep an eye on your email and Whatsapp for updates on your application status</li>
                    </ul>
                </div>
                
                <a href="https://calendly.com/haris-imran-etpa/face_to_face_meeting" class="btn">
                    <i class="far fa-calendar-alt"></i> Schedule Your Meeting
                </a>
            </div>
            
            <div class="footer">
                <p>&copy; ' . date('Y') . ' myResto Today Pvt Ltd. All rights reserved.</p>
                <p>Building No. 60/44, JC Chambers, Panampilly Nagar, Ernakulam - 682036, Kerala</p>
                <p>Email: <a href="mailto:chairman@myresto.today">chairman@myresto.today</a> | <a href="tel:+919747650176">+91 9747 650 176</a></p>
                <div class="social-icons">
                    <a href="https://facebook.com/myrestotoday" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://twitter.com/myrestotoday" target="_blank"><i class="fab fa-twitter"></i></a>
                    <a href="https://instagram.com/myrestotoday" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="https://linkedin.com/company/myrestotoday" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </body>
    </html>';
} else {
    echo "Error: " . $stmt->error;
    exit();
}
$stmt->close();

// Play alert sound when application is submitted
echo '<audio autoplay><source src="sounds/alert.mp3" type="audio/mpeg"></audio>';

// Log successful application
error_log("New application submitted by: $name ($email)");

// Close database connection
$conn->close();
?>
