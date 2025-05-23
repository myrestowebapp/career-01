<?php
session_start();
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $otp = $_POST['otp'] ?? '';

    $checkOtpQuery = "SELECT otp_generated_at FROM otp_verifications WHERE email = ? AND otp = ?";
    $stmt = $conn->prepare($checkOtpQuery);
    $stmt->bind_param('ss', $email, $otp);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($otpGeneratedAt);
        $stmt->fetch();

        $timeDifference = strtotime('now') - strtotime($otpGeneratedAt);

        if ($timeDifference <= 300) { // OTP valid for 5 minutes
            $_SESSION['user_email'] = $email;
            header('Location: dashboard.php');
            exit();
        } else {
            echo "OTP expired. Please try again.";
        }
    } else {
        echo "Invalid OTP.";
    }

    $stmt->close();
    $conn->close();
}
?>
