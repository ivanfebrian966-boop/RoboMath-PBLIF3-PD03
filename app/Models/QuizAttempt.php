<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'quiz_id', 'sesi_belajar_id', 'answer', 'is_correct', 'ai_feedback', 'time_spent', 'points_earned'])]
class QuizAttempt extends Model
{
    protected $table = 'percobaan_soal';

    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
            'time_spent' => 'integer',
            'points_earned' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class);
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Soal::class, 'quiz_id');
    }

    public function sesiBelajar(): BelongsTo
    {
        return $this->belongsTo(SesiBelajar::class);
    }
}
