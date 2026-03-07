<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\MaladieController;
use App\Http\Controllers\MedicamentController;
use App\Http\Controllers\ReportController;

// Welcome Route - Redirect to Enhanced Dashboard
Route::get('/', function() {
    return redirect()->route('dashboard.index');
});

// Dashboard Route  
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

// API Routes for Dashboard
Route::prefix('api')->group(function () {
    Route::get('/dashboard/data', [DashboardController::class, 'getDashboardData']);
    Route::get('/dashboard/low-stock', [DashboardController::class, 'getLowStockAlerts']);
    Route::get('/dashboard/expiring/{days?}', [DashboardController::class, 'getExpiringMedications']);
    Route::get('/dashboard/sales-analytics', [DashboardController::class, 'getSalesAnalytics']);
    Route::get('/dashboard/inventory-analytics', [DashboardController::class, 'getInventoryAnalytics']);
    Route::get('/dashboard/top-medications', [DashboardController::class, 'getTopMedications']);
    Route::get('/dashboard/customer-analytics', [DashboardController::class, 'getCustomerAnalytics']);
    Route::get('/dashboard/financial-analytics', [DashboardController::class, 'getFinancialAnalytics']);
    Route::get('/search', [DashboardController::class, 'search']);
    Route::post('/stock/update', [DashboardController::class, 'updateStock']);
    Route::get('/export', [DashboardController::class, 'exportDashboard']);
});

// Search Routes (must come before resource routes to avoid conflicts)
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/customers/search', [CustomerController::class, 'search'])->name('customers.search');
Route::get('/suppliers/search', [SupplierController::class, 'search'])->name('suppliers.search');
Route::get('/orders/search', [OrderController::class, 'search'])->name('orders.search');

// Resource Routes
Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);
Route::resource('suppliers', SupplierController::class);
Route::resource('customers', CustomerController::class);
Route::resource('orders', OrderController::class);
Route::resource('stores', StoreController::class);
Route::resource('stocks', StockController::class);
Route::resource('maladies', MaladieController::class);
Route::resource('medicaments', MedicamentController::class);

// Filtered Products Routes (fix conflicts)
Route::get('/products/by-supplier/{supplier}', [ProductController::class, 'productsBySupplier'])->name('products.by-supplier');
Route::get('/products/by-store/{store}', [ProductController::class, 'productsByStore'])->name('products.by-store');
Route::get('/products/by-category/{category}', [ProductController::class, 'productsByCategory'])->name('products.by-category');

// Dashboard Related Routes
Route::get('/dashboard/products/supplier', [DashboardController::class, 'productsBySupplier'])->name('dashboard.products.supplier');
Route::get('/dashboard/products/store', [DashboardController::class, 'productsByStore'])->name('dashboard.products.store');
Route::get('/dashboard/customers', [DashboardController::class, 'customers'])->name('dashboard.customers');
Route::get('/dashboard/suppliers', [DashboardController::class, 'suppliers'])->name('dashboard.suppliers');
Route::get('/dashboard/orders', [DashboardController::class, 'orders'])->name('dashboard.orders');
Route::get('/dashboard/maladies', [DashboardController::class, 'maladies'])->name('dashboard.maladies');

// Cookie/Session/Avatar Routes
Route::post('/saveCookie', [DashboardController::class, 'saveCookie'])->name('save.cookie');
Route::post('/saveSession', [DashboardController::class, 'saveSession'])->name('save.session');
Route::post('/saveAvatar', [DashboardController::class, 'saveAvatar'])->name('save.avatar');

// Import/Export Routes
Route::get('/import/products', [ProductController::class, 'import'])->name('products.import');
Route::post('/import/products', [ProductController::class, 'importStore'])->name('products.import.store');
Route::get('/export/products', [ProductController::class, 'export'])->name('products.export');

// Report Routes
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/sales', [ReportController::class, 'salesReport'])->name('reports.sales');
Route::get('/reports/inventory', [ReportController::class, 'inventoryReport'])->name('reports.inventory');
Route::get('/reports/customers', [ReportController::class, 'customerReport'])->name('reports.customers');
Route::get('/reports/suppliers', [ReportController::class, 'supplierReport'])->name('reports.suppliers');
Route::get('/reports/expiry', [ReportController::class, 'expiry'])->name('reports.expiry');

// Export Routes
Route::get('/reports/export/inventory', [ReportController::class, 'exportInventory'])->name('reports.exportInventory');
Route::get('/reports/export/sales', [ReportController::class, 'exportSales'])->name('reports.exportSales');

// AJAX Routes for dynamic data
Route::get('/ajax/products/supplier/{supplier}', [DashboardController::class, 'getProductsBySupplier']);
Route::get('/ajax/products/store/{store}', [DashboardController::class, 'getProductsByStore']);
Route::get('/ajax/medicines/malady/{malady}', [DashboardController::class, 'getMedicinesByMalady']);

// API Routes for enhanced dashboard
Route::get('/api/dashboard-data', [DashboardController::class, 'getDashboardData']);
Route::get('/api/stock-alerts', [DashboardController::class, 'getLowStockAlerts']);
Route::get('/api/expiring-medications/{days?}', [DashboardController::class, 'getExpiringMedications']);
Route::get('/api/sales-analytics', [DashboardController::class, 'getSalesAnalytics']);
Route::get('/api/inventory-analytics', [DashboardController::class, 'getInventoryAnalytics']);
Route::get('/api/stock-distribution', [DashboardController::class, 'getStockDistribution']);
Route::get('/api/search', [DashboardController::class, 'search']);
Route::post('/api/stock-update', [DashboardController::class, 'updateStock']);
Route::get('/api/top-medications', [DashboardController::class, 'getTopMedications']);
Route::get('/api/customer-analytics', [DashboardController::class, 'getCustomerAnalytics']);
Route::get('/api/financial-analytics', [DashboardController::class, 'getFinancialAnalytics']);
