<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class LarastanCommand extends Command
{
    // Perintah yang akan diketik di terminal
    protected $signature = 'code:lint';

    protected $description = 'Jalankan analisis kode statis menggunakan Larastan';

    public function handle()
    {
        $this->info('🚀 Sedang memeriksa kode dengan Larastan...');

        // Pastikan Anda sudah membuat file phpstan.neon agar 'analyse' otomatis tahu folder mana saja yang dicek
        $process = $process = new Process([base_path('vendor/bin/phpstan'), 'analyse']);
        $process->setTimeout(null);

        // Menggunakan callback agar output Larastan muncul secara REAL-TIME di terminal
        $exitCode = $process->run(function ($type, $buffer) {
            $this->output->write($buffer);
        });

        if ($exitCode === 0) {
            $this->info('✨ Pemeriksaan selesai. Kode Anda bersih!');
        } else {
            $this->error('❌ Pemeriksaan selesai. Ditemukan beberapa masalah pada kode.');
        }

        return $exitCode;
    }
}
