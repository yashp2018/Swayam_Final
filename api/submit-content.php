<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$title = trim($input['title'] ?? '');
$type = $input['type'] ?? '';
$category = $input['category'] ?? '';
$language = $input['language'] ?? 'en';
$description = trim($input['description'] ?? '');
$content = trim($input['content'] ?? '');
$mediaUrl = trim($input['media_url'] ?? '');
$thumbnailUrl = trim($input['thumbnail_url'] ?? '');

if (empty($title) || empty($type) || empty($category) || empty($content)) {
    echo json_encode(['success' => false, 'message' => 'Required fields are missing']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO content_submissions 
        (user_id, title, description, content, type, category, language, media_url, thumbnail_url) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $_SESSION['user_id'],
        $title,
        $description,
        $content,
        $type,
        $category,
        $language,
        $mediaUrl ?: null,
        $thumbnailUrl ?: null
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Content submitted successfully'
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to submit content']);
}
?>