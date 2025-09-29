<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\NotificationController;

// Authentication routes
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/', [AuthController::class, 'login']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// QR Code public access (for scanning)
Route::get('/qr/{asset_code}', [AssetController::class, 'showAssetByQR'])->name('qr.asset');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Asset management
    Route::resource('assets', AssetController::class);
    
    // Employee management
    Route::resource('employees', EmployeeController::class);
    Route::get('/employees/{employee}/assignments', [EmployeeController::class, 'getAssignments'])->name('employees.assignments');
    
    // Department management
    Route::resource('departments', DepartmentController::class);
    Route::get('/departments/{department}/employees', [DepartmentController::class, 'getEmployees'])->name('departments.employees');
    
    // Asset assignments
    Route::resource('assignments', AssignmentController::class);
    Route::get('/assignments/{assignment}/return', [AssignmentController::class, 'showReturnForm'])->name('assignments.return.form');
    Route::post('/assignments/{assignment}/return', [AssignmentController::class, 'return'])->name('assignments.return');
    Route::get('/api/assignments/expiring', [AssignmentController::class, 'getExpiringAssignments'])->name('assignments.expiring');
    
    // Incident management
    Route::resource('incidents', IncidentController::class);
    Route::post('/incidents/{incident}/resolve', [IncidentController::class, 'resolve'])->name('incidents.resolve');
    Route::post('/incidents/{incident}/close', [IncidentController::class, 'close'])->name('incidents.close');
    Route::get('/assets/{asset}/incidents', [IncidentController::class, 'getAssetIncidents'])->name('assets.incidents');
    
    // Notifications
    Route::resource('notifications', NotificationController::class)->only(['index', 'show', 'destroy']);
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::get('/api/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::get('/api/notifications/recent', [NotificationController::class, 'getRecent'])->name('notifications.recent');
    
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
