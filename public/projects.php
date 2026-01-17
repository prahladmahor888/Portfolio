<?php
/**
 * Projects Page - All Projects with Filtering
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/helpers/helpers.php';
require_once __DIR__ . '/../app/models/Project.php';
require_once __DIR__ . '/../app/models/Setting.php';

$projectModel = new Project();
$settingModel = new Setting();

$category = isset($_GET['category']) ? sanitizeInput($_GET['category']) : null;
$projects = $category ? $projectModel->getActive($category) : $projectModel->getActive();
$categories = $projectModel->getCategories();
$settings = $settingModel->getAll();

$pageTitle = 'Projects';
$metaDescription = 'Browse my portfolio of web development projects and applications.';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';
?>

<section class="section" style="padding-top: 120px;">
    <div class="container">
        <!-- Page Header -->
        <div class="section-header" data-aos="fade-up">
            <h1 class="section-title">My Projects</h1>
            <p class="section-subtitle">Showcasing my work and achievements</p>
        </div>
        
        <!-- Filter Buttons -->
        <?php if (!empty($categories)): ?>
            <div class="filter-buttons" data-aos="fade-up">
                <a href="projects" class="filter-btn <?php echo !$category ? 'active' : ''; ?>">All Projects</a>
                <?php foreach ($categories as $cat): ?>
                    <a href="?category=<?php echo urlencode($cat); ?>" class="filter-btn <?php echo $category === $cat ? 'active' : ''; ?>">
                        <?php echo clean($cat); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <!-- Projects Grid -->
        <?php if (empty($projects)): ?>
            <div class="empty-state" data-aos="fade-up">
                <div style="font-size: 4rem; margin-bottom: 1rem;">📁</div>
                <h3>No Projects Found</h3>
                <p class="text-muted">
                    <?php echo $category ? "No projects in this category yet." : "Check back soon for exciting projects!"; ?>
                </p>
                <?php if ($category): ?>
                    <a href="projects" class="btn btn-primary mt-2">View All Projects</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="projects-grid">
                <?php foreach ($projects as $index => $project): ?>
                    <div class="project-card-enhanced" data-aos="fade-up" data-aos-delay="<?php echo ($index % 6) * 100; ?>">
                        <div class="project-image-wrapper">
                            <?php if ($project['image']): ?>
                                <img src="<?php echo getFileUrl($project['image']); ?>" alt="<?php echo clean($project['title']); ?>" class="project-image">
                            <?php else: ?>
                                <div class="project-placeholder">
                                    <span style="font-size: 3rem;">💻</span>
                                </div>
                            <?php endif; ?>
                            <?php if ($project['category']): ?>
                                <div class="project-category-badge">
                                    <?php echo clean($project['category']); ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($project['is_featured']): ?>
                                <div class="project-featured-badge">
                                    ⭐ Featured
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="project-content">
                            <h3 class="project-title"><?php echo clean($project['title']); ?></h3>
                            <p class="project-description"><?php echo clean($project['description']); ?></p>
                            
                            <div class="project-tech-tags">
                                <?php
                                $techs = array_map('trim', explode(',', $project['tech_stack']));
                                foreach ($techs as $tech):
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
                                        <span>View Code</span>
                                        <span>💻</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<style>
.filter-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: var(--spacing-sm);
    justify-content: center;
    margin-bottom: var(--spacing-xl);
}

.filter-btn {
    padding: 0.625rem 1.5rem;
    background: rgba(30, 41, 59, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 500;
    transition: var(--transition);
    color: var(--color-text);
}

.filter-btn:hover,
.filter-btn.active {
    background: var(--gradient-primary);
    border-color: transparent;
    transform: translateY(-2px);
}

.project-featured-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    padding: 0.375rem 0.875rem;
    background: rgba(251, 191, 36, 0.9);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--color-bg);
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
