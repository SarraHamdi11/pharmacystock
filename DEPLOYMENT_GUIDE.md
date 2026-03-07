# 🚀 Pharmacy Management System - Deployment Guide

## 📋 Hosting Requirements

### **Server Requirements**
- **PHP**: 8.1 or higher
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **Database**: MySQL 5.7+ / MariaDB 10.3+ / PostgreSQL 12+
- **Memory**: Minimum 2GB RAM (4GB+ recommended)
- **Storage**: Minimum 10GB SSD
- **SSL Certificate**: Required for HTTPS

### **PHP Extensions Required**
```bash
php
php-fpm
php-mysql (or php-pgsql)
php-xml
php-mbstring
php-tokenizer
php-bcmath
php-curl
php-zip
php-gd
php-json
php-fileinfo
```

---

## 🛠️ Pre-Deployment Checklist

### **1. Environment Configuration**
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Set production environment
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
```

### **2. Database Setup**
```sql
-- Create database
CREATE DATABASE pharmacy_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user (optional but recommended)
CREATE USER 'pharmacy_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON pharmacy_management.* TO 'pharmacy_user'@'localhost';
FLUSH PRIVILEGES;
```

### **3. Optimize for Production**
```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
php artisan storage:link

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

---

## 🌐 Hosting Options

### **Option 1: Shared Hosting (Easy)**
**Recommended Providers:**
- SiteGround
- Bluehost
- Hostinger
- A2 Hosting

**Steps:**
1. Sign up for a hosting plan
2. Upload files via FTP/cPanel
3. Create MySQL database
4. Update `.env` file
5. Run deployment commands
6. Set up SSL certificate

### **Option 2: VPS/Dedicated Server (More Control)**
**Recommended Providers:**
- DigitalOcean
- Vultr
- Linode
- AWS EC2

**Server Setup:**
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install nginx php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring php8.2-bcmath php8.2-curl php8.2-zip php8.2-gd mysql-server -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Configure Nginx
sudo nano /etc/nginx/sites-available/pharmacy
```

### **Option 3: Cloud Platform (Scalable)**
**Recommended Providers:**
- Laravel Forge
- Vapor
- Heroku
- DigitalOcean App Platform

---

## 📁 Nginx Configuration

Create `/etc/nginx/sites-available/pharmacy`:

```nginx
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name your-domain.com www.your-domain.com;
    root /var/www/pharmacy/public;
    index index.php index.html;

    # SSL Configuration
    ssl_certificate /path/to/ssl/cert.pem;
    ssl_certificate_key /path/to/ssl/private.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    # Laravel Configuration
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Hide .env and other sensitive files
    location ~ /\.env {
        deny all;
    }

    # Block access to .htaccess
    location ~ /\.ht {
        deny all;
    }

    # Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_types text/plain text/css text/xml text/javascript application/json application/javascript application/xml+rss application/rss+xml application/atom+xml image/svg+xml;
}
```

---

## 🔧 Apache Configuration

Create `.htaccess` in public directory:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set X-Content-Type-Options "nosniff"
    Header always set Referrer-Policy "no-referrer-when-downgrade"
</IfModule>

# Gzip Compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>
```

---

## 🔒 Security Configuration

### **1. File Permissions**
```bash
# Set proper permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod -R 755 public
chmod 600 .env
```

### **2. Environment Security**
```bash
# Protect .env file
chown www-data:www-data .env
chmod 600 .env

# Hide sensitive information
APP_DEBUG=false
APP_ENV=production
```

### **3. Database Security**
```bash
# Use strong database credentials
DB_PASSWORD=your_strong_password_here

# Enable MySQL strict mode
DB_STRICT=true
```

---

## 📊 Performance Optimization

### **1. Caching Strategy**
```bash
# Enable caching in .env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Install Redis (Ubuntu/Debian)
sudo apt install redis-server
sudo systemctl enable redis-server
sudo systemctl start redis-server
```

### **2. Database Optimization**
```bash
# Optimize MySQL
mysql -u root -p -e "
OPTIMIZE TABLE products;
OPTIMIZE TABLE customers;
OPTIMIZE TABLE orders;
OPTIMIZE TABLE stocks;
"
```

### **3. Asset Optimization**
```bash
# Install and configure Laravel Mix
npm install
npm run production
```

---

## 🔄 Deployment Steps

