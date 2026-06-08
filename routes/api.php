<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/**
 * Status check route.
 * Get /api/status
 */
Route::get('/status', function () {
    return response()->json(['status' => 'ok']);
});

/**
 * Authentication routes.
 * Post /api/auth/login -> login manual pake password
 * Post /api/auth/login-google -> login pake google
 * Post /api/auth/logout -> logout user
 */
Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/login-google', 'loginGoogle');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', 'logout');
    });
});


Route::middleware('auth:sanctum')->group(function () {

    /**
     * Profile routes
     * Get /api/profile/me -> get profile user yang sedang login
     * Put /api/profile/update -> update profile user yang sedang login
     * Put /api/profile/update-password -> update password user yang sedang login
     */
    Route::prefix('profile')->controller(ProfileController::class)->group(function () {
        Route::get('/me', 'me');
        Route::put('/update', 'update');
        Route::put('/update-password', 'updatePassword');
        Route::delete('/delete', 'delete');
    });


});
