<?php

namespace Tests\Feature;

use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentClassJoinPreviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_preview_a_matching_class_and_teacher_from_its_code(): void
    {
        $teacher = User::factory()->create(['role' => 'guru', 'name' => 'Ibu Guru Budi']);
        $student = User::factory()->create(['role' => 'siswa', 'kelas' => 1]);
        ClassRoom::create([
            'teacher_id' => $teacher->id,
            'name' => 'Kelas Matematika 1 SD',
            'code' => 'RM7A9K',
            'kelas_level' => 1,
        ]);

        $response = $this->actingAs($student)
            ->postJson(route('siswa.kelas.join.preview'), ['code' => 'rm7a9k']);

        $response->assertOk()
            ->assertJsonPath('class.name', 'Kelas Matematika 1 SD')
            ->assertJsonPath('class.teacher', 'Ibu Guru Budi')
            ->assertJsonPath('can_join', true)
            ->assertJsonPath('already_joined', false);
    }

    public function test_student_cannot_preview_a_class_for_another_grade_as_joinable(): void
    {
        $teacher = User::factory()->create(['role' => 'guru']);
        $student = User::factory()->create(['role' => 'siswa', 'kelas' => 2]);
        ClassRoom::create([
            'teacher_id' => $teacher->id,
            'name' => 'Kelas 1 SD',
            'code' => 'CLASS1',
            'kelas_level' => 1,
        ]);

        $response = $this->actingAs($student)
            ->postJson(route('siswa.kelas.join.preview'), ['code' => 'CLASS1']);

        $response->assertOk()
            ->assertJsonPath('can_join', false)
            ->assertJsonPath('message', 'Tingkat kelasmu (Kelas 2 SD) tidak sesuai dengan kelas ini.');
    }

    public function test_student_gets_not_found_for_an_unknown_class_code(): void
    {
        $student = User::factory()->create(['role' => 'siswa']);

        $this->actingAs($student)
            ->postJson(route('siswa.kelas.join.preview'), ['code' => 'NOPE42'])
            ->assertNotFound()
            ->assertJsonPath('message', 'Kode kelas tidak ditemukan. Periksa kembali kode dari gurumu.');
    }

    public function test_guest_is_redirected_to_login_before_previewing_a_class(): void
    {
        $this->postJson(route('siswa.kelas.join.preview'), ['code' => 'RM7A9K'])
            ->assertUnauthorized();
    }

    public function test_teacher_cannot_preview_a_student_join_code(): void
    {
        $teacher = User::factory()->create(['role' => 'guru']);

        $this->actingAs($teacher)
            ->postJson(route('siswa.kelas.join.preview'), ['code' => 'RM7A9K'])
            ->assertRedirect(route('guru.dashboard'));
    }

    public function test_student_must_submit_a_six_character_alphanumeric_code_for_preview(): void
    {
        $student = User::factory()->create(['role' => 'siswa']);

        $this->actingAs($student)
            ->postJson(route('siswa.kelas.join.preview'), ['code' => 'short'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('code');
    }
}
