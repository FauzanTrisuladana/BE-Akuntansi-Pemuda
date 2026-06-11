<?php

namespace App\Http\Controllers;

use App\Http\Requests\HistoryRiil\IndexHistoryRiilRequest;
use App\Http\Resources\ApiResourceCollection;
use App\Http\Resources\RiilHistoryResource;
use App\Models\RiilHistory;
use Illuminate\Support\Facades\DB;

class HistoryRiilController extends Controller
{
    /**
     * Display a listing of the resource.
     * Get /api/history-riil
     */
    public function index(IndexHistoryRiilRequest $request): ApiResourceCollection
    {
        $validated = $request->validated();

        $historyRiil = RiilHistory::with(['akun'])->filter(
            search: $validated['search'] ?? null,
            tanggal_mulai: $validated['tanggal_mulai'] ?? null,
            tanggal_selesai: $validated['tanggal_selesai'] ?? null,
            kas: $validated['kas'] ?? null,
        )
            ->orderBy('created_at', 'desc')
            ->paginate($validated['per_page']);

        return RiilHistoryResource::collection($historyRiil)
            ->message('Data history riil berhasil diambil');
    }

    /**
     * Store a newly created resource in storage.
     * Put /api/history-riil/{id}/verify
     */
    public function verify(string $id): RiilHistoryResource
    {
        $historyRiil = DB::transaction(function () use ($id) {
            $historyRiil = RiilHistory::findOrFail($id);

            $historyRiil->verified = true;
            $historyRiil->save();

            RiilHistory::where('akun_id', $historyRiil->akun_id)
                ->where('date', '<', $historyRiil->date)
                ->update(['verified' => true]);

            $akun = $historyRiil->akun;
            if ($akun) {
                $akun->riil_terakhir = (int) $historyRiil->id;
                $akun->save();
            }

            return $historyRiil;
        });

        return (new RiilHistoryResource($historyRiil))
            ->message('History riil berhasil diverifikasi');
    }
}
