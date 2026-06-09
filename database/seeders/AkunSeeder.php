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
            'nama_akun' => 'Kas 17 AN',
            'kas' => '17 an',
        ]);

        Akun::create([
            'nama_akun' => 'Kas Pemuda 2',
            'kas' => 'kas pemuda',
        ]);

        Akun::create([
            'nama_akun' => 'Kas 17 AN 2',
            'kas' => '17 an',
        ]);
    }
}
