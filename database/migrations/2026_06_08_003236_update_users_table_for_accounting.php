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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['bendahara', 'biasa'])->after('email');
            $table->enum('status', ['Aktif', 'Pending', 'Tidak Aktif'])->after('role');
            $table->string('profile_image')->nullable()->after('status');
            $table->string('provider')->nullable()->after('status');
            $table->string('id_provider')->nullable()->after('provider');
            $table->timestamp('activated_at')->nullable()->after('password');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'role', 'status', 'profile_image', 'provider', 'id_provider', 'activated_at']);
            $table->dropSoftDeletes();
        });
    }
};
