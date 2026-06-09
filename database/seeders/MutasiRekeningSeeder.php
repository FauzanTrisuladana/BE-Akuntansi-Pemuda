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

        if ($kasPemuda && $kasAn) {
            MutasiRekening::create([
                'akun_debit_id' => $kasPemuda->id,
                'akun_kredit_id' => $kasAn->id,
                'date' => now()->toDateString(),
                'jumlah' => 100000,
                'keterangan' => 'Mutasi dari Kas Pemuda ke Kas AN',
            ]);
        }
    }
}
