<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HistoryRiilController;
use App\Http\Controllers\MutasiRekeningController;
use App\Http\Controllers\PenanggungJawabController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaksiController;
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
         * Get /api/penanggung-jawab/dropdown -> get list penanggung jawab untuk dropdown (hanya id dan nama)
         * Post /api/penanggung-jawab -> create penanggung jawab baru
         * Put /api/penanggung-jawab/{id} -> update penanggung jawab dengan id tertentu
         * Delete /api/penanggung-jawab/{id} -> delete penanggung
         */
        Route::get('/penanggung-jawab/dropdown', [PenanggungJawabController::class, 'dropdown']);
        Route::apiResource('penanggung-jawab', PenanggungJawabController::class);

        /**
         * Routes untuk manajemen akun
         * Get /api/akun -> get list akun
         * Get /api/akun/{id} -> get detail akun beserta transaksinya
         * Get /api/akun/dropdown -> get list akun untuk dropdown (hanya id dan nama_akun)
         * Post /api/akun -> create akun baru
         * Put /api/akun/{id} -> update akun dengan id tertentu
         * Delete /api/akun/{id} -> delete akun
         */
        Route::get('/akun/dropdown', [AkunController::class, 'dropdown']);
        Route::apiResource('akun', AkunController::class);

        /**
         * Route untuk History Riil
         * Get /api/history-riil -> get list transaksi riil
         * Put /api/history-riil/{id}/verify -> verify transaksi riil dengan id tertentu
         */
        Route::prefix('history-riil')->controller(HistoryRiilController::class)->group(function () {
            Route::get('/', 'index');
            Route::put('/{id}/verify', 'verify');
        });

        /**
         * Route untuk manajemen mutasi rekening
         * Get /api/mutasi-rekening -> get list mutasi rekening
         * Post /api/mutasi-rekening -> create mutasi rekening baru
         * Put /api/mutasi-rekening/{id} -> update mutasi rekening dengan id tertentu
         * Delete /api/mutasi-rekening/{id} -> delete mutasi rekening
         */
        Route::apiResource('mutasi-rekening', MutasiRekeningController::class)
            ->except(['show']);

        /**
         * Route untuk manajemen transaksi
         * Get /api/transaksi -> get list transaksi
         * Get /api/transaksi/{id} -> get detail transaksi dengan id tertentu
         * Post /api/transaksi -> create transaksi baru
         * Put /api/transaksi/{id} -> update transaksi dengan id tertentu
         * Delete /api/transaksi/{id} -> delete transaksi dengan id tertentu
         */
        Route::apiResource('transaksi', TransaksiController::class)
            ->except(['show']);
    });
});
