<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RewardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RouteController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function () {
    // Public endpoints
    Route::get('routes/export', [RouteController::class, 'export']);
    Route::get('routes', [RouteController::class, 'index']);
    Route::get('routes/{id}', [RouteController::class, 'show']);

    // Admin-only endpoints
    Route::middleware(['auth:sanctum', 'can:is_admin'])->group(function () {
        Route::post('routes', [RouteController::class, 'store']);
        Route::put('routes/{route}', [RouteController::class, 'update']);
        Route::delete('routes/{route}', [RouteController::class, 'destroy']);
    });

    // To test for admin and regular users Gate::define('is_admin', fn(User $user) => $user->role === 'admin');
    Route::post('login', [RouteController::class, 'login']);

    Route::prefix('notifications')->group(function () {
        Route::post('test', [NotificationController::class, 'testNotification']);
    });

     Route::get('/rewards', [RewardController::class, 'index']);
    Route::get('/rewards/history', [RewardController::class, 'history']);
    Route::post('/rewards', [RewardController::class, 'awardPoints']);
});
