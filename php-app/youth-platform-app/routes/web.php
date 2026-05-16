<?php

use App\Http\Controllers\Web\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ReportController::class, 'create'])->name('reports.create');
Route::post('/reports', [App\Http\Controllers\Api\ReportController::class, 'store'])->name('reports.store');
Route::get('/reports/list', [ReportController::class, 'index'])->name('reports.index');
