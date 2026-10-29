<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::firstOrCreate(
            ['email' => 'admin@robomath.id'],
            [
                'name' => 'Admin RoboMath',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Guru
        User::firstOrCreate(
            ['email' => 'guru@robomath.id'],
            [
                'name' => 'Ibu Guru Budi',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'is_active' => true,
            ]
        );

        // Siswa 1
        User::firstOrCreate(
            ['email' => 'siswa@robomath.id'],
            [
                'name' => 'Budi Pratama',
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'kelas' => 1,
                'total_score' => 130,
                'level' => 1,
                'streak_saat_ini' => 1,
                'streak_terbaik' => 1,
                'is_active' => true,
            ]
        );

        // Siswa 2
        User::firstOrCreate(
            ['email' => 'ani@robomath.id'],
            [
                'name' => 'Ani Lestari',
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'kelas' => 1,
                'total_score' => 250,
                'level' => 1,
                'streak_saat_ini' => 3,
                'streak_terbaik' => 5,
                'is_active' => true,
            ]
        );

        // Alias for backwards compatibility
        User::firstOrCreate(
            ['email' => 'siswa@algokids.id'],
            [
                'name' => 'Budi Pratama',
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'kelas' => 1,
                'total_score' => 130,
                'level' => 1,
                'is_active' => true,
            ]
        );
        User::firstOrCreate(
            ['email' => 'guru@algokids.id'],
            [
                'name' => 'Ibu Guru Budi',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'is_active' => true,
            ]
        );
    }
}
