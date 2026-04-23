<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'create'])->name('login');
    Route::post('login', [AuthController::class, 'store']);
    
    Route::get('forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'destroy'])->name('logout');
    
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Profile Routes
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // Admin Routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('students', \App\Http\Controllers\StudentController::class);
        Route::resource('teachers', \App\Http\Controllers\TeacherController::class);
    });
    
    // Shared Report Route (Admin & Guru)
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');

    // Guru Routes — QR Scan exclusively for teachers
    Route::middleware('role:guru')->group(function () {
        Route::get('/scan', [\App\Http\Controllers\AttendanceController::class, 'scan'])->name('attendance.scan');
        Route::post('/attendance', [\App\Http\Controllers\AttendanceController::class, 'store'])->name('attendance.store');
    });

    // Student Routes
    Route::middleware('role:student')->group(function () {
        Route::get('/my-qr', [\App\Http\Controllers\AttendanceController::class, 'showStudentQr'])->name('attendance.my-qr');
        Route::get('/my-attendance', [\App\Http\Controllers\AttendanceController::class, 'history'])->name('attendance.history');
    });

    // Notifications API
    Route::get('/api/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/api/notifications/read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read');
});
