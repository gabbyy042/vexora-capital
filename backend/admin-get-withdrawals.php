<?php
require_once 'config.php';
require_once 'functions.php';
require 'check-admin-session.php';

try {
    $stmt = $pdo->prepare("
        SELECT w.*, u.full_name, u.email 
        FROM withdrawals w
        JOIN users u ON w.user_id = u.user_id
        ORDER BY w.created_at DESC
    ");
    $stmt->execute();
    $withdrawals = $stmt->fetchAll();

    successResponse('Withdrawals retrieved', ['withdrawals' => $withdrawals]);

} catch (Exception $e) {
    error_log("Admin get withdrawals error: " . $e->getMessage());
    errorResponse('Failed to retrieve withdrawals');
}
?>