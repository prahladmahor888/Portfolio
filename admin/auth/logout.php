<?php
/**
 * Logout Script
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/auth.php';

logoutUser();

// Redirect to login page
header('Location: login.php');
exit;
