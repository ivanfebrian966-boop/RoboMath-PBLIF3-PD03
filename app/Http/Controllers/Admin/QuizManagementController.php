<?php

namespace App\Http\Controllers\Admin;

use App\AI\AIManager;
use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Topic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QuizManagementController extends Controller
{
    public function index(Request $request)
    {
        $topicFilter = $request->query('topic_id');
        $typeFilter = $request->query('type');

        $query = Quiz::with('topic')->latest();

        if ($topicFilter) {
            $query->where('topic_id', $topicFilter);
        }
        if ($typeFilter) {
            $query->where('type', $typeFilter);
        }

        $quizzes = $query->paginate(20);
        $topics = Topic::where('is_active', true)->orderBy('kelas_level')->orderBy('order')->get();

        return view('admin.quizzes.index', compact('quizzes', 'topics', 'topicFilter', 'typeFilter'));
    }

    public function create()
    {
        $topics = Topic::where('is_active', true)->orderBy('kelas_level')->orderBy('order')->get();
        $lessons = Lesson::with('topic')->orderBy('topic_id')->orderBy('order')->get();

        return view('admin.quizzes.create', compact('topics', 'lessons'));
    }

    public function store(Request $request)
    {
        $isEssay = in_array($request->input('type'), ['essay', 'uraian']);

        $rules = [
            'topic_id' => ['required', 'exists:topics,id'],
            'lesson_id' => ['nullable', 'exists:lessons,id'],
            'type' => ['required', 'in:pilihan_ganda,drag_drop,cerita_logika,essay'],
            'question' => ['required', 'string'],
            'correct_answer' => ['required', 'string'],
            'explanation' => ['nullable', 'string'],
            'difficulty' => ['required', 'in:mudah,sedang,sulit'],
            'points' => ['required', 'integer', 'min:1', 'max:100'],
            'is_active' => ['boolean'],
        ];

        if (! $isEssay) {
            $rules['options'] = ['required', 'array', 'min:2'];
            $rules['options.*'] = ['required', 'string'];
        } else {
            $rules['options'] = ['nullable', 'array'];
        }

        $data = $request->validate($rules);
        $data['is_active'] = $request->boolean('is_active', true);
        if ($isEssay) {
            $data['options'] = null;
        }

        Quiz::create($data);

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Soal berhasil ditambahkan! ✅');
    }

    public function edit(Quiz $quiz)
    {
        $topics = Topic::where('is_active', true)->orderBy('kelas_level')->orderBy('order')->get();
        $lessons = Lesson::with('topic')->orderBy('topic_id')->orderBy('order')->get();

        return view('admin.quizzes.edit', compact('quiz', 'topics', 'lessons'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $isEssay = in_array($request->input('type'), ['essay', 'uraian']);

        $rules = [
            'topic_id' => ['required', 'exists:topics,id'],
            'lesson_id' => ['nullable', 'exists:lessons,id'],
            'type' => ['required', 'in:pilihan_ganda,drag_drop,cerita_logika,essay'],
            'question' => ['required', 'string'],
            'correct_answer' => ['required', 'string'],
            'explanation' => ['nullable', 'string'],
            'difficulty' => ['required', 'in:mudah,sedang,sulit'],
            'points' => ['required', 'integer', 'min:1', 'max:100'],
            'is_active' => ['boolean'],
        ];

        if (! $isEssay) {
            $rules['options'] = ['required', 'array', 'min:2'];
            $rules['options.*'] = ['required', 'string'];
        } else {
            $rules['options'] = ['nullable', 'array'];
        }

        $data = $request->validate($rules);
        $data['is_active'] = $request->boolean('is_active', true);
        if ($isEssay) {
            $data['options'] = null;
        }

        $quiz->update($data);

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Soal berhasil diperbarui! ✅');
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Soal berhasil dihapus.');
    }

    /**
     * Generate soal matematika secara otomatis menggunakan AI (Essay atau Pilihan Ganda).
     */
    public function generateAi(Request $request, AIManager $aiManager): JsonResponse
    {
        $validated = $request->validate([
            'topic_id' => ['required', 'exists:topics,id'],
            'type' => ['required', 'in:essay,pilihan_ganda,campuran'],
            'count' => ['required', 'integer', 'min:1', 'max:10'],
            'difficulty' => ['required', 'in:mudah,sedang,sulit'],
            'prompt' => ['nullable', 'string', 'max:500'],
            'auto_save' => ['nullable', 'boolean'],
        ]);

        $topic = Topic::findOrFail($validated['topic_id']);
        $count = (int) $validated['count'];
        $type = $validated['type'];
        $difficulty = $validated['difficulty'];
        $customPrompt = trim($validated['prompt'] ?? '');

        $typeInstruction = match ($type) {
            'essay' => 'SELURUHNYA berupa soal ESSAY / URAIAN penalaran matematika ramah anak SD. Tanpa opsi A, B, C, D.',
            'pilihan_ganda' => 'SELURUHNYA berupa soal PILIHAN GANDA 4 opsi (A, B, C, D).',
            default => 'Kombinasi soal Essay dan Pilihan Ganda secara seimbang.',
        };

        $systemPrompt = <<<PROMPT
Anda adalah Pakar Kurikulum Matematika SD di RoboMath.
Tugas Anda: Buat {$count} butir soal latihan matematika SD untuk:
- Topik: "{$topic->title}" (Kelas {$topic->kelas_level} SD)
- Tipe Soal: {$typeInstruction}
- Tingkat Kesulitan: {$difficulty}
- Catatan Khusus Pengajar: "{$customPrompt}"

WAJIB hasilkan HANYA JSON array murni tanpa pembuka/penutup basa-basi, dengan format:
[
  {
    "type": "essay" atau "pilihan_ganda",
    "question": "Bunyi pertanyaan kontekstual dan menarik untuk anak SD...",
    "options": null (jika essay) atau {"A": "pilihan A", "B": "pilihan B", "C": "pilihan C", "D": "pilihan D"},
    "correct_answer": "Model jawaban lengkap jika essay, atau huruf 'A'/'B'/'C'/'D' jika pilihan ganda",
    "explanation": "Penjelasan langkah penyelesaian yang ramah anak",
    "difficulty": "{$difficulty}",
    "points": 10
  }
]
PROMPT;

        $questions = [];
        try {
            $aiResponse = $aiManager->chat($systemPrompt, $request->user(), 'json');
            $rawText = trim($aiResponse->text);
            $cleanJson = preg_replace('/^```(?:json)?\s*/i', '', $rawText);
            $cleanJson = preg_replace('/\s*```$/', '', $cleanJson);
            $parsed = json_decode(trim($cleanJson), true);

            if (is_array($parsed)) {
                if (isset($parsed['questions']) && is_array($parsed['questions'])) {
                    $parsed = $parsed['questions'];
                }
                foreach ($parsed as $item) {
                    if (! empty($item['question']) && ! empty($item['correct_answer'])) {
                        $qType = ($item['type'] ?? '') === 'pilihan_ganda' ? 'pilihan_ganda' : 'essay';
                        $questions[] = [
                            'topic_id' => $topic->id,
                            'type' => $qType,
                            'question' => $item['question'],
                            'options' => $qType === 'pilihan_ganda' && is_array($item['options'] ?? null) ? $item['options'] : null,
                            'correct_answer' => $item['correct_answer'],
                            'explanation' => $item['explanation'] ?? '',
                            'difficulty' => in_array($item['difficulty'] ?? '', ['mudah', 'sedang', 'sulit']) ? $item['difficulty'] : $difficulty,
                            'points' => (int) ($item['points'] ?? 10),
                            'is_active' => true,
                        ];
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning('AI Quiz Generation error: '.$e->getMessage());
        }

        // Fallback jika provider AI offline atau format JSON tidak sesuai
        if (empty($questions)) {
            $questions = $this->generateFallbackQuestions($topic, $type, $count, $difficulty);
        }

        // Jika opsi auto_save dicentang, langsung simpan ke database
        $savedCount = 0;
        if ($request->boolean('auto_save', false)) {
            foreach ($questions as $q) {
                Quiz::create($q);
                $savedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'topic' => $topic->title,
            'auto_saved' => $request->boolean('auto_save', false),
            'saved_count' => $savedCount,
            'count' => count($questions),
            'questions' => $questions,
            'message' => $request->boolean('auto_save', false)
                ? "Berhasil men-generate {$savedCount} butir soal dan langsung disimpan ke topik \"{$topic->title}\"! 🎉"
                : 'Berhasil men-generate '.count($questions)." butir soal untuk topik \"{$topic->title}\". Silakan tinjau sebelum disimpan.",
        ]);
    }

    /**
     * Simpan kumpulan butir soal hasil generate ke bank soal.
     */
    public function saveBatch(Request $request): JsonResponse
    {
        $request->validate([
            'topic_id' => ['required', 'exists:topics,id'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.question' => ['required', 'string'],
            'questions.*.type' => ['required', 'in:essay,pilihan_ganda,drag_drop,cerita_logika'],
            'questions.*.correct_answer' => ['required', 'string'],
        ]);

        $topic = Topic::findOrFail($request->input('topic_id'));
        $created = 0;

        foreach ($request->input('questions') as $item) {
            $isEssay = in_array($item['type'] ?? '', ['essay', 'uraian']);
            Quiz::create([
                'topic_id' => $topic->id,
                'lesson_id' => null,
                'type' => $isEssay ? 'essay' : ($item['type'] ?? 'pilihan_ganda'),
                'question' => $item['question'],
                'options' => (! $isEssay && is_array($item['options'] ?? null)) ? $item['options'] : null,
                'correct_answer' => $item['correct_answer'],
                'explanation' => $item['explanation'] ?? '',
                'difficulty' => in_array($item['difficulty'] ?? '', ['mudah', 'sedang', 'sulit']) ? $item['difficulty'] : 'sedang',
                'points' => (int) ($item['points'] ?? 10),
                'is_active' => true,
            ]);
            $created++;
        }

        return response()->json([
            'success' => true,
            'count' => $created,
            'message' => "{$created} butir soal berhasil disimpan ke bank soal topik \"{$topic->title}\"! 🎉",
        ]);
    }

    /**
     * Generator cadangan berbasis tematik jika AI API sedang offline.
     *
     * @return list<array<string, mixed>>
     */
    protected function generateFallbackQuestions(Topic $topic, string $type, int $count, string $difficulty): array
    {
        $questions = [];
        $kelas = $topic->kelas_level ?? 3;
        $title = $topic->title ?? 'Matematika';

        $essayTemplates = [
            [
                'question' => 'Di sebuah perpustakaan mini SD, terdapat 45 buku cerita dan 30 buku ensiklopedia. Datang kiriman baru sebanyak 25 buku dongeng. Berapa total buku sekarang? Jelaskan cara kamu menghitungnya!',
                'correct_answer' => 'Total buku adalah 100 buku. Langkah: 45 + 30 + 25 = 100 buku.',
                'explanation' => 'Kita jumlahkan buku cerita dan ensiklopedia terlebih dahulu: 45 + 30 = 75. Kemudian ditambahkan kiriman baru 25 buku: 75 + 25 = 100 buku.',
            ],
            [
                'question' => 'Ani memiliki sebatang cokelat yang dipotong menjadi 10 bagian sama besar. Ani memberikan 4 bagian kepada adiknya dan 2 bagian kepada kakaknya. Berapa bagian cokelat yang masih tersisa pada Ani? Tuliskan langkah pengurangan pecahannya!',
                'correct_answer' => 'Sisa cokelat Ani adalah 4/10 bagian (atau 2/5). Langkah: 10/10 - 4/10 - 2/10 = 4/10.',
                'explanation' => 'Total potongan cokelat utuh adalah 10/10. Bagian yang diberikan = 4/10 + 2/10 = 6/10. Maka sisanya adalah 10/10 - 6/10 = 4/10 bagian.',
            ],
            [
                'question' => 'Pak Budi memanen 6 kantong apel dari kebunnya. Setiap kantong berisi 8 buah apel manis. Semua apel tersebut akan dibagikan secara adil kepada 4 tetangganya. Berapa apel yang didapat setiap tetangga? Tuliskan cara perhitungannya!',
                'correct_answer' => 'Setiap tetangga mendapatkan 12 buah apel. Langkah: (6 x 8) : 4 = 48 : 4 = 12 apel.',
                'explanation' => 'Pertama kalikan jumlah kantong dengan isi setiap kantong untuk mengetahui total apel: 6 x 8 = 48 apel. Lalu bagi secara adil untuk 4 tetangga: 48 : 4 = 12 apel.',
            ],
            [
                'question' => 'Dono membeli 3 bungkus permen rasa jeruk seharga Rp5.000 per bungkus dan 2 kotak susu seharga Rp7.000 per kotak. Dono membayar dengan selembar uang Rp50.000. Berapa uang kembalian yang harus diterima Dono? Jelaskan langkahnya!',
                'correct_answer' => 'Uang kembalian Dono adalah Rp21.000. Langkah: Total belanja = (3 x 5.000) + (2 x 7.000) = 15.000 + 14.000 = 29.000. Kembalian = 50.000 - 29.000 = 21.000.',
                'explanation' => 'Hitung harga permen: 3 x 5.000 = 15.000. Hitung harga susu: 2 x 7.000 = 14.000. Total belanja = 29.000. Kembalian = 50.000 - 29.000 = 21.000 rupiah.',
            ],
            [
                'question' => 'Dalam sebuah pertandingan cerdas cermat, regu Merah berhasil menjawab 7 soal benar (+10 poin per soal) dan 2 soal salah (-5 poin per soal). Berapakah skor total regu Merah? Tuliskan uraian langkah hitunganmu!',
                'correct_answer' => 'Skor total regu Merah adalah 60 poin. Langkah: (7 x 10) - (2 x 5) = 70 - 10 = 60 poin.',
                'explanation' => 'Poin jawaban benar: 7 x 10 = 70 poin. Poin pengurangan jawaban salah: 2 x 5 = 10 poin. Total skor akhir: 70 - 10 = 60 poin.',
            ],
        ];

        $mcqTemplates = [
            [
                'question' => 'Berapakah hasil dari 25 x 4 + 50?',
                'options' => ['A' => '100', 'B' => '150', 'C' => '200', 'D' => '250'],
                'correct_answer' => 'B',
                'explanation' => 'Kerjakan perkalian terlebih dahulu: 25 x 4 = 100, lalu tambahkan 50 menjadi 150.',
            ],
            [
                'question' => 'Sebuah persegi memiliki panjang sisi 9 cm. Berapakah keliling persegi tersebut?',
                'options' => ['A' => '18 cm', 'B' => '27 cm', 'C' => '36 cm', 'D' => '81 cm'],
                'correct_answer' => 'C',
                'explanation' => 'Keliling persegi dihitung dengan 4 x sisi = 4 x 9 cm = 36 cm.',
            ],
        ];

        for ($i = 0; $i < $count; $i++) {
            $wantEssay = $type === 'essay' || ($type === 'campuran' && $i % 2 === 0);

            if ($wantEssay) {
                $tmpl = $essayTemplates[$i % count($essayTemplates)];
                $questions[] = [
                    'topic_id' => $topic->id,
                    'type' => 'essay',
                    'question' => "[Topik: {$title}] ".$tmpl['question'],
                    'options' => null,
                    'correct_answer' => $tmpl['correct_answer'],
                    'explanation' => $tmpl['explanation'],
                    'difficulty' => $difficulty,
                    'points' => 10,
                    'is_active' => true,
                ];
            } else {
                $tmpl = $mcqTemplates[$i % count($mcqTemplates)];
                $questions[] = [
                    'topic_id' => $topic->id,
                    'type' => 'pilihan_ganda',
                    'question' => "[Topik: {$title}] ".$tmpl['question'],
                    'options' => $tmpl['options'],
                    'correct_answer' => $tmpl['correct_answer'],
                    'explanation' => $tmpl['explanation'],
                    'difficulty' => $difficulty,
                    'points' => 10,
                    'is_active' => true,
                ];
            }
        }

        return $questions;
    }
}
