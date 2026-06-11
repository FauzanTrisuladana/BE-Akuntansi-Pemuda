<?php

namespace App\Http\Controllers;

use App\Http\Requests\Akun\IndexAkunRequest;
use App\Http\Requests\Akun\StoreAkunRequest;
use App\Http\Requests\Akun\UpdateAkunRequest;
use App\Http\Resources\AkunResource;
use App\Http\Resources\ApiResource;
use App\Http\Resources\ApiResourceCollection;
use App\Models\Akun;

class AkunController extends Controller
{
    /**
     * Display a listing of the resource.
     * Get /api/akun
     */
    public function index(IndexAkunRequest $request): ApiResourceCollection
    {
        $validated = $request->validated();

        $akun = Akun::filter(
            search: $validated['search'] ?? null,
            kas: $validated['kas'] ?? null,
        )
            ->orderBy('nama_akun')
            ->paginate($validated['per_page']);

        return AkunResource::collection($akun)
            ->message('Data akun berhasil diambil');
    }

    /**
     * Store a newly created resource in storage.
     * Post /api/akun
     */
    public function store(StoreAkunRequest $request): AkunResource
    {
        $validated = $request->validated();

        $akun = Akun::create([
            'nama_akun' => $validated['nama_akun'],
            'kas' => $validated['kas'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return (new AkunResource($akun))
            ->message('Akun berhasil dibuat');
    }

    /**
     * Display the specified resource.
     * Get /api/akun/{id}
     */
    public function show(string $id): AkunResource
    {
        $akun = Akun::with(['transaksi', 'mutasiDebit', 'mutasiKredit'])->findOrFail($id);

        return (new AkunResource($akun))
            ->message('Data akun berhasil diambil');
    }

    /**
     * Update the specified resource in storage.
     * Put /api/akun/{id}
     */
    public function update(UpdateAkunRequest $request, string $id): AkunResource
    {
        $validated = $request->validated();

        $akun = Akun::findOrFail($id);

        $akun->update($validated);

        return (new AkunResource($akun))
            ->message('Akun berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     * Delete /api/akun/{id}
     */
    public function destroy(string $id): ApiResource
    {
        $akun = Akun::findOrFail($id);

        $akun->delete();

        return (new ApiResource(null))
            ->message('Akun berhasil dihapus');
    }
}
