<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Authentication Routes
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth.check'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Management
    Route::get('/management', [ManagementController::class, 'index'])->name('management');
    Route::post('/dormitories', [ManagementController::class, 'storeDormitory'])->name('dormitories.store');
    Route::delete('/dormitories/{dormitory}', [ManagementController::class, 'destroyDormitory'])->name('dormitories.destroy');
    Route::post('/students', [ManagementController::class, 'storeStudent'])->name('students.store');
    Route::delete('/students/{student}', [ManagementController::class, 'destroyStudent'])->name('students.destroy');
    Route::post('/students/upload-excel', [ManagementController::class, 'uploadExcel'])->name('students.upload-excel');

    // Records
    Route::get('/records', [RecordController::class, 'index'])->name('records');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/reports/{record}', [ReportController::class, 'show'])->name('reports.show');
});
