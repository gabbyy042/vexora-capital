<?php
require_once 'config.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse('Invalid request method', 405);
}

$fullName = sanitize($_POST['full_name'] ?? '');
$email = sanitize($_POST['email'] ?? '');
$phone = sanitize($_POST['phone'] ?? '');
$country = sanitize($_POST['country'] ?? '');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';
$referralCode = sanitize($_POST['referral_code'] ?? '');

if (empty($fullName) || empty($email) || empty($password)) {
    errorResponse('All fields are required');
}

if (!validateEmail($email)) {
    errorResponse('Invalid email address');
}

if (strlen($password) < 8) {
    errorResponse('Password must be at least 8 characters');
}

if ($password !== $confirmPassword) {
    errorResponse('Passwords do not match');
}

$stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    errorResponse('Email already registered');
}

$referrerId = null;
if (!empty($referralCode)) {
    $referrerId = getReferrerByCode($referralCode);
}

try {
    $pdo->beginTransaction();

    $passwordHash = hashPassword($password);
    $userReferralCode = generateReferralCode();
    $verificationToken = generateToken();

    $stmt = $pdo->prepare("
        INSERT INTO users 
        (full_name, email, phone, country, password_hash, referral_code, 
         verification_token, referred_by, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'unverified')
    ");

    $stmt->execute([
        $fullName,
        $email,
        $phone,
        $country,
        $passwordHash,
        $userReferralCode,
        $verificationToken,
        $referrerId
    ]);

    $newUserId = $pdo->lastInsertId();

    if ($referrerId) {
        $stmt = $pdo->prepare("
            INSERT INTO referrals 
            (referrer_user_id, referred_user_id, commission_rate, status) 
            VALUES (?, ?, 10, 'pending')
        ");
        $stmt->execute([$referrerId, $newUserId]);
    }

    logActivity($newUserId, null, 'user_registered', "New user registered: {$email}");

    $pdo->commit();

    sendVerificationEmail($newUserId, $email, $verificationToken);

    successResponse('Registration successful! Please check your email to verify your account.', [
        'email' => $email
    ]);

} catch (Exception $e) {
    $pdo->rollBack();
    error_log("Registration error: " . $e->getMessage());
    errorResponse('Registration failed. Please try again.');
}
?>