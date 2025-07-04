<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\JobListingController;
use App\Http\Controllers\Api\Admin\MetricsController;
use App\Http\Controllers\Api\Candidate\ApplicationController;
use App\Http\Controllers\Api\Employer\JobListingController as EmployerJobListingController;
use App\Http\Controllers\Api\Employer\ApplicationController as EmployerApplicationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

// Public job listings
Route::get('jobs', [JobListingController::class, 'index']);
Route::get('jobs/{jobListing}', [JobListingController::class, 'show']);

// Protected routes
Route::middleware('auth:api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
    });

    // Employer routes
    Route::middleware(['role:employer,api'])->prefix('employer')->group(function () {
        Route::apiResource('jobs', EmployerJobListingController::class);
        Route::get('jobs/{jobListing}/applications', [EmployerApplicationController::class, 'index']);
        Route::patch('jobs/{jobListing}/applications/{application}/status', [EmployerApplicationController::class, 'updateStatus']);
    });

    // Candidate routes
    Route::middleware(['role:candidate,api'])->prefix('candidate')->group(function () {
        Route::get('applications', [ApplicationController::class, 'index']);
        Route::post('jobs/{jobListing}/apply', [ApplicationController::class, 'apply']);
    });

    // Admin routes
    Route::middleware(['role:admin,api'])->prefix('admin')->group(function () {
        Route::get('metrics', [MetricsController::class, 'index']);
    });
});
