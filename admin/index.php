<?php 
session_start();

// Default Admin Password
$admin_password = "123456789";

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Database Connection
$conn = new mysqli('62.72.28.154', 'u144227799_career_user', 'Career1234@', 'u144227799_career_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Search
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $conn->real_escape_string($_GET['search']);
    $sql = "SELECT * FROM career_applications 
            WHERE name LIKE '%$search_query%' 
            OR district LIKE '%$search_query%' 
            OR skills LIKE '%$search_query%'";
} else {
    $sql = "SELECT * FROM career_applications";
}

$result = $conn->query($sql);

// Include header
include 'includes/header.php';
?>

<!-- Main Content -->
<div class="content-wrapper">
    <!-- Search Section -->
    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title"><i class="fas fa-search"></i> Search Applications</h2>
        </div>
        <div class="card-body">
            <form action="" method="GET" class="search-form">
                <div class="search-container">
                    <input type="text" name="search" class="search-input" placeholder="Search by name, district, or skills" value="<?= htmlspecialchars($search_query) ?>">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <?php if (!empty($search_query)): ?>
                        <a href="index.php" class="btn btn-secondary">Clear</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Applications Grid -->
    <div class="content-card mt-4">
        <div class="card-header">
            <h2 class="card-title"><i class="fas fa-users"></i> Applications</h2>
        </div>
        <div class="card-body">
            <div class="applications-grid">
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="applicant-card">
                            <!-- Cover Image Section (Using profile image as cover) -->
                            <div class="profile-cover-container">
                                <?php if (!empty($row['profile_image'])): ?>
                                    <div class="profile-cover" style="background-image: url('../<?= htmlspecialchars($row['profile_image']) ?>');"></div>
                                    <div class="cover-overlay"></div>
                                <?php else: ?>
                                    <div class="profile-cover default-cover"></div>
                                    <div class="cover-overlay"></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="applicant-header">
                                <div class="profile-image-container">
                                    <?php if (!empty($row['profile_image'])): ?>
                                        <img src="../<?= htmlspecialchars($row['profile_image']) ?>" alt="Profile Image" class="profile-image">
                                    <?php else: ?>
                                        <div class="profile-image-placeholder">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="applicant-info">
                                    <h3 class="applicant-name"><?= htmlspecialchars($row['name']) ?></h3>
                                    <span class="district-badge">
                                        <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($row['district']) ?>
                                    </span>
                                </div>
                            </div>
                            <div class="applicant-skills">
                                <?php
                                $skills = explode(',', $row['skills']);
                                foreach ($skills as $skill) {
                                    $color = '#' . substr(md5($skill), 0, 6);
                                    echo "<span class='skill-badge' style='background-color: $color;'>$skill</span>";
                                }
                                ?>
                            </div>
                            <div class="applicant-status">
                                <?php
                                $status = $row['status'] ?? 'New';
                                $statusClass = '';
                                
                                switch($status) {
                                    case 'New':
                                        $statusClass = 'status-new';
                                        break;
                                    case 'Contacted':
                                        $statusClass = 'status-contacted';
                                        break;
                                    case 'Interviewed':
                                        $statusClass = 'status-interviewed';
                                        break;
                                    case 'Rejected':
                                        $statusClass = 'status-rejected';
                                        break;
                                    default:
                                        $statusClass = 'status-new';
                                }
                                ?>
                                <span class="status-badge <?= $statusClass ?>">
                                    <?= htmlspecialchars($status) ?>
                                </span>
                            </div>
                            <div class="applicant-actions">
                                <a href="view_details.php?id=<?= $row['id'] ?>" class="btn btn-primary">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                                <a href="chat_with_user.php?id=<?= $row['id'] ?>" class="btn btn-secondary">
                                    <i class="fas fa-comments"></i> Chat
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-results">
                        <i class="fas fa-search fa-3x"></i>
                        <p>No applications found.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    .search-form {
        width: 100%;
    }
    
    .search-container {
        display: flex;
        gap: var(--spacing-3);
    }
    
    .search-input {
        flex: 1;
        padding: var(--spacing-3);
        border: 1px solid var(--gray-300);
        border-radius: var(--border-radius);
        font-size: var(--font-size-base);
    }
    
    .applications-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: var(--spacing-4);
    }
    
    .applicant-card {
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow-sm);
        padding: 0;
        transition: transform var(--transition-speed) var(--transition-timing), box-shadow var(--transition-speed) var(--transition-timing);
        overflow: hidden;
    }
    
    .applicant-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--box-shadow);
    }
    
    /* Cover Image Styles */
    .profile-cover-container {
        position: relative;
        width: 100%;
        height: 100px;
        overflow: hidden;
    }
    
    .profile-cover {
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center top;
        background-repeat: no-repeat;
        transition: transform 0.3s ease;
        transform: scale(1.05);
        filter: blur(1px);
    }
    
    .profile-cover:hover {
        transform: scale(1.1);
    }
    
    .default-cover {
        background-color: var(--primary);
        background-image: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    }
    
    .cover-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, rgba(0,0,0,0.2), rgba(0,0,0,0.5));
    }
    
    .applicant-header {
        display: flex;
        align-items: center;
        padding: var(--spacing-4);
        position: relative;
        z-index: 1;
        margin-top: -40px;
    }
    
    .profile-image-container {
        flex-shrink: 0;
        margin-right: var(--spacing-3);
        position: relative;
        z-index: 2;
    }
    
    .profile-image {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }
    
    .profile-image-placeholder {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: var(--gray-200);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }
    
    .profile-image-placeholder i {
        font-size: 2rem;
        color: var(--gray-500);
    }
    
    .applicant-info {
        flex: 1;
    }
    
    .applicant-name {
        font-size: var(--font-size-lg);
        font-weight: var(--font-weight-semibold);
        color: var(--gray-800);
        margin-bottom: var(--spacing-2);
    }
    
    .district-badge {
        display: inline-flex;
        align-items: center;
        gap: var(--spacing-1);
        background-color: var(--gray-200);
        color: var(--gray-700);
        padding: var(--spacing-1) var(--spacing-2);
        border-radius: var(--border-radius);
        font-size: var(--font-size-sm);
    }
    
    .applicant-skills {
        display: flex;
        flex-wrap: wrap;
        gap: var(--spacing-1);
        margin: 0 var(--spacing-4) var(--spacing-3);
    }
    
    .skill-badge {
        color: white;
        padding: var(--spacing-1) var(--spacing-2);
        border-radius: var(--border-radius);
        font-size: var(--font-size-sm);
    }
    
    .applicant-status {
        margin: 0 var(--spacing-4) var(--spacing-3);
    }
    
    .status-badge {
        display: inline-block;
        padding: var(--spacing-1) var(--spacing-2);
        border-radius: var(--border-radius);
        font-size: var(--font-size-sm);
        font-weight: var(--font-weight-medium);
    }
    
    .status-new {
        background-color: var(--primary);
        color: white;
    }
    
    .status-contacted {
        background-color: var(--warning);
        color: var(--dark);
    }
    
    .status-interviewed {
        background-color: var(--success);
        color: white;
    }
    
    .status-rejected {
        background-color: var(--danger);
        color: white;
    }
    
    .applicant-actions {
        display: flex;
        gap: var(--spacing-2);
        margin: var(--spacing-3) var(--spacing-4) var(--spacing-4);
    }
    
    .no-results {
        grid-column: 1 / -1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: var(--spacing-6);
        color: var(--gray-500);
        text-align: center;
    }
    
    .no-results i {
        margin-bottom: var(--spacing-3);
    }
</style>

<?php
// Include footer
include 'includes/footer.php';

$conn->close();
?>
