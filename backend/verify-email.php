<?php
/**
 * Verify Email Handler
 */
require_once 'config.php';

$token = $_GET['token'] ?? '';

if (empty($token)) {
    header('Location: login.html?error=invalid_token');
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
        header('Location: login.html?error=invalid_token');
        exit;
    }
    
    // Update user status
    $stmt = $pdo->prepare("
        UPDATE users 
        SET email_verified = TRUE, status = 'active', verification_token = NULL 
        WHERE user_id = ?
    ");
    $stmt->execute([$user['user_id']]);
    
    header('Location: login.html?verified=1');
    exit;
    
} catch (Exception $e) {
    error_log("Verification error: " . $e->getMessage());
    header('Location: login.html?error=verification_failed');
    exit;
}
?>