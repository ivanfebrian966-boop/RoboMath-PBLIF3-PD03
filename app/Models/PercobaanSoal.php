<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['user_id', 'quiz_id', 'sesi_belajar_id', 'answer', 'is_correct', 'ai_feedback', 'time_spent', 'points_earned'])]
class PercobaanSoal extends QuizAttempt
{
    protected $table = 'percobaan_soal';
}
