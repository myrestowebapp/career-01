<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

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
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
    $uploadDir = 'uploads_profile/';
    $profile_image_path = $uploadDir . time() . '_' . basename($_FILES['profile_image']['name']);
    if (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $profile_image_path)) {
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
$cv_file = $_FILES['cv']['name'];
$cv_tmp_name = $_FILES['cv']['tmp_name'];
$cv_target_path = 'uploads/' . basename($cv_file);

if (!move_uploaded_file($cv_tmp_name, $cv_target_path)) {
    die("Failed to upload CV. Please try again.");
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
        <title>Application Confirmation - myResto Today</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <style>
            body {
                font-family: Arial, sans-serif;
                text-align: center;
                margin: 0;
                padding: 0;
                background-color: #f4f4f4;
            }
            .container {
                width: 100%;
                max-width: 600px;
                margin: 50px auto;
                background: white;
                padding: 20px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
            }
            .header img {
                width: 100px;
            }
            .header h2 {
                color: #333;
            }
            .confirmation {
                margin: 30px 0;
                font-size: 18px;
                color: #555;
            }
            .confirmation img {
                max-width: 100%;
                border-radius: 5px;
                margin: 15px 0;
            }
            .footer {
                margin-top: 30px;
                font-size: 14px;
                color: #666;
            }
            .social-icons a {
                margin: 0 10px;
                color: #333;
                font-size: 18px;
                text-decoration: none;
            }
            .social-icons a:hover {
                color: #007bff;
            }
        </style>
    </head>
    <body>

    <div class="container">
        <div class="header">
            <img src="https://myresto.today/logo.png" alt="myResto Today Logo">
            <h2>Thank You for Your Application!</h2>
        </div>

<div class="confirmation">
        <p>Hi <strong>' . htmlspecialchars($name) . '</strong>,</p>
        <img src="https://career.myresto.today/' . htmlspecialchars($profile_image_path) . '" alt="Uploaded Image" style="max-width: 150px; border-radius: 10px;">
        <p>Your application has been submitted successfully.</p>
        <p>We appreciate your willingness to be a part of our team.</p>
        <p>Please schedule your meeting with the company\'s onboarding team using the link provided in the email you received.</p>
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


// Allowed MIME types for profile images
$allowed_image_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
$allowed_cv_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];

// Check if profile image is uploaded without errors
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    $profile_tmp_path = $_FILES['profile_image']['tmp_name'];

    // Validate MIME type
    $profile_mime = mime_content_type($profile_tmp_path);
    if (!in_array($profile_mime, $allowed_image_types)) {
        die("Invalid profile image format. Please upload a JPG, PNG, or GIF.");
    }

    // Validate file extension (Extra security)
    $profile_extension = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($profile_extension, $allowed_extensions)) {
        die("Invalid file extension. Allowed: JPG, PNG, GIF.");
    }

    // Check file size (Limit to 2MB)
    $max_file_size = 2 * 1024 * 1024; // 2MB
    if ($_FILES['profile_image']['size'] > $max_file_size) {
        die("File size exceeds 2MB limit.");
    }

    // Generate a unique file name to prevent overwriting
    $new_file_name = uniqid('profile_', true) . '.' . $profile_extension;
    
    // Define upload directory
    $upload_dir = "uploads_profile/";
    
    // Move uploaded file to the directory
    if (!move_uploaded_file($profile_tmp_path, $upload_dir . $new_file_name)) {
        die("Failed to upload profile image. Please try again.");
    }

    // Save file path for database insertion
    $profile_image_path = $new_file_name;
}

// Allowed MIME types for CVs
$allowed_cv_types = [
    'application/pdf',
    'application/msword', // .doc
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document' // .docx
];

