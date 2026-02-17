<?php
require_once 'config.php';
require_once 'functions.php';

if (!isLoggedIn()) {
    header('Location: ../login.html');
    exit;
}

$timeout = 1800;
if (isset($_SESSION['logged_in_at']) && (time() - $_SESSION['logged_in_at']) > $timeout) {
    session_destroy();
    header('Location: ../login.html?error=session_expired');
    exit;
}

$_SESSION['logged_in_at'] = time();
?>