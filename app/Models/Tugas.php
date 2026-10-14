<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['class_room_id', 'topic_id', 'judul', 'tenggat_waktu', 'nilai_lulus'])]
class Tugas extends Model
{
    protected $table = 'tugas';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tenggat_waktu' => 'datetime',
            'nilai_lulus' => 'float',
        ];
    }

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class, 'class_room_id');
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function pengumpulan(): HasMany
    {
        return $this->hasMany(PengumpulanTugas::class);
    }
}
