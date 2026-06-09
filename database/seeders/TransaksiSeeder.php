<?php

namespace Database\Seeders;

use App\Models\Akun;
use App\Models\PenanggungJawab;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $akuns = Akun::all();
        $users = User::all();
        $penanggungJawabs = PenanggungJawab::all();

        foreach ($akuns as $akun) {
            foreach ($users as $user) {
                foreach ($penanggungJawabs as $pj) {
                    // Pemasukan
                    Transaksi::create([
                        'akun_id' => $akun->id,
                        'penginput_id' => $user->id,
                        'penanggung_jawab_id' => $pj->id,
                        'deskripsi' => 'Pemasukan Dana '.$akun->nama_akun,
                        'date' => now()->toDateString(),
                        'jenis_transaksi' => 'pemasukan',
                        'jumlah' => 1000000,
                    ]);

                    // Pengeluaran
                    Transaksi::create([
                        'akun_id' => $akun->id,
                        'penginput_id' => $user->id,
                        'penanggung_jawab_id' => $pj->id,
                        'deskripsi' => 'Pengeluaran Dana '.$akun->nama_akun,
                        'date' => now()->subDay()->toDateString(),
                        'jenis_transaksi' => 'pengeluaran',
                        'jumlah' => 500000,
                    ]);
                }
            }
        }
    }
}
