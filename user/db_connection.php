<?php
$host = '62.72.28.154';
$username = 'u144227799_career_user';
$password = 'Career1234@';
$dbname = 'u144227799_career_db';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
