<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['document_summary_id', 'position', 'content'])]
class DocumentChunk extends Model
{
    protected $table = 'potongan_dokumen';

    protected function casts(): array
    {
        return [
            'position' => 'integer',
        ];
    }

    public function documentSummary(): BelongsTo
    {
        return $this->belongsTo(Dokumen::class, 'document_summary_id');
    }
}
