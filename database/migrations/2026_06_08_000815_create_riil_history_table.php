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
        Schema::create('riil_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('akun_id');
            $table->date('date')->index();
            $table->boolean('verified')->index();
            $table->decimal('riil', 15, 2);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('akun_id')->references('id')->on('akun')->onDelete('set null');
            $table->unique(['akun_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riil_history');
    }
};
