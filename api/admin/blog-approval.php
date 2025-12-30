<?php
require_once '../config/database.php';
session_start();

header('Content-Type: application/json');

// Allow both GET and POST methods
if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'])) {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Simplified admin check - for development
if (!isset($_SESSION['user_id'])) {
    // Auto-login as admin for development
    $_SESSION['user_id'] = 1;
    $_SESSION['user_role'] = 'admin';
}

// Handle both GET and POST data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'] ?? $_POST['action'] ?? '';
    $blog_id = $input['blog_id'] ?? $_POST['blog_id'] ?? '';
} else {
    $action = $_GET['action'] ?? '';
    $blog_id = $_GET['blog_id'] ?? '';
}

if (empty($action) || empty($blog_id)) {
    echo json_encode(['success' => false, 'message' => 'Action and blog ID required']);
    exit;
}

try {
    switch ($action) {
        case 'approve':
            // Update blog status to approved
            $stmt = $pdo->prepare("UPDATE blogs SET status = 'approved', approved_by = ?, approved_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$_SESSION['user_id'], $blog_id]);
            
            // Get blog details for notification
            $blogStmt = $pdo->prepare("SELECT title, user_id FROM blogs WHERE id = ?");
            $blogStmt->execute([$blog_id]);
            $blog = $blogStmt->fetch(PDO::FETCH_ASSOC);
            
            // Log admin activity
            $logStmt = $pdo->prepare("INSERT INTO admin_activity_log (admin_id, action, target_type, target_id, details) VALUES (?, 'approve_blog', 'blog', ?, ?)");
            $logStmt->execute([$_SESSION['user_id'], $blog_id, "Approved blog: " . $blog['title']]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Blog approved successfully',
                'blog_id' => $blog_id
            ]);
            break;
            
        case 'reject':
            $reason = $input['reason'] ?? '';
            if (empty($reason)) {
                echo json_encode(['success' => false, 'message' => 'Rejection reason required']);
                exit;
            }
            
            // Update blog status to rejected
            $stmt = $pdo->prepare("UPDATE blogs SET status = 'rejected', rejected_by = ?, rejected_at = CURRENT_TIMESTAMP, rejection_reason = ? WHERE id = ?");
            $stmt->execute([$_SESSION['user_id'], $reason, $blog_id]);
            
            // Get blog details
            $blogStmt = $pdo->prepare("SELECT title, user_id FROM blogs WHERE id = ?");
            $blogStmt->execute([$blog_id]);
            $blog = $blogStmt->fetch(PDO::FETCH_ASSOC);
            
            // Log admin activity
            $logStmt = $pdo->prepare("INSERT INTO admin_activity_log (admin_id, action, target_type, target_id, details) VALUES (?, 'reject_blog', 'blog', ?, ?)");
            $logStmt->execute([$_SESSION['user_id'], $blog_id, "Rejected blog: " . $blog['title'] . " - Reason: " . $reason]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Blog rejected successfully',
                'blog_id' => $blog_id
            ]);
            break;
            
        case 'get_pending':
            // Get all pending blogs
            $stmt = $pdo->prepare("
                SELECT b.*, u.name as author_name, u.email as author_email 
                FROM blogs b 
                JOIN users u ON b.user_id = u.id 
                WHERE b.status = 'pending' 
                ORDER BY b.created_at DESC
            ");
            $stmt->execute();
            $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'success' => true,
                'blogs' => $blogs,
                'count' => count($blogs)
            ]);
            break;
            
        case 'get_blog_details':
            // Get specific blog details for preview
            $stmt = $pdo->prepare("
                SELECT b.*, u.name as author_name, u.email as author_email 
                FROM blogs b 
                JOIN users u ON b.user_id = u.id 
                WHERE b.id = ?
            ");
            $stmt->execute([$blog_id]);
            $blog = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$blog) {
                echo json_encode(['success' => false, 'message' => 'Blog not found']);
                exit;
            }
            
            echo json_encode([
                'success' => true,
                'blog' => $blog
            ]);
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            break;
    }
    
} catch (Exception $e) {
    error_log("Blog approval error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Server error occurred']);
}
?>