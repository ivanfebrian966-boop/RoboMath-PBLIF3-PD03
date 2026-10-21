<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'slug', 'description', 'icon', 'color', 'kelas_level', 'order', 'category', 'is_active', 'document_summary_id'])]
class Topic extends Model
{
    protected function casts(): array
    {
        return [
            'kelas_level' => 'integer',
            'order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    /**
     * Dokumen asal yang diproses AI menjadi topik ini
     */
    public function documentSummary(): BelongsTo
    {
        return $this->belongsTo(DocumentSummary::class);
    }

    /**
     * Kelas-kelas yang mengaktifkan topik ini
     */
    public function classRooms(): BelongsToMany
    {
        return $this->belongsToMany(ClassRoom::class, 'class_room_topic')
            ->withTimestamps();
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'urutan' => 'Urutan (Sequencing)',
            'percabangan' => 'Percabangan (Selection)',
            'pengulangan' => 'Pengulangan (Looping)',
            'pola' => 'Pola & Pattern',
            'dekomposisi' => 'Dekomposisi',
            default => $this->category,
        };
    }

    public function getCategoryIconAttribute(): string
    {
        return match ($this->category) {
            'urutan' => '📋',
            'percabangan' => '🔀',
            'pengulangan' => '🔄',
            'pola' => '🧩',
            'dekomposisi' => '🔍',
            default => '📚',
        };
    }
}
