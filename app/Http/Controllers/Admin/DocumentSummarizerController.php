<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentSummary;
use App\Models\Quiz;
use App\Models\Topic;
use App\Services\DocumentSummarizerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentSummarizerController extends Controller
{
    public function __construct(protected DocumentSummarizerService $summarizerService) {}

    /**
     * Tampilkan halaman utama Ringkasan Dokumen AI.
     */
    public function index(Request $request)
    {
        $search = $request->query('q');
        $styleFilter = $request->query('style');

        $summariesQuery = DocumentSummary::with('user')
            ->when($search, function ($query, $term) {
                $query->where(function ($q) use ($term) {
                    $q->where('title', 'like', "%{$term}%")
                        ->orWhere('original_filename', 'like', "%{$term}%")
                        ->orWhere('summary', 'like', "%{$term}%");
                });
            })
            ->when($styleFilter, function ($query, $style) {
                $query->where('summary_style', $style);
            })
            ->latest();

        $summaries = $summariesQuery->paginate(8)->withQueryString();

        // Statistik agregat
        $totalSummaries = DocumentSummary::count();
        $totalWordsAnalyzed = DocumentSummary::sum('word_count_original');
        $avgCompression = DocumentSummary::where('word_count_original', '>', 0)
            ->get()
            ->avg('compression_ratio') ?? 0;

        return view('admin.document-summarizer.index', compact(
            'summaries',
            'totalSummaries',
            'totalWordsAnalyzed',
            'avgCompression',
            'search',
            'styleFilter'
        ));
    }

    /**
     * Endpoint pemrosesan upload dokumen dan ringkasan AI.
     */
    public function summarize(Request $request): JsonResponse
    {
        $request->validate([
            'document_file' => [
                'nullable',
                'file',
                'max:20480', // 20MB
            ],
            'direct_text' => 'nullable|string',
            'custom_title' => 'nullable|string|max:255',
            'custom_prompt' => 'nullable|string|max:2000',
            'summary_style' => 'nullable|string|max:50',
            'summary_length' => 'nullable|string|max:50',
            'focus_topic' => 'nullable|string|max:255',
            'save_history' => 'nullable',
        ]);

        if (! $request->hasFile('document_file') && empty(trim($request->input('direct_text', '')))) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan unggah berkas dokumen (PDF, DOCX, TXT, MD) atau masukkan teks langsung.',
            ], 422);
        }

        $filePath = null;
        $fileType = 'text';
        $fileSize = 0;
        $originalFilename = 'Input Teks Langsung';
        $title = $request->input('custom_title') ?: 'Latihan Soal Dokumen';
        $extractedText = '';

        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $originalFilename = $file->getClientOriginalName();
            $fileSize = $file->getSize() ?: 0;
            $fileType = strtolower($file->getClientOriginalExtension());

            // Extract text from file
            $extracted = $this->summarizerService->extractContent($file);
            $extractedText = $extracted['text'];
            $title = $request->input('custom_title') ?: $extracted['title'];

            // Optional: Store original file in private documents storage
            try {
                $filePath = $file->store('documents', 'local');
            } catch (\Throwable $e) {
                // Ignore storage failure if local disk is constrained
            }
        } else {
            $extractedText = trim($request->input('direct_text'));
            $fileSize = strlen($extractedText);
            $title = $request->input('custom_title') ?: 'Latihan Soal Materi '.now()->format('d M Y');
        }

        $wordCountOriginal = str_word_count(strip_tags($extractedText));

        if (empty($extractedText) || $wordCountOriginal < 5) {
            return response()->json([
                'success' => false,
                'message' => 'Dokumen tidak memuat teks yang cukup untuk dianalisis oleh AI. Pastikan dokumen teks dapat dibaca (bukan foto/scan gambar buram).',
            ], 422);
        }

        // Generate AI Question Summary
        $customPrompt = $request->input('custom_prompt');
        $style = $request->input('summary_style', 'kuis_latihan');
        $length = $request->input('summary_length', ! empty($customPrompt) ? 'kustom' : '5_soal');

        $aiResult = $this->summarizerService->summarize(
            text: $extractedText,
            title: $title,
            customPrompt: $customPrompt,
            style: $style,
            length: $length,
            focus: $request->input('focus_topic'),
            user: Auth::user()
        );

        $wordCountSummary = $aiResult['word_count'];
        $saveHistory = $request->boolean('save_history', true);
        $summaryModel = null;

        if ($saveHistory) {
            $summaryModel = DocumentSummary::create([
                'user_id' => Auth::id() ?? 1,
                'title' => $title,
                'original_filename' => $originalFilename,
                'file_type' => $fileType,
                'file_size' => $fileSize,
                'file_path' => $filePath,
                'extracted_text' => mb_substr($extractedText, 0, 50000), // retain preview
                'summary' => $aiResult['summary'],
                'key_points' => $aiResult['key_points'],
                'summary_style' => $style,
                'summary_length' => $length,
                'word_count_original' => $wordCountOriginal,
                'word_count_summary' => $wordCountSummary,
                'ai_provider' => $aiResult['provider'],
            ]);
        }

        $reductionRatio = $wordCountOriginal > 0
            ? max(0, min(99, (int) round((1 - ($wordCountSummary / $wordCountOriginal)) * 100)))
            : 0;

        return response()->json([
            'success' => true,
            'summary_id' => $summaryModel?->id,
            'title' => $title,
            'original_filename' => $originalFilename,
            'file_type' => strtoupper($fileType),
            'file_size_formatted' => $summaryModel ? $summaryModel->formatted_file_size : $this->formatBytes($fileSize),
            'word_count_original' => $wordCountOriginal,
            'word_count_summary' => $wordCountSummary,
            'compression_ratio' => $reductionRatio,
            'summary_markdown' => $aiResult['summary'],
            'key_points' => $aiResult['key_points'],
            'provider' => $aiResult['provider'],
            'created_at' => now()->translatedFormat('d F Y, H:i'),
        ]);
    }

    /**
     * Tampilkan detail riwayat ringkasan spesifik (JSON untuk modal).
     */
    public function show(DocumentSummary $summary): JsonResponse
    {
        return response()->json([
            'id' => $summary->id,
            'title' => $summary->title,
            'original_filename' => $summary->original_filename,
            'file_type' => strtoupper($summary->file_type),
            'file_size' => $summary->formatted_file_size,
            'word_count_original' => $summary->word_count_original,
            'word_count_summary' => $summary->word_count_summary,
            'compression_ratio' => $summary->compression_ratio,
            'summary_style' => $summary->style_label,
            'summary' => $summary->summary,
            'key_points' => $summary->key_points ?? [],
            'ai_provider' => $summary->ai_provider,
            'created_at' => $summary->created_at->translatedFormat('d F Y, H:i'),
            'user_name' => $summary->user?->name ?? 'Admin',
        ]);
    }

    /**
     * Unduh berkas ringkasan dalam format Markdown / Teks.
     */
    public function downloadText(DocumentSummary $summary): StreamedResponse
    {
        $safeTitle = preg_replace('/[^a-zA-Z0-9_-]/', '_', $summary->title);
        $fileName = "Ringkasan_RoboMath_{$safeTitle}.md";

        $content = "# Ringkasan Dokumen: {$summary->title}\n\n";
        $content .= "- **Berkas Sumber:** {$summary->original_filename} ({$summary->formatted_file_size})\n";
        $content .= "- **Tanggal Ringkas:** {$summary->created_at->format('d/m/Y H:i')}\n";
        $content .= "- **Gaya Ringkasan:** {$summary->style_label}\n";
        $content .= "- **Kata Asli:** {$summary->word_count_original} kata -> **Ringkasan:** {$summary->word_count_summary} kata (Hemat {$summary->compression_ratio}%)\n\n";
        $content .= "---\n\n";
        $content .= $summary->summary."\n\n";

        if (! empty($summary->key_points)) {
            $content .= "\n### 📌 Poin Kunci Utama:\n";
            foreach ($summary->key_points as $index => $point) {
                $num = $index + 1;
                $content .= "{$num}. {$point}\n";
            }
        }

        $content .= "\n\n---\n*Diringkas otomatis oleh Asisten AI RoboMath.*";

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $fileName, [
            'Content-Type' => 'text/markdown; charset=UTF-8',
        ]);
    }

    /**
     * Hapus riwayat ringkasan.
     */
    public function destroy(DocumentSummary $summary)
    {
        if ($summary->file_path && Storage::disk('local')->exists($summary->file_path)) {
            Storage::disk('local')->delete($summary->file_path);
        }

        $summary->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Riwayat ringkasan berhasil dihapus.',
            ]);
        }

        return back()->with('success', 'Riwayat ringkasan berhasil dihapus.');
    }

    /**
     * Parse AI-generated quiz markdown and publish questions to student quiz bank.
     */
    public function publishToStudents(Request $request): JsonResponse
    {
        $request->validate([
            'topic_id' => ['required', 'exists:topics,id'],
            'markdown' => ['required', 'string', 'min:20'],
            'difficulty' => ['nullable', 'in:mudah,sedang,sulit'],
            'points' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $topic = Topic::findOrFail($request->topic_id);
        $markdown = $request->input('markdown');
        $difficulty = $request->input('difficulty', 'sedang');
        $defaultPoints = (int) $request->input('points', 10);

        // Check if markdown is predominantly essay/uraian (no A-D options)
        $hasMcqOptions = (bool) preg_match('/(?:^|\n)\s*[-*]?\s*[A-D][\.\)]\s+/i', $markdown);
        if (! $hasMcqOptions) {
            // Attempt AI conversion from essay to multiple choice
            $aiConverted = $this->summarizerService->convertEssayToMultipleChoice($markdown, $request->user());
            if (! empty($aiConverted)) {
                $markdown = $aiConverted;
            }
        }

        $quizzes = $this->parseQuizzesFromMarkdown($markdown, $difficulty, $defaultPoints);

        if (empty($quizzes)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat mendeteksi butir soal dari dokumen. Pastikan dokumen memuat soal dan kunci jawaban yang jelas.',
            ], 422);
        }

        $created = 0;
        foreach ($quizzes as $q) {
            Quiz::create([
                'topic_id' => $topic->id,
                'lesson_id' => null,
                'type' => 'pilihan_ganda',
                'question' => $q['question'],
                'options' => $q['options'],
                'correct_answer' => $q['correct_answer'],
                'explanation' => $q['explanation'],
                'difficulty' => $q['difficulty'],
                'points' => $q['points'],
                'is_active' => true,
            ]);
            $created++;
        }

        return response()->json([
            'success' => true,
            'count' => $created,
            'topic' => $topic->title,
            'message' => "{$created} butir soal berhasil dikirim ke bank soal topik \"{$topic->title}\" dan langsung dapat dimainkan siswa!",
        ]);
    }

    /**
     * Parse structured quiz question blocks from AI markdown output.
     * Supports both multiple choice and essay/uraian questions with automatic option synthesis.
     *
     * @return array<int, array{question: string, options: array<string,string>, correct_answer: string, explanation: string, difficulty: string, points: int}>
     */
    protected function parseQuizzesFromMarkdown(string $markdown, string $difficulty, int $defaultPoints): array
    {
        $quizzes = [];

        // Match each ### Soal N block
        $pattern = '/###\s*(?:📝|🌟)?\s*Soal\s*(\d+)([\s\S]*?)(?=###\s*(?:📝|🌟)?\s*Soal\s*\d+|###\s*📌|$)/i';
        preg_match_all($pattern, $markdown, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $block = $match[2];

            // Extract question body (lines before options / blockquotes)
            $lines = explode("\n", $block);
            $bodyLines = [];
            $optionLines = [];
            $inOptions = false;

            foreach ($lines as $line) {
                $trimmed = trim($line);
                if (empty($trimmed) || preg_match('/^###/i', $trimmed)) {
                    continue;
                }
                if (preg_match('/^(?:[-*]\s*)?([A-D])[\.)\s]\s+(.+)$/i', $trimmed)) {
                    $inOptions = true;
                }
                if ($inOptions) {
                    $optionLines[] = $trimmed;
                } elseif (! preg_match('/^(?:🔑|💡|\*\*🔑|\*\*💡|Kunci|Pembahasan|>)/i', $trimmed)) {
                    $bodyLines[] = preg_replace('/^#+\s*/', '', $trimmed);
                }
            }

            $questionText = trim(preg_replace('/[*_#]/', '', implode(' ', $bodyLines)));
            if (strlen($questionText) < 5) {
                continue;
            }

            // Extract options A–D
            $options = [];
            foreach ($optionLines as $optLine) {
                if (preg_match('/^(?:[-*]\s*)?([A-D])[\.)\s]\s+(.+)$/i', $optLine, $m)) {
                    $letter = strtoupper($m[1]);
                    $text = trim(preg_replace('/[*_]/', '', $m[2]));
                    if ($text && ! isset($options[$letter])) {
                        $options[$letter] = $text;
                    }
                }
            }

            // Extract explanation
            $explanation = '';
            if (preg_match('/(?:💡\s*Pembahasan(?:\s*Seru|\s*&\s*Langkah)?|Pembahasan(?:\s*&\s*Langkah)?)\s*:\s*\*?\*?\s*([^\n\r#]+)/i', $block, $em)) {
                $explanation = trim(preg_replace('/[*_>]/', '', $em[1]));
            }

            // Extract answer key or synthesize options if missing
            $correctAnswer = '';
            if (count($options) >= 2) {
                if (preg_match('/(?:🔑\s*Kunci\s*Jawaban|Kunci\s*Jawaban)\s*:\s*\*?\*?\s*([A-D](?:\s*[-–]\s*\S+)?)/i', $block, $km)) {
                    $correctAnswer = strtoupper(trim(preg_replace('/[*_>\s]/', '', substr($km[1], 0, 1))));
                }
                if (! $correctAnswer || ! isset($options[$correctAnswer])) {
                    $correctAnswer = array_key_first($options);
                }
            } else {
                // Synthesize 4 options for essay/uraian questions
                $rawKey = '';
                if (preg_match('/(?:🔑\s*Kunci\s*Jawaban|Kunci\s*Jawaban)\s*:\s*\*?\*?\s*([^\n\r>]+)/i', $block, $km)) {
                    $rawKey = trim(preg_replace('/[*_>]/', '', $km[1]));
                }
                if (empty($rawKey)) {
                    $rawKey = 'Sesuai dengan pembahasan dan materi dokumen';
                }

                $synthesized = $this->synthesizeOptionsFromRawKey($rawKey);
                $options = $synthesized['options'];
                $correctAnswer = $synthesized['correct_answer'];
                if (empty($explanation)) {
                    $explanation = "Jawaban yang tepat adalah opsi {$correctAnswer}: {$synthesized['correct_text']}.";
                }
            }

            $quizzes[] = [
                'question' => $questionText,
                'options' => $options,
                'correct_answer' => $correctAnswer,
                'explanation' => $explanation,
                'difficulty' => $difficulty,
                'points' => $defaultPoints,
            ];
        }

        return $quizzes;
    }

    /**
     * Synthesize 4 plausible multiple-choice options from an essay answer key.
     *
     * @return array{options: array<string,string>, correct_answer: string, correct_text: string}
     */
    protected function synthesizeOptionsFromRawKey(string $rawKey): array
    {
        $cleanKey = trim(preg_replace('/\s+/', ' ', $rawKey));
        $correctText = mb_strlen($cleanKey) > 95 ? mb_substr($cleanKey, 0, 92).'...' : $cleanKey;

        $distractors = [];

        // Check for numbers in answer key to generate numerical distractors
        if (preg_match('/\b(\d+)\b/', $correctText, $numMatch)) {
            $num = (int) $numMatch[1];
            $d1 = max(1, $num - 1);
            $d2 = $num + 1;
            $d3 = $num + 2;
            $distractors[] = preg_replace('/\b'.preg_quote((string) $num, '/').'\b/', (string) $d1, $correctText, 1);
            $distractors[] = preg_replace('/\b'.preg_quote((string) $num, '/').'\b/', (string) $d2, $correctText, 1);
            $distractors[] = preg_replace('/\b'.preg_quote((string) $num, '/').'\b/', (string) $d3, $correctText, 1);
        }

        // Generic distractor pool
        $genericPool = [
            'Belum dapat dipastikan dari data dokumen materi',
            'Hanya sebagian kelompok yang ditugaskan',
            'Semua kelompok bertugas di waktu yang sama',
            'Tidak ada pasangan yang memenuhi kriteria',
            'Informasi berbeda dengan petunjuk di dokumen',
        ];

        foreach ($genericPool as $poolItem) {
            if (count($distractors) >= 3) {
                break;
            }
            if (! in_array($poolItem, $distractors) && $poolItem !== $correctText) {
                $distractors[] = $poolItem;
            }
        }

        // Randomize placement
        $letters = ['A', 'B', 'C', 'D'];
        $correctLetter = $letters[array_rand($letters)];
        $options = [];
        $dIndex = 0;

        foreach ($letters as $letter) {
            if ($letter === $correctLetter) {
                $options[$letter] = $correctText;
            } else {
                $options[$letter] = $distractors[$dIndex++] ?? 'Pilihan jawaban lainnya';
            }
        }

        return [
            'options' => $options,
            'correct_answer' => $correctLetter,
            'correct_text' => $correctText,
        ];
    }

    /**
     * Format bytes into human readable string.
     */
    protected function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1).' MB';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 1).' KB';
        }

        return $bytes.' B';
    }
}
