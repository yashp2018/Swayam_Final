<?php
/**
 * Front Controller - SWAYAM
 * Entry point for all requests
 */

// Start session
session_start();

// Load environment variables
require_once __DIR__ . '/../app/config/app.php';

// Autoload classes (if using Composer)
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

// Load routes
require_once __DIR__ . '/../routes/web.php';

// Handle the request
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
?>