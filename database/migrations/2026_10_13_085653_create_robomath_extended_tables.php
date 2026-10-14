<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat tabel sesuai class diagram RoboMath:
     * sekolah, tugas, sesi_belajar, pengumpulan_tugas, misi_pengguna
     */
    public function up(): void
    {
        // Sekolah — tempat guru & siswa terdaftar
        Schema::create('sekolah', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sekolah');
            $table->string('nama_kota')->nullable();
            $table->char('status', 1)->default('A'); // A=aktif, N=nonaktif
            $table->timestamps();
        });

        // Tambah school_id ke class_rooms agar Kelas milik Sekolah
        Schema::table('class_rooms', function (Blueprint $table) {
            $table->foreignId('school_id')->nullable()->constrained('sekolah')->nullOnDelete()->after('id');
            $table->float('nilai_lulus')->default(75)->after('is_active'); // nilai_lulus untuk Kelas
        });

        // Tambah school_id ke users agar Pengguna terdaftar di Sekolah
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('school_id')->nullable()->constrained('sekolah')->nullOnDelete()->after('is_active');
        });

        // Tugas — guru dapat membuat tugas untuk kelas
        Schema::create('tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_room_id')->constrained('class_rooms')->cascadeOnDelete();
            $table->foreignId('topic_id')->nullable()->constrained('topics')->nullOnDelete();
            $table->string('judul');
            $table->dateTime('tenggat_waktu')->nullable();
            $table->float('nilai_lulus')->default(70);
            $table->timestamps();
        });

        // PengumpulanTugas — siswa mengumpulkan jawaban tugas
        Schema::create('pengumpulan_tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')->constrained('tugas')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->float('skor')->nullable();
            $table->string('status')->default('dikirim'); // dikirim, dinilai, terlambat
            $table->timestamps();

            $table->unique(['tugas_id', 'student_id']);
        });

        // SesiBelajar — setiap sesi bermain / belajar siswa
        Schema::create('sesi_belajar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('topic_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('lesson_id')->nullable()->constrained()->nullOnDelete();
            $table->dateTime('dimulai_pada')->useCurrent();
            $table->dateTime('selesai_pada')->nullable();
            $table->unsignedInteger('poin_diperoleh')->default(0);
            $table->timestamps();
        });

        // MisiPengguna — misi/tantangan yang diberikan ke siswa
        Schema::create('misi_pengguna', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('judul');
            $table->string('deskripsi')->nullable();
            $table->string('status')->default('aktif'); // aktif, selesai, gagal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('misi_pengguna');
        Schema::dropIfExists('sesi_belajar');
        Schema::dropIfExists('pengumpulan_tugas');
        Schema::dropIfExists('tugas');

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('school_id');
        });

        Schema::table('class_rooms', function (Blueprint $table) {
            $table->dropConstrainedForeignId('school_id');
            $table->dropColumn('nilai_lulus');
        });

        Schema::dropIfExists('sekolah');
    }
};
