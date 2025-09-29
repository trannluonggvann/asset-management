<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\ReportController;

// Authentication routes
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/', [AuthController::class, 'login']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Asset management
    Route::resource('assets', AssetController::class);
    
    // QR Code routes
    Route::get('/assets/{asset}/qr', [AssetController::class, 'showQR'])->name('assets.qr');
    Route::get('/qr-scan', function() {
        return view('qr.scan');
    })->name('qr.scan');
    
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/assets', [ReportController::class, 'assetReport'])->name('reports.assets');
    Route::get('/reports/activities', [ReportController::class, 'activityReport'])->name('reports.activities');
    Route::get('/reports/export', [ReportController::class, 'exportAssets'])->name('reports.export');
});
