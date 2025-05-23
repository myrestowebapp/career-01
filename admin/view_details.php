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

// Get and sanitize ID from URL
$id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    die("Invalid ID.");
}

// Fetch applicant details
$sql = "SELECT * FROM career_applications WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Application not found.");
}

$row = $result->fetch_assoc();

// Function to generate colored skill badges
function generateSkillBadges($skills) {
    $skillsArray = explode(',', $skills);
    $badges = '';
    foreach ($skillsArray as $skill) {
        $color = '#' . substr(md5($skill), 0, 6);
        $badges .= "<span class='badge' style='background-color: $color; color: #fff; margin: 2px;'>" . htmlspecialchars($skill) . "</span> ";
    }
    return $badges;
}

// Function to display experience
function displayExperience($experienceJson) {
    $experience = json_decode($experienceJson, true);
    $output = '<ul class="list-group">';
    if (!empty($experience['fields'])) {
        for ($i = 0; $i < count($experience['fields']); $i++) {
            $output .= "<li class='list-group-item'>
                        <strong>" . htmlspecialchars($experience['fields'][$i]) . "</strong> at 
                        <em>" . htmlspecialchars($experience['companies'][$i]) . "</em> for 
                        <span>" . htmlspecialchars($experience['durations'][$i]) . "</span>
                    </li>";
        }
    } else {
        $output .= "<li class='list-group-item text-muted'>No Experience</li>";
    }
    $output .= "</ul>";
    return $output;
}

// Include header
include 'includes/header.php';
?>

