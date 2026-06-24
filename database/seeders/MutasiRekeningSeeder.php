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
        $kasPemuda = Akun::where('nama_akun', 'Cash Bila')->first();
        $kasFauzan = Akun::where('nama_akun', 'Cash')->first();
        $rekening = Akun::where('nama_akun', 'Rekening')->first();

        // Pastikan semua akun yang diperlukan ada
        if (! $kasPemuda || ! $kasFauzan || ! $rekening) {
            return;
        }

        // Mutasi dari Cash Fauzan ke Cash Bila (berdasarkan data SQL)
        MutasiRekening::create([
            'id' => 1,
            'akun_debit_id' => $kasFauzan->id,
            'akun_kredit_id' => $kasPemuda->id,
            'date' => '2026-05-23',
            'jumlah' => 2284000,
            'keterangan' => 'Pindahkan uang dari bila ke bendahara baru (fauzan)',
        ]);

        // Mutasi dari Rekening ke Cash Fauzan (berdasarkan data SQL)
        MutasiRekening::create([
            'id' => 2,
            'akun_debit_id' => $rekening->id,
            'akun_kredit_id' => $kasFauzan->id,
            'date' => '2026-06-05',
            'jumlah' => 2005000,
            'keterangan' => 'Masukkan ke rekening',
        ]);
    }
}
