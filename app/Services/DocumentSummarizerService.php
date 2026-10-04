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
     * Generate an AI summary for the given text.
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
        string $style = 'ringkasan_eksekutif',
        string $length = 'sedang',
        ?string $focus = null,
        ?User $user = null
    ): array {
        $text = trim($text);

        if (empty($text)) {
            return [
                'summary' => 'Dokumen tidak mengandung teks yang cukup untuk diringkas. Pastikan dokumen memuat teks dan bukan hanya gambar pindaian murni.',
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

        $styleInstruction = match ($style) {
            'ringkasan_eksekutif' => 'Buatkan ringkasan intisari eksekutif yang padat, terstruktur rapi dengan poin-poin utama, ide pokok, dan kesimpulan strategis.',
            'ringkasan_lengkap' => 'Buatkan rangkuman komprehensif, mengulas detail per bagian atau topik, penjelasan rinci dan alur bahasan lengkap dari dokumen.',
            'anak_sd' => 'Buatkan ringkasan dengan gaya bahasa ramah, ceria, dan mudah dipahami oleh anak-anak usia Sekolah Dasar (SD). Gunakan analogi sederhana, emoji edukatif, dan kalimat yang hangat.',
            'peta_konsep' => 'Buatkan peta konsep terstruktur, glosarium istilah-istilah kunci, definisi penting, dan bagaimana setiap konsep saling berhubungan.',
            'kuis_latihan' => 'Buatkan 5 soal latihan/kuis pemahaman materi berdasarkan isi dokumen. Setiap soal harus memiliki 4 opsi jawaban (A, B, C, D), kunci jawaban benar, serta pembahasan ringkas.',
            default => 'Buatkan ringkasan komprehensif dan terstruktur dengan poin-poin penting.',
        };

        $lengthInstruction = match ($length) {
            'singkat' => 'Panjang ringkasan: SINGKAT dan sangat padat (sekitar 100 - 200 kata, atau 4 - 6 poin inti).',
            'mendalam' => 'Panjang ringkasan: MENDALAM dan detail (sekitar 400 - 700 kata, mencakup semua sub-pembahasan).',
            default => 'Panjang ringkasan: SEDANG dan berimbang (sekitar 250 - 400 kata).',
        };

        $focusPrompt = ! empty($focus)
            ? "PERHATIAN KHUSUS DARI PENGGUNA: Berikan penekanan utama pada aspek berikut: \"{$focus}\"."
            : '';

        $prompt = <<<EOT
Anda adalah Asisten Pakar Ringkasan Dokumen dan Spesialis Edukasi RoboMath.
Tugas Anda adalah membaca, menganalisis, dan meringkas isi dokumen berjudul: "{$title}".

PANDUAN RINGKASAN:
- Gaya: {$styleInstruction}
- {$lengthInstruction}
- {$focusPrompt}
- Bahasa: Bahasa Indonesia yang baku, jelas, informatif, dan mudah dipahami.
- Format Output: Gunakan Markdown yang sangat menarik (heading ##, bullet points, teks tebal untuk istilah penting, quote > untuk kutipan atau intisari penting).
- Di bagian akhir, sertakan bagian khusus bertajuk "### 📌 Poin Kunci Utama" yang berisi 3 hingga 5 butir ringkas yang paling esensial.

ISI DOKUMEN YANG DIANALISIS:
---
{$truncatedText}
---

Silakan buat ringkasan terbaik Anda sekarang:
EOT;

        $providerName = 'gemini';
        $summaryText = '';
        $tokensUsed = null;

        try {
            // Send request to AI Manager
            $response = $this->aiManager->chat($prompt, $user, 'raw');
            $summaryText = $response->text;
            $providerName = $response->provider;
            $tokensUsed = $response->tokensUsed;
        } catch (\Throwable $e) {
            Log::error('AI Document Summarizer Error: '.$e->getMessage());
        }

        // If AI provider failed or returned generic mock fallback, provide enhanced structured summary
        if (empty($summaryText) || str_contains($summaryText, 'koneksi ke otak AI RoboBot di server sedang mengalami antrean padat')) {
            $fallback = $this->generateLocalStructuredSummary($title, $truncatedText, $style);
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
     * Extract key points from generated markdown text.
     *
     * @return list<string>
     */
    protected function extractKeyPointsFromMarkdown(string $markdown): array
    {
        $points = [];

        // Look for bullet lines starting with - or *
        if (preg_match_all('/^[*-]\s+(.+)$/m', $markdown, $matches)) {
            foreach ($matches[1] as $match) {
                $clean = trim(strip_tags($match));
                if (mb_strlen($clean) > 8 && mb_strlen($clean) < 300) {
                    $points[] = $clean;
                }
                if (count($points) >= 6) {
                    break;
                }
            }
        }

        if (empty($points)) {
            // Extract top sentences
            $sentences = preg_split('/(?<=[.?!])\s+/', strip_tags($markdown));
            foreach ($sentences as $sentence) {
                $s = trim($sentence);
                if (mb_strlen($s) > 20 && mb_strlen($s) < 250) {
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
     * Generate local structured summary when remote LLM is unavailable or offline.
     *
     * @return array{summary: string, key_points: list<string>}
     */
    protected function generateLocalStructuredSummary(string $title, string $text, string $style): array
    {
        $paragraphs = array_values(array_filter(
            array_map('trim', preg_split("/\n+/", $text)),
            fn ($p) => mb_strlen($p) > 25
        ));

        $previewText = ! empty($paragraphs) ? implode("\n\n", array_slice($paragraphs, 0, 3)) : mb_substr($text, 0, 400);

        // Extract key terms
        $words = str_word_count(strtolower(strip_tags($text)), 1);
        $stopWords = ['yang', 'untuk', 'pada', 'ke', 'para', 'namun', 'menurut', 'antara', 'dia', 'mereka', 'anda', 'kita', 'aku', 'kami', 'dan', 'atau', 'ini', 'itu', 'adalah', 'dengan', 'dari', 'dalam', 'bisa', 'dapat', 'harus'];
        $filteredWords = array_diff($words, $stopWords);
        $wordFreq = array_count_values(array_filter($filteredWords, fn ($w) => strlen($w) > 4));
        arsort($wordFreq);
        $topKeywords = array_slice(array_keys($wordFreq), 0, 5);
        $keywordsStr = ! empty($topKeywords) ? implode(', ', array_map('ucfirst', $topKeywords)) : 'Matematika, Logika, Konseptual';

        $points = [
            'Dokumen menyajikan bahasan utama seputar '.(! empty($topKeywords) ? ucfirst($topKeywords[0]) : 'topik materi').' dengan pendekatan sistematis.',
            'Fokus utama mencakup pemahaman konsep dasar, terminologi esensial, serta contoh penerapan konkret.',
            'Terdapat poin krusial mengenai struktur langkah penyelesaian masalah dan penguatan logika berpikir.',
            "Rangkuman mengidentifikasi kata kunci utama: {$keywordsStr} sebagai fondasi materi.",
        ];

        $summary = "## 📄 Ringkasan Dokumen: {$title}\n\n";
        $summary .= "> **Intisari:** Dokumen ini memuat materi penting dengan fokus pembahasan pada kata kunci: **{$keywordsStr}**.\n\n";
        $summary .= "### 📖 Ulasan Isi Dokumen\n\n";
        $summary .= $previewText."\n\n";
        $summary .= "### 🎯 Catatan Analisis & Pemahaman\n\n";
        $summary .= "- Dokumen menyajikan konsep yang relevan dan dapat dijadikan referensi bahan ajar ataupun materi pendukung siswa.\n";
        $summary .= "- Struktur materi tersusun secara berurutan dan mengarahkan pembaca pada pemahaman materi yang terpadu.\n";
        $summary .= "- Disarankan untuk memadukan intisari dokumen ini dengan latihan soal kontekstual agar retensi belajar lebih optimal.\n\n";
        $summary .= "### 📌 Poin Kunci Utama\n\n";
        foreach ($points as $p) {
            $summary .= '- '.$p."\n";
        }

        return [
            'summary' => $summary,
            'key_points' => $points,
        ];
    }
}
