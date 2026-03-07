#!/bin/bash

# Pharmacy Management System Deployment Script
# Usage: ./deploy.sh [environment] [domain]

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
ENVIRONMENT=${1:-production}
DOMAIN=${2:-localhost}
APP_DIR="/var/www/pharmacy"
BACKUP_DIR="/var/backups/pharmacy"

echo -e "${BLUE}🚀 Starting Pharmacy Management System Deployment${NC}"
echo -e "${YELLOW}Environment: $ENVIRONMENT${NC}"
echo -e "${YELLOW}Domain: $DOMAIN${NC}"

# Check if running as root
if [[ $EUID -eq 0 ]]; then
   echo -e "${RED}❌ Please run this script as a non-root user with sudo privileges${NC}"
   exit 1
fi

# Create backup directory
echo -e "${BLUE}📁 Creating backup directory...${NC}"
sudo mkdir -p $BACKUP_DIR
sudo chown $USER:$USER $BACKUP_DIR

# Backup current installation if exists
if [ -d "$APP_DIR" ]; then
    echo -e "${BLUE}💾 Backing up current installation...${NC}"
    BACKUP_FILE="$BACKUP_DIR/backup_$(date +%Y%m%d_%H%M%S).tar.gz"
    tar -czf $BACKUP_FILE -C $APP_DIR .
    echo -e "${GREEN}✅ Backup created: $BACKUP_FILE${NC}"
fi

# Update system packages
echo -e "${BLUE}🔄 Updating system packages...${NC}"
sudo apt update && sudo apt upgrade -y

# Install required packages
echo -e "${BLUE}📦 Installing required packages...${NC}"
sudo apt install -y nginx php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring php8.2-bcmath php8.2-curl php8.2-zip php8.2-gd mysql-server redis-server composer unzip

# Configure MySQL
echo -e "${BLUE}🗄️ Configuring MySQL...${NC}"
if ! mysql -u root -e "SHOW DATABASES LIKE 'pharmacy_management';" | grep -q pharmacy_management; then
    echo -e "${YELLOW}Creating database...${NC}"
    sudo mysql -u root -e "CREATE DATABASE pharmacy_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    sudo mysql -u root -e "CREATE USER 'pharmacy_user'@'localhost' IDENTIFIED BY 'SecurePassword123!';"
    sudo mysql -u root -e "GRANT ALL PRIVILEGES ON pharmacy_management.* TO 'pharmacy_user'@'localhost';"
    sudo mysql -u root -e "FLUSH PRIVILEGES;"
    echo -e "${GREEN}✅ Database and user created${NC}"
else
    echo -e "${YELLOW}⚠️ Database already exists${NC}"
fi

# Configure Redis
echo -e "${BLUE}🔧 Configuring Redis...${NC}"
sudo systemctl enable redis-server
sudo systemctl start redis-server

# Create application directory
echo -e "${BLUE}📁 Setting up application directory...${NC}"
sudo mkdir -p $APP_DIR
sudo chown $USER:$USER $APP_DIR

# Copy application files (assuming running from project root)
echo -e "${BLUE}📋 Copying application files...${NC}"
rsync -av --exclude='.git' --exclude='node_modules' --exclude='storage/logs/*' --exclude='storage/framework/cache/*' --exclude='.env' ./ $APP_DIR/

cd $APP_DIR

# Install PHP dependencies
echo -e "${BLUE}📦 Installing PHP dependencies...${NC}"
composer install --optimize-autoloader --no-dev --quiet

# Setup environment file
echo -e "${BLUE}⚙️ Setting up environment...${NC}"
if [ ! -f ".env" ]; then
    cp .env.production.example .env
    echo -e "${YELLOW}⚠️ Please edit .env file with your database credentials and domain${NC}"
    echo -e "${YELLOW}⚠️ Then run: php artisan key:generate${NC}"
    exit 1
fi

# Generate application key
echo -e "${BLUE}🔑 Generating application key...${NC}"
php artisan key:generate --force

# Clear and cache configuration
echo -e "${BLUE}🗑️ Clearing and caching configuration...${NC}"
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
echo -e "${BLUE}🗄️ Running database migrations...${NC}"
php artisan migrate --force

# Create storage link
echo -e "${BLUE}🔗 Creating storage link...${NC}"
php artisan storage:link

# Set proper permissions
echo -e "${BLUE}🔒 Setting file permissions...${NC}"
sudo chown -R www-data:www-data storage bootstrap/cache public
sudo chmod -R 755 storage bootstrap/cache public
sudo chmod 600 .env

# Configure Nginx
echo -e "${BLUE}⚙️ Configuring Nginx...${NC}"
sudo tee /etc/nginx/sites-available/pharmacy > /dev/null <<EOF
server {
    listen 80;
    server_name $DOMAIN www.$DOMAIN;
    return 301 https://\$server_name\$request_uri;
}

server {
    listen 443 ssl http2;
    server_name $DOMAIN www.$DOMAIN;
    root $APP_DIR/public;
    index index.php index.html;

    # SSL Configuration (self-signed for now)
    ssl_certificate /etc/ssl/certs/ssl-cert-snakeoil.pem;
    ssl_certificate_key /etc/ssl/private/ssl-cert-snakeoil.key;
    ssl_protocols TLSv1.2 TLSv1.3;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;

    # Laravel Configuration
    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    # Hide .env and other sensitive files
    location ~ /\.env {
        deny all;
    }

    location ~ /\.ht {
        deny all;
    }
}
EOF

# Enable site
sudo ln -sf /etc/nginx/sites-available/pharmacy /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default

# Test Nginx configuration
sudo nginx -t

# Restart services
echo -e "${BLUE}🔄 Restarting services...${NC}"
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
sudo systemctl restart mysql
sudo systemctl restart redis-server

# Enable services on boot
sudo systemctl enable nginx
sudo systemctl enable php8.2-fpm
sudo systemctl enable mysql
sudo systemctl enable redis-server

# Setup cron job for Laravel scheduler
echo -e "${BLUE}⏰ Setting up cron job...${NC}"
(crontab -l 2>/dev/null; echo "* * * * * cd $APP_DIR && php artisan schedule:run >> /dev/null 2>&1") | crontab -

# Test application
echo -e "${BLUE}🧪 Testing application...${NC}"
if curl -s -o /dev/null -w "%{http_code}" http://localhost | grep -q "200"; then
    echo -e "${GREEN}✅ Application is responding correctly${NC}"
else
    echo -e "${RED}❌ Application is not responding correctly${NC}"
    echo -e "${YELLOW}Check Nginx logs: sudo tail -f /var/log/nginx/error.log${NC}"
fi

echo -e "${GREEN}🎉 Deployment completed successfully!${NC}"
echo -e "${BLUE}📍 Application URL: https://$DOMAIN${NC}"
echo -e "${BLUE}📝 Next steps:${NC}"
echo -e "${YELLOW}1. Update .env file with your actual database credentials${NC}"
echo -e "${YELLOW}2. Install SSL certificate: sudo certbot --nginx -d $DOMAIN -d www.$DOMAIN${NC}"
echo -e "${YELLOW}3. Configure your domain DNS to point to this server${NC}"
echo -e "${YELLOW}4. Test all functionality${NC}"
echo -e "${YELLOW}5. Set up regular backups${NC}"
