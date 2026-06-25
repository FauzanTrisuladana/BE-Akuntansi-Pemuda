<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

/**
 * @property float $saldo_awal
 * @property mixed $saldo_daily
 * @property mixed $saldo_per_akun
 * @property mixed $rekonsiliasi
 */
class DashboardResource extends ApiResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'saldo_awal' => $this->saldo_awal,
            'saldo_daily' => SaldoResource::collection($this->saldo_daily ?? []),
            'saldo_per_akun' => SaldoPerAkunResource::collection($this->saldo_per_akun ?? []),
            'rekonsiliasi' => RekonsiliasiResource::collection($this->rekonsiliasi ?? []),
        ];
    }
}
