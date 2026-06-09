# 🏗️ System Architecture - PharmaStock Pro

PharmaStock Pro follows a modern, decoupled architecture designed for high performance, maintainability, and scalability. It leverages the latest Laravel 12 features while maintaining a clean separation of concerns.

## 🏛️ Architectural Layers

### 1. Presentation Layer (Frontend)
- **Blade & Tailwind CSS 4**: Highly optimized UI components with a custom design system.
- **Alpine.js**: Lightweight reactivity for dynamic dashboard elements.
- **Vite 6**: Next-generation frontend tooling for asset bundling.

### 2. Service Layer (Business Logic)
- **Location**: `app/Services/`
- **Purpose**: All core logic is extracted from controllers into specialized services (`OrderService`, `DashboardService`).
- **Benefit**: Code reuse, unit testability, and "Thin Controllers".

### 3. Data Layer (Persistence)
- **Eloquent ORM**: Fluent interface for database interactions.
- **Normalized Schema**: Optimized for pharmacy operations (Products, Stocks, Orders, Patients).
- **Audit Trail**: Automated logging of all data modifications.

---

## 🗄️ Database Schema Overview

| Table | Description | Relationships |
| :--- | :--- | :--- |
| **products** | Core medication data | `belongsTo` Category, Supplier |
| **stocks** | Inventory per store | `belongsTo` Product, Store |
| **orders** | Transaction headers | `belongsTo` Customer, `belongsToMany` Product |
| **customers**| Patient profiles | `hasMany` Orders |
| **order_items**| Transaction details | Pivot table with quantity/price history |

---

## 🔐 Security Architecture

- **RBAC (Spatie)**: Multi-role system (Admin, Manager, Pharmacist, Employee).
- **Sanctum API**: Token-based authentication for external integrations.
- **Rate Limiting**: Throttling for login and API endpoints to prevent abuse.
- **FormRequests**: Centralized validation and authorization for every request.

---

## 📡 Internal API Endpoints

| Endpoint | Method | Description |
| :--- | :--- | :--- |
| `/api/dashboard/data` | GET | High-level metrics for dashboard cards. |
| `/api/dashboard/sales-analytics` | GET | Daily revenue data (last 7 days). |
| `/api/dashboard/inventory-analytics`| GET | Stock distribution by category. |
| `/api/dashboard/low-stock` | GET | List of products below minimum threshold. |
