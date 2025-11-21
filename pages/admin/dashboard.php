<?php
// Set base path for includes
$basePath = dirname(dirname(__DIR__));  // Go up two levels to reach FARMLINK directory

// Include required files
require $basePath . '/api/config.php';
require $basePath . '/includes/session.php';

// Require admin role
$user = SessionManager::requireRole('admin');

// Get dashboard statistics
$stats = DatabaseHelper::getStats('admin');
$recentActivity = DatabaseHelper::getRecentActivity(5);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>FarmLink • Admin Dashboard</title>
  <link rel="icon" type="image/png" href="/FARMLINK/assets/img/farmlink.png">
  <link rel="stylesheet" href="/FARMLINK/style.css">
  <link rel="stylesheet" href="/FARMLINK/assets/css/admin.css">
</head>
<body data-page="admin-dashboard">
  <nav>
    <div class="nav-left">
      <a href="dashboard.php"><img src="/FARMLINK/assets/img/farmlink.png" alt="FARMLINK" class="logo"></a>
      <span class="brand">FARMLINK</span>
    </div>
    <div class="nav-right">
      <?php if ($user['profile_picture']): ?>
        <img src="/FARMLINK/uploads/profiles/<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile" class="profile-pic" onerror="this.src('/FARMLINK/assets/img/default-avatar.png');">
      <?php else: ?>
        <div class="profile-pic-default">
          <?= strtoupper(substr($user['username'], 0, 1)) ?>
        </div>
      <?php endif; ?>
      <span><?= htmlspecialchars($user['username']) ?></span>
    </div>
  </nav>

  <div class="sidebar">
    <a href="dashboard.php" class="active">Dashboard</a>
    <a href="admin-users.php">Users</a>
    <a href="admin-products.php">Products</a>
    <a href="admin-orders.php">Orders</a>
    <a href="admin-profile.php">Profile</a>
    <a href="/FARMLINK/pages/auth/logout.php">Logout</a>
  </div>

  <main class="main">
    <h1>Admin Dashboard</h1>
    <p class="lead">Manage your FarmLink platform.</p>

    <section class="stats">
      <div class="card stat-card">
        <h3>Total Users</h3>
        <p><?= $stats['total_users'] ?></p>
      </div>
      <div class="card stat-card">
        <h3>Total Products</h3>
        <p><?= $stats['total_products'] ?></p>
      </div>
      <div class="card stat-card">
        <h3>Total Orders</h3>
        <p><?= $stats['total_orders'] ?></p>
      </div>
      <div class="card stat-card">
        <h3>Total Revenue</h3>
        <p>₱<?= number_format($stats['total_revenue'], 2) ?></p>
      </div>
    </section>

    <section class="card">
      <h3>Quick Actions</h3>
      <div class="quick-actions">
        <button class="btn" onclick="location.href='users.php'">Manage Users</button>
        <button class="btn" onclick="location.href='products.php'">Manage Products</button>
        <button class="btn" onclick="location.href='orders.php'">Manage Orders</button>
      </div>
    </section>

    <section class="card">
      <h3>Recent Activity</h3>
      <div class="activity-list">
        <?php if (empty($recentActivity)): ?>
          <p>No recent activity.</p>
        <?php else: ?>
          <?php foreach ($recentActivity as $activity): ?>
            <div class="activity-item">
              <strong><?= htmlspecialchars($activity['username'] ?? 'System') ?></strong>
              <span><?= htmlspecialchars($activity['message']) ?></span>
              <small><?= date('M j, Y g:i A', strtotime($activity['created_at'])) ?></small>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <style>
    nav {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .nav-left {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .nav-right {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .logo {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      border: 2px solid #4CAF50;
      object-fit: cover;
    }

    .profile-pic {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      border: 2px solid #4CAF50;
      object-fit: cover;
    }

    .profile-pic-default {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      border: 2px solid #4CAF50;
      background-color: #4CAF50;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 16px;
    }
  </style>
</body>
</html>
