<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\CategoryController as DashboardCategoryController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Auth session (admin only). API REST ada di routes/api.php.

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::redirect('/', '/dashboard/index');

Route::middleware(['auth', 'admin'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::redirect('/', '/dashboard/index');
    Route::get('/index', [DashboardController::class, 'index'])->name('home');
    Route::get('/orders', [DashboardController::class, 'orders'])->name('orders');

    // Contoh CRUD (AJAX)
    Route::get('/categories', [DashboardCategoryController::class, 'index'])->name('categories');
    Route::get('/categories/data', [DashboardCategoryController::class, 'data'])->name('categories.data');
    Route::post('/categories', [DashboardCategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{id}', [DashboardCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [DashboardCategoryController::class, 'destroy'])->name('categories.destroy');
});
