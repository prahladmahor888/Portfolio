<?php
/**
 * About Page - Responsive Resume & Profile (Seamless Flow)
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/helpers/helpers.php';
require_once __DIR__ . '/../app/models/Skill.php';
require_once __DIR__ . '/../app/models/Setting.php';
require_once __DIR__ . '/../app/models/Experience.php';
require_once __DIR__ . '/../app/models/Project.php';

$skillModel = new Skill();
$settingModel = new Setting();
$experienceModel = new Experience();
$projectModel = new Project();

$skillsByCategory = $skillModel->getByCategory();
$settings = $settingModel->getAll();

$experiences = [];
try {
    $experiences = $experienceModel->getAllOrdered();
} catch (Exception $e) {
    // Fail silently if table not found
}

$featuredProjects = [];
try {
    $featuredProjects = $projectModel->getFeatured(4);
} catch (Exception $e) {
    // Fail silently if error
}

$pageTitle = 'Resume / About Me';
$metaDescription = 'Professional Resume of ' . ($settings['meta_author'] ?? 'Prahlad Mahour') . ' - Computer Science Engineer & Software Developer.';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';

$candidateName = !empty($settings['meta_author']) ? $settings['meta_author'] : 'Prahlad Mahour';
$candidateTagline = !empty($settings['site_tagline']) ? $settings['site_tagline'] : 'Computer Science Engineering Student & Software Developer';
$candidateEmail = $settings['site_email'] ?? 'prahladmahor888@gmail.com';
$candidatePhone = $settings['site_phone'] ?? '';
$candidateLocation = $settings['site_address'] ?? 'India';
$githubUrl = $settings['social_github'] ?? 'https://github.com';
$linkedinUrl = $settings['social_linkedin'] ?? 'https://linkedin.com';
?>

<section class="section resume-page-wrapper" style="padding-top: 110px; padding-bottom: 80px;">
    <div class="container" style="max-width: 1000px;">
        
        <!-- Top Action & Navigation Bar -->
        <div class="resume-top-bar" data-aos="fade-down">
            <div class="resume-title-wrap">
                <span class="resume-badge">📄 Professional Resume</span>
                <h1 class="section-title" style="margin-bottom: 0;">About Me &amp; Resume</h1>
            </div>
            <div class="resume-actions">
                <a href="../resume.pdf" class="btn btn-primary" target="_blank" download="Prahlad_Mahour_Resume.pdf">
                    <span><i class="fas fa-file-download"></i> Download PDF</span>
                </a>
                <button onclick="window.print()" class="btn btn-outline print-btn" title="Print Resume">
                    <span><i class="fas fa-print"></i> Print CV</span>
                </button>
                <a href="contact" class="btn btn-outline">
                    <span><i class="fas fa-envelope"></i> Contact Me</span>
                </a>
            </div>
        </div>

        <!-- 1. HERO IDENTITY CARD -->
        <div class="resume-hero-card glass-card" data-aos="fade-up">
            <div class="status-pill">
                <span class="status-dot"></span> Open to Software Engineering Opportunities
            </div>
            <h2 class="candidate-name"><?php echo clean($candidateName); ?></h2>
            <p class="candidate-tagline"><?php echo clean($candidateTagline); ?></p>

            <!-- Contact & Social Details Bar -->
            <div class="resume-contact-bar">
                <a href="mailto:<?php echo clean($candidateEmail); ?>" class="contact-pill">
                    <span class="pill-icon">✉️</span>
                    <span><?php echo clean($candidateEmail); ?></span>
                </a>
                <?php if (!empty($candidatePhone)): ?>
                    <a href="tel:<?php echo clean($candidatePhone); ?>" class="contact-pill">
                        <span class="pill-icon">📞</span>
                        <span><?php echo clean($candidatePhone); ?></span>
                    </a>
                <?php endif; ?>
                <div class="contact-pill">
                    <span class="pill-icon">📍</span>
                    <span><?php echo clean($candidateLocation); ?></span>
                </div>
                <?php if (!empty($githubUrl)): ?>
                    <a href="<?php echo clean($githubUrl); ?>" target="_blank" class="contact-pill">
                        <span class="pill-icon"><i class="fab fa-github"></i></span>
                        <span>GitHub</span>
                    </a>
                <?php endif; ?>
                <?php if (!empty($linkedinUrl)): ?>
                    <a href="<?php echo clean($linkedinUrl); ?>" target="_blank" class="contact-pill">
                        <span class="pill-icon"><i class="fab fa-linkedin"></i></span>
                        <span>LinkedIn</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- 2. PROFESSIONAL SUMMARY / ABOUT ME -->
        <div class="resume-section-item" data-aos="fade-up">
            <div class="section-title-wrap">
                <span class="section-icon">👤</span>
                <h3 class="resume-heading">Professional Summary</h3>
            </div>
            <div class="glass-card resume-card-body">
                <div class="resume-bio-content">
                    <?php echo renderFormattedBio($settings['about_bio'] ?? 'Passionate software developer with a strong foundation in computer science, core Java, database design, and modern application development.'); ?>
                </div>
            </div>
        </div>

        <!-- 3. TECHNICAL SKILLS & PROFICIENCIES -->
        <div class="resume-section-item" data-aos="fade-up">
            <div class="section-title-wrap">
                <span class="section-icon">⚡</span>
                <h3 class="resume-heading">Technical Skills &amp; Proficiencies</h3>
            </div>
            
            <?php if (!empty($skillsByCategory)): ?>
                <div class="skills-responsive-grid">
                    <?php foreach ($skillsByCategory as $category => $skills): ?>
                        <div class="glass-card skill-box-card">
                            <h4 class="skill-cat-title"><?php echo clean($category); ?></h4>
                            <div class="skill-bars-list">
                                <?php foreach ($skills as $skill): ?>
                                    <div class="resume-skill-bar-item">
                                        <div class="bar-labels">
                                            <span class="bar-skill-name"><?php echo clean($skill['name']); ?></span>
                                            <span class="bar-skill-pct"><?php echo $skill['level']; ?>%</span>
                                        </div>
                                        <div class="bar-track">
                                            <div class="bar-fill" style="width: <?php echo $skill['level']; ?>%;"></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <!-- Fallback Tech Skills Cloud -->
                <div class="glass-card resume-card-body">
                    <div class="skill-chips-cloud">
                        <span class="skill-pill">Core Java</span>
                        <span class="skill-pill">OOPs</span>
                        <span class="skill-pill">Android (Java)</span>
                        <span class="skill-pill">SQL / MySQL</span>
                        <span class="skill-pill">Firebase &amp; Firestore</span>
                        <span class="skill-pill">PHP &amp; Backend</span>
                        <span class="skill-pill">Django / Python</span>
                        <span class="skill-pill">HTML5 &amp; CSS3</span>
                        <span class="skill-pill">JavaScript</span>
                        <span class="skill-pill">Git &amp; GitHub</span>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- 4. EXPERIENCE & JOURNEY -->
        <div class="resume-section-item" data-aos="fade-up">
            <div class="section-title-wrap">
                <span class="section-icon">💼</span>
                <h3 class="resume-heading">Experience &amp; Practical Journey</h3>
            </div>
            
            <div class="resume-timeline-stream">
                <?php if (!empty($experiences)): ?>
                    <?php foreach ($experiences as $exp): ?>
                        <div class="timeline-row-item">
                            <div class="timeline-node"></div>
                            <div class="glass-card timeline-card-content">
                                <div class="timeline-header-flex">
                                    <div>
                                        <h4 class="timeline-title"><?php echo clean($exp['title']); ?></h4>
                                        <span class="timeline-sub"><?php echo clean($exp['company']); ?></span>
                                    </div>
                                    <span class="timeline-badge-tag"><?php echo clean($exp['year_range']); ?></span>
                                </div>
                                <div class="timeline-desc-text">
                                    <?php echo nl2br(clean($exp['description'])); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Default Structured Journey -->
                    <div class="timeline-row-item">
                        <div class="timeline-node"></div>
                        <div class="glass-card timeline-card-content">
                            <div class="timeline-header-flex">
                                <div>
                                    <h4 class="timeline-title">Android &amp; Full Stack Developer</h4>
                                    <span class="timeline-sub">Independent Projects &amp; Open Source</span>
                                </div>
                                <span class="timeline-badge-tag">2023 - Present</span>
                            </div>
                            <div class="timeline-desc-text">
                                • Architected and developed <strong>PocketSQL</strong>, an offline SQL and database learning application for Android with query execution and database simulation.<br>
                                • Engineered real-time chat &amp; social application using Android (Java), Firebase Authentication, and Cloud Firestore.<br>
                                • Designed relational database schemas, REST APIs, and authentication flows.
                            </div>
                        </div>
                    </div>

                    <div class="timeline-row-item">
                        <div class="timeline-node"></div>
                        <div class="glass-card timeline-card-content">
                            <div class="timeline-header-flex">
                                <div>
                                    <h4 class="timeline-title">Web &amp; Software Developer</h4>
                                    <span class="timeline-sub">Academic &amp; Practical Projects</span>
                                </div>
                                <span class="timeline-badge-tag">2022 - 2024</span>
                            </div>
                            <div class="timeline-desc-text">
                                • Built a College Management System with Django and relational databases.<br>
                                • Developed a dual-language Typing Tutor supporting English and Hindi typing practice.<br>
                                • Developed an AI-based Voice Noise Removal audio processing project.
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- 5. EDUCATION -->
        <div class="resume-section-item" data-aos="fade-up">
            <div class="section-title-wrap">
                <span class="section-icon">🎓</span>
                <h3 class="resume-heading">Education</h3>
            </div>
            <div class="resume-timeline-stream">
                <div class="timeline-row-item">
                    <div class="timeline-node"></div>
                    <div class="glass-card timeline-card-content">
                        <div class="timeline-header-flex">
                            <div>
                                <h4 class="timeline-title">B.Tech in Computer Science &amp; Engineering</h4>
                                <span class="timeline-sub">Bachelor of Technology</span>
                            </div>
                            <span class="timeline-badge-tag">3rd Year (Ongoing)</span>
                        </div>
                        <div class="timeline-desc-text">
                            <strong>Core Coursework:</strong> Object-Oriented Programming (Java), Data Structures &amp; Algorithms, Database Management Systems (DBMS / SQL), Operating Systems, Computer Networks, Software Engineering.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 6. KEY PROJECTS HIGHLIGHT -->
        <?php if (!empty($featuredProjects)): ?>
            <div class="resume-section-item" data-aos="fade-up">
                <div class="section-title-wrap">
                    <span class="section-icon">🚀</span>
                    <h3 class="resume-heading">Key Projects Highlight</h3>
                </div>
                <div class="projects-responsive-grid">
                    <?php foreach ($featuredProjects as $proj): ?>
                        <div class="glass-card project-stream-card">
                            <div class="project-card-header">
                                <h4 class="project-main-title"><?php echo clean($proj['title']); ?></h4>
                                <?php if (!empty($proj['category'])): ?>
                                    <span class="project-tag-badge"><?php echo clean($proj['category']); ?></span>
                                <?php endif; ?>
                            </div>
                            <p class="project-main-desc"><?php echo clean(truncate($proj['description'] ?? '', 180)); ?></p>
                            <?php if (!empty($proj['tech_stack'])): ?>
                                <div class="project-chips-flex">
                                    <?php 
                                    $techs = array_filter(array_map('trim', explode(',', $proj['tech_stack'])));
                                    foreach ($techs as $tech):
                                    ?>
                                        <span class="tech-chip-item"><?php echo clean($tech); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- 7. TOOLS & CORE COMPETENCIES -->
        <div class="resume-section-item" data-aos="fade-up">
            <div class="section-title-wrap">
                <span class="section-icon">🛠️</span>
                <h3 class="resume-heading">Tools &amp; Core Competencies</h3>
            </div>
            <div class="glass-card resume-card-body">
                <div class="competency-group">
                    <h4 class="comp-sub-title">Core Competencies:</h4>
                    <div class="chips-cloud-flex">
                        <span class="comp-badge-pill">Application Logic</span>
                        <span class="comp-badge-pill">Object-Oriented Programming (OOP)</span>
                        <span class="comp-badge-pill">Database Architecture &amp; SQL</span>
                        <span class="comp-badge-pill">Android App Architecture</span>
                        <span class="comp-badge-pill">RESTful APIs &amp; Authentication</span>
                        <span class="comp-badge-pill">UI/UX &amp; Responsive Layouts</span>
                    </div>
                </div>
                <div class="competency-group" style="margin-top: 1.5rem;">
                    <h4 class="comp-sub-title">Tools &amp; Environments:</h4>
                    <div class="chips-cloud-flex">
                        <span class="tool-badge-pill">Android Studio</span>
                        <span class="tool-badge-pill">VS Code</span>
                        <span class="tool-badge-pill">Git &amp; GitHub</span>
                        <span class="tool-badge-pill">Firebase Console</span>
                        <span class="tool-badge-pill">Cloud Firestore</span>
                        <span class="tool-badge-pill">MySQL / phpMyAdmin</span>
                        <span class="tool-badge-pill">Postman</span>
                        <span class="tool-badge-pill">XAMPP</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 8. LANGUAGES & ADDITIONAL INFO -->
        <div class="resume-section-item" data-aos="fade-up">
            <div class="section-title-wrap">
                <span class="section-icon">🌐</span>
                <h3 class="resume-heading">Languages &amp; Availability</h3>
            </div>
            <div class="languages-responsive-grid">
                <div class="glass-card lang-info-card">
                    <span class="lang-title">English</span>
                    <span class="lang-subtitle">Professional Working Proficiency</span>
                </div>
                <div class="glass-card lang-info-card">
                    <span class="lang-title">Hindi</span>
                    <span class="lang-subtitle">Native / Fluent</span>
                </div>
                <div class="glass-card lang-info-card">
                    <span class="lang-title">Work Status</span>
                    <span class="lang-subtitle" style="color: #34d399; font-weight: 600;">Immediate Availability</span>
                </div>
            </div>
        </div>

        <!-- 9. BOTTOM CTA BANNER -->
        <div class="resume-section-item" data-aos="fade-up" style="margin-bottom: 0;">
            <div class="glass-card resume-cta-banner">
                <div class="cta-flex-wrap">
                    <div>
                        <h4 class="cta-heading">Let's build something great together!</h4>
                        <p class="cta-sub">Looking for a passionate and dedicated developer for your team?</p>
                    </div>
                    <a href="contact" class="btn btn-primary btn-lg">
                        <span>Get In Touch →</span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<!-- Seamless Responsive Resume Styles -->
<style>
/* ---------------- SEAMLESS RESUME STYLES ---------------- */
.resume-page-wrapper {
    background: radial-gradient(circle at 50% 5%, rgba(79, 70, 229, 0.08), transparent 60%);
}

