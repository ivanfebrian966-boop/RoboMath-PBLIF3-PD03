<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom streak ke tabel users sesuai class diagram Pengguna.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('streak_saat_ini')->default(0)->after('is_active');
            $table->unsignedInteger('streak_terbaik')->default(0)->after('streak_saat_ini');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['streak_saat_ini', 'streak_terbaik']);
        });
    }
};
