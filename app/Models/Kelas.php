<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['teacher_id', 'school_id', 'name', 'code', 'kelas_level', 'is_active', 'nilai_lulus'])]
class Kelas extends ClassRoom
{
    protected $table = 'kelas';
}
