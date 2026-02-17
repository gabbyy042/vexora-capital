<?php
require_once 'config.php';
require_once 'functions.php';
require 'check-admin-session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse('Invalid request method', 405);
}

$withdrawalId = (int)($_POST['withdrawal_id'] ?? 0);
$action = sanitize($_POST['action'] ?? '');
$txHash = sanitize($_POST['tx_hash'] ?? '');
$notes = sanitize($_POST['notes'] ?? '');

if ($withdrawalId <= 0 || !in_array($action, ['complete', 'reject'])) {
    errorResponse('Invalid parameters');
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT * FROM withdrawals WHERE withdrawal_id = ?");
    $stmt->execute([$withdrawalId]);
    $withdrawal = $stmt->fetch();

    if (!$withdrawal) {
        $pdo->rollBack();
        errorResponse('Withdrawal not found');
    }

    if ($withdrawal['status'] !== 'pending') {
        $pdo->rollBack();
        errorResponse('Withdrawal already processed');
    }

    if ($action === 'complete') {
        if (empty($txHash)) {
            errorResponse('Transaction hash is required');
        }

        $stmt = $pdo->prepare("
            UPDATE withdrawals 
            SET status = 'completed', completed_at = NOW(), 
                admin_processed_by = ?, tx_hash = ?, admin_notes = ? 
            WHERE withdrawal_id = ?
        ");
        $stmt->execute([$_SESSION['admin_id'], $txHash, $notes, $withdrawalId]);

        $stmt = $pdo->prepare("
            UPDATE users 
            SET total_withdrawn = total_withdrawn + ? 
            WHERE user_id = ?
        ");
        $stmt->execute([$withdrawal['amount'], $withdrawal['user_id']]);

        logActivity($withdrawal['user_id'], $_SESSION['admin_id'], 'withdrawal_completed', 
                    "Withdrawal of $$withdrawal[amount] completed");

        $message = 'Withdrawal processed successfully';

    } else {
        $stmt = $pdo->prepare("
            UPDATE withdrawals 
            SET status = 'rejected', admin_processed_by = ?, 
                rejection_reason = ?, admin_notes = ? 
            WHERE withdrawal_id = ?
        ");
        $stmt->execute([$_SESSION['admin_id'], $notes, $notes, $withdrawalId]);

        $result = updateUserBalance(
            $withdrawal['user_id'],
            $withdrawal['amount'],
            'adjustment',
            "Withdrawal rejected - balance refunded",
            'withdrawal',
            $withdrawalId
        );

        logActivity($withdrawal['user_id'], $_SESSION['admin_id'], 'withdrawal_rejected', 
                    "Withdrawal of $$withdrawal[amount] rejected");

        $message = 'Withdrawal rejected - balance refunded';
    }

    $pdo->commit();
    successResponse($message);

} catch (Exception $e) {
    $pdo->rollBack();
    error_log("Admin process withdrawal error: " . $e->getMessage());
    errorResponse('Failed to process withdrawal');
}
?>