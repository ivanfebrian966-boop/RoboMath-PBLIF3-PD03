<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentSummary extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'original_filename',
        'file_type',
        'file_size',
        'file_path',
        'extracted_text',
        'summary',
        'key_points',
        'summary_style',
        'summary_length',
        'word_count_original',
        'word_count_summary',
        'ai_provider',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'key_points' => 'array',
        'file_size' => 'integer',
        'word_count_original' => 'integer',
        'word_count_summary' => 'integer',
    ];

    /**
     * Get the user that owns the document summary.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get human-readable file size.
     */
    public function getFormattedFileSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1).' MB';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 1).' KB';
        }

        return $bytes.' B';
    }

    /**
     * Get human-readable compression ratio.
     */
    public function getCompressionRatioAttribute(): int
    {
        if ($this->word_count_original <= 0 || $this->word_count_summary <= 0) {
            return 0;
        }

        $reduction = 1 - ($this->word_count_summary / $this->word_count_original);

        return max(0, min(99, (int) round($reduction * 100)));
    }

    /**
     * Get label for summary style.
     */
    public function getStyleLabelAttribute(): string
    {
        return match ($this->summary_style) {
            'ringkasan_eksekutif' => 'Intisari & Poin Penting',
            'ringkasan_lengkap' => 'Rangkuman Komprehensif',
            'anak_sd' => 'Ramah Anak SD',
            'peta_konsep' => 'Peta Konsep & Istilah',
            'kuis_latihan' => 'Kuis Evaluasi',
            default => 'Ringkasan Cerdas',
        };
    }
}
