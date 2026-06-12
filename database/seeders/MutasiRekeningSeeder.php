<?php

namespace Database\Seeders;

use App\Models\Akun;
use App\Models\MutasiRekening;
use Illuminate\Database\Seeder;

class MutasiRekeningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kasPemuda = Akun::where('nama_akun', 'Kas Pemuda')->first();
        $kasAn = Akun::where('nama_akun', 'Kas AN')->first();
        $bankBri = Akun::where('nama_akun', 'Bank BRI')->first();
        $danaBos = Akun::where('nama_akun', 'Dana BOS')->first();
        $bankBca = Akun::where('nama_akun', 'Bank BCA')->first();

        // Pastikan semua akun yang diperlukan ada
        if (! $kasPemuda || ! $kasAn || ! $bankBri || ! $danaBos || ! $bankBca) {
            return;
        }

        // Mutasi dari Kas Pemuda ke Kas AN
        MutasiRekening::create([
            'akun_debit_id' => $kasPemuda->id,
            'akun_kredit_id' => $kasAn->id,
            'date' => now()->subDays(10)->toDateString(),
            'jumlah' => 100000,
            'keterangan' => 'Mutasi dari Kas Pemuda ke Kas AN',
        ]);

        // Mutasi dari Kas AN ke Kas Pemuda (kebalikan)
        MutasiRekening::create([
            'akun_debit_id' => $kasAn->id,
            'akun_kredit_id' => $kasPemuda->id,
            'date' => now()->subDays(8)->toDateString(),
            'jumlah' => 50000,
            'keterangan' => 'Pengembalian dana dari Kas AN ke Kas Pemuda',
        ]);

        // Mutasi dari Kas Pemuda ke Bank BRI
        MutasiRekening::create([
            'akun_debit_id' => $kasPemuda->id,
            'akun_kredit_id' => $bankBri->id,
            'date' => now()->subDays(6)->toDateString(),
            'jumlah' => 250000,
            'keterangan' => 'Setoran ke Bank BRI untuk operasional',
        ]);

        // Mutasi dari Dana BOS ke Kas Pemuda
        MutasiRekening::create([
            'akun_debit_id' => $danaBos->id,
            'akun_kredit_id' => $kasPemuda->id,
            'date' => now()->subDays(4)->toDateString(),
            'jumlah' => 500000,
            'keterangan' => 'Penerimaan Dana BOS untuk kegiatan pemuda',
        ]);

        // Mutasi dari Kas Pemuda ke Dana BOS
        MutasiRekening::create([
            'akun_debit_id' => $kasPemuda->id,
            'akun_kredit_id' => $danaBos->id,
            'date' => now()->subDays(2)->toDateString(),
            'jumlah' => 150000,
            'keterangan' => 'Penggunaan Dana BOS untuk rapat koordinasi',
        ]);

        // Mutasi dari Kas AN ke Bank BCA
        MutasiRekening::create([
            'akun_debit_id' => $kasAn->id,
            'akun_kredit_id' => $bankBca->id,
            'date' => now()->subDays(1)->toDateString(),
            'jumlah' => 75000,
            'keterangan' => 'Transfer ke Bank BCA untuk keperluan administrasi',
        ]);

        // Mutasi antara Bank BRI dan Bank BCA
        MutasiRekening::create([
            'akun_debit_id' => $bankBri->id,
            'akun_kredit_id' => $bankBca->id,
            'date' => now()->toDateString(),
            'jumlah' => 200000,
            'keterangan' => 'Transfer antar bank untuk likuiditas',
        ]);
    }
}
