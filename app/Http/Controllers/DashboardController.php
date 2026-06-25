<?php

namespace App\Http\Controllers;

use App\Http\Requests\Dashboard\IndexDashboardRequest;
use App\Http\Resources\DashboardResource;
use App\Models\Akun;
use App\Models\PosisiKeuangan;
use App\Models\RiilHistory;
use App\Models\Transaksi;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    public function index(IndexDashboardRequest $request): DashboardResource
    {
        $validated = $request->validated();
        $tanggal1Bulanini = now()->startOfMonth()->format('Y-m-d');
        $tanggalHariini = now()->format('Y-m-d');
        $tanggal1BulanLalu = now()->subMonth()->startOfMonth()->format('Y-m-d');
        $tanggalAkhirBulanLalu = now()->subMonth()->endOfMonth()->format('Y-m-d');

        $bulanini = Transaksi::laporanFilter(
            tanggal_mulai: $tanggal1Bulanini,
            tanggal_selesai: $tanggalHariini,
            jenis_transaksi: null,
            kas: $validated['kas'] ?? null,
            akun: null,
        )
            ->selectRaw('
                SUM(CASE WHEN jenis_transaksi = "pemasukan" THEN jumlah ELSE 0 END) as pemasukan,
                SUM(CASE WHEN jenis_transaksi = "pengeluaran" THEN jumlah ELSE 0 END) as pengeluaran
            ')
            ->first();

        $bulanlalu = Transaksi::laporanFilter(
            tanggal_mulai: $tanggal1BulanLalu,
            tanggal_selesai: $tanggalAkhirBulanLalu,
            jenis_transaksi: null,
            kas: $validated['kas'] ?? null,
            akun: null,
        )
            ->selectRaw('
                SUM(CASE WHEN jenis_transaksi = "pemasukan" THEN jumlah ELSE 0 END) as pemasukan,
                SUM(CASE WHEN jenis_transaksi = "pengeluaran" THEN jumlah ELSE 0 END) as pengeluaran
            ')
            ->first();

        $posisiKeuangan = PosisiKeuangan::laporanFilter(
            tanggal_mulai: $tanggal1Bulanini,
            tanggal_selesai: $tanggalHariini,
            kas: $validated['kas'] ?? null,
            akun: null,
        );

        $totalSaldoAwal = $posisiKeuangan->sum('saldo_awal') ?? 0;
        $kasSekarang = $totalSaldoAwal + ($bulanini->pemasukan ?? 0) - ($bulanini->pengeluaran ?? 0);

        $bulanini->totalSaldo = $kasSekarang;

        $pemasukanChange = $bulanlalu->pemasukan ? round((($bulanini->pemasukan - $bulanlalu->pemasukan) / $bulanlalu->pemasukan) * 100, 2) : 0;
        $pengeluaranChange = $bulanlalu->pengeluaran ? round((($bulanini->pengeluaran - $bulanlalu->pengeluaran) / $bulanlalu->pengeluaran) * 100, 2) : 0;

        $summary = [
            'pemasukan' => [
                'total' => $bulanini->pemasukan ?? 0,
                'change' => $pemasukanChange,
            ],
            'pengeluaran' => [
                'total' => $bulanini->pengeluaran ?? 0,
                'change' => $pengeluaranChange,
            ],
            'totalSaldo' => [
                'total' => $bulanini->totalSaldo ?? 0,
            ],
        ];

        // Generate saldo daily - per tanggal dari tanggal 1 sampai hari ini
        $saldoDaily = collect($this->getSaldoDaily($tanggal1Bulanini, $tanggalHariini, $totalSaldoAwal, $validated['kas'] ?? null));

        // Generate saldo per akun - per tanggal dari tanggal 1 sampai hari ini
        $saldoPerAkun = collect($this->getSaldoPerAkun($tanggal1Bulanini, $tanggalHariini, $validated['kas'] ?? null));

        // Generate rekonsiliasi - per tanggal dari tanggal 1 sampai hari ini
        $rekonsiliasi = collect($this->getRekonsiliasi($tanggal1Bulanini, $tanggalHariini, $validated['kas'] ?? null));

        return DashboardResource::make((object) [
            'saldo_awal' => $totalSaldoAwal,
            'saldo_daily' => $saldoDaily,
            'saldo_per_akun' => $saldoPerAkun,
            'rekonsiliasi' => $rekonsiliasi,
        ])
            ->message('Data untuk dashboard berhasil diambil')
            ->additional(['summary' => $summary]);
    }

    /**
     * Get saldo daily per tanggal
     *
     * @return array<int, \stdClass>
     */
    private function getSaldoDaily(string $tanggalMulai, string $tanggalSelesai, float $saldoAwal, ?string $kas = null): array
    {
        $period = CarbonPeriod::create($tanggalMulai, '1 day', $tanggalSelesai);
        $saldoDaily = [];
        $runningSaldo = $saldoAwal;

        foreach ($period as $date) {
            $tanggal = $date->format('Y-m-d');

            // Hitung pemasukan dan pengeluaran per tanggal
            $query = Transaksi::query();

            if ($kas) {
                $query->whereHas('akun', function ($q) use ($kas) {
                    $q->where('kas', $kas);
                });
            }

            $harian = $query->where('date', $tanggal)
                ->selectRaw('
                    SUM(CASE WHEN jenis_transaksi = "pemasukan" THEN jumlah ELSE 0 END) as pemasukan,
                    SUM(CASE WHEN jenis_transaksi = "pengeluaran" THEN jumlah ELSE 0 END) as pengeluaran
                ')
                ->first();

            $pemasukan = $harian->pemasukan ?? 0;
            $pengeluaran = $harian->pengeluaran ?? 0;

            $runningSaldo += $pemasukan - $pengeluaran;

            $saldoDaily[] = (object) [
                'tanggal' => $tanggal,
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran,
                'saldo' => $runningSaldo,
            ];
        }

        return $saldoDaily;
    }

    /**
     * Get saldo per akun per tanggal
     *
     * @return array<int, \stdClass>
     */
    private function getSaldoPerAkun(string $tanggalMulai, string $tanggalSelesai, ?string $kas = null): array
    {
        $period = CarbonPeriod::create($tanggalMulai, '1 day', $tanggalSelesai);
        $saldoPerAkun = [];

        // Ambil semua akun yang relevan
        $akunQuery = Akun::query();
        if ($kas) {
            $akunQuery->where('kas', $kas);
        }
        $akuns = $akunQuery->get();

        foreach ($period as $date) {
            $tanggal = $date->format('Y-m-d');
            $akunSaldoList = [];

            foreach ($akuns as $akun) {
                // Cari riil history terdekat pada atau sebelum tanggal ini
                $riilHistory = RiilHistory::where('akun_id', $akun->id)
                    ->where('date', '<=', $tanggal)
                    ->latest('date')
                    ->first();

                $akunSaldoList[] = (object) [
                    'nama_akun' => $akun->nama_akun,
                    'saldo' => $riilHistory ? $riilHistory->riil : 0,
                ];
            }

            $saldoPerAkun[] = (object) [
                'tanggal' => $tanggal,
                'akun' => collect($akunSaldoList),
            ];
        }

        return $saldoPerAkun;
    }

    /**
     * Get rekonsiliasi per tanggal
     *
     * @return array<int, \stdClass>
     */
    private function getRekonsiliasi(string $tanggalMulai, string $tanggalSelesai, ?string $kas = null): array
    {
        $period = CarbonPeriod::create($tanggalMulai, '1 day', $tanggalSelesai);
        $rekonsiliasi = [];

        // Ambil semua akun yang relevan
        $akunQuery = Akun::query();
        if ($kas) {
            $akunQuery->where('kas', $kas);
        }
        $akuns = $akunQuery->get();

        foreach ($period as $date) {
            $tanggal = $date->format('Y-m-d');

            // Hitung total sistem (semua riil, terverifikasi atau belum)
            $sistem = 0;
            $riil = 0;

            foreach ($akuns as $akun) {
                // Sistem: saldo terakhir (verified atau belum)
                $riilSistem = RiilHistory::where('akun_id', $akun->id)
                    ->where('date', '<=', $tanggal)
                    ->latest('date')
                    ->value('riil') ?? 0;

                $sistem += $riilSistem;

                // Riil: hanya yang terverifikasi
                $riilTerverifikasi = RiilHistory::where('akun_id', $akun->id)
                    ->where('date', '<=', $tanggal)
                    ->where('verified', true)
                    ->latest('date')
                    ->value('riil') ?? 0;

                $riil += $riilTerverifikasi;
            }

            // Cek apakah semua akun sudah terverifikasi pada tanggal ini
            $allVerified = true;
            foreach ($akuns as $akun) {
                $hasVerified = RiilHistory::where('akun_id', $akun->id)
                    ->where('date', $tanggal)
                    ->where('verified', true)
                    ->exists();

                if (! $hasVerified) {
                    $allVerified = false;
                    break;
                }
            }

            $rekonsiliasi[] = (object) [
                'tanggal' => $tanggal,
                'sistem' => $sistem,
                'riil' => $riil,
                'verified' => $allVerified ? $sistem : null,
            ];
        }

        return $rekonsiliasi;
    }
}
