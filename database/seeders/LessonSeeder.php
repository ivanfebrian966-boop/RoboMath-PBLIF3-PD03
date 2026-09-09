<?php

namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\Topic;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        $urutan = Topic::where('slug', 'urutan-sequencing')->first();
        $pola = Topic::where('slug', 'pola-pattern')->first();
        $percabangan = Topic::where('slug', 'percabangan-if-else')->first();
        $pengulangan = Topic::where('slug', 'pengulangan-looping')->first();
        $dekomposisi = Topic::where('slug', 'dekomposisi')->first();

        $lessons = [
            // Urutan
            [
                'topic_id' => $urutan->id,
                'title' => 'Mengenal Langkah Berurutan',
                'slug' => 'mengenal-langkah-berurutan',
                'content' => '
                    <div class="space-y-4 text-slate-700">
                        <p class="text-lg leading-relaxed">Pernahkah kamu membuat sereal susu pagi hari? 🥣</p>
                        <div class="bg-amber-50 p-4 rounded-2xl border-2 border-amber-200">
                            <h4 class="font-bold text-amber-800 mb-2">Langkah Membuat Sereal:</h4>
                            <ol class="list-decimal list-inside space-y-1 font-semibold">
                                <li>Ambil mangkuk bersih 🥣</li>
                                <li>Tuang sereal secukupnya 🌾</li>
                                <li>Tuangkan susu dingin 🥛</li>
                                <li>Ambil sendok dan nikmati! 🥄</li>
                            </ol>
                        </div>
                        <p class="text-base">Jika kamu menuangkan susu sebelum mengambil mangkuk, apa yang akan terjadi? Susunya tumpah ke meja! 😱 Itulah mengapa **URUTAN** sangat penting dalam algoritma!</p>
                    </div>
                ',
                'order' => 1,
                'difficulty' => 'mudah',
                'estimated_minutes' => 5,
            ],
            [
                'topic_id' => $urutan->id,
                'title' => 'Algoritma Siap-Siap Sekolah',
                'slug' => 'algoritma-siap-siap-sekolah',
                'content' => '
                    <div class="space-y-4 text-slate-700">
                        <p class="text-lg leading-relaxed">Sebelum berangkat sekolah, kita melakukan serangkaian kegiatan secara urut. 🎒</p>
                        <div class="bg-blue-50 p-4 rounded-2xl border-2 border-blue-200">
                            <h4 class="font-bold text-blue-800 mb-2">Urutan yang Benar:</h4>
                            <ul class="space-y-2">
                                <li class="flex items-center gap-2"><span>1️⃣</span> Bangun tidur & merapikan tempat tidur 🛏️</li>
                                <li class="flex items-center gap-2"><span>2️⃣</span> Mandi dan gosok gigi 🧼</li>
                                <li class="flex items-center gap-2"><span>3️⃣</span> Memakai seragam sekolah 👔</li>
                                <li class="flex items-center gap-2"><span>4️⃣</span> Sarapan pagi 🍞</li>
                                <li class="flex items-center gap-2"><span>5️⃣</span> Memakai sepatu dan pamit orang tua 👟</li>
                            </ul>
                        </div>
                        <p class="text-base">Komputer bekerja persis seperti ini. Komputer menjalankan perintah baris demi baris dari atas ke bawah!</p>
                    </div>
                ',
                'order' => 2,
                'difficulty' => 'mudah',
                'estimated_minutes' => 7,
            ],

            // Percabangan
            [
                'topic_id' => $percabangan->id,
                'title' => 'Keputusan Jika - Maka (IF - ELSE)',
                'slug' => 'keputusan-jika-maka',
                'content' => '
                    <div class="space-y-4 text-slate-700">
                        <p class="text-lg leading-relaxed">Dalam hidup sehari-hari, kita sering membuat pilihan berdasarkan kondisi di sekitar kita! 🌦️</p>
                        <div class="bg-emerald-50 p-4 rounded-2xl border-2 border-emerald-200 space-y-3">
                            <h4 class="font-bold text-emerald-800">Contoh Percabangan:</h4>
                            <div class="p-3 bg-white rounded-xl border border-emerald-300 font-mono text-sm">
                                <span class="text-red-500 font-bold">JIKA</span> (Cuaca Hujan) {<br>
                                &nbsp;&nbsp;Bawa Payung ☔<br>
                                } <span class="text-blue-500 font-bold">JIKA TIDAK</span> {<br>
                                &nbsp;&nbsp;Pakai Topi 🧢<br>
                                }
                            </div>
                        </div>
                        <p class="text-base">Fitur ini membantu robot dan aplikasi membuat keputusan pintar! 🤖</p>
                    </div>
                ',
                'order' => 1,
                'difficulty' => 'sedang',
                'estimated_minutes' => 8,
            ],

            // Pengulangan
            [
                'topic_id' => $pengulangan->id,
                'title' => 'Ayo Mengulang Instruksi (LOOP)',
                'slug' => 'ayo-mengulang-instruksi',
                'content' => '
                    <div class="space-y-4 text-slate-700">
                        <p class="text-lg leading-relaxed">Bayangkan kamu diminta melompat 10 kali saat olahraga. 🤸‍♂️</p>
                        <p>Daripada guru bilang: "Lompat 1 kali! Lompat 1 kali! Lompat 1 kali..." sampai 10 kali, guru cukup bilang:</p>
                        <div class="bg-purple-50 p-4 rounded-2xl border-2 border-purple-200 font-bold text-purple-900 text-center text-lg">
                            "Ulangi Lompat sebanyak 10 kali!" 🔄
                        </div>
                        <p>Itulah yang dinamakan **Pengulangan (Loop)** dalam algoritma! Sangat praktis dan menghemat waktu!</p>
                    </div>
                ',
                'order' => 1,
                'difficulty' => 'mudah',
                'estimated_minutes' => 6,
            ],

            // Pola
            [
                'topic_id' => $pola->id,
                'title' => 'Detektif Pola Warna & Gambar',
                'slug' => 'detektif-pola-warna-gambar',
                'content' => '
                    <div class="space-y-4 text-slate-700">
                        <p class="text-lg">Menemukan pola membuatmu seperti detektif rahasia! 🕵️✨</p>
                        <div class="bg-orange-50 p-4 rounded-2xl border-2 border-orange-200 text-center">
                            <p class="font-bold text-xl mb-2">🔴 🟡 🔴 🟡 🔴 ... ?</p>
                            <p class="text-sm text-slate-600">Pola di atas adalah selang-seling Merah dan Kuning. Setelah Merah, pasti KUNING! 🟡</p>
                        </div>
                    </div>
                ',
                'order' => 1,
                'difficulty' => 'mudah',
                'estimated_minutes' => 5,
            ],

            // Dekomposisi
            [
                'topic_id' => $dekomposisi->id,
                'title' => 'Memecah Masalah Besar',
                'slug' => 'memecah-masalah-besar',
                'content' => '
                    <div class="space-y-4 text-slate-700">
                        <p class="text-lg">Bagaimana cara memakan semangka yang sangat besar? 🍉</p>
                        <p class="font-semibold">Tentu dengan memotongnya menjadi bagian-bagian kecil terlebih dahulu!</p>
                        <div class="bg-indigo-50 p-4 rounded-2xl border-2 border-indigo-200">
                            <h4 class="font-bold text-indigo-900 mb-2">Contoh Dekomposisi "Membersihkan Kamar":</h4>
                            <ul class="list-disc list-inside space-y-1">
                                <li>Bagian 1: Merapikan tempat tidur 🛏️</li>
                                <li>Bagian 2: Menyapu lantai 🧹</li>
                                <li>Bagian 3: Menata mainan di rak 🧸</li>
                            </ul>
                        </div>
                    </div>
                ',
                'order' => 1,
                'difficulty' => 'sedang',
                'estimated_minutes' => 7,
            ],
        ];

        foreach ($lessons as $lesson) {
            Lesson::updateOrCreate(['slug' => $lesson['slug']], $lesson);
        }
    }
}
