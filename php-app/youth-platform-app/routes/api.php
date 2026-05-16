<?php

use App\Http\Controllers\Api\ReportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:10,1', 'api'])->group(function () {
    Route::post('/reports', [ReportController::class, 'store']);
});
