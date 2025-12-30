<?php
require_once '../config/database.php';
session_start();

header('Content-Type: application/json');

// Simplified admin check - for development
if (!isset($_SESSION['user_id'])) {
    // Auto-login as admin for development
    $_SESSION['user_id'] = 1;
    $_SESSION['user_role'] = 'admin';
}

try {
    // Get dashboard statistics
    $stats = [];
    
    // Pending blogs count
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM blogs WHERE status = 'pending'");
    $stmt->execute();
    $stats['pending_blogs'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Approved blogs count
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM blogs WHERE status = 'approved'");
    $stmt->execute();
    $stats['approved_blogs'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Total active users
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM users WHERE status = 'active'");
    $stmt->execute();
    $stats['active_users'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Verified masters count
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM users WHERE role = 'master' AND status = 'active'");
    $stmt->execute();
    $stats['verified_masters'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Recent activity (last 10 activities)
    $stmt = $pdo->prepare("
        SELECT 
            'blog_submission' as activity_type,
            b.title as activity_title,
            u.name as user_name,
            b.created_at as activity_time,
            'pending' as status
        FROM blogs b 
        JOIN users u ON b.user_id = u.id 
        WHERE b.status = 'pending'
        
        UNION ALL
        
        SELECT 
            'blog_approval' as activity_type,
            b.title as activity_title,
            u.name as user_name,
            b.approved_at as activity_time,
            'approved' as status
        FROM blogs b 
        JOIN users u ON b.user_id = u.id 
        WHERE b.status = 'approved' AND b.approved_at IS NOT NULL
        
        UNION ALL
        
        SELECT 
            'user_registration' as activity_type,
            CONCAT('New user: ', u.name) as activity_title,
            u.name as user_name,
            u.created_at as activity_time,
            u.status as status
        FROM users u 
        WHERE u.role = 'user'
        
        ORDER BY activity_time DESC 
        LIMIT 10
    ");
    $stmt->execute();
    $recent_activity = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Most active users (by blog count)
    $stmt = $pdo->prepare("
        SELECT u.name, u.email, COUNT(b.id) as blog_count 
        FROM users u 
        LEFT JOIN blogs b ON u.id = b.user_id 
        WHERE u.role = 'user' 
        GROUP BY u.id, u.name, u.email 
        ORDER BY blog_count DESC 
        LIMIT 10
    ");
    $stmt->execute();
    $active_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Content categories distribution
    $stmt = $pdo->prepare("
        SELECT category, COUNT(*) as count 
        FROM blogs 
        WHERE status = 'approved' 
        GROUP BY category 
        ORDER BY count DESC
    ");
    $stmt->execute();
    $content_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Language distribution
    $stmt = $pdo->prepare("
        SELECT language, COUNT(*) as count 
        FROM blogs 
        WHERE status = 'approved' 
        GROUP BY language 
        ORDER BY count DESC
    ");
    $stmt->execute();
    $language_distribution = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Weekly blog submissions trend
    $stmt = $pdo->prepare("
        SELECT 
            DATE(created_at) as date,
            COUNT(*) as count 
        FROM blogs 
        WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        GROUP BY DATE(created_at) 
        ORDER BY date ASC
    ");
    $stmt->execute();
    $weekly_trend = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // User engagement metrics
    $stmt = $pdo->prepare("
        SELECT 
            COUNT(DISTINCT user_id) as total_contributors,
            AVG(CHAR_LENGTH(content)) as avg_content_length,
            COUNT(*) as total_submissions
        FROM blogs 
        WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    ");
    $stmt->execute();
    $engagement_metrics = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'data' => [
            'stats' => $stats,
            'recent_activity' => $recent_activity,
            'active_users' => $active_users,
            'content_categories' => $content_categories,
            'language_distribution' => $language_distribution,
            'weekly_trend' => $weekly_trend,
            'engagement_metrics' => $engagement_metrics
        ]
    ]);
    
} catch (Exception $e) {
    error_log("Dashboard stats error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Server error occurred']);
}
?>