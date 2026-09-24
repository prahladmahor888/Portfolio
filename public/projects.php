<?php
/**
 * Projects Page - Modern Portfolio Showcase & Live Filtering
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

$totalProjects = count($projects);

$pageTitle = 'Projects';
$metaDescription = 'Browse my portfolio of software systems, web development projects, and Android mobile applications.';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';
?>

<section class="section projects-page" style="padding-top: 110px; padding-bottom: 80px;">
    <div class="container">
        
        <!-- Page Header -->
        <div class="section-header" data-aos="fade-up">
            <div class="hero-badge" style="margin-bottom: 0.5rem;">
                <span>🚀</span> Portfolio &amp; Work
            </div>
            <h1 class="section-title">Featured Projects</h1>
            <p class="section-subtitle">Showcasing my work in Android, Web, Backend Systems &amp; AI</p>
        </div>

        <!-- Filter & Search Controls Bar -->
        <div class="projects-control-bar" data-aos="fade-up" data-aos-delay="100">
            <!-- Category Pills -->
            <div class="filter-pills-wrap">
                <a href="projects" class="filter-pill <?php echo !$category ? 'active' : ''; ?>">
                    <span>All</span>
                    <span class="pill-counter"><?php echo !$category ? $totalProjects : ''; ?></span>
                </a>
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $cat): ?>
                        <a href="?category=<?php echo urlencode($cat); ?>" class="filter-pill <?php echo $category === $cat ? 'active' : ''; ?>">
                            <span><?php echo clean($cat); ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Live Search Box -->
            <div class="project-search-box">
                <span class="search-icon">🔍</span>
                <input type="text" id="projectSearchInput" placeholder="Search by title or tech (e.g. Java, SQL)..." autocomplete="off">
            </div>
        </div>
        
        <!-- Projects Grid -->
        <?php if (empty($projects)): ?>
            <div class="empty-state-box glass-card" data-aos="fade-up">
                <div class="empty-icon">📁</div>
                <h3>No Projects Found</h3>
                <p class="empty-sub">
                    <?php echo $category ? "No projects found under the category \"<strong>" . clean($category) . "</strong>\"." : "Projects are currently being updated. Please check back soon!"; ?>
                </p>
                <?php if ($category): ?>
                    <a href="projects" class="btn btn-primary" style="margin-top: 1rem;">View All Projects</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="projects-showcase-grid" id="projectsGrid">
                <?php foreach ($projects as $index => $project): ?>
                    <article class="project-glass-card glass-card" data-aos="fade-up" data-aos-delay="<?php echo ($index % 4) * 80; ?>" 
                             data-title="<?php echo strtolower(clean($project['title'])); ?>" 
                             data-tech="<?php echo strtolower(clean($project['tech_stack'] ?? '')); ?>"
                             data-desc="<?php echo strtolower(clean($project['description'] ?? '')); ?>">
                        
                        <!-- Card Banner / Image -->
                        <div class="project-banner-wrapper">
                            <?php if (!empty($project['image'])): ?>
                                <img src="<?php echo getFileUrl($project['image']); ?>" alt="<?php echo clean($project['title']); ?>" class="project-banner-img" loading="lazy">
                            <?php else: ?>
                                <div class="project-fallback-banner">
                                    <div class="fallback-glow"></div>
                                    <div class="fallback-icon">
                                        <?php 
                                        $catLower = strtolower($project['category'] ?? '');
                                        if (strpos($catLower, 'android') !== false || strpos($catLower, 'app') !== false) {
                                            echo '📱';
                                        } elseif (strpos($catLower, 'ai') !== false || strpos($catLower, 'machine') !== false) {
                                            echo '🤖';
                                        } elseif (strpos($catLower, 'saas') !== false || strpos($catLower, 'cloud') !== false) {
                                            echo '☁️';
                                        } else {
                                            echo '💻';
                                        }
                                        ?>
                                    </div>
                                    <span class="fallback-label"><?php echo clean($project['title']); ?></span>
                                </div>
                            <?php endif; ?>

                            <!-- Badges Overlay -->
                            <div class="project-badges-overlay">
                                <?php if (!empty($project['category'])): ?>
                                    <span class="category-badge-chip"><?php echo clean($project['category']); ?></span>
                                <?php endif; ?>

                                <?php if (!empty($project['is_featured'])): ?>
                                    <span class="featured-badge-chip">⭐ Featured</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Card Content -->
                        <div class="project-card-details">
                            <h3 class="project-card-heading"><?php echo clean($project['title']); ?></h3>
                            <p class="project-card-summary">
                                <?php echo clean($project['description']); ?>
                            </p>
                            
                            <!-- Tech Stack Chips -->
                            <?php if (!empty($project['tech_stack'])): ?>
                                <div class="project-tech-pills">
                                    <?php
                                    $techs = array_filter(array_map('trim', explode(',', $project['tech_stack'])));
                                    foreach ($techs as $tech):
                                    ?>
                                        <span class="tech-pill-tag"><?php echo clean($tech); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Action Links -->
                            <div class="project-card-actions">
                                <?php if (!empty($project['live_url'])): ?>
                                    <a href="<?php echo clean($project['live_url']); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm project-action-btn">
                                        <span>Live Demo</span>
                                        <span style="font-size: 0.85rem;">↗</span>
                                    </a>
                                <?php endif; ?>

                                <?php if (!empty($project['github_url'])): ?>
                                    <a href="<?php echo clean($project['github_url']); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline btn-sm project-action-btn">
                                        <span><i class="fab fa-github"></i> Code</span>
                                    </a>
                                <?php endif; ?>

                                <?php if (empty($project['live_url']) && empty($project['github_url'])): ?>
                                    <span class="status-indicator-badge">
                                        <span>✓ Complete</span>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                    </article>
                <?php endforeach; ?>
            </div>

            <!-- No search results placeholder -->
            <div id="noSearchMatch" class="empty-state-box glass-card" style="display: none; margin-top: 2rem;">
                <div class="empty-icon">🔍</div>
                <h3>No Matching Projects Found</h3>
                <p class="empty-sub">Try searching with a different keyword or technology name.</p>
                <button onclick="resetProjectSearch()" class="btn btn-primary" style="margin-top: 1rem;">Clear Search</button>
            </div>
        <?php endif; ?>

        <!-- Bottom Collaboration Callout -->
        <div class="projects-collab-cta glass-card" data-aos="fade-up">
            <div class="collab-cta-inner">
                <div>
                    <h3 style="margin: 0 0 0.4rem 0; font-size: 1.4rem;">Have an interesting project or idea?</h3>
                    <p style="margin: 0; color: #cbd5e1; font-size: 0.95rem;">Let's collaborate and bring your ideas to life with high-performance code.</p>
                </div>
                <a href="contact" class="btn btn-primary">
                    <span>Discuss A Project →</span>
                </a>
            </div>
        </div>

    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<!-- Projects Page Specific Styles -->
<style>
/* ---------------- PROJECTS PAGE STYLES ---------------- */
.projects-page {
    background: radial-gradient(circle at 50% 10%, rgba(79, 70, 229, 0.08), transparent 60%);
}

