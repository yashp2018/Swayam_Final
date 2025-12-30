<?php
require_once 'api/config.php';

// Hash the password 'password'
$hashedPassword = password_hash('password', PASSWORD_DEFAULT);

// Update admin user
$stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = 'admin@mail.com'");
$stmt->execute([$hashedPassword]);

echo "Admin password updated!<br>";
echo "Email: admin@mail.com<br>";
echo "Password: password<br>";
echo "<a href='login.html'>Go to Login</a>";
?>