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
            'nama_akun' => 'Kas Pemuda',
            'kas' => 'kas pemuda',
        ]);

        Akun::create([
            'nama_akun' => 'Kas AN',
            'kas' => '17 an',
        ]);

        Akun::create([
            'nama_akun' => 'Bank BRI',
            'kas' => 'kas pemuda',
        ]);

        Akun::create([
            'nama_akun' => 'Dana BOS',
            'kas' => 'kas pemuda',
        ]);

        Akun::create([
            'nama_akun' => 'Bank BCA',
            'kas' => 'kas pemuda',
        ]);
    }
}
