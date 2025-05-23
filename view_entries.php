<?php 
// Database Connection
$conn = new mysqli('62.72.28.154', 'u144227799_career_user', 'Career1234@', 'u144227799_career_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all career applications
$sql = "SELECT * FROM career_applications";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Area - Applications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">All Applications</h2>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Profile</th>
            <th>Name</th>
            <th>District</th>
            <th>Skills</th>
            <th>Experience</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td>
                    <img src="<?= htmlspecialchars($row['profile_image']) ?: 'default-avatar.png' ?>" 
                         alt="Profile Image" width="50" height="50" class="rounded-circle">
                </td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['district']) ?></td>

                <!-- Skills -->
                <td>
                    <?php
                    $skills = explode(',', $row['skills']);
                    foreach ($skills as $skill):
                        $color = '#' . substr(md5($skill), 0, 6);
                        ?>
                        <span class="badge" style="background-color: <?= $color ?>;"> <?= htmlspecialchars($skill) ?> </span>
                    <?php endforeach; ?>
                </td>

                <!-- Experience -->
                <td>
                    <?php
                    $experience = json_decode($row['experience'], true);
                    if (!empty($experience)) {
                        for ($i = 0; $i < count($experience['fields']); $i++):
                            echo ($i + 1) . ". " . htmlspecialchars($experience['fields'][$i]) . " in " .
                                htmlspecialchars($experience['companies'][$i]) . " for " .
                                htmlspecialchars($experience['durations'][$i]) . "<br>";
                        endfor;
                    } else {
                        echo "No experience listed";
                    }
                    ?>
                </td>

                <!-- Action Button -->
                <td>
                    <a href="view_details.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">View Details</a>
                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailsModal<?= $row['id'] ?>">More Info</button>
                </td>
            </tr>

            <!-- Popup Modal -->
            <div class="modal fade" id="detailsModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="detailsLabel<?= $row['id'] ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsLabel<?= $row['id'] ?>">Details of <?= htmlspecialchars($row['name']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
                <p><strong>Mobile:</strong> 
                    <?= htmlspecialchars($row['mobile']) ?> 
                    <a href="https://wa.me/<?= htmlspecialchars($row['mobile']) ?>" target="_blank" style="margin-left: 10px;">
                        <img src="https://static.vecteezy.com/system/resources/previews/016/716/480/original/whatsapp-icon-free-png.png" alt="Chat on WhatsApp" width="35">
                    </a>
                </p>
                <p><strong>LinkedIn Profile:</strong>
                    <?php if (!empty($row['linkedin'])): ?>
                        <a href="<?= htmlspecialchars($row['linkedin']) ?>" target="_blank">View Profile</a>
                    <?php else: ?>
                        Not Provided
                    <?php endif; ?>
                </p>
                <p><strong>Submitted At:</strong> <?= $row['created_at'] ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
<?php
$conn->close();
?>
