<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class SaldoAkunResource extends ApiResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'nama_akun' => $this->nama_akun ?? '',
            'saldo' => $this->saldo ?? 0,
        ];
    }
}
