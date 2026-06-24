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
        // Transaksi data from SQL file - create with specific IDs to match foreign keys
        $transaksis = [
            ['id' => 1, 'akun_id' => 1, 'penginput_id' => 1, 'penanggung_jawab_id' => 1, 'deskripsi' => 'Saldo Awal', 'date' => '2026-05-05', 'jenis_transaksi' => 'pemasukan', 'jumlah' => 5400000],
            ['id' => 2, 'akun_id' => 1, 'penginput_id' => 1, 'penanggung_jawab_id' => 1, 'deskripsi' => 'Kado Lahiran', 'date' => '2026-05-05', 'jenis_transaksi' => 'pengeluaran', 'jumlah' => 195000, 'bukti' => 'https://res.cloudinary.com/dxeynvxfr/image/upload/v1782105809/bukti_transaksi/edba9zphwoqoitzlzado.jpg', 'bukti_public_id' => 'bukti_transaksi/edba9zphwoqoitzlzado.jpg'],
            ['id' => 3, 'akun_id' => 1, 'penginput_id' => 1, 'penanggung_jawab_id' => 1, 'deskripsi' => 'Bakar2 tahun baru ikan 15kg', 'date' => '2026-05-05', 'jenis_transaksi' => 'pengeluaran', 'jumlah' => 525000, 'bukti' => 'https://res.cloudinary.com/dxeynvxfr/image/upload/v1782105852/bukti_transaksi/uotkfzkhqs8qtkgbnx67.jpg', 'bukti_public_id' => 'bukti_transaksi/uotkfzkhqs8qtkgbnx67.jpg'],
            ['id' => 4, 'akun_id' => 1, 'penginput_id' => 1, 'penanggung_jawab_id' => 1, 'deskripsi' => 'Bakar2 tahun baru bumbu', 'date' => '2026-05-05', 'jenis_transaksi' => 'pengeluaran', 'jumlah' => 318000],
            ['id' => 5, 'akun_id' => 1, 'penginput_id' => 1, 'penanggung_jawab_id' => 1, 'deskripsi' => 'Baksos', 'date' => '2026-05-05', 'jenis_transaksi' => 'pengeluaran', 'jumlah' => 300000],
            ['id' => 6, 'akun_id' => 1, 'penginput_id' => 1, 'penanggung_jawab_id' => 1, 'deskripsi' => 'Bukber', 'date' => '2026-05-05', 'jenis_transaksi' => 'pengeluaran', 'jumlah' => 200000],
            ['id' => 7, 'akun_id' => 1, 'penginput_id' => 1, 'penanggung_jawab_id' => 1, 'deskripsi' => 'Roti bakar konsum rapat', 'date' => '2026-05-05', 'jenis_transaksi' => 'pengeluaran', 'jumlah' => 30000],
            ['id' => 8, 'akun_id' => 1, 'penginput_id' => 1, 'penanggung_jawab_id' => 1, 'deskripsi' => 'Dp bukber', 'date' => '2026-05-05', 'jenis_transaksi' => 'pengeluaran', 'jumlah' => 500000, 'bukti' => 'https://res.cloudinary.com/dxeynvxfr/image/upload/v1782106087/bukti_transaksi/oeq01zalmzdmx70sd8n0.jpg', 'bukti_public_id' => 'bukti_transaksi/oeq01zalmzdmx70sd8n0.jpg'],
            ['id' => 9, 'akun_id' => 1, 'penginput_id' => 1, 'penanggung_jawab_id' => 1, 'deskripsi' => 'Supir', 'date' => '2026-05-05', 'jenis_transaksi' => 'pengeluaran', 'jumlah' => 150000],
            ['id' => 10, 'akun_id' => 1, 'penginput_id' => 1, 'penanggung_jawab_id' => 1, 'deskripsi' => 'Bensin', 'date' => '2026-05-05', 'jenis_transaksi' => 'pengeluaran', 'jumlah' => 200000],
            ['id' => 11, 'akun_id' => 1, 'penginput_id' => 1, 'penanggung_jawab_id' => 1, 'deskripsi' => 'Pelunasan Bukber', 'date' => '2026-05-05', 'jenis_transaksi' => 'pengeluaran', 'jumlah' => 1018000, 'bukti' => 'https://res.cloudinary.com/dxeynvxfr/image/upload/v1782106207/bukti_transaksi/udvkeruomzn23ta3wje8.jpg', 'bukti_public_id' => 'bukti_transaksi/udvkeruomzn23ta3wje8.jpg'],
            ['id' => 12, 'akun_id' => 1, 'penginput_id' => 1, 'penanggung_jawab_id' => 1, 'deskripsi' => 'Parkir', 'date' => '2026-05-05', 'jenis_transaksi' => 'pengeluaran', 'jumlah' => 10000],
            ['id' => 13, 'akun_id' => 1, 'penginput_id' => 1, 'penanggung_jawab_id' => 1, 'deskripsi' => 'Bensin', 'date' => '2026-05-05', 'jenis_transaksi' => 'pengeluaran', 'jumlah' => 200000],
            ['id' => 14, 'akun_id' => 1, 'penginput_id' => 1, 'penanggung_jawab_id' => 1, 'deskripsi' => 'Konsum rapat 17 an petama', 'date' => '2026-05-05', 'jenis_transaksi' => 'pengeluaran', 'jumlah' => 28000],
            ['id' => 15, 'akun_id' => 1, 'penginput_id' => 1, 'penanggung_jawab_id' => 1, 'deskripsi' => 'Konsum rapat 17 an ketiga', 'date' => '2026-05-05', 'jenis_transaksi' => 'pengeluaran', 'jumlah' => 43000],
            ['id' => 16, 'akun_id' => 1, 'penginput_id' => 1, 'penanggung_jawab_id' => 1, 'deskripsi' => 'Iuran pemuda', 'date' => '2026-05-05', 'jenis_transaksi' => 'pemasukan', 'jumlah' => 440000],
            ['id' => 17, 'akun_id' => 1, 'penginput_id' => 1, 'penanggung_jawab_id' => 1, 'deskripsi' => 'Pemasukan Pemuda', 'date' => '2026-05-05', 'jenis_transaksi' => 'pemasukan', 'jumlah' => 190000],
            ['id' => 18, 'akun_id' => 1, 'penginput_id' => 1, 'penanggung_jawab_id' => 1, 'deskripsi' => 'Pemasukan Pemuda', 'date' => '2026-05-06', 'jenis_transaksi' => 'pemasukan', 'jumlah' => 190000],
            ['id' => 19, 'akun_id' => 1, 'penginput_id' => 1, 'penanggung_jawab_id' => 1, 'deskripsi' => 'Konsum Rapat 17 an 23 Mei', 'date' => '2026-05-23', 'jenis_transaksi' => 'pengeluaran', 'jumlah' => 29000],
            ['id' => 20, 'akun_id' => 2, 'penginput_id' => 1, 'penanggung_jawab_id' => 4, 'deskripsi' => 'Bumbu Bakar2 Kurban', 'date' => '2026-05-28', 'jenis_transaksi' => 'pengeluaran', 'jumlah' => 45000, 'bukti' => 'https://res.cloudinary.com/dxeynvxfr/image/upload/v1782107073/bukti_transaksi/wizcuncdbu46pqsw3hr4.jpg', 'bukti_public_id' => 'bukti_transaksi/wizcuncdbu46pqsw3hr4.jpg'],
            ['id' => 21, 'akun_id' => 2, 'penginput_id' => 1, 'penanggung_jawab_id' => 4, 'deskripsi' => 'Masak Masak 30 Mei', 'date' => '2026-05-30', 'jenis_transaksi' => 'pengeluaran', 'jumlah' => 75000],
            ['id' => 22, 'akun_id' => 3, 'penginput_id' => 1, 'penanggung_jawab_id' => 1, 'deskripsi' => 'Snack sosialisasi 17 an', 'date' => '2026-06-05', 'jenis_transaksi' => 'pengeluaran', 'jumlah' => 262500, 'bukti' => 'https://res.cloudinary.com/dxeynvxfr/image/upload/v1782107391/bukti_transaksi/yafl4gj9cbpizpps1usi.jpg', 'bukti_public_id' => 'bukti_transaksi/yafl4gj9cbpizpps1usi.jpg'],
            ['id' => 23, 'akun_id' => 3, 'penginput_id' => 1, 'penanggung_jawab_id' => 2, 'deskripsi' => 'Print proposal dan materi', 'date' => '2026-06-06', 'jenis_transaksi' => 'pengeluaran', 'jumlah' => 80000, 'bukti' => 'https://res.cloudinary.com/dxeynvxfr/image/upload/v1782107525/bukti_transaksi/uukysz7froldosb9nfyg.jpg', 'bukti_public_id' => 'bukti_transaksi/uukysz7froldosb9nfyg.jpg'],
            ['id' => 24, 'akun_id' => 3, 'penginput_id' => 1, 'penanggung_jawab_id' => 1, 'deskripsi' => 'Beli air sosialisasi', 'date' => '2026-06-14', 'jenis_transaksi' => 'pengeluaran', 'jumlah' => 20000, 'bukti' => 'https://res.cloudinary.com/dxeynvxfr/image/upload/v1782107578/bukti_transaksi/juuukxprawwcqfmljxjg.jpg', 'bukti_public_id' => 'bukti_transaksi/juuukxprawwcqfmljxjg.jpg'],
            ['id' => 25, 'akun_id' => 3, 'penginput_id' => 1, 'penanggung_jawab_id' => 1, 'deskripsi' => 'Beli konsumsi rapat 13 juni 2026', 'date' => '2026-06-14', 'jenis_transaksi' => 'pengeluaran', 'jumlah' => 14000],
            ['id' => 26, 'akun_id' => 2, 'penginput_id' => 1, 'penanggung_jawab_id' => 4, 'deskripsi' => 'Pengeluaran masak2', 'date' => '2026-06-15', 'jenis_transaksi' => 'pengeluaran', 'jumlah' => 50000],
            ['id' => 27, 'akun_id' => 2, 'penginput_id' => 1, 'penanggung_jawab_id' => 3, 'deskripsi' => 'Beli karpet ruang pemuda', 'date' => '2026-06-15', 'jenis_transaksi' => 'pengeluaran', 'jumlah' => 55000, 'bukti' => 'https://res.cloudinary.com/dxeynvxfr/image/upload/v1782107851/bukti_transaksi/gqutivodm9ujcbgbjkjw.jpg', 'bukti_public_id' => 'bukti_transaksi/gqutivodm9ujcbgbjkjw.jpg'],
            ['id' => 28, 'akun_id' => 3, 'penginput_id' => 1, 'penanggung_jawab_id' => 1, 'deskripsi' => 'Konsumsi rapat', 'date' => '2026-06-20', 'jenis_transaksi' => 'pengeluaran', 'jumlah' => 315000, 'bukti' => 'https://res.cloudinary.com/dxeynvxfr/image/upload/v1782107978/bukti_transaksi/ytwc3hkachlhzbzxtnt2.jpg', 'bukti_public_id' => 'bukti_transaksi/ytwc3hkachlhzbzxtnt2.jpg'],
            ['id' => 29, 'akun_id' => 3, 'penginput_id' => 1, 'penanggung_jawab_id' => 1, 'deskripsi' => 'Konsum rapat proposal dan konsum rapat pensi', 'date' => '2026-06-21', 'jenis_transaksi' => 'pengeluaran', 'jumlah' => 64000, 'bukti' => 'https://res.cloudinary.com/dxeynvxfr/image/upload/v1782108162/bukti_transaksi/seycawrl1aoy8h1xr1wd.jpg', 'bukti_public_id' => 'bukti_transaksi/seycawrl1aoy8h1xr1wd.jpg'],
        ];

        foreach ($transaksis as $transaksi) {
            Transaksi::create($transaksi);
        }

        Transaksi::findorFail(17)->delete();
    }
}
