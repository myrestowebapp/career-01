<?php
$conn = new mysqli('62.72.28.154', 'u144227799_career_user', 'Career1234@', 'u144227799_career_db');
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$user_id = $_GET['id'] ?? 0;
$sql = "SELECT * FROM chat_messages WHERE user_id = ? ORDER BY timestamp ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='text-center text-muted p-4'><i class='fas fa-comments fa-2x mb-3'></i><p>No messages yet. Start the conversation!</p></div>";
} else {
    while ($row = $result->fetch_assoc()) {
        $class = ($row['sender'] == 'admin') ? 'message-admin' : 'message-user';
        $sender = ucfirst($row['sender']);
        $time = isset($row['timestamp']) ? date('M d, g:i a', strtotime($row['timestamp'])) : '';
        
        echo "<div class='message $class'>";
        echo "<div class='message-content'>" . htmlspecialchars($row['message']) . "</div>";
        echo "<div class='message-time'>$sender • $time</div>";
        echo "</div>";
    }
}

$stmt->close();
$conn->close();
?>
