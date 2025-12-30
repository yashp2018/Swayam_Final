<?php
/**
 * SWAYAM Setup Script
 * Run this file to set up the complete application
 */

echo "<h1>🕉️ SWAYAM Setup</h1>";

// Step 1: Test database connection
echo "<h2>Step 1: Testing Database Connection</h2>";
try {
    $pdo = new PDO("mysql:host=localhost;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ MySQL connection successful<br>";
    
    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS swayam CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Database 'swayam' created/verified<br>";
    
} catch(PDOException $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}

// Step 2: Import database structure
echo "<h2>Step 2: Setting up Database Tables</h2>";
try {
    $pdo = new PDO("mysql:host=localhost;dbname=swayam;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Read and execute SQL file
    $sql = file_get_contents(__DIR__ . '/database/swayam_database.sql');
    $pdo->exec($sql);
    echo "✅ Database tables created successfully<br>";
    
} catch(Exception $e) {
    echo "❌ Database setup error: " . $e->getMessage() . "<br>";
}

// Step 3: Create blogs table
echo "<h2>Step 3: Setting up Blog System</h2>";
try {
    $sql = "CREATE TABLE IF NOT EXISTS blogs (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        category VARCHAR(100) NOT NULL,
        language ENUM('en','hi','mr') DEFAULT 'en',
        tags TEXT,
        excerpt TEXT,
        featured_image VARCHAR(500),
        content LONGTEXT,
        media JSON,
        status ENUM('draft','pending','published','rejected') DEFAULT 'pending',
        views INT DEFAULT 0,
        likes INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_status (status),
        INDEX idx_category (category),
        INDEX idx_created (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $pdo->exec($sql);
    echo "✅ Blogs table created successfully<br>";
    
} catch(Exception $e) {
    echo "❌ Blog setup error: " . $e->getMessage() . "<br>";
}

// Step 4: Verify installation
echo "<h2>Step 4: Verification</h2>";
$tables = ['users', 'admin_users', 'categories', 'content_submissions', 'blogs'];
foreach ($tables as $table) {
    $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Table '$table' exists<br>";
    } else {
        echo "❌ Table '$table' missing<br>";
    }
}

// Step 5: Create upload directories
echo "<h2>Step 5: Creating Upload Directories</h2>";
$dirs = [
    'public/uploads',
    'public/uploads/blogs',
    'public/uploads/profiles',
    'storage/logs'
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        echo "✅ Created directory: $dir<br>";
    } else {
        echo "✅ Directory exists: $dir<br>";
    }
}

echo "<h2>🎉 Setup Complete!</h2>";
echo "<p><strong>Default Admin Login:</strong></p>";
echo "<ul>";
echo "<li>Email: admin@mail.com</li>";
echo "<li>Password: password</li>";
echo "</ul>";

echo "<h3>🔗 Quick Links:</h3>";
echo "<ul>";
echo "<li><a href='http://localhost/Swayam/'>Homepage</a></li>";
echo "<li><a href='http://localhost/Swayam/login.html'>Login</a></li>";
echo "<li><a href='http://localhost/Swayam/register.html'>Register</a></li>";
echo "<li><a href='http://localhost/Swayam/admin/dashboard.html'>Admin Panel</a></li>";
echo "</ul>";

echo "<h3>🔧 Test Links:</h3>";
echo "<ul>";
echo "<li><a href='test-db.php'>Test Database</a></li>";
echo "<li><a href='check-files.php'>Check Files</a></li>";
echo "</ul>";
?>