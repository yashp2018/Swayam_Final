<?php
require_once 'api/config/database.php';

try {
    // Create admin activity log table
    $sql = "CREATE TABLE IF NOT EXISTS admin_activity_log (
        id INT AUTO_INCREMENT PRIMARY KEY,
        admin_id INT NOT NULL,
        action VARCHAR(50) NOT NULL,
        target_type VARCHAR(50) NOT NULL,
        target_id INT NOT NULL,
        details TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);
    echo "✅ Admin activity log table created successfully<br>";

    // Update blogs table to include approval workflow columns
    $sql = "ALTER TABLE blogs 
            ADD COLUMN IF NOT EXISTS approved_by INT NULL,
            ADD COLUMN IF NOT EXISTS approved_at TIMESTAMP NULL,
            ADD COLUMN IF NOT EXISTS rejected_by INT NULL,
            ADD COLUMN IF NOT EXISTS rejected_at TIMESTAMP NULL,
            ADD COLUMN IF NOT EXISTS rejection_reason TEXT NULL,
            ADD FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL,
            ADD FOREIGN KEY (rejected_by) REFERENCES users(id) ON DELETE SET NULL";
    $pdo->exec($sql);
    echo "✅ Blogs table updated with approval workflow columns<br>";

    // Create user activity tracking table
    $sql = "CREATE TABLE IF NOT EXISTS user_activity (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        activity_type VARCHAR(50) NOT NULL,
        activity_data JSON,
        ip_address VARCHAR(45),
        user_agent TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_user_activity (user_id, created_at),
        INDEX idx_activity_type (activity_type)
    )";
    $pdo->exec($sql);
    echo "✅ User activity tracking table created successfully<br>";

    // Create content categories table
    $sql = "CREATE TABLE IF NOT EXISTS content_categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        name_hi VARCHAR(100),
        name_mr VARCHAR(100),
        description TEXT,
        icon VARCHAR(50),
        color VARCHAR(7),
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "✅ Content categories table created successfully<br>";

    // Insert default categories
    $categories = [
        ['Spiritual Journey', 'आध्यात्मिक यात्रा', 'आध्यात्मिक प्रवास', 'Personal spiritual experiences and temple visits', 'fas fa-om', '#927397'],
        ['Meditation', 'ध्यान', 'ध्यान', 'Meditation practices and techniques', 'fas fa-leaf', '#10b981'],
        ['Yoga & Wellness', 'योग और स्वास्थ्य', 'योग आणि आरोग्य', 'Yoga practices and wellness tips', 'fas fa-spa', '#3b82f6'],
        ['Arts & Culture', 'कला और संस्कृति', 'कला आणि संस्कृती', 'Music, dance, and cultural expressions', 'fas fa-music', '#f59e0b'],
        ['Philosophy', 'दर्शन', 'तत्त्वज्ञान', 'Philosophical thoughts and wisdom', 'fas fa-book-open', '#8b5cf6'],
        ['Personal Stories', 'व्यक्तिगत कहानियां', 'वैयक्तिक कथा', 'Personal growth and life experiences', 'fas fa-heart', '#ef4444']
    ];

    $stmt = $pdo->prepare("INSERT IGNORE INTO content_categories (name, name_hi, name_mr, description, icon, color) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($categories as $category) {
        $stmt->execute($category);
    }
    echo "✅ Default content categories inserted<br>";

    // Create masters/experts table
    $sql = "CREATE TABLE IF NOT EXISTS masters (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        specialization VARCHAR(100) NOT NULL,
        bio TEXT,
        experience_years INT DEFAULT 0,
        certifications JSON,
        skills JSON,
        rating DECIMAL(3,2) DEFAULT 0.00,
        total_sessions INT DEFAULT 0,
        total_students INT DEFAULT 0,
        verification_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
        verified_by INT NULL,
        verified_at TIMESTAMP NULL,
        is_featured BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (verified_by) REFERENCES users(id) ON DELETE SET NULL
    )";
    $pdo->exec($sql);
    echo "✅ Masters table created successfully<br>";

    // Create retreat management table
    $sql = "CREATE TABLE IF NOT EXISTS retreats (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(200) NOT NULL,
        description TEXT,
        duration_days INT NOT NULL,
        max_participants INT DEFAULT 20,
        price DECIMAL(10,2),
        location VARCHAR(200),
        start_date DATE,
        end_date DATE,
        status ENUM('draft', 'published', 'full', 'completed', 'cancelled') DEFAULT 'draft',
        created_by INT NOT NULL,
        syllabus JSON,
        requirements TEXT,
        includes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);
    echo "✅ Retreats table created successfully<br>";

    // Insert sample data for testing
    echo "<br><h3>🌱 Inserting Sample Data...</h3>";

    // Sample pending blogs
    $sampleBlogs = [
        [
            'title' => 'शेगांव मंदिर की आध्यात्मिक यात्रा',
            'content' => 'गजानन महाराज के दर्शन के लिए शेगांव की मेरी यात्रा एक अविस्मरणीय अनुभव था...',
            'language' => 'hi',
            'category' => 'Spiritual Journey',
            'user_id' => 1,
            'status' => 'pending'
        ],
        [
            'title' => 'योगाचे आध्यात्मिक फायदे',
            'content' => 'योगाचा सराव केल्याने केवळ शारीरिक फायदे होत नाहीत तर आध्यात्मिक उन्नती देखील होते...',
            'language' => 'mr',
            'category' => 'Yoga & Wellness',
            'user_id' => 1,
            'status' => 'pending'
        ],
        [
            'title' => 'Classical Music as Spiritual Practice',
            'content' => 'How Indian classical music became my path to spiritual awakening and self-discovery...',
            'language' => 'en',
            'category' => 'Arts & Culture',
            'user_id' => 1,
            'status' => 'pending'
        ]
    ];

    $stmt = $pdo->prepare("INSERT INTO blogs (title, content, language, category, user_id, status) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($sampleBlogs as $blog) {
        $stmt->execute(array_values($blog));
    }
    echo "✅ Sample pending blogs inserted<br>";

    echo "<br><h2>🎉 Admin System Setup Complete!</h2>";
    echo "<p>✅ All tables created successfully</p>";
    echo "<p>✅ Sample data inserted</p>";
    echo "<p>✅ Blog approval workflow ready</p>";
    echo "<p>✅ User activity tracking enabled</p>";
    echo "<br><p><strong>Next Steps:</strong></p>";
    echo "<ul>";
    echo "<li>Access admin dashboard: <a href='admin/dashboard.html'>admin/dashboard.html</a></li>";
    echo "<li>Login with admin credentials</li>";
    echo "<li>Start reviewing pending blogs</li>";
    echo "</ul>";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    error_log("Admin setup error: " . $e->getMessage());
}
?>