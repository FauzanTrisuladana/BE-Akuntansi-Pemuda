<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class PosisiKeuanganResource extends ApiResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'nama_akun' => $this->nama_akun,
            'saldo_awal' => $this->saldo_awal,
            'pemasukan' => $this->pemasukan,
            'pengeluaran' => $this->pengeluaran,
            'total' => $this->saldo_akhir,
            'riil' => $this->riil,
            'selisih' => $this->selisih,
            'keterangan' => $this->keterangan,
        ];
    }
}
