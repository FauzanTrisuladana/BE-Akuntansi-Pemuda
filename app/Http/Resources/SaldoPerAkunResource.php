<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class SaldoPerAkunResource extends ApiResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'tanggal' => isset($this->tanggal)
                ? (is_string($this->tanggal) ? $this->tanggal : $this->tanggal->format('Y-m-d'))
                : null,
            'akun' => SaldoAkunResource::collection($this->akun ?? []),
        ];
    }
}
