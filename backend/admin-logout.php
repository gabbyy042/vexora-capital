<?php
session_start();

if (isset($_SESSION['admin_id'])) {
    require_once 'config.php';
    require_once 'functions.php';
    logActivity(null, $_SESSION['admin_id'], 'admin_logout', 'Admin logged out');
}

session_destroy();
header('Location: ../admin-login.html?message=logged_out');
exit;
?>