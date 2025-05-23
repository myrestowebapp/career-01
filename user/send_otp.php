<?php
session_start();

// Database connection
$conn = new mysqli('62.72.28.154', 'u144227799_career_user', 'Career1234@', 'u144227799_career_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format.");
}

// Generate a 6-digit OTP
$otp = rand(100000, 999999);

// Check if email already exists (since it's the primary key)
$check_query = "SELECT * FROM otp_verifications WHERE email = ?";
$stmt_check = $conn->prepare($check_query);
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows > 0) {
    // Update existing OTP
    $update_query = "UPDATE otp_verifications SET otp = ?, otp_generated_at = CURRENT_TIMESTAMP WHERE email = ?";
    $stmt_update = $conn->prepare($update_query);
    $stmt_update->bind_param("ss", $otp, $email);
    $stmt_update->execute();
    $stmt_update->close();
} else {
    // Insert new OTP
    $insert_query = "INSERT INTO otp_verifications (email, otp) VALUES (?, ?)";
    $stmt_insert = $conn->prepare($insert_query);
    $stmt_insert->bind_param("ss", $email, $otp);
    $stmt_insert->execute();
    $stmt_insert->close();
}

$stmt_check->close();

// Send OTP via Gmail (using PHPMailer)
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
    

    $mail->setFrom('your-email@gmail.com', 'Career Portal');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Your OTP for Career Portal Login';
    $mail->Body    = "Your One Time Password (OTP) is: <b>$otp</b>. This OTP is valid for 10 minutes.";

    $mail->send();
    $_SESSION['otp_email'] = $email; // Store email in session for verification
echo "<script>window.location.href = 'verify_otp.php';</script>";
exit;

} catch (Exception $e) {
    echo "OTP could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

$conn->close();
?>