.resume-top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1.25rem;
    margin-bottom: 2rem;
}

.resume-badge {
    display: inline-block;
    padding: 0.35rem 0.85rem;
    background: rgba(79, 70, 229, 0.15);
    border: 1px solid rgba(79, 70, 229, 0.3);
    border-radius: 30px;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--color-accent);
    margin-bottom: 0.5rem;
}

.resume-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

/* Hero Identity Card */
.resume-hero-card {
    padding: 2.25rem;
    margin-bottom: 2.5rem;
    position: relative;
    overflow: hidden;
}

.resume-hero-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--gradient-primary);
}

.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.3rem 0.8rem;
    background: rgba(16, 185, 129, 0.12);
    border: 1px solid rgba(16, 185, 129, 0.3);
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    color: #34d399;
    margin-bottom: 0.85rem;
}

.status-dot {
    width: 8px;
    height: 8px;
    background: #10b981;
    border-radius: 50%;
    box-shadow: 0 0 8px #10b981;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(0.95); opacity: 0.8; }
    50% { transform: scale(1.2); opacity: 1; }
    100% { transform: scale(0.95); opacity: 0.8; }
}

.candidate-name {
    font-size: clamp(2rem, 5vw, 2.75rem);
    font-weight: 800;
    letter-spacing: -0.5px;
    margin: 0 0 0.35rem 0;
    background: linear-gradient(135deg, #ffffff 40%, #94a3b8 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.candidate-tagline {
    font-size: clamp(1rem, 2.5vw, 1.2rem);
    color: var(--color-accent);
    font-weight: 500;
    margin: 0 0 1.5rem 0;
}

/* Contact Bar */
.resume-contact-bar {
    display: flex;
    flex-wrap: wrap;
    gap: 0.65rem;
}

.contact-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.45rem 0.95rem;
    background: rgba(30, 41, 59, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 8px;
    font-size: 0.875rem;
    color: var(--color-text-secondary, #cbd5e1);
    text-decoration: none;
    transition: var(--transition);
}

.contact-pill:hover {
    background: rgba(79, 70, 229, 0.2);
    border-color: var(--color-primary);
    color: #ffffff;
    transform: translateY(-2px);
}

.pill-icon {
    font-size: 1rem;
    color: var(--color-accent);
}

/* Resume Section Items */
.resume-section-item {
    margin-bottom: 2.5rem;
}

.section-title-wrap {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.25rem;
}

.section-icon {
    font-size: 1.35rem;
}

.resume-heading {
    font-size: 1.35rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #f8fafc;
    margin: 0;
    position: relative;
}

.resume-card-body {
    padding: 1.75rem 2rem;
}

/* Bio Content */
.resume-bio-content {
    font-size: 1.025rem;
    line-height: 1.85;
    color: #cbd5e1;
}

.resume-bio-content p {
    margin-bottom: 1rem;
}

.resume-bio-content p:last-child {
    margin-bottom: 0;
}

.resume-bio-content strong {
    color: #ffffff;
    font-weight: 600;
}

/* Skills Responsive Grid */
.skills-responsive-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(290px, 1fr));
    gap: 1.25rem;
}

