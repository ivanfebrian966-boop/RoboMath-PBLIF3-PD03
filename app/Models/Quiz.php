<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['lesson_id', 'topic_id', 'type', 'question', 'options', 'correct_answer', 'explanation', 'difficulty', 'points', 'is_active'])]
class Quiz extends Model
{
    protected function casts(): array
    {
        return [
            'options' => 'array',
            'points' => 'integer',
            'is_active' => 'boolean',
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

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'pilihan_ganda' => 'Pilihan Ganda',
            'drag_drop' => 'Drag & Drop',
            'cerita_logika' => 'Cerita Logika',
            default => $this->type,
        };
    }
}
