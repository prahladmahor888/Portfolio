<?php
/**
 * Admin - Site Settings
 */

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/app/helpers/helpers.php';
require_once dirname(__DIR__) . '/app/models/Setting.php';
require_once dirname(__DIR__) . '/app/models/User.php';

requireAuth();

$settingModel = new Setting();
$userModel = new User();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
        setFlash('error', 'Invalid request');
        redirect($_SERVER['REQUEST_URI']);
    }
    
    if (isset($_POST['save_settings'])) {
        $settings = [
            'site_name' => sanitizeInput($_POST['site_name']),
            'site_tagline' => sanitizeInput($_POST['site_tagline']),
            'site_description' => sanitizeInput($_POST['site_description']),
            'site_email' => sanitizeInput($_POST['site_email']),
            'site_phone' => sanitizeInput($_POST['site_phone']),
            'site_address' => sanitizeInput($_POST['site_address']),
            'meta_keywords' => sanitizeInput($_POST['meta_keywords']),
            'meta_author' => sanitizeInput($_POST['meta_author']),
            'social_github' => sanitizeInput($_POST['social_github']),
            'social_linkedin' => sanitizeInput($_POST['social_linkedin']),
            'social_twitter' => sanitizeInput($_POST['social_twitter']),
            'social_instagram' => sanitizeInput($_POST['social_instagram']),
            'social_youtube' => sanitizeInput($_POST['social_youtube']),
            'about_bio' => sanitizeInput($_POST['about_bio']),
            'hero_title' => sanitizeInput($_POST["hero_title"]),
            'hero_subtitle' => sanitizeInput($_POST["hero_subtitle"])
        ];
        
        if ($settingModel->updateMultiple($settings)) {
            setFlash('success', 'Settings updated successfully');
        } else {
            setFlash('error', 'Failed to update settings');
        }
        redirect(ADMIN_URL . '/settings.php');
    }
}

// Get all settings
$settings = $settingModel->getAll();

$pageTitle = 'Settings';
include dirname(__DIR__) . '/includes/admin-header.php';
?>

<div class="page-header">
    <h1>Site Settings</h1>
</div>

<div class="form-container">
    <form method="POST" class="admin-form">
        <?php echo csrfField(); ?>
        
        <h3>General Settings</h3>
        <div class="form-row">
            <div class="form-group col-6">
                <label for="site_name">Site Name</label>
                <input type="text" id="site_name" name="site_name" value="<?php echo clean($settings['site_name'] ?? ''); ?>">
            </div>
            <div class="form-group col-6">
                <label for="site_tagline">Tagline</label>
                <input type="text" id="site_tagline" name="site_tagline" value="<?php echo clean($settings['site_tagline'] ?? ''); ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label for="site_description">Site Description</label>
            <textarea id="site_description" name="site_description" rows="3"><?php echo clean($settings['site_description'] ?? ''); ?></textarea>
        </div>
        
        <h3>Contact Information</h3>
        <div class="form-row">
            <div class="form-group col-4">
                <label for="site_email">Email</label>
                <input type="email" id="site_email" name="site_email" value="<?php echo clean($settings['site_email'] ?? ''); ?>">
            </div>
            <div class="form-group col-4">
                <label for="site_phone">Phone</label>
                <input type="text" id="site_phone" name="site_phone" value="<?php echo clean($settings['site_phone'] ?? ''); ?>">
            </div>
            <div class="form-group col-4">
                <label for="site_address">Address</label>
                <input type="text" id="site_address" name="site_address" value="<?php echo clean($settings['site_address'] ?? ''); ?>">
            </div>
        </div>
        
        <h3>Social Media Links</h3>
        <div class="form-row">
            <div class="form-group col-6">
                <label for="social_github">GitHub URL</label>
                <input type="url" id="social_github" name="social_github" value="<?php echo clean($settings['social_github'] ?? ''); ?>">
            </div>
            <div class="form-group col-6">
                <label for="social_linkedin">LinkedIn URL</label>
                <input type="url" id="social_linkedin" name="social_linkedin" value="<?php echo clean($settings['social_linkedin'] ?? ''); ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-6">
                <label for="social_twitter">Twitter URL</label>
                <input type="url" id="social_twitter" name="social_twitter" value="<?php echo clean($settings['social_twitter'] ?? ''); ?>">
            </div>
            <div class="form-group col-6">
                <label for="social_instagram">Instagram URL</label>
                <input type="url" id="social_instagram" name="social_instagram" value="<?php echo clean($settings['social_instagram'] ?? ''); ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-6">
                <label for="social_youtube">YouTube URL</label>
                <input type="url" id="social_youtube" name="social_youtube" value="<?php echo clean($settings['social_youtube'] ?? ''); ?>">
            </div>
        </div>
        
        <h3>Homepage Content</h3>
        <div class="form-group">
            <label for="hero_title">Hero Title</label>
            <input type="text" id="hero_title" name="hero_title" value="<?php echo clean($settings["hero_title"] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="hero_subtitle">Hero Subtitle</label>
            <input type="text" id="hero_subtitle" name="hero_subtitle" value="<?php echo clean($settings["hero_subtitle"] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="about_bio">About Bio</label>
            <textarea id="about_bio" name="about_bio" rows="4"><?php echo clean($settings['about_bio'] ?? ''); ?></textarea>
        </div>
        
        <h3>SEO Settings</h3>
        <div class="form-group">
            <label for="meta_keywords">Meta Keywords (comma-separated)</label>
            <input type="text" id="meta_keywords" name="meta_keywords" value="<?php echo clean($settings['meta_keywords'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="meta_author">Meta Author</label>
            <input type="text" id="meta_author" name="meta_author" value="<?php echo clean($settings['meta_author'] ?? ''); ?>">
        </div>
        
        <div class="form-actions">
            <button type="submit" name="save_settings" class="btn btn-primary">Save Settings</button>
        </div>
    </form>
</div>

<?php include dirname(__DIR__) . '/includes/admin-footer.php'; ?>
