# Pharmacy Stock

Inventory and stock management system for pharmacies. Built with a Laravel backend (API) and a React frontend to manage products, suppliers, stock movements, expirations, and simple reporting.

Status: Beta / Production-ready (adjust as needed)

[![License](https://img.shields.io/badge/license-MIT-blue)](#)
[![Backend](https://img.shields.io/badge/backend-Laravel-orange)](#)
[![Frontend](https://img.shields.io/badge/frontend-React-blue)](#)

## Table of Contents
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Demo / Screenshots](#demo--screenshots)
- [Getting Started](#getting-started)
  - [Prerequisites](#prerequisites)
  - [Backend (Laravel) setup](#backend-laravel-setup)
  - [Frontend (React) setup](#frontend-react-setup)
- [Environment Variables](#environment-variables)
- [Database & Seeds](#database--seeds)
- [Testing](#testing)
- [Deployment](#deployment)
- [API](#api)
- [Roadmap](#roadmap)
- [Contributing](#contributing)
- [License](#license)
- [Contact](#contact)

## Features
- Product catalog with categories and batches
- Stock in / stock out operations
- Expiry date tracking with alerts
- Supplier management and purchase order records
- Sales / dispensing logs
- Low-stock notifications & reporting
- Role-based access (Admin, Pharmacist, Viewer)
- CSV import/export for inventory

## Tech Stack
- Backend: PHP, Laravel (API), Eloquent ORM
- Frontend: React (Vite or Create React App), React Router
- Database: MySQL / MariaDB
- Optional: Redis for queues, Horizon for job monitoring
- DevOps: Docker (recommended), GitHub Actions for CI

## Demo / Screenshots
- Add screenshots into `/docs` or `/public/assets` and reference them here.
- Example: `![Dashboard](/docs/screenshots/dashboard.png)`

## Getting Started

### Prerequisites
- PHP 8.x, Composer
- Node.js 16+ and npm/yarn
- MySQL or MariaDB
- (Optional) Docker & Docker Compose

### Backend (Laravel) setup
1. Clone the repo
   ```bash
   git clone https://github.com/SarraHamdi11/pharmacystock.git
   cd pharmacystock
   ```
2. Install PHP dependencies
   ```bash
   composer install
   ```
3. Copy and edit .env
   ```bash
   cp .env.example .env
   # Edit DB_*, APP_URL, and other variables
   php artisan key:generate
   ```
4. Run migrations and seeders
   ```bash
   php artisan migrate --seed
   ```
5. Start the backend
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```
   Or use Docker: `docker compose up --build`

### Frontend (React) setup
1. From repo root:
   ```bash
   cd frontend
   npm install
   cp .env.example .env
   # configure API base URL in .env (e.g., VITE_API_URL=http://localhost:8000/api)
   npm run dev
   ```
2. Build for production:
   ```bash
   npm run build
   ```

## Environment Variables (example)
Backend (.env)
```
APP_NAME=PharmacyStock
APP_ENV=local
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pharmacy_db
DB_USERNAME=root
DB_PASSWORD=secret
```

Frontend (.env)
```
VITE_API_URL=http://localhost:8000/api
```

## Database & Seeds
- Seeds provide sample products, suppliers, and demo users.
- To refresh database and reseed:
  ```bash
  php artisan migrate:fresh --seed
  ```

## Testing
- Backend tests with PHPUnit / Pest:
  ```bash
  ./vendor/bin/phpunit
  ```
- Frontend tests (Jest / RTL):
  ```bash
  npm test
  ```

## Deployment
- Recommended: containerize with Docker and use a process manager or orchestrator.
- Example simple production steps:
  - Build frontend and serve static files via Nginx or serve frontend separately.
  - Use queue workers & scheduler (supervisor or systemd) for background jobs (notifications, expiry checks).
  - Configure SSL (Let's Encrypt) and environment secrets.

## API
- API follows REST conventions. Add real API docs or an OpenAPI spec in `/docs`.
- Example endpoints:
  - GET /api/products
  - POST /api/products
  - GET /api/stock-movements
  - POST /api/purchase-orders

## Roadmap
- Add barcode scanner integration (mobile/web)
- Scheduled expiry notifications via email/SMS
- Role & permission granularization
- Analytics & custom reports

## Contributing
- Please open issues for bugs or feature requests.
- Follow coding standards: PSR-12 for PHP, ESLint + Prettier for JS.
- Add tests for new features and run linters before submitting PRs.

## License
This project is licensed under the MIT License — see the LICENSE file for details.

## Contact
SarraHamdi11 — https://github.com/SarraHamdi11
Project link: https://github.com/SarraHamdi11/pharmacystock