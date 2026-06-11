<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class AkunResource extends ApiResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->whenHas('id'),
            'nama_akun' => $this->whenHas('nama_akun'),
            'kas' => $this->whenHas('kas'),
            'jumlah' => $this->whenHas('jumlah'),
            'keterangan' => $this->whenHas('keterangan'),

            'riil_terakhir' => new RiilHistoryResource($this->whenLoaded('riilHistory')),
            'riil_history' => RiilHistoryResource::collection($this->whenLoaded('riilHistories')),
            'mutasi_debit' => MutasiRekeningResource::collection($this->whenLoaded('mutasiDebit')),
            'mutasi_kredit' => MutasiRekeningResource::collection($this->whenLoaded('mutasiKredit')),
            'transaksi' => TransaksiResource::collection($this->whenLoaded('transaksi')),
        ];
    }
}
