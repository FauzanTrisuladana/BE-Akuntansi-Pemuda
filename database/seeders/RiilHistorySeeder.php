<?php

namespace Database\Seeders;

use App\Models\Akun;
use App\Models\RiilHistory;
use Illuminate\Database\Seeder;

class RiilHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $akuns = Akun::all();

        foreach ($akuns as $akun) {
            // Verified true
            RiilHistory::create([
                'akun_id' => $akun->id,
                'date' => now()->toDateString(),
                'verified' => true,
                'riil' => 1000000,
            ]);

            // Verified false
            RiilHistory::create([
                'akun_id' => $akun->id,
                'date' => now()->subDay()->toDateString(),
                'verified' => false,
                'riil' => 500000,
            ]);
        }
    }
}
