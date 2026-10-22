<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['teacher_id', 'school_id', 'name', 'code', 'kelas_level', 'is_active', 'nilai_lulus'])]
class ClassRoom extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'kelas_level' => 'integer',
            'is_active' => 'boolean',
            'nilai_lulus' => 'float',
        ];
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'teacher_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Pengguna::class, 'anggota_kelas', 'class_room_id', 'student_id')
            ->using(AnggotaKelas::class)
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

    /**
     * Topik yang diaktifkan guru untuk kelas ini (Bank Soal Global → Kelas)
     */
    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(Topik::class, 'kelas_topik', 'class_room_id', 'topic_id')
            ->withTimestamps();
    }

    /**
     * Ambil semua soal yang tersedia untuk kelas ini
     * (soal dari topik yang dipilih guru)
     */
    public function availableQuizzes(): Builder
    {
        return Soal::whereIn('topic_id', $this->topics()->pluck('topik.id'))
            ->where('is_active', true)
            ->where('review_status', 'approved')
            ->whereHas('topic', fn ($query) => $query
                ->where('is_active', true)
                ->where('review_status', 'approved'));
    }
}
