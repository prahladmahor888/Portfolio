<?php
/**
 * Admin Blog Edit/Add Page
 */

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/app/helpers/helpers.php';
require_once dirname(__DIR__) . '/app/models/Blog.php';

requireAuth();

$blogModel = new Blog();
$blog = null;
$error = null;
$success = null;
$isEditing = false;

// Check if editing
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $blog = $blogModel->find($id);
    
    if (!$blog) {
        header('Location: blogs.php');
        exit;
    }
    $isEditing = true;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid security token";
    } else {
        $data = [
            'title' => sanitizeInput($_POST['title']),
            'slug' => generateSlug($_POST['title']), // Auto-generate slug
            'content' => $_POST['content'], // Allow HTML for rich text
            'category' => sanitizeInput($_POST['category']),
            'tags' => sanitizeInput($_POST['tags']),
            'status' => $_POST['status']
        ];
        
        // Handle Image Upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload = uploadFile($_FILES['image'], 'blogs');
            if ($upload['success']) {
                $data['image'] = $upload['filename'];
                // Delete old image if updating
                if ($isEditing && $blog['image']) {
                    deleteFile($blog['image']);
                }
            } else {
                $error = $upload['error'];
            }
        }
        
        if (!$error) {
            if ($isEditing) {
                if ($blogModel->update($id, $data)) {
                    $success = "Blog updated successfully";
                    $blog = array_merge($blog, $data);
                } else {
                    $error = "Failed to update blog";
                }
            } else {
                $data['author_id'] = getCurrentUserId();
                if ($blogModel->create($data)) {
                    setFlash('success', 'Blog post created successfully');
                    header('Location: blogs.php');
                    exit;
                } else {
                    $error = "Failed to create blog post";
                }
            }
        }
    }
}

$pageTitle = $isEditing ? 'Edit Blog Post' : 'New Blog Post';
include dirname(__DIR__) . '/includes/admin-header.php';
?>

<!-- TinyMCE CDN for Rich Text Editing -->
<!-- TinyMCE CDN (Community Version via cdnjs) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#content',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        height: 500
    });
</script>

<div class="page-header">
    <div>
        <h1 class="page-title"><?php echo $pageTitle; ?></h1>
        <p style="color: var(--admin-text-light); margin-top: 0.5rem;">
            <?php echo $isEditing ? 'Edit your existing post' : 'Share your knowledge with the world'; ?>
        </p>
    </div>
    <a href="blogs.php" class="btn btn-outline">
        <span>←</span>
        <span>Back to Blogs</span>
    </a>
</div>

<div class="form-container" style="max-width: 1000px; margin: 0 auto;">
    <?php if ($error): ?>
        <div class="alert alert-error">
            <strong>⚠️ Error:</strong> <?php echo clean($error); ?>
        </div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success">
            <strong>✓ Success:</strong> <?php echo clean($success); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <?php echo csrfField(); ?>
        
        <div class="grid grid-2" style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
            <!-- Main Content Column -->
            <div>
                <div class="form-group">
                    <label for="title">Post Title</label>
                    <input type="text" id="title" name="title" value="<?php echo clean($blog['title'] ?? ''); ?>" placeholder="Enter a catchy title..." required style="font-size: 1.25rem; font-weight: 600;">
                </div>
                
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea id="content" name="content"><?php echo $blog['content'] ?? ''; ?></textarea>
                </div>
            </div>
            
            <!-- Sidebar Column -->
            <div>
                <div style="background: var(--admin-bg); padding: 1.5rem; border-radius: 12px; position: sticky; top: 100px;">
                    <div class="form-group">
                        <label for="status">Publish Status</label>
                        <select id="status" name="status">
                            <option value="draft" <?php echo ($blog['status'] ?? '') === 'draft' ? 'selected' : ''; ?>>Draft</option>
                            <option value="published" <?php echo ($blog['status'] ?? '') === 'published' ? 'selected' : ''; ?>>Published</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select id="category" name="category" required>
                            <option value="">Select Category...</option>
                            <option value="Web Development" <?php echo ($blog['category'] ?? '') === 'Web Development' ? 'selected' : ''; ?>>Web Development</option>
                            <option value="Design" <?php echo ($blog['category'] ?? '') === 'Design' ? 'selected' : ''; ?>>Design</option>
                            <option value="Tutorial" <?php echo ($blog['category'] ?? '') === 'Tutorial' ? 'selected' : ''; ?>>Tutorial</option>
                            <option value="Technology" <?php echo ($blog['category'] ?? '') === 'Technology' ? 'selected' : ''; ?>>Technology</option>
                            <option value="Personal" <?php echo ($blog['category'] ?? '') === 'Personal' ? 'selected' : ''; ?>>Personal</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="tags">Tags</label>
                        <input type="text" id="tags" name="tags" value="<?php echo clean($blog['tags'] ?? ''); ?>" placeholder="e.g. #Java, #Security">
                    </div>
                    
                    <div class="form-group">
                        <label>Featured Image</label>
                        <div style="border: 2px dashed var(--admin-border); border-radius: 8px; padding: 1rem; text-align: center; cursor: pointer; background: white;" onclick="document.getElementById('image').click()">
                            <?php if (!empty($blog['image'])): ?>
                                <img src="<?php echo getFileUrl($blog['image']); ?>" alt="Current Image" style="max-width: 100%; border-radius: 8px; margin-bottom: 0.5rem;">
                                <div style="font-size: 0.8rem; color: var(--admin-text-light);">Click to change image</div>
                            <?php else: ?>
                                <div style="font-size: 2rem; margin-bottom: 0.5rem;">🖼️</div>
                                <div style="font-size: 0.9rem; font-weight: 500;">Upload Image</div>
                                <div style="font-size: 0.8rem; color: var(--admin-text-light);">PNG, JPG up to 2MB</div>
                            <?php endif; ?>
                        </div>
                        <input type="file" id="image" name="image" accept="image/*" style="display: none;" onchange="previewImage(this)">
                    </div>
                    
                    <div style="margin-top: 2rem;">
                        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 1rem;">
                            <?php echo $isEditing ? 'Update Post' : 'Publish Post'; ?>
                        </button>
                        <?php if ($isEditing): ?>
                            <a href="blogs.php?action=delete&id=<?php echo $blog['id']; ?>" class="btn btn-danger" style="width: 100%; justify-content: center; margin-top: 0.5rem;" onclick="return confirm('Delete this post?');">
                                Delete Post
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        // You could add JS image preview logic here if desired
        // For now the text changes to show selected file name
        const container = input.previousElementSibling;
        const text = document.createElement('div');
        text.style.marginTop = '0.5rem';
        text.style.fontWeight = 'bold';
        text.style.color = 'var(--admin-primary)';
        text.textContent = 'Selected: ' + input.files[0].name;
        
        // Remove existing text/image preview temporarily for simple feedback
        // In a real app you'd render the FileReader result
        container.innerHTML = '<div style="font-size: 2rem; margin-bottom: 0.5rem;">✅</div>';
        container.appendChild(text);
    }
}
</script>

<?php include dirname(__DIR__) . '/includes/admin-footer.php'; ?>
