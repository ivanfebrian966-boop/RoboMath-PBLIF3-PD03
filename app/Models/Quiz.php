<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'lesson_id',
    'topic_id',
    'document_summary_id',
    'type',
    'question',
    'options',
    'correct_answer',
    'explanation',
    'difficulty',
    'points',
    'is_active',
    'review_status',
    'admin_notes',
    'reviewed_by',
    'reviewed_at',
])]
class Quiz extends Model
{
    protected function casts(): array
    {
        return [
            'options' => 'array',
            'points' => 'integer',
            'is_active' => 'boolean',
            'reviewed_at' => 'datetime',
        ];
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function documentSummary(): BelongsTo
    {
        return $this->belongsTo(DocumentSummary::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'pilihan_ganda' => 'Pilihan Ganda',
            'essay', 'uraian' => 'Essay / Uraian',
            'drag_drop' => 'Drag & Drop',
            'cerita_logika' => 'Cerita Logika',
            default => $this->type,
        };
    }

    public function isEssay(): bool
    {
        return in_array($this->type, ['essay', 'uraian']);
    }
}
