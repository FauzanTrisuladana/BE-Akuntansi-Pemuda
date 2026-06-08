<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PenanggungJawab;

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
