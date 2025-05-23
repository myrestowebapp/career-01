<?php
$conn = new mysqli('62.72.28.154', 'u144227799_career_user', 'Career1234@', 'u144227799_career_db');
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$user_id = $_POST['user_id'] ?? 0;
$mark = $_POST['mark'] ?? null;

if (!is_numeric($mark) || $mark < 0 || $mark > 100) {
    die("Invalid mark.");
}

$sql = "UPDATE career_applications SET interview_mark = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $mark, $user_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Interview mark saved successfully.";
} else {
    echo "Error saving mark.";
}

$stmt->close();
$conn->close();
?>