.skill-box-card {
    padding: 1.5rem;
    height: fit-content;
}

.skill-cat-title {
    font-size: 1rem;
    color: var(--color-accent);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0 0 1rem 0;
    padding-bottom: 0.5rem;
    border-bottom: 1px dashed rgba(255, 255, 255, 0.1);
}

.resume-skill-bar-item {
    margin-bottom: 0.75rem;
}

.resume-skill-bar-item:last-child {
    margin-bottom: 0;
}

.bar-labels {
    display: flex;
    justify-content: space-between;
    font-size: 0.85rem;
    margin-bottom: 0.3rem;
    color: #e2e8f0;
}

.bar-skill-name {
    font-weight: 500;
}

.bar-skill-pct {
    font-weight: 600;
    color: var(--color-accent);
}

.bar-track {
    height: 7px;
    background: rgba(15, 23, 42, 0.8);
    border-radius: 6px;
    overflow: hidden;
}

.bar-fill {
    height: 100%;
    background: var(--gradient-primary);
    border-radius: 6px;
    box-shadow: 0 0 8px rgba(79, 70, 229, 0.4);
}

/* Timeline Stream */
.resume-timeline-stream {
    position: relative;
    padding-left: 1.75rem;
}

.resume-timeline-stream::before {
    content: '';
    position: absolute;
    left: 6px;
    top: 10px;
    bottom: 10px;
    width: 2px;
    background: linear-gradient(180deg, var(--color-primary), rgba(79, 70, 229, 0.2));
}

