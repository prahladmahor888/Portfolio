<?php
/**
 * Blog Page - All Blog Posts
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/helpers/helpers.php';
require_once __DIR__ . '/../app/models/Blog.php';
require_once __DIR__ . '/../app/models/Setting.php';

$blogModel = new Blog();
$settingModel = new Setting();

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = BLOG_PER_PAGE;
$offset = ($page - 1) * $limit;

$blogs = $blogModel->getPublished($limit, $offset);
$totalBlogs = $blogModel->count(['status' => 'published']);
$totalPages = ceil($totalBlogs / $limit);
$settings = $settingModel->getAll();

$pageTitle = 'Blog';
$metaDescription = 'Read my latest articles and insights on web development, programming, and technology.';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';
?>

<section class="section" style="padding-top: 120px;">
    <div class="container">
        <!-- Page Header -->
        <div class="section-header" data-aos="fade-up">
            <h1 class="section-title">Blog</h1>
            <p class="section-subtitle">Insights, tutorials, and thoughts</p>
        </div>
        
        <!-- Blog Grid -->
        <?php if (empty($blogs)): ?>
            <div class="empty-state" data-aos="fade-up">
                <div style="font-size: 4rem; margin-bottom: 1rem;">📝</div>
                <h3>No Blog Posts Yet</h3>
                <p class="text-muted">Check back soon for interesting articles!</p>
            </div>
        <?php else: ?>
            <div class="blog-grid">
                <?php foreach ($blogs as $index => $blog): ?>
                    <article class="blog-card" data-aos="fade-up" data-aos-delay="<?php echo ($index % 3) * 100; ?>">
                        <?php if ($blog['image']): ?>
                            <div class="blog-image-wrapper">
                                <img src="<?php echo getFileUrl($blog['image']); ?>" alt="<?php echo clean($blog['title']); ?>" class="blog-image">
                                <?php if ($blog['category']): ?>
                                    <div class="meta-item">
                                    <?php 
                                    $wordCount = str_word_count(strip_tags($blog['content']));
                                    $readingTime = ceil($wordCount / 200);
                                    ?>
                                    <i class="far fa-clock"></i> <?php echo $readingTime; ?> min read
                                </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="blog-content">
                            <div class="blog-meta">
                                <span class="blog-date">📅 <?php echo formatDate($blog['created_at'], 'M d, Y'); ?></span>
                                <span class="blog-views">👁️ <?php echo number_format($blog['views']); ?> views</span>
                            </div>
                            
                            <h2 class="blog-title">
                                <a href="blog-details.php?slug=<?php echo urlencode($blog['slug']); ?>">
                                    <?php echo clean($blog['title']); ?>
                                </a>
                            </h2>
                            
                            <?php if ($blog['excerpt']): ?>
                                <p class="blog-excerpt"><?php echo clean($blog['excerpt']); ?></p>
                            <?php else: ?>
                                <p class="blog-excerpt"><?php echo clean(truncate(strip_tags($blog['content']), 150)); ?></p>
                            <?php endif; ?>
                            
                            <?php if (!empty($blog['tags'])): ?>
                                <div class="blog-tags">
                                    <?php
                                    $tags = array_filter(array_map('trim', explode(',', $blog['tags'])));
                                    foreach (array_slice($tags, 0, 3) as $tag):
                                        if (empty($tag)) continue;
                                    ?>
                                        <span class="blog-tag">#<?php echo clean(ltrim($tag, '#')); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            
                            <a href="blog-details.php?slug=<?php echo urlencode($blog['slug']); ?>" class="read-more-btn">
                                <span>Read More</span>
                                <span>→</span>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="pagination" data-aos="fade-up">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>" class="pagination-btn">← Previous</a>
                    <?php endif; ?>
                    
                    <div class="pagination-numbers">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="?page=<?php echo $i; ?>" class="pagination-number <?php echo $i === $page ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                    
                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?php echo $page + 1; ?>" class="pagination-btn">Next →</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<style>
.blog-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: var(--spacing-xl);
    margin-bottom: var(--spacing-2xl);
}

.blog-card {
    background: rgba(30, 41, 59, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    overflow: hidden;
    transition: var(--transition);
    display: flex;
    flex-direction: column;
}

.blog-card:hover {
    transform: translateY(-8px);
    border-color: rgba(79, 70, 229, 0.5);
    box-shadow: var(--shadow-lg);
}

.blog-image-wrapper {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.blog-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.blog-card:hover .blog-image {
    transform: scale(1.1);
}

.blog-category-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    padding: 0.375rem 0.875rem;
    background: rgba(79, 70, 229, 0.9);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.blog-content {
    padding: var(--spacing-lg);
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.blog-meta {
    display: flex;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-sm);
    font-size: 0.75rem;
    color: var(--color-text-muted);
}

.blog-title {
    font-size: 1.25rem;
    margin-bottom: var(--spacing-sm);
}

.blog-title a {
    color: var(--color-text);
    transition: var(--transition);
}

.blog-title a:hover {
    color: var(--color-accent);
}

.blog-excerpt {
    color: var(--color-text-muted);
    line-height: 1.7;
    margin-bottom: var(--spacing-md);
    flex-grow: 1;
}

.blog-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: var(--spacing-md);
}

.blog-tag {
    font-size: 0.75rem;
    color: var(--color-accent);
    font-weight: 500;
}

.read-more-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--color-accent);
    font-weight: 600;
    font-size: 0.875rem;
    transition: var(--transition);
}

.read-more-btn:hover {
    gap: 0.75rem;
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: var(--spacing-md);
}

.pagination-btn,
.pagination-number {
    padding: 0.625rem 1.25rem;
    background: rgba(30, 41, 59, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    color: var(--color-text);
    font-weight: 500;
    transition: var(--transition);
}

.pagination-btn:hover,
.pagination-number:hover {
    background: var(--color-primary);
    border-color: var(--color-primary);
}

.pagination-number.active {
    background: var(--gradient-primary);
    border-color: transparent;
}

.pagination-numbers {
    display: flex;
    gap: 0.5rem;
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
