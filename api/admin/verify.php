<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['token'])) {
    echo json_encode(['success' => false, 'message' => 'Token required']);
    exit;
}

$token = $input['token'];

try {
    $pdo = new PDO($dsn, $username, $password_db, $options);
    
    // Verify token and get admin info
    $stmt = $pdo->prepare("
        SELECT u.id, u.name, u.email, u.role, s.expires_at
        FROM admin_sessions s
        JOIN users u ON s.user_id = u.id
        WHERE s.token = ? AND s.expires_at > NOW() AND u.is_active = 1
    ");
    $stmt->execute([$token]);
    $session = $stmt->fetch();
    
    if (!$session) {
        echo json_encode(['success' => false, 'message' => 'Invalid or expired token']);
        exit;
    }
    
    // Update session expiry
    $newExpiry = date('Y-m-d H:i:s', strtotime('+24 hours'));
    $stmt = $pdo->prepare("UPDATE admin_sessions SET expires_at = ? WHERE token = ?");
    $stmt->execute([$newExpiry, $token]);
    
    echo json_encode([
        'success' => true,
        'admin' => [
            'id' => $session['id'],
            'name' => $session['name'],
            'email' => $session['email'],
            'role' => $session['role']
        ]
    ]);
    
} catch (PDOException $e) {
    error_log("Token verification error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>