<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuCategoryController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\PaymentController;

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Application
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('menu-categories', MenuCategoryController::class);
    Route::resource('menu-items', MenuItemController::class);
    Route::resource('packages', PackageController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('orders', OrderController::class);

    // Nested: Order Items
    Route::get('orders/{order}/items/create', [OrderItemController::class, 'create'])->name('orders.items.create');
    Route::post('orders/{order}/items', [OrderItemController::class, 'store'])->name('orders.items.store');
    Route::delete('orders/{order}/items/{item}', [OrderItemController::class, 'destroy'])->name('orders.items.destroy');

    // Nested: Payments
    Route::get('orders/{order}/payments/create', [PaymentController::class, 'create'])->name('orders.payments.create');
    Route::post('orders/{order}/payments', [PaymentController::class, 'store'])->name('orders.payments.store');
    Route::delete('orders/{order}/payments/{payment}', [PaymentController::class, 'destroy'])->name('orders.payments.destroy');
});

// Health check
Route::get('/healthz', function () {
    return response()->noContent();
});

