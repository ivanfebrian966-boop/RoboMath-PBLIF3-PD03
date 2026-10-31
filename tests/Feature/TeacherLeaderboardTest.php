<?php

namespace Tests\Feature;

use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class TeacherLeaderboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_progress_page_matches_the_teacher_dashboard_and_only_shows_their_students(): void
    {
        $teacher = User::factory()->create(['role' => 'guru']);
        $otherTeacher = User::factory()->create(['role' => 'guru']);
        $student = User::factory()->create([
            'name' => 'Siswa Pantauan',
            'email' => 'pantauan@example.test',
            'role' => 'siswa',
            'kelas' => 4,
            'total_score' => 250,
        ]);
        $runnerUp = User::factory()->create([
            'name' => 'Siswa Posisi Dua',
            'role' => 'siswa',
            'kelas' => 4,
            'total_score' => 100,
        ]);
        $otherStudent = User::factory()->create([
            'name' => 'Siswa Guru Lain',
            'email' => 'guru-lain@example.test',
            'role' => 'siswa',
            'kelas' => 4,
        ]);
        $teacherClass = ClassRoom::create([
            'teacher_id' => $teacher->id,
            'name' => 'Kelas Pantauan',
            'code' => Str::upper(Str::random(8)),
            'kelas_level' => 4,
        ]);
        $otherClass = ClassRoom::create([
            'teacher_id' => $otherTeacher->id,
            'name' => 'Kelas Guru Lain',
            'code' => Str::upper(Str::random(8)),
            'kelas_level' => 4,
        ]);
        $teacherClass->students()->attach($student->id);
        $teacherClass->students()->attach($runnerUp->id);
        $otherClass->students()->attach($otherStudent->id);

        $response = $this->actingAs($teacher)->get(route('guru.leaderboard'));

        $response->assertOk()
            ->assertSee('Leaderboard')
            ->assertSee('Unduh Excel')
            ->assertSee('laporan/download/pdf?kelas=all')
            ->assertSee('Tingkat kelas')
            ->assertSee('Papan peringkat siswa')
            ->assertSee('Juara kelas')
            ->assertSee('Unggul 150 poin dari peringkat kedua.')
            ->assertSee('Perlu perhatian')
            ->assertSee('Akurasi 0%')
            ->assertSee('Siswa Pantauan')
            ->assertSee('pantauan@example.test')
            ->assertSee('Siswa Posisi Dua')
            ->assertDontSee('Siswa Guru Lain')
            ->assertDontSee('guru-lain@example.test');
    }
}