### **Step 1: Prepare Files**
```bash
# Create deployment package
tar -czf pharmacy-deployment.tar.gz \
    --exclude=node_modules \
    --exclude=.git \
    --exclude=storage/logs/* \
    --exclude=storage/framework/cache/* \
    .
```

### **Step 2: Upload to Server**
```bash
# Using SCP
scp pharmacy-deployment.tar.gz user@server:/var/www/

# Using FTP (alternative)
# Upload via FileZilla or similar tool
```

### **Step 3: Extract and Configure**
```bash
# Extract files
cd /var/www/
tar -xzf pharmacy-deployment.tar.gz
mv pharmacy-deployment/* .
rm -rf pharmacy-deployment pharmacy-deployment.tar.gz

# Set permissions
chown -R www-data:www-data storage bootstrap/cache public
chmod -R 755 storage bootstrap/cache public
```

### **Step 4: Final Setup**
```bash
# Install dependencies
composer install --optimize-autoloader --no-dev

# Configure environment
cp .env.example .env
php artisan key:generate

# Run database migrations
php artisan migrate --force

# Seed database (optional)
php artisan db:seed --force

# Optimize application
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
```

---

## 🔍 Testing Before Going Live

### **1. Functionality Tests**
- [ ] Dashboard loads correctly
- [ ] Login/logout works
- [ ] CRUD operations work
- [ ] Search functionality works
- [ ] Charts display correctly
- [ ] Mobile responsive design works

### **2. Performance Tests**
- [ ] Page load time < 3 seconds
- [ ] Database queries optimized
- [ ] Images and assets compressed
- [ ] Caching is working

### **3. Security Tests**
- [ ] HTTPS working correctly
- [ ] Security headers present
- [ ] No sensitive data exposed
- [ ] File permissions correct

---

## 📱 Domain and SSL Setup

### **1. Domain Configuration**
```bash
# Point domain to server IP
A Record: @ -> YOUR_SERVER_IP
A Record: www -> YOUR_SERVER_IP
```

### **2. Free SSL (Let's Encrypt)**
```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Get SSL certificate
sudo certbot --nginx -d your-domain.com -d www.your-domain.com

# Auto-renewal
sudo crontab -e
# Add: 0 12 * * * /usr/bin/certbot renew --quiet
```

### **3. Paid SSL (Alternative)**
- Purchase from Namecheap, GoDaddy, etc.
- Install certificate files
- Update Nginx/Apache configuration

---

## 🚨 Monitoring and Maintenance

### **1. Log Monitoring**
```bash
# Application logs
tail -f storage/logs/laravel.log

# Nginx logs
tail -f /var/log/nginx/error.log
tail -f /var/log/nginx/access.log

# PHP logs
tail -f /var/log/php8.2-fpm.log
```

### **2. Backup Strategy**
```bash
# Database backup script
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u pharmacy_user -p pharmacy_management > /backups/db_backup_$DATE.sql

# File backup
tar -czf /backups/files_backup_$DATE.tar.gz /var/www/pharmacy/storage/app/public
```

### **3. Scheduled Tasks**
```bash
# Add to crontab
* * * * * cd /var/www/pharmacy && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🆘 Troubleshooting

### **Common Issues:**
1. **500 Internal Server Error**: Check file permissions and `.env` configuration
2. **Database Connection Failed**: Verify database credentials and firewall
3. **CSS/JS Not Loading**: Run `php artisan storage:link`
4. **Slow Performance**: Enable caching and optimize database
5. **SSL Issues**: Check certificate paths and Nginx configuration

### **Debug Commands:**
```bash
# Check Laravel status
php artisan about

# Check routes
php artisan route:list

# Clear cache
php artisan optimize:clear

# Test database connection
php artisan tinker
DB::connection()->getPdo();
```

---

## 📞 Support

If you encounter issues during deployment:
1. Check error logs first
2. Verify server requirements
3. Test database connection
4. Validate file permissions
5. Check SSL configuration

---

## ✅ Pre-Launch Checklist

- [ ] Server meets requirements
- [ ] Database created and configured
- [ ] Environment file configured
- [ ] SSL certificate installed
- [ ] File permissions set correctly
- [ ] Application optimized for production
- [ ] All functionality tested
- [ ] Performance optimized
- [ ] Security measures implemented
- [ ] Backup strategy configured
- [ ] Monitoring set up
- [ ] Domain pointed correctly

**🎉 Your Pharmacy Management System is ready for production!**
