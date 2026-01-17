<?php
/**
 * About Page - Full Biography and Skills
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/helpers/helpers.php';
require_once __DIR__ . '/../app/models/Skill.php';
require_once __DIR__ . '/../app/models/Setting.php';
require_once __DIR__ . '/../app/models/Experience.php';

$skillModel = new Skill();
$settingModel = new Setting();
$experienceModel = new Experience();

$skillsByCategory = $skillModel->getByCategory();
$settings = $settingModel->getAll();
$experiences = [];
try {
    $experiences = $experienceModel->getAllOrdered();
} catch (Exception $e) {
    // Fail silently if table not found, just show empty timeline
}

$pageTitle = 'About Me';
$metaDescription = 'Learn more about my background, skills, and experience in web development.';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';
?>

<section class="section" style="padding-top: 120px;">
    <div class="container">
        <!-- Page Header -->
        <div class="section-header" data-aos="fade-up">
            <h1 class="section-title">About Me</h1>
            <p class="section-subtitle">Get to know me better</p>
        </div>
        
        <!-- Bio Section -->
        <div class="grid grid-2" style="align-items: start; margin-bottom: var(--spacing-2xl);">
            <div class="glass-card" data-aos="fade-right">
                <h2 style="margin-bottom: var(--spacing-md);">Who I Am</h2>
                <p style="line-height: 1.8; margin-bottom: var(--spacing-md);">
                    <?php echo nl2br(clean($settings['about_bio'] ?? 'Passionate developer creating innovative solutions.')); ?>
                </p>
                <p style="line-height: 1.8; margin-bottom: var(--spacing-md); color: var(--color-text-muted);">
                    I specialize in building modern web applications with clean code, excellent user experience, and scalable architecture. 
                    My journey in tech has equipped me with a diverse skill set and a problem-solving mindset.
                </p>
                <div style="display: flex; gap: var(--spacing-md); margin-top: var(--spacing-lg);">
                    <a href="../index#projects" class="btn btn-primary">View My Work</a>
                    <a href="contact" class="btn btn-outline">Get In Touch</a>
                </div>
            </div>
            
            <div data-aos="fade-left">
                <div class="glass-card">
                    <h3 style="margin-bottom: var(--spacing-md);">Quick Facts</h3>
                    <div class="fact-list">
                        <div class="fact-item">
                            <span class="fact-icon">📍</span>
                            <div>
                                <strong>Location</strong>
                                <p><?php echo clean($settings['site_address'] ?? 'Available Worldwide'); ?></p>
                            </div>
                        </div>
                        <div class="fact-item">
                            <span class="fact-icon">✉️</span>
                            <div>
                                <strong>Email</strong>
                                <p><a href="mailto:<?php echo clean($settings['site_email'] ?? ''); ?>"><?php echo clean($settings['site_email'] ?? 'Contact me'); ?></a></p>
                            </div>
                        </div>
                        <div class="fact-item">
                            <span class="fact-icon">💼</span>
                            <div>
                                <strong>Work Status</strong>
                                <p>Available for Projects</p>
                            </div>
                        </div>
                        <div class="fact-item">
                            <span class="fact-icon">🎯</span>
                            <div>
                                <strong>Focus</strong>
                                <p>Full Stack Development</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Skills Section -->
        <div style="margin-bottom: var(--spacing-2xl);">
            <div class="section-header" data-aos="fade-up">
                <h2 class="section-title">Technical Skills</h2>
                <p class="section-subtitle">Proficiency in various technologies</p>
            </div>
            
            <?php if (!empty($skillsByCategory)): ?>
                <div class="skills-categories">
                    <?php foreach ($skillsByCategory as $category => $skills): ?>
                        <div class="skill-category-box" data-aos="fade-up">
                            <h3 class="category-title"><?php echo clean($category); ?></h3>
                            <div class="skills-list">
                                <?php foreach ($skills as $skill): ?>
                                    <div class="skill-item-fancy">
                                        <div class="skill-header-fancy">
                                            <span class="skill-name"><?php echo clean($skill['name']); ?></span>
                                            <span class="skill-percentage"><?php echo $skill['level']; ?>%</span>
                                        </div>
                                        <div class="skill-bar-fancy">
                                            <div class="skill-progress-fancy" style="width: 0%" data-width="<?php echo $skill['level']; ?>%"></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Experience Timeline -->
        <div data-aos="fade-up">
            <div class="section-header">
                <h2 class="section-title">Experience & Education</h2>
                <p class="section-subtitle">My professional journey</p>
            </div>
            
            <div class="timeline">
                <?php foreach($experiences as $index => $exp): ?>
                <div class="timeline-item" data-aos="fade-right" data-aos-delay="<?php echo $index * 100; ?>">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content glass-card">
                        <span class="timeline-date"><?php echo clean($exp['year_range']); ?></span>
                        
                        <h3><?php echo clean($exp['title']); ?></h3>
                        <p class="timeline-company"><?php echo clean($exp['company']); ?></p>
                        <p><?php echo nl2br(clean($exp['description'])); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<style>
.fact-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.fact-item {
    display: flex;
    gap: var(--spacing-md);
    align-items: flex-start;
}

.fact-icon {
    font-size: 1.5rem;
    line-height: 1;
}

.fact-item strong {
    display: block;
    margin-bottom: 0.25rem;
}

.fact-item p {
    margin: 0;
    color: var(--color-text-muted);
    font-size: 0.875rem;
}

.skills-categories {
    display: grid;
    gap: var(--spacing-lg);
}

.skill-category-box {
    background: rgba(30, 41, 59, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    padding: var(--spacing-lg);
}

.category-title {
    color: var(--color-accent);
    margin-bottom: var(--spacing-md);
    font-size: 1.25rem;
}

.skills-list {
    display: grid;
    gap: var(--spacing-md);
}

.skill-item-fancy {
    padding: var(--spacing-sm) 0;
}

.skill-header-fancy {
    display: flex;
    justify-content: space-between;
    margin-bottom: var(--spacing-xs);
    font-size: 0.875rem;
}

.skill-name {
    font-weight: 500;
}

.skill-percentage {
    color: var(--color-accent);
    font-weight: 600;
}

.skill-bar-fancy {
    height: 8px;
    background: var(--color-bg-light);
    border-radius: 10px;
    overflow: hidden;
}

.skill-progress-fancy {
    height: 100%;
    background: var(--gradient-primary);
    border-radius: 10px;
    transition: width 1.5s ease;
    box-shadow: 0 0 10px rgba(79, 70, 229, 0.5);
}

.timeline {
    position: relative;
    padding-left: var(--spacing-xl);
}

.timeline::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--gradient-primary);
}

.timeline-item {
    position: relative;
    margin-bottom: var(--spacing-xl);
}

.timeline-marker {
    position: absolute;
    left: -42px;
    top: 0;
    width: 16px;
    height: 16px;
    background: var(--color-primary);
    border: 3px solid var(--color-bg);
    border-radius: 50%;
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.2);
}

.timeline-content {
    padding: var(--spacing-lg);
}

.timeline-date {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: rgba(79, 70, 229, 0.2);
    border: 1px solid var(--color-primary);
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-bottom: var(--spacing-sm);
}

.timeline-content h3 {
    margin-bottom: 0.25rem;
}

.timeline-company {
    color: var(--color-accent);
    font-weight: 500;
    margin-bottom: var(--spacing-sm);
}

.timeline-content p:last-child {
    color: var(--color-text-muted);
    line-height: 1.6;
    margin: 0;
}
</style>

<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
AOS.init({
    duration: 800,
    once: true
});

// Animate skill bars when in view
const skillBars = document.querySelectorAll('.skill-progress-fancy');
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const width = entry.target.getAttribute('data-width');
            entry.target.style.width = width;
            observer.unobserve(entry.target);
        }
    });
}, { threshold: 0.5 });

skillBars.forEach(bar => observer.observe(bar));
</script>
