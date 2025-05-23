<?php
// Database connection details
$servername = "62.72.28.154";  // Your database server (IP or hostname)
$username = "u144227799_career_user";  // Your database username
$password = "Career1234@";  // Your database password
$dbname = "u144227799_career_db";  // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optionally, set the character set to UTF-8 to support special characters
$conn->set_charset("utf8");

?>
