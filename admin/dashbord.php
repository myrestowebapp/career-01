<?php
session_start();
if (!isset($_SESSION['email']) || $_POST['otp'] != $_SESSION['otp']) {
    die("Invalid OTP! <a href='login.php'>Try Again</a>");
}

// Database Connection
$conn = new mysqli('62.72.28.154', 'u144227799_career_user', 'Career1234@', 'u144227799_career_db');
$email = $_SESSION['email'];

// Fetch User Data
$result = $conn->query("SELECT * FROM career_applications WHERE email='$email'");
$user = $result->fetch_assoc();
$marks = $user['marks'] ?? 'Not Available';

// Fetch Unread Messages Count
$chat_result = $conn->query("SELECT COUNT(*) as unread FROM chat_messages WHERE email='$email' AND admin_reply IS NOT NULL AND is_read = 0");
$chat_data = $chat_result->fetch_assoc();
$unread_messages = $chat_data['unread'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<header>
    <div class="logo"><img src="assets/logo.png" alt="Logo"></div>
    <nav>
        <ul>
            <li><a href="#profile">Profile</a></li>
            <li><a href="#chat">Chat <?php if ($unread_messages > 0) echo "<span class='badge'>$unread_messages</span>"; ?></a></li>
            <li><a href="#aadhaar">Upload Aadhaar</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<div class="container">
    <h2>Welcome, <?= htmlspecialchars($user['name']) ?></h2>
    <p><strong>Your Marks:</strong> <?= htmlspecialchars($marks) ?></p>

    <!-- Profile Management -->
    <section id="profile">
        <h3>Update Your Profile</h3>
        <form action="update_profile.php" method="post">
            <label>Skills:</label>
            <input type="text" name="skills" value="<?= htmlspecialchars($user['skills'] ?? '') ?>" required>
            <label>Experience:</label>
            <textarea name="experience"><?= htmlspecialchars($user['experience'] ?? '') ?></textarea>
            <button type="submit">Save</button>
        </form>
    </section>

    <!-- Chat Section -->
    <section id="chat">
        <h3>Chat with Admin</h3>
        <form action="process_chat.php" method="post">
            <textarea name="message" rows="3" placeholder="Type your message here..." required></textarea>
            <button type="submit">Send</button>
        </form>
        <div id="chat_messages">
            <?php
            $messages = $conn->query("SELECT * FROM chat_messages WHERE email='$email' ORDER BY sent_at DESC");
            while ($msg = $messages->fetch_assoc()) {
                echo "<p><strong>You:</strong> " . htmlspecialchars($msg['message']) . "</p>";
                if (!empty($msg['admin_reply'])) {
                    echo "<p><strong>Admin:</strong> " . htmlspecialchars($msg['admin_reply']) . "</p>";
                    $conn->query("UPDATE chat_messages SET is_read=1 WHERE id=" . $msg['id']);
                }
            }
            ?>
        </div>
    </section>

    <!-- Aadhaar Upload -->
    <section id="aadhaar">
        <h3>Upload Aadhaar Card</h3>
        <form action="upload_aadhaar.php" method="post" enctype="multipart/form-data">
            <input type="file" name="aadhaar_file" accept=".jpg, .jpeg, .png, .pdf" required>
            <button type="submit">Upload</button>
        </form>
    </section>
</div>

<footer>
    <p>© 2025 MyResto Today Pvt Ltd</p>
</footer>

<script>
setInterval(() => {
    fetch('check_notifications.php')
    .then(response => response.json())
    .then(data => {
        if (data.unread > 0) {
            document.querySelector('nav ul li a[href="#chat"]').innerHTML = "Chat <span class='badge'>" + data.unread + "</span>";
        }
    });
}, 5000);
</script>

</body>
</html>
