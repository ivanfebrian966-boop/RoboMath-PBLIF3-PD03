<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['tugas_id', 'student_id', 'skor', 'status'])]
class PengumpulanTugas extends Model
{
    protected $table = 'pengumpulan_tugas';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'skor' => 'float',
        ];
    }

    public function tugas(): BelongsTo
    {
        return $this->belongsTo(Tugas::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
