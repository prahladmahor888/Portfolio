<?php
/**
 * Admin Header - Modern Navigation
 */

if (!defined('APP_ENV')) {
    exit('Direct access not allowed');
}

$currentUser = getCurrentUser();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');

// Get unread message count
require_once dirname(__DIR__) . '/app/models/Message.php';
$messageModel = new Message();
$unreadCount = $messageModel->countUnread();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Admin'; ?> - Portfolio Admin</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Admin Styles -->
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/admin.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar Navigation -->
        <aside class="admin-sidebar" id="adminSidebar">
            <!-- Logo -->
            <div class="admin-logo">
                <span class="admin-logo-icon">🚀</span>
                <h2>Portfolio Admin</h2>
            </div>
            
            <!-- Navigation -->
            <nav class="admin-nav">
                <!-- Main Section -->
                <div class="nav-section">
                    <div class="nav-section-title">Main</div>
                    <a href="dashboard.php" class="nav-item <?php echo $currentPage === 'dashboard' ? 'active' : ''; ?>">
                        <span class="nav-icon">📊</span>
                        <span>Dashboard</span>
                    </a>
                    <a href="messages.php" class="nav-item <?php echo $currentPage === 'messages' ? 'active' : ''; ?>">
                        <span class="nav-icon">✉️</span>
                        <span>Messages</span>
                        <?php if ($unreadCount > 0): ?>
                            <span class="badge"><?php echo $unreadCount; ?></span>
                        <?php endif; ?>
                    </a>
                </div>
                
                <!-- Content Section -->
                <div class="nav-section">
                    <div class="nav-section-title">Content</div>
                    <a href="projects.php" class="nav-item <?php echo $currentPage === 'projects' ? 'active' : ''; ?>">
                        <span class="nav-icon">📁</span>
                        <span>Projects</span>
                    </a>
                    <a href="blogs.php" class="nav-item <?php echo $currentPage === 'blogs' ? 'active' : ''; ?>">
                        <span class="nav-icon">📝</span>
                        <span>Blog Posts</span>
                    </a>
                    <a href="services.php" class="nav-item <?php echo $currentPage === 'services' ? 'active' : ''; ?>">
                        <span class="nav-icon">⚙️</span>
                        <span>Services</span>
                    </a>
                    <a href="skills.php" class="nav-item <?php echo $currentPage === 'skills' ? 'active' : ''; ?>">
                        <span class="nav-icon">🎯</span>
                        <span>Skills</span>
                    </a>
                </div>
                
                <!-- Settings Section -->
                <div class="nav-section">
                    <div class="nav-section-title">Settings</div>
                    <a href="settings.php" class="nav-item <?php echo $currentPage === 'settings' ? 'active' : ''; ?>">
                        <span class="nav-icon">⚙️</span>
                        <span>Site Settings</span>
                    </a>
                </div>
            </nav>
            
            <!-- Sidebar Footer -->
            <div class="sidebar-footer">
                <a href="auth/logout.php" class="nav-item" style="color: rgba(255, 255, 255, 0.7);">
                    <span class="nav-icon">🚪</span>
                    <span>Logout</span>
                </a>
            </div>
        </aside>
        
        <!-- Main Content Area -->
        <main class="admin-main">
            <!-- Top Bar -->
            <header class="admin-topbar">
                <div class="topbar-left">
                    <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
                    <h1 class="topbar-title"><?php echo $pageTitle ?? 'Admin Panel'; ?></h1>
                </div>
                
                <div class="topbar-right">
                    <a href="<?php echo SITE_URL; ?>" target="_blank" class="btn btn-sm btn-outline" title="View Website">
                        <span>👁️</span>
                        <span>View Site</span>
                    </a>
                    
                    <div class="user-info">
                        <div class="user-avatar">
                            <?php echo strtoupper(substr($currentUser['name'], 0, 1)); ?>
                        </div>
                        <div class="user-details">
                            <div class="user-name"><?php echo clean($currentUser['name']); ?></div>
                            <div class="user-role">Administrator</div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <div class="admin-content">
                <?php
                // Display flash messages
                if ($success = getFlash('success')): ?>
                    <div class="alert alert-success">
                        <strong>✓ Success!</strong> <?php echo clean($success); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($error = getFlash('error')): ?>
                    <div class="alert alert-error">
                        <strong>⚠️ Error!</strong> <?php echo clean($error); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($info = getFlash('info')): ?>
                    <div class="alert alert-info">
                        <strong>ℹ️ Info:</strong> <?php echo clean($info); ?>
                    </div>
                <?php endif; ?>
