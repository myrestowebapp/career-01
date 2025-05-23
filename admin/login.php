<?php
session_start();

$default_password = "123456789"; // Default admin password

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_password = $_POST['password'];

    if ($entered_password === $default_password) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: index.php"); // Redirect to dashboard
        exit();
    } else {
        $error = "Invalid password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Career Portal - Admin Login</title>
    <!-- Google Fonts - Roboto -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Custom Admin Styles -->
    <link rel="stylesheet" href="assets/admin-style.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: var(--gray-100);
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: var(--spacing-5);
        }
        .login-card {
            background-color: white;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--box-shadow-lg);
            overflow: hidden;
        }
        .login-header {
            background-color: var(--primary);
            color: white;
            padding: var(--spacing-4);
            text-align: center;
        }
        .login-body {
            padding: var(--spacing-5);
        }
        .login-logo {
            width: 120px;
            height: auto;
            margin-bottom: var(--spacing-3);
        }
    </style>
</head>
<body>
<div class="login-container">
    <div class="text-center mb-4">
        <img src="logo.png" alt="Career Portal" class="login-logo">
    </div>
    <div class="login-card">
        <div class="login-header">
            <h2 style="margin: 0; font-weight: var(--font-weight-medium);">Admin Login</h2>
        </div>
        <div class="login-body">
            <form method="POST">
                <?php if (isset($error)): ?>
                    <div style="color: var(--danger); margin-bottom: var(--spacing-4); padding: var(--spacing-3); background-color: rgba(239, 71, 111, 0.1); border-radius: var(--border-radius);">
                        <i class="fas fa-exclamation-circle"></i> <?= $error ?>
                    </div>
                <?php endif; ?>
                <div class="form-group">
                    <label for="password" class="form-label">Enter Password:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-4">Login <i class="fas fa-sign-in-alt ml-2"></i></button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
