<?php
require_once '../config.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Access denied']);
    exit;
}

try {
    // Get total users
    $usersStmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE role != 'admin'");
    $totalUsers = $usersStmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Get pending content
    $pendingStmt = $pdo->query("SELECT COUNT(*) as count FROM content_submissions WHERE status = 'pending'");
    $pendingContent = $pendingStmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Get approved content
    $approvedStmt = $pdo->query("SELECT COUNT(*) as count FROM content_submissions WHERE status = 'approved'");
    $approvedContent = $approvedStmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Get total categories
    $categoriesStmt = $pdo->query("SELECT COUNT(*) as count FROM categories WHERE status = 'active'");
    $totalCategories = $categoriesStmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    echo json_encode([
        'success' => true,
        'data' => [
            'users' => $totalUsers,
            'pending' => $pendingContent,
            'approved' => $approvedContent,
            'categories' => $totalCategories
        ]
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to load stats']);
}
?>