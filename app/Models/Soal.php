<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;

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
class Soal extends Quiz
{
    protected $table = 'soal';
}
