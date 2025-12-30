<?php
session_start();

// Simple admin session setup for development
$_SESSION['user_id'] = 1;
$_SESSION['user_role'] = 'admin';
$_SESSION['user_name'] = 'Admin User';

echo json_encode([
    'success' => true,
    'message' => 'Admin session created',
    'user' => [
        'id' => $_SESSION['user_id'],
        'role' => $_SESSION['user_role'],
        'name' => $_SESSION['user_name']
    ]
]);
?>