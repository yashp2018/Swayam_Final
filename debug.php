<?php
echo "PHP is working!<br>";
echo "Current directory: " . __DIR__ . "<br>";
echo "Server info: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";

// Test if files exist
$files = [
    'api/config.php',
    'api/login.php', 
    'api/register.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✓ File exists: $file<br>";
    } else {
        echo "✗ File missing: $file<br>";
    }
}
?>