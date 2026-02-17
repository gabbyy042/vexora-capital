<?php
require_once 'config.php';
require_once 'functions.php';

$token = sanitize($_GET['token'] ?? '');

if (empty($token)) {
    header('Location: ../login.html?error=invalid_token');
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT user_id, email, full_name 
        FROM users 
        WHERE verification_token = ? AND status = 'unverified'
    ");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if (!$user) {
        header('Location: ../login.html?error=invalid_token');
        exit;
    }

    $stmt = $pdo->prepare("
        UPDATE users 
        SET email_verified = TRUE, 
            status = 'active', 
            verification_token = NULL 
        WHERE user_id = ?
    ");
    $stmt->execute([$user['user_id']]);

    logActivity($user['user_id'], null, 'email_verified', 'User verified email');

    header('Location: ../verify-email-success.html');
    exit;

} catch (Exception $e) {
    error_log("Email verification error: " . $e->getMessage());
    header('Location: ../login.html?error=verification_failed');
    exit;
}
?>