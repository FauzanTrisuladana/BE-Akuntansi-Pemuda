<?php

namespace Database\Seeders;

use App\Models\Akun;
use Illuminate\Database\Seeder;

class AkunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Akun::create([
            'riil_terakhir' => null,
            'nama_akun' => 'Cash Bila',
            'kas' => 'kas pemuda',
            'jumlah' => 0,
            'keterangan' => 'Kas yang dipegang Bila',
        ]);

        Akun::create([
            'riil_terakhir' => null,
            'nama_akun' => 'Cash',
            'kas' => 'kas pemuda',
            'jumlah' => 54000,
            'keterangan' => 'Kas yang dipegang Fauzan',
        ]);

        Akun::create([
            'riil_terakhir' => null,
            'nama_akun' => 'Rekening',
            'kas' => 'kas pemuda',
            'jumlah' => 1249500,
            'keterangan' => 'Uang yang ada di rekening',
        ]);
    }
}
