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
        Schema::create('mutasi_rekening', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('akun_debit_id');
            $table->unsignedBigInteger('akun_kredit_id');
            $table->date('date')->index();
            $table->integer('jumlah');
            $table->text('keterangan');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('akun_debit_id')->references('id')->on('akun')->onDelete('set null');
            $table->foreign('akun_kredit_id')->references('id')->on('akun')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_rekening');
    }
};
