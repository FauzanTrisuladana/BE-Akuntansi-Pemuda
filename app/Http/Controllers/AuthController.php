<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LoginGoogleRequest;
use App\Http\Resources\ApiResource;
use App\Http\Resources\UserResource;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Api untuk login user dengan email dan password.
     * Post /api/auth/login
     */
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        if (!auth()->attempt($validated)) {
            abort(401, 'Email atau password salah');
        }

        $user = auth()->user();

        if ($user->status !== 'Aktif') {
            abort(403, 'Akun Anda belum aktif. Silakan hubungi admin untuk mengaktifkan akun Anda.');
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return (new UserResource($user))->additional([
            'auth' => [
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ])
        ->message('Login berhasil');
    }

    /**
     * Api untuk login user dengan Google.
     * Post /api/auth/login-google
     */
    public function loginGoogle(LoginGoogleRequest $request)
    {
        $validated = $request->validated();

        $googleClientId = (string) config('services.google.client_id', '');
        $tokenInfoResponse = Http::get('https://oauth2.googleapis.com/tokeninfo', [
            'id_token' => $validated['id_token'],
        ]);

        if (!$tokenInfoResponse->successful()) {
            abort(401, 'Google token tidak valid');
        }

        $payload = $tokenInfoResponse->json();

        if (($payload['aud'] ?? null) !== $googleClientId) {
            abort(401, 'Google token audience tidak sesuai');
        }

        if (!isset($payload['email'], $payload['sub'])) {
            abort(401, 'Google token payload tidak lengkap');
        }

        $user = User::withTrashed()->firstOrCreate(
            [
                'email' => $payload['email'],
            ],
            [
                'name' => $payload['name'],
                'provider' => 'google',
                'id_provider' => $payload['sub'],
                'status' => 'pending',
                'profile_image' => $payload['picture'] ?? null,
                'role' => 'biasa',
            ]
        );

        if ($user->status !== 'Aktif') {
            abort(403, 'Akun Anda belum aktif. Silakan hubungi admin untuk mengaktifkan akun Anda.');
        }

        $user->update([
            'name' => $payload['name'],
            'provider' => 'google',
            'id_provider' => $payload['sub'],
            'profile_image' => $payload['picture'] ?? null,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return (new UserResource($user))->additional([
            'auth' => [
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ])
        ->message('Login dengan Google berhasil');
    }

    /**
     * Api untuk logout user.
     * Post /api/auth/logout
     */
    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();

        return (new ApiResource(null))
            ->message('Berhasil logout');
    }
}
