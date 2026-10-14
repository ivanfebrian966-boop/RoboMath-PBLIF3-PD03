<?php

namespace Tests\Feature;

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
    }
}
