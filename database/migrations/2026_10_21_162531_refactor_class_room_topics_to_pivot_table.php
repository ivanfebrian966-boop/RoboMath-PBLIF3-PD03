<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Refaktor relasi ClassRoom ↔ Topic:
     * - Ganti kolom JSON topic_ids → tabel pivot class_room_topic
     * - Tambah document_summary_id ke topics agar Topik bisa dilacak ke Dokumen asal
     */
    public function up(): void
    {
        // 1. Tabel pivot ClassRoom ↔ Topic (many-to-many)
        Schema::create('class_room_topic', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_room_id')->constrained('class_rooms')->cascadeOnDelete();
            $table->foreignId('topic_id')->constrained('topics')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['class_room_id', 'topic_id']);
        });

        // 2. Tambah FK document_summary_id ke topics
        Schema::table('topics', function (Blueprint $table) {
            $table->foreignId('document_summary_id')
                ->nullable()
                ->after('id')
                ->constrained('document_summaries')
                ->nullOnDelete();
        });

        // 3. Migrasi data dari JSON topic_ids ke pivot table
        $classRooms = DB::table('class_rooms')->whereNotNull('topic_ids')->get();
        foreach ($classRooms as $classRoom) {
            $topicIds = json_decode($classRoom->topic_ids, true) ?? [];
            foreach ($topicIds as $topicId) {
                // Pastikan topik masih ada sebelum insert
                if (DB::table('topics')->where('id', $topicId)->exists()) {
                    DB::table('class_room_topic')->insertOrIgnore([
                        'class_room_id' => $classRoom->id,
                        'topic_id' => $topicId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // 4. Hapus kolom JSON lama
        Schema::table('class_rooms', function (Blueprint $table) {
            $table->dropColumn('topic_ids');
        });
    }

    public function down(): void
    {
        // Kembalikan kolom JSON topic_ids
        Schema::table('class_rooms', function (Blueprint $table) {
            $table->json('topic_ids')->nullable()->after('kelas_level');
        });

        // Migrasi data kembali dari pivot ke JSON
        $pivotData = DB::table('class_room_topic')
            ->select('class_room_id', DB::raw('JSON_ARRAYAGG(topic_id) as topic_ids'))
            ->groupBy('class_room_id')
            ->get();

        foreach ($pivotData as $row) {
            DB::table('class_rooms')
                ->where('id', $row->class_room_id)
                ->update(['topic_ids' => $row->topic_ids]);
        }

        // Hapus FK dari topics
        Schema::table('topics', function (Blueprint $table) {
            $table->dropConstrainedForeignId('document_summary_id');
        });

        Schema::dropIfExists('class_room_topic');
    }
};
