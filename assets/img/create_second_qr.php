<?php
// Create the second QR code based on the provided image
$qrCode2 = '
<svg width="150" height="150" xmlns="http://www.w3.org/2000/svg">
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
  
  <!-- Enhanced data pattern for second QR -->
  <rect x="40" y="5" width="5" height="5" fill="black"/>
  <rect x="50" y="5" width="5" height="5" fill="black"/>
  <rect x="60" y="5" width="5" height="5" fill="black"/>
  <rect x="70" y="5" width="5" height="5" fill="black"/>
  <rect x="80" y="5" width="5" height="5" fill="black"/>
  <rect x="90" y="5" width="5" height="5" fill="black"/>
  <rect x="100" y="5" width="5" height="5" fill="black"/>
  <rect x="110" y="5" width="5" height="5" fill="black"/>
  
  <!-- Vertical pattern -->
  <rect x="40" y="15" width="5" height="5" fill="black"/>
  <rect x="40" y="25" width="5" height="5" fill="black"/>
  <rect x="40" y="35" width="5" height="5" fill="black"/>
  <rect x="40" y="45" width="5" height="5" fill="black"/>
  <rect x="40" y="55" width="5" height="5" fill="black"/>
  <rect x="40" y="65" width="5" height="5" fill="black"/>
  <rect x="40" y="75" width="5" height="5" fill="black"/>
  <rect x="40" y="85" width="5" height="5" fill="black"/>
  <rect x="40" y="95" width="5" height="5" fill="black"/>
  <rect x="40" y="105" width="5" height="5" fill="black"/>
  
  <!-- Additional pattern elements -->
  <rect x="50" y="50" width="5" height="5" fill="black"/>
  <rect x="60" y="60" width="5" height="5" fill="black"/>
  <rect x="70" y="70" width="5" height="5" fill="black"/>
  <rect x="80" y="80" width="5" height="5" fill="black"/>
  <rect x="90" y="90" width="5" height="5" fill="black"/>
  <rect x="100" y="100" width="5" height="5" fill="black"/>
  
  <!-- More complex pattern -->
  <rect x="55" y="45" width="5" height="5" fill="black"/>
  <rect x="65" y="55" width="5" height="5" fill="black"/>
  <rect x="75" y="65" width="5" height="5" fill="black"/>
  <rect x="85" y="75" width="5" height="5" fill="black"/>
  <rect x="95" y="85" width="5" height="5" fill="black"/>
</svg>';

// Save as SVG files for the second QR code
file_put_contents('gcash-qr2.svg', $qrCode2);
file_put_contents('paymaya-qr2.svg', $qrCode2);

// Create base64 encoded version for inline use
$base64QR2 = base64_encode($qrCode2);

echo "Second QR codes created successfully!<br>";
echo "Files: gcash-qr2.svg, paymaya-qr2.svg<br>";
echo "Base64 encoded second QR code:<br>";
echo "<textarea style='width:100%; height:100px;'>data:image/svg+xml;base64," . $base64QR2 . "</textarea>";
?>
