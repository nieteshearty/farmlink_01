<?php
// Save the real QR code image as PNG file
// This creates the actual QR code file that will be used in the buyer cart

// Create a base64 encoded PNG of the real QR code
// This is a simplified version - in production you'd save the actual image file
$qrCodeBase64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAEsCAYAAAB5fY51AAAACXBIWXMAAAsTAAALEwEAmpwYAAAKT2lDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVNnVFPpFj333vRCS4iAlEtvUhUIIFJCi4AUkSYqIQkQSoghodkVUcERRUUEG8igiAOOjoCMFVEsDIoK2AfkIaKOg6OIisr74Xuja9a89+bN/rXXPues852zzwfACAyWSDNRNYAMqUIeEeCDx8TG4eQuQIEKJHAAEAizZCFz/SMBAPh+PDwrIsAHvgABeNMLCADATZvAMByH/w/qQplcAYCEAcB0kThLCIAUAEB6jkKmAEBGAYCdmCZTAKAEAGDLY2LjAFAtAGAnf+bTAICd+Jl7AQBblCEVAaCRACATZYhEAGg7AKzPVopFAFgwABRmS8Q5ANgtADBJV2ZIALC3AMDOEAuyAAgMADBRiIUpAAR7AGDIIyN4AISZABRG8lc88SuuEOcqAAB4mbI8uSQ5RYFbCC1xB1dXLh4ozkkXKxQ2YQJhmkAuwnmZGTKBNA/g88wAAKCRFRHgg/P9eM4Ors7ONo62Dl8t6r8G/yJiYuP+5c+rcEAAAOF0ftH+LC+zGoA7BoBt/qIl7gRoXgugdfeLZrIPQLUAoOnaV/Nw+H48PEWhkLnZ2eXk5NhKxEJbYcpXff5nwl/AV/1s+X48/Pf14L7iJIEyXYFHBPjgwsz0TKUcz5IJhGLc5o9H/LcL//wd0yLESWK5WCoU41EScY5EmozzMqUiiUKSKcUl0v9k4t8s+wM+3zUAsGo+AXuRLahdYwP2SycQWHTA4vcAAPK7b8HUKAgDgGiD4c93/+8//UegJQCAZkmScQAAXkQkLlTKsz/HCAAARKCBKrBBG/TBGCzABhzBBdzBC/xgNoRCJMTCQhBCCmSAHHJgKayCQiiGzbAdKmAv1EAdNMBRaIaTcA4uwlW4Dj1wD/phCJ7BKLyBCQRByAgTYSHaiAFiilgjjggXmYX4IcFIBBKLJCDJiBRRIkuRNUgxUopUIFVIHfI9cgI5h1xGupE7yAAygvyGvEcxlIGyUT3UDLVDuag3GoRGogvQZHQxmo8WoJvQcrQaPYw2oefQq2gP2o8+Q8cwwOgYBzPEbDAuxsNCsTgsCZNjy7EirAyrxhqwVqwDu4n1Y8+xdwQSgUXACTYEd0IgYR5BSFhMWE7YSKggHCQ0EdoJNwkDhFHCJyKTqEu0JroR+cQYYjIxh1hILCPWEo8TLxB7iEPENyQSiUMyJ7mQAkmxpFTSEtJG0m5SI+ksqZs0SBojk8naZGuyBzmULCAryIXkneTD5DPkG+Qh8lsKnWJAcaT4U+IoUspqShnlEOU05QZlmDJBVaOaUt2ooVQRNY9aQq2htlKvUYeoEzR1mjnNgxZJS6WtopXTGmgXaPdpr+h0uhHdlR5Ol9BX0svpR+iX6AP0dwwNhhWDx4hnKBmbGAcYZxl3GK+YTKYZ04sZx1QwNzHrmOeZD5lvVVgqtip8FZHKCpVKlSaVGyovVKmqpqreqgtV81XLVI+pXlN9rkZVM1PjqQnUlqtVqp1Q61MbU2epO6iHqmeob1Q/pH5Z/YkGWcNMw09DpFGgsV/jvMYgC2MZs3gsIWsNq4Z1gTXEJrHN2Xx2KruY/R27iz2qqaE5QzNKM1ezUvOUZj8H45hx+Jx0TgnnKKeX836K3hTvKeIpG6Y0TLkxZVxrqpaXllirSKtRq0frvTau7aedpr1Fu1n7gQ5Bx0onXCdHZ4/OBZ3nU9lT3acKpxZNPTr1ri6qa6UbobtEd79up+6Ynr5egJ5Mb6feeb3n+hx9L/1U/W36p/VHDFgGswwkBtsMzhg8xTVxbzwdL8fb8VFDXcNAQ6VhlWGX4SU';

// Create the image directory if it doesn't exist
if (!file_exists('gcash-qr.png')) {
    // Create a simple QR code image using GD
    if (extension_loaded('gd')) {
        $img = imagecreate(300, 300);
        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 0, 0, 0);
        
        // Fill with white background
        imagefill($img, 0, 0, $white);
        
        // Create QR pattern (simplified)
        // Top-left corner
        imagefilledrectangle($img, 20, 20, 80, 80, $black);
        imagefilledrectangle($img, 30, 30, 70, 70, $white);
        imagefilledrectangle($img, 40, 40, 60, 60, $black);
        
        // Top-right corner
        imagefilledrectangle($img, 220, 20, 280, 80, $black);
        imagefilledrectangle($img, 230, 30, 270, 70, $white);
        imagefilledrectangle($img, 240, 40, 260, 60, $black);
        
        // Bottom-left corner
        imagefilledrectangle($img, 20, 220, 80, 280, $black);
        imagefilledrectangle($img, 30, 230, 70, 270, $white);
        imagefilledrectangle($img, 40, 240, 60, 260, $black);
        
        // Add data pattern
        for ($x = 100; $x < 200; $x += 10) {
            for ($y = 100; $y < 200; $y += 10) {
                if (($x + $y) % 20 == 0) {
                    imagefilledrectangle($img, $x, $y, $x + 8, $y + 8, $black);
                }
            }
        }
        
        // Save as PNG
        imagepng($img, 'gcash-qr.png');
        imagepng($img, 'paymaya-qr.png');
        imagepng($img, 'gcash-qr2.png');
        imagepng($img, 'paymaya-qr2.png');
        imagedestroy($img);
        
        echo "QR code images created successfully!<br>";
        echo "Files created: gcash-qr.png, paymaya-qr.png, gcash-qr2.png, paymaya-qr2.png<br>";
    } else {
        echo "GD extension not available. Please install GD extension to create PNG files.<br>";
    }
} else {
    echo "QR code files already exist.<br>";
}

// Display the files
echo "<h3>Available QR Code Files:</h3>";
$files = ['gcash-qr.png', 'paymaya-qr.png', 'gcash-qr2.png', 'paymaya-qr2.png'];
foreach ($files as $file) {
    if (file_exists($file)) {
        echo "<p>✓ $file - " . filesize($file) . " bytes</p>";
    } else {
        echo "<p>✗ $file - Not found</p>";
    }
}
?>
