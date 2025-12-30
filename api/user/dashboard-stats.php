<?php
require_once '../config/database.php';
session_start();

header('Content-Type: application/json');

// Auto-login for development
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['user_role'] = 'user';
}

$user_id = $_SESSION['user_id'];

try {
    // Get user blog statistics
    $stats = [];
    
    // Total blogs
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM blogs WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $stats['total_blogs'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Pending blogs
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM blogs WHERE user_id = ? AND status = 'pending'");
    $stmt->execute([$user_id]);
    $stats['pending_blogs'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Approved blogs
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM blogs WHERE user_id = ? AND status = 'approved'");
    $stmt->execute([$user_id]);
    $stats['approved_blogs'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Total views (mock data for now)
    $stats['total_views'] = $stats['approved_blogs'] * 45;
    
    // Recent blogs
    $stmt = $pdo->prepare("
        SELECT title, category, language, status, created_at 
        FROM blogs 
        WHERE user_id = ? 
        ORDER BY created_at DESC 
        LIMIT 5
    ");
    $stmt->execute([$user_id]);
    $recent_blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'stats' => $stats,
        'recent_blogs' => $recent_blogs
    ]);
    
} catch (Exception $e) {
    error_log("User dashboard error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Server error occurred']);
}
?>