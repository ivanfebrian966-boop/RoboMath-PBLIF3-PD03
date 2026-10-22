<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('users', 'pengguna');
        Schema::rename('document_summaries', 'dokumen');
        Schema::rename('document_chunks', 'potongan_dokumen');
        Schema::rename('topics', 'topik');
        Schema::rename('quizzes', 'soal');
        Schema::rename('quiz_attempts', 'percobaan_soal');
        Schema::rename('badges', 'pencapaian');
        Schema::rename('user_badges', 'pencapaian_pengguna');
        Schema::rename('class_rooms', 'kelas');
        Schema::rename('class_room_student', 'anggota_kelas');
        Schema::rename('class_room_topic', 'kelas_topik');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('kelas_topik', 'class_room_topic');
        Schema::rename('anggota_kelas', 'class_room_student');
        Schema::rename('kelas', 'class_rooms');
        Schema::rename('pencapaian_pengguna', 'user_badges');
        Schema::rename('pencapaian', 'badges');
        Schema::rename('percobaan_soal', 'quiz_attempts');
        Schema::rename('soal', 'quizzes');
        Schema::rename('topik', 'topics');
        Schema::rename('potongan_dokumen', 'document_chunks');
        Schema::rename('dokumen', 'document_summaries');
        Schema::rename('pengguna', 'users');
    }
};
