<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentQuizTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_quiz_page_invites_students_to_join_a_class_with_a_code(): void
    {
        $student = User::factory()->create(['role' => 'siswa']);

        $response = $this->actingAs($student)->get(route('siswa.quiz'));

        $response->assertOk()
            ->assertSee('Cari topik latihan...')
            ->assertSee('Masuk Kelas dengan Kode')
            ->assertSee('role="dialog"', false)
            ->assertSee('Masuk ke kelas', false)
            ->assertDontSee('href="'.route('siswa.kelas.join').'"', false);
    }

    public function test_student_can_open_the_class_code_modal_from_the_empty_tasks_page(): void
    {
        $student = User::factory()->create(['role' => 'siswa']);

        $response = $this->actingAs($student)->get(route('siswa.tugas.index'));

        $response->assertOk()
            ->assertSee('Gabung Kelas')
            ->assertSee('role="dialog"', false)
            ->assertDontSee('href="'.route('siswa.kelas.join').'"', false);
    }

    public function test_student_class_join_modal_has_six_character_inputs_and_paste_action(): void
    {
        $student = User::factory()->create(['role' => 'siswa']);

        $this->actingAs($student)
            ->get(route('siswa.quiz'))
            ->assertSee('Kode kelas dari guru')
            ->assertSee('Tempel kode')
            ->assertSee('Batal')
            ->assertSee('Masuk ke kelas');
    }
}
