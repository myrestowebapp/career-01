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

// Check if interviews table exists, if not create it
$check_table = $conn->query("SHOW TABLES LIKE 'interviews'");
if ($check_table->num_rows == 0) {
    $create_table = "CREATE TABLE interviews (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        applicant_id INT(11) NOT NULL,
        interview_date DATETIME NOT NULL,
        interview_type VARCHAR(50) DEFAULT 'Online',
        interview_status VARCHAR(50) DEFAULT 'Scheduled',
        interview_notes TEXT,
        interviewer VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (applicant_id) REFERENCES career_applications(id) ON DELETE CASCADE
    )";
    
    if (!$conn->query($create_table)) {
        $error_message = "Error creating interviews table: " . $conn->error;
    }
}

// Handle form submissions
$success_message = "";
$error_message = "";

// Schedule new interview
if (isset($_POST['schedule_interview'])) {
    $applicant_id = $conn->real_escape_string($_POST['applicant_id']);
    $interview_date = $conn->real_escape_string($_POST['interview_date']);
    $interview_type = $conn->real_escape_string($_POST['interview_type']);
    $interviewer = $conn->real_escape_string($_POST['interviewer']);
    
    $sql = "INSERT INTO interviews (applicant_id, interview_date, interview_type, interviewer) 
            VALUES (?, ?, ?, ?)"; 
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $applicant_id, $interview_date, $interview_type, $interviewer);
    
    if ($stmt->execute()) {
        $success_message = "Interview scheduled successfully!";
    } else {
        $error_message = "Error scheduling interview: " . $stmt->error;
    }
    $stmt->close();
}

// Update interview status
if (isset($_POST['update_status'])) {
    $interview_id = $conn->real_escape_string($_POST['interview_id']);
    $new_status = $conn->real_escape_string($_POST['new_status']);
    $interview_notes = $conn->real_escape_string($_POST['interview_notes']);
    
    $sql = "UPDATE interviews SET interview_status = ?, interview_notes = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $new_status, $interview_notes, $interview_id);
    
    if ($stmt->execute()) {
        $success_message = "Interview status updated successfully!";
    } else {
        $error_message = "Error updating interview status: " . $stmt->error;
    }
    $stmt->close();
}

// Delete interview
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $interview_id = $conn->real_escape_string($_GET['delete']);
    
    $sql = "DELETE FROM interviews WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $interview_id);
    
    if ($stmt->execute()) {
        $success_message = "Interview deleted successfully!";
    } else {
        $error_message = "Error deleting interview: " . $stmt->error;
    }
    $stmt->close();
}

// Handle filtering
$filter_status = isset($_GET['status']) ? $conn->real_escape_string($_GET['status']) : '';
$filter_date = isset($_GET['date']) ? $conn->real_escape_string($_GET['date']) : '';
$filter_type = isset($_GET['type']) ? $conn->real_escape_string($_GET['type']) : '';

// Build query with filters
$where_clauses = [];
if (!empty($filter_status)) {
    $where_clauses[] = "i.interview_status = '$filter_status'";
}
if (!empty($filter_date)) {
    $where_clauses[] = "DATE(i.interview_date) = '$filter_date'";
}
if (!empty($filter_type)) {
    $where_clauses[] = "i.interview_type = '$filter_type'";
}

$where_sql = '';
if (!empty($where_clauses)) {
    $where_sql = 'WHERE ' . implode(' AND ', $where_clauses);
}

// Fetch interviews with applicant details
$sql = "SELECT i.*, a.name as applicant_name, a.email as applicant_email, a.district, a.mobile, a.profile_image 
        FROM interviews i 
        JOIN career_applications a ON i.applicant_id = a.id 
        $where_sql 
        ORDER BY i.interview_date ASC";
$interviews_result = $conn->query($sql);

// Fetch applicants for dropdown
$applicants_sql = "SELECT id, name, email, district FROM career_applications ORDER BY name ASC";
$applicants_result = $conn->query($applicants_sql);

// Include header
include 'includes/header.php';
?>

