<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Badge\BadgeController;
use App\Http\Controllers\Bus\BusController;
use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Contribution\ContributionController;
use App\Http\Controllers\DevOps\DevOpsController;
use App\Http\Controllers\Notification\NotificationController;
use App\Http\Controllers\Route\RouteController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Get authenticated user
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::prefix('routes')->group(function () {
        // Public endpoints
        Route::get('export', [RouteController::class, 'export']);
        Route::get('', action: [RouteController::class, 'index']);
        Route::get('{route}', [RouteController::class, 'show']);

        // Admin-only endpoints
        Route::middleware(['auth:sanctum', 'can:is_admin'])->group(function () {
            Route::post('', [RouteController::class, 'store']);
            Route::put('{route}', [RouteController::class, 'update']);
            Route::delete('{route}', [RouteController::class, 'destroy']);
        });
    });
    Route::prefix('badges')->group(function () {
        Route::middleware(['auth:sanctum', 'can:is_admin'])->group(function () {
            // Badge endpoints for Admin
            Route::get('', [BadgeController::class, 'index']);
            Route::post('', [BadgeController::class, 'store']);
            Route::get('{badge}', [BadgeController::class, 'show']);
            Route::put('{badge}', [BadgeController::class, 'update']);
            Route::delete('{badge}', [BadgeController::class, 'destroy']);
        });
    });

    // Admin dashboard and management endpoints
    Route::prefix('admin')
        ->middleware(['auth:sanctum', \App\Http\Middleware\AdminMiddleware::class])
        ->group(function () {
            Route::get('dashboard', [AdminController::class, 'dashboard']);
            Route::get('contributions', [AdminController::class, 'contributions']);
            Route::get('routes', [AdminController::class, 'routes']);
            Route::get('users', [AdminController::class, 'users']);
        });
    Route::prefix('notifications')->group(function () {
        Route::post('test', [NotificationController::class, 'testNotification']);
    });

    // Public routes with session support for OAuth
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('forgot-password', [AuthController::class, 'forgotPassword']);

        // Protected routes
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('user', [AuthController::class, 'user']);
            Route::post('resend-verification', [AuthController::class, 'resendVerificationEmail']);
            Route::get('verify-email/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');
        });
    });

    // User profile routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('users/{user}', [UserController::class, 'show']);
        Route::put('user/profile', [UserController::class, 'updateProfile']);

        // Route::prefix('rewards')->group(function () {
        //     Route::get('', [RewardController::class, 'index']);
        //     Route::get('history', [RewardController::class, 'history']);
        //     Route::post('', [RewardController::class, 'awardPoints']);
        // });
    });

    // Chat endpoints
    Route::middleware('auth:sanctum')->prefix('chat')->group(function () {
        Route::get('{bus}', [ChatController::class, 'index']);
        Route::post('{bus}', [ChatController::class, 'store']);
    });

    // DevOps endpoints (token-protected)
    Route::post('devops/clear-cache', [DevOpsController::class, 'clearCache']);

    // Contributions endpoints
    Route::prefix('contributions')->group(function () {
        // Public endpoint - anyone can view latest contributions
        Route::get('latest', [ContributionController::class, 'getLatest']);

        // Authenticated endpoints - require login to submit
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('location', [ContributionController::class, 'submitLocation']);
            Route::post('crowding', [ContributionController::class, 'submitCrowding']);
        });
    });

    // Buses endpoints (public)
    Route::prefix('buses')->group(function () {
        Route::get('', [BusController::class, 'index']);
        Route::get('{bus}', [BusController::class, 'show']);
        Route::get('route/{route}', [BusController::class, 'getByRoute']);

        Route::middleware(['auth:sanctum', 'can:is_admin'])->group(function () {
            Route::post('', [BusController::class, 'store']);
            Route::put('{bus}', [BusController::class, 'update']);
            Route::delete('{bus}', [BusController::class, 'destroy']);
        });
    });
});