/* Control Bar (Filters & Search) */
.projects-control-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1.25rem;
    margin-bottom: 2.5rem;
}

.filter-pills-wrap {
    display: flex;
    flex-wrap: wrap;
    gap: 0.6rem;
    align-items: center;
}

.filter-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.35rem 0.85rem;
    background: rgba(30, 41, 59, 0.5);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 30px;
    font-size: 0.8rem;
    font-weight: 500;
    color: var(--color-text-secondary, #cbd5e1);
    text-decoration: none;
    transition: var(--transition);
}

.filter-pill:hover {
    background: rgba(79, 70, 229, 0.2);
    border-color: var(--color-primary);
    color: #ffffff;
    transform: translateY(-2px);
}

.filter-pill.active {
    background: var(--gradient-primary);
    border-color: transparent;
    color: #ffffff;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
}

.pill-counter {
    font-size: 0.7rem;
    opacity: 0.85;
}

/* Live Search Box */
.project-search-box {
    position: relative;
    min-width: 250px;
}

.project-search-box input {
    width: 100%;
    padding: 0.425rem 0.85rem 0.425rem 2.2rem;
    background: rgba(30, 41, 59, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 30px;
    color: #ffffff;
    font-size: 0.825rem;
    outline: none;
    transition: var(--transition);
}

.project-search-box input:focus {
    border-color: var(--color-primary);
    background: rgba(30, 41, 59, 0.9);
    box-shadow: 0 0 12px rgba(79, 70, 229, 0.3);
}

.project-search-box input::placeholder {
    color: #94a3b8;
}

.search-icon {
    position: absolute;
    left: 0.85rem;
    top: 50%;
    transform: translateY(-50%);
    font-size: 0.85rem;
    pointer-events: none;
    opacity: 0.7;
}

/* Projects Grid */
.projects-showcase-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.75rem;
    margin-bottom: 3.5rem;
}

/* Project Glass Card */
.project-glass-card {
    display: flex;
    flex-direction: column;
    height: 100%;
    padding: 0;
    overflow: hidden;
    border-radius: 14px;
    border: 1px solid rgba(255, 255, 255, 0.09);
    background: rgba(15, 23, 42, 0.7);
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s ease, border-color 0.3s ease;
}

.project-glass-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5), 0 0 20px rgba(79, 70, 229, 0.2);
    border-color: rgba(79, 70, 229, 0.5);
}

/* Project Banner */
.project-banner-wrapper {
    position: relative;
    width: 100%;
    height: 200px;
    background: #0f172a;
    overflow: hidden;
}

.project-banner-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.project-glass-card:hover .project-banner-img {
    transform: scale(1.05);
}

/* Fallback Banner */
.project-fallback-banner {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.9), rgba(15, 23, 42, 0.95));
    position: relative;
    padding: 1rem;
    text-align: center;
}

