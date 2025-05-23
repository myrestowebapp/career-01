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

// Check if settings table exists, if not create it
$check_table = $conn->query("SHOW TABLES LIKE 'system_settings'");
if ($check_table->num_rows == 0) {
    $create_table = "CREATE TABLE system_settings (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        setting_name VARCHAR(255) NOT NULL,
        setting_value TEXT,
        setting_group VARCHAR(100) DEFAULT 'general',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($create_table) === TRUE) {
        // Insert default settings
        $default_settings = [
            ["email_notifications", "1", "notifications"],
            ["admin_email", "admin@example.com", "notifications"],
            ["application_open", "1", "application"],
            ["max_file_size", "5", "application"],
            ["allowed_file_types", "pdf,doc,docx", "application"],
            ["welcome_message", "Thank you for applying! We will review your application soon.", "messages"]
        ];
        
        $stmt = $conn->prepare("INSERT INTO system_settings (setting_name, setting_value, setting_group) VALUES (?, ?, ?)");
        foreach ($default_settings as $setting) {
            $stmt->bind_param("sss", $setting[0], $setting[1], $setting[2]);
            $stmt->execute();
        }
    }
}

// Handle form submission
$success_message = "";
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update settings
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'setting_') === 0) {
            $setting_name = substr($key, 8); // Remove 'setting_' prefix
            $setting_value = $conn->real_escape_string($value);
            
            $update_sql = "UPDATE system_settings SET setting_value = ? WHERE setting_name = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("ss", $setting_value, $setting_name);
            
            if ($stmt->execute()) {
                $success_message = "Settings updated successfully!";
            } else {
                $error_message = "Error updating settings: " . $conn->error;
                break;
            }
        }
    }
}

// Fetch all settings grouped by category
$settings = [];
$settings_query = "SELECT * FROM system_settings ORDER BY setting_group, setting_name";
$settings_result = $conn->query($settings_query);

while ($row = $settings_result->fetch_assoc()) {
    $settings[$row['setting_group']][] = $row;
}

// Include header
include 'includes/header.php';
?>

<!-- Main Content -->
<div class="content-wrapper">
    <!-- Page Title -->
    <div class="page-header">
        <h1><i class="fas fa-cog"></i> System Settings</h1>
        <p>Configure application settings and preferences</p>
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
    
    <form method="POST" action="">
        <!-- Settings Tabs -->
        <div class="content-card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="settings-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab">General</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="notifications-tab" data-toggle="tab" href="#notifications" role="tab">Notifications</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="application-tab" data-toggle="tab" href="#application" role="tab">Application Form</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="messages-tab" data-toggle="tab" href="#messages" role="tab">Messages</a>
                    </li>
                </ul>
            </div>
            
            <div class="card-body">
                <div class="tab-content" id="settings-content">
                    <!-- General Settings -->
                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                        <h3 class="section-title">General Settings</h3>
                        <div class="form-group">
                            <label for="site_title">Site Title</label>
                            <input type="text" class="form-control" id="site_title" name="setting_site_title" 
                                value="<?= isset($settings['general'][0]['setting_value']) ? htmlspecialchars($settings['general'][0]['setting_value']) : 'Career Portal' ?>">
                        </div>
                        <div class="form-group">
                            <label for="site_description">Site Description</label>
                            <textarea class="form-control" id="site_description" name="setting_site_description" rows="3"><?= isset($settings['general'][1]['setting_value']) ? htmlspecialchars($settings['general'][1]['setting_value']) : 'Career opportunities and job applications' ?></textarea>
                        </div>
                    </div>
                    
                    <!-- Notification Settings -->
                    <div class="tab-pane fade" id="notifications" role="tabpanel">
                        <h3 class="section-title">Notification Settings</h3>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="email_notifications" name="setting_email_notifications" value="1" 
                                    <?= isset($settings['notifications'][0]['setting_value']) && $settings['notifications'][0]['setting_value'] == '1' ? 'checked' : '' ?>>
                                <label class="custom-control-label" for="email_notifications">Enable Email Notifications</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="admin_email">Admin Email Address</label>
                            <input type="email" class="form-control" id="admin_email" name="setting_admin_email" 
                                value="<?= isset($settings['notifications'][1]['setting_value']) ? htmlspecialchars($settings['notifications'][1]['setting_value']) : '' ?>">
                            <small class="form-text text-muted">Email address for receiving notifications</small>
                        </div>
                    </div>
                    
                    <!-- Application Form Settings -->
                    <div class="tab-pane fade" id="application" role="tabpanel">
                        <h3 class="section-title">Application Form Settings</h3>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="application_open" name="setting_application_open" value="1" 
                                    <?= isset($settings['application'][0]['setting_value']) && $settings['application'][0]['setting_value'] == '1' ? 'checked' : '' ?>>
                                <label class="custom-control-label" for="application_open">Applications Open</label>
                            </div>
                            <small class="form-text text-muted">When disabled, users cannot submit new applications</small>
                        </div>
                        <div class="form-group">
                            <label for="max_file_size">Maximum File Size (MB)</label>
                            <input type="number" class="form-control" id="max_file_size" name="setting_max_file_size" min="1" max="20" 
                                value="<?= isset($settings['application'][1]['setting_value']) ? htmlspecialchars($settings['application'][1]['setting_value']) : '5' ?>">
                        </div>
                        <div class="form-group">
                            <label for="allowed_file_types">Allowed File Types</label>
                            <input type="text" class="form-control" id="allowed_file_types" name="setting_allowed_file_types" 
                                value="<?= isset($settings['application'][2]['setting_value']) ? htmlspecialchars($settings['application'][2]['setting_value']) : 'pdf,doc,docx' ?>">
                            <small class="form-text text-muted">Comma-separated list of allowed file extensions</small>
                        </div>
                    </div>
                    
                    <!-- Message Settings -->
                    <div class="tab-pane fade" id="messages" role="tabpanel">
                        <h3 class="section-title">Message Templates</h3>
                        <div class="form-group">
                            <label for="welcome_message">Welcome Message</label>
                            <textarea class="form-control" id="welcome_message" name="setting_welcome_message" rows="3"><?= isset($settings['messages'][0]['setting_value']) ? htmlspecialchars($settings['messages'][0]['setting_value']) : 'Thank you for applying! We will review your application soon.' ?></textarea>
                            <small class="form-text text-muted">Message shown to users after submitting an application</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-actions mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Settings
            </button>
            <button type="reset" class="btn btn-secondary">
                <i class="fas fa-undo"></i> Reset Changes
            </button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>

<script>
$(document).ready(function() {
    // Initialize Bootstrap tabs
    $('#settings-tabs a').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
    
    // Form validation
    $('form').on('submit', function(e) {
        // Add any custom validation here if needed
    });
});
</script>