<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class RiilHistoryResource extends ApiResource
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
            'date' => $this->whenHas('date'),
            'verified' => $this->whenHas('verified'),
            'riil' => $this->whenHas('riil'),

            'akun' => new AkunResource($this->whenLoaded('akun')),
        ];
    }
}
