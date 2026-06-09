# 🚀 Deployment Guide - PharmaStock Pro

This guide provides step-by-step instructions for deploying PharmaStock Pro to various environments, including production servers and cloud platforms.

## 📋 Hosting Requirements

### **Server Requirements**
- **PHP**: 8.2 or higher
- **Web Server**: Nginx (Recommended) or Apache 2.4+
- **Database**: MySQL 5.7+ / PostgreSQL 12+ (Supabase compatible)
- **Memory**: Minimum 2GB RAM
- **Storage**: Minimum 10GB SSD
- **SSL Certificate**: Required for HTTPS (Let's Encrypt recommended)

### **PHP Extensions Required**
`bcmath`, `curl`, `fileinfo`, `gd`, `json`, `mbstring`, `openssl`, `pdo_mysql/pgsql`, `tokenizer`, `xml`, `zip`.

---

## 🛠️ Pre-Deployment Checklist

### **1. Environment Configuration**
```bash
# Set production environment in .env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database (Example for PostgreSQL/Supabase)
DB_CONNECTION=pgsql
DB_HOST=your-db-host
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-password
```

### **2. Optimization Commands**
```bash
# Clear and cache configuration
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

### **Option 1: Vercel (Cloud)**
PharmaStock Pro is optimized for Vercel deployment via `@vercel/php`.

**Steps:**
1. Connect your GitHub repository to Vercel.
2. Add all environment variables (especially `APP_KEY` and `DB_CREDENTIALS`).
3. Deploy. The `vercel.json` and `vite.config.js` are already pre-configured.

### **Option 2: VPS (DigitalOcean, Vultr, AWS)**
Recommended for full control.

**Steps:**
1. Set up a Linux server (Ubuntu 22.04+).
2. Install PHP 8.2, Nginx, and your preferred DB.
3. Clone the repo and run the standard Laravel deployment script.
4. Set up a SSL certificate via Certbot.

---

## 🔧 Troubleshooting

### **Common Issues**
- **Database Connection**: Ensure your production IP is whitelisted in your database provider (e.g., Supabase).
- **Vite Build**: If styles are missing, ensure `npm run build` was executed and the `public/build` directory is present.
- **500 Errors**: Check `storage/logs/laravel.log` for detailed error reports.
