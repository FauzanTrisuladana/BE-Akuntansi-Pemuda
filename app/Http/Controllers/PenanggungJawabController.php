<?php

namespace App\Http\Controllers;

use App\Http\Requests\PJ\IndexPJRequest;
use App\Http\Requests\PJ\StorePJRequest;
use App\Http\Requests\PJ\UpdatePJRequest;
use App\Http\Resources\ApiResource;
use App\Http\Resources\ApiResourceCollection;
use App\Http\Resources\PenanggungJawabResource;
use App\Models\PenanggungJawab;

class PenanggungJawabController extends Controller
{
    /**
     * Display a listing of the resource.
     * Get /api/penanggung-jawab
     */
    public function index(IndexPJRequest $request): ApiResourceCollection
    {
        $validated = $request->validated();

        $query = PenanggungJawab::filter(
            search: $validated['search'] ?? null,
        )
            ->orderBy('nama')
            ->paginate($validated['per_page']);

        return PenanggungJawabResource::collection($query)
            ->message('Data penanggung jawab berhasil diambil');
    }

    public function show(string $id): PenanggungJawabResource
    {
        $pj = PenanggungJawab::with(['transaksi'])->findOrFail($id);

        return (new PenanggungJawabResource($pj))
            ->message('Data transaksi penanggung jawab berhasil diambil');
    }

    /**
     * Store a newly created resource in storage.
     * Post /api/penanggung-jawab
     */
    public function store(StorePJRequest $request): PenanggungJawabResource
    {
        $validated = $request->validated();

        $pj = PenanggungJawab::create([
            'nama' => $validated['nama'],
        ]);

        return (new PenanggungJawabResource($pj))
            ->message('Penanggung jawab berhasil dibuat');
    }

    /**
     * Update the specified resource in storage.
     * Put /api/penanggung-jawab/{id}
     */
    public function update(UpdatePJRequest $request, string $id): PenanggungJawabResource
    {
        $validated = $request->validated();

        $pj = PenanggungJawab::findOrFail($id);

        $pj->update([
            'nama' => $validated['nama'],
        ]);

        return (new PenanggungJawabResource($pj))
            ->message('Penanggung jawab berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     * Delete /api/penanggung-jawab/{id}
     */
    public function destroy(string $id): ApiResource
    {
        $pj = PenanggungJawab::findOrFail($id);

        $pj->delete();

        return (new ApiResource(null))
            ->message('Penanggung jawab berhasil dihapus');
    }
}
