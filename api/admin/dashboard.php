<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

try {
    $pdo = new PDO($dsn, $username, $password_db, $options);
    
    // Get statistics
    $stats = [];
    
    // Pending approvals
    $stmt = $pdo->query("SELECT COUNT(*) FROM submissions WHERE status = 'pending'");
    $stats['pending'] = $stmt->fetchColumn();
    
    // Total users
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE is_active = 1");
    $stats['users'] = $stmt->fetchColumn();
    
    // Active retreats
    $stmt = $pdo->query("SELECT COUNT(*) FROM retreats WHERE status = 'active'");
    $stats['retreats'] = $stmt->fetchColumn();
    
    // Published content
    $stmt = $pdo->query("SELECT COUNT(*) FROM submissions WHERE status = 'approved'");
    $stats['content'] = $stmt->fetchColumn();
    
    // Recent activities
    $stmt = $pdo->prepare("
        SELECT 
            s.title,
            u.name as author,
            s.type,
            s.status,
            s.created_at,
            CASE 
                WHEN s.status = 'approved' THEN 'Content Approved'
                WHEN s.status = 'pending' THEN 'Pending Review'
                WHEN s.status = 'rejected' THEN 'Content Rejected'
                ELSE 'Unknown Status'
            END as activity_type
        FROM submissions s
        JOIN users u ON s.user_id = u.id
        ORDER BY s.created_at DESC
        LIMIT 5
    ");
    $stmt->execute();
    $activities = $stmt->fetchAll();
    
    // Format activities for frontend
    $formattedActivities = array_map(function($activity) {
        $iconClass = 'bg-primary';
        $icon = 'fas fa-file-alt';
        
        switch($activity['status']) {
            case 'approved':
                $iconClass = 'bg-success';
                $icon = 'fas fa-check-circle';
                break;
            case 'pending':
                $iconClass = 'bg-warning';
                $icon = 'fas fa-clock';
                break;
            case 'rejected':
                $iconClass = 'bg-danger';
                $icon = 'fas fa-times-circle';
                break;
        }
        
        return [
            'text' => $activity['activity_type'] . ': "' . $activity['title'] . '" by ' . $activity['author'],
            'time' => date('M j, Y g:i A', strtotime($activity['created_at'])),
            'iconClass' => $iconClass,
            'icon' => $icon
        ];
    }, $activities);
    
    echo json_encode([
        'success' => true,
        'stats' => $stats,
        'activities' => $formattedActivities
    ]);
    
} catch (PDOException $e) {
    error_log("Dashboard data error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>