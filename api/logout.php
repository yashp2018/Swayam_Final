<?php
require_once 'config.php';

// Destroy session
session_destroy();
session_start();

echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
?>