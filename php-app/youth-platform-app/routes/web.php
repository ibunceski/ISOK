<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Web\ReportController;
use Illuminate\Support\Facades\Route;

// Public routes - accessible without authentication
Route::get('/', [ReportController::class, 'create'])->name('reports.create');
Route::post('/reports', [App\Http\Controllers\Api\ReportController::class, 'store'])->name('reports.store');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Admin-only routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports/{id}', [DashboardController::class, 'showReport'])->name('reports.show');
    Route::get('/reports', function() {
        return redirect()->route('admin.dashboard');
    })->name('reports.index');
});

// Legacy route - redirect to admin dashboard for authenticated users
Route::get('/reports/list', function () {
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
})->name('reports.index');
