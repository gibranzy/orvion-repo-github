<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\ProductController as UserProductController;
use App\Http\Controllers\User\WishlistController as UserWishlistController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\User\CartController as UserCartController;
use App\Http\Controllers\User\SettingsController as UserSettingsController;
use App\Http\Controllers\ProfileController;

// ==========================================
// WELCOME PAGE (Landing Page)
// ==========================================
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// ==========================================
// ROOT REDIRECT
// ==========================================
Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->role === 'admin' 
            ? redirect()->route('admin.dashboard')
            : redirect()->route('user.dashboard');
    }
    return redirect()->route('welcome');
});

// ==========================================
// SHARED DASHBOARD
// ==========================================
Route::middleware(['auth'])->get('/dashboard', function () {
    $user = Auth::user();
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('user.dashboard');
})->name('dashboard');

// ==========================================
// PROFILE ROUTES (SHARED - Admin & User)
// ==========================================
Route::middleware(['auth'])->group(function () {
    Route::delete('/profile/delete-avatar', [ProfileController::class, 'deleteAvatar'])
        ->name('profile.delete-avatar');
});

// ==========================================
// ADMIN ROUTES
// ==========================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Analytics (BARU!)
    Route::get('/analytics', [AdminDashboardController::class, 'analytics'])->name('analytics');
    
    // Products Management
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    
    // Users Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    
    // Settings
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingsController::class, 'store'])->name('settings.store');

    // Orders Management
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
});

// ==========================================
// USER ROUTES
// ==========================================
Route::middleware(['auth', 'user'])->prefix('user')->name('user.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    
    // Search (HARUS sebelum /products/{product})
    Route::get('/products/search', [UserProductController::class, 'search'])->name('products.search');
    
    // Products Catalog
    Route::get('/products', [UserProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [UserProductController::class, 'show'])->name('products.show');
    
    // Wishlist
    Route::get('/wishlist', [UserWishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [UserWishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/{wishlist}', [UserWishlistController::class, 'destroy'])->name('wishlist.destroy');
    
    // Orders
    Route::get('/orders', [UserOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [UserOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders', [UserOrderController::class, 'store'])->name('orders.store');
    Route::post('/orders/{order}/cancel', [UserOrderController::class, 'cancel'])->name('orders.cancel');
    
    // Cart Routes
    Route::get('/cart', [UserCartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [UserCartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [UserCartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{productId}', [UserCartController::class, 'remove'])->name('cart.remove');
    Route::get('/cart/count', [UserCartController::class, 'count'])->name('cart.count');
    Route::get('/cart/checkout', [UserCartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/cart/process-checkout', [UserCartController::class, 'processCheckout'])->name('cart.processCheckout');
    Route::post('/cart/clear', [UserCartController::class, 'clear'])->name('cart.clear');
    
    // Settings
    Route::get('/settings', [UserSettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [UserSettingsController::class, 'store'])->name('settings.store');
});

// Auth routes from Breeze
require __DIR__.'/auth.php';