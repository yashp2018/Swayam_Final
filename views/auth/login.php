<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Swayam</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="logo-icon">
                    <span class="logo-symbol">स्व</span>
                </div>
                <h1>Welcome Back</h1>
                <p>Sign in to your Swayam account</p>
            </div>
            
            <form id="loginForm" class="auth-form">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn-primary">Sign In</button>
                
                <div class="auth-links">
                    <a href="register.html">Don't have an account? Sign up</a>
                    <a href="forgot-password.html">Forgot password?</a>
                </div>
            </form>
        </div>
    </div>
    
    <script src="public/js/auth.js"></script>
</body>
</html>