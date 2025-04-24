<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\MaladieController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MedicamentController;

// Routes d'authentification
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

Route::get('/password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Route de changement de langue/locale (important : avec le nom "locale")
Route::get('/locale/{locale}', function (string $locale) {
    if (in_array($locale, ['en', 'es', 'fr', 'ar'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('locale');

// Routes authentifiées
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Resources (CRUD automatique)
    Route::resource('products', ProductController::class);
    Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('stores', StoreController::class);
    Route::resource('stocks', StockController::class);
    Route::resource('maladies', MaladieController::class);
    Route::resource('medicaments', MedicamentController::class);

    // Routes filtrées par catégorie
    Route::get('/products-by-category', [CategoryController::class, 'productsByCategory'])
        ->name('products.by.category');
    Route::get('/products-by-category/{category}', [CategoryController::class, 'getProductsByCategory'])
        ->name('products.filter.by.category');

    // Routes filtrées par fournisseur
    Route::get('/products-by-supplier', [DashboardController::class, 'productsBySupplier'])
        ->name('products.by.supplier');
    Route::get('/api/products-by-supplier/{supplier}', [DashboardController::class, 'getProductsBySupplier'])
        ->name('api.products.by.supplier');

    // Routes filtrées par magasin
    Route::get('/products-by-store', [DashboardController::class, 'productsByStore'])
        ->name('products.by.store');
    Route::get('/api/products-by-store/{store}', [DashboardController::class, 'getProductsByStore'])
        ->name('api.products.by.store');

    // Routes diverses tableau de bord
    Route::get('/customers', [DashboardController::class, 'customers'])->name('customers.index');
    Route::get('/customers/search', [CustomerController::class, 'search'])->name('customers.search');
    Route::get('/suppliers', [DashboardController::class, 'suppliers'])->name('suppliers.index');
    Route::get('/orders', [DashboardController::class, 'orders'])->name('orders.index');

    // Routes pour les cookies/sessions/avatar
    Route::post("/saveCookie", [DashboardController::class, 'saveCookie'])->name("saveCookie");
    Route::post("/saveSession", [DashboardController::class, 'saveSession'])->name("saveSession"); 
    Route::post("/saveAvatar", [DashboardController::class, 'saveAvatar'])->name("saveAvatar");

    // Import/Export
    Route::get('products-export', [ProductController::class, 'export'])->name('products.export');
    Route::post('products-import', [ProductController::class, 'import'])->name('products.import');
});

// Raccourci pour toutes les routes d'authentification
Auth::routes();

// Redirection vers le tableau de bord 
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');