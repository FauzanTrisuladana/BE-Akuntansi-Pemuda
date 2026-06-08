<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class LarastanCommand extends Command
{
    // Ini adalah perintah yang akan kamu ketik di terminal
    protected $signature = 'code:lint';

    protected $description = 'Jalankan analisis kode statis menggunakan Larastan';

    public function handle()
    {
        $this->info('Sedang memeriksa kode...');

        // Menjalankan perintah PHPStan di latar belakang
        $process = new Process(['./vendor/bin/phpstan', 'analyse']);
        $process->setTimeout(null);
        $process->run();

        // Tampilkan outputnya di terminal
        echo $process->getOutput();

        $this->info('Pemeriksaan selesai.');

        return $process->getExitCode();
    }
}
