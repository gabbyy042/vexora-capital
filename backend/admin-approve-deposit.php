<?php
require_once 'config.php';
require_once 'functions.php';
require 'check-admin-session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse('Invalid request method', 405);
}

$depositId = (int)($_POST['deposit_id'] ?? 0);
$action = sanitize($_POST['action'] ?? '');
$notes = sanitize($_POST['notes'] ?? '');

if ($depositId <= 0 || !in_array($action, ['approve', 'reject'])) {
    errorResponse('Invalid parameters');
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT * FROM deposits WHERE deposit_id = ?");
    $stmt->execute([$depositId]);
    $deposit = $stmt->fetch();

    if (!$deposit) {
        $pdo->rollBack();
        errorResponse('Deposit not found');
    }

    if ($deposit['status'] !== 'pending') {
        $pdo->rollBack();
        errorResponse('Deposit already processed');
    }

    if ($action === 'approve') {
        $stmt = $pdo->prepare("
            UPDATE deposits 
            SET status = 'completed', approved_at = NOW(), 
                admin_approved_by = ?, admin_notes = ? 
            WHERE deposit_id = ?
        ");
        $stmt->execute([$_SESSION['admin_id'], $notes, $depositId]);

        $result = updateUserBalance(
            $deposit['user_id'],
            $deposit['amount'],
            'deposit',
            "Deposit approved: {$deposit['crypto_type']} transaction",
            'deposit',
            $depositId
        );

        if (!$result['success']) {
            $pdo->rollBack();
            errorResponse($result['message']);
        }

        $stmt = $pdo->prepare("
            UPDATE users 
            SET total_deposited = total_deposited + ? 
            WHERE user_id = ?
        ");
        $stmt->execute([$deposit['amount'], $deposit['user_id']]);

        logActivity($deposit['user_id'], $_SESSION['admin_id'], 'deposit_approved', 
                    "Deposit of $$deposit[amount] approved");

        $message = 'Deposit approved successfully';

    } else {
        $stmt = $pdo->prepare("
            UPDATE deposits 
            SET status = 'rejected', admin_approved_by = ?, admin_notes = ? 
            WHERE deposit_id = ?
        ");
        $stmt->execute([$_SESSION['admin_id'], $notes, $depositId]);

        logActivity($deposit['user_id'], $_SESSION['admin_id'], 'deposit_rejected', 
                    "Deposit of $$deposit[amount] rejected");

        $message = 'Deposit rejected';
    }

    $pdo->commit();
    successResponse($message);

} catch (Exception $e) {
    $pdo->rollBack();
    error_log("Admin approve deposit error: " . $e->getMessage());
    errorResponse('Failed to process deposit');
}
?>