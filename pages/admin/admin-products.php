<?php
// Set base path for includes
$basePath = dirname(dirname(__DIR__));  // Go up two levels to reach FARMLINK directory

// Include required files
require $basePath . '/api/config.php';
require $basePath . '/includes/session.php';

// Require admin role
$user = SessionManager::requireRole('admin');

// Handle product operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    try {
        $pdo = getDBConnection();
        
        if ($action === 'delete_product') {
            $productId = $_POST['product_id'];
            
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$productId]);
            
            if ($stmt->rowCount() > 0) {
                $_SESSION['success'] = "Product deleted successfully!";
                SessionManager::logActivity($user['id'], 'admin', "Deleted product ID: {$productId}");
            } else {
                $_SESSION['error'] = "Product not found.";
            }
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "An error occurred. Please try again.";
    }
    
    header('Location: admin-products.php');
    exit;
}

// Get all products
$products = DatabaseHelper::getProducts();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>FarmLink • Manage Products</title>
  <link rel="icon" type="image/png" href="/FARMLINK/assets/img/farmlink.png">
  <link rel="stylesheet" href="/FARMLINK/style.css">
  <link rel="stylesheet" href="/FARMLINK/assets/css/admin.css">
</head>
<body data-page="admin-products">
  <nav>
    <div class="nav-left">
      <a href="dashboard.php"><img src="/FARMLINK/assets/img/farmlink.png" alt="FARMLINK" class="logo"></a>
      <span class="brand">FARMLINK</span>
    </div>
    <span>Product Management</span>
  </nav>

  <div class="sidebar">
    <a href="admin-dashboard.php">Dashboard</a>
    <a href="admin-users.php">Manage Users</a>
    <a href="admin-products.php" class="active">Manage Products</a>
    <a href="admin-orders.php">View Orders</a>
    <a href="/FARMLINK/pages/auth/logout.php">Logout</a>
  </div>

  <main class="main">
    <h1>Product Management</h1>
    <p class="lead">View and manage all products in the system.</p>

    <?php if (isset($_SESSION['success'])): ?>
      <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <section class="table-wrap">
      <h3>All Products</h3>
      <table>
        <thead>
          <tr>
            <th>Product</th>
            <th>Farmer</th>
            <th>Category</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Created</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($products)): ?>
            <tr>
              <td colspan="7" style="text-align:center; padding:40px; color:#999;">
                No products found.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($products as $product): ?>
              <tr>
                <td>
                  <strong><?= htmlspecialchars($product['name']) ?></strong>
                  <?php if ($product['description']): ?>
                    <br><small><?= htmlspecialchars(substr($product['description'], 0, 50)) ?>...</small>
                  <?php endif; ?>
                  <img src="/FARMLINK/uploads/products/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                </td>
                <td>
                  <?= htmlspecialchars($product['farmer_name']) ?>
                  <?php if ($product['farm_name']): ?>
                    <br><small><?= htmlspecialchars($product['farm_name']) ?></small>
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($product['category']) ?></td>
                <td><?= $product['quantity'] ?> <?= htmlspecialchars($product['unit']) ?></td>
                <td>₱<?= number_format($product['price'], 2) ?></td>
                <td><?= date('M j, Y', strtotime($product['created_at'])) ?></td>
                <td>
                  <form method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="delete_product">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this product?')">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </section>
  </main>

  <style>
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
