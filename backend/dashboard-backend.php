<?php
require_once 'config.php';
require_once 'functions.php';

if (!isLoggedIn()) {
    errorResponse('Not logged in', 401);
}

try {
    $userId = $_SESSION['user_id'];
    $user = getCurrentUser();

    if (!$user) {
        errorResponse('User not found', 404);
    }

    $stats = [
        'balance' => (float)$user['balance'],
        'total_deposited' => (float)$user['total_deposited'],
        'total_invested' => (float)$user['total_invested'],
        'total_profit' => (float)$user['total_profit'],
        'total_withdrawn' => (float)$user['total_withdrawn'],
        'referral_earnings' => (float)$user['referral_earnings']
    ];

    $stmt = $pdo->prepare("
        SELECT * FROM investments 
        WHERE user_id = ? AND status = 'active' 
        ORDER BY start_date DESC
    ");
    $stmt->execute([$userId]);
    $activeInvestments = $stmt->fetchAll();

    $stmt = $pdo->prepare("
        SELECT * FROM transactions 
        WHERE user_id = ? 
        ORDER BY created_at DESC 
        LIMIT 10
    ");
    $stmt->execute([$userId]);
    $recentTransactions = $stmt->fetchAll();

    $stmt = $pdo->prepare("
        SELECT COUNT(*) as count FROM deposits 
        WHERE user_id = ? AND status = 'pending'
    ");
    $stmt->execute([$userId]);
    $pendingDeposits = $stmt->fetch()['count'];

    $stmt = $pdo->prepare("
        SELECT COUNT(*) as count FROM withdrawals 
        WHERE user_id = ? AND status = 'pending'
    ");
    $stmt->execute([$userId]);
    $pendingWithdrawals = $stmt->fetch()['count'];

    successResponse('Dashboard data retrieved', [
        'user' => [
            'full_name' => $user['full_name'],
            'email' => $user['email'],
            'referral_code' => $user['referral_code']
        ],
        'stats' => $stats,
        'active_investments' => $activeInvestments,
        'recent_transactions' => $recentTransactions,
        'pending_deposits' => $pendingDeposits,
        'pending_withdrawals' => $pendingWithdrawals
    ]);

} catch (Exception $e) {
    error_log("Dashboard error: " . $e->getMessage());
    errorResponse('Failed to load dashboard data');
}
?>