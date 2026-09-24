-- Sample Data for Portfolio Database
-- Insert this after running schema.sql

-- Create default admin user
-- Password: Admin@123 (hashed with PHP password_hash)
INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES
('Prahlad Mahour', 'prahladmahor888@gmail.com', '$2y$10$O5wDvsi.uNdVg9OGqporpexxLQgDaORiM0kRu8S5bpWdFT16Ao9Ai', 'admin');

-- Insert default site settings
INSERT INTO `settings` (`setting_key`, `setting_value`, `setting_type`) VALUES
('site_name', 'My Portfolio', 'text'),
('site_tagline', 'Full Stack Developer & Designer', 'text'),
('site_description', 'Professional portfolio showcasing my projects and expertise', 'textarea'),
('site_email', 'hello@myportfolio.com', 'text'),
('site_phone', '+1 234 567 8900', 'text'),
('site_address', 'San Francisco, CA', 'text'),
('meta_keywords', 'portfolio, web developer, full stack', 'text'),
('meta_author', 'Your Name', 'text'),
('social_github', 'https://github.com/yourusername', 'text'),
('social_linkedin', 'https://linkedin.com/in/yourusername', 'text'),
('social_twitter', 'https://twitter.com/yourusername', 'text'),
('social_instagram', '', 'text'),
('resume_file', '', 'text'),
('about_bio', 'Passionate developer with expertise in building modern web applications.', 'textarea'),
('hero_title', 'Hi, I\'m Your Name', 'text'),
('hero_subtitle', 'I build amazing digital experiences', 'text'),
('site_status', 'active', 'text');

-- Sample projects
INSERT INTO `projects` (`title`, `description`, `tech_stack`, `category`, `live_url`, `github_url`, `is_featured`, `sort_order`, `status`) VALUES
('E-Commerce Platform', 'A full-featured online shopping platform with payment integration, user authentication, and admin dashboard.', 'React, Node.js, MongoDB, Stripe', 'Web Application', 'https://demo.example.com', 'https://github.com/username/ecommerce', 1, 1, 'active'),
('Task Management App', 'Collaborative task management tool with real-time updates and team collaboration features.', 'Vue.js, Firebase, Tailwind CSS', 'Web Application', 'https://tasks.example.com', 'https://github.com/username/taskapp', 1, 2, 'active'),
('Portfolio Website Builder', 'A drag-and-drop portfolio builder for creatives and developers.', 'Next.js, Prisma, PostgreSQL', 'SaaS', '', 'https://github.com/username/portfolio-builder', 0, 3, 'active');

-- Sample skills
INSERT INTO `skills` (`name`, `category`, `level`, `sort_order`, `status`) VALUES
('HTML5', 'Frontend', 95, 1, 'active'),
('CSS3/SASS', 'Frontend', 90, 2, 'active'),
('JavaScript', 'Frontend', 92, 3, 'active'),
('React', 'Frontend', 88, 4, 'active'),
('Vue.js', 'Frontend', 85, 5, 'active'),
('PHP', 'Backend', 90, 6, 'active'),
('Node.js', 'Backend', 87, 7, 'active'),
('MySQL', 'Backend', 85, 8, 'active'),
('MongoDB', 'Backend', 82, 9, 'active'),
('Git', 'Tools', 90, 10, 'active'),
('Docker', 'Tools', 78, 11, 'active'),
('AWS', 'Tools', 75, 12, 'active');

-- Sample services
INSERT INTO `services` (`title`, `description`, `icon`, `price`, `features`, `sort_order`, `status`) VALUES
('Web Development', 'Custom website development tailored to your business needs with modern technologies and best practices.', 'fas fa-code', 'Starting at $999', 'Responsive Design\nSEO Optimized\nFast Loading\nSecure & Scalable', 1, 'active'),
('UI/UX Design', 'Beautiful and intuitive user interfaces that provide exceptional user experiences.', 'fas fa-paint-brush', 'Starting at $499', 'User Research\nWireframing\nPrototyping\nUser Testing', 2, 'active'),
('API Development', 'RESTful API development for web and mobile applications with comprehensive documentation.', 'fas fa-server', 'Starting at $799', 'RESTful Architecture\nAuthentication\nDocumentation\nScalable Solutions', 3, 'active'),
('Consulting', 'Technical consulting and code reviews to improve your existing projects.', 'fas fa-lightbulb', '$150/hour', 'Code Review\nArchitecture Planning\nPerformance Optimization\nBest Practices', 4, 'active');

-- Sample blog posts
INSERT INTO `blogs` (`title`, `slug`, `excerpt`, `content`, `category`, `tags`, `meta_title`, `meta_description`, `status`) VALUES
('Getting Started with Modern Web Development', 'getting-started-modern-web-development', 'A comprehensive guide to starting your journey in web development with the latest technologies and best practices.', '<h2>Introduction</h2><p>Web development has evolved significantly over the years. In this post, we\'ll explore the essential tools and technologies you need to get started in 2026.</p><h3>Essential Technologies</h3><ul><li>HTML5 & CSS3</li><li>JavaScript (ES6+)</li><li>React or Vue.js</li><li>Node.js</li></ul><p>This is a sample blog post. Replace with your actual content.</p>', 'Tutorial', 'web development, tutorial, beginners', 'Getting Started with Modern Web Development - My Portfolio', 'Learn the essentials of modern web development with this comprehensive guide covering HTML, CSS, JavaScript, and popular frameworks.', 'published'),
('Building Scalable Applications', 'building-scalable-applications', 'Learn the principles and patterns for building applications that can grow with your business.', '<h2>Scalability Matters</h2><p>Building applications that scale is crucial for long-term success. Here are key considerations...</p><p>This is a sample blog post. Add your actual content here.</p>', 'Architecture', 'scalability, architecture, best practices', 'Building Scalable Applications - Best Practices', 'Discover the principles and patterns for building scalable applications that grow with your business needs.', 'published');
