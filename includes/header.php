<?php
/**
 * Header Template - Public Website
 */

if (!defined('APP_ENV')) {
    require_once dirname(__DIR__) . '/config/config.php';
    require_once dirname(__DIR__) . '/app/helpers/helpers.php';
}

$pageTitleFull = ($pageTitle ?? 'Home') . ' - ' . ($settings['site_name'] ?? 'My Portfolio');
$metaDesc = $metaDescription ?? ($settings['site_description'] ?? '');
$metaKeywords = $settings['meta_keywords'] ?? '';
$metaAuthor = $settings['meta_author'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo clean($metaDesc); ?>">
    <meta name="keywords" content="<?php echo clean($metaKeywords); ?>">
    <meta name="author" content="<?php echo clean($metaAuthor); ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo clean($pageTitleFull); ?>">
    <meta property="og:description" content="<?php echo clean($metaDesc); ?>">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:title" content="<?php echo clean($pageTitleFull); ?>">
    <meta property="twitter:description" content="<?php echo clean($metaDesc); ?>">
    
    <title><?php echo clean($pageTitleFull); ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    
    <!-- Stylesheet -->
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous">

</head>
<body>
