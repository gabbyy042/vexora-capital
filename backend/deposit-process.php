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
$txHash = sanitize($_POST['tx_hash'] ?? '');

$allowedCryptos = ['BTC', 'ETH', 'USDT'];
if (!in_array($cryptoType, $allowedCryptos)) {
    errorResponse('Invalid cryptocurrency type');
}

if ($amount < MIN_DEPOSIT) {
    errorResponse('Minimum deposit is $' . MIN_DEPOSIT);
}

if (empty($txHash)) {
    errorResponse('Transaction hash is required');
}

$walletAddresses = [
    'BTC' => BTC_WALLET,
    'ETH' => ETH_WALLET,
    'USDT' => USDT_TRC20_WALLET
];

try {
    $stmt = $pdo->prepare("
        INSERT INTO deposits 
        (user_id, crypto_type, amount, wallet_address, tx_hash, status) 
        VALUES (?, ?, ?, ?, ?, 'pending')
    ");

    $stmt->execute([
        $userId,
        $cryptoType,
        $amount,
        $walletAddresses[$cryptoType],
        $txHash
    ]);

    $depositId = $pdo->lastInsertId();

    logActivity($userId, null, 'deposit_submitted', "Deposit of $$amount $cryptoType submitted");

    successResponse('Deposit submitted successfully! Waiting for admin approval.', [
        'deposit_id' => $depositId
    ]);

} catch (Exception $e) {
    error_log("Deposit error: " . $e->getMessage());
    errorResponse('Failed to submit deposit');
}
?>