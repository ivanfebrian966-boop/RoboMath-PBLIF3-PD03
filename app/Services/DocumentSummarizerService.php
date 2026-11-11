<?php

namespace App\Services;

use App\AI\AIManager;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Parser;
use ZipArchive;

class DocumentSummarizerService
{
    public function __construct(
        protected AIManager $aiManager,
        protected MathDatasetService $mathDatasetService,
    ) {}

    /**
     * @return list<string>
     */
    public function chunkText(string $text, int $maxCharacters = 3000): array
    {
        $words = preg_split('/\s+/u', trim($text), -1, PREG_SPLIT_NO_EMPTY);
        $chunks = [];
        $chunk = '';

        foreach ($words ?: [] as $word) {
            if ($chunk !== '' && mb_strlen($chunk) + mb_strlen($word) + 1 > $maxCharacters) {
                $chunks[] = $chunk;
                $chunk = '';
            }

            $chunk = $chunk === '' ? $word : $chunk.' '.$word;
        }

        if ($chunk !== '') {
            $chunks[] = $chunk;
        }

        return $chunks;
    }

    /**
     * Extract text, metadata, and properties from an uploaded file.
     *
     * @return array{
     *     text: string,
     *     title: string,
     *     file_type: string,
     *     file_size: int,
     *     word_count: int,
     *     raw_base64: ?string,
     *     mime_type: string
     * }
     */
    public function extractContent(UploadedFile $file): array
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType() ?: 'application/octet-stream';
        $originalName = $file->getClientOriginalName();
        $title = pathinfo($originalName, PATHINFO_FILENAME);
        $size = $file->getSize() ?: 0;
        $path = $file->getRealPath();

        $extractedText = match ($extension) {
            'txt', 'md', 'markdown', 'csv', 'json', 'log' => $this->extractFromPlainText($path),
            'docx' => $this->extractFromDocx($path),
            'pdf' => $this->extractFromPdf($path),
            default => $this->extractFromPlainText($path),
        };

        // Fallback title formatting
        $cleanTitle = ucwords(str_replace(['-', '_'], ' ', $title));
        $extractedText = trim($extractedText);

        // Sanitize extracted text to valid UTF-8 (PDF parsers can return invalid byte sequences)
        if ($extractedText !== '') {
            $extractedText = @iconv('UTF-8', 'UTF-8//IGNORE', $extractedText) ?: mb_convert_encoding($extractedText, 'UTF-8', 'UTF-8');
            $extractedText = trim($extractedText);
        }

        // Validate extracted text quality; clear garbled/unreadable text so the caller
        // can surface a meaningful error instead of generating nonsensical quiz questions.
        if ($extractedText !== '' && ! $this->isTextQualityAcceptable($extractedText)) {
            Log::warning('DocumentSummarizer: extracted text quality too low (likely garbled PDF encoding), clearing text.', [
                'file' => $originalName,
                'sample' => mb_substr($extractedText, 0, 200),
            ]);
            $extractedText = '';
        }

        $wordCount = $extractedText !== '' ? str_word_count(strip_tags($extractedText)) : 0;

