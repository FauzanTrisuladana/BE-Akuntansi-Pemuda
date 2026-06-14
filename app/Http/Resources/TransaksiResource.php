<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class TransaksiResource extends ApiResource
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
            'akun_id' => $this->whenHas('akun_id'),
            'penginput_id' => $this->whenHas('penginput_id'),
            'penanggung_jawab_id' => $this->whenHas('penanggung_jawab_id'),
            'deskripsi' => $this->whenHas('deskripsi'),
            'date' => $this->date ? $this->date->format('Y-m-d') : null,
            'jenis_transaksi' => $this->whenHas('jenis_transaksi'),
            'jumlah' => $this->whenHas('jumlah'),
            'bukti' => $this->whenHas('bukti'),
            'bukti_public_id' => $this->whenHas('bukti_public_id'),

            'akun' => new AkunResource($this->whenLoaded('akun')),
            'penginput' => new UserResource($this->whenLoaded('penginput')),
            'penanggung_jawab' => new PenanggungJawabResource($this->whenLoaded('penanggungJawab')),
        ];
    }
}
