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
            'nama' => 'Bila',
            'valuasi_transaksi' => 0,
        ]);

        PenanggungJawab::create([
            'nama' => 'Fauzan',
            'valuasi_transaksi' => 0,
        ]);

        PenanggungJawab::create([
            'nama' => 'Badi',
            'valuasi_transaksi' => 0,
        ]);

        PenanggungJawab::create([
            'nama' => 'Andri',
            'valuasi_transaksi' => 0,
        ]);
    }
}
