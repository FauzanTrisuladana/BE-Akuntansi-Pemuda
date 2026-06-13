<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class MutasiRekeningResource extends ApiResource
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
            'akun_debit_id' => $this->whenHas('akun_debit_id'),
            'akun_kredit_id' => $this->whenHas('akun_kredit_id'),
            'date' => $this->date ? $this->date->format('Y-m-d') : null,
            'jumlah' => $this->whenHas('jumlah'),
            'keterangan' => $this->whenHas('keterangan'),

            'akun_debit' => new AkunResource($this->whenLoaded('akunDebit')),
            'akun_kredit' => new AkunResource($this->whenLoaded('akunKredit')),
        ];
    }
}
