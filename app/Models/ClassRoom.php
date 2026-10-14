<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['teacher_id', 'school_id', 'name', 'code', 'kelas_level', 'topic_ids', 'is_active', 'nilai_lulus'])]
class ClassRoom extends Model
{
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'topic_ids' => 'array',
            'kelas_level' => 'integer',
            'is_active' => 'boolean',
            'nilai_lulus' => 'float',
        ];
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_room_student', 'class_room_id', 'student_id')
            ->withPivot('joined_at')
            ->withTimestamps();
    }

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'school_id');
    }

    public function tugas(): HasMany
    {
        return $this->hasMany(Tugas::class, 'class_room_id');
    }
}
