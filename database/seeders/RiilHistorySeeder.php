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
        $akun1 = Akun::where('nama_akun', 'Cash Bila')->first();
        $akun2 = Akun::where('nama_akun', 'Cash')->first();
        $akun3 = Akun::where('nama_akun', 'Rekening')->first();

        if (! $akun1 || ! $akun2 || ! $akun3) {
            return;
        }

        $riil1 = RiilHistory::create([
            'akun_id' => $akun1->id,
            'date' => '2026-05-05',
            'verified' => true,
            'riil' => 2123000.00,
        ]);

        $riil2 = RiilHistory::create([
            'akun_id' => $akun1->id,
            'date' => '2026-05-06',
            'verified' => true,
            'riil' => 2313000.00,
        ]);

        $riil3 = RiilHistory::create([
            'akun_id' => $akun1->id,
            'date' => '2026-05-23',
            'verified' => true,
            'riil' => 0.00,
        ]);

        $riil4 = RiilHistory::create([
            'akun_id' => $akun2->id,
            'date' => '2026-05-23',
            'verified' => true,
            'riil' => 2284000.00,
        ]);

        $riil5 = RiilHistory::create([
            'akun_id' => $akun2->id,
            'date' => '2026-05-28',
            'verified' => true,
            'riil' => 2239000.00,
        ]);

        $riil6 = RiilHistory::create([
            'akun_id' => $akun2->id,
            'date' => '2026-05-30',
            'verified' => true,
            'riil' => 2164000.00,
        ]);

        $riil7 = RiilHistory::create([
            'akun_id' => $akun3->id,
            'date' => '2026-06-05',
            'verified' => true,
            'riil' => 1742500.00,
        ]);

        $riil8 = RiilHistory::create([
            'akun_id' => $akun2->id,
            'date' => '2026-06-05',
            'verified' => true,
            'riil' => 159000.00,
        ]);

        $riil9 = RiilHistory::create([
            'akun_id' => $akun3->id,
            'date' => '2026-06-06',
            'verified' => true,
            'riil' => 1662500.00,
        ]);

        $riil10 = RiilHistory::create([
            'akun_id' => $akun3->id,
            'date' => '2026-06-14',
            'verified' => true,
            'riil' => 1628500.00,
        ]);

        $riil11 = RiilHistory::create([
            'akun_id' => $akun2->id,
            'date' => '2026-06-15',
            'verified' => true,
            'riil' => 54000.00,
        ]);

        $riil12 = RiilHistory::create([
            'akun_id' => $akun3->id,
            'date' => '2026-06-20',
            'verified' => true,
            'riil' => 1313500.00,
        ]);

        $riil13 = RiilHistory::create([
            'akun_id' => $akun3->id,
            'date' => '2026-06-21',
            'verified' => true,
            'riil' => 1249500.00,
        ]);

        // Update riil_terakhir pada akun sesuai dengan data SQL
        $akun1->update(['riil_terakhir' => $riil3->id]);
        $akun2->update(['riil_terakhir' => $riil11->id]);
        $akun3->update(['riil_terakhir' => $riil13->id]);
    }
}
