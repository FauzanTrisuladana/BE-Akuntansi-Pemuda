<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\User\{
    IndexUserRequest,
    StoreUserRequest,
    UpdateUserRequest
};
use App\Http\Resources\UserResource;
use App\Http\Resources\ApiResource;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * Get /api/user
     */
    public function index(IndexUserRequest $request)
    {
        $validated = $request->validated();

        $users = User::filter(
            search: $validated['search'] ?? null,
            role: $validated['role'] ?? null,
            status: $validated['status'] ?? null,
        )
        ->orderBy('name')
        ->paginate($validated['per_page']);

        return UserResource::collection($users)
            ->message('Data user berhasil diambil');
    }

    /**
     * Store a newly created resource in storage.
     * Post /api/user
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['nama'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'status' => $validated['status'],
            'activated_at' => $validated['activated_at'],
        ]);

        return (new UserResource($user))
            ->message('User berhasil dibuat');
    }

    /**
     * Update the specified resource in storage.
     * Put /api/user/{id}
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $validated = $request->validated();

        $user = User::findOrFail($id);

        $user->update($validated);

        return (new UserResource($user))
            ->message('User berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     * Delete /api/user/{id}
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return (new ApiResource(null))
            ->message('User berhasil dihapus');
    }

    /**
     * Toggle the status of the specified resource in storage.
     * Put /api/user/{id}/toggle-status
     */
    public function toggleStatus(string $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'status' => $user->status === 'Aktif' ? 'Tidak Aktif' : 'Aktif'
        ]);

        return (new UserResource($user))
            ->message("Status user berhasil diubah menjadi {$user->status}");
    }
}
