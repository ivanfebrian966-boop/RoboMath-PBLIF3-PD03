<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama_sekolah', 'nama_kota', 'status'])]
class Sekolah extends Model
{
    protected $table = 'sekolah';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => 'string',
        ];
    }

    public function isAktif(): bool
    {
        return $this->status === 'A';
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'school_id');
    }

    public function classRooms(): HasMany
    {
        return $this->hasMany(ClassRoom::class, 'school_id');
    }
}
