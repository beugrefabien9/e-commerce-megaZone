<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StoreController::class, 'index'])->name('store.index');
Route::get('/category/{category}', [StoreController::class, 'category'])->name('store.category');
Route::get('/product/{product}', [StoreController::class, 'product'])->name('store.product');
Route::get('/search', [StoreController::class, 'search'])->name('store.search');

// Cart routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Checkout routes
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
});

// Admin routes
Route::prefix('administrateur')->middleware('auth')->group(function () {
    // Dashboard - accès direct à l'admin
    Route::get('/admin', [AdminProductController::class, 'dashboard'])->name('admin.store');

    // Products routes
    Route::get('/admin/products', [AdminProductController::class, 'index'])->name('admin.products.index');
    Route::get('/admin/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
    Route::post('/admin/products', [AdminProductController::class, 'store'])->name('admin.products.store');
    Route::get('/admin/products/{product}', [AdminProductController::class, 'show'])->name('admin.products.show');
    Route::get('/admin/products/{product}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/admin/products/{product}', [AdminProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/admin/products/{product}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');

    // Categories routes
    Route::resource('/admin/categories', CategoryController::class, ['as' => 'admin']);

    // Sub-categories routes
    Route::resource('/admin/sub-categories', SubCategoryController::class, ['as' => 'admin']);

    //commandes
    Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/admin/orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::patch('/admin/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    Route::patch('/admin/orders/{order}/payment-status', [AdminOrderController::class, 'updatePaymentStatus'])->name('admin.orders.updatePaymentStatus');
    
});

// Authentication routes pour la boutique
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Déconnexion boutique
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Authentication routes pour l'admin
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [LoginController::class, 'login']);
    Route::get('/admin/register', [RegisterController::class, 'showRegistrationForm'])->name('admin.register');
    Route::post('/admin/register', [RegisterController::class, 'register']);
});

// Déconnexion admin
Route::middleware('auth')->group(function () {
    Route::post('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout');
});




