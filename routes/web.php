<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;

Route::get('/', fn () => redirect()->route('dashboard.index'));

Route::middleware(['auth'])->group(function () {
    Route::get('/search', [SearchController::class, 'global'])->name('search.global');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Products Management
    Route::middleware(['can:manage products'])->group(function () {
        Route::resource('products', ProductController::class)->except(['destroy']);
        Route::resource('categories', CategoryController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::resource('stores', StoreController::class);
        Route::resource('stocks', StockController::class);
        Route::get('/import/products', [ProductController::class, 'import'])->name('products.import');
        Route::post('/import/products', [ProductController::class, 'importStore'])->name('products.import.store');
        Route::get('/export/products', [ProductController::class, 'export'])->name('products.export');
    });

    // Orders Management
    Route::middleware(['can:manage orders'])->group(function () {
        Route::resource('orders', OrderController::class)->except(['destroy']);
    });

    // Patients Management
    Route::middleware(['can:manage patients'])->group(function () {
        Route::resource('customers', CustomerController::class)->except(['destroy']);
    });

    // Admin & Manager: Reports and Activity
    Route::middleware(['role:Admin|Manager'])->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/sales', [ReportController::class, 'salesReport'])->name('reports.sales');
        Route::get('/reports/inventory', [ReportController::class, 'inventoryReport'])->name('reports.inventory');
        Route::get('/reports/customers', [ReportController::class, 'customerReport'])->name('reports.customers');
        Route::get('/reports/suppliers', [ReportController::class, 'supplierReport'])->name('reports.suppliers');
        Route::get('/reports/expiry', [ReportController::class, 'expiry'])->name('reports.expiry');
        Route::get('/reports/export/inventory', [ReportController::class, 'exportInventory'])->name('reports.exportInventory');
        Route::get('/reports/export/sales', [ReportController::class, 'exportSales'])->name('reports.exportSales');
        Route::get('/activities', [\App\Http\Controllers\ActivityController::class, 'index'])->name('activities.index');
    });

    // Admin Only: Critical Deletions
    Route::middleware(['role:Admin'])->group(function () {
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
        Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    });

    // Internal APIs
    Route::prefix('api')->group(function () {
        Route::get('/dashboard/data', [DashboardController::class, 'getDashboardData']);
        Route::get('/dashboard/low-stock', [DashboardController::class, 'getLowStockAlerts']);
        Route::get('/dashboard/expiring/{days?}', [DashboardController::class, 'getExpiringMedications']);
        Route::get('/dashboard/sales-analytics', [DashboardController::class, 'getSalesAnalytics']);
        Route::get('/dashboard/inventory-analytics', [DashboardController::class, 'getInventoryAnalytics']);
    });
});

Auth::routes();
