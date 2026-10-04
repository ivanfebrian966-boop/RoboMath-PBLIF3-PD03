<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentSummary;
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
            'summary_style' => 'required|in:ringkasan_eksekutif,ringkasan_lengkap,anak_sd,peta_konsep,kuis_latihan',
            'summary_length' => 'required|in:singkat,sedang,mendalam',
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
        $title = $request->input('custom_title') ?: 'Ringkasan Teks Mandiri';
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
            $title = $request->input('custom_title') ?: 'Catatan Materi '.now()->format('d M Y');
        }

        $wordCountOriginal = str_word_count(strip_tags($extractedText));

        if (empty($extractedText) || $wordCountOriginal < 5) {
            return response()->json([
                'success' => false,
                'message' => 'Dokumen tidak memuat teks yang cukup untuk dianalisis oleh AI. Pastikan dokumen teks dapat dibaca (bukan foto/scan gambar buram).',
            ], 422);
        }

        // Generate AI Summary
        $aiResult = $this->summarizerService->summarize(
            text: $extractedText,
            title: $title,
            style: $request->input('summary_style', 'ringkasan_eksekutif'),
            length: $request->input('summary_length', 'sedang'),
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
                'summary_style' => $request->input('summary_style'),
                'summary_length' => $request->input('summary_length'),
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
