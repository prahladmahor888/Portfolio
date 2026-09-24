<?php
/**
 * Portfolio Homepage - Root Index
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/helpers/helpers.php';
require_once __DIR__ . '/app/models/Project.php';
require_once __DIR__ . '/app/models/Service.php';
require_once __DIR__ . '/app/models/Skill.php';
require_once __DIR__ . '/app/models/Setting.php';

// Get data
$projectModel = new Project();
$serviceModel = new Service();
$skillModel = new Skill();
$settingModel = new Setting();

$featuredProjects = $projectModel->getFeatured(6);
$services = $serviceModel->getActive();
$skillsByCategory = $skillModel->getByCategory();
$settings = $settingModel->getAll();

$heroTitle = $settings['hero_title'] ?? "Hi, I'm Your Name";
$heroSubtitle = $settings['hero_subtitle'] ?? "I build amazing digital experiences";

$pageTitle = 'Home';
$metaDescription = $settings['site_description'] ?? 'Professional portfolio';
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';
?>

<!-- Hero Section -->
<section class="hero" id="home">
    <div class="container">
        <div class="hero-content">
            <div class="hero-badge" data-aos="fade-down">
                <span>👋</span> Welcome to my portfolio
            </div>
            <h1 class="hero-title" data-aos="fade-up" data-aos-delay="100"><?php echo clean($heroTitle); ?></h1>
            <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="200"><?php echo clean($heroSubtitle); ?></p>
            <div class="hero-buttons" data-aos="fade-up" data-aos-delay="300">
                <a href="#projects" class="btn btn-primary">
                    <span>View My Work</span>
                    <span style="margin-left: 0.5rem;">→</span>
                </a>
                <a href="public/contact.php" class="btn btn-outline">
                    <span>Get In Touch</span>
                    <span style="margin-left: 0.5rem;">✉️</span>
                </a>
                <a href="resume.pdf" class="btn btn-outline" target="_blank" download="Prahalad_Mahour_Resume.pdf"><i class="fas fa-file-download"></i> Download CV</a>
            </div>
            
            <!-- Scroll Indicator -->
            <div class="scroll-indicator" data-aos="fade-up" data-aos-delay="400">
                <span>Scroll to explore</span>
                <div class="scroll-arrow">↓</div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-box" data-aos="zoom-in" data-aos-delay="100">
                <div class="stat-number" data-count="<?php echo count($featuredProjects); ?>">0</div>
                <div class="stat-label">Projects Completed</div>
            </div>
            <div class="stat-box" data-aos="zoom-in" data-aos-delay="200">
                <div class="stat-number" data-count="<?php echo count($skillsByCategory); ?>">0</div>
                <div class="stat-label">Skill Categories</div>
            </div>
            <div class="stat-box" data-aos="zoom-in" data-aos-delay="300">
                <div class="stat-number" data-count="<?php echo count($services); ?>">0</div>
                <div class="stat-label">Services Offered</div>
            </div>
            <div class="stat-box" data-aos="zoom-in" data-aos-delay="400">
                <div class="stat-number" data-count="100">0</div>
                <div class="stat-label">Client Satisfaction</div>
                <span style="color: var(--color-accent); font-size: 1.25rem;">%</span>
            </div>
        </div>
    </div>
</section>

<!-- Technology Stack -->
<section class="section tech-stack-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title">Tech Stack</h2>
            <p class="section-subtitle">Technologies I work with</p>
        </div>
        
        <?php
        // Fetch skills from database
        // $skillModel is already instantiated at top of file
        $skills = $skillModel->all([], 'category ASC, sort_order ASC');
        ?>

        <div class="tech-grid" data-aos="fade-up" data-aos-delay="200">
            <?php if (!empty($skills)): ?>
                <?php foreach ($skills as $skill): ?>
                    <div class="tech-item">
                        <div class="tech-icon">
                            <?php 
                            $icon = $skill['icon'] ?? '';
                            $ext = pathinfo($icon, PATHINFO_EXTENSION);
                            $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']);
                            
                            if ($isImage): 
                            ?>
                                <img src="<?php echo getFileUrl($icon); ?>" alt="<?php echo clean($skill['name']); ?>" style="width: 2.5rem; height: 2.5rem; object-fit: contain;">
                            <?php elseif (!empty($icon) && strpos($icon, 'fa-') !== false): ?>
                                <i class="<?php echo clean($icon); ?>"></i>
                            <?php else: ?>
                                <?php echo !empty($icon) ? clean($icon) : '🚀'; ?>
                            <?php endif; ?>
                        </div>
                        <span><?php echo clean($skill['name']); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback if no skills in DB -->
                <div class="tech-item">
                    <div class="tech-icon">🚀</div>
                    <span>Full Stack Development</span>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- About Preview -->
<section class="section" id="about-preview">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title">About Me</h2>
        </div>
        <div class="grid grid-2" data-aos="fade-up" data-aos-delay="200" style="align-items: start;">
            <div class="glass-card" style="height: fit-content; align-self: start;">
                <p class="text-lg" style="line-height: 1.8; margin-bottom: 1.5rem;">
                    <?php echo getBioPreview($settings['about_bio'] ?? 'Passionate developer creating innovative solutions.', 220); ?>
                </p>
                <a href="public/about.php" class="btn btn-primary">Learn More →</a>
            </div>
            <div style="height: fit-content; align-self: start;">
                <?php if (!empty($skillsByCategory)): ?>
                    <?php $count = 0; foreach ($skillsByCategory as $category => $skills): ?>
                        <?php if ($count++ >= 2) break; ?>
                        <h4><?php echo clean($category); ?></h4>
                        <?php foreach (array_slice($skills, 0, 3) as $skill): ?>
                            <div class="skill-item">
                                <div class="skill-header">
                                    <span><?php echo clean($skill['name']); ?></span>
                                    <span><?php echo $skill['level']; ?>%</span>
                                </div>
                                <div class="skill-bar">
                                    <div class="skill-progress" style="width: <?php echo $skill['level']; ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Featured Projects -->
<section class="section" id="projects">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title">Featured Projects</h2>
            <p class="section-subtitle">Check out some of my recent work</p>
        </div>
        
        <?php if (empty($featuredProjects)): ?>
            <div class="empty-state" data-aos="fade-up">
                <div style="font-size: 4rem; margin-bottom: 1rem;">📁</div>
                <h3>No Projects Yet</h3>
                <p class="text-muted">Check back soon for exciting projects!</p>
            </div>
        <?php else: ?>
            <div class="projects-grid">
                <?php foreach ($featuredProjects as $index => $project): ?>
                    <div class="project-card-enhanced" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                        <div class="project-image-wrapper">
                            <?php if ($project['image']): ?>
                                <img src="<?php echo getFileUrl($project['image']); ?>" alt="<?php echo clean($project['title']); ?>" class="project-image">
                            <?php else: ?>
                                <div class="project-placeholder">
                                    <span style="font-size: 3rem;">💻</span>
                                </div>
                            <?php endif; ?>
                            <div class="project-category-badge">
                                <?php echo clean($project['category'] ?? 'Project'); ?>
                            </div>
                        </div>
                        
                        <div class="project-content">
                            <h3 class="project-title"><?php echo clean($project['title']); ?></h3>
                            <p class="project-description"><?php echo clean(truncate($project['description'], 120)); ?></p>
                            
                            <div class="project-tech-tags">
                                <?php
                                $techs = array_map('trim', explode(',', $project['tech_stack']));
                                foreach (array_slice($techs, 0, 4) as $tech):
                                ?>
                                    <span class="tech-tag"><?php echo clean($tech); ?></span>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="project-actions">
                                <?php if ($project['live_url']): ?>
                                    <a href="<?php echo clean($project['live_url']); ?>" target="_blank" class="project-link">
                                        <span>Live Demo</span>
                                        <span>🔗</span>
                                    </a>
                                <?php endif; ?>
                                <?php if ($project['github_url']): ?>
                                    <a href="<?php echo clean($project['github_url']); ?>" target="_blank" class="project-link">
                                        <span>Code</span>
                                        <span>💻</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-3" data-aos="fade-up">
                <a href="public/projects.php" class="btn btn-primary btn-lg">
                    <span>View All Projects</span>
                    <span style="margin-left: 0.5rem;">→</span>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Services -->
<?php if (!empty($services)): ?>
<section class="section" id="services">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title">Services</h2>
            <p class="section-subtitle">What I can do for you</p>
        </div>
        <div class="grid grid-3">
            <?php foreach ($services as $index => $service): ?>
                <div class="glass-card" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">
                        <?php 
                        $icon = $service['icon'] ?: '⚙️';
                        // Decode potentially encoded HTML from old saves (double decode for safety)
                        $icon = html_entity_decode(html_entity_decode($service['icon'] ?: '⚙️'));
                        $icon = trim($icon);
                        
                        if (strpos($icon, '<') !== false) {
                            echo html_entity_decode($icon);
                        } elseif (stripos($icon, 'fa') !== false) {
                            echo '<i class="' . clean($icon) . '"></i>';
                        } else {
                            echo clean($icon);
                        }
                        ?>
                    </div>
                    <h3><?php echo clean($service['title']); ?></h3>
                    <p><?php echo clean($service['description']); ?></p>
                    <?php if ($service['price']): ?>
                        <p class="mt-2" style="color: var(--color-accent); font-weight: 600;"><?php echo clean($service['price']); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Contact CTA -->
<section class="section" id="contact-cta">
    <div class="container">
        <div class="glass-card text-center" data-aos="fade-up" style="padding: 3rem;">
            <h2>Let's Work Together</h2>
            <p class="text-muted" style="font-size: 1.125rem; margin-bottom: 2rem;">
                Have a project in mind? Let's discuss how I can help bring your ideas to life.
            </p>
            <a href="public/contact.php" class="btn btn-primary">Get In Touch</a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

<!-- AOS Animation Library -->
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true,
        offset: 100
    });
</script>
