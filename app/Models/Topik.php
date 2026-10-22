<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['title', 'slug', 'description', 'icon', 'color', 'kelas_level', 'order', 'category', 'is_active', 'document_summary_id', 'review_status', 'reviewed_by', 'reviewed_at'])]
class Topik extends Topic
{
    protected $table = 'topik';
}
