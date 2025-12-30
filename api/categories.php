<?php
require_once 'config.php';

try {
    $stmt = $pdo->query("SELECT name_en, name_hi, name_mr, icon FROM categories WHERE status = 'active' ORDER BY name_en");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'categories' => $categories
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to load categories']);
}
?>