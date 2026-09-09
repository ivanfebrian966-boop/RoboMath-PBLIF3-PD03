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
        User::updateOrCreate(
            ['email' => 'admin@algokids.id'],
            [
                'name' => 'Admin AlgoKids',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Guru
        $guru = User::updateOrCreate(
            ['email' => 'guru@algokids.id'],
            [
                'name' => 'Ibu Guru Budi',
                'password' => Hash::make('password'),
                'role' => 'guru',
            ]
        );

        // Orang Tua
        $parent = User::updateOrCreate(
            ['email' => 'orangtua@algokids.id'],
            [
                'name' => 'Bapak Hendra (Orang Tua)',
                'password' => Hash::make('password'),
                'role' => 'orangtua',
            ]
        );

        // Siswa 1
        $siswa1 = User::updateOrCreate(
            ['email' => 'siswa@algokids.id'],
            [
                'name' => 'Budi Pratama',
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'kelas' => 1,
                'total_score' => 120,
                'level' => 1,
            ]
        );

        // Siswa 2
        $siswa2 = User::updateOrCreate(
            ['email' => 'ani@algokids.id'],
            [
                'name' => 'Ani Lestari',
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'kelas' => 1,
                'total_score' => 250,
                'level' => 1,
            ]
        );

        // Relasi Orang tua - Siswa
        $parent->students()->syncWithoutDetaching([$siswa1->id, $siswa2->id]);
    }
}
