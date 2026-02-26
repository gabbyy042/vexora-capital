<?php
/**
 * Backend: User Registration Handler
 */
require_once 'config.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Invalid request'], 405);
}

$fullName = sanitize($_POST['full_name'] ?? '');
$email = sanitize($_POST['email'] ?? '');
$phone = sanitize($_POST['phone'] ?? '');
$country = sanitize($_POST['country'] ?? '');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

// Validation
if (empty($fullName) || empty($email) || empty($password)) {
    jsonResponse(['success' => false, 'message' => 'All fields required']);
}

if ($password !== $confirmPassword) {
    jsonResponse(['success' => false, 'message' => 'Passwords do not match']);
}

if (strlen($password) < 8) {
    jsonResponse(['success' => false, 'message' => 'Password must be 8+ characters']);
}

// Check if email exists
$stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    jsonResponse(['success' => false, 'message' => 'Email already registered']);
}

try {
    // Generate tokens
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $referralCode = strtoupper(substr(md5(uniqid()), 0, 8));
    $verificationToken = bin2hex(random_bytes(32));
    
    // Insert user
    $stmt = $pdo->prepare("
        INSERT INTO users 
        (full_name, email, phone, country, password_hash, referral_code, verification_token, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, 'unverified')
    ");
    
    $stmt->execute([$fullName, $email, $phone, $country, $passwordHash, $referralCode, $verificationToken]);
    
    // Send verification email
    $verifyLink = SITE_URL . "/verify-email.php?token=" . $verificationToken;
    $subject = "Verify Your Email - " . SITE_NAME;
    $body = "Click here to verify: <a href='$verifyLink'>$verifyLink</a>";
    mail($email, $subject, $body, "From: " . SUPPORT_EMAIL);
    
    jsonResponse([
        'success' => true,
        'message' => 'Registration successful! Check your email to verify.',
        'email' => $email
    ]);
    
} catch (Exception $e) {
    error_log("Registration error: " . $e->getMessage());
    jsonResponse(['success' => false, 'message' => 'Registration failed']);
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
?>