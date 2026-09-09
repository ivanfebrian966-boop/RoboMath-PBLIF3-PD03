<?php

namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Topic;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $urutan = Topic::where('slug', 'urutan-sequencing')->first();
        $pola = Topic::where('slug', 'pola-pattern')->first();
        $percabangan = Topic::where('slug', 'percabangan-if-else')->first();
        $pengulangan = Topic::where('slug', 'pengulangan-looping')->first();
        $dekomposisi = Topic::where('slug', 'dekomposisi')->first();

        $lessonUrutan1 = Lesson::where('slug', 'mengenal-langkah-berurutan')->first();
        $lessonPercabangan1 = Lesson::where('slug', 'keputusan-jika-maka')->first();
        $lessonPengulangan1 = Lesson::where('slug', 'ayo-mengulang-instruksi')->first();
        $lessonPola1 = Lesson::where('slug', 'detektif-pola-warna-gambar')->first();
        $lessonDekomposisi1 = Lesson::where('slug', 'memecah-masalah-besar')->first();

        $quizzes = [
            // --- URUTAN ---
            [
                'topic_id' => $urutan->id,
                'lesson_id' => $lessonUrutan1->id,
                'type' => 'pilihan_ganda',
                'question' => 'Manakah urutan yang BENAR saat mencuci tangan?',
                'options' => [
                    'A' => 'Keringkan dengan handuk ➡️ Basahi tangan ➡️ Pakai sabun ➡️ Bilas air',
                    'B' => 'Basahi tangan ➡️ Pakai sabun ➡️ Bilas air ➡️ Keringkan dengan handuk',
                    'C' => 'Pakai sabun ➡️ Keringkan dengan handuk ➡️ Bilas air ➡️ Basahi tangan',
                    'D' => 'Bilas air ➡️ Keringkan dengan handuk ➡️ Pakai sabun ➡️ Basahi tangan',
                ],
                'correct_answer' => 'B',
                'explanation' => 'Urutan mencuci tangan yang benar adalah membasahi tangan dulu, memberi sabun, membilas hingga bersih, lalu mengeringkannya!',
                'difficulty' => 'mudah',
                'points' => 10,
                'is_active' => true,
            ],
            [
                'topic_id' => $urutan->id,
                'lesson_id' => $lessonUrutan1->id,
                'type' => 'cerita_logika',
                'question' => 'Budi ingin pergi ke sekolah naik sepeda. Sepeda Budi terkunci di garasi. Kunci garasi ada di dalam meja belajar Budi. Urutkan langkah Budi agar bisa naik sepeda!',
                'options' => [
                    'A' => 'Ambil kunci di meja ➡️ Buka kunci garasi ➡️ Ambil sepeda ➡️ Berangkat sekolah',
                    'B' => 'Ambil sepeda ➡️ Buka kunci garasi ➡️ Ambil kunci di meja ➡️ Berangkat sekolah',
                    'C' => 'Berangkat sekolah ➡️ Ambil kunci di meja ➡️ Buka kunci garasi ➡️ Ambil sepeda',
                    'D' => 'Buka kunci garasi ➡️ Ambil kunci di meja ➡️ Ambil sepeda ➡️ Berangkat sekolah',
                ],
                'correct_answer' => 'A',
                'explanation' => 'Budi harus mengambil kunci di meja terlebih dahulu, membuka garasi, mengambil sepeda, baru bisa berangkat ke sekolah!',
                'difficulty' => 'sedang',
                'points' => 15,
                'is_active' => true,
            ],

            // --- PERCABANGAN ---
            [
                'topic_id' => $percabangan->id,
                'lesson_id' => $lessonPercabangan1->id,
                'type' => 'pilihan_ganda',
                'question' => 'JIKA nilai ujian Dini lebih besar dari 70, Dini lulus. Nilai Dini adalah 85. Apakah Dini lulus?',
                'options' => [
                    'A' => 'Ya, Dini lulus 🎉',
                    'B' => 'Tidak, Dini tidak lulus ❌',
                    'C' => 'Dini harus mengulang 📝',
                    'D' => 'Tidak bisa ditentukan ❓',
                ],
                'correct_answer' => 'A',
                'explanation' => 'Karena 85 lebih besar dari 70, maka kondisi JIKA (nilai > 70) terpenuhi! Dini Lulus!',
                'difficulty' => 'mudah',
                'points' => 10,
                'is_active' => true,
            ],
            [
                'topic_id' => $percabangan->id,
                'lesson_id' => $lessonPercabangan1->id,
                'type' => 'cerita_logika',
                'question' => 'Instruksi Robot: "JIKA di depanmu ada dinding, belok KANAN. JIKA TIDAK ada dinding, jalan LURUS". Di depan Robot TIDAK ADA dinding. Apa yang dilakukan Robot?',
                'options' => [
                    'A' => 'Belok Kanan ➡️',
                    'B' => 'Jalan Lurus ⬆️',
                    'C' => 'Belok Kiri ⬅️',
                    'D' => 'Berhenti 🛑',
                ],
                'correct_answer' => 'B',
                'explanation' => 'Karena kondisi "ada dinding" adalah TIDAK (false), maka Robot menjalankan bagian JIKA TIDAK yaitu Jalan Lurus!',
                'difficulty' => 'sedang',
                'points' => 15,
                'is_active' => true,
            ],

            // --- PENGULANGAN ---
            [
                'topic_id' => $pengulangan->id,
                'lesson_id' => $lessonPengulangan1->id,
                'type' => 'pilihan_ganda',
                'question' => 'Jika ada perintah "Ulangi (Tepuk Tangan 👏) sebanyak 4 kali", berapa kali kamu menepuk tangan?',
                'options' => [
                    'A' => '2 kali',
                    'B' => '3 kali',
                    'C' => '4 kali',
                    'D' => '5 kali',
                ],
                'correct_answer' => 'C',
                'explanation' => 'Perintah loop menyatakan mengulang 4 kali, jadi kamu menepuk tangan sebanyak 4 kali!',
                'difficulty' => 'mudah',
                'points' => 10,
                'is_active' => true,
            ],
            [
                'topic_id' => $pengulangan->id,
                'lesson_id' => $lessonPengulangan1->id,
                'type' => 'cerita_logika',
                'question' => 'Kelinci ingin mengambil 3 buah wortel 🥕. Untuk mengambil 1 wortel, Kelinci harus melompat 2 kali. Berapa total lompatan Kelinci untuk mendapatkan 3 wortel?',
                'options' => [
                    'A' => '3 kali',
                    'B' => '5 kali',
                    'C' => '6 kali',
                    'D' => '9 kali',
                ],
                'correct_answer' => 'C',
                'explanation' => '3 wortel × 2 lompatan per wortel = 6 lompatan total!',
                'difficulty' => 'sedang',
                'points' => 15,
                'is_active' => true,
            ],

            // --- POLA ---
            [
                'topic_id' => $pola->id,
                'lesson_id' => $lessonPola1->id,
                'type' => 'pilihan_ganda',
                'question' => 'Perhatikan pola angka berikut: 2, 4, 6, 8, ... Berapakah angka selanjutnya?',
                'options' => [
                    'A' => '9',
                    'B' => '10',
                    'C' => '11',
                    'D' => '12',
                ],
                'correct_answer' => 'B',
                'explanation' => 'Pola angka ini bertambah 2 setiap langkah (+2). 8 + 2 = 10!',
                'difficulty' => 'mudah',
                'points' => 10,
                'is_active' => true,
            ],

            // --- DEKOMPOSISI ---
            [
                'topic_id' => $dekomposisi->id,
                'lesson_id' => $lessonDekomposisi1->id,
                'type' => 'pilihan_ganda',
                'question' => 'Manakah contoh Dekomposisi (memecah masalah) dari kegiatan "Membuat Kue Tar"?',
                'options' => [
                    'A' => 'Membeli kue tar langsung di toko bakery 🎂',
                    'B' => 'Membagi kegiatan menjadi: Siapkan bahan ➡️ Buat adonan ➡️ Panggang ➡️ Hias kue 🍓',
                    'C' => 'Langsung memakan kue tar tanpa dimasak 😋',
                    'D' => 'Meniup lilin di atas kue tar 🕯️',
                ],
                'correct_answer' => 'B',
                'explanation' => 'Dekomposisi berarti membagi tugas besar "Membuat Kue Tar" menjadi langkah-langkah kecil yang lebih sederhana!',
                'difficulty' => 'sedang',
                'points' => 15,
                'is_active' => true,
            ],
        ];

        foreach ($quizzes as $quiz) {
            Quiz::create($quiz);
        }
    }
}
