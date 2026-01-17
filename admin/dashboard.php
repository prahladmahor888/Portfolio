<?php
/**
 * Admin Dashboard - Modern & Enhanced
 */

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/app/helpers/helpers.php';
require_once dirname(__DIR__) . '/app/models/Project.php';
require_once dirname(__DIR__) . '/app/models/Blog.php';
require_once dirname(__DIR__) . '/app/models/Service.php';
require_once dirname(__DIR__) . '/app/models/Message.php';

// Require authentication
requireAuth();

$currentUser = getCurrentUser();

// Get statistics
$projectModel = new Project();
$blogModel = new Blog();
$serviceModel = new Service();
$messageModel = new Message();

$stats = [
    'projects' => $projectModel->count(),
    'blogs' => $blogModel->count(),
    'services' => $serviceModel->count(),
    'messages' => $messageModel->countUnread()
];

// Get recent data
$recentProjects = $projectModel->all([], 'created_at DESC', 5);
$recentMessages = $messageModel->all([], 'created_at DESC', 5);

$pageTitle = 'Dashboard';
include dirname(__DIR__) . '/includes/admin-header.php';
?>

<!-- Welcome Section -->
<div class="page-header" style="background: linear-gradient(135deg, rgba(79, 70, 229, 0.1) 0%, rgba(6, 182, 212, 0.1) 100%); padding: 2rem; border-radius: 12px; margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 2rem; margin-bottom: 0.5rem;">👋 Welcome back, <?php echo clean($currentUser['name']); ?>!</h1>
            <p style="color: var(--admin-text-light); margin: 0;">Here's what's happening with your portfolio today</p>
        </div>
        <a href="<?php echo SITE_URL; ?>" target="_blank" class="btn btn-primary">
            <span>👁️</span>
            <span>View Live Site</span>
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-grid" style="margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #4F46E5 0%, #6366F1 100%); color: white; font-size: 2rem;">
            📁
        </div>
        <div class="stat-info">
            <h3><?php echo number_format($stats['projects']); ?></h3>
            <p>Total Projects</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #10B981 0%, #34D399 100%); color: white; font-size: 2rem;">
            📝
        </div>
        <div class="stat-info">
            <h3><?php echo number_format($stats['blogs']); ?></h3>
            <p>Blog Posts</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #F59E0B 0%, #FBBF24 100%); color: white; font-size: 2rem;">
            ⚙️
        </div>
        <div class="stat-info">
            <h3><?php echo number_format($stats['services']); ?></h3>
            <p>Services</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #EF4444 0%, #F87171 100%); color: white; font-size: 2rem;">
            ✉️
        </div>
        <div class="stat-info">
            <h3><?php echo number_format($stats['messages']); ?></h3>
            <p>Unread Messages</p>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: var(--admin-shadow); border: 1px solid var(--admin-border); margin-bottom: 2rem;">
    <h2 style="margin-bottom: 1rem; font-size: 1.25rem;">⚡ Quick Actions</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <a href="projects.php" class="quick-action-btn">
            <span style="font-size: 2rem;">➕</span>
            <span>Add Project</span>
        </a>
        <a href="blogs.php" class="quick-action-btn">
            <span style="font-size: 2rem;">✍️</span>
            <span>Write Blog</span>
        </a>
        <a href="services.php" class="quick-action-btn">
            <span style="font-size: 2rem;">⚙️</span>
            <span>Add Service</span>
        </a>
        <a href="settings.php" class="quick-action-btn">
            <span style="font-size: 2rem;">🔧</span>
            <span>Settings</span>
        </a>
    </div>
</div>

