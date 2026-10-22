<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\Sekolah;
use App\Models\Topic;
use App\Models\Tugas;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SekolahSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 2 sekolah contoh
        $sdn1 = Sekolah::updateOrCreate(
            ['nama_sekolah' => 'SDN 1 Bandung'],
            [
                'nama_kota' => 'Bandung',
                'status' => 'A',
            ]
        );

        $sdn2 = Sekolah::updateOrCreate(
            ['nama_sekolah' => 'SDN 5 Jakarta'],
            [
                'nama_kota' => 'Jakarta',
                'status' => 'A',
            ]
        );

        // Hubungkan guru ke sekolah
        User::where('role', 'guru')->update(['school_id' => $sdn1->id]);

        // Buat kelas demo
        $guru = User::where('email', 'guru@robomath.id')->first();

        if ($guru) {
            $kelas = ClassRoom::updateOrCreate(
                ['code' => 'MATH101'],
                [
                    'teacher_id' => $guru->id,
                    'school_id' => $sdn1->id,
                    'name' => 'Kelas 1A - Matematika',
                    'kelas_level' => 1,
                    'is_active' => true,
                    'nilai_lulus' => 75,
                ]
            );

            $approvedTopicIds = Topic::where('kelas_level', 1)
                ->where('is_active', true)
                ->where('review_status', 'approved')
                ->pluck('id');
            $kelas->topics()->syncWithoutDetaching($approvedTopicIds);

            $studentIds = User::where('role', 'siswa')
                ->where('kelas', 1)
                ->pluck('id');
            $kelas->students()->syncWithoutDetaching(
                $studentIds->mapWithKeys(fn ($studentId) => [
                    $studentId => ['joined_at' => now()],
                ])->all()
            );

            // Buat tugas contoh
            Tugas::updateOrCreate(
                ['judul' => 'Latihan Penjumlahan 1-10'],
                [
                    'class_room_id' => $kelas->id,
                    'judul' => 'Latihan Penjumlahan 1-10',
                    'tenggat_waktu' => Carbon::now()->addDays(7),
                    'nilai_lulus' => 70,
                ]
            );
        }
    }
}
