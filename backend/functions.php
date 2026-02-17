<?php
/**
 * VEXORA CAPITAL - Helper Functions
 */

require_once 'config.php';

// ============================================
// USER AUTHENTICATION
// ============================================

function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ../login.html');
        exit;
    }
}

function getCurrentUser() {
    global $pdo;
    if (!isLoggedIn()) return null;

    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ? LIMIT 1");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

function isAdmin() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

function requireAdmin() {
    if (!isAdmin()) {
        header('Location: ../admin-login.html');
        exit;
    }
}

// ============================================
// PASSWORD FUNCTIONS
// ============================================

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// ============================================
// INPUT SANITIZATION
// ============================================

function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// ============================================
// REFERRAL SYSTEM
// ============================================

function generateReferralCode() {
    return strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
}

function getReferralCode($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT referral_code FROM users WHERE user_id = ?");
    $stmt->execute([$userId]);
    $result = $stmt->fetch();
    return $result ? $result['referral_code'] : null;
}

function getReferrerByCode($referralCode) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE referral_code = ?");
    $stmt->execute([$referralCode]);
    $result = $stmt->fetch();
    return $result ? $result['user_id'] : null;
}

// ============================================
// TOKEN GENERATION
// ============================================

function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = generateToken();
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// ============================================
// EMAIL FUNCTIONS
// ============================================

function sendEmail($to, $subject, $body, $isHTML = true) {
    $headers = "From: " . FROM_NAME . " <" . FROM_EMAIL . ">\r\n";
    $headers .= "Reply-To: " . SUPPORT_EMAIL . "\r\n";

    if ($isHTML) {
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    }

    return mail($to, $subject, $body, $headers);
}

function sendVerificationEmail($userId, $email, $token) {
    $verifyLink = SITE_URL . "/backend/verify-email.php?token=" . $token;

    $subject = "Verify Your Email - " . SITE_NAME;
    $body = "
    <html>
    <body style='font-family: Arial, sans-serif;'>
        <h2>Welcome to " . SITE_NAME . "!</h2>
        <p>Please verify your email address by clicking the link below:</p>
        <p><a href='{$verifyLink}' style='background: #FFD700; color: #0B1437; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;'>Verify Email</a></p>
        <p>Or copy this link: {$verifyLink}</p>
        <p>This link will expire in 24 hours.</p>
    </body>
    </html>";

    return sendEmail($email, $subject, $body);
}

// ============================================
// LOGGING
// ============================================

function logActivity($userId, $adminId, $action, $description = '', $metadata = null) {
    global $pdo;

    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
    $metadataJson = $metadata ? json_encode($metadata) : null;

    $stmt = $pdo->prepare("
        INSERT INTO activity_logs 
        (user_id, admin_id, action, description, ip_address, user_agent, metadata) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    return $stmt->execute([
        $userId,
        $adminId,
        $action,
        $description,
        $ipAddress,
        $userAgent,
        $metadataJson
    ]);
}

// ============================================
// BALANCE MANAGEMENT
// ============================================

function updateUserBalance($userId, $amount, $type, $description, $referenceType = null, $referenceId = null) {
    global $pdo;

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("SELECT balance FROM users WHERE user_id = ? FOR UPDATE");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if (!$user) {
            $pdo->rollBack();
            return ['success' => false, 'message' => 'User not found'];
        }

        $balanceBefore = $user['balance'];
        $balanceAfter = $balanceBefore + $amount;

        if ($balanceAfter < 0) {
            $pdo->rollBack();
            return ['success' => false, 'message' => 'Insufficient balance'];
        }

        $stmt = $pdo->prepare("UPDATE users SET balance = ? WHERE user_id = ?");
        $stmt->execute([$balanceAfter, $userId]);

        $stmt = $pdo->prepare("
            INSERT INTO transactions 
            (user_id, type, amount, balance_before, balance_after, description, 
             reference_type, reference_id, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'completed')
        ");
        $stmt->execute([
            $userId,
            $type,
            $amount,
            $balanceBefore,
            $balanceAfter,
            $description,
            $referenceType,
            $referenceId
        ]);

        $pdo->commit();

        return [
            'success' => true,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter
        ];

    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Balance update error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error'];
    }
}

// ============================================
// API RESPONSE
// ============================================

function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function successResponse($message, $data = null) {
    jsonResponse([
        'success' => true,
        'message' => $message,
        'data' => $data
    ]);
}

function errorResponse($message, $statusCode = 400) {
    jsonResponse([
        'success' => false,
        'message' => $message
    ], $statusCode);
}

function formatMoney($amount) {
    return '$' . number_format($amount, 2);
}

function formatDate($timestamp) {
    return date('M d, Y H:i', strtotime($timestamp));
}
?>