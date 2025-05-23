<?php
session_start();
$conn = new mysqli('62.72.28.154', 'u144227799_career_user', 'Career1234@', 'u144227799_career_db');

$email = $_SESSION['email'];
$message = $_POST['message'];

$conn->query("INSERT INTO chat_messages (email, message, sent_at) VALUES ('$email', '$message', NOW())");

header("Location: dashboard.php#chat");
?>
