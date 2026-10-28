<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'avatar', 'kelas', 'total_score', 'level', 'is_active', 'streak_saat_ini', 'streak_terbaik', 'school_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    protected $table = 'pengguna';

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'kelas' => 'integer',
            'total_score' => 'integer',
            'level' => 'integer',
            'is_active' => 'boolean',
            'streak_saat_ini' => 'integer',
            'streak_terbaik' => 'integer',
        ];
    }

    // === Role Helpers ===

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    public function isActiveAccount(): bool
    {
        return (bool) $this->is_active;
    }

    // === Relationships ===

    public function quizAttempts(): HasMany
    {
        return $this->hasMany(PercobaanSoal::class, 'user_id');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(Progress::class, 'user_id');
    }

    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Pencapaian::class, 'pencapaian_pengguna', 'user_id', 'badge_id')
            ->using(PencapaianPengguna::class)
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    /**
     * ClassRooms this teacher manages (guru) or student enrolled in (siswa)
     */
    public function classRooms(): BelongsToMany
    {
        return $this->belongsToMany(Kelas::class, 'anggota_kelas', 'student_id', 'class_room_id')
            ->using(AnggotaKelas::class)
            ->withPivot('joined_at')
            ->withTimestamps();
    }

    /**
     * ClassRooms this teacher teaches
     */
    public function teachingClassRooms(): HasMany
    {
        return $this->hasMany(Kelas::class, 'teacher_id');
    }

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'school_id');
    }

    public function sesiBelajar(): HasMany
    {
        return $this->hasMany(SesiBelajar::class, 'user_id');
    }

    public function misiPengguna(): HasMany
    {
        return $this->hasMany(MisiPengguna::class, 'user_id');
    }

    public function pengumpulanTugas(): HasMany
    {
        return $this->hasMany(PengumpulanTugas::class, 'student_id');
    }

    // === Gamification Helpers ===

    public function addScore(int $points): void
    {
        $this->increment('total_score', $points);
        $this->updateLevel();
        $this->updateStreak();
    }

    public function updateStreak(): void
    {
        $this->increment('streak_saat_ini');
        if ($this->streak_saat_ini > $this->streak_terbaik) {
            $this->update(['streak_terbaik' => $this->streak_saat_ini]);
        }
    }

    public function resetStreak(): void
    {
        $this->update(['streak_saat_ini' => 0]);
    }

    public function updateLevel(): void
    {
        $score = $this->total_score;
        $level = match (true) {
            $score >= 5000 => 5, // Diamond
            $score >= 3000 => 4, // Platinum
            $score >= 1500 => 3, // Gold
            $score >= 500 => 2,  // Silver
            default => 1,        // Bronze
        };
        $this->update(['level' => $level]);
    }

    public function getLevelNameAttribute(): string
    {
        return match ($this->level) {
            5 => 'Diamond',
            4 => 'Platinum',
            3 => 'Gold',
            2 => 'Silver',
            default => 'Bronze',
        };
    }

    public function getLevelColorAttribute(): string
    {
        return match ($this->level) {
            5 => '#B9F2FF',
            4 => '#E5E4E2',
            3 => '#FFD700',
            2 => '#C0C0C0',
            default => '#CD7F32',
        };
    }
}
