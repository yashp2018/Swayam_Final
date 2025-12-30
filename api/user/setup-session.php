<?php
session_start();

// Simple user session setup for development
$_SESSION['user_id'] = 1;
$_SESSION['user_role'] = 'user';
$_SESSION['user_name'] = 'Test User';

echo json_encode([
    'success' => true,
    'message' => 'User session created',
    'user' => [
        'id' => $_SESSION['user_id'],
        'role' => $_SESSION['user_role'],
        'name' => $_SESSION['user_name']
    ]
]);
?>