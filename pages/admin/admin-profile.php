<?php
// Set base path for includes
$basePath = dirname(dirname(__DIR__));  // Go up two levels to reach FARMLINK directory

// Include required files
require $basePath . '/api/config.php';
require $basePath . '/includes/session.php';

// Require admin role
$user = SessionManager::requireRole('admin');

// Handle profile updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    
    // Handle profile picture upload
    $profilePicture = $user['profile_picture']; // Keep existing if no new upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../uploads/profiles/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileExtension = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($fileExtension, $allowedExtensions)) {
            $fileName = uniqid() . '.' . $fileExtension;
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadPath)) {
                // Delete old profile picture if exists
                if ($user['profile_picture'] && file_exists('../../' . $user['profile_picture'])) {
                    unlink('../../' . $user['profile_picture']);
                }
                $profilePicture = $uploadPath;
            }
        }
    }
    
    try {
        $pdo = getDBConnection();
        
        // Check if username/email already exists (excluding current user)
        $stmt = $pdo->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
        $stmt->execute([$username, $email, $user['id']]);
        
        if ($stmt->fetch()) {
            $_SESSION['error'] = "Username or email already exists.";
        } else {
            // Update profile
            if (!empty($newPassword)) {
                if (empty($currentPassword) || !password_verify($currentPassword, $user['password'])) {
                    $_SESSION['error'] = "Current password is incorrect.";
                } else {
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, profile_picture = ?, password = ? WHERE id = ?");
                    $stmt->execute([$username, $email, $profilePicture, $hashedPassword, $user['id']]);
                }
            } else {
                $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, profile_picture = ? WHERE id = ?");
                $stmt->execute([$username, $email, $profilePicture, $user['id']]);
            }
            
            if (!isset($_SESSION['error'])) {
                // Update session data
                $_SESSION['user']['username'] = $username;
                $_SESSION['user']['email'] = $email;
                $_SESSION['user']['profile_picture'] = $profilePicture;
                
                $_SESSION['success'] = "Profile updated successfully!";
                SessionManager::logActivity($user['id'], 'profile', "Updated profile information");
            }
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Failed to update profile.";
    }
    
    header('Location: admin-profile.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>FarmLink • Admin Profile</title>
  <link rel="icon" type="image/png" href="/FARMLINK/assets/img/farmlink.png">
  <link rel="stylesheet" href="/FARMLINK/style.css">
  <link rel="stylesheet" href="/FARMLINK/assets/css/admin.css">
</head>
<body data-page="admin-profile">
  <nav>
    <div class="nav-left">
      <a href="dashboard.php"><img src="/FARMLINK/assets/img/farmlink.png" alt="FARMLINK" class="logo"></a>
      <span class="brand">FARMLINK</span>
    </div>
    <span>Profile</span>
  </nav>

  <div class="sidebar">
    <a href="admin-dashboard.php">Dashboard</a>
    <a href="admin-users.php">Manage Users</a>
    <a href="admin-products.php">Manage Products</a>
    <a href="admin-orders.php">View Orders</a>
    <a href="admin-profile.php" class="active">Profile</a>
    <a href="/FARMLINK/pages/auth/logout.php">Logout</a>
  </div>

  <main class="main">
    <h1>Admin Profile Settings</h1>
    <p class="lead">Update your administrator account information.</p>

    <?php if (isset($_SESSION['success'])): ?>
      <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <section class="form-section">
      <form method="POST" enctype="multipart/form-data">
        <h3>Profile Picture</h3>
        <div class="profile-upload">
          <div class="current-profile">
            <?php if ($user['profile_picture']): ?>
              <img src="/FARMLINK/uploads/profiles/<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile Picture" onerror="this.src='/FARMLINK/assets/img/default-avatar.png';" class="current-pic">
            <?php else: ?>
              <div class="profile-pic-default current-pic">
                <?= strtoupper(substr($user['username'], 0, 1)) ?>
              </div>
            <?php endif; ?>
            <label>Current Profile Picture</label>
          </div>
          <input type="file" name="profile_picture" id="profilePic" accept="image/*" />
          <div class="profile-preview" id="profilePreview" style="display:none;">
            <img id="previewImg" src="" alt="Profile Preview" />
            <label>New Profile Picture</label>
          </div>
        </div>
        
        <h3>Basic Information</h3>
        <input name="username" placeholder="Username" value="<?= htmlspecialchars($user['username']) ?>" required />
        <input name="email" type="email" placeholder="Email" value="<?= htmlspecialchars($user['email']) ?>" required />
        
        <h3>Change Password (Optional)</h3>
        <input name="current_password" type="password" placeholder="Current Password" />
        <input name="new_password" type="password" placeholder="New Password" />
        
        <div style="text-align:right; margin-top: 16px;">
          <button type="submit" class="btn">Update Profile</button>
        </div>
      </form>
    </section>
  </main>

  <style>
    .alert {
      padding: 12px;
      margin: 16px 0;
      border-radius: 4px;
    }
    
    .alert-success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    
    .alert-error {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
  </style>
</div>

<script>
// Profile picture preview
document.getElementById('profilePic').addEventListener('change', function(e) {
  const file = e.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
      document.getElementById('previewImg').src = e.target.result;
      document.getElementById('profilePreview').style.display = 'block';
    };
    reader.readAsDataURL(file);
  }
});
</script>

<style>
.profile-upload {
  display: flex;
  gap: 20px;
  align-items: center;
  margin: 20px 0;
  flex-wrap: wrap;
}

.current-profile, .profile-preview {
  text-align: center;
}

.current-pic, .profile-preview img {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  border: 3px solid #4CAF50;
  object-fit: cover;
  margin-bottom: 8px;
}

.profile-pic-default {
  background-color: #4CAF50;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 24px;
}

.profile-upload label {
  font-size: 12px;
  color: #666;
  display: block;
}

.profile-upload input[type="file"] {
  margin: 10px 0;
}
</style>
</body>
</html>
