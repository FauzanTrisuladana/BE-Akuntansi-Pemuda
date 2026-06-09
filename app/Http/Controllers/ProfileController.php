<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Resources\ApiResource;
use App\Http\Resources\UserResource;

class ProfileController extends Controller
{
    /**
    * Api untuk mengambil profile user yang sedang login.
    * Get /api/profile/me
    */
    public function me(Request $request)
    {
        $user = $request->user();

        return (new UserResource($user))
            ->message('Profile berhasil diambil');
    }

    /**
    * Api untuk update profile user yang sedang login.
    * Put /api/profile/update
    */
    public function update(UpdateProfileRequest $request)
    {
        $validated = $request->validated();

        $user = $request->user();

        $user->update($validated);

        return (new UserResource($user))
            ->message('Profile berhasil diupdate');
    }

    /**
     * Api untuk update password user yang sedang login.
     * Put /api/profile/update-password
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $validated = $request->validated();

        $user = $request->user();

        $user->update([
            'password' => $validated['password'],
        ]);

        return (new UserResource($user))
            ->message('Password berhasil diupdate');
    }

    /**
     * Api untuk delete akun user yang sedang login.
     * Delete /api/profile/delete
     */
    public function delete(Request $request)
    {
        $user = $request->user();

        $user->update([
            'status' => 'Tidak Aktif',
        ]);

        $user->delete();

        return (new ApiResource(null))
            ->message('Akun berhasil dihapus');
    }
}
