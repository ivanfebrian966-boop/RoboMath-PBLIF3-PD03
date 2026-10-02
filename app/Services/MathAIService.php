<?php

namespace App\Services;

use App\AI\AIManager;
use Illuminate\Support\Facades\File;

class MathAIService
{
    public function __construct(protected AIManager $aiManager) {}

    /**
     * Membaca dataset soal dari file JSON.
     */
    public function getDataset(): array
    {
        $path = database_path('data/math_questions_dataset.json');

        if (! File::exists($path)) {
            return [];
        }

        return File::json($path) ?? [];
    }

    /**
     * 1. AI CLASSIFIER / ANALYZER:
     * Menganalisis tingkat kognitif, bentuk soal, konteks, dan saran perbaikan.
     */
    public function analyzeQuestion(string $questionText, int $grade = 1): array
    {
        $examples = json_encode(array_slice($this->getDataset(), 0, 3), JSON_PRETTY_PRINT);

        $prompt = <<<PROMPT
Anda adalah Pakar Asesmen Pendidikan Matematika SD dan Taksonomi Pembelajaran.
Analisis teks soal matematika berikut yang ditujukan untuk Siswa Kelas {$grade} SD:
"{$questionText}"

Gunakan acuan taksonomi dan format standar berikut (seperti pada contoh dataset ini):
{$examples}

Berikan output HANYA dalam format JSON murni tanpa pembungkus markdown (tanpa ```json dan tanpa ```) dengan struktur berikut:
{
    "grade": {$grade},
    "material": "Nama perkiraan materi/topik matematika",
    "question_form": "Reproduction Level / Connection Level / Reflection Level",
    "question_context": "Non-contextual / Contextual / Applicative/Authentic",
    "answer_form": "Closed-ended / Open-ended",
    "cognitive_level": "Remembering / Understanding / Applying / Analyzing / Evaluating / Creating",
    "difficulty": "mudah / sedang / sulit",
    "analysis_summary": "Penjelasan detail mengapa soal ini masuk ke level kognitif dan konteks tersebut dalam bahasa Indonesia.",
    "improvement_suggestion": "Saran konkrit untuk meningkatkan kualitas pedagogis soal agar lebih menarik bagi anak SD."
}
PROMPT;

        try {
            $response = $this->aiManager->chat($prompt, null, 'json');
            $cleanJson = trim(str_replace(['```json', '```'], '', $response->text));
            $decoded = json_decode($cleanJson, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }

            return [
                'grade' => $grade,
                'material' => 'Matematika Dasar',
                'question_form' => 'Connection Level',
                'question_context' => 'Contextual',
                'answer_form' => 'Closed-ended',
                'cognitive_level' => 'Applying',
                'difficulty' => 'sedang',
                'analysis_summary' => $response->text,
                'improvement_suggestion' => 'Pastikan soal menggunakan bahasa yang konkret dan ilustratif.',
            ];
        } catch (\Throwable $e) {
            return [
                'error' => 'Gagal menganalisis soal dengan AI: '.$e->getMessage(),
            ];
        }
    }

    /**
     * 2. AI QUESTION GENERATOR:
     * Membuat soal baru sesuai parameter kelas, materi, dan tingkat kognitif.
     */
    public function generateQuestion(int $grade, string $material, string $cognitiveLevel, string $contextType = 'Contextual'): array
    {
        $prompt = <<<PROMPT
Buatkan 1 butir soal matematika yang edukatif, ramah anak, dan berkualitas tinggi untuk Siswa SD Kelas {$grade}.
Topik/Materi: "{$material}"
Tingkat Kognitif: "{$cognitiveLevel}"
Tipe Konteks: "{$contextType}"

Outputkan HANYA dalam format JSON murni tanpa pembungkus markdown (tanpa ```json dan tanpa ```) dengan struktur persis seperti ini:
{
    "grade": {$grade},
    "material": "{$material}",
    "question_text": "Teks soal cerita/pertanyaan yang menarik dan jelas untuk anak SD...",
    "type": "pilihan_ganda",
    "options": ["Pilihan A", "Pilihan B", "Pilihan C", "Pilihan D"],
    "correct_answer": "Pilihan yang benar (harus sama persis dengan salah satu elemen di options)",
    "explanation": "Langkah-langkah penyelesaian bertahap yang mudah dipahami anak-anak.",
    "labels": {
        "question_form": "Connection Level",
        "question_context": "{$contextType}",
        "answer_form": "Closed-ended",
        "cognitive_level": "{$cognitiveLevel}"
    }
}
PROMPT;

        try {
            $response = $this->aiManager->chat($prompt, null, 'json');
            $cleanJson = trim(str_replace(['```json', '```'], '', $response->text));
            $decoded = json_decode($cleanJson, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }

            return [
                'error' => 'Format balasan AI tidak valid.',
                'raw' => $response->text,
            ];
        } catch (\Throwable $e) {
            return [
                'error' => 'Gagal membuat soal dengan AI: '.$e->getMessage(),
            ];
        }
    }
}
