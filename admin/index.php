<?php
/**
 * Admin Index Redirection
 * Redirects to dashboard if logged in, otherwise requireAuth handles redirect to login
 */

require_once dirname(__DIR__) . '/config/auth.php';

// If we are here and not redirected by requireAuth(), it means we are logged in.
// requireAuth() is called inside here? No, requireAuth checks and redirects if NOT logged in.
// So we call it first.

requireAuth();

// If we pass requireAuth, we are logged in.
header('Location: dashboard.php');
exit;
?>
