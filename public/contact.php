<?php
/**
 * Contact Page
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/helpers/helpers.php';
require_once __DIR__ . '/../app/models/Setting.php';

$settingModel = new Setting();
$settings = $settingModel->getAll();

$pageTitle = 'Contact';
$metaDescription = 'Get in touch with me for your next project or collaboration. Let\'s build something amazing together.';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';
?>

<section class="section" style="padding-top: 120px;">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h1 class="section-title">Get In Touch</h1>
            <p class="section-subtitle">Let's discuss your next project</p>
        </div>
        
        <div class="grid grid-2" style="align-items: start;">
            <!-- Contact Form -->
            <div class="glass-card" data-aos="fade-right">
                <form id="contact-form" class="contact-form">
                    <div class="form-group">
                        <label for="name">Your Name *</label>
                        <input type="text" id="name" name="name" required placeholder="John Doe">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Your Email *</label>
                        <input type="email" id="email" name="email" required placeholder="john@example.com">
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" placeholder="Project Inquiry">
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" rows="6" required placeholder="Tell me about your project..."></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Send Message</button>
                </form>
            </div>
            
            <!-- Contact Info -->
            <div data-aos="fade-left">
                <div class="glass-card" style="margin-bottom: 1.5rem;">
                    <h3 style="margin-bottom: 1.5rem;">Contact Information</h3>
                    
                    <?php if (!empty($settings['site_email'])): ?>
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <span style="font-size: 1.5rem;">✉️</span>
                            <div>
                                <strong>Email</strong>
                                <p style="margin: 0; color: var(--color-text-muted);">
                                    <a href="mailto:<?php echo clean($settings['site_email']); ?>"><?php echo clean($settings['site_email']); ?></a>
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['site_phone'])): ?>
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <span style="font-size: 1.5rem;">📞</span>
                            <div>
                                <strong>Phone</strong>
                                <p style="margin: 0; color: var(--color-text-muted);">
                                    <a href="tel:<?php echo clean($settings['site_phone']); ?>"><?php echo clean($settings['site_phone']); ?></a>
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['site_address'])): ?>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <span style="font-size: 1.5rem;">📍</span>
                            <div>
                                <strong>Location</strong>
                                <p style="margin: 0; color: var(--color-text-muted);"><?php echo clean($settings['site_address']); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="glass-card">
                    <h3 style="margin-bottom: 1rem;">Connect With Me</h3>
                    <div class="social-links" style="justify-content: flex-start;">
                        <?php
                        $socialLinks = [
                            'github' => ['url' => $settings['social_github'] ?? '', 'icon' => '<i class="fa-brands fa-github"></i>'],
                            'linkedin' => ['url' => $settings['social_linkedin'] ?? '', 'icon' => '<i class="fa-brands fa-linkedin"></i>'],
                            'twitter' => ['url' => $settings['social_twitter'] ?? '', 'icon' => '<i class="fa-brands fa-twitter"></i>'],
                            'instagram' => ['url' => $settings['social_instagram'] ?? '', 'icon' => '<i class="fa-brands fa-instagram"></i>']
                        ];
                        
                        foreach ($socialLinks as $platform => $data):
                            if ($data['url']):
                        ?>
                            <a href="<?php echo clean($data['url']); ?>" target="_blank" rel="noopener noreferrer" class="social-link">
                                <?php echo $data['icon']; ?>
                            </a>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<style>
.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 0.875rem;
    background: var(--color-bg-light);
    border: 1px solid var(--color-border);
    border-radius: var(--border-radius);
    color: var(--color-text);
    font-family: var(--font-primary);
    font-size: 1rem;
    transition: var(--transition);
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.btn-block {
    width: 100%;
}
</style>

<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true
    });
</script>
