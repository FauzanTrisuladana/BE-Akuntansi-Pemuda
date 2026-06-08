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
            'riil_terakhir' => $this->whenHas('riil_terakhir'),
            'nama_akun' => $this->whenHas('nama_akun'),
            'kas' => $this->whenHas('kas'),

            'riil_history' => RiilHistoryResource::collection($this->whenLoaded('riilHistory')),
        ];
    }
}
