<?php

namespace Database\Seeders;

use App\Models\PenanggungJawab;
use Illuminate\Database\Seeder;

class PenanggungJawabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PenanggungJawab::create([
            'nama' => 'Bendahara Pemuda',
            'valuasi_transaksi' => 1000000,
        ]);

        PenanggungJawab::create([
            'nama' => 'Ketua Pemuda',
            'valuasi_transaksi' => 500000,
        ]);
    }
}
