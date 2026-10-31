<?php

namespace Tests\Feature;

use App\Models\ClassRoom;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_access_dashboard(): void
    {
        $this->seed();

        $student = User::where('role', 'siswa')->first();

        $response = $this->actingAs($student)->get(route('siswa.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Halo,');
        $response->assertSee('bg-[#EF383A]', false);
    }

    public function test_student_dashboard_shows_four_class_topics_per_page(): void
    {
        $this->seed();

        $student = User::where('email', 'siswa@robomath.id')->firstOrFail();
        $teacher = User::where('email', 'guru@robomath.id')->firstOrFail();
        $classRoom = ClassRoom::create([
            'teacher_id' => $teacher->id,
            'name' => 'Kelas Satu',
            'code' => 'PAGE01',
            'kelas_level' => 1,
        ]);
        $topics = Topic::where('kelas_level', 1)->orderBy('order')->get();
        $classRoom->topics()->sync($topics->modelKeys());
        $classRoom->students()->attach($student->id, ['joined_at' => now()]);

        $firstPage = $this->actingAs($student)->get(route('siswa.dashboard'));

        $firstPage->assertSee('Menampilkan 1–4 dari 5 modul')
            ->assertDontSee('Dekomposisi (Decomposition)');

        $secondPage = $this->get(route('siswa.dashboard', ['page' => 2]));

        $secondPage->assertSee('Menampilkan 5–5 dari 5 modul')
            ->assertSee('Dekomposisi (Decomposition)')
            ->assertDontSee('Urutan (Sequencing)');
    }
}
