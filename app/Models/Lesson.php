<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['topic_id', 'title', 'slug', 'content', 'video_url', 'illustration', 'order', 'difficulty', 'estimated_minutes', 'is_active'])]
class Lesson extends Model
{
    protected function casts(): array
    {
        return [
            'order' => 'integer',
            'estimated_minutes' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    public function progress(): HasMany
    {
        return $this->hasMany(Progress::class);
    }

    public function getDifficultyLabelAttribute(): string
    {
        return match ($this->difficulty) {
            'mudah' => '⭐ Mudah',
            'sedang' => '⭐⭐ Sedang',
            'sulit' => '⭐⭐⭐ Sulit',
            default => $this->difficulty,
        };
    }
}
