<?php
/**
 * Application Configuration
 * Main configuration file for the portfolio website
 */

// Environment (development or production)
define('APP_ENV', 'development');

// Database Configuration
define('DB_HOST', 'localhost:3307');
define('DB_NAME', 'portfolio_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Site Configuration
// Determine protocol (supports HTTPS and common reverse proxy headers)
$protocol = 'http';
if (
    (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' && $_SERVER['HTTPS'] !== '') ||
    (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false) ||
    (isset($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on')
) {
    $protocol = 'https';
}
// Build SITE_URL; when running in CLI or missing HTTP_HOST, fall back to localhost
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
// Automatically detect if running on localhost (subdirectory) or production (root)
$path = ($host === 'localhost' || $host === '127.0.0.1') ? '/Portfolio' : '';
define('SITE_URL', $protocol . '://' . $host . $path);
define('ADMIN_URL', SITE_URL . '/admin');

// Directory Paths
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('ASSETS_PATH', ROOT_PATH . '/assets');
define('UPLOADS_PATH', ASSETS_PATH . '/uploads');
define('INCLUDES_PATH', ROOT_PATH . '/includes');

// URL Paths
define('ASSETS_URL', SITE_URL . '/assets');
define('UPLOADS_URL', ASSETS_URL . '/uploads');

// Session Configuration
define('SESSION_NAME', 'portfolio_session');
define('SESSION_LIFETIME', 7200); // 2 hours

// Security
define('CSRF_TOKEN_NAME', 'csrf_token');
define('PASSWORD_MIN_LENGTH', 8);

// Upload Configuration
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp']);
define('ALLOWED_DOCUMENT_TYPES', ['application/pdf']);

// Pagination
define('ITEMS_PER_PAGE', 12);
define('BLOG_PER_PAGE', 10);

// Email Configuration (for contact form)
define('CONTACT_EMAIL', 'admin@portfolio.com');
define('EMAIL_FROM_NAME', 'Portfolio Contact Form');

// Timezone
date_default_timezone_set('Asia/Kolkata');

// Error Reporting
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    // Log error
    ini_set('log_errors', 1);
    ini_set('error_log', ROOT_PATH . '/storage/logs/error.log');
}

/**
 * Universal Error Handler
 * Catches Exceptions and Fatal Errors to render the custom error page
 * instead of a white screen or default server error.
 */
function customExceptionHandler($e) {
    // Only handle if not in development mode (or if you want to test it, comment this check)
    // For this user request, we want to SHOW the error page, so we run this always or fallback.
    // However, usually we want stacks in dev. Let's make it universal for 500s.
    
    // Check if headers sent
    if (headers_sent()) {
        return;
    }
    
    // Set 500 Code
    http_response_code(500);
    
    // Define variable to be used in error.php
    $code = 500;
    
    // Include the error page logic directly
    // checking availability first
    $errorPage = PUBLIC_PATH . '/error.php';
    if (file_exists($errorPage)) {
        // We need to bypass the require_once checks in error.php or ensure variables are set
        // Since error.php requires config, and we are IN config, this might loop if not careful.
        // But invalidly, error.php does require_once.
        // Strategy: We will render a simple fallback or try to include.
        // Better: We explicitly set a global flag so error.php knows context.
        include $errorPage;
        exit;
    }
}

// Register Handler
// set_exception_handler('customExceptionHandler');

// Handle Fatal Errors (like syntax errors in other files)
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && ($error['type'] === E_ERROR || $error['type'] === E_PARSE || $error['type'] === E_CORE_ERROR || $error['type'] === E_COMPILE_ERROR)) {
        // Clear buffer
        if (ob_get_length()) {
            ob_clean();
        }
        http_response_code(500);
        $code = 500;
        if (defined('PUBLIC_PATH') && file_exists(PUBLIC_PATH . '/error.php')) {
            include PUBLIC_PATH . '/error.php';
        } else {
            echo "<h1>500 Internal Server Error</h1><p>An unexpected error occurred.</p>";
        }
        exit;
    }
});

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
}
