<?php
// Path to the placeholder image
$placeholderPath = __DIR__ . '/placeholder.jpg';

// Only create if it doesn't exist
if (!file_exists($placeholderPath)) {
    // Create a 300x300 image
    $width = 300;
    $height = 300;
    
    // Create a blank image
    $image = imagecreatetruecolor($width, $height);
    
    // Allocate colors
    $bgColor = imagecolorallocate($image, 240, 240, 240);  // Light gray
    $borderColor = imagecolorallocate($image, 220, 220, 220); // Slightly darker gray for border
    $textColor = imagecolorallocate($image, 180, 180, 180); // Darker gray for text
    
    // Fill the background
    imagefilledrectangle($image, 0, 0, $width, $height, $bgColor);
    
    // Add border
    imagerectangle($image, 0, 0, $width-1, $height-1, $borderColor);
    
    // Add text
    $text = "No Image Available";
    $fontSize = 5; // 1-5 for built-in fonts
    $textWidth = imagefontwidth($fontSize) * strlen($text);
    $textX = ($width - $textWidth) / 2;
    $textY = ($height - imagefontheight($fontSize)) / 2;
    
    imagestring($image, $fontSize, $textX, $textY, $text, $textColor);
    
    // Save the image
    imagejpeg($image, $placeholderPath, 90);
    imagedestroy($image);
    
    // Set proper permissions
    chmod($placeholderPath, 0644);
    
    // Output success message
    if (php_sapi_name() === 'cli') {
        echo "Created placeholder image at: $placeholderPath\n";
    } else {
        header('Content-Type: text/plain');
        echo "Created placeholder image at: $placeholderPath";
    }
} else if (php_sapi_name() !== 'cli') {
    header('Content-Type: text/plain');
    echo "Placeholder image already exists at: $placeholderPath";
}
?>