// Check if CV file is uploaded without errors
if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
    $cv_tmp_path = $_FILES['cv']['tmp_name'];

    // Validate MIME type
    $cv_mime = mime_content_type($cv_tmp_path);
    if (!in_array($cv_mime, $allowed_cv_types)) {
        die("Invalid CV format. Only PDF and DOC files are allowed.");
    }

    // Validate file extension (Extra security)
    $cv_extension = strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION));
    $allowed_cv_extensions = ['pdf', 'doc', 'docx'];
    if (!in_array($cv_extension, $allowed_cv_extensions)) {
        die("Invalid file extension. Only PDF, DOC, and DOCX are allowed.");
    }

    // Check file size (Limit to 5MB)
    $max_cv_size = 5 * 1024 * 1024; // 5MB
    if ($_FILES['cv']['size'] > $max_cv_size) {
        die("CV file size exceeds the 5MB limit.");
    }

    // Generate a unique file name to prevent overwriting
    $new_cv_file_name = uniqid('cv_', true) . '.' . $cv_extension;
    
    // Define upload directory
    $upload_dir = "uploads_cv/";

    // Move uploaded file to the directory
    if (!move_uploaded_file($cv_tmp_path, $upload_dir . $new_cv_file_name)) {
        die("Failed to upload CV. Please try again.");
    }

    // Save file path for database insertion
    $cv_file = $new_cv_file_name;
}


// Send email to the applicant
if (!empty($email)) {
    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'career.myrestotoday@gmail.com';
        $mail->Password = 'nvpf wuux rvdn wmbu'; // Use app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email Setup
        $mail->setFrom('mail@myresto.today', 'myResto Today Pvt Ltd');
        $mail->addAddress($email, $name);
        $mail->isHTML(true);
        $mail->Subject = 'Thank You for Your Application';

        $mail->Body = ' 
        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyResto Today - Career Application</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f9f9f9; color: #333; }
        .container { max-width: 600px; margin: 30px auto; padding: 20px; background: #fff; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); text-align: center; }
        .header { text-align: center; border-bottom: 2px solid #f1f1f1; padding-bottom: 20px; }
        .content { line-height: 1.6; text-align: left; }
        .footer { background-color: #333; color: #fff; padding: 20px; margin-top: 30px; text-align: center; border-radius: 8px; }
        .btn { display: inline-block; margin-top: 20px; background: #007BFF; color: #fff; padding: 10px 20px; border-radius: 5px; text-decoration: none; }
        .btn:hover { background: #0056b3; }
        .social-icons a { color: #fff; font-size: 18px; margin: 0 8px; transition: color 0.3s ease-in-out; }
        .social-icons a:hover { color: #f9b115; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://myresto.today/logo.png" alt="myResto Today Logo" width="100">
            <h2>Thank You for Your Application!</h2>
        </div>
        <div class="content">
            <p>Dear ' . htmlspecialchars($name) . ',</p>
            <p>Thank you for applying to our organization. We have received your application and will review it soon. Below are the details you submitted:</p>
            <ul>
                <li><strong>Email:</strong> ' . htmlspecialchars($email) . '</li>
                <li><strong>Mobile:</strong> ' . htmlspecialchars($mobile) . '</li>
                <li><strong>Skills:</strong> ' . htmlspecialchars(implode(', ', $skills)) . '</li>
            </ul>
            <p>If any of the above information is incorrect, please contact us at <a href="mailto:myrestotoday@gmail.com">myrestotoday@gmail.com</a>.</p>
            <p>Your application has been successfully submitted. To complete the process You also schedule an online meeting with our CEO at your convenience:</p>
            <a href="https://calendly.com/haris-imran-etpa/face_to_face_meeting" class="btn">Schedule Meeting</a>
        </div>
        <div class="footer">
            <p>&copy; ' . date('Y') . ' myResto Today Pvt Ltd. All rights reserved.</p>
            <p>Building No. 60/44, JC Chambers, Panampilly Nagar, Ernakulam - 682036, Kerala</p>
            <p>Email: chairman@myresto.today | +91 9747 650 176</p>
            <div class="social-icons">
                <a href="https://facebook.com/myrestotoday" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="https://twitter.com/myrestotoday" target="_blank"><i class="fab fa-twitter"></i></a>
                <a href="https://instagram.com/myrestotoday" target="_blank"><i class="fab fa-instagram"></i></a>
                <a href="https://linkedin.com/company/myrestotoday" target="_blank"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
    </div>
</body>
</html>
        ';

        $mail->send();
        echo "<h3 style='color:blue; text-align:center;'>Email sent successfully to $email!</h3>";
    } catch (Exception $e) {
        echo "<h3 style='color:red; text-align:center;'>Failed to send email. Error: {$mail->ErrorInfo}</h3>";
    }
}

$conn->close();
?>
