<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class MathDatasetService
{
    /** Path to the Excel dataset relative to base_path(). */
    protected string $datasetPath;

    /** Cache TTL in seconds (24 hours). */
    protected int $cacheTtl = 86400;

    public function __construct()
    {
        $this->datasetPath = base_path('Dataset_Inventarisasi_Soal_Matematika_SD.xlsx');
    }

    /**
     * Return all dataset rows as an array of associative arrays.
     *
     * @return list<array{
     *     Grade: string,
     *     Material: string,
     *     QuestionForm: string,
     *     QuestionContext: string,
     *     AnswerForm: string,
     *     CognitiveLevel: string,
     *     Evidence: string
     * }>
     */
    public function all(): array
    {
        return Cache::remember('math_dataset_rows', $this->cacheTtl, fn () => $this->parseXlsx());
    }

    /**
     * Return the flat list of unique material (topic) names from the dataset.
     *
     * @return list<string>
     */
    public function materials(): array
    {
        return Cache::remember('math_dataset_materials', $this->cacheTtl, function () {
            $materials = array_unique(array_column($this->all(), 'Material'));
            sort($materials);

            return array_values($materials);
        });
    }

    /**
     * Return topics grouped by grade level.
     *
     * @return array<string, list<string>>
     */
    public function materialsByGrade(): array
    {
        return Cache::remember('math_dataset_by_grade', $this->cacheTtl, function () {
            $grouped = [];

            foreach ($this->all() as $row) {
                $grade = $row['Grade'];
                $material = $row['Material'];

                if (! isset($grouped[$grade])) {
                    $grouped[$grade] = [];
                }

                if (! in_array($material, $grouped[$grade], true)) {
                    $grouped[$grade][] = $material;
                }
            }

            ksort($grouped);

            return $grouped;
        });
    }

    /**
     * Build a formatted string listing all valid math topics for injection into AI prompts.
     */
    public function buildTopicListForPrompt(): string
    {
        $byGrade = $this->materialsByGrade();

        if (empty($byGrade)) {
            return 'matematika SD kelas 1–6 (operasi hitung, geometri, pecahan, data, dll.)';
        }

        $lines = [];
        foreach ($byGrade as $grade => $topics) {
            $lines[] = "  • {$grade}: ".implode(', ', $topics);
        }

        return implode("\n", $lines);
    }

    /**
     * Determine whether the given text appears to be a project presentation,
     * software specification, or non-curricular report.
     */
    public function isPresentationOrTechnicalDocument(string $text): bool
    {
        $lower = mb_strtolower($text);

        $presentationMarkers = [
            'slide 1', 'slide 2', 'team pbl', 'project owner', 'scrum master',
            'functional requirements', 'non-functional requirements',
            'user stories', 'sprint 1', 'sprint 2', 'use case', 'erd diagram',
            'arsitektur sistem', 'backend', 'frontend', 'framework',
        ];

        $markerHits = 0;
        foreach ($presentationMarkers as $marker) {
            if (str_contains($lower, $marker)) {
                $markerHits++;
                if ($markerHits >= 2) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Determine whether the given text appears to be math-related, based on keywords
     * extracted from the official dataset's material names and common math vocabulary.
     *
     * Returns true when the text contains at least $threshold matching math keywords.
     */
    public function isMathRelated(string $text, int $threshold = 2): bool
    {
        if (empty(trim($text))) {
            return false;
        }

        // Fast rejection for project pitch / presentations / technical software docs
        if ($this->isPresentationOrTechnicalDocument($text)) {
            return false;
        }

        $lowerText = mb_strtolower($text);

        // Build keyword list from dataset materials (cached alongside dataset)
        $datasetKeywords = Cache::remember('math_dataset_keywords', $this->cacheTtl, function () {
            $keywords = [];
            foreach ($this->materials() as $material) {
                // Split each material name into individual words (≥4 chars) as keywords
                $words = preg_split('/[\s,\-\/]+/u', mb_strtolower($material), -1, PREG_SPLIT_NO_EMPTY);
                foreach ($words as $word) {
                    if (mb_strlen($word) >= 4) {
                        $keywords[] = $word;
                    }
                }
            }

            return array_values(array_unique($keywords));
        });

        // Core math vocabulary strictly related to elementary mathematics concepts
        // Note: Generic words like 'soal', 'latihan', 'kuis', 'ujian' are intentionally excluded
        // because non-math project docs and quizzes also frequently mention them.
        $coreKeywords = [
            'bilangan', 'hitung', 'penjumlahan', 'pengurangan',
            'perkalian', 'pembagian', 'pecahan', 'desimal', 'persen', 'geometri', 'bangun',
            'luas', 'keliling', 'volume', 'sudut', 'segitiga', 'persegi', 'lingkaran',
            'kubus', 'balok', 'nilai tempat', 'bangun datar', 'bangun ruang',
            'diagram', 'grafik', 'statistik', 'rata-rata', 'median', 'modus',
            'kpk', 'fpb', 'prima', 'faktor', 'kelipatan', 'pangkat', 'akar', 'koordinat',
            'skala', 'rasio', 'proporsi', 'satuan panjang', 'satuan berat',
        ];

        $allKeywords = array_values(array_unique(array_merge($coreKeywords, $datasetKeywords)));

        $hits = 0;
        foreach ($allKeywords as $keyword) {
            if (str_contains($lowerText, $keyword)) {
                $hits++;
                if ($hits >= $threshold) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Return a preview list of sample materials from the dataset.
     *
     * @return list<string>
     */
    public function sampleMaterials(int $limit = 6): array
    {
        $materials = $this->materials();
        if (empty($materials)) {
            return [
                'Penjumlahan & Pengurangan Bilangan Cacah',
                'Nilai Tempat & Membandingkan Bilangan',
                'Pecahan Sederhana & Desimal',
                'Bangun Datar (Segitiga, Persegi, Lingkaran)',
                'Pengukuran Panjang, Berat & Waktu',
                'Penyajian Data & Diagram Gambar',
            ];
        }

        return array_slice($materials, 0, $limit);
    }

    /**
     * Flush all cached dataset data (call after replacing the Excel file).
     */
    public function flushCache(): void
    {
        Cache::forget('math_dataset_rows');
        Cache::forget('math_dataset_materials');
        Cache::forget('math_dataset_by_grade');
        Cache::forget('math_dataset_keywords');
    }

    /**
     * Parse the .xlsx file using ZipArchive + shared-strings XML (no external library).
     *
     * @return list<array<string, string>>
     */
    protected function parseXlsx(): array
    {
        if (! file_exists($this->datasetPath)) {
            Log::warning('MathDatasetService: dataset file not found at '.$this->datasetPath);

            return [];
        }

        $zip = new ZipArchive;
        if ($zip->open($this->datasetPath) !== true) {
            Log::error('MathDatasetService: cannot open dataset xlsx file.');

            return [];
        }

        // 1. Load shared strings table
        $sharedStrings = [];
        $ssIndex = $zip->locateName('xl/sharedStrings.xml');
        if ($ssIndex !== false) {
            $ssXml = $zip->getFromIndex($ssIndex);
            if ($ssXml) {
                $sharedStrings = $this->parseSharedStrings($ssXml);
            }
        }

        // 2. Load the first worksheet
        $wsIndex = $zip->locateName('xl/worksheets/sheet1.xml');
        $wsXml = $wsIndex !== false ? $zip->getFromIndex($wsIndex) : false;
        $zip->close();

        if (! $wsXml) {
            Log::error('MathDatasetService: sheet1.xml not found in dataset xlsx.');

            return [];
        }

        return $this->parseWorksheet($wsXml, $sharedStrings);
    }

    /**
     * Parse sharedStrings.xml into an indexed array of string values.
     *
     * @return list<string>
     */
    protected function parseSharedStrings(string $xml): array
    {
        $strings = [];

        try {
            $dom = new \DOMDocument;
            @$dom->loadXML($xml);
            $siNodes = $dom->getElementsByTagName('si');

            foreach ($siNodes as $si) {
                $tNodes = $si->getElementsByTagName('t');
                $value = '';
                foreach ($tNodes as $t) {
                    $value .= $t->nodeValue;
                }
                $strings[] = $value;
            }
        } catch (\Throwable $e) {
            Log::warning('MathDatasetService: error parsing sharedStrings.xml — '.$e->getMessage());
        }

        return $strings;
    }

    /**
     * Parse sheet1.xml into rows of associative arrays keyed by header names.
     *
     * @param  list<string>  $sharedStrings
     * @return list<array<string, string>>
     */
    protected function parseWorksheet(string $xml, array $sharedStrings): array
    {
        $rows = [];
        $headers = [];

        try {
            $dom = new \DOMDocument;
            @$dom->loadXML($xml);

            $rowNodes = $dom->getElementsByTagName('row');

            foreach ($rowNodes as $rowNode) {
                /** @var \DOMElement $rowNode */
                $rowIndex = (int) $rowNode->getAttribute('r');
                $cells = [];

                $cNodes = $rowNode->getElementsByTagName('c');
                foreach ($cNodes as $cNode) {
                    /** @var \DOMElement $cNode */
                    $cellRef = $cNode->getAttribute('r');
                    $type = $cNode->getAttribute('t');
                    $vNode = $cNode->getElementsByTagName('v')->item(0);
                    $rawValue = $vNode ? $vNode->nodeValue : '';

                    preg_match('/^([A-Z]+)/', $cellRef, $colMatch);
                    $colLetter = $colMatch[1] ?? 'A';
                    $colIndex = $this->columnLetterToIndex($colLetter);

                    if ($type === 's') {
                        $cells[$colIndex] = $sharedStrings[(int) $rawValue] ?? '';
                    } else {
                        $cells[$colIndex] = $rawValue;
                    }
                }

                if ($rowIndex === 1) {
                    $headers = $cells;
                } else {
                    if (empty($cells)) {
                        continue;
                    }

                    $assoc = [];
                    foreach ($headers as $colIdx => $headerName) {
                        $rawHeader = trim((string) $headerName);
                        $key = $this->normaliseHeader($rawHeader);
                        $assoc[$key] = trim((string) ($cells[$colIdx] ?? ''));
                    }

                    if (array_filter($assoc) !== []) {
                        $rows[] = $assoc;
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::error('MathDatasetService: error parsing sheet1.xml — '.$e->getMessage());
        }

        return $rows;
    }

    /**
     * Convert spreadsheet column letters (A, B, ..., Z, AA, AB, ...) to 0-based integer index.
     */
    protected function columnLetterToIndex(string $letters): int
    {
        $index = 0;
        $length = strlen($letters);
        for ($i = 0; $i < $length; $i++) {
            $index = $index * 26 + (ord($letters[$i]) - ord('A') + 1);
        }

        return $index - 1;
    }

    /**
     * Normalise Excel header names to short PHP-friendly keys.
     */
    protected function normaliseHeader(string $header): string
    {
        return match (true) {
            str_contains($header, 'Grade') => 'Grade',
            str_contains($header, 'Material') => 'Material',
            str_contains($header, 'Question Form') || $header === 'QF' => 'QuestionForm',
            str_contains($header, 'Question Context') || $header === 'QC' => 'QuestionContext',
            str_contains($header, 'Answer Form') || $header === 'AF' => 'AnswerForm',
            str_contains($header, 'Cognitive') || $header === 'CL' => 'CognitiveLevel',
            str_contains($header, 'Evidence') => 'Evidence',
            default => $header,
        };
    }
}
