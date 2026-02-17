<?php
require_once 'config.php';
require_once 'functions.php';
require 'check-admin-session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse('Invalid request method', 405);
}

$userId = (int)($_POST['user_id'] ?? 0);
$amount = (float)($_POST['amount'] ?? 0);
$reason = sanitize($_POST['reason'] ?? '');

if ($userId <= 0 || $amount == 0) {
    errorResponse('Invalid parameters');
}

try {
    $result = updateUserBalance(
        $userId,
        $amount,
        'adjustment',
        "Admin adjustment: $reason",
        'admin_edit',
        $_SESSION['admin_id']
    );

    if (!$result['success']) {
        errorResponse($result['message']);
    }

    logActivity($userId, $_SESSION['admin_id'], 'balance_edited', 
                "Admin edited balance by $$amount. Reason: $reason");

    successResponse('Balance updated successfully', [
        'new_balance' => $result['balance_after']
    ]);

} catch (Exception $e) {
    error_log("Admin edit balance error: " . $e->getMessage());
    errorResponse('Failed to update balance');
}
?>