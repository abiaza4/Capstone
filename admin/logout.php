<?php
require_once '../db.php';

// Clear remember me cookies
setcookie('user_email', '', time() - 3600, '/');
setcookie('admin_email', '', time() - 3600, '/');

// Destroy session
session_destroy();

// Redirect to home
header("Location: ../index.php");
exit;
