<?php
require_once '../includes/config.php';

// Destroy session
session_unset();
session_destroy();

// Redirect to login page
redirect(BASE_URL . 'admin/login.php');
?>