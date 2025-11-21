<?php
require_once __DIR__ . '/api/config.php';

$pdo = getDBConnection();

// Get all products with images
$stmt = $pdo->query("SELECT id, name, image, farmer_id FROM products WHERE image IS NOT NULL AND image != ''");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Product Image Debug</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .product-img { width: 100px; height: 100px; object-fit: cover; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <h1>Product Image Debug</h1>
    
    <h2>Products with Images</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Product Name</th>
            <th>Image Path (DB)</th>
            <th>Full File Path</th>
            <th>File Exists?</th>
            <th>Web URL</th>
            <th>Preview</th>
        </tr>
        <?php foreach ($products as $product): 
            $imagePath = $product['image'];
            
            // Check different possible file paths
            $possiblePaths = [
                $_SERVER['DOCUMENT_ROOT'] . '/FARMLINK/uploads/products/' . $imagePath,
                $_SERVER['DOCUMENT_ROOT'] . '/FARMLINK/uploads/products/' . basename($imagePath),
                $_SERVER['DOCUMENT_ROOT'] . $imagePath
            ];
            
            $fileExists = false;
            $actualPath = '';
            foreach ($possiblePaths as $path) {
                if (file_exists($path)) {
                    $fileExists = true;
                    $actualPath = $path;
                    break;
                }
            }
            
            $webUrl = '/FARMLINK/uploads/products/' . basename($imagePath);
        ?>
            <tr>
                <td><?= $product['id'] ?></td>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td><code><?= htmlspecialchars($imagePath) ?></code></td>
                <td><code><?= htmlspecialchars($actualPath ?: 'Not found') ?></code></td>
                <td class="<?= $fileExists ? 'success' : 'error' ?>">
                    <?= $fileExists ? '✅ Yes' : '❌ No' ?>
                </td>
                <td><code><?= htmlspecialchars($webUrl) ?></code></td>
                <td>
                    <?php if ($fileExists): ?>
                        <img src="<?= htmlspecialchars($webUrl) ?>" 
                             class="product-img" 
                             alt="<?= htmlspecialchars($product['name']) ?>"
                             onerror="this.style.display='none'; this.nextSibling.style.display='inline';">
                        <span style="display:none;" class="error">Failed to load</span>
                    <?php else: ?>
                        <span class="error">File not found</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    
    <?php if (empty($products)): ?>
        <p>No products with images found.</p>
    <?php endif; ?>
    
    <h2>Upload Directory Info</h2>
    <?php
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/FARMLINK/uploads/products/';
    echo "<p>Upload directory: <code>" . htmlspecialchars($uploadDir) . "</code></p>";
    echo "<p>Directory exists: " . (is_dir($uploadDir) ? '✅ Yes' : '❌ No') . "</p>";
    
    if (is_dir($uploadDir)) {
        $files = scandir($uploadDir);
        $imageFiles = array_filter($files, function($file) {
            return in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']);
        });
        
        echo "<p>Image files in directory: " . count($imageFiles) . "</p>";
        if (!empty($imageFiles)) {
            echo "<h3>Files in directory:</h3><ul>";
            foreach ($imageFiles as $file) {
                echo "<li><code>$file</code></li>";
            }
            echo "</ul>";
        }
    }
    ?>
    
    <h2>Current Image Path Logic</h2>
    <p>Current code uses: <code>/FARMLINK/uploads/products/<?= htmlspecialchars($product['image']) ?></code></p>
    <p>This assumes the database stores just the filename, not the full path.</p>
</body>
</html>