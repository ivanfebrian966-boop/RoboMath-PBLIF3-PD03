<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentSummary extends Model
{
    protected $table = 'dokumen';

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
        'status',
        'error_message',
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
     * Admin yang mengunggah dokumen
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class);
    }

    /**
     * Topik-topik yang dihasilkan AI dari dokumen ini
     */
    public function topics(): HasMany
    {
        return $this->hasMany(Topik::class);
    }

    /**
     * Soal-soal yang dihasilkan AI dari dokumen ini (Bank Soal Global)
     */
    public function quizzes(): HasMany
    {
        return $this->hasMany(Soal::class);
    }

    public function chunks(): HasMany
    {
        return $this->hasMany(PotonganDokumen::class)->orderBy('position');
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