<iframe src="https://calendar.google.com/calendar/embed?src=haris.imran.etpa%40gmail.com&ctz=Asia%2FKolkata" style="border: 0" width="800" height="600" frameborder="0" scrolling="no"></iframe>
<!-- Main Content -->
<div class="content-wrapper">
    <!-- Page Title -->
    <div class="page-header">
        <h1><i class="fas fa-user-tie"></i> Interview Management</h1>
        <p>Schedule and manage applicant interviews</p>
    </div>
    
    <?php if (!empty($success_message)): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> <?= $success_message ?>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($error_message)): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i> <?= $error_message ?>
    </div>
    <?php endif; ?>
    
    <!-- Schedule New Interview -->
    <div class="content-card mb-4">
        <div class="card-header">
            <h2 class="card-title"><i class="fas fa-calendar-plus"></i> Schedule New Interview</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="applicant_id">Select Applicant</label>
                        <select class="form-control" id="applicant_id" name="applicant_id" required>
                            <option value="">-- Select Applicant --</option>
                            <?php while ($applicant = $applicants_result->fetch_assoc()): ?>
                                <option value="<?= $applicant['id'] ?>">
                                    <?= htmlspecialchars($applicant['name']) ?> - <?= htmlspecialchars($applicant['email']) ?> (<?= htmlspecialchars($applicant['district']) ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="interview_date">Interview Date & Time</label>
                        <input type="datetime-local" class="form-control" id="interview_date" name="interview_date" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="interview_type">Interview Type</label>
                        <select class="form-control" id="interview_type" name="interview_type">
                            <option value="Online">Online</option>
                            <option value="In-person">In-person</option>
                            <option value="Phone">Phone</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="interviewer">Interviewer</label>
                        <input type="text" class="form-control" id="interviewer" name="interviewer" placeholder="Enter interviewer name">
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" name="schedule_interview" class="btn btn-primary">
                        <i class="fas fa-calendar-check"></i> Schedule Interview
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Filter Interviews -->
    <div class="content-card mb-4">
        <div class="card-header">
            <h2 class="card-title"><i class="fas fa-filter"></i> Filter Interviews</h2>
        </div>
        <div class="card-body">
            <form action="" method="GET" class="filter-form">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="Scheduled" <?= ($filter_status == 'Scheduled') ? 'selected' : '' ?>>Scheduled</option>
                            <option value="Completed" <?= ($filter_status == 'Completed') ? 'selected' : '' ?>>Completed</option>
                            <option value="Cancelled" <?= ($filter_status == 'Cancelled') ? 'selected' : '' ?>>Cancelled</option>
                            <option value="No Show" <?= ($filter_status == 'No Show') ? 'selected' : '' ?>>No Show</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="date">Interview Date</label>
                        <input type="date" class="form-control" id="date" name="date" value="<?= $filter_date ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="type">Interview Type</label>
                        <select class="form-control" id="type" name="type">
                            <option value="">All Types</option>
                            <option value="Online" <?= ($filter_type == 'Online') ? 'selected' : '' ?>>Online</option>
                            <option value="In-person" <?= ($filter_type == 'In-person') ? 'selected' : '' ?>>In-person</option>
                            <option value="Phone" <?= ($filter_type == 'Phone') ? 'selected' : '' ?>>Phone</option>
                        </select>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Apply Filters</button>
                    <a href="interviews.php" class="btn btn-secondary"><i class="fas fa-redo"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Interviews List -->
    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title"><i class="fas fa-list"></i> Scheduled Interviews (<?= $interviews_result->num_rows ?>)</h2>
        </div>
        <div class="card-body">
            <?php if ($interviews_result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Applicant</th>
                                <th>Date & Time</th>
                                <th>Type</th>
                                <th>Interviewer</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($interview = $interviews_result->fetch_assoc()): 
                                // Determine row class based on status
                                $row_class = '';
                                switch ($interview['interview_status']) {
                                    case 'Completed':
                                        $row_class = 'table-success';
                                        break;
                                    case 'Cancelled':
                                        $row_class = 'table-danger';
                                        break;
                                    case 'No Show':
                                        $row_class = 'table-warning';
                                        break;
                                    default:
                                        // Check if interview is today
                                        $interview_date = new DateTime($interview['interview_date']);
                                        $today = new DateTime('today');
                                        if ($interview_date->format('Y-m-d') == $today->format('Y-m-d')) {
                                            $row_class = 'table-primary';
                                        }
                                }
                            ?>
                                <tr class="<?= $row_class ?>">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="interview-user-avatar">
                                                <?php if (!empty($interview['profile_image'])): ?>
                                                    <img src="../<?= htmlspecialchars($interview['profile_image']) ?>" alt="Profile" class="avatar-img">
                                                <?php else: ?>
                                                    <div class="avatar-placeholder">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="ml-3">
                                                <h5 class="mb-0"><?= htmlspecialchars($interview['applicant_name']) ?></h5>
                                                <small class="text-muted"><?= htmlspecialchars($interview['applicant_email']) ?></small><br>
                                                <small class="text-muted"><?= htmlspecialchars($interview['mobile']) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?= date('d M Y, h:i A', strtotime($interview['interview_date'])) ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $type_badge_class = 'badge-info';
                                        if ($interview['interview_type'] == 'In-person') {
                                            $type_badge_class = 'badge-primary';
                                        } else if ($interview['interview_type'] == 'Phone') {
                                            $type_badge_class = 'badge-secondary';
                                        }
                                        ?>
                                        <span class="badge <?= $type_badge_class ?>"><?= htmlspecialchars($interview['interview_type']) ?></span>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($interview['interviewer']) ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $status_badge_class = 'badge-info';
                                        if ($interview['interview_status'] == 'Completed') {
                                            $status_badge_class = 'badge-success';
                                        } else if ($interview['interview_status'] == 'Cancelled') {
                                            $status_badge_class = 'badge-danger';
                                        } else if ($interview['interview_status'] == 'No Show') {
                                            $status_badge_class = 'badge-warning';
                                        }
                                        ?>
                                        <span class="badge <?= $status_badge_class ?>"><?= htmlspecialchars($interview['interview_status']) ?></span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                                                Actions
                                            </button>
                                            <div class="dropdown-menu">
                                                <a href="#" class="dropdown-item update-status" data-toggle="modal" data-target="#updateStatusModal" 
                                                   data-id="<?= $interview['id'] ?>"
                                                   data-status="<?= htmlspecialchars($interview['interview_status']) ?>"
                                                   data-notes="<?= htmlspecialchars($interview['interview_notes']) ?>">
                                                    <i class="fas fa-edit"></i> Update Status
                                                </a>
                                                <a href="chat_with_user.php?id=<?= $interview['applicant_id'] ?>" class="dropdown-item">
                                                    <i class="fas fa-comments"></i> Chat with Applicant
                                                </a>
                                                <a href="view_details.php?id=<?= $interview['applicant_id'] ?>" class="dropdown-item">
                                                    <i class="fas fa-user"></i> View Profile
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a href="interviews.php?delete=<?= $interview['id'] ?>" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this interview?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-calendar-alt fa-3x text-muted"></i>
                    <h3>No Interviews Scheduled</h3>
                    <p>Use the form above to schedule interviews with applicants.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" role="dialog" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStatusModalLabel">Update Interview Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="interview_id" id="modal_interview_id">
                    <div class="form-group">
                        <label for="new_status">Status</label>
                        <select class="form-control" id="new_status" name="new_status" required>
                            <option value="Scheduled">Scheduled</option>
                            <option value="Completed">Completed</option>
                            <option value="Cancelled">Cancelled</option>
                            <option value="No Show">No Show</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="interview_notes">Interview Notes</label>
                        <textarea class="form-control" id="interview_notes" name="interview_notes" rows="4" placeholder="Enter interview notes, feedback, or comments"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
$(document).ready(function() {
    // Auto-submit form when select fields change in filter form
    $('.filter-form select, .filter-form input[type="date"]').on('change', function() {
        $(this).closest('form').submit();
    });
    
    // Handle update status modal
    $('.update-status').on('click', function() {
        var id = $(this).data('id');
        var status = $(this).data('status');
        var notes = $(this).data('notes');
        
        $('#modal_interview_id').val(id);
        $('#new_status').val(status);
        $('#interview_notes').val(notes);
    });
});
</script>