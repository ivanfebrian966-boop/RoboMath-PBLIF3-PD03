<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name', 'slug', 'description', 'icon', 'color', 'requirement_type', 'requirement_value'])]
class Badge extends Model
{
    protected $table = 'pencapaian';

    protected function casts(): array
    {
        return [
            'requirement_value' => 'integer',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(Pengguna::class, 'pencapaian_pengguna', 'badge_id', 'user_id')
            ->using(PencapaianPengguna::class)
            ->withPivot('earned_at')
            ->withTimestamps();
    }
}
