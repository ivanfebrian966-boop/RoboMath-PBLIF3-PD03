<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'slug', 'description', 'icon', 'color', 'requirement_type', 'requirement_value'])]
class Pencapaian extends Badge
{
    protected $table = 'pencapaian';
}
