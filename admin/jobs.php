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
    error_log("Database connection failed: " . $conn->connect_error);
    die("Database connection failed. Please try again later or contact support.");
}

// Check if jobs table exists, if not create it
$check_table = $conn->query("SHOW TABLES LIKE 'jobs'");
if ($check_table->num_rows == 0) {
    $create_table = "CREATE TABLE jobs (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        department VARCHAR(100) NOT NULL,
        location VARCHAR(100) NOT NULL,
        type VARCHAR(50) NOT NULL,
        description TEXT,
        requirements TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if (!$conn->query($create_table)) {
        error_log("Error creating jobs table: " . $conn->error);
        die("Error creating database structure. Please contact support.");
    }
}

// Set the character set to UTF-8
$conn->set_charset("utf8");

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_error.log');

// Handle job deletion
$success_message = "";
$error_message = "";

if (isset($_POST['delete_job'])) {
    $job_id = $conn->real_escape_string($_POST['job_id']);
    
    // Delete the job entry
    $delete_sql = "DELETE FROM jobs WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $job_id);
    
    if ($stmt->execute()) {
        $success_message = "Job deleted successfully!";
    } else {
        $error_message = "Error deleting job: " . $conn->error;
    }
    $stmt->close();
}

// Fetch all jobs
$sql = "SELECT * FROM jobs ORDER BY created_at DESC";
$result = $conn->query($sql);

// Include header
include 'includes/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Manage Jobs</h3>
                </div>
                <div class="card-body">
                    <?php if ($success_message): ?>
                        <div class="alert alert-success">
                            <?php echo $success_message; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error_message): ?>
                        <div class="alert alert-danger">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Department</th>
                                    <th>Location</th>
                                    <th>Type</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result && $result->num_rows > 0): ?>
                                    <?php while($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                                            <td><?php echo htmlspecialchars($row['department']); ?></td>
                                            <td><?php echo htmlspecialchars($row['location']); ?></td>
                                            <td><?php echo htmlspecialchars($row['type']); ?></td>
                                            <td><?php echo date('Y-m-d H:i', strtotime($row['created_at'])); ?></td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm" 
                                                        onclick="confirmDelete(<?php echo $row['id']; ?>)">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No jobs found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this job?
            </div>
            <div class="modal-footer">
                <form method="POST">
                    <input type="hidden" name="job_id" id="deleteJobId">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="delete_job" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(jobId) {
    document.getElementById('deleteJobId').value = jobId;
    $('#deleteModal').modal('show');
}
</script>

<?php
include 'includes/footer.php';
$conn->close();
?>