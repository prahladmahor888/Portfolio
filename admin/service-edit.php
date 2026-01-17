<?php
/**
 * Admin Service Edit/Add Page
 */

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/app/helpers/helpers.php';
require_once dirname(__DIR__) . '/app/models/Service.php';

requireAuth();

$serviceModel = new Service();
$service = null;
$error = null;
$success = null;
$isEditing = false;

// Check if editing
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $service = $serviceModel->find($id);
    
    if (!$service) {
        header('Location: services.php');
        exit;
    }
    $isEditing = true;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid security token";
    } else {
        $data = [
            'title' => sanitizeInput($_POST['title']),
            'description' => sanitizeInput($_POST['description']),
            'icon' => $_POST['icon'], // Allow raw emoji
            'price' => sanitizeInput($_POST['price']),
            'features' => sanitizeInput($_POST['features']),
            'status' => $_POST['status']
        ];
        
        if (empty($data['title'])) {
            $error = "Title is required";
        } else {
            if ($isEditing) {
                if ($serviceModel->update($id, $data)) {
                    $success = "Service updated successfully";
                    $service = array_merge($service, $data);
                } else {
                    $error = "Failed to update service";
                }
            } else {
                if ($serviceModel->create($data)) {
                    setFlash('success', 'Service created successfully');
                    header('Location: services.php');
                    exit;
                } else {
                    $error = "Failed to create service";
                }
            }
        }
    }
}

$pageTitle = $isEditing ? 'Edit Service' : 'Add New Service';
include dirname(__DIR__) . '/includes/admin-header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title"><?php echo $pageTitle; ?></h1>
        <p style="color: var(--admin-text-light); margin-top: 0.5rem;">
            <?php echo $isEditing ? 'Update existing service details' : 'Create a new service offering'; ?>
        </p>
    </div>
    <a href="services.php" class="btn btn-outline">
        <span>←</span>
        <span>Back to Services</span>
    </a>
</div>

