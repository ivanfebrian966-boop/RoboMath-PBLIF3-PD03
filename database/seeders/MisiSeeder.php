<?php

namespace Database\Seeders;

use App\Models\Misi;
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
            Misi::updateOrCreate(
                ['judul' => $mission['judul']],
                $mission
            );
        }
    }
}
