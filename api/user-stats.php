<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

try {
    // Get user creation date
    $userStmt = $pdo->prepare("SELECT created_at FROM users WHERE id = ?");
    $userStmt->execute([$_SESSION['user_id']]);
    $user = $userStmt->fetch(PDO::FETCH_ASSOC);
    
    // Get blog stats
    $blogStmt = $pdo->prepare("
        SELECT 
            COUNT(*) as totalBlogs,
            SUM(CASE WHEN status = 'published' THEN 1 ELSE 0 END) as publishedBlogs,
            SUM(views) as totalViews
        FROM blogs 
        WHERE user_id = ?
    ");
    $blogStmt->execute([$_SESSION['user_id']]);
    $blogStats = $blogStmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'stats' => [
            'totalBlogs' => $blogStats['totalBlogs'] ?? 0,
            'publishedBlogs' => $blogStats['publishedBlogs'] ?? 0,
            'totalViews' => $blogStats['totalViews'] ?? 0,
            'memberSince' => $user['created_at']
        ]
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to load stats']);
}
?>