<?php

namespace Database\Seeders;

use App\Models\Misi;
use App\Models\MisiPengguna;
use App\Models\User;
use Illuminate\Database\Seeder;

class MisiSeeder extends Seeder
{
    public function run(): void
    {
        $missions = [
            [
                'judul' => 'Mulai Belajar',
                'deskripsi' => 'Selesaikan satu latihan soal hari ini.',
                'poin' => 10,
                'is_active' => true,
            ],
            [
                'judul' => 'Rajin Berlatih',
                'deskripsi' => 'Jawab lima soal dengan benar.',
                'poin' => 25,
                'is_active' => true,
            ],
            [
                'judul' => 'Penjelajah Topik',
                'deskripsi' => 'Selesaikan latihan dari tiga topik berbeda.',
                'poin' => 30,
                'is_active' => true,
            ],
        ];

        foreach ($missions as $mission) {
            $misiModel = Misi::updateOrCreate(
                ['judul' => $mission['judul']],
                $mission
            );

            $students = User::where('role', 'siswa')->get();
            foreach ($students as $student) {
                MisiPengguna::firstOrCreate(
                    [
                        'user_id' => $student->id,
                        'misi_id' => $misiModel->id,
                    ],
                    [
                        'judul' => $mission['judul'],
                        'deskripsi' => $mission['deskripsi'],
                        'status' => 'belum',
                    ]
                );
            }
        }
    }
}
