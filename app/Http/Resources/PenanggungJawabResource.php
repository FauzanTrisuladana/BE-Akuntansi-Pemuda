<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class PenanggungJawabResource extends ApiResource
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
            'nama' => $this->whenHas('nama'),
            'valuasi_transaksi' => $this->whenHas('valuasi_transaksi'),
        ];
    }
}
