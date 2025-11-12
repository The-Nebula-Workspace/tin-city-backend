<?php


use App\Http\Controllers\BadgeController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DevOpsController;

Route::prefix('v1')->group(function () {
    Route::prefix('routes')->group(function () {
        // Public endpoints
        Route::get('export', [RouteController::class, 'export']);
        Route::get('', action: [RouteController::class, 'index']);
        Route::get('{id}', [RouteController::class, 'show']);

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
            Route::get('{id}', [BadgeController::class, 'show']);
            Route::put('{badge}', [BadgeController::class, 'update']);
            Route::delete('{badge}', [BadgeController::class, 'destroy']);
        });
    });

    // Admin dashboard and management endpoints
    Route::middleware(['auth:sanctum', 'can:is_admin'])->group(function () {
        Route::get('admin/dashboard', [AdminController::class, 'dashboard']);
        Route::get('admin/contributions', [AdminController::class, 'contributions']);
        Route::get('admin/routes', [AdminController::class, 'routes']);
        Route::get('admin/users', [AdminController::class, 'users']);
    });

    // To test for admin and regular users Gate::define('is_admin', fn(User $user) => $user->role === 'admin');
    Route::prefix('notifications')->group(function () {
        Route::post('test', [NotificationController::class, 'testNotification']);
    });

    // Admin routes group (use admin prefix)
    Route::prefix('admin')->middleware(['auth:sanctum', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard']);
    });

    // Public routes with session support for OAuth
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('forgot-password', [AuthController::class, 'forgotPassword']);

        // Google OAuth routes with web middleware for session support
        Route::middleware('web')->group(function () {
            Route::get('redirect', [GoogleAuthController::class, 'redirectToGoogle']);
            Route::get('callback', [GoogleAuthController::class, 'handleGoogleCallback']);
        });


        // Protected routes
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('user', [AuthController::class, 'user']);
            Route::post('resend-verification', [AuthController::class, 'resendVerificationEmail']);
            Route::get('verify-email/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');
        });
    });

    // DevOps endpoints (token-protected)
    Route::post('devops/clear-cache', [DevOpsController::class, 'clearCache']);

    // Contributions endpoints (authenticated)
    Route::prefix('contributions')->middleware('auth:sanctum')->group(function () {
        Route::post('location', [\App\Http\Controllers\ContributionController::class, 'submitLocation']);
        Route::post('crowding', [\App\Http\Controllers\ContributionController::class, 'submitCrowding']);
        Route::get('latest', [\App\Http\Controllers\ContributionController::class, 'getLatest']);
    });
});
