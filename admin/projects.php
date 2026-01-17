<?php
/**
 * Admin - Manage Projects
 */

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/app/helpers/helpers.php';
require_once dirname(__DIR__) . '/app/models/Project.php';

// Require authentication
requireAuth();

$projectModel = new Project();
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
        setFlash('error', 'Invalid request');
        redirect($_SERVER['REQUEST_URI']);
    }
    
    if (isset($_POST['delete'])) {
        $id = (int)$_POST['id'];
        $project = $projectModel->find($id);
        
        if ($project) {
            // Delete image file
            if ($project['image']) {
                deleteFile($project['image']);
            }
            
            if ($projectModel->delete($id)) {
                setFlash('success', 'Project deleted successfully');
            } else {
                setFlash('error', 'Failed to delete project');
            }
        }
        redirect(ADMIN_URL . '/projects.php');
    } elseif (isset($_POST['save'])) {
        $data = [
            'title' => sanitizeInput($_POST['title']),
            'description' => sanitizeInput($_POST['description']),
            'tech_stack' => sanitizeInput($_POST['tech_stack']),
            'category' => sanitizeInput($_POST['category']),
            'live_url' => sanitizeInput($_POST['live_url']),
            'github_url' => sanitizeInput($_POST['github_url']),
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'sort_order' => (int)$_POST['sort_order'],
            'status' => $_POST['status']
        ];
        
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $upload = uploadFile($_FILES['image'], 'projects');
            
            if ($upload['success']) {
                $data['image'] = $upload['filename'];
                
                // Delete old image if editing
                if ($id) {
                    $existing = $projectModel->find($id);
                    if ($existing && $existing['image']) {
                        deleteFile($existing['image']);
                    }
                }
            } else {
                setFlash('error', $upload['error']);
                redirect($_SERVER['REQUEST_URI']);
            }
        }
        
        if ($id) {
            // Update
            if ($projectModel->update($id, $data)) {
                setFlash('success', 'Project updated successfully');
                redirect(ADMIN_URL . '/projects.php');
            } else {
                setFlash('error', 'Failed to update project');
            }
        } else {
            // Create
            if ($projectModel->create($data)) {
                setFlash('success', 'Project created successfully');
                redirect(ADMIN_URL . '/projects.php');
            } else {
                setFlash('error', 'Failed to create project');
            }
        }
    }
}

// Get project for editing
$project = null;
if ($action === 'edit' && $id) {
    $project = $projectModel->find($id);
}

// Get all projects
$projects = $projectModel->all([], 'sort_order ASC, created_at DESC');

$pageTitle = $action === 'list' ? 'Projects' : ($action === 'new' ? 'Add Project' : 'Edit Project');
include dirname(__DIR__) . '/includes/admin-header.php';
?>

<?php if ($action === 'list'): ?>
    <div class="page-header">
        <h1>Projects</h1>
        <a href="?action=new" class="btn btn-primary">+ Add New Project</a>
    </div>
    
    <div class="data-table">
        <table>
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Tech Stack</th>
                    <th>Featured</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($projects)): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">No projects yet. Add your first project!</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($projects as $proj): ?>
                        <tr>
                            <td><?php echo $proj['sort_order']; ?></td>
                            <td>
                                <?php if ($proj['image']): ?>
                                    <img src="<?php echo getFileUrl($proj['image']); ?>" alt="" class="thumb-sm">
                                <?php else: ?>
                                    <span class="text-muted">No image</span>
                                <?php endif; ?>
                            </td>
                            <td><strong><?php echo clean($proj['title']); ?></strong></td>
                            <td><?php echo clean($proj['category'] ?? 'N/A'); ?></td>
                            <td><small><?php echo clean(truncate($proj['tech_stack'], 40)); ?></small></td>
                            <td><?php echo $proj['is_featured'] ? '⭐ Yes' : 'No'; ?></td>
                            <td><span class="badge badge-<?php echo $proj['status']; ?>"><?php echo $proj['status']; ?></span></td>
                            <td class="actions">
                                <a href="?action=edit&id=<?php echo $proj['id']; ?>" class="btn-sm">Edit</a>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this project?');">
                                    <?php echo csrfField(); ?>
                                    <input type="hidden" name="id" value="<?php echo $proj['id']; ?>">
                                    <button type="submit" name="delete" class="btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

<?php else: ?>
    <div class="page-header">
        <h1><?php echo $action === 'new' ? 'Add New' : 'Edit'; ?> Project</h1>
        <a href="?action=list" class="btn">← Back to List</a>
    </div>
    
    <div class="form-container">
        <form method="POST" enctype="multipart/form-data" class="admin-form">
            <?php echo csrfField(); ?>
            
            <div class="form-row">
                <div class="form-group col-8">
                    <label for="title">Project Title *</label>
                    <input type="text" id="title" name="title" value="<?php echo clean($project['title'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group col-4">
                    <label for="category">Category</label>
                    <input type="text" id="category" name="category" value="<?php echo clean($project['category'] ?? ''); ?>" placeholder="e.g., Web App">
                </div>
            </div>
            
            <div class="form-group">
                <label for="description">Description *</label>
                <textarea id="description" name="description" rows="4" required><?php echo clean($project['description'] ?? ''); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="tech_stack">Tech Stack (comma-separated) *</label>
                <input type="text" id="tech_stack" name="tech_stack" value="<?php echo clean($project['tech_stack'] ?? ''); ?>" placeholder="React, Node.js, MongoDB" required>
            </div>
            
            <div class="form-row">
                <div class="form-group col-6">
                    <label for="live_url">Live URL</label>
                    <input type="url" id="live_url" name="live_url" value="<?php echo clean($project['live_url'] ?? ''); ?>" placeholder="https://">
                </div>
                
                <div class="form-group col-6">
                    <label for="github_url">GitHub URL</label>
                    <input type="url" id="github_url" name="github_url" value="<?php echo clean($project['github_url'] ?? ''); ?>" placeholder="https://github.com/">
                </div>
            </div>
            
            <div class="form-group">
                <label for="image">Project Image (JPG, PNG, WebP - Max 5MB)</label>
                <input type="file" id="image" name="image" accept="image/*">
                <?php if ($project && $project['image']): ?>
                    <div class="image-preview">
                        <img src="<?php echo getFileUrl($project['image']); ?>" alt="Current">
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="form-row">
                <div class="form-group col-4">
                    <label for="sort_order">Sort Order</label>
                    <input type="number" id="sort_order" name="sort_order" value="<?php echo $project['sort_order'] ?? 0; ?>" min="0">
                </div>
                
                <div class="form-group col-4">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="active" <?php echo ($project['status'] ?? 'active') === 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo ($project['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
                
                <div class="form-group col-4">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_featured" value="1" <?php echo ($project['is_featured'] ?? 0) ? 'checked' : ''; ?>>
                        Featured Project
                    </label>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" name="save" class="btn btn-primary">Save Project</button>
                <a href="?action=list" class="btn">Cancel</a>
            </div>
        </form>
    </div>
<?php endif; ?>

<?php include dirname(__DIR__) . '/includes/admin-footer.php'; ?>
