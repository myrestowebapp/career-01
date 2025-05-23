<?php
// Database Connection
$conn = new mysqli('62.72.28.154', 'u144227799_career_user', 'Career1234@', 'u144227799_career_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user ID from the URL
$user_id = $_GET['id'] ?? 0;

// Fetch user details
$sql = "SELECT * FROM career_applications WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("User not found.");
}

$user = $result->fetch_assoc();
$stmt->close();

// Include header
include 'includes/header.php';
?>

<!-- Main Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-user-tie mr-2"></i> Applicant: <?= htmlspecialchars($user['name']) ?>
                    </h5>
                    <a href="view_details.php?id=<?= $user_id ?>" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> Back to Details
                    </a>
                </div>
                
                <div class="card-body">
                    <!-- Interview Mark Section -->
                    <div class="form-group mb-4">
                        <label class="form-label">Interview Mark (Out of 100):</label>
                        <div class="d-flex">
                            <input type="number" id="interview-mark" class="form-control" value="<?= $user['interview_mark'] ?? '' ?>" min="0" max="100">
                            <button class="btn btn-success ml-2" onclick="saveMark()">Save Mark</button>
                        </div>
                    </div>
                    
                    <!-- Chat Interface -->
                    <div class="chat-container">
                        <div class="chat-messages" id="chat-box">
                            <!-- Messages will be loaded here using AJAX -->
                        </div>
                        
                        <div class="chat-input">
                            <form id="chat-form" class="d-flex w-100">
                                <input type="hidden" name="user_id" value="<?= $user_id ?>">
                                <input type="hidden" name="sender" value="admin">
                                <input type="text" name="message" id="message" class="form-control" placeholder="Type your message..." required>
                                <button class="btn btn-primary ml-2" type="submit">
                                    <i class="fas fa-paper-plane"></i> Send
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Applicant Summary Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title">Applicant Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
                            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                            <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>District:</strong> <?= htmlspecialchars($user['district']) ?></p>
                            <p><strong>Skills:</strong> <?= htmlspecialchars($user['skills']) ?></p>
                            <p><strong>Applied On:</strong> <?= date('d M Y', strtotime($user['created_at'])) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Chat Functionality -->
<script>
$(document).ready(function(){
    function loadChat() {
        $.ajax({
            url: "load_chat.php?id=<?= $user_id ?>",
            method: "GET",
            success: function(data) {
                $("#chat-box").html(data);
                $("#chat-box").scrollTop($("#chat-box")[0].scrollHeight);
            }
        });
    }

    $("#chat-form").submit(function(e){
        e.preventDefault();
        $.ajax({
            url: "send_message.php",
            method: "POST",
            data: $(this).serialize(),
            success: function() {
                $("#message").val("");
                loadChat();
            }
        });
    });

    loadChat();
    setInterval(loadChat, 3000);
});

function saveMark() {
    let mark = $("#interview-mark").val();
    $.post("save_mark.php", { user_id: <?= $user_id ?>, mark: mark }, function(response) {
        alert(response);
    });
}
</script>

<?php
// Include footer
include 'includes/footer.php';
?>