.timeline-row-item {
    position: relative;
    margin-bottom: 1.5rem;
}

.timeline-row-item:last-child {
    margin-bottom: 0;
}

.timeline-node {
    position: absolute;
    left: -1.75rem;
    top: 10px;
    width: 14px;
    height: 14px;
    background: var(--color-primary);
    border: 3px solid #0f172a;
    border-radius: 50%;
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.3);
}

.timeline-card-content {
    padding: 1.5rem 1.75rem;
}

.timeline-header-flex {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.timeline-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: #ffffff;
    margin: 0 0 0.2rem 0;
}

.timeline-sub {
    font-size: 0.95rem;
    color: var(--color-accent);
    font-weight: 500;
}

.timeline-badge-tag {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--color-accent);
    background: rgba(79, 70, 229, 0.15);
    padding: 0.2rem 0.65rem;
    border-radius: 6px;
    border: 1px solid rgba(79, 70, 229, 0.25);
}

.timeline-desc-text {
    font-size: 0.95rem;
    line-height: 1.75;
    color: #cbd5e1;
}

/* Projects Responsive Grid */
.projects-responsive-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.25rem;
}

.project-stream-card {
    padding: 1.5rem;
    height: fit-content;
}

.project-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.project-main-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: #f8fafc;
    margin: 0;
}

