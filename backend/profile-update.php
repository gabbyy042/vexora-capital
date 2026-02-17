<?php
require_once 'config.php';
require_once 'functions.php';
require 'check-session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse('Invalid request method', 405);
}

$userId = $_SESSION['user_id'];
$fullName = sanitize($_POST['full_name'] ?? '');
$phone = sanitize($_POST['phone'] ?? '');
$country = sanitize($_POST['country'] ?? '');

if (empty($fullName)) {
    errorResponse('Name is required');
}

try {
    $stmt = $pdo->prepare("
        UPDATE users 
        SET full_name = ?, phone = ?, country = ? 
        WHERE user_id = ?
    ");

    $stmt->execute([$fullName, $phone, $country, $userId]);

    $_SESSION['full_name'] = $fullName;

    logActivity($userId, null, 'profile_updated', 'User updated profile');

    successResponse('Profile updated successfully');

} catch (Exception $e) {
    error_log("Profile update error: " . $e->getMessage());
    errorResponse('Failed to update profile');
}
?>