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
    public function __construct(protected AIManager $aiManager) {}

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
                $parser = new Parser;
                $pdf = $parser->parseFile($filePath);
                $text = $pdf->getText();

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
     *     word_count: int
     * }
     */
    public function summarize(
        string $text,
        string $title,
        ?string $customPrompt = null,
        string $style = 'kuis_latihan',
        string $length = 'sedang',
        ?string $focus = null,
        ?User $user = null
    ): array {
        $text = trim($text);

        if (empty($text)) {
            return [
                'summary' => 'Dokumen tidak mengandung teks yang cukup untuk dibuatkan soal. Pastikan dokumen memuat teks dan bukan hanya gambar pindaian murni.',
                'key_points' => ['Tidak ada teks yang dapat dianalisis.'],
                'provider' => 'system',
                'tokens_used' => 0,
                'word_count' => 0,
            ];
        }

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
[Tuliskan pertanyaan essay/uraian yang jelas, kontekstual, dan ramah anak SD tanpa ada pilihan A, B, C, D]

> **🔑 Kunci Jawaban:** [Model jawaban lengkap yang diharapkan]
> **💡 Pembahasan & Langkah:** [Penjelasan langkah-langkah penyelesaian terinci]
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
[Tuliskan pertanyaan dengan jelas dan ramah anak]
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

        $prompt = <<<EOT
Anda adalah Guru Matematika & Sahabat Belajar Anak SD di RoboMath.
Tugas Anda: Meringkas dokumen berjudul "{$title}" LANGSUNG MENJADI PAKET SOAL LATIHAN ANAK SD YANG RAPI, CERIA, DAN MENARIK.

ATURAN UTAMA (SANGAT PENTING):
1. DILARANG KERAS menyertakan kalimat pembuka/sapaan formal/basa-basi robotik (JANGAN tulis "Sebagai Asisten...", "Saya telah menganalisis...", "Berikut ini adalah...", dll).
2. LANGSUNG MULAI dari judul dokumen: "## 🌟 Paket Soal: {$title}".
3. Gaya Bahasa: Ramah, santun, ceria, dan mudah dipahami oleh anak-anak Sekolah Dasar (SD). Gunakan analogi konkret dan situasi sehari-hari yang menyenangkan.
4. {$instruction}

{$formatGuide}

DI AKHIR TANGGAPAN:
### 📌 Rangkuman Konsep Kunci
- [Poin kesimpulan materi 1]
- [Poin kesimpulan materi 2]
- [Poin kesimpulan materi 3]

ISI DOKUMEN MATERI:
---
{$truncatedText}
---

Mulai paket soal sekarang (tanpa pembuka basa-basi):
EOT;

        $providerName = 'gemini';
        $summaryText = '';
        $tokensUsed = null;

        try {
            // Send request to AI Manager
            $response = $this->aiManager->chat($prompt, $user, 'raw');
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

        // If AI provider failed or returned generic mock fallback, provide enhanced structured questions
        if (empty($summaryText) || str_contains($summaryText, 'koneksi ke otak AI RoboBot di server sedang mengalami antrean padat')) {
            $fallback = $this->generateLocalStructuredSummary($title, $truncatedText, $customPrompt);
            $summaryText = $fallback['summary'];
            $keyPoints = $fallback['key_points'];
            $providerName = 'robomath-local (offline smart fallback)';
        } else {
            $keyPoints = $this->extractKeyPointsFromMarkdown($summaryText);
        }

        $wordCountSummary = str_word_count(strip_tags($summaryText));

        return [
            'summary' => $summaryText,
            'key_points' => $keyPoints,
            'provider' => $providerName,
            'tokens_used' => $tokensUsed,
            'word_count' => $wordCountSummary,
        ];
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
        $topKeywords = array_values(array_slice(array_keys($wordFreq), 0, 10));

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

        if ($isEssay) {
            $summary = "## 🌟 Paket Soal Essay & Uraian: {$title}\n\n";
            $summary .= "> 💡 **Petunjuk:** Bacalah setiap pertanyaan dengan teliti dan tuliskan penjelasan atau langkah penyelesaianmu secara lengkap ya!\n\n";

            $essayTemplates = [
                fn ($i, $kw) => "### 📝 Soal {$i}\nJelaskan dengan bahasamu sendiri apa yang dimaksud dengan materi **{$kw}** berdasarkan dokumen di atas, serta berikan 1 contoh konkret dalam kehidupan sehari-hari!\n\n> **🔑 Kunci Jawaban:** Siswa mampu menguraikan definisi {$kw} dengan benar dan menyertakan contoh penerapan nyata yang tepat.\n> **💡 Pembahasan & Langkah:** {$kw} merupakan konsep utama yang menjelaskan prinsip dasar materi sehingga siswa diharapkan memahami maknanya secara menyeluruh.\n\n",
                fn ($i, $kw) => "### 📝 Soal {$i}\nTuliskan langkah-langkah yang harus dilakukan saat menyelesaikan persoalan terkait **{$kw}** secara runtut dan sistematis!\n\n> **🔑 Kunci Jawaban:** Siswa menyebutkan tahapan: (1) Memahami informasi soal, (2) Memilih operasi/rumus yang sesuai, (3) Melakukan perhitungan, dan (4) Memeriksa kembali hasil.\n> **💡 Pembahasan & Langkah:** Mengikuti prosedur penyelesaian teratur meminimalisir kesalahan perhitungan pada topik {$kw}.\n\n",
                fn ($i, $kw) => "### 📝 Soal {$i}\nBagaimana caramu menerapkan konsep **{$kw}** ketika menghadapi permasalahan nyata di lingkungan sekolah atau rumah?\n\n> **🔑 Kunci Jawaban:** Siswa memberikan ilustrasi pemecahan masalah kontekstual yang relevan dengan prinsip {$kw}.\n> **💡 Pembahasan & Langkah:** {$kw} melatih nalar kritis siswa dalam menghubungkan materi teori dengan situasi nyata.\n\n",
                fn ($i, $kw) => "### 📝 Soal {$i}\nMengapa pemahaman terhadap **{$kw}** sangat penting bagi siswa sebelum mempelajari topik matematika tingkat lanjut?\n\n> **🔑 Kunci Jawaban:** Karena {$kw} menjadi prasyarat esensial untuk memahami hubungan antar-konsep dan pemodelan matematis berikutnya.\n> **💡 Pembahasan & Langkah:** Penguasaan {$kw} membangun pondasi penalaran yang kokoh bagi siswa.\n\n",
                fn ($i, $kw) => "### 📝 Soal {$i}\nJika kamu diminta menjelaskan **{$kw}** kepada teman sebangkumu, analogi atau cerita sederhana apa yang akan kamu gunakan?\n\n> **🔑 Kunci Jawaban:** Siswa mampu membuat analogi yang mudah dimengerti dan sesuai dengan esensi materi {$kw}.\n> **💡 Pembahasan & Langkah:** Kemampuan menjelaskan ulang konsep menunjukkan pemahaman mendalam siswa terhadap materi.\n\n",
                fn ($i, $kw) => "### 📝 Soal {$i}\nAnalisis apa saja kemungkinan kesalahan yang sering dilakukan saat mempelajari materi **{$kw}**, dan bagaimana cara menghindarinya?\n\n> **🔑 Kunci Jawaban:** Siswa memetakan potensi kekeliruan perhitungan atau miskonsepsi pada {$kw} serta memberikan solusi pencegahannya.\n> **💡 Pembahasan & Langkah:** Mengetahui letak kesalahan umum membantu siswa lebih teliti dalam pengerjaan tugas.\n\n",
                fn ($i, $kw) => "### 📝 Soal {$i}\nBuatlah sebuah soal cerita buatanmu sendiri yang bertemakan kegiatan sehari-hari berdasarkan konsep **{$kw}** beserta cara penyelesaiannya!\n\n> **🔑 Kunci Jawaban:** Soal cerita memuat narasi yang logis, data bilangan yang relevan, dan kunci langkah penyelesaian yang benar.\n> **💡 Pembahasan & Langkah:** Melatih kreativitas dan kemampuan menyusun model matematis mandiri.\n\n",
                fn ($i, $kw) => "### 📝 Soal {$i}\nUraikan hubungan keterkaitan antara topik **{$kw}** dengan materi pelajaran yang pernah kamu pelajari sebelumnya!\n\n> **🔑 Kunci Jawaban:** Siswa menemukan relasi antar topik materi secara logis dan runtut.\n> **💡 Pembahasan & Langkah:** Memperkuat peta konsep belajar yang komprehensif.\n\n",
            ];

            for ($idx = 0; $idx < $targetCount; $idx++) {
                $num = $idx + 1;
                $kw = ucfirst($keywords[$idx % count($keywords)]);
                $templateFn = $essayTemplates[$idx % count($essayTemplates)];
                $summary .= $templateFn($num, $kw);
            }
        } else {
            $summary = "## 🎯 Paket Soal Pilihan Ganda: {$title}\n\n";
            $summary .= "> **Intisari Pembelajaran:** Soal dirancang berdasarkan topik esensial dokumen: **{$keywords[0]}**, **{$keywords[1]}**, dan **{$keywords[2]}**.\n\n";

            $mcTemplates = [
                fn ($i, $kw) => "### 📝 Soal {$i}\nBerdasarkan materi pada dokumen, apa definisi atau gagasan utama yang paling tepat mengenai **{$kw}**?\n- A. Landasan konsep utama yang mendasari seluruh pembahasan materi\n- B. Langkah opsional yang tidak mempengaruhi hasil akhir\n- C. Istilah pelengkap tanpa makna konseptual\n- D. Rumus turunan yang hanya dipakai pada kondisi khusus\n\n> **🔑 Kunci Jawaban:** A. Landasan konsep utama yang mendasari seluruh pembahasan materi\n> **💡 Pembahasan:** {$kw} adalah elemen fundamental yang dijelaskan secara rinci dalam dokumen sebagai fondasi pemahaman materi.\n\n",
                fn ($i, $kw) => "### 📝 Soal {$i}\nDalam kaitannya dengan **{$kw}**, langkah pemecahan masalah yang paling sistematis adalah...\n- A. Mengabaikan data awal dan langsung menebak solusi\n- B. Mengidentifikasi informasi penting, menentukan operasi yang sesuai, lalu mengevaluasi hasil\n- C. Menyelesaikan secara acak tanpa memperhatikan urutan operasi\n- D. Menggunakan rumus yang tidak relevan dengan persoalan\n\n> **🔑 Kunci Jawaban:** B. Mengidentifikasi informasi penting, menentukan operasi yang sesuai, lalu mengevaluasi hasil\n> **💡 Pembahasan:** Penerapan {$kw} menuntut pendekatan terstruktur mulai dari pemahaman data hingga verifikasi hasil akhir.\n\n",
                fn ($i, $kw) => "### 📝 Soal {$i}\nManakah dari pernyataan berikut yang paling tepat mencerminkan implementasi **{$kw}** dalam kehidupan sehari-hari?\n- A. Membantu memecahkan masalah kontekstual dengan pemikiran logis dan terukur\n- B. Hanya berguna dalam ujian tertulis tanpa relevansi praktis\n- C. Menghambat proses pengambilan keputusan yang cepat\n- D. Mengganti semua prinsip dasar matematika yang telah ada\n\n> **🔑 Kunci Jawaban:** A. Membantu memecahkan masalah kontekstual dengan pemikiran logis dan terukur\n> **💡 Pembahasan:** Materi {$kw} dirancang untuk melatih kemampuan nalar siswa dalam menyelesaikan studi kasus konkret.\n\n",
                fn ($i, $kw) => "### 📝 Soal {$i}\nMengapa penguasaan terhadap aspek **{$kw}** penting sebelum melangkah ke topik yang lebih kompleks?\n- A. Agar siswa dapat membaca, menafsirkan, dan menarik kesimpulan yang akurat dari informasi yang diberikan\n- B. Karena aspek ini merupakan satu-satunya materi yang diujikan\n- C. Untuk memperpanjang waktu belajar tanpa tujuan khusus\n- D. Karena materi ini tidak membutuhkan pemahaman konsep sebelumnya\n\n> **🔑 Kunci Jawaban:** A. Agar siswa dapat membaca, menafsirkan, dan menarik kesimpulan yang akurat dari informasi yang diberikan\n> **💡 Pembahasan:** Pemahaman {$kw} menjadi jembatan antara konsep teoretis dan penalaran analitis siswa.\n\n",
                fn ($i, $kw) => "### 📝 Soal {$i}\nStrategi terbaik dalam mengembangkan **{$kw}** saat menghadapi soal cerita adalah...\n- A. Membaca soal secara cermat, menuliskan hal yang diketahui dan ditanyakan, serta menyusun model penyelesaian\n- B. Memilih opsi jawaban terpanjang tanpa membaca pertanyaan\n- C. Mengabaikan instruksi dan langsung menjawab sembarangan\n- D. Menyerah sebelum mencoba menganalisis informasi\n\n> **🔑 Kunci Jawaban:** A. Membaca soal secara cermat, menuliskan hal yang diketahui dan ditanyakan, serta menyusun model penyelesaian\n> **💡 Pembahasan:** Logika penyelesaian terstruktur membantu siswa merumuskan strategi pengerjaan yang efektif dan meminimalkan kesalahan.\n\n",
            ];

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
}
