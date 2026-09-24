<?php
/**
 * Portfolio Homepage
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/helpers/helpers.php';
require_once __DIR__ . '/../app/models/Project.php';
require_once __DIR__ . '/../app/models/Service.php';
require_once __DIR__ . '/../app/models/Skill.php';
require_once __DIR__ . '/../app/models/Setting.php';

// Get data
$projectModel = new Project();
$serviceModel = new Service();
$skillModel = new Skill();
$settingModel = new Setting();

$featuredProjects = $projectModel->getFeatured(6);
$services = $serviceModel->getActive();
$skillsByCategory = $skillModel->getByCategory();
$settings = $settingModel->getAll();

$heroTitle = $settings['hero_title'] ?? 'Hi, I\'m Your Name';
$heroSubtitle = $settings['hero_subtitle'] ?? 'I build amazing digital experiences';

$pageTitle = 'Home';
$metaDescription = $settings['site_description'] ?? 'Professional portfolio';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';
?>

<!-- Hero Section -->
<section class="hero" id="home">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title"><?php echo clean($heroTitle); ?></h1>
            <p class="hero-subtitle"><?php echo clean($heroSubtitle); ?></p>
            <div class="hero-buttons">
                <a href="#projects" class="btn btn-primary">View My Work</a>
                <a href="#contact" class="btn btn-outline">Get In Touch</a>
            </div>
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
                <a href="about" class="btn btn-primary">Learn More →</a>
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
            <p class="text-center text-muted">No projects to display yet.</p>
        <?php else: ?>
            <div class="grid grid-3">
                <?php foreach ($featuredProjects as $index => $project): ?>
                    <div class="glass-card project-card" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                        <?php if ($project['image']): ?>
                            <img src="<?php echo getFileUrl($project['image']); ?>" alt="<?php echo clean($project['title']); ?>" class="project-image">
                        <?php else: ?>
                            <div style="height: 250px; background: var(--gradient-card); display: flex; align-items: center; justify-content: center; color: var(--color-text-muted);">
                                No Image
                            </div>
                        <?php endif; ?>
                        <div class="project-overlay">
                            <h3><?php echo clean($project['title']); ?></h3>
                            <p><?php echo clean(truncate($project['description'], 100)); ?></p>
                            <div class="project-tags">
                                <?php
                                $techs = array_map('trim', explode(',', $project['tech_stack']));
                                foreach (array_slice($techs, 0, 3) as $tech):
                                ?>
                                    <span class="tag"><?php echo clean($tech); ?></span>
                                <?php endforeach; ?>
                            </div>
                            <div class="mt-2" style="display: flex; gap: 1rem;">
                                <?php if ($project['live_url']): ?>
                                    <a href="<?php echo clean($project['live_url']); ?>" target="_blank" class="btn btn-primary" style="font-size: 0.875rem; padding: 0.5rem 1rem;">Live Demo</a>
                                <?php endif; ?>
                                <?php if ($project['github_url']): ?>
                                    <a href="<?php echo clean($project['github_url']); ?>" target="_blank" class="btn btn-outline" style="font-size: 0.875rem; padding: 0.5rem 1rem;">GitHub</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-3">
                <a href="projects" class="btn btn-primary">View All Projects →</a>
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
                        $icon = $service['icon'] ?? '';
                        $ext = pathinfo($icon, PATHINFO_EXTENSION);
                        $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']);
                        
                        if ($isImage): 
                        ?>
                            <img src="<?php echo getFileUrl($icon); ?>" alt="<?php echo clean($service['title']); ?>" style="width: 3rem; height: 3rem; object-fit: contain;">
                        <?php elseif (!empty($icon) && strpos($icon, 'fa-') !== false): ?>
                            <i class="<?php echo clean($icon); ?>"></i>
                        <?php else: ?>
                            <?php echo !empty($icon) ? clean($icon) : '⚙️'; ?>
                        <?php endif; ?>
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
            <a href="contact" class="btn btn-primary">Get In Touch</a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>

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
