<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class SaldoResource extends ApiResource
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
            'pemasukan' => $this->pemasukan ?? 0,
            'pengeluaran' => $this->pengeluaran ?? 0,
            'saldo' => $this->saldo ?? 0,
        ];
    }
}
