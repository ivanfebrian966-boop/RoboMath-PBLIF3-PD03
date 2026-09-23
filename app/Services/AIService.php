<?php

namespace App\Services;

use App\Models\User;

class AIService
{
    /**
     * Chat with AI assistant - currently using mock responses for demo.
     * Can be extended to use OpenAI/Gemini API.
     */
    public function chat(string $message, User $user, ?string $context = null): string
    {
        $provider = config('services.ai.provider', 'mock');

        if ($provider === 'mock') {
            return $this->mockResponse($message, $user, $context);
        }

        // Future: OpenAI/Gemini integration
        return $this->mockResponse($message, $user, $context);
    }

    /**
     * Generate mock AI responses for demo purposes.
     */
    private function mockResponse(string $message, User $user, ?string $context = null): string
    {
        $lowerMessage = strtolower($message);
        $name = $user->name;

        // Pattern matching for common questions
        if (str_contains($lowerMessage, 'apa itu robomath')) {
            return "Hai $name! 🤖 **RoboMath** adalah Aplikasi Web Berbasis AI untuk Pengembangan Pembelajaran Matematika Anak Sekolah Dasar! Di sini kamu bisa belajar penjumlahan, perkalian, pecahan, hingga logika matematika secara interaktif! 🚀🌟";
        }

        if (str_contains($lowerMessage, 'apa itu algoritma')) {
            return "Hai $name! 🤖 Algoritma matematika itu seperti **langkah-langkah resep masakan** lho! Bayangkan kamu mau menghitung perkalian 4 x 5. Kamu bisa melakukan langkah berurutan:\n\n1. 🔢 Ambil angka 5\n2. ➕ Tambahkan 5 sebanyak 4 kali (5 + 5 + 5 + 5)\n3. 🎯 Hasilnya = 20!\n\nSetiap langkah perhitungan berurutan seperti itu dinamakan **algoritma matematika**! 😊";
        }

        if (str_contains($lowerMessage, 'apa itu percabangan') || str_contains($lowerMessage, 'if')) {
            return "Hai $name! 🌟 **Percabangan (IF-ELSE)** dalam logika matematika itu seperti membuat **keputusan**!\n\nContoh:\n- 🎯 **JIKA** nilai kamu ≥ 80 → Selamat, kamu dapat lencana Emas! 🏆\n- ⭐ **JIKA TIDAK** → Tetap semangat latihan lagi ya! 💪\n\nKomputer dan kalkulator menggunakan logika percabangan ini untuk membantu menghitung! Keren kan? 😎";
        }

        if (str_contains($lowerMessage, 'apa itu pengulangan') || str_contains($lowerMessage, 'loop') || str_contains($lowerMessage, 'perkalian')) {
            return "Hai $name! 🔄 **Pengulangan** (atau **loop**) sangat penting di matematika! Perkalian sebenarnya adalah penjumlahan yang **diulang-ulang**!\n\nContoh:\n- ✖️ 3 x 4 = 4 + 4 + 4 = 12\n- 🏃 Mengulang hitungan 1 sampai 10\n\nDengan perkalian dan loop, kamu bisa menghitung banyak hal dengan sangat cepat! 🎉";
        }

        if (str_contains($lowerMessage, 'apa itu urutan') || str_contains($lowerMessage, 'sequence')) {
            return "Hai $name! 📋 **Urutan** (Sequence) adalah langkah penyelesaian soal matematika yang dilakukan **satu per satu** secara berurutan!\n\nContoh urutan berhitung:\n1. ✏️ Kerjakan operasi dalam kurung dulu\n2. ✖️ Kerjakan perkalian atau pembagian\n3. ➕ Kerjakan penjumlahan atau pengurangan\n\nUrutannya harus tepat agar hasilnya benar ya! ✨";
        }

        if (str_contains($lowerMessage, 'halo') || str_contains($lowerMessage, 'hai') || str_contains($lowerMessage, 'hello')) {
            return "Halo $name! 👋😊 Aku **RoboBot**, teman belajarmu di **RoboMath**! Aku bisa membantumu belajar tentang:\n\n➕ **Penjumlahan & Pengurangan**\n✖️ **Perkalian & Pembagian**\n🔀 **Logika Matematika & Algoritma**\n🧩 **Pola Angka & Teori Bilangan**\n\nMau belajar matematika apa hari ini? 📚";
        }

        if (str_contains($lowerMessage, 'terima kasih') || str_contains($lowerMessage, 'makasih')) {
            return "Sama-sama $name! 🤖 Senang bisa membantu! Kalau ada soal matematika yang membingungkan, tanyakan padaku kapan saja ya! Semangat belajarnya! 💪🎉";
        }

        if (str_contains($lowerMessage, 'sulit') || str_contains($lowerMessage, 'susah') || str_contains($lowerMessage, 'tidak mengerti')) {
            return "Jangan khawatir $name! 🤗 Belajar matematika memang butuh proses, tapi kamu pasti bisa!\n\n💡 Tips dari RoboBot:\n1. Coba baca penjelasan materi pelan-pelan\n2. Gunakan corak/gambar untuk membayangkan angka\n3. Mulai dari latihan soal yang paling mudah\n4. Tanya RoboBot kalau bingung!\n\nKamu anak yang pintar dan pasti bisa! ⭐🌟";
        }

        if (str_contains($lowerMessage, 'pola') || str_contains($lowerMessage, 'pattern')) {
            return "Hai $name! 🧩 **Pola Angka** adalah deretan angka yang mempunyai aturan tertentu!\n\nContoh pola matematika:\n- 🔴🔵🔴🔵🔴🔵 → selanjutnya **🔴**!\n- 2, 4, 6, 8, ... → tambah 2, selanjutnya **10**!\n- 5, 10, 15, 20, ... → kelipatan 5, selanjutnya **25**!\n\nMenemukan pola matematika bikin otak kita makin cerdas! 🕵️✨";
        }

        // Default response
        return "Hai $name! 🤖 Pertanyaan yang bagus! Aku RoboBot, asisten belajarmu di RoboMath.\n\nCoba tanyakan aku tentang:\n- 🤖 Apa itu **RoboMath**?\n- ✖️ Cara belajar **perkalian cepat**\n- 🔀 Apa itu **algoritma matematika**?\n- 🧩 Menemukan **pola angka**\n\nAtau tuliskan soal matematika yang ingin kamu pelajari! 😊✨";
    }
}