<!-- Recent Activity Grid -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Recent Projects -->
    <div class="data-table">
        <div class="table-header">
            <h2 class="table-title">📁 Recent Projects</h2>
            <a href="projects.php" class="btn btn-sm btn-outline">View All →</a>
        </div>
        <?php if (empty($recentProjects)): ?>
            <div style="padding: 3rem; text-align: center; color: var(--admin-text-light);">
                <div style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.5;">📁</div>
                <p style="margin: 0;">No projects yet</p>
                <a href="projects.php" class="btn btn-primary" style="margin-top: 1rem;">Create Your First Project</a>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Project</th>
                        <th>Category</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentProjects as $project): ?>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <?php if ($project['image']): ?>
                                        <img src="<?php echo getFileUrl($project['image']); ?>" alt="" class="thumb-sm">
                                    <?php else: ?>
                                        <div style="width: 60px; height: 60px; background: var(--admin-bg); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">📄</div>
                                    <?php endif; ?>
                                    <strong><?php echo clean($project['title']); ?></strong>
                                </div>
                            </td>
                            <td>
                                <span style="background: rgba(79, 70, 229, 0.1); color: var(--admin-primary); padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                                    <?php echo clean($project['category'] ?? 'General'); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge-<?php echo $project['status'] === 'active' ? 'active' : 'inactive'; ?>">
                                    <?php echo ucfirst($project['status']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Recent Messages -->
    <div class="data-table">
        <div class="table-header">
            <h2 class="table-title">✉️ Recent Messages</h2>
            <a href="messages.php" class="btn btn-sm btn-outline">View All →</a>
        </div>
        <?php if (empty($recentMessages)): ?>
            <div style="padding: 3rem; text-align: center; color: var(--admin-text-light);">
                <div style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.5;">✉️</div>
                <p style="margin: 0;">No messages yet</p>
            </div>
        <?php else: ?>
            <div style="display: flex; flex-direction: column; gap: 1rem; padding: 1.5rem;">
                <?php foreach ($recentMessages as $message): ?>
                    <div style="display: flex; gap: 1rem; padding: 1rem; background: <?php echo $message['is_read'] ? 'white' : 'linear-gradient(to right, rgba(79, 70, 229, 0.05), transparent)'; ?>; border: 1px solid <?php echo $message['is_read'] ? 'var(--admin-border)' : '#E0E7FF'; ?>; border-radius: 8px; transition: transform 0.2s; position: relative;" onmouseover="this.style.transform='translateX(5px)'" onmouseout="this.style.transform='translateX(0)'">
                        <!-- Avatar -->
                        <div style="width: 40px; height: 40px; background: var(--admin-primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.9rem; flex-shrink: 0;">
                            <?php 
                            $initials = '';
                            $nameParts = explode(' ', $message['name']);
                            foreach($nameParts as $part) {
                                $initials .= strtoupper(substr($part, 0, 1));
                            }
                            echo substr($initials, 0, 2);
                            ?>
                        </div>
                        
                        <div style="flex: 1; min-width: 0;">
                            <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.25rem;">
                                <h4 style="margin: 0; font-size: 0.95rem; color: var(--admin-text);"><?php echo clean($message['name']); ?></h4>
                                <span style="font-size: 0.75rem; color: var(--admin-text-light);"><?php echo timeAgo($message['created_at']); ?></span>
                            </div>
                            
                            <p style="margin: 0 0 0.25rem 0; font-size: 0.85rem; color: var(--admin-text-light);"><?php echo clean($message['subject'] ?: 'No Subject'); ?></p>
                            
                            <p style="margin: 0; font-size: 0.9rem; color: var(--admin-text); line-height: 1.5; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <?php echo clean($message['message']); ?>
                            </p>
                        </div>
                        
                        <?php if (!$message['is_read']): ?>
                            <div style="position: absolute; top: 1rem; right: 1rem; width: 8px; height: 8px; background: #EF4444; border-radius: 50%;"></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Additional Styling -->
<style>
.quick-action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1.5rem;
    background: var(--admin-bg);
    border: 1px solid var(--admin-border);
    border-radius: 12px;
    text-decoration: none;
    color: var(--admin-text);
    font-weight: 500;
    transition: all 0.3s ease;
}

.quick-action-btn:hover {
    background: white;
    border-color: var(--admin-primary);
    transform: translateY(-4px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
}

@media (max-width: 768px) {
    div[style*="grid-template-columns: 1fr 1fr"] {
        grid-template-columns: 1fr !important;
    }
}
</style>

<?php include dirname(__DIR__) . '/includes/admin-footer.php'; ?>
