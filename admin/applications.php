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

// Handle Filtering
$filter_district = isset($_GET['district']) ? $conn->real_escape_string($_GET['district']) : '';
$filter_skills = isset($_GET['skills']) ? $conn->real_escape_string($_GET['skills']) : '';
$sort_by = isset($_GET['sort']) ? $conn->real_escape_string($_GET['sort']) : 'created_at';
$sort_order = isset($_GET['order']) ? $conn->real_escape_string($_GET['order']) : 'DESC';

// Validate sort parameters
$allowed_sort_fields = ['name', 'district', 'created_at', 'marks'];
$allowed_sort_orders = ['ASC', 'DESC'];

if (!in_array($sort_by, $allowed_sort_fields)) {
    $sort_by = 'created_at';
}

if (!in_array($sort_order, $allowed_sort_orders)) {
    $sort_order = 'DESC';
}

// Build query with filters
$where_clauses = [];
if (!empty($filter_district)) {
    $where_clauses[] = "district LIKE '%$filter_district%'";
}
if (!empty($filter_skills)) {
    $where_clauses[] = "skills LIKE '%$filter_skills%'";
}

$where_sql = '';
if (!empty($where_clauses)) {
    $where_sql = 'WHERE ' . implode(' AND ', $where_clauses);
}

$sql = "SELECT * FROM career_applications $where_sql ORDER BY $sort_by $sort_order";
$result = $conn->query($sql);

// Get unique districts for filter dropdown
$districts_query = "SELECT DISTINCT district FROM career_applications ORDER BY district ASC";
$districts_result = $conn->query($districts_query);

// Include header
include 'includes/header.php';
?>

<!-- Main Content -->
<main class="main-content">
    <div class="content-wrapper">
        <!-- Page Title -->
        <div class="page-header">
            <h1><i class="fas fa-file-alt"></i> Job Applications</h1>
            <p>View and manage all job applications</p>
        </div>
        
        <!-- Filters Section -->
        <div class="content-card">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-filter"></i> Filter Applications</h2>
            </div>
            <div class="card-body">
                <form action="" method="GET" class="filter-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="district">District</label>
                            <select name="district" id="district" class="form-control">
                                <option value="">All Districts</option>
                                <?php while($district = $districts_result->fetch_assoc()): ?>
                                    <option value="<?= htmlspecialchars($district['district']) ?>" <?= ($filter_district == $district['district']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($district['district']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="skills">Skills</label>
                            <input type="text" name="skills" id="skills" class="form-control" placeholder="e.g. PHP, JavaScript" value="<?= htmlspecialchars($filter_skills) ?>">
                        </div>
                        <div class="form-group">
                            <label for="sort">Sort By</label>
                            <select name="sort" id="sort" class="form-control">
                                <option value="created_at" <?= ($sort_by == 'created_at') ? 'selected' : '' ?>>Date Applied</option>
                                <option value="name" <?= ($sort_by == 'name') ? 'selected' : '' ?>>Name</option>
                                <option value="district" <?= ($sort_by == 'district') ? 'selected' : '' ?>>District</option>
                                <option value="marks" <?= ($sort_by == 'marks') ? 'selected' : '' ?>>Marks</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="order">Order</label>
                            <select name="order" id="order" class="form-control">
                                <option value="DESC" <?= ($sort_order == 'DESC') ? 'selected' : '' ?>>Descending</option>
                                <option value="ASC" <?= ($sort_order == 'ASC') ? 'selected' : '' ?>>Ascending</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Apply Filters</button>
                        <a href="applications.php" class="btn btn-secondary"><i class="fas fa-redo"></i> Reset</a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Applications List -->
        <div class="content-card mt-4">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-users"></i> Applications (<?= $result->num_rows ?>)</h2>
            </div>
            <div class="card-body">
                <?php if ($result->num_rows > 0): ?>
                    <div class="applications-grid">
                        <?php while($row = $result->fetch_assoc()): ?>
                            <div class="applicant-card">
                                <div class="applicant-profile-image">
                                    <?php if (!empty($row['profile_image'])): ?>
                                        <img src="../<?= htmlspecialchars($row['profile_image']) ?>" alt="Profile Image" class="profile-image">
                                    <?php else: ?>
                                        <div class="profile-image-placeholder">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="applicant-header">
                                    <h3 class="applicant-name"><?= htmlspecialchars($row['name']) ?></h3>
                                    <span class="district-badge">
                                        <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($row['district']) ?>
                                    </span>
                                </div>
                                <div class="applicant-info">
                                    <div class="info-item">
                                        <i class="fas fa-envelope"></i> <?= htmlspecialchars($row['email']) ?>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-phone"></i> <?= htmlspecialchars($row['mobile']) ?>
                                    </div>
                                    <?php if (!empty($row['marks'])): ?>
                                    <div class="info-item marks">
                                        <i class="fas fa-star"></i> Marks: <?= htmlspecialchars($row['marks']) ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="applicant-skills">
                                    <?php
                                    $skills = explode(',', $row['skills']);
                                    foreach ($skills as $skill) {
                                        $color = '#' . substr(md5($skill), 0, 6);
                                        echo "<span class='skill-badge' style='background-color: $color;'>" . htmlspecialchars(trim($skill)) . "</span>";
                                    }
                                    ?>
                                </div>
                                <div class="applicant-actions">
                                    <a href="view_details.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                    <a href="chat_with_user.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-comments"></i> Chat
                                    </a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-search fa-3x"></i>
                        <h3>No Applications Found</h3>
                        <p>Try adjusting your filters or check back later for new applications.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
</main>

<script>
// Add any JavaScript functionality here
document.addEventListener('DOMContentLoaded', function() {
    // Example: Auto-submit form when select fields change
    const filterSelects = document.querySelectorAll('.filter-form select');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            document.querySelector('.filter-form').submit();
        });
    });
});
</script>