<?php
/**
 * Admin Skills Management
 */

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/app/helpers/helpers.php';
require_once dirname(__DIR__) . '/app/models/Skill.php'; // We'll need to create this model

requireAuth();

// Skill model is already required above
$skillModel = new Skill();
$error = null;

// Check for Edit Mode
$editSkill = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $editId = (int)$_GET['id'];
    $editSkill = $skillModel->find($editId);
}

// Handle Form Submission (Add/Edit Skill)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid security token";
    } else {
        $icon = sanitizeInput($_POST['icon']);
        
        // Handle Image Upload
        if (isset($_FILES['image_icon']) && $_FILES['image_icon']['error'] === UPLOAD_ERR_OK) {
            $upload = uploadFile($_FILES['image_icon'], 'skills');
            if ($upload['success']) {
                $icon = $upload['filename'];
            } else {
                $error = $upload['error'];
            }
        } elseif ($editSkill && empty($icon)) {
            // Keep existing icon if editing and no new icon provided
            $icon = $editSkill['icon'];
        }
        
        if (!$error) {
            $data = [
                'name' => sanitizeInput($_POST['name']),
                'category' => sanitizeInput($_POST['category']),
                'level' => (int)$_POST['percentage'],
                'icon' => $icon,
                'sort_order' => (int)($_POST['order'] ?? 0)
            ];
            
            if ($editSkill) {
                // Update
                if ($skillModel->update($editSkill['id'], $data)) {
                    setFlash('success', 'Skill updated successfully');
                    header('Location: skills.php');
                    exit;
                } else {
                    $error = "Failed to update skill";
                }
            } else {
                // Create
                if ($skillModel->create($data)) {
                    setFlash('success', 'Skill added successfully');
                    header('Location: skills.php');
                    exit;
                } else {
                    $error = "Failed to add skill";
                }
            }
        }
    }
}

// Handle Delete
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    if ($skillModel->delete((int)$_GET['id'])) {
        setFlash('success', 'Skill deleted successfully');
    } else {
        setFlash('error', 'Failed to delete skill');
    }
    header('Location: skills.php');
    exit;
}

// Fetch all skills for display
$skills = $skillModel->all([], 'category ASC, sort_order ASC');

// Group skills by category for display
$groupedSkills = [];
foreach ($skills as $skill) {
    if (!isset($groupedSkills[$skill['category']])) {
        $groupedSkills[$skill['category']] = [];
    }
    $groupedSkills[$skill['category']][] = $skill;
}

$pageTitle = 'Skills Management';
include dirname(__DIR__) . '/includes/admin-header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">🎯 Skills Management</h1>
        <p style="color: var(--admin-text-light); margin-top: 0.5rem;">Manage your technical expertise</p>
    </div>
    <?php if ($editSkill): ?>
        <a href="skills.php" class="btn btn-outline">Cancel Edit</a>
    <?php endif; ?>
</div>

