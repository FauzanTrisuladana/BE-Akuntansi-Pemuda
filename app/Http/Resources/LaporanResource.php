<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class LaporanResource extends ApiResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'transaksi' => TransaksiResource::collection($this->transaksi),
            'mutasi' => MutasiRekeningResource::collection($this->mutasi),
            'posisi_keuangan' => PosisiKeuanganResource::collection($this->posisiKeuangan),
        ];
    }
}
