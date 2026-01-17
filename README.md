# Dynamic Personal Portfolio Website

A modern, production-ready personal portfolio website with a powerful admin panel for content management. Built with PHP 8+, MySQL, and modern web technologies.

## ✨ Features

### Public Website
- **Modern UI/UX** - Glassmorphism effects, smooth animations, gradient accents
- **Fully Dynamic** - All content managed through admin panel
- **SEO Optimized** - Meta tags, clean URLs, sitemap
- **Responsive Design** - Mobile-first, works on all devices
- **Smooth Animations** - AOS (Animate on Scroll) library integration
- **Fast Performance** - Optimized CSS, lazy loading, browser caching

### Admin Panel
- **Secure Authentication** - Password hashing, CSRF protection, session management
- **Dashboard** - Statistics overview, recent activity
- **Content Management** - Full CRUD for Projects, Blogs, Services, Skills
- **Message Inbox** - View contact form submissions
- **Settings** - Site configuration, social links, SEO meta tags
- **Image Upload** - Secure file handling with validation

### Security
- ✅ Password hashing with `password_hash()`
- ✅ PDO prepared statements (SQL injection protection)
- ✅ CSRF token validation
- ✅ XSS input sanitization
- ✅ Secure file uploads
- ✅ `.htaccess` security headers

## 🚀 Installation

### Prerequisites
- **XAMPP** (or LAMP/WAMP) with:
  - PHP 8.0 or higher
  - MySQL 5.7 or higher
  - Apache with mod_rewrite enabled

### Step 1: Clone/Download
```bash
# The project is already in c:\xampp\htdocs\Protfolio
```

### Step 2: Create Database
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Create a new database named `portfolio_db`
3. Import the schema:
   - Click on `portfolio_db`
   - Go to "Import" tab
   - Select `/database/schema.sql`
   - Click "Go"
4. Import the sample data:
   - Go to "Import" tab again
   - Select `/database/seed.sql`
   - Click "Go"

### Step 3: Configure
Edit `config/config.php` if needed (default settings should work with XAMPP):
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'portfolio_db');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### Step 4: Start XAMPP
1. Start Apache
2. Start MySQL

### Step 5: Access Your Site
- **Public Website**: `http://localhost/Protfolio/public/`
- **Admin Panel**: `http://localhost/Protfolio/admin/auth/login.php`

### Default Admin Credentials
```
Email: admin@portfolio.com
Password: Admin@123
```

**⚠️ IMPORTANT:** Change these credentials immediately after first login in Admin → Settings

## 📁 Project Structure

```
Protfolio/
├── admin/              # Admin panel pages
│   ├── auth/          # Login/logout
│   ├── dashboard.php  # Admin dashboard
│   ├── projects.php   # Projects CRUD
│   ├── messages.php   # Contact messages
│   └── settings.php   # Site settings
├── app/               # Application logic
│   ├── models/        # Database models (OOP)
│   └── helpers/       # Utility functions
├── assets/            # Static assets
│   ├── css/          # Stylesheets
│   ├── js/           # JavaScript files
│   └── uploads/      # User uploaded files
├── config/            # Configuration files
│   ├── config.php    # Main config
│   ├── database.php  # Database connection
│   └── auth.php      # Authentication helpers
├── database/          # Database files
│   ├── schema.sql    # Database structure
│   └── seed.sql      # Sample data
├── includes/          # Reusable templates
│   ├── header.php    # HTML head
│   ├── navbar.php    # Navigation
│   └── footer.php    # Footer
├── public/            # Public website pages
│   ├── index.php     # Homepage
│   ├── contact.php   # Contact page
│   └── ...           # Other pages
├── ajax/              # AJAX handlers
│   └── contact.php   # Contact form processor
├── .htaccess          # Security & URL rewriting
└── robots.txt         # SEO crawling rules
```

## 🎨 Customization

### Change Site Name & Info
1. Login to admin panel
2. Go to **Settings**
3. Update site name, tagline, description, contact info
4. Save changes

### Add Projects
1. Go to **Admin → Projects**
2. Click "Add New Project"
3. Fill in details, upload image
4. Mark as "Featured" to show on homepage
5. Save

### Manage Services
1. Go to **Admin → Services**
2. Add/edit services you offer
3. Set pricing, icons, descriptions

### Update Social Links
1. Go to **Admin → Settings**
2. Scroll to "Social Media Links"
3. Enter your profile URLs
4. Save

## 🎯 Usage Guide

### Adding a Blog Post
*(Blog CRUD pages can be added similarly to projects.php)*
1. Go to Admin → Blogs
2. Click "Add New Blog"
3. Slug is auto-generated from title
4. Use rich text editor for content
5. Set status to "Published"

### Viewing Messages
1. Go to Admin → Messages
2. See all contact form submissions
3. Mark as read or delete

## 🔧 Troubleshooting

### Database Connection Error
- Check XAMPP MySQL is running
- Verify database name in `config/config.php`
- Ensure `portfolio_db` exists in phpMyAdmin

### Images Not Uploading
- Check `assets/uploads/` folder exists
- Verify folder permissions (755 or 777)
- Check PHP upload limits in XAMPP `php.ini`

### Admin Panel Not Loading CSS
- Clear browser cache
- Check `SITE_URL` in `config/config.php`
- Verify files in `assets/css/` exist

### 404 Errors
- Enable mod_rewrite in Apache
- Check `.htaccess` file exists
- Verify `RewriteBase` in `.htaccess`

## 🚀 Deployment to Production

1. **Update Configuration**:
   - Change `APP_ENV` to `'production'` in `config/config.php`
   - Update `SITE_URL` to your domain
   - Set strong database password

2. **Security**:
   - Change admin password
   - Enable HTTPS in `.htaccess` (uncomment force HTTPS rules)
   - Set restrictive file permissions

3. **Upload Files**:
   - Upload all files via FTP/cPanel
   - Import database via phpMyAdmin

4. **Test Everything**:
   - Check all pages load
   - Test admin login
   - Verify contact form works

## 💡 Tips

- **Performance**: Enable PHP OPcache for production
- **SEO**: Submit `sitemap.xml` to Google Search Console
- **Backups**: Regularly backup database and uploads folder
- **Security**: Keep PHP and MySQL updated

## 📝 License

This project is  open-source and free to use for personal and commercial projects.

## 🤝 Support

For issues or questions:
1. Check the Troubleshooting section
2. Review code comments
3. Check database schema

---

**Built with ❤️ using PHP, MySQL, and modern web technologies**
