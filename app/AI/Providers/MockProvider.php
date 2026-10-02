<?php

namespace App\AI\Providers;

use App\AI\Contracts\AIProviderInterface;
use App\AI\DTOs\AIChatResponse;
use App\Models\User;

class MockProvider implements AIProviderInterface
{
    public function chat(string $message, ?User $user = null, ?string $context = null): AIChatResponse
    {
        $lowerMessage = strtolower($message);
        $name = $user?->name ?? 'Teman';

        if (str_contains($lowerMessage, 'apa itu robomath')) {
            $reply = "Hai $name! 🤖 **RoboMath** adalah Aplikasi Web Berbasis AI untuk Pembelajaran Matematika Anak SD! Di sini kamu bisa belajar operasi hitung, pecahan, hingga logika matematika interaktif! 🚀🌟";
        } elseif (str_contains($lowerMessage, 'apa itu algoritma')) {
            $reply = "Hai $name! 🤖 Algoritma itu seperti **langkah resep masakan**! Contoh: 4 x 5 = ambil 5, tambah 5 sebanyak 4 kali (5+5+5+5) = 20! Langkah berurutan itu dinamakan **algoritma**! 😊";
        } elseif (str_contains($lowerMessage, 'halo') || str_contains($lowerMessage, 'hai')) {
            $reply = "Halo $name! 👋😊 Aku **RoboBot**, teman belajarmu di RoboMath! Mau belajar topik matematika atau logika apa hari ini? 📚";
        } else {
            $reply = "Hai $name! 🤖 Aku RoboBot. Ada soal matematika atau materi yang ingin kamu tanyakan dan kita pelajari bersama? ✨";
        }

        return new AIChatResponse(
            text: $reply,
            provider: 'mock'
        );
    }
}
