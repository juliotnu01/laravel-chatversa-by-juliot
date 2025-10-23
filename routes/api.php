<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Auth routes
    Route::post('auth/login', [AuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('auth/user', [AuthController::class, 'user']);
        Route::post('auth/logout', [AuthController::class, 'logout']);

        // Projects
        Route::apiResource('projects', ProjectController::class);
        
        // Tasks
        Route::apiResource('tasks', TaskController::class);
        Route::post('tasks/{task}/status', [TaskController::class, 'updateStatus']);
    });
});