<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            [
                'name' => 'Langkah Pertama 🐣',
                'slug' => 'langkah-pertama',
                'description' => 'Menyelesaikan materi pembelajaran pertamamu di AlgoKids!',
                'icon' => '🐣',
                'color' => '#FF4757',
                'requirement_type' => 'lessons_completed',
                'requirement_value' => 1,
            ],
            [
                'name' => 'Pencari Jawaban 🔍',
                'slug' => 'pencari-jawaban',
                'description' => 'Menjawab 5 soal latihan dengan benar!',
                'icon' => '🎯',
                'color' => '#FFA502',
                'requirement_type' => 'quizzes_correct',
                'requirement_value' => 5,
            ],
            [
                'name' => 'Bintang Sekolah ⭐',
                'slug' => 'bintang-sekolah',
                'description' => 'Mencapai total skor 100 poin!',
                'icon' => '⭐',
                'color' => '#FFD700',
                'requirement_type' => 'score',
                'requirement_value' => 100,
            ],
            [
                'name' => 'Master Logika 🧠',
                'slug' => 'master-logika',
                'description' => 'Menyelesaikan 5 materi pembelajaran!',
                'icon' => '🧠',
                'color' => '#70A1FF',
                'requirement_type' => 'lessons_completed',
                'requirement_value' => 5,
            ],
            [
                'name' => 'Raja Algoritma 👑',
                'slug' => 'raja-algoritma',
                'description' => 'Mencapai total skor 500 poin!',
                'icon' => '👑',
                'color' => '#2ED573',
                'requirement_type' => 'score',
                'requirement_value' => 500,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(['slug' => $badge['slug']], $badge);
        }
    }
}
