<?php
require_once 'api/config.php';

try {
    // Create blogs table
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
    echo "✅ Blogs table created successfully!<br>";
    
    // Add some sample blog categories if they don't exist
    $categories = [
        ['Meditation Techniques', 'ध्यान तकनीक', 'ध्यान तंत्र', 'fas fa-lotus'],
        ['Spiritual Stories', 'आध्यात्मिक कहानियाँ', 'आध्यात्मिक कथा', 'fas fa-book-open'],
        ['Yoga Practice', 'योग अभ्यास', 'योग सराव', 'fas fa-spa'],
        ['Life Philosophy', 'जीवन दर्शन', 'जीवन तत्वज्ञान', 'fas fa-lightbulb']
    ];
    
    foreach ($categories as $cat) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO categories (name_en, name_hi, name_mr, icon) VALUES (?, ?, ?, ?)");
        $stmt->execute($cat);
    }
    
    echo "✅ Blog categories added!<br>";
    echo "<br><a href='admin/blog-management.html'>Go to Admin Blog Management</a><br>";
    echo "<a href='user-blog.html'>Go to User Blog Creation</a><br>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>