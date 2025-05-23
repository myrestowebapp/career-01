<?php
// db_connection.php
$host = '62.72.28.154';
$username = 'u144227799_career_user';
$password = 'Career1234@';
$database = 'u144227799_career_db';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
