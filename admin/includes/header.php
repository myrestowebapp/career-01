<?php
// Only start session if one doesn't already exist
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Get current page for active menu highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Career Portal - Admin Panel</title>
    <!-- Google Fonts - Roboto -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Custom Admin Styles -->
    <link rel="stylesheet" href="assets/admin-style.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="profile-container">
                <img src="logo.png" alt="Career Portal" class="logo">
                <h3 class="mt-3 mb-4">Admin Dashboard</h3>
            </div>
            
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="index.php" class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="applications.php" class="nav-link <?php echo ($current_page == 'applications.php') ? 'active' : ''; ?>">
                        <i class="fas fa-file-alt"></i> Applications
                    </a>
                </li>
                <li class="nav-item">
                    <a href="interviews.php" class="nav-link <?php echo ($current_page == 'interviews.php') ? 'active' : ''; ?>">
                        <i class="fas fa-user-tie"></i> Interviews
                    </a>
                </li>
                <li class="nav-item">
                    <a href="chat_overview.php" class="nav-link <?php echo ($current_page == 'chat_overview.php' || $current_page == 'chat_with_user.php') ? 'active' : ''; ?>">
                        <i class="fas fa-comments"></i> Chat
                    </a>
                </li>
                <li class="nav-item">
                    <a href="settings.php" class="nav-link <?php echo ($current_page == 'settings.php') ? 'active' : ''; ?>">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                </li>
                <li class="nav-item mt-5">
                    <a href="logout.php" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </aside>
        
        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Top Header -->
            <header class="admin-header">
                <div class="toggle-sidebar d-md-none">
                    <i class="fas fa-bars"></i>
                </div>
                
                <div class="header-title">
                    <i class="fas fa-clipboard-list mr-2"></i> <?php 
                    // Set page title based on current page
                    switch($current_page) {
                        case 'index.php':
                            echo 'Dashboard';
                            break;
                        case 'applications.php':
                            echo 'Applications';
                            break;
                        case 'view_details.php':
                            echo 'Applicant Details';
                            break;
                        case 'chat_with_user.php':
                            echo 'Chat with Applicant';
                            break;
                        case 'chat_overview.php':
                            echo 'Chat Overview';
                            break;
                        case 'settings.php':
                            echo 'Settings';
                            break;
                        default:
                            echo 'Admin Panel';
                    }
                    ?>
                </div>
                
                <div class="header-actions">
                    <div class="search-container">
                        <form method="GET" action="index.php">
                            <input type="text" name="search" class="form-control" placeholder="Search..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        </form>
                    </div>
                </div>
            </header>