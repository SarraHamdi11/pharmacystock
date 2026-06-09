<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public routes
Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);
Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);

// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    // Dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('/dashboard/low-stock', [DashboardController::class, 'lowStock']);
    
    // Products
    Route::apiResource('products', ProductController::class);
    Route::get('/products/search', [ProductController::class, 'search']);
    Route::get('/products/by-category/{category}', [ProductController::class, 'byCategory']);
    Route::get('/products/by-supplier/{supplier}', [ProductController::class, 'bySupplier']);
    Route::post('/products/import', [ProductController::class, 'import']);
    Route::get('/products/export', [ProductController::class, 'export']);
    
    // Reports
    Route::get('/reports/sales', [DashboardController::class, 'salesReport']);
    Route::get('/reports/inventory', [DashboardController::class, 'inventoryReport']);
    Route::get('/reports/expiry', [DashboardController::class, 'expiryReport']);
});
