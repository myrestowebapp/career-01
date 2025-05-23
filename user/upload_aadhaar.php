<?php
session_start();
$conn = new mysqli('62.72.28.154', 'u144227799_career_user', 'Career1234@', 'u144227799_career_db');
$email = $_SESSION['email'];

$target_dir = "uploads/";
$file_name = $email . "_" . basename($_FILES["aadhaar_file"]["name"]);
$target_file = $target_dir . $file_name;

if (move_uploaded_file($_FILES["aadhaar_file"]["tmp_name"], $target_file)) {
    $conn->query("UPDATE career_applications SET aadhaar='$file_name' WHERE email='$email'");
    echo "File uploaded successfully.";
} else {
    echo "Error uploading file.";
}
?>
