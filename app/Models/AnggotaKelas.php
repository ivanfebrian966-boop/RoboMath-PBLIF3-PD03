<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\Pivot;

#[Fillable(['class_room_id', 'student_id', 'joined_at'])]
class AnggotaKelas extends Pivot
{
    protected $table = 'anggota_kelas';

    public $incrementing = true;

    protected function casts(): array
    {
        return [
            'joined_at' => 'datetime',
        ];
    }
}
