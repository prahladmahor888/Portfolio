<?php
/**
 * Error Page Handler
 * Handles display of various HTTP error codes
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/helpers/helpers.php';

// Get status code from server redirect, query parameter, or global variable (from handler)
if (!isset($code)) {
    $code = $_SERVER['REDIRECT_STATUS'] ?? $_GET['code'] ?? 404;
}
$code = intval($code);

// Valid error codes
$validCodes = [400, 401, 403, 404, 500, 503];
if (!in_array($code, $validCodes)) {
    $code = 404;
}

// Set response code
http_response_code($code);

// Error details
$errors = [
    400 => [
        'title' => 'Bad Request',
        'message' => 'The server cannot or will not process the request due to an apparent client error.',
        'icon' => 'fa-exclamation-circle'
    ],
    401 => [
        'title' => 'Unauthorized',
        'message' => 'Authentication is required and has failed or has not yet been provided.',
        'icon' => 'fa-lock'
    ],
    403 => [
        'title' => 'Forbidden',
        'message' => 'You do not have permission to access the requested resource.',
        'icon' => 'fa-ban'
    ],
    404 => [
        'title' => 'Page Not Found',
        'message' => 'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.',
        'icon' => 'fa-search'
    ],
    500 => [
        'title' => 'Internal Server Error',
        'message' => 'The server encountered an unexpected condition that prevented it from fulfilling the request.',
        'icon' => 'fa-server'
    ],
    503 => [
        'title' => 'Service Unavailable',
        'message' => 'The server is currently unable to handle the request due to a temporary overload or scheduled maintenance.',
        'icon' => 'fa-tools'
    ]
];

$error = $errors[$code];
$pageTitle = $code . ' - ' . $error['title'];
?>

<?php include __DIR__ . '/../includes/header.php'; ?>
<?php // We don't need navbar on serious errors, but for 404/403 it keeps navigation easy. Let's include it. ?>
<?php include __DIR__ . '/../includes/navbar.php'; ?>

<section class="section" style="min-height: 80vh; display: flex; align-items: center; padding-top: 100px;">
    <div class="container">
        <div class="glass-card text-center" style="padding: 4rem 2rem; max-width: 800px; margin: 0 auto;">
            <div style="font-size: 5rem; color: var(--color-accent); margin-bottom: 1.5rem;">
                <i class="fas <?php echo $error['icon']; ?>"></i>
            </div>
            
            <h1 style="font-size: 3rem; margin-bottom: 1rem; line-height: 1;">
                <?php echo $code; ?>
            </h1>
            
            <h2 style="font-size: 2rem; margin-bottom: 1.5rem; color: var(--color-text);">
                <?php echo $error['title']; ?>
            </h2>
            
            <p style="font-size: 1.25rem; color: var(--color-text-muted); margin-bottom: 2.5rem; max-width: 600px; margin-left: auto; margin-right: auto;">
                <?php echo $error['message']; ?>
            </p>
            
            <div style="display: flex; gap: 1rem; justify-content: center;">
                <a href="<?php echo SITE_URL; ?>" class="btn btn-primary">
                    <i class="fas fa-home"></i> Back to Home
                </a>
                <button onclick="history.back()" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Go Back
                </button>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
