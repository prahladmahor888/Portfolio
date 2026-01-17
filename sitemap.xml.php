<?php
/**
 * Dynamic XML Sitemap Generator
 * Generates sitemap from database content
 */

// Prevent any output before XML
ob_start();

// Database connection without session
try {
    $dsn = "mysql:host=localhost;dbname=portfolio_db;charset=utf8mb4";
    $pdo = new PDO($dsn, 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    // Get published blogs
    $stmt = $pdo->prepare("SELECT slug, updated_at FROM blogs WHERE status = 'published' ORDER BY created_at DESC");
    $stmt->execute();
    $blogs = $stmt->fetchAll();
    
} catch (PDOException $e) {
    $blogs = [];
}

// Use current host for base URL
$path = ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') ? '/Portfolio' : '';
$baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . $path;

// Clear any buffered output
ob_end_clean();

// Set XML header
header('Content-Type: application/xml; charset=utf-8');

// Output XML
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Homepage -->
    <url>
        <loc><?php echo $baseUrl; ?>/index.php</loc>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
    </url>
    
    <!-- About Page -->
    <url>
        <loc><?php echo $baseUrl; ?>/public/about.php</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    
    <!-- Projects Page -->
    <url>
        <loc><?php echo $baseUrl; ?>/public/projects.php</loc>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    
    <!-- Services Page -->
    <url>
        <loc><?php echo $baseUrl; ?>/public/services.php</loc>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    
    <!-- Blog Page -->
    <url>
        <loc><?php echo $baseUrl; ?>/public/blog.php</loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    
    <!-- Contact Page -->
    <url>
        <loc><?php echo $baseUrl; ?>/public/contact.php</loc>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    
    <?php foreach ($blogs as $blog): ?>
    <!-- Blog Post: <?php echo htmlspecialchars($blog['slug']); ?> -->
    <url>
        <loc><?php echo $baseUrl; ?>/public/blog-details.php?slug=<?php echo urlencode($blog['slug']); ?></loc>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
        <lastmod><?php echo date('Y-m-d', strtotime($blog['updated_at'])); ?></lastmod>
    </url>
    <?php endforeach; ?>
</urlset>
