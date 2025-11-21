<?php
// Set base path for includes
$basePath = dirname(dirname(__DIR__));  // Go up two levels to reach FARMLINK directory

// Include required files
require $basePath . '/api/config.php';
require $basePath . '/includes/session.php';

// Require admin role
$user = SessionManager::requireRole('admin');

// Handle user operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    try {
        $pdo = getDBConnection();
        
        if ($action === 'create_user') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $role = $_POST['role'];
            $farmName = trim($_POST['farm_name'] ?? '');
            $location = trim($_POST['location'] ?? '');
            $company = trim($_POST['company'] ?? '');
            
            if (empty($username) || empty($email) || empty($password)) {
                $_SESSION['error'] = "Please fill all required fields.";
            } else {
                // Check if username or email already exists
                $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
                $stmt->execute([$username, $email]);
                
                if ($stmt->fetch()) {
                    $_SESSION['error'] = "Username or email already exists.";
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    
                    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role, farm_name, location, company) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$username, $email, $hashedPassword, $role, $farmName, $location, $company]);
                    
                    $_SESSION['success'] = "User created successfully!";
                    SessionManager::logActivity($user['id'], 'admin', "Created new {$role}: {$username}");
                }
            }
            
        } elseif ($action === 'delete_user') {
            $userId = $_POST['user_id'];
            
            if ($userId == $user['id']) {
                $_SESSION['error'] = "Cannot delete your own account.";
            } else {
                $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$userId]);
                
                if ($stmt->rowCount() > 0) {
                    $_SESSION['success'] = "User deleted successfully!";
                    SessionManager::logActivity($user['id'], 'admin', "Deleted user ID: {$userId}");
                } else {
                    $_SESSION['error'] = "User not found.";
                }
            }
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "An error occurred. Please try again.";
    }
    
    header('Location: admin-users.php');
    exit;
}

// Get all users
$users = DatabaseHelper::getUsers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>FarmLink • Manage Users</title>
  <link rel="icon" type="image/png" href="/FARMLINK/assets/img/farmlink.png">
  <link rel="stylesheet" href="/FARMLINK/style.css">
  <link rel="stylesheet" href="/FARMLINK/assets/css/admin.css">
</head>
<body data-page="admin-users">
  <nav>
    <div class="nav-left">
      <a href="dashboard.php"><div class="profile-pic-default"><?= strtoupper(substr($user['username'], 0, 1)) ?></div></a>
      <span class="brand">FARMLINK</span>
    </div>
    <span>User Management</span>
  </nav>

  <div class="sidebar">
    <a href="admin-dashboard.php">Dashboard</a>
    <a href="admin-users.php" class="active">Manage Users</a>
    <a href="admin-products.php">Manage Products</a>
    <a href="admin-orders.php">View Orders</a>
    <a href="/FARMLINK/pages/auth/logout.php">Logout</a>
  </div>

  <main class="main">
    <h1>User Management</h1>
    <p class="lead">Create and manage user accounts.</p>

    <?php if (isset($_SESSION['success'])): ?>
      <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <section class="form-section">
      <h3>Create New User</h3>
      <form method="POST">
        <input type="hidden" name="action" value="create_user">
        
        <input name="username" placeholder="Username" required />
        <input name="email" type="email" placeholder="Email" required />
        <input name="password" type="password" placeholder="Password" required />
        
        <select name="role" id="roleSelect" required>
          <option value="farmer">Farmer</option>
          <option value="buyer">Buyer</option>
          <option value="admin">Admin</option>
        </select>
        
        <div id="farmerFields" style="display:none;">
          <input name="farm_name" placeholder="Farm Name" />
          <input name="location" placeholder="Location" />
        </div>
        
        <div id="buyerFields" style="display:none;">
          <input name="company" placeholder="Company" />
          <input name="location" placeholder="Location" />
        </div>
        
        <div style="text-align:right; margin-top: 16px;">
          <button type="submit" class="btn">Create User</button>
        </div>
      </form>
    </section>

    <section class="table-wrap">
      <h3>All Users</h3>
      <table>
        <thead>
          <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Additional Info</th>
            <th>Created</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($users)): ?>
            <tr>
              <td colspan="6" style="text-align:center; padding:40px; color:#999;">
                No users found.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($users as $userItem): ?>
              <tr>
                <td><?= htmlspecialchars($userItem['username']) ?></td>
                <td><?= htmlspecialchars($userItem['email']) ?></td>
                <td><span class="role-<?= $userItem['role'] ?>"><?= ucfirst($userItem['role']) ?></span></td>
                <td>
                  <?php if ($userItem['role'] === 'farmer'): ?>
                    <?= $userItem['farm_name'] ? htmlspecialchars($userItem['farm_name']) : '-' ?>
                    <?php if ($userItem['location']): ?>
                      <br><small><?= htmlspecialchars($userItem['location']) ?></small>
                    <?php endif; ?>
                  <?php elseif ($userItem['role'] === 'buyer'): ?>
                    <?= $userItem['company'] ? htmlspecialchars($userItem['company']) : '-' ?>
                    <?php if ($userItem['location']): ?>
                      <br><small><?= htmlspecialchars($userItem['location']) ?></small>
                    <?php endif; ?>
                  <?php else: ?>
                    -
                  <?php endif; ?>
                </td>
                <td><?= date('M j, Y', strtotime($userItem['created_at'])) ?></td>
                <td>
                  <?php if ($userItem['id'] != $user['id']): ?>
                    <form method="POST" style="display: inline;">
                      <input type="hidden" name="action" value="delete_user">
                      <input type="hidden" name="user_id" value="<?= $userItem['id'] ?>">
                      <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this user?')">Delete</button>
                    </form>
                  <?php else: ?>
                    <span style="color: #999;">Current User</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </section>
  </main>

  <script>
    // Show/hide additional fields based on role
    document.getElementById('roleSelect').addEventListener('change', function() {
      const role = this.value;
      document.getElementById('farmerFields').style.display = role === 'farmer' ? 'block' : 'none';
      document.getElementById('buyerFields').style.display = role === 'buyer' ? 'block' : 'none';
    });
  </script>

  <style>
    .form-section {
      margin-bottom: 30px;
    }
    
    .form-section input, .form-section select {
      width: 100%;
      margin: 8px 0;
      padding: 12px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }
    
    .role-farmer { color: #27ae60; font-weight: bold; }
    .role-buyer { color: #3498db; font-weight: bold; }
    .role-admin { color: #e74c3c; font-weight: bold; }
    
    .btn-danger {
      background-color: #e74c3c;
      color: white;
    }
    
    .btn-danger:hover {
      background-color: #c0392b;
    }
    
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
</body>
</html>
