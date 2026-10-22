<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'misi_id', 'judul', 'deskripsi', 'status'])]
class MisiPengguna extends Model
{
    protected $table = 'misi_pengguna';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => 'string',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'user_id');
    }

    public function misi(): BelongsTo
    {
        return $this->belongsTo(Misi::class);
    }

    public function isSelesai(): bool
    {
        return $this->status === 'selesai';
    }
}
