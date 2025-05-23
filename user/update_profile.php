<?php
session_start();
$conn = new mysqli('62.72.28.154', 'u144227799_career_user', 'Career1234@', 'u144227799_career_db');
$email = $_SESSION['email'];

$skills = $_POST['skills'];
$experience = $_POST['experience'];

$conn->query("UPDATE career_applications SET skills='$skills', experience='$experience' WHERE email='$email'");

header("Location: dashboard.php");
?>