.project-tag-badge {
    font-size: 0.75rem;
    color: var(--color-accent);
    background: rgba(79, 70, 229, 0.15);
    padding: 0.15rem 0.5rem;
    border-radius: 4px;
}

.project-main-desc {
    font-size: 0.875rem;
    color: #94a3b8;
    line-height: 1.6;
    margin: 0 0 1rem 0;
}

.project-chips-flex {
    display: flex;
    flex-wrap: wrap;
    gap: 0.4rem;
}

.tech-chip-item {
    font-size: 0.75rem;
    padding: 0.2rem 0.55rem;
    background: rgba(15, 23, 42, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 4px;
    color: #cbd5e1;
}

/* Competencies & Tools */
.comp-sub-title {
    font-size: 0.95rem;
    color: var(--color-accent);
    margin: 0 0 0.75rem 0;
    font-weight: 600;
}

.chips-cloud-flex {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.comp-badge-pill {
    font-size: 0.85rem;
    padding: 0.35rem 0.75rem;
    background: rgba(79, 70, 229, 0.15);
    border: 1px solid rgba(79, 70, 229, 0.3);
    border-radius: 6px;
    color: #f1f5f9;
}

.tool-badge-pill {
    font-size: 0.85rem;
    padding: 0.35rem 0.75rem;
    background: rgba(15, 23, 42, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 6px;
    color: #cbd5e1;
}

/* Languages Responsive Grid */
.languages-responsive-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.lang-info-card {
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    height: fit-content;
}

.lang-title {
    font-size: 1rem;
    font-weight: 700;
    color: #f8fafc;
}

.lang-subtitle {
    font-size: 0.85rem;
    color: #94a3b8;
}

/* Bottom CTA Banner */
.resume-cta-banner {
    padding: 2rem 2.25rem;
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.2), rgba(124, 58, 237, 0.12));
    border-color: rgba(79, 70, 229, 0.35);
}

.cta-flex-wrap {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1.5rem;
}

.cta-heading {
    font-size: 1.35rem;
    margin: 0 0 0.35rem 0;
    color: #ffffff;
}

.cta-sub {
    margin: 0;
    color: #cbd5e1;
    font-size: 0.95rem;
}

/* ---------------- RESPONSIVE MEDIA QUERIES ---------------- */
@media (max-width: 768px) {
    .resume-top-bar {
        flex-direction: column;
        align-items: flex-start;
    }
    .resume-actions {
        width: 100%;
    }
    .resume-actions .btn {
        flex: 1;
        justify-content: center;
    }
    .resume-hero-card {
        padding: 1.5rem;
    }
    .resume-card-body {
        padding: 1.25rem;
    }
    .timeline-card-content {
        padding: 1.25rem;
    }
    .resume-timeline-stream {
        padding-left: 1.25rem;
    }
    .timeline-node {
        left: -1.25rem;
    }
    .cta-flex-wrap {
        flex-direction: column;
        align-items: flex-start;
    }
    .cta-flex-wrap .btn {
        width: 100%;
        justify-content: center;
    }
}

/* ---------------- PRINT FRIENDLY STYLES ---------------- */
@media print {
    body {
        background: #ffffff !important;
        color: #1e293b !important;
    }
    .navbar, footer, .resume-top-bar, .resume-cta-banner, .status-pill {
        display: none !important;
    }
    .section {
        padding: 0 !important;
    }
    .glass-card {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: none !important;
        color: #1e293b !important;
    }
    .candidate-name {
        color: #0f172a !important;
        -webkit-text-fill-color: #0f172a !important;
    }
    .candidate-tagline {
        color: #4f46e5 !important;
    }
    .resume-heading {
        color: #0f172a !important;
    }
    .timeline-title, .project-main-title, .lang-title {
        color: #0f172a !important;
    }
    .timeline-desc-text, .resume-bio-content, .project-main-desc {
        color: #334155 !important;
    }
    .bar-track {
        background: #e2e8f0 !important;
    }
    .bar-fill {
        background: #4f46e5 !important;
    }
}
</style>
