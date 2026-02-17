<?php
require_once 'config.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse('Invalid request method', 405);
}

$email = sanitize($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    errorResponse('Email and password are required');
}

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        errorResponse('Invalid email or password');
    }

    if (!verifyPassword($password, $user['password_hash'])) {
        errorResponse('Invalid email or password');
    }

    if (!$user['email_verified'] || $user['status'] === 'unverified') {
        errorResponse('Please verify your email before logging in');
    }

    if ($user['status'] === 'suspended') {
        errorResponse('Your account has been suspended. Please contact support.');
    }

    session_regenerate_id(true);

    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['logged_in_at'] = time();

    $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
    $stmt->execute([$user['user_id']]);

    logActivity($user['user_id'], null, 'user_login', 'User logged in');

    successResponse('Login successful', [
        'redirect' => '../dashboard.html'
    ]);

} catch (Exception $e) {
    error_log("Login error: " . $e->getMessage());
    errorResponse('Login failed. Please try again.');
}
?>