<?php
/**
 * Backend: Login Handler
 */
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Invalid request'], 405);
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    jsonResponse(['success' => false, 'message' => 'Email and password required']);
}

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if (!$user || !password_verify($password, $user['password_hash'])) {
        jsonResponse(['success' => false, 'message' => 'Invalid email or password']);
    }
    
    if (!$user['email_verified'] || $user['status'] === 'unverified') {
        jsonResponse(['success' => false, 'message' => 'Please verify your email first']);
    }
    
    if ($user['status'] === 'suspended') {
        jsonResponse(['success' => false, 'message' => 'Account suspended']);
    }
    
    // Set session
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['full_name'] = $user['full_name'];
    
    // Update last login
    $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
    $stmt->execute([$user['user_id']]);
    
    jsonResponse([
        'success' => true,
        'message' => 'Login successful',
        'redirect' => 'dashboard.html'
    ]);
    
} catch (Exception $e) {
    error_log("Login error: " . $e->getMessage());
    jsonResponse(['success' => false, 'message' => 'Login failed']);
}

function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
?>