.fallback-glow {
    position: absolute;
    width: 120px;
    height: 120px;
    background: radial-gradient(circle, rgba(79, 70, 229, 0.35), transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}

.fallback-icon {
    font-size: 2.75rem;
    margin-bottom: 0.4rem;
    z-index: 1;
    filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.4));
    transition: transform 0.3s ease;
}

.project-glass-card:hover .fallback-icon {
    transform: scale(1.15);
}

.fallback-label {
    font-size: 0.85rem;
    color: #94a3b8;
    font-weight: 500;
    z-index: 1;
}

/* Overlay Badges */
.project-badges-overlay {
    position: absolute;
    top: 0.85rem;
    left: 0.85rem;
    right: 0.85rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 0.5rem;
    z-index: 2;
    pointer-events: none;
}

.category-badge-chip {
    padding: 0.25rem 0.65rem;
    background: rgba(15, 23, 42, 0.85);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 20px;
    font-size: 0.725rem;
    font-weight: 600;
    color: var(--color-accent);
}

.featured-badge-chip {
    padding: 0.25rem 0.65rem;
    background: rgba(245, 158, 11, 0.9);
    backdrop-filter: blur(8px);
    border-radius: 20px;
    font-size: 0.725rem;
    font-weight: 700;
    color: #0f172a;
    box-shadow: 0 2px 10px rgba(245, 158, 11, 0.4);
}

/* Card Details */
.project-card-details {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.project-card-heading {
    font-size: 1.25rem;
    font-weight: 700;
    color: #ffffff;
    margin: 0 0 0.5rem 0;
    line-height: 1.4;
}

.project-card-summary {
    font-size: 0.925rem;
    color: #cbd5e1;
    line-height: 1.65;
    margin: 0 0 1.25rem 0;
    flex-grow: 1;
}

/* Tech Pills */
.project-tech-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 0.4rem;
    margin-bottom: 1.5rem;
}

.tech-pill-tag {
    font-size: 0.75rem;
    padding: 0.25rem 0.6rem;
    background: rgba(30, 41, 59, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 6px;
    color: #e2e8f0;
    font-weight: 500;
}

/* Action Buttons */
.project-card-actions {
    display: flex;
    gap: 0.75rem;
    align-items: center;
    flex-wrap: wrap;
    padding-top: 1rem;
    border-top: 1px solid rgba(255, 255, 255, 0.06);
}

.project-action-btn {
    flex: 1;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    gap: 0.35rem;
    padding: 0.4rem 0.75rem;
    font-size: 0.825rem;
    border-radius: 6px;
    text-decoration: none;
}

.status-indicator-badge {
    font-size: 0.8rem;
    color: #10b981;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}

/* Empty States */
.empty-state-box {
    text-align: center;
    padding: 3.5rem 2rem;
    max-width: 600px;
    margin: 2rem auto;
}

.empty-icon {
    font-size: 3.5rem;
    margin-bottom: 1rem;
}

.empty-state-box h3 {
    font-size: 1.5rem;
    color: #ffffff;
    margin: 0 0 0.5rem 0;
}

.empty-sub {
    color: #94a3b8;
    line-height: 1.6;
    margin: 0;
}

/* Bottom Collab Banner */
.projects-collab-cta {
    padding: 2.25rem;
    border-color: rgba(79, 70, 229, 0.35);
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.18), rgba(124, 58, 237, 0.1));
}

.collab-cta-inner {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1.5rem;
}

/* ---------------- RESPONSIVE MEDIA QUERIES ---------------- */
@media (max-width: 850px) {
    .projects-control-bar {
        flex-direction: column;
        align-items: stretch;
    }
    .project-search-box {
        width: 100%;
    }
    .projects-showcase-grid {
        grid-template-columns: 1fr;
    }
    .collab-cta-inner {
        flex-direction: column;
        align-items: flex-start;
    }
    .collab-cta-inner .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<!-- Live Search JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('projectSearchInput');
    const projectCards = document.querySelectorAll('.project-glass-card');
    const noResults = document.getElementById('noSearchMatch');
    const projectsGrid = document.getElementById('projectsGrid');

    if (searchInput && projectCards.length > 0) {
        searchInput.addEventListener('input', function() {
            const query = this.value.trim().toLowerCase();
            let visibleCount = 0;

            projectCards.forEach(card => {
                const title = card.getAttribute('data-title') || '';
                const tech = card.getAttribute('data-tech') || '';
                const desc = card.getAttribute('data-desc') || '';

                if (query === '' || title.includes(query) || tech.includes(query) || desc.includes(query)) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (noResults) {
                noResults.style.display = (visibleCount === 0) ? 'block' : 'none';
            }
        });
    }
});

function resetProjectSearch() {
    const searchInput = document.getElementById('projectSearchInput');
    if (searchInput) {
        searchInput.value = '';
        searchInput.dispatchEvent(new Event('input'));
    }
}
</script>
