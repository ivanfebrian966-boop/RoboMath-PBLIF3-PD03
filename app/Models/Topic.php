<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'slug', 'description', 'icon', 'color', 'kelas_level', 'order', 'category', 'is_active', 'document_summary_id', 'review_status', 'reviewed_by', 'reviewed_at'])]
class Topic extends Model
{
    protected $table = 'topik';

    public function scopeAvailableToStudent(Builder $query, User $student): Builder
    {
        return $query
            ->where('is_active', true)
            ->where('review_status', 'approved')
            ->where('kelas_level', $student->kelas)
            ->whereHas('classRooms', fn (Builder $classRooms) => $classRooms
                ->where('kelas.is_active', true)
                ->whereHas('students', fn (Builder $students) => $students->whereKey($student->id)));
    }

    protected function casts(): array
    {
        return [
            'kelas_level' => 'integer',
            'order' => 'integer',
            'is_active' => 'boolean',
            'reviewed_at' => 'datetime',
        ];
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Soal::class);
    }

    /**
     * Dokumen asal yang diproses AI menjadi topik ini
     */
    public function documentSummary(): BelongsTo
    {
        return $this->belongsTo(Dokumen::class, 'document_summary_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Kelas-kelas yang mengaktifkan topik ini
     */
    public function classRooms(): BelongsToMany
    {
        return $this->belongsToMany(Kelas::class, 'kelas_topik', 'topic_id', 'class_room_id')
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
