<?php
/**
 * Navigation Bar - Public Website
 */

$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$isRoot = ($currentPage === 'index' && strpos($_SERVER['PHP_SELF'], '/public/') === false);
$prefix = $isRoot ? 'public/' : '';
?>
<nav class="navbar">
    <div class="container nav-container">
        <a href="<?php echo $isRoot ? './' : '../'; ?>index" class="nav-logo"><?php echo clean($settings['site_name'] ?? 'Portfolio'); ?></a>
        
        <button class="menu-toggle" onclick="document.querySelector('.nav-menu').classList.toggle('active')">☰</button>
        
        <ul class="nav-menu">
            <li><a href="<?php echo $prefix; ?>index" class="nav-link <?php echo $currentPage === 'index' ? 'active' : ''; ?>">Home</a></li>
            <li><a href="<?php echo $prefix; ?>about" class="nav-link <?php echo $currentPage === 'about' ? 'active' : ''; ?>">About</a></li>
            <li><a href="<?php echo $prefix; ?>projects" class="nav-link <?php echo $currentPage === 'projects' ? 'active' : ''; ?>">Projects</a></li>
            <li><a href="<?php echo $prefix; ?>services" class="nav-link <?php echo $currentPage === 'services' ? 'active' : ''; ?>">Services</a></li>
            <li><a href="<?php echo $prefix; ?>blog" class="nav-link <?php echo $currentPage === 'blog' ? 'active' : ''; ?>">Blog</a></li>
            <li><a href="<?php echo $prefix; ?>contact" class="nav-link <?php echo $currentPage === 'contact' ? 'active' : ''; ?>">Contact</a></li>
        </ul>
    </div>
</nav>

<script>
// Navbar scroll effect
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});
</script>
