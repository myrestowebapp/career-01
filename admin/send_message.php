<?php
$conn = new mysqli('62.72.28.154', 'u144227799_career_user', 'Career1234@', 'u144227799_career_db');
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$user_id = $_POST['user_id'] ?? 0;
$sender = $_POST['sender'] ?? 'admin';
$message = trim($_POST['message']);

if (!empty($message)) {
    $sql = "INSERT INTO chat_messages (user_id, sender, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $user_id, $sender, $message);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
?>
