<?php

use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function (): void {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store'])->name('login.store');
});

Route::post('logout', [LoginController::class, 'destroy'])->name('logout')->middleware('auth');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('customers', CustomerController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('statuses', StatusController::class);
    Route::resource('users', UserController::class);
    Route::resource('assignments', \App\Http\Controllers\Admin\AssignmentController::class);
    Route::post('assignments/{assignment}/complete', [\App\Http\Controllers\Admin\AssignmentController::class, 'complete'])->name('assignments.complete');
    Route::post('assignments/{assignment}/return', [\App\Http\Controllers\Admin\AssignmentController::class, 'returnTask'])->name('assignments.return');
});

Route::middleware(['auth'])->prefix('technician')->name('technician.')->group(function (): void {
    Route::get('/', [\App\Http\Controllers\Technician\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/assignments/{id}', [\App\Http\Controllers\Technician\AssignmentController::class, 'show'])->name('assignments.show');
    Route::post('/assignments/{id}/check-in', [\App\Http\Controllers\Technician\AssignmentController::class, 'checkIn'])->name('assignments.checkin');
    Route::post('/assignments/{id}/check-out', [\App\Http\Controllers\Technician\AssignmentController::class, 'checkOut'])->name('assignments.checkout');
});
