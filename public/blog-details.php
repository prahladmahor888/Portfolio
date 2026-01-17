<?php
/**
 * Blog Details Page - Individual Blog Post
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/helpers/helpers.php';
require_once __DIR__ . '/../app/models/Blog.php';
require_once __DIR__ . '/../app/models/Setting.php';

$blogModel = new Blog();
$settingModel = new Setting();

$slug = isset($_GET['slug']) ? sanitizeInput($_GET['slug']) : '';

if (!$slug) {
    header('Location: blog.php');
    exit;
}

$blog = $blogModel->findBySlug($slug);

if (!$blog || $blog['status'] !== 'published') {
    header('Location: blog.php');
    exit;
}

// Increment view count
$blogModel->incrementViews($blog['id']);

// Get related  posts
$relatedPosts = $blogModel->getPublished(3, 0, $blog['id']);

$settings = $settingModel->getAll();
$pageTitle = $blog['title'];
$metaDescription = $blog['meta_description'] ?: clean(truncate(strip_tags($blog['content']), 160));
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';
?>

<article class="section" style="padding-top: 120px;">
    <div class="container" style="max-width: 900px;">
        <!-- Blog Header -->
        <header class="blog-header" data-aos="fade-up">
            <?php if ($blog['category']): ?>
                <span class="blog-category-badge-large"><?php echo clean($blog['category']); ?></span>
            <?php endif; ?>
            
            <h1 class="blog-detail-title"><?php echo clean($blog['title']); ?></h1>
            
            <div class="blog-meta-large">
                <span>📅 <?php echo formatDate($blog['created_at'], 'F d, Y'); ?></span>
                <span>•</span>
                <span>👁️ <?php echo number_format($blog['views']); ?> views</span>
                <?php if ($blog['reading_time']): ?>
                            <span><i class="far fa-clock"></i> <?php 
                                $rt = isset($blog['reading_time']) ? $blog['reading_time'] : 0;
                                if(!$rt) $rt = ceil(str_word_count(strip_tags($blog['content'] ?? '')) / 200);
                                echo $rt; 
                            ?> min read</span>
                <?php endif; ?>
            </div>
            
            <?php if ($blog['tags']): ?>
                <div class="blog-tags-large">
                    <?php
                    $tags = array_map('trim', explode(',', $blog['tags']));
                    foreach ($tags as $tag):
                    ?>
                        <span class="blog-tag-large">#<?php echo clean($tag); ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </header>
        
        <!-- Featured Image -->
        <?php if ($blog['image']): ?>
            <div class="blog-featured-image" data-aos="fade-up">
                <img src="<?php echo getFileUrl($blog['image']); ?>" alt="<?php echo clean($blog['title']); ?>">
            </div>
        <?php endif; ?>
        
        <!-- Blog Content -->
        <div class="blog-content-area glass-card" data-aos="fade-up">
            <?php echo $blog['content']; // Content should be sanitized in admin ?>
        </div>
        
        <!-- Share Section -->
        <div class="share-section glass-card" data-aos="fade-up">
            <h3>Share this article</h3>
            <div class="share-buttons">
                <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($blog['title']); ?>&url=<?php echo urlencode(getCurrentUrl()); ?>" target="_blank" class="share-btn" title="Share on Twitter">
                    🐦 Twitter
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(getCurrentUrl()); ?>" target="_blank" class="share-btn" title="Share on Facebook">
                    📘 Facebook
                </a>
                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(getCurrentUrl()); ?>&title=<?php echo urlencode($blog['title']); ?>" target="_blank" class="share-btn" title="Share on LinkedIn">
                    💼 LinkedIn
                </a>
            </div>
        </div>
        
        <!-- Related Posts -->
        <?php if (!empty($relatedPosts)): ?>
            <div class="related-posts" data-aos="fade-up">
                <h2 class="section-title">Related Articles</h2>
                <div class="related-grid">
                    <?php foreach ($relatedPosts as $related): ?>
                        <a href="blog-details.php?slug=<?php echo urlencode($related['slug']); ?>" class="related-card">
                            <?php if ($related['image']): ?>
                                <img src="<?php echo getFileUrl($related['image']); ?>" alt="<?php echo clean($related['title']); ?>">
                            <?php endif; ?>
                            <div class="related-content">
                                <h4><?php echo clean($related['title']); ?></h4>
                                <span class="related-date">📅 <?php echo formatDate($related['created_at'], 'M d, Y'); ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Back to Blog -->
        <div class="text-center" data-aos="fade-up">
            <a href="blog" class="btn btn-outline btn-lg">
                <span>←</span>
                <span style="margin-left: 0.5rem;">Back to Blog</span>
            </a>
        </div>
    </div>
</article>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<style>
.blog-header {
    text-align: center;
    margin-bottom: var(--spacing-xl);
}

.blog-category-badge film-large {
    display: inline-block;
    padding: 0.5rem 1.25rem;
    background: rgba(79, 70, 229, 0.2);
    border: 1px solid var(--color-primary);
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: var(--spacing-md);
}

.blog-detail-title {
    font-size: clamp(2rem, 5vw, 3rem);
    margin-bottom: var(--spacing-md);
    line-height: 1.2;
}

.blog-meta-large {
    display: flex;
    justify-content: center;
    gap: var(--spacing-sm);
    color: var(--color-text-muted);
    font-size: 0.875rem;
    margin-bottom: var(--spacing-md);
    flex-wrap: wrap;
}

.blog-tags-large {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: var(--spacing-sm);
}

.blog-tag-large {
    color: var(--color-accent);
    font-weight: 500;
    font-size: 0.875rem;
}

.blog-featured-image {
    margin-bottom: var(--spacing-xl);
    border-radius: var(--border-radius);
    overflow: hidden;
}

.blog-featured-image img {
    width: 100%;
    height: auto;
    display: block;
}

.blog-content-area {
    padding: var(--spacing-xl);
    margin-bottom: var(--spacing-xl);
    line-height: 1.8;
}

.blog-content-area h2,
.blog-content-area h3,
.blog-content-area h4 {
    margin-top: var(--spacing-lg);
    margin-bottom: var(--spacing-md);
}

.blog-content-area p {
    margin-bottom: var(--spacing-md);
}

.blog-content-area ul,
.blog-content-area ol {
    margin-bottom: var(--spacing-md);
    padding-left: var(--spacing-lg);
}

.blog-content-area li {
    margin-bottom: var(--spacing-xs);
}

.blog-content-area img {
    max-width: 100%;
    border-radius: 8px;
    margin: var(--spacing-lg) 0;
}

.blog-content-area code {
    background: rgba(79, 70, 229, 0.1);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    font-size: 0.875em;
}

.blog-content-area pre {
    background: var(--color-bg-lighter);
    padding: var(--spacing-md);
    border-radius: 8px;
    overflow-x: auto;
    margin: var(--spacing-md) 0;
}

.share-section {
    padding: var(--spacing-lg);
    margin-bottom: var(--spacing-xl);
    text-align: center;
}

.share-section h3 {
    margin-bottom: var(--spacing-md);
}

.share-buttons {
    display: flex;
    justify-content: center;
    gap: var(--spacing-md);
    flex-wrap: wrap;
}

.share-btn {
    padding: 0.625rem 1.5rem;
    background: rgba(30, 41, 59, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 50px;
    font-weight: 500;
    transition: var(--transition);
}

.share-btn:hover {
    background: var(--gradient-primary);
    border-color: transparent;
    transform: translateY(-2px);
}

.related-posts {
    margin-bottom: var(--spacing-2xl);
}

.related-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-md);
    margin-top: var(--spacing-lg);
    margin-bottom: var(--spacing-lg);
}

.related-card {
    background: rgba(30, 41, 59, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    overflow: hidden;
    transition: var(--transition);
    display: block;
}

.related-card:hover {
    transform: translateY(-4px);
    border-color: var(--color-primary);
}

.related-card img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.related-content {
    padding: var(--spacing-md);
}

.related-content h4 {
    font-size: 1rem;
    margin-bottom: 0.5rem;
    color: var(--color-text);
}

.related-date {
    font-size: 0.75rem;
    color: var(--color-text-muted);
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
