<?php
echo "<h2>File Check</h2>";

$files = [
    'public/css/style.css',
    'public/js/auth.js',
    'api/config.php',
    'api/login.php',
    'api/register.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✅ $file exists<br>";
    } else {
        echo "❌ $file MISSING<br>";
    }
}

echo "<br><h3>Database Test:</h3>";
try {
    $pdo = new PDO("mysql:host=localhost;dbname=swayam;charset=utf8mb4", "root", "");
    echo "✅ Database connected<br>";
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE email = 'admin@mail.com'");
    $count = $stmt->fetchColumn();
    echo "✅ Admin user exists: $count<br>";
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>";
}
?>