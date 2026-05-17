<?php

use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\MessageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:10,1', 'api'])->group(function () {
    Route::post('/reports', [ReportController::class, 'store']);
});

// Anonymous chat routes for reports (public access with rate limiting)
Route::middleware(['throttle:20,1', 'api'])->group(function () {
    Route::get('/reports/{anonymousTag}/messages', [MessageController::class, 'getMessages']);
    Route::post('/reports/{anonymousTag}/messages', [MessageController::class, 'sendMessage']);
});

// Admin chat routes (requires authentication)
Route::middleware(['auth:sanctum', 'api'])->group(function () {
    Route::post('/admin/reports/{reportId}/respond', [MessageController::class, 'adminRespond']);
});


