<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginGoogleRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\ApiResource;
use App\Http\Resources\UserResource;
use App\Mail\NewUserNotification;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * Api untuk login user dengan email dan password.
     * Post /api/auth/login
     */
    public function login(LoginRequest $request): ApiResource
    {
        $validated = $request->validated();

        $user = User::withTrashed()->where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            abort(401, 'Email atau password salah');
        }

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
    public function loginGoogle(LoginGoogleRequest $request): ApiResource
    {
        $validated = $request->validated();

        $googleClientId = (string) config('services.google.client_id', '');
        $tokenInfoResponse = Http::get('https://oauth2.googleapis.com/tokeninfo', [
            'id_token' => $validated['id_token'],
        ]);

        if (! $tokenInfoResponse->successful()) {
            abort(401, 'Google token tidak valid');
        }

        $payload = $tokenInfoResponse->json();

        if (($payload['aud'] ?? null) !== $googleClientId) {
            abort(401, 'Google token audience tidak sesuai');
        }

        if (! isset($payload['email'], $payload['sub'])) {
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

        if ($user->wasRecentlyCreated) {
            Mail::to('fauzantrisuladana@gmail.com')->send(new NewUserNotification(
                userName: $payload['name'],
                userEmail: $payload['email'],
            ));
        }

        if ($user->status !== 'Aktif') {
            abort(403, 'Akun Anda belum aktif. Silakan hubungi admin untuk mengaktifkan akun Anda.');
        }

        $updateData = [
            'name' => $payload['name'],
            'provider' => 'google',
            'id_provider' => $payload['sub'],
        ];

        $currentProfileImage = $user->profile_image;
        if (! $currentProfileImage || ! str_contains($currentProfileImage, 'res.cloudinary.com')) {
            $updateData['profile_image'] = $payload['picture'] ?? null;
        }

        $user->update($updateData);

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
    public function logout(): ApiResource
    {
        auth()->user()->currentAccessToken()->delete();

        return (new ApiResource(null))
            ->message('Berhasil logout');
    }
}
