<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Web\ReportController;
use Illuminate\Support\Facades\Route;

// Public routes - accessible without authentication
Route::get('/', [ReportController::class, 'create'])->name('reports.create');
Route::post('/reports', [App\Http\Controllers\Api\ReportController::class, 'store'])->name('reports.store');
Route::get('/reports/success', [ReportController::class, 'success'])->name('reports.success');

// Chat routes for reporters
Route::post('/chat/lookup', [App\Http\Controllers\Web\ChatController::class, 'lookup'])->name('chat.lookup');
Route::get('/chat/{tag}', [App\Http\Controllers\Web\ChatController::class, 'view'])->name('chat.view');
Route::get('/chat/{tag}/messages', [App\Http\Controllers\Web\ChatController::class, 'getMessages'])->name('chat.messages');
Route::post('/chat/{tag}/message', [App\Http\Controllers\Api\ChatController::class, 'sendMessage'])->name('chat.send');

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
    Route::get('/reports/{id}/messages', [DashboardController::class, 'getMessages'])->name('reports.messages');
    Route::post('/reports/{id}/archive', [DashboardController::class, 'archiveReport'])->name('reports.archive');
    Route::post('/reports/{id}/unarchive', [DashboardController::class, 'unarchiveReport'])->name('reports.unarchive');
    Route::post('/reports/{id}/respond', [DashboardController::class, 'sendResponse'])->name('reports.respond');
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
