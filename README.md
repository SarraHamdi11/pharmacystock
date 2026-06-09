# PharmaStock Pro 💊 | Enterprise Pharmacy Management System

[![Laravel](https://img.shields.io/badge/Laravel-12.0-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-4.0-38B2AC?style=for-the-badge&logo=tailwind-css)](https://tailwindcss.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![Sanctum](https://img.shields.io/badge/Auth-Sanctum-blue?style=for-the-badge)](https://laravel.com/docs/sanctum)
[![Testing](https://img.shields.io/badge/Testing-Pest_PHP-green?style=for-the-badge)](https://pestphp.com)

**PharmaStock Pro** is a high-performance, enterprise-grade Pharmacy Management System designed to streamline inventory control, patient records, and sales analytics. Built with a senior-level focus on clean architecture, security, and scalability.

## 🚀 Key Features

- **📊 Dynamic Dashboard**: Real-time KPI tracking, sales analytics, and automated task prioritization.
- **📦 Smart Inventory**: Multi-store stock tracking, expiry date monitoring, and low-stock alerts.
- **💳 POS & Order Management**: Professional transaction processing with automated inventory deduction.
- **🛡️ RBAC Security**: Granular Role-Based Access Control using Spatie (Admin, Manager, Pharmacist, Employee).
- **📡 RESTful API**: Fully documented API protected by Laravel Sanctum for mobile integration.
- **🌍 Localization**: Multi-language support (EN, FR, AR, ES) with automatic locale detection.
- **📋 Audit Trail**: Comprehensive logging of all critical system activities.

## 🏗️ Architecture
The project follows a **Service Layer Pattern** to maintain "Thin Controllers" and encapsulate business logic.

- **Controllers**: Handle request/response and delegate to services.
- **Services**: Contain the core business logic (`OrderService`, `ProductService`, `DashboardService`).
- **FormRequests**: Centralized validation and authorization logic.
- **Policies**: Fine-grained authorization for all resources.
- **DTOs & Exports**: Structured data handling for imports/exports.

## 🛠️ Tech Stack
- **Backend**: Laravel 12.x, PHP 8.2+
- **Frontend**: Tailwind CSS 4, Alpine.js, Blade Components, Vite 6
- **Database**: Optimized for PostgreSQL/MySQL (SQLite for development)
- **Security**: Spatie Permissions, Laravel Sanctum, Rate Limiting
- **Testing**: Pest PHP (Feature & Unit testing)
- **CI/CD**: GitHub Actions (Automated testing, Pint, and migrations)

## ⚙️ Installation

1. **Clone & Install**
   ```bash
   git clone https://github.com/your-username/pharmacystock.git
   cd pharmacystock
   composer install
   npm install && npm run build
   ```

2. **Setup Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Initialize Database**
   ```bash
   touch database/database.sqlite
   php artisan migrate --seed
   ```

## 🧪 Demo Credentials
*Default password for all accounts: `password`*

| Role | Email | Best for testing... |
| :--- | :--- | :--- |
| **Admin** | `admin@pharma.com` | Full system control, activity logs, deletions. |
| **Manager** | `manager@pharma.com` | Inventory reports and sales analytics. |
| **Pharmacist**| `pharmacist@pharma.com` | Medication management and orders. |
| **Employee** | `employee@pharma.com` | Sales processing and patient records. |

## 🧪 Testing
```bash
# Run all tests
php artisan test

# Run tests with coverage
php artisan test --coverage
```

## 🛡️ Security & Performance
- **SQL Injection Protection**: Via Eloquent parameter binding.
- **XSS Protection**: Blade's automatic escaping.
- **CSRF Protection**: Integrated middleware for all forms.
- **N+1 Query Prevention**: Eager loading implemented across all relationships.
- **Caching**: Dashboard stats and analytics cached for optimal performance.

## 🚀 Deployment
PharmaStock Pro is ready for one-click deployment to **Railway**, **Render**, or **VPS**. See the [Deployment Guide](docs/DEPLOYMENT.md) for details.

---
Developed with ❤️ for the Pharmacy Community.
