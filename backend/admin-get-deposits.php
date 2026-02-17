<?php
require_once 'config.php';
require_once 'functions.php';
require 'check-admin-session.php';

try {
    $stmt = $pdo->prepare("
        SELECT d.*, u.full_name, u.email 
        FROM deposits d
        JOIN users u ON d.user_id = u.user_id
        ORDER BY d.created_at DESC
    ");
    $stmt->execute();
    $deposits = $stmt->fetchAll();

    successResponse('Deposits retrieved', ['deposits' => $deposits]);

} catch (Exception $e) {
    error_log("Admin get deposits error: " . $e->getMessage());
    errorResponse('Failed to retrieve deposits');
}
?>