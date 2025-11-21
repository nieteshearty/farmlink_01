<?php
// Set base path for includes
$basePath = dirname(dirname(__DIR__));  // Go up two levels to reach FARMLINK directory

// Include required files
require $basePath . '/api/config.php';
require $basePath . '/includes/session.php';

// Require admin role
$user = SessionManager::requireRole('admin');

// Get all orders
$orders = DatabaseHelper::getOrders();
$stats = DatabaseHelper::getStats('admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>FarmLink • View Orders</title>
  <link rel="icon" type="image/png" href="/FARMLINK/assets/img/farmlink.png">
  <link rel="stylesheet" href="/FARMLINK/style.css">
  <link rel="stylesheet" href="/FARMLINK/assets/css/admin.css">
</head>
<body data-page="admin-orders">
  <nav>
    <a href="dashboard.php"><img src="/FARMLINK/assets/img/farmlink.png" alt="FARMLINK" class="logo"></a>
    <span>Order Management</span>
  </nav>

  <div class="sidebar">
    <a href="dashboard.php"><img src="/FARMLINK/assets/img/farmlink.png" alt="FARMLINK" class="logo"></a>
    <a href="admin-products.php">Manage Products</a>
    <a href="admin-orders.php" class="active">View Orders</a>
    <a href="/FARMLINK/pages/auth/logout.php">Logout</a>
  </div>

  <main class="main">
    <h1>Order Management</h1>
    <p class="lead">View and monitor all orders in the system.</p>

    <section class="stats">
      <div class="card stat-card">
        <h3>Total Orders</h3>
        <p><?= $stats['total_orders'] ?></p>
      </div>
      <div class="card stat-card">
        <h3>Pending Orders</h3>
        <p><?= count(array_filter($orders, function($o) { return $o['status'] === 'pending'; })) ?></p>
      </div>
      <div class="card stat-card">
        <h3>Completed Orders</h3>
        <p><?= count(array_filter($orders, function($o) { return $o['status'] === 'completed'; })) ?></p>
      </div>
      <div class="card stat-card">
        <h3>Total Revenue</h3>
        <p>₱<?= number_format($stats['total_revenue'], 2) ?></p>
      </div>
    </section>

    <section class="table-wrap">
      <h3>All Orders</h3>
      <table>
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Buyer</th>
            <th>Farmer</th>
            <th>Total</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($orders)): ?>
            <tr>
              <td colspan="7" style="text-align:center; padding:40px; color:#999;">
                No orders found.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($orders as $order): ?>
              <tr>
                <td>#<?= $order['id'] ?></td>
                <td>
                  <?= htmlspecialchars($order['buyer_name']) ?>
                  <?php if ($order['buyer_company']): ?>
                    <br><small><?= htmlspecialchars($order['buyer_company']) ?></small>
                  <?php endif; ?>
                </td>
                <td>
                  <?= htmlspecialchars($order['farmer_name']) ?>
                  <?php if ($order['farm_name']): ?>
                    <br><small><?= htmlspecialchars($order['farm_name']) ?></small>
                  <?php endif; ?>
                </td>
                <td>₱<?= number_format($order['total'], 2) ?></td>
                <td><span class="status-<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span></td>
                <td><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
                <td>
                  <button class="btn" onclick="viewOrder(<?= $order['id'] ?>)">View Details</button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </section>
  </main>

  <script>
    function viewOrder(orderId) {
      window.open('order-details.php?id=' + orderId, '_blank', 'width=600,height=400');
    }
  </script>

  <style>
    .status-pending { color: #e67e22; font-weight: bold; }
    .status-completed { color: #27ae60; font-weight: bold; }
    .status-cancelled { color: #e74c3c; font-weight: bold; }
  </style>
</body>
</html>
