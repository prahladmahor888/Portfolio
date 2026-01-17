<?php
/**
 * Admin Services Management
 */

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/app/helpers/helpers.php';
require_once dirname(__DIR__) . '/app/models/Service.php';

requireAuth();

$serviceModel = new Service();
$currentUser = getCurrentUser();

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    if ($serviceModel->delete($id)) {
        setFlash('success', 'Service deleted successfully');
    } else {
        setFlash('error', 'Failed to delete service');
    }
    
    header('Location: services.php');
    exit;
}

// Get all services
$services = $serviceModel->all([], 'created_at DESC');

$pageTitle = 'Services Management';
include dirname(__DIR__) . '/includes/admin-header.php';
?>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">⚙️ Services Management</h1>
        <p style="color: var(--admin-text-light); margin-top: 0.5rem;">Manage your services and offerings</p>
    </div>
    <a href="service-edit.php" class="btn btn-primary">
        <span>➕</span>
        <span>Add New Service</span>
    </a>
</div>

<!-- Services Grid -->
<?php if (empty($services)): ?>
    <div class="data-table">
        <div style="padding: 4rem 2rem; text-align: center; color: var(--admin-text-light);">
            <div style="font-size: 5rem; margin-bottom: 1rem; opacity: 0.3;">⚙️</div>
            <h3 style="margin-bottom: 0.5rem;">No Services Yet</h3>
            <p style="margin-bottom: 1.5rem;">Add services to showcase what you offer</p>
            <a href="service-edit.php" class="btn btn-primary btn-lg">
                <span>➕</span>
                <span>Add Your First Service</span>
            </a>
        </div>
    </div>
<?php else: ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <?php foreach ($services as $service): ?>
            <div class="service-card" style="background: white; border: 1px solid var(--admin-border); border-radius: 12px; overflow: hidden; box-shadow: var(--admin-shadow); transition: all 0.3s; height: 100%; display: flex; flex-direction: column;">
                <div style="padding: 1.5rem; flex: 1; display: flex; flex-direction: column;">
                    <!-- Service Icon & Title -->
                    <div style="display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1rem;">
                        <div style="width: 50px; height: 50px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, rgba(79, 70, 229, 0.1) 0%, rgba(6, 182, 212, 0.1) 100%); border-radius: 12px; color: var(--admin-primary); font-size: 1.5rem;">
                            <?php 
                            $icon = $service['icon'];
                            if (strpos($icon, 'fa-') !== false) {
                                echo '<i class="' . clean($icon) . '"></i>';
                            } else {
                                echo $icon ?: '⚙️';
                            }
                            ?>
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <h3 style="margin: 0 0 0.25rem 0; font-size: 1.1rem; font-weight: 600; line-height: 1.3;"><?php echo clean($service['title']); ?></h3>
                            <div>
                                <?php if ($service['status'] === 'active'): ?>
                                    <span class="badge" style="background: #ECFDF5; color: #059669; padding: 0.15rem 0.5rem; border-radius: 6px; font-size: 0.7rem;">Active</span>
                                <?php else: ?>
                                    <span class="badge" style="background: #FEF2F2; color: #DC2626; padding: 0.15rem 0.5rem; border-radius: 6px; font-size: 0.7rem;">Inactive</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <p style="color: var(--admin-text-light); font-size: 0.875rem; margin-bottom: 1.25rem; line-height: 1.6; flex-grow: 1;">
                        <?php echo clean(truncate($service['description'], 100)); ?>
                    </p>
                    
                    <!-- Features -->
                    <?php if ($service['features']): ?>
                        <div style="margin-bottom: 1.25rem; background: var(--admin-bg); padding: 0.75rem; border-radius: 8px;">
                            <?php
                            $features = explode("\n", $service['features']);
                            $displayFeatures = array_slice(array_filter($features), 0, 2);
                            foreach ($displayFeatures as $feature):
                            ?>
                                <div style="font-size: 0.8rem; color: var(--admin-text); margin-bottom: 0.25rem; display: flex; align-items: center; gap: 0.5rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <span style="color: var(--admin-primary);">•</span>
                                    <span><?php echo clean(trim($feature)); ?></span>
                                </div>
                            <?php endforeach; ?>
                            <?php if (count($features) > 2): ?>
                                <div style="font-size: 0.75rem; color: var(--admin-text-light); margin-top: 0.25rem; padding-left: 0.75rem;">+<?php echo count($features) - 2; ?> more</div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Price and Actions footer -->
                    <div style="margin-top: auto; padding-top: 1rem; border-top: 1px solid var(--admin-border); display: flex; align-items: center; justify-content: space-between;">
                        <div style="font-weight: 700; color: var(--admin-text); font-size: 1.1rem;">
                            <?php echo $service['price'] ? clean($service['price']) : '<span style="color: var(--admin-text-light); font-size: 0.9rem;">Custom</span>'; ?>
                        </div>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="service-edit.php?id=<?php echo $service['id']; ?>" class="btn btn-sm btn-outline" style="padding: 0.4rem 0.6rem;" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <a href="?action=delete&id=<?php echo $service['id']; ?>" 
                               class="btn btn-sm btn-danger" 
                               style="padding: 0.4rem 0.6rem;"
                               onclick="return confirm('Are you sure you want to delete this service?')"
                               title="Delete">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Stats Summary -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <div style="background: white; padding: 1.5rem; border-radius: 12px; border: 1px solid var(--admin-border);">
            <div style="font-size: 0.875rem; color: var(--admin-text-light); margin-bottom: 0.5rem;">Total Services</div>
            <div style="font-size: 2rem; font-weight: 700; color: var(--admin-primary);"><?php echo count($services); ?></div>
        </div>
        <div style="background: white; padding: 1.5rem; border-radius: 12px; border: 1px solid var(--admin-border);">
            <div style="font-size: 0.875rem; color: var(--admin-text-light); margin-bottom: 0.5rem;">Active</div>
            <div style="font-size: 2rem; font-weight: 700; color: #10B981;">
                <?php echo count(array_filter($services, fn($s) => $s['status'] === 'active')); ?>
            </div>
        </div>
        <div style="background: white; padding: 1.5rem; border-radius: 12px; border: 1px solid var(--admin-border);">
            <div style="font-size: 0.875rem; color: var(--admin-text-light); margin-bottom: 0.5rem;">Inactive</div>
            <div style="font-size: 2rem; font-weight: 700; color: #F59E0B;">
                <?php echo count(array_filter($services, fn($s) => $s['status'] === 'inactive')); ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php include dirname(__DIR__) . '/includes/admin-footer.php'; ?>
