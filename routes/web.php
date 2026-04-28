<?php

use Illuminate\Support\Facades\Route;

// Front Controllers
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\ProductController as FrontProductController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\CheckoutController;

// Auth Controllers
use App\Http\Controllers\Auth\AuthController;

// User Controllers
use App\Http\Controllers\User\OrderController as UserOrderController;

// Admin Controllers
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\PackController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\RetourController;
use App\Http\Controllers\Admin\SettingController;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');

// Products
Route::get('/products', [FrontProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [FrontProductController::class, 'show'])->name('products.show');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');

// Checkout & Tracking
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{tracking_code}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::match(['get', 'post'], '/track-order', [CheckoutController::class, 'track'])->name('track.order');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// User routes
Route::middleware(['auth'])->group(function () {
    Route::get('/my-orders', [UserOrderController::class, 'index'])->name('user.orders');
    Route::post('/my-orders/{order}/return', [UserOrderController::class, 'returnOrder'])->name('user.orders.return');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    Route::resource('categories', CategoryController::class)->except('show');
    Route::resource('brands', BrandController::class)->except('show');
    
    Route::resource('products', ProductController::class);
    Route::post('products/image/{image}/delete', [ProductController::class, 'deleteImage'])->name('products.image.delete');
    Route::post('products/image/{image}/main', [ProductController::class, 'setMainImage'])->name('products.image.main');
    
    Route::resource('packs', PackController::class);
    
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('orders/{order}/manual-return', [OrderController::class, 'manualReturn'])->name('orders.manual-return');
    
    Route::get('retours', [RetourController::class, 'index'])->name('retours.index');
    Route::get('retours/{retour}', [RetourController::class, 'show'])->name('retours.show');
    Route::post('retours/{retour}/status', [RetourController::class, 'updateStatus'])->name('retours.status');
    
    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
});
