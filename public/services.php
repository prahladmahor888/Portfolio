<?php
/**
 * Services Page - All Services
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/helpers/helpers.php';
require_once __DIR__ . '/../app/models/Service.php';
require_once __DIR__ . '/../app/models/Setting.php';

$serviceModel = new Service();
$settingModel = new Setting();

$services = $serviceModel->getActive();
$settings = $settingModel->getAll();

$pageTitle = 'Services';
$metaDescription = 'Professional web development services including design, development, and consulting.';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';
?>

<section class="section" style="padding-top: 120px;">
    <div class="container">
        <!-- Page Header -->
        <div class="section-header" data-aos="fade-up">
            <h1 class="section-title">My Services</h1>
            <p class="section-subtitle">What I can help you with</p>
        </div>
        
        <!-- Services Grid -->
        <?php if (empty($services)): ?>
            <div class="empty-state" data-aos="fade-up">
                <div style="font-size: 4rem; margin-bottom: 1rem;">⚙️</div>
                <h3>No Services Listed</h3>
                <p class="text-muted">Check back soon for available services!</p>
            </div>
        <?php else: ?>
            <div class="services-grid">
                <?php foreach ($services as $index => $service): ?>
                    <div class="service-card" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                        <div class="service-icon">
                            <?php 
                            $icon = $service['icon'] ?: '⚙️';
                            $icon = html_entity_decode(html_entity_decode($icon));
                            $icon = trim($icon);
                            
                            if (strpos($icon, '<i') !== false || strpos($icon, '&lt;i') !== false) {
                                echo html_entity_decode($icon);
                            } elseif (preg_match('/fa-/', $icon)) {
                                echo '<i class="' . clean($icon) . '"></i>';
                            } else {
                                echo clean($icon);
                            }
                            ?>
                        </div>
                        <h3 class="service-title"><?php echo clean($service['title']); ?></h3>
                        <p class="service-description"><?php echo clean($service['description']); ?></p>
                        
                        <?php if ($service['features']): ?>
                            <div class="service-features">
                                <?php
                                $features = explode("\n", $service['features']);
                                foreach (array_filter($features) as $feature):
                                ?>
                                    <div class="feature-item">
                                        <span class="feature-check">✓</span>
                                        <span><?php echo clean(trim($feature)); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($service['price']): ?>
                            <div class="service-price">
                                <?php echo clean($service['price']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <a href="contact" class="btn btn-primary btn-block">
                            <span>Get Started</span>
                            <span style="margin-left: 0.5rem;">→</span>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <!-- CTA Section -->
        <div class="glass-card text-center" data-aos="fade-up" style="margin-top: var(--spacing-2xl); padding: var(--spacing-2xl);">
            <h2 style="margin-bottom: var(--spacing-md);">Need a Custom Solution?</h2>
            <p class="text-muted" style="font-size: 1.125rem; margin-bottom: var(--spacing-lg); max-width: 600px; margin-left: auto; margin-right: auto;">
                Every project is unique. Let's discuss your specific requirements and create a tailored solution that fits your needs perfectly.
            </p>
            <div style="display: flex; gap: var(--spacing-md); justify-content:center; flex-wrap: wrap;">
                <a href="contact" class="btn btn-primary btn-lg">
                    <span>Contact Me</span>
                    <span style="margin-left: 0.5rem;">✉️</span>
                </a>
                <a href="projects" class="btn btn-outline btn-lg">
                    <span>View Portfolio</span>
                    <span style="margin-left: 0.5rem;">→</span>
                </a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<style>
.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-xl);
}

.service-card {
    background: rgba(30, 41, 59, 0.5);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    padding: var(--spacing-xl);
    transition: var(--transition);
    display: flex;
    flex-direction: column;
}

.service-card:hover {
    transform: translateY(-8px);
    border-color: rgba(79, 70, 229, 0.5);
    box-shadow: var(--shadow-lg);
}

.service-icon {
    font-size: 3.5rem;
    margin-bottom: var(--spacing-md);
    line-height: 1;
}

.service-title {
    font-size: 1.5rem;
    margin-bottom: var(--spacing-sm);
}

.service-description {
    color: var(--color-text-muted);
    line-height: 1.7;
    margin-bottom: var(--spacing-lg);
    flex-grow: 1;
}

.service-features {
    margin-bottom: var(--spacing-lg);
}

.feature-item {
    display: flex;
    gap: var(--spacing-sm);
    align-items: flex-start;
    margin-bottom: var(--spacing-sm);
    font-size: 0.875rem;
}

.feature-check {
    color: var(--color-accent);
    font-weight: 700;
    flex-shrink: 0;
}

.service-price {
    font-size: 1.5rem;
    font-weight: 700;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    color: transparent;
    margin-bottom: var(--spacing-lg);
    text-align: center;
}

.btn-block {
    width: 100%;
    justify-content: center;
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