<!-- Main Content -->
<div class="content-wrapper">
    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title"><i class="fas fa-user"></i> Applicant Details</h2>
        </div>
        
        <div class="card-body">
            <div class="applicant-profile">
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
                
                <div class="profile-header">
                    <div class="profile-image-container">
                        <?php if (!empty($row['profile_image'])): ?>
                            <img src="../<?= htmlspecialchars($row['profile_image']) ?>" alt="Profile Image" class="profile-image">
                        <?php else: ?>
                            <div class="profile-image-placeholder">
                                <i class="fas fa-user"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="profile-info">
                        <h3 class="profile-name"><?= htmlspecialchars($row['name']) ?></h3>
                        <p class="profile-district"><?= htmlspecialchars($row['district']) ?></p>
                    </div>
                </div>
                
                <div class="profile-content">
                    <div class="profile-section">
                        <h4 class="section-title">Basic Information</h4>
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Native Location:</span>
                                <span class="info-value"><?= htmlspecialchars($row['native_location']) ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="profile-section">
                        <h4 class="section-title">Contact Information</h4>
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Email:</span>
                                <span class="info-value"><?= htmlspecialchars($row['email']) ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Mobile:</span>
                                <span class="info-value">
                                    <?= htmlspecialchars($row['mobile']) ?>
                                    <a href="tel:+91<?= htmlspecialchars($row['mobile']) ?>" class="call-link">
                                        <i class="fas fa-phone-alt"></i>
                                    </a>
                                    <a href="https://wa.me/91<?= htmlspecialchars($row['mobile']) ?>" target="_blank" class="whatsapp-link">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">LinkedIn:</span>
                                <span class="info-value">
                                    <?php if (!empty($row['linkedin'])): ?>
                                        <a href="<?= htmlspecialchars($row['linkedin']) ?>" target="_blank" class="linkedin-link">View Profile</a>
                                    <?php else: ?>
                                        Not Provided
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Submitted At:</span>
                                <span class="info-value"><?= htmlspecialchars($row['created_at']) ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="profile-section">
                        <h4 class="section-title">Skills</h4>
                        <div class="skills-container">
                            <?= generateSkillBadges($row['skills']) ?>
                        </div>
                    </div>
                    
                    <div class="profile-section">
                        <h4 class="section-title">Experience</h4>
                        <div class="experience-container">
                            <?= displayExperience($row['experience']) ?>
                        </div>
                    </div>
                    
                    <div class="profile-section">
                        <h4 class="section-title">Uploaded CV</h4>
                        <div class="cv-container">
                            <?php if (!empty($row['cv_file'])): ?>
                                <a href="../uploads/<?= htmlspecialchars($row['cv_file']) ?>" class="btn btn-primary" target="_blank">
                                    <i class="fas fa-file-pdf"></i> View CV
                                </a>
                            <?php else: ?>
                                <span class="text-muted">No CV Uploaded</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="profile-actions">
                        <a href="chat_with_user.php?id=<?= $id ?>" class="btn btn-primary">
                            <i class="fas fa-comments"></i> Chat with <?= htmlspecialchars($row['name']) ?>
                        </a>
                        <button onclick="window.history.back();" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Go Back
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .applicant-profile {
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow-sm);
        position: relative;
        overflow: hidden;
    }
    
    /* Cover Image Styles */
    .profile-cover-container {
        position: relative;
        width: 100%;
        height: 300px;
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
    
    .profile-header {
        display: flex;
        align-items: center;
        padding: var(--spacing-5) var(--spacing-4);
        border-bottom: 1px solid var(--gray-200);
        position: relative;
        z-index: 1;
        margin-top: -150px; /* Increased negative margin to position header higher */
        background-color: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(5px);
        border-top-left-radius: var(--border-radius);
        border-top-right-radius: var(--border-radius);
    }
    
    .profile-image-container {
        flex-shrink: 0;
        margin-right: var(--spacing-4);
        position: relative;
        z-index: 2;
    }
    
    .profile-image {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        margin-top: -100px; /* Pull the image up to overlap half with cover */
    }
    
    .profile-image-placeholder {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background-color: var(--gray-200);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 5px solid white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        margin-top: -100px; /* Pull the placeholder up to overlap half with cover */
    }
    
    .profile-image-placeholder i {
        font-size: 4rem;
        color: var(--gray-500);
    }
    
    .profile-info {
        flex: 1;
        text-align: left;
    }
    
    .profile-name {
        font-size: var(--font-size-xl);
        font-weight: var(--font-weight-semibold);
        color: var(--gray-800);
        margin: 0 0 var(--spacing-2) 0;
    }
    
    .profile-district {
        font-size: var(--font-size-md);
        color: var(--gray-600);
        margin: 0;
    }
    
    .profile-content {
        padding: var(--spacing-5);
    }
    
    .profile-section {
        margin-bottom: var(--spacing-5);
    }
    
    .section-title {
        font-size: var(--font-size-lg);
        font-weight: var(--font-weight-semibold);
        color: var(--gray-700);
        border-bottom: 2px solid var(--gray-200);
        padding-bottom: var(--spacing-2);
        margin-bottom: var(--spacing-4);
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: var(--spacing-4);
    }
    
    .info-item {
        display: flex;
        flex-direction: column;
    }
    
    .info-label {
        font-weight: var(--font-weight-medium);
        color: var(--gray-600);
        margin-bottom: var(--spacing-1);
    }
    
    .info-value {
        color: var(--gray-800);
    }
    
    .whatsapp-link {
        color: #25D366;
        margin-left: var(--spacing-2);
        font-size: var(--font-size-lg);
    }
    
    .linkedin-link {
        color: var(--primary);
        text-decoration: none;
    }
    
    .linkedin-link:hover {
        text-decoration: underline;
    }
    
    .skills-container {
        display: flex;
        flex-wrap: wrap;
        gap: var(--spacing-2);
    }
    
    .badge {
        padding: var(--spacing-2) var(--spacing-3);
        border-radius: var(--border-radius);
        font-size: var(--font-size-sm);
        font-weight: var(--font-weight-medium);
    }
    
    .experience-container .list-group {
        border-radius: var(--border-radius);
        overflow: hidden;
    }
    
    .experience-container .list-group-item {
        padding: var(--spacing-3);
        border-color: var(--gray-200);
    }
    
    .cv-container {
        margin-bottom: var(--spacing-4);
    }
    
    .profile-actions {
        display: flex;
        gap: var(--spacing-3);
        margin-top: var(--spacing-5);
    }
    
    .btn {
        padding: var(--spacing-2) var(--spacing-4);
        border-radius: var(--border-radius);
        font-weight: var(--font-weight-medium);
        display: inline-flex;
        align-items: center;
        gap: var(--spacing-2);
        transition: all var(--transition-speed) var(--transition-timing);
    }
    
    .btn-primary {
        background-color: var(--primary);
        border: 1px solid var(--primary);
        color: white;
    }
    
    .btn-primary:hover {
        background-color: var(--secondary);
        border-color: var(--secondary);
    }
    
    .btn-secondary {
        background-color: var(--gray-200);
        border: 1px solid var(--gray-300);
        color: var(--gray-700);
    }
    
    .btn-secondary:hover {
        background-color: var(--gray-300);
    }
</style>

<?php
// Include footer
include 'includes/footer.php';

$stmt->close();
$conn->close();
?>
