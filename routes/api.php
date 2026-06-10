<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PenanggungJawabController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
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

    /**
     * Routes untuk role bendahara
     * Hanya bisa diakses oleh user dengan role bendahara
     */
    Route::middleware('role:bendahara')->group(function () {

        /**
         * User management routes
         * Get /api/user -> get list user
         * Post /api/user -> create user baru
         * Put /api/user/{id}/toggle-status -> toggle status user dengan id tertentu
         * Put /api/user/{id} -> update user dengan id tertentu
         * Delete /api/user/{id} -> delete user dengan id tertentu
         */
        Route::put('/user/{id}/toggle-status', [UserController::class, 'toggleStatus']);
        Route::apiResource('user', UserController::class)
            ->except(['show']);

        /**
         * Routes untuk manajemen penanggung jawab
         * Get /api/penanggung-jawab -> get list penanggung jawab
         * Get /api/penanggung-jawab/{id} -> get detail transaksi penanggung jawab dengan id tertentu
         * Post /api/penanggung-jawab -> create penanggung jawab baru
         * Put /api/penanggung-jawab/{id} -> update penanggung jawab dengan id tertentu
         * Delete /api/penanggung-jawab/{id} -> delete penanggung
         */
        Route::apiResource('penanggung-jawab', PenanggungJawabController::class);
    });
});
