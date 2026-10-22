<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'topic_id', 'lesson_id', 'status', 'score', 'completed_at'])]
class Progress extends Model
{
    protected $table = 'progress';

    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'user_id');
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topik::class, 'topic_id');
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}
