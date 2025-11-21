<?php
// Generate QR code PNG files for GCash and PayMaya
// This script creates the actual PNG files that the buyer cart expects

// Check if GD extension is available
if (extension_loaded('gd')) {
    echo "GD extension is available. Creating PNG files...<br>";
    
    // Create a simple QR code pattern using GD
    function createQRCodePNG($filename, $size = 150) {
        $img = imagecreate($size, $size);
        
        // Define colors
        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 0, 0, 0);
        
        // Fill background with white
        imagefill($img, 0, 0, $white);
        
        // Create QR code pattern (simplified version)
        $cellSize = 5;
        
        // Top-left corner
        imagefilledrectangle($img, 0, 0, 35, 35, $black);
        imagefilledrectangle($img, 5, 5, 30, 30, $white);
        imagefilledrectangle($img, 10, 10, 25, 25, $black);
        
        // Top-right corner
        imagefilledrectangle($img, 115, 0, 150, 35, $black);
        imagefilledrectangle($img, 120, 5, 145, 30, $white);
        imagefilledrectangle($img, 125, 10, 140, 25, $black);
        
        // Bottom-left corner
        imagefilledrectangle($img, 0, 115, 35, 150, $black);
        imagefilledrectangle($img, 5, 120, 30, 145, $white);
        imagefilledrectangle($img, 10, 125, 25, 140, $black);
        
        // Add some data pattern
        for ($x = 40; $x < 110; $x += 10) {
            imagefilledrectangle($img, $x, 0, $x + 5, 5, $black);
            imagefilledrectangle($img, $x, 40, $x + 5, 45, $black);
            imagefilledrectangle($img, $x, 50, $x + 5, 55, $black);
        }
        
        // Vertical lines
        for ($y = 40; $y < 110; $y += 10) {
            imagefilledrectangle($img, 0, $y, 5, $y + 5, $black);
            imagefilledrectangle($img, 40, $y, 45, $y + 5, $black);
        }
        
        // Save as PNG
        imagepng($img, $filename);
        imagedestroy($img);
        
        return file_exists($filename);
    }
    
    // Generate the required PNG files
    $files = [
        'gcash-qr.png' => 'GCash QR Code',
        'paymaya-qr.png' => 'PayMaya QR Code',
        'gcash-qr2.png' => 'GCash QR Code 2',
        'paymaya-qr2.png' => 'PayMaya QR Code 2'
    ];
    
    foreach ($files as $filename => $description) {
        if (createQRCodePNG($filename)) {
            echo "✓ Created: $filename ($description)<br>";
        } else {
            echo "✗ Failed to create: $filename<br>";
        }
    }
    
} else {
    echo "GD extension not available. Creating fallback solution...<br>";
    
    // Create a simple SVG QR code pattern
    $qrSvg = '<svg width="150" height="150" xmlns="http://www.w3.org/2000/svg">
  <rect width="150" height="150" fill="white"/>
  <!-- Top-left corner -->
  <rect x="0" y="0" width="35" height="35" fill="black"/>
  <rect x="5" y="5" width="25" height="25" fill="white"/>
  <rect x="10" y="10" width="15" height="15" fill="black"/>
  <!-- Top-right corner -->
  <rect x="115" y="0" width="35" height="35" fill="black"/>
  <rect x="120" y="5" width="25" height="25" fill="white"/>
  <rect x="125" y="10" width="15" height="15" fill="black"/>
  <!-- Bottom-left corner -->
  <rect x="0" y="115" width="35" height="35" fill="black"/>
  <rect x="5" y="120" width="25" height="25" fill="white"/>
  <rect x="10" y="125" width="15" height="15" fill="black"/>
  <!-- Data pattern -->
  <rect x="40" y="0" width="5" height="5" fill="black"/>
  <rect x="50" y="0" width="5" height="5" fill="black"/>
  <rect x="60" y="0" width="5" height="5" fill="black"/>
  <rect x="70" y="0" width="5" height="5" fill="black"/>
  <rect x="80" y="0" width="5" height="5" fill="black"/>
  <rect x="90" y="0" width="5" height="5" fill="black"/>
  <rect x="100" y="0" width="5" height="5" fill="black"/>
  <rect x="110" y="0" width="5" height="5" fill="black"/>
  <rect x="0" y="40" width="5" height="5" fill="black"/>
  <rect x="10" y="40" width="5" height="5" fill="black"/>
  <rect x="20" y="40" width="5" height="5" fill="black"/>
  <rect x="30" y="40" width="5" height="5" fill="black"/>
  <rect x="40" y="40" width="5" height="5" fill="black"/>
  <rect x="50" y="40" width="5" height="5" fill="black"/>
  <rect x="60" y="40" width="5" height="5" fill="black"/>
  <rect x="70" y="40" width="5" height="5" fill="black"/>
  <rect x="80" y="40" width="5" height="5" fill="black"/>
  <rect x="90" y="40" width="5" height="5" fill="black"/>
  <rect x="100" y="40" width="5" height="5" fill="black"/>
  <rect x="110" y="40" width="5" height="5" fill="black"/>
  <rect x="120" y="40" width="5" height="5" fill="black"/>
  <rect x="130" y="40" width="5" height="5" fill="black"/>
  <rect x="140" y="40" width="5" height="5" fill="black"/>
  <rect x="40" y="50" width="5" height="5" fill="black"/>
  <rect x="60" y="50" width="5" height="5" fill="black"/>
  <rect x="80" y="50" width="5" height="5" fill="black"/>
  <rect x="100" y="50" width="5" height="5" fill="black"/>
  <rect x="120" y="50" width="5" height="5" fill="black"/>
  <rect x="140" y="50" width="5" height="5" fill="black"/>
</svg>';
    
    // Save SVG files (as fallback)
    $files = [
        'gcash-qr.svg' => 'GCash QR Code SVG',
        'paymaya-qr.svg' => 'PayMaya QR Code SVG',
        'gcash-qr2.svg' => 'GCash QR Code 2 SVG',
        'paymaya-qr2.svg' => 'PayMaya QR Code 2 SVG'
    ];
    
    foreach ($files as $filename => $description) {
        if (file_put_contents($filename, $qrSvg)) {
            echo "✓ Created: $filename ($description)<br>";
        } else {
            echo "✗ Failed to create: $filename<br>";
        }
    }
    
    echo "<br>Note: PNG files could not be created due to missing GD extension.<br>";
    echo "SVG files created as fallback. You may need to update the buyer cart to use SVG files.<br>";
}

echo "<br><strong>Generation complete!</strong><br>";
echo "Files should now be available in the assets/img directory.<br>";
?>