        return [
            'text' => $extractedText,
            'title' => $cleanTitle ?: 'Dokumen Tanpa Judul',
            'file_type' => $extension ?: 'dokumen',
            'file_size' => $size,
            'word_count' => $wordCount,
            'raw_base64' => $size <= 8 * 1024 * 1024 ? base64_encode(file_get_contents($path)) : null,
            'mime_type' => $mimeType,
        ];
    }

    /**
     * Determine whether extracted text is readable and usable for AI question generation.
     *
     * Garbled PDF output (from non-standard font encoding) typically consists mostly of
     * characters outside the printable Latin/Indonesian character range. We accept the text
     * only when at least 60 % of its characters are printable ASCII or common Indonesian
     * Unicode letters, digits, punctuation, or whitespace.
     */
    protected function isTextQualityAcceptable(string $text): bool
    {
        // Ensure clean UTF-8 before evaluating regex
        $clean = @iconv('UTF-8', 'UTF-8//IGNORE', $text) ?: $text;
        $totalChars = mb_strlen($clean, 'UTF-8');
        if ($totalChars === 0) {
            return false;
        }

        // Count characters that are clearly "normal" (ASCII printable + common UTF-8 Latin)
        $normalCount = preg_match_all(
            '/[\x20-\x7E\xC0-\xFF\s]/u',
            $clean,
            $dummy
        );

        if ($normalCount === false) {
            // Fallback without /u if still has UTF-8 encoding issue
            $normalCount = preg_match_all('/[\x20-\x7E\s]/', $clean, $dummy);
        }

        $ratio = ($normalCount ?: 0) / $totalChars;

        // Also require at least a handful of Indonesian/Latin alphabetic words
        $wordMatches = preg_match_all('/\b[a-zA-Z]{3,}\b/', $clean, $dummy2);

        return $ratio >= 0.50 && ($wordMatches ?: 0) >= 5;
    }

    /**
     * Extract text from plain text files.
     */
    protected function extractFromPlainText(string $filePath): string
    {
        if (! file_exists($filePath)) {
            return '';
        }

        $content = file_get_contents($filePath);
        if ($content === false) {
            return '';
        }

        // Convert encoding to UTF-8 if necessary
        $encoding = mb_detect_encoding($content, ['UTF-8', 'ISO-8859-1', 'Windows-1252', 'ASCII'], true);
        if ($encoding && $encoding !== 'UTF-8') {
            $content = mb_convert_encoding($content, 'UTF-8', $encoding);
        }

        return $content;
    }

    /**
     * Extract text from Word DOCX archive.
     */
    protected function extractFromDocx(string $filePath): string
    {
        if (! class_exists(ZipArchive::class)) {
            return '';
        }

        $zip = new ZipArchive;
        if ($zip->open($filePath) !== true) {
            return '';
        }

        $xmlIndex = $zip->locateName('word/document.xml');
        if ($xmlIndex === false) {
            $zip->close();

            return '';
        }

        $xmlData = $zip->getFromIndex($xmlIndex);
        $zip->close();

        if (! $xmlData) {
            return '';
        }

        // Add line breaks at paragraph and break tags
        $xmlData = str_replace(['</w:p>', '<w:br/>', '<w:br >'], "\n", $xmlData);
        $text = strip_tags($xmlData);

        // Normalize multiple blank lines
        return preg_replace("/\n{3,}/", "\n\n", trim($text));
    }

    /**
     * Extract clean text from PDF documents using Smalot Parser or stream decompression fallback.
     */
    protected function extractFromPdf(string $filePath): string
    {
        if (! file_exists($filePath)) {
            return '';
        }

        // Primary: use Smalot PDF Parser for full font mapping and decompression
        if (class_exists(Parser::class)) {
            try {
                @ini_set('memory_limit', '1024M');
                $parser = new Parser;
                $pdf = $parser->parseFile($filePath);
                $text = $pdf->getText();

                if (empty(trim($text))) {
                    $pages = $pdf->getPages();
                    $pageTexts = [];
                    foreach ($pages as $page) {
                        $pText = trim($page->getText());
                        if ($pText !== '') {
                            $pageTexts[] = $pText;
                        }
                    }
                    $text = implode("\n\n", $pageTexts);
                }

                if (! empty(trim($text))) {
                    return trim($text);
                }
            } catch (\Throwable $e) {
                Log::warning('Smalot PDF Parser fallback triggered: '.$e->getMessage());
            }
        }

        $content = file_get_contents($filePath);
        if ($content === false || strlen($content) === 0) {
            return '';
        }

        $extracted = '';

        // Fallback: Match all stream ... endstream blocks
        if (preg_match_all('/stream[\r\n]+(.*?)[\r\n]+endstream/s', $content, $matches)) {
            foreach ($matches[1] as $stream) {
                // Try decompressing flate stream
                $decompressed = @gzuncompress($stream);
                if ($decompressed === false) {
                    $decompressed = @gzinflate($stream);
                }
                if ($decompressed === false) {
                    $decompressed = $stream;
                }

                // Look for text blocks BT ... ET
                if (preg_match_all('/BT[\r\n\s]+(.*?)[\r\n\s]+ET/s', $decompressed, $textBlocks)) {
                    foreach ($textBlocks[1] as $block) {
                        // Extract text from (text) Tj
                        if (preg_match_all('/\((.*?)\)\s*Tj/s', $block, $tjMatches)) {
                            foreach ($tjMatches[1] as $t) {
                                $extracted .= $this->decodePdfString($t).' ';
                            }
                            $extracted .= "\n";
                        }

                        // Extract text from [(t1)(t2)] TJ
                        if (preg_match_all('/\[(.*?)\]\s*TJ/s', $block, $tjArrayMatches)) {
                            foreach ($tjArrayMatches[1] as $arrayContent) {
                                if (preg_match_all('/\((.*?)\)/s', $arrayContent, $subStrings)) {
                                    foreach ($subStrings[1] as $sub) {
                                        $extracted .= $this->decodePdfString($sub);
                                    }
                                    $extracted .= ' ';
                                }
                            }
                            $extracted .= "\n";
                        }
                    }
                }
            }
        }

        return trim(preg_replace('/\s+/', ' ', $extracted));
    }

    /**
     * Decode PDF literal escaped characters.
     */
    protected function decodePdfString(string $str): string
    {
        $search = ['\\\\', '\(', '\)', '\n', '\r', '\t', '\b', '\f'];
        $replace = ['\\', '(', ')', "\n", "\r", "\t", "\x08", "\x0C"];
        $str = str_replace($search, $replace, $str);

        // Octal escape sequences \ddd
        return preg_replace_callback('/\\\\([0-7]{1,3})/', function ($m) {
            return chr(octdec($m[1]));
        }, $str);
    }

    /**
     * Generate an AI question set / summary for the given text.
     *
     * @return array{
     *     summary: string,
     *     key_points: list<string>,
     *     provider: string,
     *     tokens_used: ?int,
     *     word_count: int,
     *     rejected: bool
     * }
     */
    public function summarize(
        string $text,
        string $title,
        ?string $customPrompt = null,
        string $style = 'kuis_latihan',
        string $length = 'sedang',
        ?string $focus = null,
        ?User $user = null,
        ?string $rawBase64 = null,
        ?string $mimeType = null
    ): array {
        $text = trim($text);
        $hasVisionInput = ! empty($rawBase64) && ! empty($mimeType);

        if (empty($text) && ! $hasVisionInput) {
            return [
                'summary' => "## ⚠️ Dokumen Tidak Dapat Dibaca\n\n"
                    ."Maaf, teks dalam dokumen ini **tidak dapat diekstrak** karena menggunakan encoding font khusus atau merupakan hasil pindaian gambar (scanned PDF) yang tidak memiliki lapisan teks.\n\n"
                    ."**Solusi yang disarankan:**\n"
                    ."- Gunakan PDF yang memiliki teks asli (bukan hasil scan).\n"
                    ."- Konversi dokumen ke format **DOCX** atau **TXT** terlebih dahulu.\n"
                    .'- Pastikan file tidak diproteksi atau terenkripsi.',
                'key_points' => ['Dokumen tidak dapat diekstrak — gunakan PDF berteks atau format DOCX/TXT.'],
                'provider' => 'system',
                'tokens_used' => 0,
                'word_count' => 0,
                'rejected' => false,
            ];
        }

        // ── Math-only guardrail ──────────────────────────────────────────────
        // For text-based input, run a fast keyword check first. Skip for pure
        // vision input (we can't read the image content here).
        if (! $hasVisionInput && ! empty($text)) {
            $sampleText = mb_substr($text, 0, 2000).' '.$title;
            if (! $this->mathDatasetService->isMathRelated($sampleText)) {
                $rejectionMessage = $this->buildRejectionMessage($title);

                return [
                    'summary' => $rejectionMessage,
                    'key_points' => ['Dokumen ini tidak berkaitan dengan matematika SD — RoboMath hanya memproses materi matematika.'],
                    'provider' => 'system',
                    'tokens_used' => 0,
                    'word_count' => str_word_count(strip_tags($rejectionMessage)),
                    'rejected' => true,
                ];
            }
        }
        // ────────────────────────────────────────────────────────────────────

        // Limit input size to prevent token exhaustion (approx ~25,000 chars)
        $truncatedText = mb_substr($text, 0, 25000);
        if (mb_strlen($text) > 25000) {
            $truncatedText .= "\n\n[... teks dokumen dipotong untuk batas analisis AI ...]";
        }

        $hasCustomPrompt = ! empty(trim((string) $customPrompt));
        $isEssayRequest = $hasCustomPrompt && (bool) preg_match('/(essay|esai|uraian|isian|terbuka|jawaban\s*singkat)/i', (string) $customPrompt);

        // Detect requested question count if specified by user (e.g. "6 soal essay", "10 butir", etc.)
        $requestedCount = null;
        if ($hasCustomPrompt && preg_match('/(\d+)\s*(?:soal|butir|nomor|buah|pertanyaan)/i', (string) $customPrompt, $countMatches)) {
            $requestedCount = (int) $countMatches[1];
        }

        if ($isEssayRequest) {
            $countText = $requestedCount ? "TEPAT {$requestedCount}" : 'sejumlah yang diminta';
            $instruction = "PERMINTAAN KHUSUS GURU/PENGGUNA (SOAL ESSAY / URAIAN MURNI):\n\"".trim((string) $customPrompt)."\"\n".
                "- Target Jumlah: Buat {$countText} butir soal.\n".
                "- WAJIB 100% SEMUA BUTIR SOAL MURNI BERBENTUK ESSAY / URAIAN DARI SOAL NOMOR 1 SAMPAI SOAL TERAKHIR.\n".
                "- DILARANG KERAS MEMBUAT SOAL PILIHAN GANDA (DILARANG MENYERTAKAN OPSI A, B, C, D) PADA SOAL MANAPUN.\n".
                '- Setiap butir soal harus berupa pertanyaan uraian pemahaman konsep / penalaran / soal cerita yang memerlukan jawaban deskriptif, dilengkapi kunci jawaban model dan pembahasan langkah penyelesaian.';
            $formatGuide = <<<'FMT'
FORMAT SETIAP BUTIR SOAL ESSAY / URAIAN (DILARANG MEMBUAT PILIHAN GANDA A, B, C, D):
### 📝 Soal [Nomor]
[Tuliskan pertanyaan essay/uraian yang jelas, kontekstual, dan ramah anak SD tanpa ada pilihan A, B, C, D. Tuliskan pecahan secara natural dan sederhana seperti "1/2" atau "setengah", DILARANG menggunakan kode LaTeX seperti $\frac{1}{2}$]

> **🔑 Kunci Jawaban:** [Model jawaban lengkap yang diharapkan. Gunakan format pecahan biasa seperti 1/2]
> **💡 Pembahasan & Langkah:** [Penjelasan langkah-langkah penyelesaian terinci dengan bahasa ramah anak]
FMT;
        } elseif ($hasCustomPrompt) {
            $countText = $requestedCount ? "TEPAT {$requestedCount}" : 'sejumlah yang diminta';
            $instruction = "PERMINTAAN KHUSUS GURU/PENGGUNA:\n\"".trim((string) $customPrompt)."\"\n".
                "- Target Jumlah: Buat {$countText} butir soal.\n".
                '- PENTING: Ikuti jenis soal persis sesuai permintaan di atas. Jika meminta essay/uraian, SELURUH soal harus murni essay tanpa opsi A, B, C, D.';
            $formatGuide = <<<'FMT'
PANDUAN FORMAT BUTIR SOAL:
- Jika meminta Pilihan Ganda: Sertakan opsi (- A., - B., - C., - D.), kunci jawaban, dan pembahasan.
- Jika meminta Essay/Uraian/Isian: Tuliskan pertanyaan uraian, kunci jawaban model, dan pembahasan (TANPA opsi A, B, C, D).
FMT;
        } else {
            $instruction = 'Susun TEPAT 5 butir soal pilihan ganda (A, B, C, D) yang seru, edukatif, dan kontekstual merangkum materi dokumen.';
            $formatGuide = <<<'FMT'
FORMAT SETIAP BUTIR SOAL (Pilihan Ganda):
### 📝 Soal [Nomor]
[Tuliskan pertanyaan dengan jelas dan ramah anak. Tuliskan pecahan secara natural dan sederhana seperti "1/2" atau "1/3", DILARANG menggunakan kode LaTeX seperti $\frac{1}{2}$]
- A. [Pilihan A]
- B. [Pilihan B]
- C. [Pilihan C]
- D. [Pilihan D]

> **🔑 Kunci Jawaban:** [Pilihan benar]
> **💡 Pembahasan Seru:** [Penjelasan cara menjawab dengan bahasa sederhana yang mudah dimengerti anak]
FMT;
        }

        if (! empty($focus) && ! $hasCustomPrompt) {
            $instruction .= "\nFokus Topik: Berikan penekanan utama pada materi: \"{$focus}\".";
        }

        $documentContentContext = ! empty($truncatedText)
            ? "ISI DOKUMEN MATERI (HASIL EKSTRAKSI TEKS):\n---\n{$truncatedText}\n---"
            : 'Dokumen ini dikirimkan langsung sebagai berkas PDF/Gambar visual (terlampir). Analisislah seluruh teks, ilustrasi, diagram, dan soal matematika yang ada di dalam gambar/dokumen terlampir.';

        $prompt = <<<EOT
Anda adalah Guru Matematika & Sahabat Belajar Anak SD di RoboMath.
Tugas Anda: Meringkas dokumen berjudul "{$title}" LANGSUNG MENJADI PAKET SOAL LATIHAN MATEMATIKA ANAK SD YANG RAPI, CERIA, DAN MENARIK.

═══════════════════════════════════════════════════════
GUARDRAIL WAJIB — BACA SEBELUM MELANJUTKAN
═══════════════════════════════════════════════════════
RoboMath adalah aplikasi latihan MATEMATIKA SD. Anda HANYA boleh membuat soal yang berkaitan dengan
materi matematika Sekolah Dasar (operasi hitung, pecahan, geometri, pengukuran, data/statistik, dll.).

Jika isi dokumen ini BUKAN tentang matematika (misalnya: IPA, IPS, Bahasa Indonesia, Sejarah,
Agama, Olahraga, Pemrograman, dsb.), JANGAN buat soal sama sekali. Sebagai gantinya, tuliskan
PERSIS kalimat berikut (tanpa perubahan apapun):

## ❌ Dokumen Bukan Materi Matematika

Maaf, RoboMath hanya dapat memproses dokumen materi **matematika SD** 📐🔢
Dokumen "{$title}" tampaknya berisi materi di luar matematika.

**Silakan unggah dokumen yang berisi:**
- Operasi hitung (penjumlahan, pengurangan, perkalian, pembagian)
- Pecahan, desimal, atau persen
- Geometri dan pengukuran (bangun datar/ruang, luas, keliling, volume)
- Data dan statistik (diagram, grafik, rata-rata)
- Bilangan (cacah, bulat, prima, KPK, FPB)
- Materi matematika SD lainnya

═══════════════════════════════════════════════════════

ATURAN UTAMA (SANGAT PENTING):
1. DILARANG KERAS menyertakan kalimat pembuka/sapaan formal/basa-basi robotik (JANGAN tulis "Sebagai Asisten...", "Saya telah menganalisis...", "Berikut ini adalah...", dll).
2. LANGSUNG MULAI dari judul dokumen: "## 🌟 Paket Soal: {$title}".
3. Gaya Bahasa: Ramah, santun, ceria, dan mudah dipahami oleh anak-anak Sekolah Dasar (SD). Gunakan analogi konkret dan situasi sehari-hari yang menyenangkan.
4. {$instruction}
5. Jika dokumen berupa pindaian/scan atau memiliki ilustrasi/diagram gambar dalam berkas terlampir, pahami dan gunakan isi gambar/diagram tersebut untuk menyusun soal matematika kontekstual.
6. SELURUH soal WAJIB berkaitan dengan matematika. Jika materi dokumen menyinggung topik non-matematika, ubah/adaptasi soal agar tetap berbasis perhitungan atau konsep matematika yang relevan.
7. PENULISAN SIMBOL/PECAHAN: DILARANG menggunakan notasi LaTeX (seperti $\frac{a}{b}$ atau $\times$). Gunakan penulisan teks biasa yang mudah dibaca anak SD: tulis pecahan sebagai "1/2", "1/3", atau "setengah"; perkalian sebagai "x"; pembagian sebagai ":" atau "bagi".

{$formatGuide}

DI AKHIR TANGGAPAN:
### 📌 Rangkuman Konsep Kunci
- [Poin kesimpulan materi 1]
- [Poin kesimpulan materi 2]
- [Poin kesimpulan materi 3]

{$documentContentContext}

Mulai paket soal sekarang (tanpa pembuka basa-basi):
EOT;

        $providerName = 'gemini';
        $summaryText = '';
        $tokensUsed = null;

        try {
            // Prioritize multimodal/vision mode when base64 file data is available and text is sparse/garbled
            // or directly send document to multimodal model (e.g. Gemini)
            if ($hasVisionInput && (empty($text) || mb_strlen($text) < 150)) {
                $response = $this->aiManager->chatWithDocument(
                    message: $prompt,
                    base64Data: (string) $rawBase64,
                    mimeType: (string) $mimeType,
                    user: $user,
                    context: 'raw'
                );
            } else {
                // Standard text chat
                $response = $this->aiManager->chat($prompt, $user, 'raw');
            }

            $summaryText = $this->stripIntroPreamble($response->text);

            // If user explicitly requested essay/uraian, sanitize any accidental multiple choice options
            if ($isEssayRequest) {
                $summaryText = $this->sanitizeEssayOutput($summaryText);
            }

            $providerName = $response->provider;
            $tokensUsed = $response->tokensUsed;
        } catch (\Throwable $e) {
            Log::error('AI Document Summarizer Error: '.$e->getMessage());
        }

        // If AI provider failed, returned generic conversational mock fallback, or didn't generate questions, provide enhanced structured questions
        $hasQuestionSection = preg_match('/###\s*(?:📝|🌟)?\s*Soal/i', $summaryText);

        // Check whether the AI itself rejected the document as non-math content
        $aiRejectedAsMathIrrelevant = str_contains($summaryText, '❌ Dokumen Bukan Materi Matematika');

        if ($aiRejectedAsMathIrrelevant) {
            $wordCountSummary = str_word_count(strip_tags($summaryText));

            return [
                'summary' => $summaryText,
                'key_points' => ['Dokumen ini tidak berkaitan dengan matematika SD — RoboMath hanya memproses materi matematika.'],
                'provider' => $providerName,
                'tokens_used' => $tokensUsed,
                'word_count' => $wordCountSummary,
                'rejected' => true,
            ];
        }

        if (empty($summaryText) || ! $hasQuestionSection || str_contains($summaryText, 'koneksi ke otak AI RoboBot di server sedang mengalami antrean padat')) {
            $fallback = $this->generateLocalStructuredSummary($title, $truncatedText, $customPrompt);
            $summaryText = $fallback['summary'];
            $keyPoints = $fallback['key_points'];
            $providerName = 'robomath-local (offline smart fallback)';
        } else {
            $keyPoints = $this->extractKeyPointsFromMarkdown($summaryText);
        }

        $summaryText = $this->cleanLatexNotation($summaryText);
        $wordCountSummary = str_word_count(strip_tags($summaryText));

        return [
            'summary' => $summaryText,
            'key_points' => $keyPoints,
            'provider' => $providerName,
            'tokens_used' => $tokensUsed,
            'word_count' => $wordCountSummary,
            'rejected' => false,
        ];
    }

    /**
     * Clean raw LaTeX notation like $\frac{a}{b}$ into plain human-readable math like a/b.
     */
    protected function cleanLatexNotation(string $text): string
    {
        // Replace \frac{a}{b} with a/b (handle optional spacing or escaped slashes)
        $text = preg_replace_callback('/\\\\?frac\s*\{([^{}]+)\}\s*\{([^{}]+)\}/i', function ($m) {
            return trim($m[1]).'/'.trim($m[2]);
        }, $text);

        // Replace common LaTeX symbols
        $replacements = [
            '\\times' => 'x',
            '\\div' => ':',
            '\\pm' => '±',
            '\\le' => '≤',
            '\\ge' => '≥',
            '\\neq' => '≠',
            '\\approx' => '≈',
        ];
        $text = str_replace(array_keys($replacements), array_values($replacements), $text);

        // Strip lone $ or \$ delimiters wrapping math expressions: $ 1/2 $ -> 1/2
        $text = preg_replace('/(?<!\\\\)\$([^\$]+)\$/', '$1', $text);
        $text = str_replace(['\$', '\\\\'], ['', ''], $text);

        return $text;
    }

    /**
     * Build a user-facing rejection message for documents that are not math-related.
     */
    protected function buildRejectionMessage(string $title): string
    {
        return <<<MD
## ❌ Dokumen Bukan Materi Matematika

Maaf, RoboMath hanya dapat memproses dokumen materi **matematika SD** 📐🔢
Dokumen "{$title}" tidak terdeteksi sebagai materi matematika.

**Silakan unggah dokumen yang berisi:**
- Operasi hitung (penjumlahan, pengurangan, perkalian, pembagian)
- Pecahan, desimal, atau persen
- Geometri dan pengukuran (bangun datar/ruang, luas, keliling, volume)
- Data dan statistik (diagram, grafik, rata-rata)
- Bilangan (cacah, bulat, prima, KPK, FPB)
- Materi matematika SD lainnya
MD;
    }

    /**
     * Sanitize and strip any accidental multiple-choice options in pure essay output.
     */
    protected function sanitizeEssayOutput(string $text): string
    {
        $lines = explode("\n", $text);
        $cleanLines = [];

        foreach ($lines as $line) {
            $trimmedLine = trim($line);

            // Check if line is an option like "- A. ...", "* A. ...", "A. ...", "A) ..."
            if (preg_match('/^(?:[*-]\s*)?[A-D][\.\)]\s+.+$/i', $trimmedLine)) {
                continue;
            }

            // Clean up key answer if it says "🔑 Kunci Jawaban: A. Penjelasan..." -> "🔑 Kunci Jawaban: Penjelasan..."
            if (preg_match('/(>\s*\*\*🔑\s*Kunci\s+Jawaban:\*\*)\s*[A-D][\.\)]\s*(.*)/i', $line, $keyMatch)) {
                $cleanLines[] = $keyMatch[1].' '.$keyMatch[2];

                continue;
            }

            $cleanLines[] = $line;
        }

        return implode("\n", $cleanLines);
    }

    /**
     * Strip robotic / conversational preamble before markdown headers or questions.
     */
    protected function stripIntroPreamble(string $text): string
    {
        $trimmed = trim($text);

        // If text starts with conversational preamble like "Sebagai Asisten...", strip until first markdown heading ## or ### or Soal 1
        if (preg_match('/^(?:Sebagai\s+Asisten|Tentu|Halo|Berikut\s+adalah|Saya\s+telah|Baiklah|Berikut\s+ini).*?(?=(##|###|📝|Soal\s+1))/is', $trimmed, $matches, PREG_OFFSET_CAPTURE)) {
            $pos = $matches[1][1] ?? 0;
            if ($pos > 0) {
                $trimmed = trim(substr($trimmed, $pos));
            }
        }

        return $trimmed;
    }

    /**
     * Extract key points from generated markdown text.
     *
     * @return list<string>
     */
    protected function extractKeyPointsFromMarkdown(string $markdown): array
    {
        $points = [];

        // Look for bullet lines starting with - or * in Rangkuman Konsep section
        if (preg_match('/###\s*📌\s*Rangkuman Konsep Kunci(.*?)(?=###|$)/s', $markdown, $sectionMatch)) {
            if (preg_match_all('/^[*-]\s+(.+)$/m', $sectionMatch[1], $matches)) {
                foreach ($matches[1] as $match) {
                    $clean = trim(strip_tags($match));
                    if (mb_strlen($clean) > 8 && mb_strlen($clean) < 300) {
                        $points[] = $clean;
                    }
                }
            }
        }

        // Fallback to any bullet lines
        if (empty($points) && preg_match_all('/^[*-]\s+(.+)$/m', $markdown, $matches)) {
            foreach ($matches[1] as $match) {
                $clean = trim(strip_tags($match));
                // Skip options like A., B., C., D.
                if (preg_match('/^[A-D]\.\s/i', $clean)) {
                    continue;
                }
                if (mb_strlen($clean) > 8 && mb_strlen($clean) < 300) {
                    $points[] = $clean;
                }
                if (count($points) >= 5) {
                    break;
                }
            }
        }

        if (empty($points)) {
            // Extract sentences as fallback
            $sentences = preg_split('/(?<=[.?!])\s+/', strip_tags($markdown));
            foreach ($sentences as $sentence) {
                $s = trim($sentence);
                if (mb_strlen($s) > 20 && mb_strlen($s) < 250 && ! preg_match('/^[A-D]\./i', $s)) {
                    $points[] = $s;
                }
                if (count($points) >= 4) {
                    break;
                }
            }
        }

        return array_values($points);
    }

    /**
     * Generate local structured questions when remote LLM is unavailable or offline.
     *
     * @return array{summary: string, key_points: list<string>}
     */
    protected function generateLocalStructuredSummary(string $title, string $text, ?string $customPrompt = null): array
    {
        // Extract key terms
        $words = str_word_count(strtolower(strip_tags($text)), 1);
        $stopWords = ['yang', 'untuk', 'pada', 'ke', 'para', 'namun', 'menurut', 'antara', 'dia', 'mereka', 'anda', 'kita', 'aku', 'kami', 'dan', 'atau', 'ini', 'itu', 'adalah', 'dengan', 'dari', 'dalam', 'bisa', 'dapat', 'harus', 'serta', 'sebagai'];
        $filteredWords = array_diff($words, $stopWords);
        $wordFreq = array_count_values(array_filter($filteredWords, fn ($w) => strlen($w) > 4));
        arsort($wordFreq);
        // Only keep words that look like real Latin/Indonesian words (no garbled characters)
        $cleanKeywords = array_filter(array_keys($wordFreq), fn ($w) => preg_match('/^[a-z\x{00C0}-\x{024F}]+$/u', $w));
        $topKeywords = array_values(array_slice($cleanKeywords, 0, 10));

        $keywords = [
            $topKeywords[0] ?? 'Konsep Dasar',
            $topKeywords[1] ?? 'Operasi Hitung',
            $topKeywords[2] ?? 'Penerapan Soal',
            $topKeywords[3] ?? 'Analisis Data',
            $topKeywords[4] ?? 'Logika Penyelesaian',
            $topKeywords[5] ?? 'Penalaran Konsep',
            $topKeywords[6] ?? 'Studi Kasus',
            $topKeywords[7] ?? 'Langkah Kerja',
            $topKeywords[8] ?? 'Evaluasi Hasil',
            $topKeywords[9] ?? 'Pemecahan Masalah',
        ];

        $isEssay = ! empty($customPrompt) && (bool) preg_match('/(essay|esai|uraian|isian|terbuka)/i', $customPrompt);

        // Detect count
        $targetCount = 5;
        if (! empty($customPrompt) && preg_match('/(\d+)\s*(?:soal|butir|nomor|buah|pertanyaan)/i', $customPrompt, $countMatches)) {
            $targetCount = max(1, min(10, (int) $countMatches[1]));
        }

        $hasMathContext = (bool) preg_match('/(bilangan|cacah|ratusan|puluhan|satuan|angka|nilai\s*tempat|pecahan|hitung|penjumlahan|pengurangan|perkalian|pembagian)/i', $title.' '.$text);

        if ($isEssay) {
            $summary = "## 🌟 Paket Soal Essay & Uraian: {$title}\n\n";
            $summary .= "> 💡 **Petunjuk:** Bacalah setiap pertanyaan dengan teliti dan tuliskan penjelasan atau langkah penyelesaianmu secara lengkap ya!\n\n";

            if ($hasMathContext) {
                $essayTemplates = [
                    fn ($i, $kw) => "### 📝 Soal {$i}\nSebuah bilangan terdiri atas 3 angka yaitu 5 ratusan, 7 puluhan, dan 2 satuan. Berapakah lambang bilangan tersebut dan bagaimana cara membacanya?\n\n> **🔑 Kunci Jawaban:** Lambang bilangannya adalah 572, dibaca lima ratus tujuh puluh dua.\n> **💡 Pembahasan & Langkah:** Nilai tempat: 5 ratusan = 500, 7 puluhan = 70, 2 satuan = 2. Dijumlahkan menjadi 500 + 70 + 2 = 572.\n\n",
                    fn ($i, $kw) => "### 📝 Soal {$i}\nPerhatikan bilangan 354. Tentukan nilai tempat dari setiap angka penyusun bilangan tersebut (ratusan, puluhan, dan satuan)!\n\n> **🔑 Kunci Jawaban:** Angka 3 menempati nilai tempat ratusan (300), angka 5 menempati puluhan (50), dan angka 4 menempati satuan (4).\n> **💡 Pembahasan & Langkah:** Setiap posisi angka menentukan nilainya: ratusan di depan, puluhan di tengah, dan satuan di paling belakang.\n\n",
                    fn ($i, $kw) => "### 📝 Soal {$i}\nJelaskan mengapa 10 kumpulan dari 10 satuan dapat membentuk 1 ratusan? Tuliskan penjelasan dan perhitungannya!\n\n> **🔑 Kunci Jawaban:** Karena 10 x 10 satuan = 100 satuan, dan 100 satuan sama dengan 1 ratusan.\n> **💡 Pembahasan & Langkah:** Pengelompokan bilangan: 10 satuan = 1 puluhan, maka 10 puluhan = 100 atau 1 ratusan.\n\n",
                    fn ($i, $kw) => "### 📝 Soal {$i}\nJika Meutia memiliki 7 lembar kartu ratusan, 5 lembar kartu puluhan, dan 9 lembar kartu satuan, berapa jumlah total nilai kartu yang dimiliki Meutia?\n\n> **🔑 Kunci Jawaban:** Total nilainya adalah 759 (tujuh ratus lima puluh sembilan).\n> **💡 Pembahasan & Langkah:** 7 ratusan = 700, 5 puluhan = 50, 9 satuan = 9. Total = 700 + 50 + 9 = 759.\n\n",
                    fn ($i, $kw) => "### 📝 Soal {$i}\nBuatlah 2 contoh bilangan tiga angka yang angka puluhannya kurang dari 3 dan jelaskan cara membaca kedua bilangan tersebut!\n\n> **🔑 Kunci Jawaban:** Contoh: 215 (dua ratus lima belas) angka puluhannya 1, dan 408 (empat ratus delapan) angka puluhannya 0.\n> **💡 Pembahasan & Langkah:** Angka puluhan kurang dari 3 adalah 0, 1, atau 2. Siswa dapat menyusun kombinasi bilangan apa saja yang memenuhi syarat tersebut.\n\n",
                ];
            } else {
                $essayTemplates = [
                    fn ($i, $kw) => "### 📝 Soal {$i}\nJelaskan dengan bahasamu sendiri apa yang dimaksud dengan materi **{$kw}** berdasarkan dokumen di atas, serta berikan 1 contoh konkret dalam kehidupan sehari-hari!\n\n> **🔑 Kunci Jawaban:** Siswa mampu menguraikan definisi {$kw} dengan benar dan menyertakan contoh penerapan nyata yang tepat.\n> **💡 Pembahasan & Langkah:** {$kw} merupakan konsep utama yang menjelaskan prinsip dasar materi sehingga siswa diharapkan memahami maknanya secara menyeluruh.\n\n",
                    fn ($i, $kw) => "### 📝 Soal {$i}\nTuliskan langkah-langkah yang harus dilakukan saat menyelesaikan persoalan terkait **{$kw}** secara runtut dan sistematis!\n\n> **🔑 Kunci Jawaban:** Siswa menyebutkan tahapan: (1) Memahami informasi soal, (2) Memilih operasi/rumus yang sesuai, (3) Melakukan perhitungan, dan (4) Memeriksa kembali hasil.\n> **💡 Pembahasan & Langkah:** Mengikuti prosedur penyelesaian teratur meminimalisir kesalahan perhitungan pada topik {$kw}.\n\n",
                    fn ($i, $kw) => "### 📝 Soal {$i}\nBagaimana caramu menerapkan konsep **{$kw}** ketika menghadapi permasalahan nyata di lingkungan sekolah atau rumah?\n\n> **🔑 Kunci Jawaban:** Siswa memberikan ilustrasi pemecahan masalah kontekstual yang relevan dengan prinsip {$kw}.\n> **💡 Pembahasan & Langkah:** {$kw} melatih nalar kritis siswa dalam menghubungkan materi teori dengan situasi nyata.\n\n",
                    fn ($i, $kw) => "### 📝 Soal {$i}\nMengapa pemahaman terhadap **{$kw}** sangat penting bagi siswa sebelum mempelajari topik matematika tingkat lanjut?\n\n> **🔑 Kunci Jawaban:** Karena {$kw} menjadi prasyarat esensial untuk memahami hubungan antar-konsep dan pemodelan matematis berikutnya.\n> **💡 Pembahasan & Langkah:** Penguasaan {$kw} membangun pondasi penalaran yang kokoh bagi siswa.\n\n",
                    fn ($i, $kw) => "### 📝 Soal {$i}\nJika kamu diminta menjelaskan **{$kw}** kepada teman sebangkumu, analogi atau cerita sederhana apa yang akan kamu gunakan?\n\n> **🔑 Kunci Jawaban:** Siswa mampu membuat analogi yang mudah dimengerti dan sesuai dengan esensi materi {$kw}.\n> **💡 Pembahasan & Langkah:** Kemampuan menjelaskan ulang konsep menunjukkan pemahaman mendalam siswa terhadap materi.\n\n",
                ];
            }

            for ($idx = 0; $idx < $targetCount; $idx++) {
                $num = $idx + 1;
                $kw = ucfirst($keywords[$idx % count($keywords)]);
                $templateFn = $essayTemplates[$idx % count($essayTemplates)];
                $summary .= $templateFn($num, $kw);
            }
        } else {
            $summary = "## 🎯 Paket Soal Pilihan Ganda: {$title}\n\n";
            $summary .= "> **Intisari Pembelajaran:** Soal dirancang berdasarkan konsep matematika SD: Nilai Tempat, Lambang Bilangan, dan Operasi Hitung.\n\n";

            if ($hasMathContext) {
                $mcTemplates = [
                    fn ($i, $kw) => "### 📝 Soal {$i}\nBilangan 671 dibaca sebagai...\n- A. Enam ratus tujuh belas\n- B. Enam ratus tujuh puluh satu\n- C. Tujuh ratus enam puluh satu\n- D. Enam puluh tujuh satu\n\n> **🔑 Kunci Jawaban:** B. Enam ratus tujuh puluh satu\n> **💡 Pembahasan:** 671 terdiri dari 6 ratusan (600), 7 puluhan (70), dan 1 satuan (1), sehingga dibaca enam ratus tujuh puluh satu.\n\n",
                    fn ($i, $kw) => "### 📝 Soal {$i}\nPada bilangan 405, angka 0 menempati nilai tempat...\n- A. Ratusan\n- B. Puluhan\n- C. Satuan\n- D. Ribuan\n\n> **🔑 Kunci Jawaban:** B. Puluhan\n> **💡 Pembahasan:** Urutan nilai tempat dari kanan ke kiri adalah satuan (5), puluhan (0), dan ratusan (4).\n\n",
                    fn ($i, $kw) => "### 📝 Soal {$i}\nBerapakah hasil dari 2 ratusan + 3 puluhan + 6 satuan?\n- A. 236\n- B. 263\n- C. 326\n- D. 632\n\n> **🔑 Kunci Jawaban:** A. 236\n> **💡 Pembahasan:** 2 ratusan = 200, 3 puluhan = 30, 6 satuan = 6. Dijumlahkan menjadi 200 + 30 + 6 = 236.\n\n",
                    fn ($i, $kw) => "### 📝 Soal {$i}\n10 tumpukan dari 10 satuan kubus setara dengan...\n- A. 10 satuan\n- B. 50 satuan\n- C. 100 satuan (1 ratusan)\n- D. 1.000 satuan\n\n> **🔑 Kunci Jawaban:** C. 100 satuan (1 ratusan)\n> **💡 Pembahasan:** 10 tumpukan yang masing-masing berisi 10 satuan menghasilkan 10 x 10 = 100 satuan atau 1 ratusan.\n\n",
                    fn ($i, $kw) => "### 📝 Soal {$i}\nManakah dari bilangan berikut yang memiliki angka ratusan kurang dari 5?\n- A. 759\n- B. 634\n- C. 912\n- D. 239\n\n> **🔑 Kunci Jawaban:** D. 239\n> **💡 Pembahasan:** Angka ratusan pada 239 adalah 2, dan 2 kurang dari 5.\n\n",
                ];
            } else {
                $mcTemplates = [
                    fn ($i, $kw) => "### 📝 Soal {$i}\nBerdasarkan materi pada dokumen, apa definisi atau gagasan utama yang paling tepat mengenai **{$kw}**?\n- A. Landasan konsep utama yang mendasari seluruh pembahasan materi\n- B. Langkah opsional yang tidak mempengaruhi hasil akhir\n- C. Istilah pelengkap tanpa makna konseptual\n- D. Rumus turunan yang hanya dipakai pada kondisi khusus\n\n> **🔑 Kunci Jawaban:** A. Landasan konsep utama yang mendasari seluruh pembahasan materi\n> **💡 Pembahasan:** {$kw} adalah elemen fundamental yang dijelaskan secara rinci dalam dokumen sebagai fondasi pemahaman materi.\n\n",
                    fn ($i, $kw) => "### 📝 Soal {$i}\nDalam kaitannya dengan **{$kw}**, langkah pemecahan masalah yang paling sistematis adalah...\n- A. Mengabaikan data awal dan langsung menebak solusi\n- B. Mengidentifikasi informasi penting, menentukan operasi yang sesuai, lalu mengevaluasi hasil\n- C. Menyelesaikan secara acak tanpa memperhatikan urutan operasi\n- D. Menggunakan rumus yang tidak relevan dengan persoalan\n\n> **🔑 Kunci Jawaban:** B. Mengidentifikasi informasi penting, menentukan operasi yang sesuai, lalu mengevaluasi hasil\n> **💡 Pembahasan:** Penerapan {$kw} menuntut pendekatan terstruktur mulai dari pemahaman data hingga verifikasi hasil akhir.\n\n",
                    fn ($i, $kw) => "### 📝 Soal {$i}\nManakah dari pernyataan berikut yang paling tepat mencerminkan implementasi **{$kw}** dalam kehidupan sehari-hari?\n- A. Membantu memecahkan masalah kontekstual dengan pemikiran logis dan terukur\n- B. Hanya berguna dalam ujian tertulis tanpa relevansi praktis\n- C. Menghambat proses pengambilan keputusan yang cepat\n- D. Mengganti semua prinsip dasar matematika yang telah ada\n\n> **🔑 Kunci Jawaban:** A. Membantu memecahkan masalah kontekstual dengan pemikiran logis dan terukur\n> **💡 Pembahasan:** Materi {$kw} dirancang untuk melatih kemampuan nalar siswa dalam menyelesaikan studi kasus konkret.\n\n",
                    fn ($i, $kw) => "### 📝 Soal {$i}\nMengapa penguasaan terhadap aspek **{$kw}** penting sebelum melangkah ke topik yang lebih kompleks?\n- A. Agar siswa dapat membaca, menafsirkan, dan menarik kesimpulan yang akurat dari informasi yang diberikan\n- B. Karena aspek ini merupakan satu-satunya materi yang diujikan\n- C. Untuk memperpanjang waktu belajar tanpa tujuan khusus\n- D. Karena materi ini tidak membutuhkan pemahaman konsep sebelumnya\n\n> **🔑 Kunci Jawaban:** A. Agar siswa dapat membaca, menafsirkan, dan menarik kesimpulan yang akurat dari informasi yang diberikan\n> **💡 Pembahasan:** Pemahaman {$kw} menjadi jembatan antara konsep teoretis dan penalaran analitis siswa.\n\n",
                    fn ($i, $kw) => "### 📝 Soal {$i}\nStrategi terbaik dalam mengembangkan **{$kw}** saat menghadapi soal cerita adalah...\n- A. Membaca soal secara cermat, menuliskan hal yang diketahui dan ditanyakan, serta menyusun model penyelesaian\n- B. Memilih opsi jawaban terpanjang tanpa membaca pertanyaan\n- C. Mengabaikan instruksi dan langsung menjawab sembarangan\n- D. Menyerah sebelum mencoba menganalisis informasi\n\n> **🔑 Kunci Jawaban:** A. Membaca soal secara cermat, menuliskan hal yang diketahui dan ditanyakan, serta menyusun model penyelesaian\n> **💡 Pembahasan:** Logika penyelesaian terstruktur membantu siswa merumuskan strategi pengerjaan yang efektif dan meminimalkan kesalahan.\n\n",
                ];
            }

            for ($idx = 0; $idx < $targetCount; $idx++) {
                $num = $idx + 1;
                $kw = ucfirst($keywords[$idx % count($keywords)]);
                $templateFn = $mcTemplates[$idx % count($mcTemplates)];
                $summary .= $templateFn($num, $kw);
            }
        }

        $summary .= "### 📌 Rangkuman Konsep Kunci\n";
        $points = [
            "Penguasaan konsep utama ({$keywords[0]}) sebagai dasar penalaran materi dokumen.",
            "Penerapan operasi dan metode ({$keywords[1]}) secara sistematis dan runtut.",
            "Integrasi materi ({$keywords[2]}) dalam pemecahan masalah kontekstual sehari-hari.",
            "Analisis dan evaluasi informasi ({$keywords[3]}) untuk menarik kesimpulan yang tepat.",
            "Pengembangan logika berpikir ({$keywords[4]}) dalam menyelesaikan soal-soal latihan.",
        ];

        foreach ($points as $p) {
            $summary .= '- '.$p."\n";
        }

        return [
            'summary' => $summary,
            'key_points' => $points,
        ];
    }

    /**
     * Convert essay/uraian question markdown into interactive multiple-choice quiz questions.
     */
    public function convertEssayToMultipleChoice(string $markdown, ?User $user = null): string
    {
        $prompt = <<<EOT
Anda adalah Guru Matematika & Pembuat Kuis Anak SD di RoboMath.
Tugas Anda: Ubah butir-butir soal uraian/essay berikut ini menjadi BUTIR SOAL PILIHAN GANDA (A, B, C, D) interaktif yang seru dan mudah dimengerti anak SD.

ATURAN UTAMA:
1. Pertahankan topik dan maksud pertanyaan dari setiap butir soal.
2. Buatkan 4 pilihan jawaban yang masuk akal: - A., - B., - C., - D.
3. Tentukan 1 kunci jawaban yang benar sesuai kunci jawaban asli.
4. Buatkan pembahasan singkat dan jelas.
5. DILARANG ada teks pembuka atau basa-basi robotik, langsung mulai dari "### 📝 Soal 1".

FORMAT WAJIB SETIAP SOAL:
### 📝 Soal [Nomor]
[Pertanyaan pilihan ganda ramah anak]
- A. [Pilihan A]
- B. [Pilihan B]
- C. [Pilihan C]
- D. [Pilihan D]

> **🔑 Kunci Jawaban:** [Pilihan benar, contoh: A. ...]
> **💡 Pembahasan:** [Penjelasan cara menjawab]

SOAL ASLI YANG PERLU DIUBAH:
{$markdown}
EOT;

        try {
            $response = $this->aiManager->chat($prompt, $user, 'raw');
            $clean = $this->stripIntroPreamble($response->text);
            if (! empty($clean) && preg_match('/###\s*(?:📝|🌟)?\s*Soal/i', $clean) && preg_match('/[-*]?\s*[A-D][\.\)]/i', $clean)) {
                return $clean;
            }
        } catch (\Throwable $e) {
            Log::warning('Convert essay to MCQ failed: '.$e->getMessage());
        }

        return '';
    }
}
