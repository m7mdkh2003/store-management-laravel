<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/category/{category}', [HomeController::class, 'category'])->name('category.products');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:5,1')
        ->name('register.store');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/my-orders', [OrderController::class, 'mine'])->name('orders.mine');
    Route::post('/order/{product}', [OrderController::class, 'store'])->name('orders.store');
    Route::patch('/my-orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::get('/products-archive', [ProductController::class, 'archived'])->name('products.archived');
        Route::patch('/products-archive/{product}/restore', [ProductController::class, 'restore'])->name('products.restore');
        Route::resource('products', ProductController::class)->except(['show']);
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    });
});
