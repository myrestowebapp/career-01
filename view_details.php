<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database Connection
$conn = new mysqli('62.72.28.154', 'u144227799_career_user', 'Career1234@', 'u144227799_career_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the ID from URL
$id = $_GET['id'] ?? 0;

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
?>
<?php include 'includes/header.php'; ?>

<style>
    .applicant-details-container {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-top: 20px;
    }
    .section-title {
        color: #495057;
        font-weight: bold;
        border-bottom: 2px solid #6c757d;
        margin-bottom: 10px;
        padding-bottom: 5px;
    }
    .badge {
        font-size: 0.9em;
    }
    .profile-image {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #6c757d;
        margin-bottom: 15px;
    }
    
    /* Mobile-specific styles to ensure content is readable */
    @media (max-width: 768px) {
        .sidebar {
            /* Ensure sidebar is hidden by default on mobile */
            transform: translateX(-100%);
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            z-index: 1050; /* Higher z-index to ensure it's above other content */
            width: 80%; /* Reduce width on mobile */
            max-width: 280px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }
        
        /* Add extra padding to ensure content doesn't get hidden under the menu */
        .applicant-details-container {
            padding: 15px;
            margin-top: 10px;
            width: 100%;
        }
        
        /* Ensure the toggle button is visible and properly positioned */
        .toggle-sidebar {
            display: flex !important;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1040;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            justify-content: center;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        
        /* Add overlay when sidebar is active */
        .sidebar.active + .main-content::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
        }
    }
</style>

<div class="applicant-details-container">
    <h2 class="text-center mb-4 text-primary">Details of <?= htmlspecialchars($row['name']) ?></h2>

    <div class="text-center">
        <?php if (!empty($row['profile_image'])): ?>
            <img src="<?= htmlspecialchars($row['profile_image']) ?>" alt="Profile Image" class="profile-image">
        <?php else: ?>
            <p class="text-muted">No Profile Image</p>
        <?php endif; ?>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <h4 class="section-title">Basic Information</h4>
            <p><strong>Name:</strong> <?= htmlspecialchars($row['name']) ?></p>
            <p><strong>District:</strong> <?= htmlspecialchars($row['district']) ?></p>
            <p><strong>Native Location:</strong> <?= htmlspecialchars($row['native_location']) ?></p>
        </div>
        <div class="col-md-6">
    <h4 class="section-title">Contact Information</h4>
    <p><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
    <p><strong>Mobile:</strong> 
        <?= htmlspecialchars($row['mobile']) ?> 
        <a href="https://wa.me/<?= htmlspecialchars($row['mobile']) ?>" target="_blank" style="margin-left: 10px;">
            <img src="https://static.vecteezy.com/system/resources/previews/016/716/480/original/whatsapp-icon-free-png.png" alt="Chat on WhatsApp" width="35">
        </a>
    </p>
    <p>
        <strong>LinkedIn Profile:</strong> 
        <?php if (!empty($row['linkedin'])): ?>
            <a href="<?= htmlspecialchars($row['linkedin']) ?>" target="_blank" class="text-decoration-none text-primary">View Profile</a>
        <?php else: ?>
            Not Provided
        <?php endif; ?>
    </p>
    <p><strong>Submitted At:</strong> <?= htmlspecialchars($row['created_at']) ?></p>
</div>


    </div>

    <div class="row mb-3">
        <div class="col-12">
            <h4 class="section-title">Skills</h4>
            <div><?= generateSkillBadges($row['skills']) ?></div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <h4 class="section-title">Experience</h4>
            <?= displayExperience($row['experience']) ?>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <h4 class="section-title">Uploaded CV</h4>
            <p>
                <?php if (!empty($row['cv_file'])): ?>
                    <a href="uploads/<?= htmlspecialchars($row['cv_file']) ?>" class="btn btn-success" target="_blank">View CV</a>
                <?php else: ?>
                    <span class="text-muted">No CV Uploaded</span>
                <?php endif; ?>
            </p>
        </div>
    </div>

    <div class="text-center">
        <a href="view_entries.php" class="btn btn-secondary">Back to Applications</a>
    </div>
</div>

<?php
$stmt->close();
$conn->close();

include 'includes/footer.php';
?>
