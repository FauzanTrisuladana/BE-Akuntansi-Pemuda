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
        ]);

        PenanggungJawab::create([
            'nama' => 'Fauzan',
        ]);

        PenanggungJawab::create([
            'nama' => 'Badi',
        ]);

        PenanggungJawab::create([
            'nama' => 'Andri',
        ]);
    }
}
