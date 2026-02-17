<?php
require_once 'config.php';
require_once 'functions.php';

if (!isAdmin()) {
    header('Location: ../admin-login.html');
    exit;
}

$timeout = 3600;
if (isset($_SESSION['admin_logged_in_at']) && (time() - $_SESSION['admin_logged_in_at']) > $timeout) {
    session_destroy();
    header('Location: ../admin-login.html?error=session_expired');
    exit;
}

$_SESSION['admin_logged_in_at'] = time();
?>