<?php
session_start();

if (isset($_SESSION['user_id'])) {
    require_once 'config.php';
    require_once 'functions.php';
    logActivity($_SESSION['user_id'], null, 'user_logout', 'User logged out');
}

session_destroy();

if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

header('Location: ../login.html?message=logged_out');
exit;
?>