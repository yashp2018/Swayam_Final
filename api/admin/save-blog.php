<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Access denied']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$id = $input['id'] ?? null;
$title = trim($input['title'] ?? '');
$category = $input['category'] ?? '';
$language = $input['language'] ?? 'en';
$status = $input['status'] ?? 'draft';
$excerpt = trim($input['excerpt'] ?? '');
$featuredImage = trim($input['featured_image'] ?? '');
$content = $input['content'] ?? '';
$media = json_encode($input['media'] ?? []);

if (empty($title) || empty($category)) {
    echo json_encode(['success' => false, 'message' => 'Title and category are required']);
    exit;
}

try {
    if ($id) {
        // Update existing blog
        $stmt = $pdo->prepare("
            UPDATE blogs SET 
            title = ?, category = ?, language = ?, status = ?, 
            excerpt = ?, featured_image = ?, content = ?, media = ?,
            updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        $stmt->execute([
            $title, $category, $language, $status,
            $excerpt, $featuredImage ?: null, $content, $media, $id
        ]);
    } else {
        // Create new blog
        $stmt = $pdo->prepare("
            INSERT INTO blogs 
            (user_id, title, category, language, status, excerpt, featured_image, content, media) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $_SESSION['user_id'], $title, $category, $language, $status,
            $excerpt, $featuredImage ?: null, $content, $media
        ]);
    }
    
    echo json_encode(['success' => true, 'message' => 'Blog saved successfully']);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to save blog']);
}
?>