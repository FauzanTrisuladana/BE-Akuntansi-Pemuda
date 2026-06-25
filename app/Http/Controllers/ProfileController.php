<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdatePhotoRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Resources\ApiResource;
use App\Http\Resources\UserResource;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Api untuk mengambil profile user yang sedang login.
     * Get /api/profile/me
     */
    public function me(Request $request): UserResource
    {
        $user = $request->user();

        return (new UserResource($user))
            ->message('Profile berhasil diambil');
    }

    /**
     * Api untuk update profile user yang sedang login.
     * Put /api/profile/update
     */
    public function update(UpdateProfileRequest $request): UserResource
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
    public function updatePassword(UpdatePasswordRequest $request): UserResource
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
     * Api update photo profile user yang sedang login.
     * Post /api/profile/update-photo
     */
    public function updatePhoto(UpdatePhotoRequest $request): UserResource
    {
        $validated = $request->validated();

        $user = $request->user();

        if ($user->profile_image_public_id) {
            Cloudinary::uploadApi()->destroy($user->profile_image_public_id);
        }

        $upload = Cloudinary::uploadApi()->upload($request->file('profile_image')->getRealPath(), [
            'folder' => 'profile-image',
            'resource_type' => 'auto',
        ]);

        $user->update([
            'profile_image' => $upload['secure_url'],
            'profile_image_public_id' => $upload['public_id'],
        ]);

        return (new UserResource($user))
            ->message('Foto profile berhasil diupdate');
    }

    /**
     * Api untuk delete akun user yang sedang login.
     * Delete /api/profile/delete
     */
    public function delete(Request $request): ApiResource
    {
        $user = $request->user();

        $user->update([
            'status' => 'Tidak Aktif',
        ]);

        $user->delete();

        $request->user()->currentAccessToken()->delete();

        return (new ApiResource(null))
            ->message('Akun berhasil dihapus');
    }
}
