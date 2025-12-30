<?php
require_once 'api/config/database.php';

try {
    // Update blogs table to include media_files column
    $sql = "ALTER TABLE blogs ADD COLUMN IF NOT EXISTS media_files JSON";
    $pdo->exec($sql);
    echo "✅ Blogs table updated with media_files column<br>";

    // Create uploads directory
    if (!file_exists('uploads/blogs/')) {
        mkdir('uploads/blogs/', 0777, true);
        echo "✅ Uploads directory created<br>";
    }

    // Insert sample user for testing
    $stmt = $pdo->prepare("INSERT IGNORE INTO users (name, email, password, role, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute(['Test User', 'user@test.com', password_hash('password', PASSWORD_DEFAULT), 'user', 'active']);
    echo "✅ Test user created (user@test.com / password)<br>";

    // Insert sample blogs for testing
    $sampleBlogs = [
        [
            'user_id' => 1,
            'title' => 'My Spiritual Journey to Rishikesh',
            'content' => '<p>Last month I visited Rishikesh, the yoga capital of the world. The experience was transformative...</p>',
            'category' => 'spiritual-journey',
            'language' => 'en',
            'tags' => 'rishikesh, yoga, spirituality',
            'status' => 'pending'
        ],
        [
            'user_id' => 1,
            'title' => 'गंगा आरती का अनुभव',
            'content' => '<p>हरिद्वार में गंगा आरती देखना एक अविस्मरणीय अनुभव था...</p>',
            'category' => 'spiritual-journey',
            'language' => 'hi',
            'tags' => 'गंगा, आरती, हरिद्वार',
            'status' => 'pending'
        ],
        [
            'user_id' => 1,
            'title' => 'Daily Meditation Practice',
            'content' => '<p>Here are 5 simple meditation techniques that changed my life...</p>',
            'category' => 'meditation',
            'language' => 'en',
            'tags' => 'meditation, mindfulness, peace',
            'status' => 'approved'
        ]
    ];

    $stmt = $pdo->prepare("INSERT INTO blogs (user_id, title, content, category, language, tags, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
    foreach ($sampleBlogs as $blog) {
        $stmt->execute(array_values($blog));
    }
    echo "✅ Sample blogs inserted<br>";

    echo "<br><h2>🎉 Complete Workflow Setup!</h2>";
    echo "<h3>📋 User Workflow:</h3>";
    echo "<ol>";
    echo "<li><strong>Homepage:</strong> <a href='index.html'>index.html</a></li>";
    echo "<li><strong>User Login:</strong> <a href='login.html'>login.html</a> (user@test.com / password)</li>";
    echo "<li><strong>User Dashboard:</strong> <a href='dashboard.html'>dashboard.html</a></li>";
    echo "<li><strong>Create Blog:</strong> <a href='create-blog.html'>create-blog.html</a></li>";
    echo "</ol>";

    echo "<h3>👨‍💼 Admin Workflow:</h3>";
    echo "<ol>";
    echo "<li><strong>Admin Login:</strong> <a href='admin/login.php'>admin/login.php</a> (admin@mail.com / password)</li>";
    echo "<li><strong>Admin Dashboard:</strong> <a href='admin/dashboard.html'>admin/dashboard.html</a></li>";
    echo "<li><strong>Blog Management:</strong> <a href='admin/blog-management.html'>admin/blog-management.html</a></li>";
    echo "</ol>";

    echo "<h3>🔄 Complete Flow:</h3>";
    echo "<p>1. User creates blog → 2. Admin reviews → 3. Admin approves/rejects → 4. Blog published</p>";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}
?>