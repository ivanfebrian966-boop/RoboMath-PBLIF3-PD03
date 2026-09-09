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
        if (str_contains($lowerMessage, 'apa itu algoritma')) {
            return "Hai $name! 🤖 Algoritma itu seperti **resep masakan** lho! Bayangkan kamu mau membuat nasi goreng. Kamu harus:\n\n1. 🍚 Siapkan nasi\n2. 🧈 Panaskan minyak\n3. 🥚 Masukkan telur\n4. 🍚 Masukkan nasi\n5. 🧂 Beri bumbu\n6. 🍽️ Sajikan!\n\nNah, langkah-langkah berurutan seperti itu namanya **algoritma**! Setiap langkah harus dilakukan dengan urutan yang benar ya! 😊";
        }

        if (str_contains($lowerMessage, 'apa itu percabangan') || str_contains($lowerMessage, 'if')) {
            return "Hai $name! 🌟 **Percabangan** itu seperti ketika kamu harus **memilih**!\n\nContoh:\n- ☔ **JIKA** hujan → bawa payung\n- ☀️ **JIKA TIDAK** hujan → pakai topi\n\nDalam algoritma, ini disebut **IF-ELSE**. Komputer juga perlu membuat pilihan, sama seperti kamu setiap hari! Keren kan? 😎";
        }

        if (str_contains($lowerMessage, 'apa itu pengulangan') || str_contains($lowerMessage, 'loop')) {
            return "Hai $name! 🔄 **Pengulangan** (atau **loop**) itu ketika kamu melakukan sesuatu **berulang-ulang**!\n\nContoh:\n- 🎵 Nyanyikan lagu \"Balonku\" sebanyak 3 kali\n- ✏️ Tulis namamu 5 kali\n- 🏃 Lari keliling lapangan 4 kali\n\nDalam komputer, pengulangan membuat pekerjaan jadi lebih mudah. Bayangkan kalau harus menulis kode yang sama 1000 kali! 😱 Dengan loop, cukup tulis sekali! 🎉";
        }

        if (str_contains($lowerMessage, 'apa itu urutan') || str_contains($lowerMessage, 'sequence')) {
            return "Hai $name! 📋 **Urutan** (Sequence) adalah langkah-langkah yang dilakukan **satu per satu** secara berurutan!\n\nContoh urutan gosok gigi:\n1. 🪥 Ambil sikat gigi\n2. 💧 Basahi sikat gigi\n3. 🧴 Beri pasta gigi\n4. 😁 Sikat gigi atas dan bawah\n5. 💦 Kumur-kumur\n6. ✨ Gigi bersih!\n\nUrutannya tidak boleh tertukar ya! Masa kumur dulu baru sikat? 😂";
        }

        if (str_contains($lowerMessage, 'halo') || str_contains($lowerMessage, 'hai') || str_contains($lowerMessage, 'hello')) {
            return "Halo $name! 👋😊 Aku **AlgoBot**, teman belajarmu di AlgoKids! Aku bisa membantu kamu belajar tentang:\n\n🔢 **Urutan** - Langkah-langkah berurutan\n🔀 **Percabangan** - Membuat pilihan\n🔄 **Pengulangan** - Mengulang kegiatan\n🧩 **Pola** - Menemukan pola\n\nMau belajar apa hari ini? 📚";
        }

        if (str_contains($lowerMessage, 'terima kasih') || str_contains($lowerMessage, 'makasih')) {
            return "Sama-sama $name! 🌈 Senang bisa membantu! Kalau ada yang mau ditanyakan lagi, jangan ragu ya! Semangat belajarnya! 💪🎉";
        }

        if (str_contains($lowerMessage, 'sulit') || str_contains($lowerMessage, 'susah') || str_contains($lowerMessage, 'tidak mengerti')) {
            return "Jangan khawatir $name! 🤗 Belajar hal baru memang kadang terasa sulit, tapi kamu pasti bisa!\n\n💡 Tips dariku:\n1. Coba baca materinya pelan-pelan\n2. Latihan soal dari yang mudah dulu\n3. Kalau bingung, tanya aku ya!\n4. Istirahat sebentar kalau capek\n\nIngat, semua ahli pernah jadi pemula! Kamu hebat karena mau terus belajar! ⭐🌟";
        }

        if (str_contains($lowerMessage, 'pola') || str_contains($lowerMessage, 'pattern')) {
            return "Hai $name! 🧩 **Pola** itu sesuatu yang berulang dengan aturan tertentu!\n\nContoh pola:\n- 🔴🔵🔴🔵🔴🔵 → selanjutnya? **🔴**!\n- 1, 3, 5, 7, ... → selanjutnya? **9**!\n- ⬆️➡️⬇️⬅️⬆️➡️ → selanjutnya? **⬇️**!\n\nMenemukan pola itu seperti jadi detektif! 🕵️ Kamu harus mengamati dengan teliti! 🔍";
        }

        // Default response
        return "Hai $name! 🤖 Pertanyaan yang bagus! Aku AlgoBot, asisten belajarmu di AlgoKids.\n\nCoba tanyakan aku tentang:\n- 📋 Apa itu **urutan** (sequence)?\n- 🔀 Apa itu **percabangan** (if-else)?\n- 🔄 Apa itu **pengulangan** (loop)?\n- 🧩 Apa itu **pola** (pattern)?\n\nAtau ceritakan kesulitanmu, aku siap membantu! 😊✨";
    }
}
