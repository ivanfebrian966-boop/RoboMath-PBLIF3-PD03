<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\Pivot;

#[Fillable(['user_id', 'badge_id', 'earned_at'])]
class PencapaianPengguna extends Pivot
{
    protected $table = 'pencapaian_pengguna';

    public $incrementing = true;

    protected function casts(): array
    {
        return [
            'earned_at' => 'datetime',
        ];
    }
}
