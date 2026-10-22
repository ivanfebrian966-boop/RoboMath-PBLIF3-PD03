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
        Schema::create('misi', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->unsignedInteger('poin')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('misi_pengguna', function (Blueprint $table) {
            $table->foreignId('misi_id')->nullable()->after('user_id')->constrained('misi')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('misi_pengguna', function (Blueprint $table) {
            $table->dropConstrainedForeignId('misi_id');
        });

        Schema::dropIfExists('misi');
    }
};
