<?php
require_once 'config.php';
require_once 'functions.php';
require 'check-admin-session.php';

try {
    $stmt = $pdo->prepare("
        SELECT user_id, full_name, email, balance, total_deposited, 
               total_invested, total_withdrawn, status, created_at, last_login
        FROM users 
        ORDER BY created_at DESC
    ");
    $stmt->execute();
    $users = $stmt->fetchAll();

    successResponse('Users retrieved', ['users' => $users]);

} catch (Exception $e) {
    error_log("Admin get users error: " . $e->getMessage());
    errorResponse('Failed to retrieve users');
}
?>