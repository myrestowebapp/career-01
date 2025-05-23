<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Database Connection
$conn = new mysqli('62.72.28.154', 'u144227799_career_user', 'Career1234@', 'u144227799_career_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get chat statistics
$total_chats_query = "SELECT COUNT(DISTINCT user_id) as total FROM chat_messages";
$total_chats_result = $conn->query($total_chats_query);
$total_chats = $total_chats_result->fetch_assoc()['total'] ?? 0;

$unread_chats_query = "SELECT COUNT(DISTINCT user_id) as unread FROM chat_messages WHERE sender = 'user' AND is_read = 0";
$unread_chats_result = $conn->query($unread_chats_query);
$unread_chats = $unread_chats_result->fetch_assoc()['unread'] ?? 0;

// Get recent conversations
$recent_chats_query = "SELECT 
    cm.user_id,
    ca.name,
    ca.email,
    ca.district,
    ca.profile_image,
    MAX(cm.timestamp) as last_message_time,
    (SELECT COUNT(*) FROM chat_messages WHERE user_id = cm.user_id AND sender = 'user' AND is_read = 0) as unread_count,
    (SELECT message FROM chat_messages WHERE user_id = cm.user_id ORDER BY timestamp DESC LIMIT 1) as last_message
    FROM chat_messages cm
    JOIN career_applications ca ON cm.user_id = ca.id
    GROUP BY cm.user_id
    ORDER BY last_message_time DESC";

$recent_chats_result = $conn->query($recent_chats_query);

// Include header
include 'includes/header.php';
?>

<!-- Main Content -->
<div class="content-wrapper">
    <!-- Page Title -->
    <div class="page-header">
        <h1><i class="fas fa-comments"></i> Chat Overview</h1>
        <p>Manage all conversations with applicants</p>
    </div>
    
    <!-- Chat Statistics -->
    <div class="row">
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="stats-card bg-primary">
                <div class="stats-card-body">
                    <div class="stats-icon"><i class="fas fa-comments"></i></div>
                    <div class="stats-content">
                        <h3 class="stats-number"><?= $total_chats ?></h3>
                        <p class="stats-text">Total Conversations</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="stats-card bg-warning">
                <div class="stats-card-body">
                    <div class="stats-icon"><i class="fas fa-envelope"></i></div>
                    <div class="stats-content">
                        <h3 class="stats-number"><?= $unread_chats ?></h3>
                        <p class="stats-text">Unread Conversations</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Search and Filter -->
    <div class="content-card mb-4">
        <div class="card-header">
            <h2 class="card-title"><i class="fas fa-search"></i> Search Conversations</h2>
        </div>
        <div class="card-body">
            <form action="" method="GET" class="filter-form">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="search_name">Applicant Name</label>
                        <input type="text" class="form-control" id="search_name" name="search_name" placeholder="Search by name">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="search_district">District</label>
                        <input type="text" class="form-control" id="search_district" name="search_district" placeholder="Search by district">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="filter_unread">Message Status</label>
                        <select class="form-control" id="filter_unread" name="filter_unread">
                            <option value="">All Messages</option>
                            <option value="1">Unread Only</option>
                        </select>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                    <a href="chat_overview.php" class="btn btn-secondary"><i class="fas fa-redo"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Conversations List -->
    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title"><i class="fas fa-comments"></i> Recent Conversations</h2>
        </div>
        <div class="card-body">
            <?php if ($recent_chats_result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Applicant</th>
                                <th>Last Message</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($chat = $recent_chats_result->fetch_assoc()): ?>
                                <tr class="<?= $chat['unread_count'] > 0 ? 'table-warning' : '' ?>">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="chat-user-avatar">
                                                <?php if (!empty($chat['profile_image'])): ?>
                                                    <img src="../<?= htmlspecialchars($chat['profile_image']) ?>" alt="Profile" class="avatar-img">
                                                <?php else: ?>
                                                    <div class="avatar-placeholder">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="ml-3">
                                                <h5 class="mb-0"><?= htmlspecialchars($chat['name']) ?></h5>
                                                <small class="text-muted"><?= htmlspecialchars($chat['district']) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="chat-preview">
                                            <?= htmlspecialchars(substr($chat['last_message'], 0, 50)) ?><?= strlen($chat['last_message']) > 50 ? '...' : '' ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted"><?= date('M d, g:i a', strtotime($chat['last_message_time'])) ?></span>
                                    </td>
                                    <td>
                                        <?php if ($chat['unread_count'] > 0): ?>
                                            <span class="badge badge-warning"><?= $chat['unread_count'] ?> Unread</span>
                                        <?php else: ?>
                                            <span class="badge badge-success">Read</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="chat_with_user.php?id=<?= $chat['user_id'] ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-comments"></i> Chat
                                        </a>
                                        <a href="view_details.php?id=<?= $chat['user_id'] ?>" class="btn btn-info btn-sm">
                                            <i class="fas fa-user"></i> Profile
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-comments fa-3x text-muted"></i>
                    <h3>No Conversations Yet</h3>
                    <p>When applicants start chatting, their conversations will appear here.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
$(document).ready(function() {
    // Auto-refresh the page every 60 seconds to show new messages
    setTimeout(function() {
        location.reload();
    }, 60000);
    
    // Auto-submit form when select fields change
    $('#filter_unread').on('change', function() {
        $(this).closest('form').submit();
    });
});
</script>