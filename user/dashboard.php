<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    die("Access Denied! <a href='login.php'>Login Again</a>");
}

// Ensure user_id is set
if (!isset($_SESSION['user_id'])) {
    die("User session is missing. <a href='login.php'>Login Again</a>");
}

// Database Connection
$conn = new mysqli('62.72.28.154', 'u144227799_career_user', 'Career1234@', 'u144227799_career_db');

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Fetch User Data
$stmt = $conn->prepare("SELECT * FROM career_applications WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    die("User data not found.");
}

$marks = $user['marks'] ?? 'Not Available';

// Fetch Unread Messages Count (without 'is_read' column)
$stmt = $conn->prepare("SELECT COUNT(*) as unread FROM chat_messages WHERE user_id = ? AND sender = 'admin'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$chat_data = $result->fetch_assoc();
$unread_messages = $chat_data['unread'] ?? 0;
$stmt->close();
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
            <li><a href="#chat">Chat <?= ($unread_messages > 0) ? "<span class='badge'>$unread_messages</span>" : ''; ?></a></li>
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
            $stmt = $conn->prepare("SELECT * FROM chat_messages WHERE user_id = ? ORDER BY timestamp DESC");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $messages = $stmt->get_result();

            while ($msg = $messages->fetch_assoc()) {
                echo "<p><strong>You:</strong> " . htmlspecialchars($msg['message']) . "</p>";
                if ($msg['sender'] === 'admin') {
                    echo "<p><strong>Admin:</strong> " . htmlspecialchars($msg['message']) . "</p>";
                }
            }
            $stmt->close();
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

</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
