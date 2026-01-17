<?php
/**
 * Admin Blogs Management
 */

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/app/helpers/helpers.php';
require_once dirname(__DIR__) . '/app/models/Blog.php';

requireAuth();

$blogModel = new Blog();
$currentUser = getCurrentUser();

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Delete associated image
    $blog = $blogModel->findById($id);
    if ($blog && $blog['image']) {
        deleteFile($blog['image']);
    }
    
    if ($blogModel->delete($id)) {
        setFlash('success', 'Blog post deleted successfully');
    } else {
        setFlash('error', 'Failed to delete blog post');
    }
    
    header('Location: blogs.php');
    exit;
}

// Get all blogs
$blogs = $blogModel->all([], 'created_at DESC');

$pageTitle = 'Blog Management';
include dirname(__DIR__) . '/includes/admin-header.php';
?>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">📝 Blog Management</h1>
        <p style="color: var(--admin-text-light); margin-top: 0.5rem;">Manage your blog posts</p>
    </div>
    <a href="blog-edit.php" class="btn btn-primary">
        <span>➕</span>
        <span>Add New Blog</span>
    </a>
</div>

<!-- Blogs Table -->
<div class="data-table">
    <div class="table-header">
        <h2 class="table-title">All Blog Posts (<?php echo count($blogs); ?>)</h2>
    </div>
    
    <?php if (empty($blogs)): ?>
        <div style="padding: 4rem 2rem; text-align: center; color: var(--admin-text-light);">
            <div style="font-size: 5rem; margin-bottom: 1rem; opacity: 0.3;">📝</div>
            <h3 style="margin-bottom: 0.5rem;">No Blog Posts Yet</h3>
            <p style="margin-bottom: 1.5rem;">Start sharing your knowledge and insights</p>
            <a href="blog-edit.php" class="btn btn-primary btn-lg">
                <span>✍️</span>
                <span>Write Your First Blog Post</span>
            </a>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Blog Post</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Date</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($blogs as $blog): ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <?php if ($blog['image']): ?>
                                    <img src="<?php echo getFileUrl($blog['image']); ?>" alt="" class="thumb-sm">
                                <?php else: ?>
                                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-accent) 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem;">
                                        📄
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <strong style="display: block; margin-bottom: 0.25rem;"><?php echo clean($blog['title']); ?></strong>
                                    <div style="font-size: 0.875rem; color: var(--admin-text-light);">
                                        <?php echo clean(truncate(strip_tags($blog['content']), 60)); ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php if ($blog['category']): ?>
                                <span style="background: rgba(79, 70, 229, 0.1); color: var(--admin-primary); padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                                    <?php echo clean($blog['category']); ?>
                                </span>
                            <?php else: ?>
                                <span style="color: var(--admin-text-light);">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($blog['status'] === 'published'): ?>
                                <span class="badge-active">Published</span>
                            <?php else: ?>
                                <span class="badge-inactive">Draft</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span style="display: flex; align-items: center; gap: 0.25rem;">
                                <span>👁️</span>
                                <span><?php echo number_format($blog['views']); ?></span>
                            </span>
                        </td>
                        <td><?php echo formatDate($blog['created_at'], 'M d, Y'); ?></td>
                        <td>
                            <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                <a href="blog-edit.php?id=<?php echo $blog['id']; ?>" class="btn btn-sm btn-outline" title="Edit">
                                    ✏️ Edit
                                </a>
                                <a href="?action=delete&id=<?php echo $blog['id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this blog post?')"
                                   title="Delete">
                                    🗑️
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- Stats Summary -->
<?php if (!empty($blogs)): ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 2rem;">
        <div style="background: white; padding: 1.5rem; border-radius: 12px; border: 1px solid var(--admin-border);">
            <div style="font-size: 0.875rem; color: var(--admin-text-light); margin-bottom: 0.5rem;">Total Posts</div>
            <div style="font-size: 2rem; font-weight: 700; color: var(--admin-primary);"><?php echo count($blogs); ?></div>
        </div>
        <div style="background: white; padding: 1.5rem; border-radius: 12px; border: 1px solid var(--admin-border);">
            <div style="font-size: 0.875rem; color: var(--admin-text-light); margin-bottom: 0.5rem;">Published</div>
            <div style="font-size: 2rem; font-weight: 700; color: #10B981;">
                <?php echo count(array_filter($blogs, fn($b) => $b['status'] === 'published')); ?>
            </div>
        </div>
        <div style="background: white; padding: 1.5rem; border-radius: 12px; border: 1px solid var(--admin-border);">
            <div style="font-size: 0.875rem; color: var(--admin-text-light); margin-bottom: 0.5rem;">Drafts</div>
            <div style="font-size: 2rem; font-weight: 700; color: #F59E0B;">
                <?php echo count(array_filter($blogs, fn($b) => $b['status'] === 'draft')); ?>
            </div>
        </div>
        <div style="background: white; padding: 1.5rem; border-radius: 12px; border: 1px solid var(--admin-border);">
            <div style="font-size: 0.875rem; color: var(--admin-text-light); margin-bottom: 0.5rem;">Total Views</div>
            <div style="font-size: 2rem; font-weight: 700; color: var(--admin-accent);">
                <?php echo number_format(array_sum(array_column($blogs, 'views'))); ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php include dirname(__DIR__) . '/includes/admin-footer.php'; ?>
