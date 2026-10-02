<?php

namespace App\AI\Providers;

use App\AI\Contracts\AIProviderInterface;
use App\AI\DTOs\AIChatResponse;
use App\Models\User;

class MockProvider implements AIProviderInterface
{
    public function chat(string $message, ?User $user = null, ?string $context = null): AIChatResponse
    {
        if ($context === 'json') {
            return $this->handleJsonMock($message);
        }

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

    protected function handleJsonMock(string $prompt): AIChatResponse
    {
        $lower = strtolower($prompt);

        if (str_contains($lower, 'analisis teks soal')) {
            $mockAnalysis = [
                'grade' => 3,
                'material' => 'Operasi Hitung & Penalaran Kontekstual',
                'question_form' => 'Connection Level',
                'question_context' => 'Contextual',
                'answer_form' => 'Closed-ended',
                'cognitive_level' => 'Applying',
                'difficulty' => 'sedang',
                'analysis_summary' => 'Soal ini menyajikan skenario kontekstual yang menguji kemampuan siswa dalam menerapkan konsep matematika ke dalam pemecahan masalah nyata.',
                'improvement_suggestion' => 'Tambahkan ilustrasi visual atau gambar konkret agar menarik minat siswa sekolah dasar.',
            ];

            return new AIChatResponse(
                text: json_encode($mockAnalysis, JSON_PRETTY_PRINT),
                provider: 'mock (offline fallback)'
            );
        }

        // Question Generator Fallback
        $mockGenerated = [
            'grade' => 4,
            'material' => 'Pecahan dan Operasi Hitung',
            'question_text' => 'Ibu membeli sebuah kue tart dan memotongnya menjadi 8 bagian sama besar. Rina memakan 2 bagian dan adiknya memakan 1 bagian. Berapa bagian sisa kue tart ibu?',
            'type' => 'pilihan_ganda',
            'options' => ['3/8 bagian', '5/8 bagian', '6/8 bagian', '7/8 bagian'],
            'correct_answer' => '5/8 bagian',
            'explanation' => 'Total potongan adalah 8/8. Kue yang dimakan = 2/8 + 1/8 = 3/8. Sisa kue = 8/8 - 3/8 = 5/8 bagian.',
            'labels' => [
                'question_form' => 'Connection Level',
                'question_context' => 'Contextual',
                'answer_form' => 'Closed-ended',
                'cognitive_level' => 'Applying',
            ],
        ];

        return new AIChatResponse(
            text: json_encode($mockGenerated, JSON_PRETTY_PRINT),
            provider: 'mock (offline fallback)'
        );
    }
}
