#!/bin/bash

# 🆓 FREE Hosting Deployment Script
# Vercel + Railway - 100% FREE

set -e

# Colors
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

echo -e "${BLUE}🆓 FREE Hosting Deployment - Vercel + Railway${NC}"
echo -e "${YELLOW}Cost: $0/month | Time: 15 minutes${NC}"

# Check if Git is initialized
if [ ! -d ".git" ]; then
    echo -e "${BLUE}📦 Initializing Git repository...${NC}"
    git init
    git add .
    git commit -m "Initial commit - Pharmacy Management System"
    echo -e "${YELLOW}⚠️ Please create a GitHub repository and push this code${NC}"
    echo -e "${YELLOW}   Commands:${NC}"
    echo -e "${YELLOW}   git remote add origin https://github.com/yourusername/pharmacy-management.git${NC}"
    echo -e "${YELLOW}   git push -u origin main${NC}"
    echo -e "${YELLOW}   Then run this script again${NC}"
    exit 1
fi

# Check if we have GitHub remote
if ! git remote get-url origin &>/dev/null; then
    echo -e "${RED}❌ No GitHub remote found${NC}"
    echo -e "${YELLOW}Please set up GitHub first:${NC}"
    echo -e "${YELLOW}1. Create repository at github.com${NC}"
    echo -e "${YELLOW}2. Run: git remote add origin https://github.com/yourusername/repo.git${NC}"
    echo -e "${YELLOW}3. Run: git push -u origin main${NC}"
    exit 1
fi

# Create Vercel configuration
echo -e "${BLUE}⚙️ Creating Vercel configuration...${NC}"
cat > vercel.json << 'EOF'
{
  "version": 2,
  "builds": [
    {
      "src": "public/index.php",
      "use": "@vercel/php"
    },
    {
      "src": "artisan",
      "use": "@vercel/php"
    }
  ],
  "routes": [
    {
      "src": "/(.*)",
      "dest": "/public/index.php"
    }
  ],
  "env": {
    "APP_NAME": "PharmaStock Pro",
    "APP_ENV": "production",
    "APP_DEBUG": "false",
    "CACHE_DRIVER": "file",
    "SESSION_DRIVER": "file",
    "QUEUE_CONNECTION": "sync"
  }
}
EOF

# Create API directory for serverless functions
echo -e "${BLUE}📁 Creating API directory...${NC}"
mkdir -p api

# Create serverless function for artisan commands
cat > api/artisan.php << 'EOF'
<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$status = $kernel->handle(
    $input = new Symfony\Component\Console\Input\ArgvInput,
    new Symfony\Component\Console\Output\ConsoleOutput
);

$kernel->terminate($input, $status);

exit($status);
EOF

# Update .env for production
echo -e "${BLUE}📝 Updating environment configuration...${NC}"
if [ ! -f ".env" ]; then
    cp .env.example .env
fi

# Generate new app key
echo -e "${BLUE}🔑 Generating application key...${NC}"
php artisan key:generate --force

# Create free hosting checklist
cat > FREE_HOSTING_CHECKLIST.md << 'EOF'
# 🆓 FREE Hosting Checklist

## 📋 Before You Start

### ✅ Required Accounts:
- [ ] GitHub account (free)
- [ ] Vercel account (free)
- [ ] Railway account (free)

### ✅ Code Preparation:
- [ ] All code pushed to GitHub
- [ ] .env file configured
- [ ] Application key generated

## 🚀 Deployment Steps

### Step 1: Database Setup (Railway)
1. Go to https://railway.app
2. Click "New Project"
3. Select "MySQL"
4. Wait for deployment (2-3 minutes)
5. Click on MySQL service
6. Click "Connect"
7. Copy connection string

### Step 2: Application Setup (Vercel)
1. Go to https://vercel.com
2. Click "New Project"
3. Import your GitHub repository
4. Add environment variables:
   - APP_KEY: (from your .env file)
   - DB_CONNECTION: mysql
   - DB_HOST: (from Railway)
   - DB_PORT: 3306
   - DB_DATABASE: railway
   - DB_USERNAME: railway
   - DB_PASSWORD: (from Railway)
   - APP_URL: https://your-app-name.vercel.app
5. Click "Deploy"

### Step 3: Final Setup
1. Wait for deployment (2-3 minutes)
2. Visit your Vercel URL
3. Run migrations if needed
4. Test all functionality

## 🔧 Environment Variables

### Copy these to Vercel:
```
APP_NAME=PharmaStock Pro
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_KEY_HERE
APP_URL=https://your-app-name.vercel.app
DB_CONNECTION=mysql
DB_HOST=your-railway-host.railway.app
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=railway
DB_PASSWORD=YOUR_RAILWAY_PASSWORD
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

## 🎯 What You Get FREE

### Vercel (Hosting):
- ✅ 100GB bandwidth/month
- ✅ HTTPS automatically
- ✅ Global CDN
- ✅ Custom domains
- ✅ Automatic deployments

### Railway (Database):
- ✅ $5 credit/month
- ✅ MySQL database
- ✅ Automatic backups
- ✅ SSL connection
- ✅ Easy management

## 📱 Mobile Access

Your app will work like a mobile app:
- ✅ Responsive design
- ✅ Touch-friendly
- ✅ PWA ready
- ✅ Fast loading

## 🆘 Troubleshooting

### Common Issues:
1. **Database Connection**: Check Railway credentials
2. **Build Errors**: Check Vercel logs
3. **404 Errors**: Check routes in vercel.json
4. **White Screen**: Check APP_KEY in Vercel

### Quick Fixes:
- Re-deploy: Push to GitHub
- Clear cache: Visit Vercel dashboard
- Check logs: Vercel Functions tab
- Reset database: Railway dashboard

## 🎉 You're Live!

Your Pharmacy Management System is now:
- ✅ Hosted 100% FREE
- ✅ Accessible worldwide
- ✅ Mobile-friendly
- ✅ Secure with HTTPS
- ✅ Auto-scaling

Share your Vercel URL and start using your system!
EOF

# Push to GitHub
echo -e "${BLUE}📤 Pushing to GitHub...${NC}"
git add .
git commit -m "Ready for free hosting - Vercel + Railway" || true
git push origin main || true

echo -e "${GREEN}🎉 FREE Hosting Setup Complete!${NC}"
echo -e "${BLUE}📋 Next Steps:${NC}"
echo -e "${YELLOW}1. Open FREE_HOSTING_CHECKLIST.md${NC}"
echo -e "${YELLOW}2. Create Railway database: https://railway.app${NC}"
echo -e "${YELLOW}3. Deploy to Vercel: https://vercel.com${NC}"
echo -e "${YELLOW}4. Your app will be live in 15 minutes!${NC}"
echo -e ""
echo -e "${BLUE}📱 Your app will be available at:${NC}"
echo -e "${YELLOW}   https://your-app-name.vercel.app${NC}"
echo -e ""
echo -e "${GREEN}🆓 Total Cost: $0/month${NC}"
echo -e "${GREEN}⏱️  Total Time: 15 minutes${NC}"
echo -e "${GREEN}🚀 Total Features: Everything included!${NC}"