<div class="form-container" style="max-width: 800px; margin: 0 auto;">
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

    <form method="POST" action="">
        <?php echo csrfField(); ?>
        
        <div class="form-group">
            <label for="title">Service Title</label>
            <input type="text" id="title" name="title" value="<?php echo clean($service['title'] ?? ''); ?>" placeholder="e.g. Web Development" required>
        </div>
        
        <div class="grid grid-2" style="display: grid; grid-template-columns: 100px 1fr; gap: 1rem; align-items: start;">
            <div class="form-group">
                <label for="icon">Icon</label>
                <div style="position: relative;">
                    <input type="text" id="icon" name="icon" value="<?php echo clean($service['icon'] ?? '⚙️'); ?>" style="text-align: center; font-size: 1.5rem; padding: 0.5rem;" required>
                    <button type="button" onclick="toggleEmojiPicker()" style="font-size: 0.8rem; width: 100%; margin-top: 0.25rem; background: none; border: none; color: var(--admin-primary); cursor: pointer;">Pick Emoji</button>
                    
                    <!-- Simple Emoji Picker -->
                    <div id="emojiPicker" style="display: none; position: absolute; top: 100%; left: 0; background: white; border: 1px solid var(--admin-border); box-shadow: var(--admin-shadow-lg); border-radius: 8px; padding: 0.5rem; width: 300px; grid-template-columns: repeat(8, 1fr); gap: 0.5rem; z-index: 10; height: 200px; overflow-y: auto;">
                        <?php 
                        $emojis = ['💻','🌐','📱','🎨','✏️','📢','📈','🛒','🔒','⚙️','🚀','⚡','🔧','🛠️','📅','📊','📝','🔍','🤖','☁️','💾','🎥','📷','🎮','🎯','💡','🔌','🔋','📡','🔨','📐','🏗️'];
                        foreach($emojis as $emoji) {
                            echo '<button type="button" onclick="selectEmoji(\''.$emoji.'\')" style="font-size: 1.5rem; background: none; border: none; cursor: pointer; padding: 0.25rem; border-radius: 4px; transition: background 0.2s;">'.$emoji.'</button>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="price">Price (Optional)</label>
                <input type="text" id="price" name="price" value="<?php echo clean($service['price'] ?? ''); ?>" placeholder="e.g. Starting at $500">
            </div>
        </div>
        
        <div class="form-group">
            <label for="description">Short Description</label>
            <textarea id="description" name="description" rows="3" placeholder="Brief overview of the service..." required><?php echo clean($service['description'] ?? ''); ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="features">Features (One per line)</label>
            <textarea id="features" name="features" rows="6" placeholder="✓ Responsive Design&#10;✓ SEO Optimization&#10;✓ 1 Year Support" style="font-family: monospace;"><?php echo clean($service['features'] ?? ''); ?></textarea>
            <p style="font-size: 0.75rem; color: var(--admin-text-light); margin-top: 0.25rem;">Enter each feature on a new line. These will be displayed as a list.</p>
        </div>
        
        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="active" <?php echo ($service['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                <option value="inactive" <?php echo ($service['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
            </select>
        </div>
        
        <div style="display: flex; gap: 1rem; margin-top: 2rem; padding-top: 1rem; border-top: 1px solid var(--admin-border);">
            <button type="submit" class="btn btn-primary btn-lg" style="flex: 1; justify-content: center;">
                <?php echo $isEditing ? 'Update Service' : 'Create Service'; ?>
            </button>
            <?php if ($isEditing): ?>
                <a href="services.php?action=delete&id=<?php echo $service['id']; ?>" class="btn btn-danger btn-lg" onclick="return confirm('Delete this service?');">Delete</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Preview Card -->
<div style="margin-top: 3rem; max-width: 800px; margin-left: auto; margin-right: auto;">
    <h3 style="margin-bottom: 1rem; color: var(--admin-text-light); border-bottom: 1px solid var(--admin-border); padding-bottom: 0.5rem;">Live Preview</h3>
    
    <div style="background: white; border: 1px solid var(--admin-border); border-radius: 12px; padding: 2rem; box-shadow: var(--admin-shadow); display: flex; flex-direction: column; align-items: start; max-width: 400px; margin: 0 auto;">
        <div id="previewIcon" style="font-size: 3rem; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, rgba(79, 70, 229, 0.1) 0%, rgba(6, 182, 212, 0.1) 100%); border-radius: 12px; margin-bottom: 1.5rem;">
            <?php 
            $icon = $service['icon'] ?? '⚙️';
            $icon = html_entity_decode($icon);
            
            if (strpos($icon, '<i') !== false) {
                echo $icon;
            } elseif (strpos($icon, 'fa-') !== false) {
                echo '<i class="' . clean($icon) . '"></i>';
            } else {
                echo clean($icon);
            }
            ?>
        </div>
        <h3 id="previewTitle" style="margin: 0 0 1rem 0; font-size: 1.5rem;"><?php echo $service['title'] ?? 'Service Title'; ?></h3>
        <p id="previewDesc" style="color: var(--admin-text-light); font-size: 0.875rem; margin-bottom: 1.5rem; line-height: 1.6;">
            <?php echo $service['description'] ?? 'Service description will appear here...'; ?>
        </p>
        <div id="previewFeatures" style="margin-bottom: 1.5rem; width: 100%;">
            <!-- Features preview -->
        </div>
        <div id="previewPrice" style="font-size: 1.5rem; font-weight: 700; color: var(--admin-primary); margin-top: auto;">
            <?php echo $service['price'] ?? '$0.00'; ?>
        </div>
    </div>
</div>

<script>
    function toggleEmojiPicker() {
        const picker = document.getElementById('emojiPicker');
        picker.style.display = picker.style.display === 'none' ? 'grid' : 'none';
    }
    
    function selectEmoji(emoji) {
        document.getElementById('icon').value = emoji;
        document.getElementById('previewIcon').textContent = emoji;
        document.getElementById('emojiPicker').style.display = 'none';
    }
    
    // Live Preview Scripts
    const inputs = ['title', 'description', 'price', 'icon'];
    inputs.forEach(id => {
        document.getElementById(id).addEventListener('input', function(e) {
            let val = e.target.value;
            if (id === 'price' && !val) val = '$0.00';
            
            const previewEl = document.getElementById('preview' + id.charAt(0).toUpperCase() + id.slice(1));
            
            if (id === 'icon') {
                if (val.includes('<i')) {
                    previewEl.innerHTML = val;
                } else if (val.includes('fa-')) {
                    previewEl.innerHTML = `<i class="${val}"></i>`;
                } else {
                    previewEl.textContent = val;
                }
            } else {
                previewEl.textContent = val;
            }
        });
    });
    
    document.getElementById('features').addEventListener('input', function(e) {
        const features = e.target.value.split('\n').filter(f => f.trim());
        const container = document.getElementById('previewFeatures');
        container.innerHTML = features.slice(0, 3).map(f => `
            <div style="font-size: 0.875rem; margin-bottom: 0.25rem; display: flex; align-items: center; gap: 0.5rem;">
                <span style="color: var(--admin-accent);">✓</span>
                <span>${f}</span>
            </div>
        `).join('');
    });
    
    // Trigger feature preview on load
    document.getElementById('features').dispatchEvent(new Event('input'));
</script>

<?php include dirname(__DIR__) . '/includes/admin-footer.php'; ?>
