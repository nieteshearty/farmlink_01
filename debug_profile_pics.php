<?php
require_once __DIR__ . '/api/config.php';

$pdo = getDBConnection();

// Get all users with profile pictures
$stmt = $pdo->query("SELECT id, username, email, profile_picture FROM users WHERE profile_picture IS NOT NULL");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Profile Picture Debug</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .profile-pic { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <h1>Profile Picture Debug</h1>
    
    <h2>Users with Profile Pictures</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Profile Picture Path</th>
            <th>File Exists?</th>
            <th>Preview</th>
        </tr>
        <?php foreach ($users as $user): 
            $fullPath = $_SERVER['DOCUMENT_ROOT'] . $user['profile_picture'];
            $fileExists = file_exists($fullPath);
        ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><code><?= htmlspecialchars($user['profile_picture']) ?></code></td>
                <td class="<?= $fileExists ? 'success' : 'error' ?>">
                    <?= $fileExists ? '✅ Yes' : '❌ No' ?>
                </td>
                <td>
                    <?php if ($fileExists): ?>
                        <img src="<?= htmlspecialchars($user['profile_picture']) ?>" 
                             class="profile-pic" 
                             alt="Profile"
                             onerror="this.style.display='none'; this.nextSibling.style.display='inline';">
                        <span style="display:none;" class="error">Failed to load</span>
                    <?php else: ?>
                        <span class="error">File not found</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    
    <?php if (empty($users)): ?>
        <p>No users with profile pictures found.</p>
    <?php endif; ?>
    
    <h2>Upload Directory Info</h2>
    <?php
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/FARMLINK/uploads/profiles/';
    echo "<p>Upload directory: <code>" . htmlspecialchars($uploadDir) . "</code></p>";
    echo "<p>Directory exists: " . (is_dir($uploadDir) ? '✅ Yes' : '❌ No') . "</p>";
    
    if (is_dir($uploadDir)) {
        $files = scandir($uploadDir);
        $imageFiles = array_filter($files, function($file) {
            return in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']);
        });
        
        echo "<p>Image files in directory: " . count($imageFiles) . "</p>";
        if (!empty($imageFiles)) {
            echo "<ul>";
            foreach ($imageFiles as $file) {
                echo "<li><code>$file</code></li>";
            }
            echo "</ul>";
        }
    }
    ?>
</body>
</html>
