<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosisiKeuangan extends Model
{
    protected $table = null;

    public $timestamps = false;

    protected $primaryKey = null;

    public $incrementing = false;

    protected $fillable = [
        'nama_akun',
        'saldo_awal',
        'pemasukan',
        'pengeluaran',
        'saldo_akhir',
        'riil',
        'selisih',
        'keterangan',
    ];

    public static function laporanFilter(string $tanggal_mulai, string $tanggal_selesai, string $kas, ?int $akun = null)
    {
        $query = Akun::query();

        $query->where('kas', $kas);

        if ($akun) {
            $query->where('id', $akun);
        }

        $akuns = $query->get();

        $results = collect();

        foreach ($akuns as $akunData) {
            $saldoAwal = 0;
            $pemasukan = 0;
            $pengeluaran = 0;

            // Hitung saldo awal (sebelum tanggal_mulai)
            $totalPemasukanAwal = Transaksi::where('akun_id', $akunData->id)
                ->where('date', '<', $tanggal_mulai)
                ->where('jenis_transaksi', 'pemasukan')
                ->sum('jumlah');

            $totalPengeluaranAwal = Transaksi::where('akun_id', $akunData->id)
                ->where('date', '<', $tanggal_mulai)
                ->where('jenis_transaksi', 'pengeluaran')
                ->sum('jumlah');

            // Hitung mutasi untuk saldo awal
            $mutasiPemasukanAwal = (MutasiRekening::where('akun_debit_id', $akunData->id)
                ->where('date', '<', $tanggal_mulai)
                ->sum('jumlah')) ?: 0;

            $mutasiPengeluaranAwal = (MutasiRekening::where('akun_kredit_id', $akunData->id)
                ->where('date', '<', $tanggal_mulai)
                ->sum('jumlah')) ?: 0;

            $saldoAwal = ($totalPemasukanAwal ?: 0) - ($totalPengeluaranAwal ?: 0) + ($mutasiPemasukanAwal ?: 0) - ($mutasiPengeluaranAwal ?: 0);

            // Hitung pemasukan dan pengeluaran dalam periode
            $pemasukan = (Transaksi::where('akun_id', $akunData->id)
                ->where('jenis_transaksi', 'pemasukan')
                ->where('date', '>=', $tanggal_mulai)
                ->where('date', '<=', $tanggal_selesai)
                ->sum('jumlah')) ?: 0;

            $pengeluaran = (Transaksi::where('akun_id', $akunData->id)
                ->where('jenis_transaksi', 'pengeluaran')
                ->where('date', '>=', $tanggal_mulai)
                ->where('date', '<=', $tanggal_selesai)
                ->sum('jumlah')) ?: 0;

            // Hitung mutasi rekening - akun debit = pemasukan, akun kredit = pengeluaran
            $mutasiPemasukan = (MutasiRekening::where('akun_debit_id', $akunData->id)
                ->where('date', '>=', $tanggal_mulai)
                ->where('date', '<=', $tanggal_selesai)
                ->sum('jumlah')) ?: 0;

            $mutasiPengeluaran = (MutasiRekening::where('akun_kredit_id', $akunData->id)
                ->where('date', '>=', $tanggal_mulai)
                ->where('date', '<=', $tanggal_selesai)
                ->sum('jumlah')) ?: 0;

            $pemasukan += $mutasiPemasukan;
            $pengeluaran += $mutasiPengeluaran;

            $saldoAkhir = $saldoAwal + $pemasukan - $pengeluaran;

            // Ambil riil terakhir sebelum tanggal_selesai
            $riil = RiilHistory::where('akun_id', $akunData->id)
                ->where('verified', true)
                ->where('date', '<=', $tanggal_selesai)
                ->latest('date')
                ->value('riil') ?? 0;

            $selisih = $riil - $saldoAkhir;

            // Tentukan keterangan
            if ($selisih == 0) {
                $keterangan = 'Seimbang';
            } elseif ($selisih > 0) {
                $keterangan = 'Uang Lebih';
            } else {
                $keterangan = 'Uang Kurang';
            }

            $results->push(new self([
                'nama_akun' => $akunData->nama_akun,
                'saldo_awal' => $saldoAwal,
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran,
                'saldo_akhir' => $saldoAkhir,
                'riil' => $riil,
                'selisih' => abs($selisih),
                'keterangan' => $keterangan,
            ]));
        }

        return $results;
    }
}
