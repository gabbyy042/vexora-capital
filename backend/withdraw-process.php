<?php
require_once 'config.php';
require_once 'functions.php';
require 'check-session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse('Invalid request method', 405);
}

$userId = $_SESSION['user_id'];
$cryptoType = sanitize($_POST['crypto_type'] ?? '');
$amount = (float)($_POST['amount'] ?? 0);
$walletAddress = sanitize($_POST['wallet_address'] ?? '');

$allowedCryptos = ['BTC', 'ETH', 'USDT'];
if (!in_array($cryptoType, $allowedCryptos)) {
    errorResponse('Invalid cryptocurrency type');
}

if ($amount < MIN_WITHDRAWAL) {
    errorResponse('Minimum withdrawal is $' . MIN_WITHDRAWAL);
}

if (empty($walletAddress)) {
    errorResponse('Wallet address is required');
}

$user = getCurrentUser();
if (!$user) {
    errorResponse('User not found');
}

$fee = ($amount * WITHDRAWAL_FEE_PERCENT) / 100;
$netAmount = $amount - $fee;

if ($user['balance'] < $amount) {
    errorResponse('Insufficient balance');
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("
        INSERT INTO withdrawals 
        (user_id, crypto_type, amount, fee, net_amount, wallet_address, status) 
        VALUES (?, ?, ?, ?, ?, ?, 'pending')
    ");

    $stmt->execute([
        $userId,
        $cryptoType,
        $amount,
        $fee,
        $netAmount,
        $walletAddress
    ]);

    $withdrawalId = $pdo->lastInsertId();

    $result = updateUserBalance($userId, -$amount, 'withdrawal', "Withdrawal request: $$amount", 'withdrawal', $withdrawalId);

    if (!$result['success']) {
        $pdo->rollBack();
        errorResponse($result['message']);
    }

    logActivity($userId, null, 'withdrawal_requested', "Withdrawal of $$amount $cryptoType requested");

    $pdo->commit();

    successResponse('Withdrawal request submitted! Pending admin approval.', [
        'withdrawal_id' => $withdrawalId,
        'fee' => $fee,
        'net_amount' => $netAmount
    ]);

} catch (Exception $e) {
    $pdo->rollBack();
    error_log("Withdrawal error: " . $e->getMessage());
    errorResponse('Failed to submit withdrawal request');
}
?>