<div style="display: grid; grid-template-columns: 350px 1fr; gap: 2rem; align-items: start;">
    
    <!-- Add/Edit Skill Form -->
    <div class="form-container" style="position: sticky; top: 100px;">
        <h3 style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--admin-border); padding-bottom: 1rem;">
            <?php echo $editSkill ? 'Edit Skill: ' . clean($editSkill['name']) : 'Add New Skill'; ?>
        </h3>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo clean($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="?<?php echo $editSkill ? 'action=edit&id=' . $editSkill['id'] : ''; ?>" enctype="multipart/form-data">
            <?php echo csrfField(); ?>
            
            <div class="form-group">
                <label for="name">Skill Name</label>
                <input type="text" id="name" name="name" placeholder="e.g. PHP, React" value="<?php echo $editSkill ? clean($editSkill['name']) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <?php 
                    $categories = ['Frontend', 'Backend', 'Database', 'DevOps', 'Design', 'Soft Skills', 'Tools'];
                    $currentCat = $editSkill['category'] ?? '';
                    foreach ($categories as $cat) {
                        $selected = $currentCat === $cat ? 'selected' : '';
                        echo "<option value=\"$cat\" $selected>$cat</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="percentage">Proficiency (%)</label>
                <input type="range" id="percentage" name="percentage" min="0" max="100" value="<?php echo $editSkill ? $editSkill['level'] : '70'; ?>" oninput="this.nextElementSibling.value = this.value">
                <output style="display: block; text-align: center; font-weight: bold; margin-top: 0.5rem; color: var(--admin-primary);"><?php echo $editSkill ? $editSkill['level'] : '70'; ?></output>
            </div>
            
            <div class="form-group">
                <label>Skill Icon</label>
                <div style="font-size: 0.8rem; color: var(--admin-text-light); margin-bottom: 0.5rem;">Choose either FontAwesome class OR upload an image</div>
                
                <!-- Option 1: FontAwesome -->
                <label for="icon" style="font-size: 0.8rem; font-weight: normal; display: block; margin-bottom: 0.25rem;">Option 1: FontAwesome Class</label>
                <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem;">
                    <div style="width: 40px; height: 40px; background: var(--admin-bg); display: flex; align-items: center; justify-content: center; border-radius: 8px;" id="iconPreview">
                        <?php if ($editSkill && !empty($editSkill['icon']) && strpos($editSkill['icon'], 'fa-') !== false): ?>
                            <i class="<?php echo clean($editSkill['icon']); ?>"></i>
                        <?php else: ?>
                            <i class="fas fa-code"></i>
                        <?php endif; ?>
                    </div>
                    <input type="text" id="icon" name="icon" placeholder="e.g. fab fa-php" style="flex: 1;" oninput="updateIconPreview(this.value)" value="<?php echo ($editSkill && !empty($editSkill['icon']) && strpos($editSkill['icon'], 'fa-') !== false) ? clean($editSkill['icon']) : ''; ?>">
                </div>
                
                <!-- Option 2: Image Upload -->
                <label for="image_icon" style="font-size: 0.8rem; font-weight: normal; display: block; margin-bottom: 0.25rem;">Option 2: Upload Custom Image</label>
                <?php if ($editSkill && !empty($editSkill['icon']) && strpos($editSkill['icon'], '.') !== false): ?>
                    <div style="margin-bottom: 0.5rem;">
                        <img src="<?php echo getFileUrl($editSkill['icon']); ?>" style="height: 40px; width: 40px; object-fit: contain; border: 1px solid var(--admin-border); border-radius: 4px;">
                        <span style="font-size: 0.75rem; color: var(--admin-text-light); vertical-align: middle;">Current Icon</span>
                    </div>
                <?php endif; ?>
                <input type="file" id="image_icon" name="image_icon" accept="image/*" style="font-size: 0.8rem;">
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                <?php echo $editSkill ? 'Update Skill' : 'Add Skill'; ?>
            </button>
        </form>
    </div>

    <!-- Skills List -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <?php if (empty($groupedSkills)): ?>
            <div class="data-table" style="padding: 3rem; text-align: center;">
                <div style="font-size: 4rem; opacity: 0.2; margin-bottom: 1rem;">🎯</div>
                <h3>No Skills Added Yet</h3>
                <p style="color: var(--admin-text-light);">Use the form on the left to add your skills.</p>
            </div>
        <?php else: ?>
            <?php foreach ($groupedSkills as $category => $categorySkills): ?>
                <div class="data-table">
                    <div class="table-header">
                        <h3 class="table-title"><?php echo clean($category); ?></h3>
                        <span class="badge" style="background: var(--admin-current);"><?php echo count($categorySkills); ?> skills</span>
                    </div>
                    <div style="padding: 1.5rem; display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem;">
                        <?php foreach ($categorySkills as $skill): ?>
                            <div style="background: var(--admin-bg); border: 1px solid var(--admin-border); border-radius: 8px; padding: 1rem; position: relative; group;">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.75rem;">
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <div style="font-size: 1.5rem; color: var(--admin-primary); width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                            <?php if (!empty($skill['icon'])): ?>
                                                <?php if (strpos($skill['icon'], 'fa-') !== false): ?>
                                                    <i class="<?php echo clean($skill['icon']); ?>"></i>
                                                <?php elseif (strpos($skill['icon'], '.') !== false): ?>
                                                    <img src="<?php echo getFileUrl($skill['icon']); ?>" alt="" style="width: 100%; height: 100%; object-fit: contain;">
                                                <?php else: ?>
                                                    <?php echo $skill['icon']; // Text/Emoji ?>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                🚀
                                            <?php endif; ?>
                                        </div>
                                        <strong><?php echo clean($skill['name']); ?></strong>
                                    </div>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <a href="?action=edit&id=<?php echo $skill['id']; ?>" 
                                           style="color: var(--admin-primary); opacity: 0.5; transition: opacity 0.2s;"
                                           onmouseover="this.style.opacity=1"
                                           onmouseout="this.style.opacity=0.5"
                                           title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <a href="?action=delete&id=<?php echo $skill['id']; ?>" 
                                           onclick="return confirm('Delete this skill?')"
                                           style="color: #EF4444; opacity: 0.5; transition: opacity 0.2s;"
                                           onmouseover="this.style.opacity=1"
                                           onmouseout="this.style.opacity=0.5"
                                           title="Delete">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </div>
                                </div>
                                <div style="background: rgba(0,0,0,0.1); height: 6px; border-radius: 3px; overflow: hidden;">
                                    <div style="background: var(--admin-primary); height: 100%; width: <?php echo $skill['level'] ?? 0; ?>%;"></div>
                                </div>
                                <div style="text-align: right; font-size: 0.75rem; margin-top: 0.25rem; color: var(--admin-text-light);">
                                    <?php echo $skill['level'] ?? 0; ?>%
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
               

<script>
function updateIconPreview(val) {
    const preview = document.querySelector('#iconPreview i');
    if (val) {
        preview.className = val;
    } else {
        preview.className = 'fas fa-code';
    }
}
</script>

<?php include dirname(__DIR__) . '/includes/admin-footer.php'; ?>
