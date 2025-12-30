<?php
require_once 'config.php';

$authenticated = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

if ($authenticated) {
    echo json_encode([
        'authenticated' => true,
        'user' => [
            'id' => $_SESSION['user_id'] ?? null,
            'name' => $_SESSION['name'] ?? null,
            'role' => $_SESSION['role'] ?? null
        ]
    ]);
} else {
    echo json_encode(['authenticated' => false]);
}
?>