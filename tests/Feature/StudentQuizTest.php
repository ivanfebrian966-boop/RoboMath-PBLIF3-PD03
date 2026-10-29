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
            ->assertSee(route('siswa.kelas.join'), false);
    }
}
