<?php
/**
 * VEXORA CAPITAL - Main Configuration File
 * ⚠️ UPDATE ALL VALUES BELOW FOR YOUR SERVER!
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('UTC');

// ============================================
// DATABASE CONFIGURATION
// ⚠️ CHANGE THESE VALUES!
// ============================================
define('DB_HOST', 'sql201.infinityfree.com'); // Your MySQL host
define('DB_NAME', 'if0_40943082_vexora_db'); // Your database name
define('DB_USER', 'if0_40943082'); // Your database username
define('DB_PASS', 'YOUR_DATABASE_PASSWORD_HERE'); // ⚠️ CHANGE THIS!

// Database connection
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// ============================================
// SITE CONFIGURATION
// ⚠️ CHANGE YOUR_DOMAIN!
// ============================================
define('SITE_URL', 'http://vexoracapital.rf.gd'); // ⚠️ Change to YOUR domain
define('SITE_NAME', 'VEXORA CAPITAL');
define('SUPPORT_EMAIL', 'support@vexoracapital.com');
define('ADMIN_EMAIL', 'admin@vexoracapital.com');

// ============================================
// EMAIL CONFIGURATION (Gmail SMTP)
// ⚠️ USE GMAIL APP PASSWORD!
// ============================================
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your-email@gmail.com'); // ⚠️ Your Gmail
define('SMTP_PASS', 'your-16-char-app-password'); // ⚠️ Gmail App Password
define('FROM_EMAIL', 'noreply@vexoracapital.com');
define('FROM_NAME', 'VEXORA CAPITAL');

// ============================================
// CRYPTO WALLET ADDRESSES (Pre-configured)
// ✅ These are YOUR real wallet addresses
// ============================================
define('BTC_WALLET', 'bc1qz69wrtc6nkrqhxmta0h7jzv6wcut8pshaus3hw');
define('ETH_WALLET', '0xC8E8029Fe2Ea976394563F7a650f417b4dA5bAfE');
define('USDT_TRC20_WALLET', 'TGLf3LMQ5FCjk5HuJx7gmmPLVWWkAxgXLD');

// ============================================
// INVESTMENT PLANS
// ============================================
define('INVESTMENT_PLANS', json_encode([
    'basic' => [
        'name' => 'Basic',
        'min' => 50,
        'max' => 499,
        'profit' => 20,
        'duration' => 24,
        'referral_bonus' => 5
    ],
    'bronze' => [
        'name' => 'Bronze',
        'min' => 500,
        'max' => 999,
        'profit' => 35,
        'duration' => 48,
        'referral_bonus' => 10
    ],
    'standard' => [
        'name' => 'Standard',
        'min' => 1000,
        'max' => 1999,
        'profit' => 80,
        'duration' => 92,
        'referral_bonus' => 10
    ],
    'gold' => [
        'name' => 'Gold',
        'min' => 2000,
        'max' => 2499,
        'profit' => 100,
        'duration' => 48,
        'referral_bonus' => 10
    ],
    'company_shares' => [
        'name' => 'Company Shares',
        'min' => 6000,
        'max' => 1000000,
        'profit' => 120,
        'duration' => 72,
        'referral_bonus' => 15
    ],
    'real_estate' => [
        'name' => 'Real Estate',
        'min' => 10000,
        'max' => PHP_INT_MAX,
        'profit' => 150,
        'duration' => 48,
        'referral_bonus' => 15
    ]
]));

// Financial settings
define('MIN_DEPOSIT', 50);
define('MIN_WITHDRAWAL', 50);
define('WITHDRAWAL_FEE_PERCENT', 2);

// ============================================
// SECURITY SETTINGS
// ============================================
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS
ini_set('session.cookie_lifetime', 1800);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Security headers
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");

// ============================================
// HELPER FUNCTIONS
// ============================================
function getSetting($key, $default = null) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
    $stmt->execute([$key]);
    $result = $stmt->fetch();
    return $result ? $result['setting_value'] : $default;
}

function setSetting($key, $value) {
    global $pdo;
    $stmt = $pdo->prepare("
        INSERT INTO settings (setting_key, setting_value) 
        VALUES (?, ?) 
        ON DUPLICATE KEY UPDATE setting_value = ?
    ");
    return $stmt->execute([$key, $value, $value]);
}
?>