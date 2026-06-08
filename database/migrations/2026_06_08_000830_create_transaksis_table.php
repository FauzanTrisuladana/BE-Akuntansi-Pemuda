<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('akun_id');
            $table->unsignedBigInteger('penginput_id');
            $table->unsignedBigInteger('penanggung_jawab_id');
            $table->string('deskripsi');
            $table->date('date')->index();
            $table->enum('jenis_transaksi', ['pemasukan', 'pengeluaran'])->index();
            $table->integer('jumlah');
            $table->string('bukti')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('akun_id')->references('id')->on('akun')->onDelete('cascade');
            $table->foreign('penginput_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('penanggung_jawab_id')->references('id')->on('penanggung_jawab')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
