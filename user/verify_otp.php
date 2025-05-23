<?php 
session_start();

// Check if email exists in session
if (!isset($_SESSION['otp_email'])) {
    header("Location: login.php"); // Redirect to login if session not found
    exit();
}

$email = $_SESSION['otp_email'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_otp = $_POST['otp'] ?? '';

    // Database connection
    $conn = new mysqli('62.72.28.154', 'u144227799_career_user', 'Career1234@', 'u144227799_career_db');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Verify OTP
    $query = "SELECT otp, otp_generated_at FROM otp_verifications WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        $otp_time = new DateTime($data['otp_generated_at'], new DateTimeZone('UTC'));
        $current_time = new DateTime("now", new DateTimeZone('UTC'));
        $time_diff = $current_time->getTimestamp() - $otp_time->getTimestamp();

        if ($time_diff > 600) { // 600 seconds = 10 minutes
            // OTP expired → Delete OTP & ask to request a new one
            $delete_stmt = $conn->prepare("DELETE FROM otp_verifications WHERE email = ?");
            $delete_stmt->bind_param("s", $email);
            $delete_stmt->execute();
            $delete_stmt->close();

            echo "<p>OTP expired. Please request a new one. <a href='login.php'>Resend OTP</a></p>";
        } elseif ($entered_otp == $data['otp']) {
            // OTP is valid → Log the user in
            $_SESSION['loggedin'] = true;
            $_SESSION['user_email'] = $email;

            // Delete OTP after successful verification
            $delete_stmt = $conn->prepare("DELETE FROM otp_verifications WHERE email = ?");
            $delete_stmt->bind_param("s", $email);
            $delete_stmt->execute();
            $delete_stmt->close();

            header("Location: dashboard.php");
            exit();
        } else {
            echo "<p>Invalid OTP. Please try again.</p>";
        }
    } else {
        echo "<p>OTP not found. Please request a new one.</p>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Enter OTP</h2>
    <p>OTP has been sent to <?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>.</p>
    <form method="POST">
        <input type="text" name="otp" placeholder="Enter OTP" required>
        <button type="submit">Verify</button>
    </form>
</body>
</html>
