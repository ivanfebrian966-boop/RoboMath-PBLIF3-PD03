<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['document_summary_id', 'position', 'content'])]
class PotonganDokumen extends DocumentChunk
{
    protected $table = 'potongan_dokumen';
}
