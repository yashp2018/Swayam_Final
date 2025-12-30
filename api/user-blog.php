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
$category = $input['category'] ?? '';
$language = $input['language'] ?? 'en';
$tags = trim($input['tags'] ?? '');
$excerpt = trim($input['excerpt'] ?? '');
$featuredImage = trim($input['featured_image'] ?? '');
$content = $input['content'] ?? '';
$media = json_encode($input['media'] ?? []);
$status = $input['status'] ?? 'pending'; // pending, draft, published

if (empty($title) || empty($category) || empty($content)) {
    echo json_encode(['success' => false, 'message' => 'Title, category, and content are required']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO blogs 
        (user_id, title, category, language, tags, excerpt, featured_image, content, media, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $_SESSION['user_id'],
        $title,
        $category,
        $language,
        $tags,
        $excerpt,
        $featuredImage ?: null,
        $content,
        $media,
        $status
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Blog submitted successfully'
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to submit blog']);
}
?>