<?php

use App\Http\Controllers\Api\AssignmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/dashboard/statistic', [DashboardController::class, 'getStatistic']);

    Route::apiResource('customers', \App\Http\Controllers\Api\CustomerController::class);
    Route::apiResource('roles', \App\Http\Controllers\Api\RoleController::class);
    Route::apiResource('statuses', \App\Http\Controllers\Api\StatusController::class);
    Route::apiResource('users', \App\Http\Controllers\Api\UserController::class);

    Route::apiResource('assignments', AssignmentController::class);
    Route::post('assignments/{id}/check-in', [AssignmentController::class, 'checkIn']);
    Route::post('assignments/{id}/check-out', [AssignmentController::class, 'checkOut']);
    Route::post('assignments/{id}/review', [AssignmentController::class, 'review']);
});

Route::post('/login', [AuthController::class, 'login']);
