<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MyResto Today</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    <form action="send_otp.php" method="post">
        <label>Email ID:</label>
        <input type="email" name="email" required>
        <button type="submit">Send OTP</button>
    </form>
</div>

</body>
</html>
