<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'topic_id', 'lesson_id', 'dimulai_pada', 'selesai_pada', 'poin_diperoleh'])]
class SesiBelajar extends Model
{
    protected $table = 'sesi_belajar';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'dimulai_pada' => 'datetime',
            'selesai_pada' => 'datetime',
            'poin_diperoleh' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Durasi sesi dalam detik.
     */
    public function getDurasiDetikAttribute(): int
    {
        if (! $this->selesai_pada) {
            return 0;
        }

        return (int) $this->dimulai_pada->diffInSeconds($this->selesai_pada);
    }
}
