<?php

namespace Database\Seeders;

use App\Models\Topic;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    public function run(): void
    {
        $topics = [
            [
                'title' => 'Urutan (Sequencing)',
                'slug' => 'urutan-sequencing',
                'description' => 'Belajar menyusun langkah-langkah berurutan dengan benar agar tujuan tercapai!',
                'icon' => '📋',
                'color' => '#FF4757',
                'kelas_level' => 1,
                'order' => 1,
                'category' => 'urutan',
                'is_active' => true,
            ],
            [
                'title' => 'Pola & Pattern',
                'slug' => 'pola-pattern',
                'description' => 'Mengenali bentuk, warna, dan angka yang berulang untuk memprediksi langkah selanjutnya!',
                'icon' => '🧩',
                'color' => '#FFA502',
                'kelas_level' => 1,
                'order' => 2,
                'category' => 'pola',
                'is_active' => true,
            ],
            [
                'title' => 'Percabangan (Selection / If-Else)',
                'slug' => 'percabangan-if-else',
                'description' => 'Membuat keputusan logis: JIKA kondisi terpenuhi, LAKUKAN aksi tertentu!',
                'icon' => '🔀',
                'color' => '#2ED573',
                'kelas_level' => 1,
                'order' => 3,
                'category' => 'percabangan',
                'is_active' => true,
            ],
            [
                'title' => 'Pengulangan (Looping)',
                'slug' => 'pengulangan-looping',
                'description' => 'Mengulang instruksi secara efisien tanpa harus menulisnya berkali-kali!',
                'icon' => '🔄',
                'color' => '#1E90FF',
                'kelas_level' => 1,
                'order' => 4,
                'category' => 'pengulangan',
                'is_active' => true,
            ],
            [
                'title' => 'Dekomposisi (Decomposition)',
                'slug' => 'dekomposisi',
                'description' => 'Memecah masalah besar menjadi bagian-bagian kecil yang lebih mudah diselesaikan!',
                'icon' => '🔍',
                'color' => '#9B59B6',
                'kelas_level' => 1,
                'order' => 5,
                'category' => 'dekomposisi',
                'is_active' => true,
            ],
        ];

        foreach ($topics as $topic) {
            Topic::updateOrCreate(['slug' => $topic['slug']], $topic);
        }
    }
}
