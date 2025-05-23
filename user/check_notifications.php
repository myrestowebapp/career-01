<?php
session_start();
$conn = new mysqli('62.72.28.154', 'u144227799_career_user', 'Career1234@', 'u144227799_career_db');
$email = $_SESSION['email'];

$result = $conn->query("SELECT COUNT(*) as unread FROM chat_messages WHERE email='$email' AND admin_reply IS NOT NULL AND is_read = 0");
$unread = $result->fetch_assoc()['unread'];

echo json_encode(["unread" => $unread]);
?>
