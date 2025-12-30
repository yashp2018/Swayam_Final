<?php
require_once '../api/config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $email = $input['email'] ?? '';
    $password = $input['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Email and password required']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT id, name, email, password, role, status, language FROM users WHERE email = ? AND status = 'active'");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            // Update last login
            $updateStmt = $pdo->prepare("UPDATE users SET updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $updateStmt->execute([$user['id']]);
            
            // Store user session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            
            // Remove password from response
            unset($user['password']);
            
            echo json_encode([
                'success' => true,
                'message' => 'Login successful',
                'user' => $user
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Server error']);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swayam Admin - Login</title>
    <link rel="stylesheet" href="../public/css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .login-body {
            background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-secondary) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        
        .login-card {
            background: var(--admin-white);
            padding: 3rem;
            border-radius: var(--admin-radius-lg);
            box-shadow: var(--admin-shadow-xl);
            width: 100%;
            max-width: 420px;
            animation: fadeIn 0.8s ease-out;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-logo {
            width: 80px;
            height: 80px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2rem;
            animation: gentleFloat 4s ease-in-out infinite;
        }
        
        .login-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 600;
            color: var(--admin-gray-900);
            margin-bottom: 0.5rem;
        }
        
        .login-subtitle {
            color: var(--admin-gray-500);
            font-size: 0.875rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--admin-gray-700);
            margin-bottom: 0.5rem;
        }
        
        .form-input {
            width: 100%;
            padding: 1rem;
            border: 2px solid var(--admin-gray-200);
            border-radius: var(--admin-radius);
            font-size: 1rem;
            transition: var(--admin-transition);
            background: var(--admin-white);
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 3px rgba(146, 115, 151, 0.1);
        }
        
        .login-btn {
            width: 100%;
            padding: 1rem;
            background: var(--gradient-primary);
            color: white;
            border: none;
            border-radius: var(--admin-radius);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--admin-transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--admin-shadow-lg);
        }
        
        .login-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .back-link {
            text-align: center;
            margin-top: 2rem;
        }
        
        .back-link a {
            color: var(--admin-gray-500);
            text-decoration: none;
            font-size: 0.875rem;
            transition: var(--admin-transition-fast);
        }
        
        .back-link a:hover {
            color: var(--admin-primary);
        }
        
        .message {
            padding: 1rem;
            border-radius: var(--admin-radius);
            margin-bottom: 1rem;
            display: none;
        }
        
        .message.success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--admin-success);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }
        
        .message.error {
            background: rgba(220, 53, 69, 0.1);
            color: var(--admin-error);
            border: 1px solid rgba(220, 53, 69, 0.2);
        }
    </style>
</head>
<body class="login-body">
    <div class="login-card">
        <div class="login-header">
            <div class="login-logo">
                <i class="fas fa-user-shield"></i>
            </div>
            <h1 class="login-title">Admin Login</h1>
            <p class="login-subtitle">Swayam Platform Administration</p>
        </div>

        <form id="loginForm">
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" id="email" class="form-input" required placeholder="admin@swayam.org" autocomplete="email">
            </div>
            
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" id="password" class="form-input" required placeholder="Enter your password" autocomplete="current-password">
            </div>
            
            <div id="message" class="message"></div>
            
            <button type="submit" class="login-btn" id="loginBtn">
                <i class="fas fa-sign-in-alt"></i>
                <span>Sign In</span>
            </button>
        </form>

        <div class="back-link">
            <a href="../index.html">
                <i class="fas fa-arrow-left"></i> Back to Website
            </a>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const messageDiv = document.getElementById('message');
            const loginBtn = document.getElementById('loginBtn');
            
            // Disable button and show loading
            loginBtn.disabled = true;
            loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Signing In...</span>';
            
            try {
                const response = await fetch('login.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({email, password})
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Store token
                    localStorage.setItem('swayamAdminToken', result.token);
                    
                    // Show success message
                    messageDiv.className = 'message success';
                    messageDiv.innerHTML = '<i class="fas fa-check-circle"></i> Login successful! Redirecting...';
                    messageDiv.style.display = 'block';
                    
                    // Redirect after delay
                    setTimeout(() => {
                        window.location.href = 'index.html';
                    }, 1500);
                } else {
                    // Show error message
                    messageDiv.className = 'message error';
                    messageDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + (result.message || 'Login failed. Please try again.');
                    messageDiv.style.display = 'block';
                    
                    // Re-enable button
                    loginBtn.disabled = false;
                    loginBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> <span>Sign In</span>';
                }
            } catch (error) {
                console.error('Login error:', error);
                
                // Show error message
                messageDiv.className = 'message error';
                messageDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Connection error. Please try again.';
                messageDiv.style.display = 'block';
                
                // Re-enable button
                loginBtn.disabled = false;
                loginBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> <span>Sign In</span>';
            }
        });
        
        // Check if already logged in
        if (localStorage.getItem('swayamAdminToken')) {
            window.location.href = 'index.html';
        }
    </script>
</body>
</html>