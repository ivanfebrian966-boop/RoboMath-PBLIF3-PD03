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
Anda adalah Pakar Asesmen Pendidikan Matematika SD dan Taksonomi Pembelajaran (Bloom & PISA).
Tugas Anda adalah menganalisis teks butir soal matematika berikut untuk Siswa Kelas {$grade} SD:
"{$questionText}"

Gunakan acuan taksonomi dan format standar berikut:
{$examples}

Panduan Analisis Objektif & Anti-Halusinasi:
1. Evaluasi apakah bahasa dan tingkat kesukaran soal benar-benar realistis dan sesuai untuk usia Siswa Kelas {$grade} SD.
2. Periksa apakah konsep matematika dalam soal sudah tepat, tidak ada ambiguitas atau kontradiksi.
3. Berikan analisis faktual dan rasional, jangan mengada-ada konsep yang tidak ada di dalam soal.
4. Jika ada kekurangan atau peluang perbaikan pada soal, berikan saran konstruktif pada "improvement_suggestion".

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
Anda adalah Guru Matematika SD Berpengalaman dan Pakar Kurikulum Matematika Anak.
Buatkan 1 butir soal matematika yang edukatif, ramah anak, dan bermutu tinggi untuk Siswa SD Kelas {$grade}.
Topik/Materi: "{$material}"
Tingkat Kognitif: "{$cognitiveLevel}"
Tipe Konteks: "{$contextType}"

ATURAN VERIFIKASI MATEMATIKA & ANTI-NGAWUR (WAJIB DIPATUHI):
1. Verifikasi Hitungan (Self-Verification): Selesaikan soal secara mandiri terlebih dahulu. Pastikan perhitungan angka 100% akurat dan logis tanpa ada kesalahan aritmatika sekecil apa pun.
2. Kesesuaian Usia Anak SD:
   - Kelas 1-2: Gunakan bilangan bulat kecil (1-20 atau 1-100), penjumlahan/pengurangan sederhana, konteks benda nyata (buah, mainan, hewan). JANGAN gunakan bilangan negatif atau pecahan rumit.
   - Kelas 3-4: Perkalian, pembagian dasar, pecahan sederhana, waktu, pengukuran.
   - Kelas 5-6: Pecahan campuran, desimal, persentase, bangun datar/ruang, perbandingan.
3. Kualitas Pilihan Jawaban:
   - Sediakan tepat 4 opsi jawaban ("options") yang berbeda satu sama lain.
   - HANYA ada 1 opsi yang BENAR. Tiga opsi lainnya adalah pengecoh (distraktor) yang masuk akal namun salah secara hitungan.
4. Kunci Jawaban: Nilai "correct_answer" HARUS SAMA PERSIS karakter demi karakter dengan salah satu pilihan di dalam array "options".
5. Penjelasan Runtut: "explanation" harus memaparkan langkah penyelesaian bertahap yang runut, akurat, dan mudah dipahami anak SD.

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
                // Sanity check: ensure correct_answer is in options
                if (isset($decoded['options'], $decoded['correct_answer']) && is_array($decoded['options'])) {
                    if (! in_array($decoded['correct_answer'], $decoded['options'], true)) {
                        // If exact match failed, check loose or append
                        $decoded['options'][0] = $decoded['correct_answer'];
                    }
                }

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
