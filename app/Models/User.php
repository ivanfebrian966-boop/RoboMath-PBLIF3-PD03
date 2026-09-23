<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'avatar', 'kelas', 'total_score', 'level', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
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

    public function isOrangtua(): bool
    {
        return $this->role === 'orangtua';
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
        return $this->hasMany(QuizAttempt::class);
    }

    public function progress(): HasMany
    {
        return $this->hasMany(Progress::class);
    }

    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'user_badges')->withPivot('earned_at')->withTimestamps();
    }

    public function chatMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    /**
     * Students linked to this parent
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'parent_student', 'parent_id', 'student_id')->withTimestamps();
    }

    /**
     * Parents linked to this student
     */
    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'parent_student', 'student_id', 'parent_id')->withTimestamps();
    }

    /**
     * ClassRooms this teacher manages (guru) or student enrolled in (siswa)
     */
    public function classRooms(): BelongsToMany
    {
        return $this->belongsToMany(ClassRoom::class, 'class_room_student', 'student_id', 'class_room_id')
            ->withPivot('joined_at')
            ->withTimestamps();
    }

    /**
     * ClassRooms this teacher teaches
     */
    public function teachingClassRooms(): HasMany
    {
        return $this->hasMany(ClassRoom::class, 'teacher_id');
    }

    // === Gamification Helpers ===

    public function addScore(int $points): void
    {
        $this->increment('total_score', $points);
        $this->updateLevel();
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
