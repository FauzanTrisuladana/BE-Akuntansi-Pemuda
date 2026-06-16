<?php

namespace App\Http\Controllers;

use App\Http\Requests\Laporan\IndexLaporanRequest;
use App\Http\Resources\LaporanResource;
use App\Models\MutasiRekening;
use App\Models\PosisiKeuangan;
use App\Models\Transaksi;

class LaporanController extends Controller
{
    public function index(IndexLaporanRequest $request): LaporanResource
    {
        $validated = $request->validated();

        $transaksi = Transaksi::with(['akun', 'penanggungJawab', 'penginput'])
            ->laporanFilter(
                tanggal_mulai: $validated['tanggal_mulai'] ?? null,
                tanggal_selesai: $validated['tanggal_selesai'] ?? null,
                jenis_transaksi: $validated['jenis_transaksi'] ?? null,
                kas: $validated['kas'] ?? null,
                akun: $validated['akun'] ?? null,
            )
            ->orderBy('date', 'desc')
            ->get();

        $mutasi = MutasiRekening::with(['akunDebit', 'akunKredit'])
            ->laporanFilter(
                tanggal_mulai: $validated['tanggal_mulai'] ?? null,
                tanggal_selesai: $validated['tanggal_selesai'] ?? null,
                kas: $validated['kas'] ?? null,
                akun: $validated['akun'] ?? null,
            )
            ->orderBy('date', 'desc')
            ->get();

        $posisiKeuangan = PosisiKeuangan::laporanFilter(
            tanggal_mulai: $validated['tanggal_mulai'] ?? null,
            tanggal_selesai: $validated['tanggal_selesai'] ?? null,
            kas: $validated['kas'] ?? null,
            akun: $validated['akun'] ?? null,
        );

        $summary = Transaksi::laporanFilter(
            tanggal_mulai: $validated['tanggal_mulai'] ?? null,
            tanggal_selesai: $validated['tanggal_selesai'] ?? null,
            jenis_transaksi: $validated['jenis_transaksi'] ?? null,
            kas: $validated['kas'] ?? null,
            akun: $validated['akun'] ?? null,
        )
            ->selectRaw('
                SUM(CASE WHEN jenis_transaksi = "pemasukan" THEN jumlah ELSE 0 END) as total_pemasukan,
                SUM(CASE WHEN jenis_transaksi = "pengeluaran" THEN jumlah ELSE 0 END) as total_pengeluaran
            ')
            ->first();

        $totalSaldoAwal = $posisiKeuangan->sum('saldo_awal') ?? 0;
        $kasSekarang = $totalSaldoAwal + ($summary->total_pemasukan ?? 0) - ($summary->total_pengeluaran ?? 0);

        $summary->saldo_awal = $totalSaldoAwal;
        $summary->kas_sekarang = $kasSekarang;

        return LaporanResource::make((object) [
            'transaksi' => $transaksi,
            'mutasi' => $mutasi,
            'posisiKeuangan' => $posisiKeuangan,
        ])
            ->message('Data laporan berhasil diambil')
            ->additional(['summary' => $summary]);
    }
}
