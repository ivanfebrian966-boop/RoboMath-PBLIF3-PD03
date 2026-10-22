<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;

#[Fillable(['name', 'email', 'password', 'role', 'avatar', 'kelas', 'total_score', 'level', 'is_active', 'streak_saat_ini', 'streak_terbaik', 'school_id'])]
#[Hidden(['password', 'remember_token'])]
class Pengguna extends User
{
    protected $table = 'pengguna';
}
