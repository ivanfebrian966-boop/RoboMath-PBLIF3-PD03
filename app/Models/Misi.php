<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['judul', 'deskripsi', 'poin', 'is_active'])]
class Misi extends Model
{
    protected $table = 'misi';

    protected function casts(): array
    {
        return [
            'poin' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function penggunaMisi(): HasMany
    {
        return $this->hasMany(MisiPengguna::class);
    }
}
