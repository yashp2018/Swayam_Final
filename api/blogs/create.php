<?php
require_once '../config/database.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Check user authentication
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Authentication required']);
    exit;
}

$user_id = $_SESSION['user_id'];
$title = $_POST['title'] ?? '';
$content = $_POST['content'] ?? '';
$category = $_POST['category'] ?? '';
$language = $_POST['language'] ?? 'en';
$tags = $_POST['tags'] ?? '';
$status = $_POST['status'] ?? 'pending';

// Validate required fields
if (empty($title) || empty($content) || empty($category)) {
    echo json_encode(['success' => false, 'message' => 'Title, content, and category are required']);
    exit;
}

try {
    // Create uploads directory if it doesn't exist
    $uploadDir = '../uploads/blogs/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Handle file uploads
    $mediaFiles = [];
    foreach ($_FILES as $key => $file) {
        if (strpos($key, 'media_') === 0 && $file['error'] === UPLOAD_ERR_OK) {
            $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileName = uniqid() . '_' . time() . '.' . $fileExtension;
            $filePath = $uploadDir . $fileName;
            
            // Validate file type
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'avi', 'mov', 'mp3', 'wav', 'ogg'];
            if (!in_array(strtolower($fileExtension), $allowedTypes)) {
                continue;
            }
            
            // Validate file size (10MB max)
            if ($file['size'] > 10 * 1024 * 1024) {
                continue;
            }
            
            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                $mediaFiles[] = [
                    'filename' => $fileName,
                    'original_name' => $file['name'],
                    'type' => $file['type'],
                    'size' => $file['size']
                ];
            }
        }
    }

    // Insert blog into database
    $stmt = $pdo->prepare("
        INSERT INTO blogs (user_id, title, content, category, language, tags, status, media_files, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)
    ");
    
    $mediaJson = json_encode($mediaFiles);
    $stmt->execute([$user_id, $title, $content, $category, $language, $tags, $status, $mediaJson]);
    
    $blog_id = $pdo->lastInsertId();
    
    // Log user activity
    $activityStmt = $pdo->prepare("
        INSERT INTO user_activity (user_id, activity_type, activity_data, created_at) 
        VALUES (?, 'blog_created', ?, CURRENT_TIMESTAMP)
    ");
    $activityData = json_encode([
        'blog_id' => $blog_id,
        'title' => $title,
        'category' => $category,
        'language' => $language
    ]);
    $activityStmt->execute([$user_id, $activityData]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Blog created successfully',
        'blog_id' => $blog_id,
        'status' => $status
    ]);
    
} catch (Exception $e) {
    error_log("Blog creation error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Server error occurred']);
}
?>