<?php
/**
 * Footer Template - Public Website
 */

$socialLinks = [
    'github' => $settings['social_github'] ?? '',
    'linkedin' => $settings['social_linkedin'] ?? '',
    'twitter' => $settings['social_twitter'] ?? '',
    'instagram' => $settings['social_instagram'] ?? '',
    'youtube' => $settings['social_youtube'] ?? '',
];
?>

<footer class="footer">
    <div class="container">
        <div class="social-links">
            <?php foreach ($socialLinks as $platform => $url): ?>
                <?php if ($url): ?>
                    <a href="<?php echo clean($url); ?>" target="_blank" rel="noopener noreferrer" class="social-link" title="<?php echo ucfirst($platform); ?>">
                        <?php
                        $icons = [
                            'github' => '<i class="fab fa-github"></i>',
                            'linkedin' => '<i class="fab fa-linkedin"></i>',
                            'twitter' => '<i class="fab fa-twitter"></i>',
                            'instagram' => '<i class="fab fa-instagram"></i>',
                            'youtube' => '<i class="fab fa-youtube"></i>',
                        ];
                        echo $icons[$platform] ?? '🔗';
                        ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        
        <p class="text-muted">
            &copy; <?php echo date('Y'); ?> <?php echo clean($settings['site_name'] ?? 'Prahlad Mahour'); ?>. All rights reserved.
        </p>
    </div>
</footer>

<script>
    const SITE_URL = "<?php echo SITE_URL; ?>";
</script>
<script src="<?php echo ASSETS_URL; ?>/js/main.js"></script>
</body>
</html>
