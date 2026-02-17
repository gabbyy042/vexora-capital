<?php
require_once 'config.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse('Invalid request method', 405);
}

$username = sanitize($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    errorResponse('Username and password are required');
}

try {
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ? AND status = 'active' LIMIT 1");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if (!$admin) {
        errorResponse('Invalid credentials');
    }

    if (!verifyPassword($password, $admin['password_hash'])) {
        errorResponse('Invalid credentials');
    }

    session_regenerate_id(true);

    $_SESSION['admin_id'] = $admin['admin_id'];
    $_SESSION['admin_username'] = $admin['username'];
    $_SESSION['admin_role'] = $admin['role'];
    $_SESSION['admin_logged_in_at'] = time();

    $stmt = $pdo->prepare("UPDATE admin_users SET last_login = NOW() WHERE admin_id = ?");
    $stmt->execute([$admin['admin_id']]);

    logActivity(null, $admin['admin_id'], 'admin_login', 'Admin logged in');

    successResponse('Login successful', [
        'redirect' => '../admin.html'
    ]);

} catch (Exception $e) {
    error_log("Admin login error: " . $e->getMessage());
    errorResponse('Login failed');
}
?>