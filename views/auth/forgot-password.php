<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Swayam</title>
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
                <h1>Reset Password</h1>
                <p>Enter your email to receive reset instructions</p>
            </div>
            
            <form id="forgotPasswordForm" class="auth-form">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <button type="submit" class="btn-primary">Send Reset Link</button>
                
                <div class="auth-links">
                    <a href="login.html">Back to Login</a>
                    <a href="register.html">Don't have an account? Sign up</a>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Password reset functionality will be implemented with email service. For now, please contact admin.');
        });
    </script>
</body>
</html>