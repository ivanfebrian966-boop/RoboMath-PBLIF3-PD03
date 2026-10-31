<?php

namespace Tests\Feature;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_shows_active_user_counts_and_paginates_students(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->create(['role' => 'guru', 'is_active' => true]);
        User::factory()->create(['role' => 'siswa', 'is_active' => false, 'name' => 'Siswa Nonaktif']);

        foreach (range(1, 6) as $number) {
            User::factory()->create([
                'role' => 'siswa',
                'kelas' => $number < 6 ? 1 : 4,
                'name' => "Siswa {$number}",
                'total_score' => $number * 10,
            ]);
        }

        $firstPage = $this->actingAs($admin)->get(route('admin.dashboard'));

        $firstPage->assertSee('Dashboard &amp; Laporan Performa', false)
            ->assertSee('Total Siswa')
            ->assertSee('6', false)
            ->assertSee('Menampilkan 1–5 dari 6 siswa')
            ->assertSee('Siswa 5')
            ->assertDontSee('Siswa 1')
            ->assertDontSee('Siswa Nonaktif');

        $this->get(route('admin.dashboard', ['page' => 2]))
            ->assertSee('Menampilkan 6–6 dari 6 siswa')
            ->assertSee('Siswa 1');
    }

    public function test_admin_dashboard_filters_students_and_shows_accuracy_by_grade(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $classOneStudent = User::factory()->create([
            'role' => 'siswa',
            'kelas' => 1,
            'name' => 'Siswa Kelas Satu',
        ]);
        $classFourStudent = User::factory()->create([
            'role' => 'siswa',
            'kelas' => 4,
            'name' => 'Siswa Kelas Empat',
        ]);
        $topic = Topic::create([
            'title' => 'Topik Dashboard Admin',
            'slug' => 'topik-dashboard-admin',
            'description' => 'Topik untuk menguji laporan performa admin.',
            'kelas_level' => 1,
            'order' => 1,
            'category' => 'pola',
            'is_active' => true,
            'review_status' => 'approved',
        ]);
        $quiz = Quiz::create([
            'topic_id' => $topic->id,
            'type' => 'pilihan_ganda',
            'question' => 'Berapa hasil satu tambah satu?',
            'options' => ['2', '3'],
            'correct_answer' => '2',
            'difficulty' => 'mudah',
            'points' => 10,
            'is_active' => true,
            'review_status' => 'approved',
        ]);
        QuizAttempt::create([
            'user_id' => $classOneStudent->id,
            'quiz_id' => $quiz->id,
            'answer' => '2',
            'is_correct' => true,
        ]);
        QuizAttempt::create([
            'user_id' => $classOneStudent->id,
            'quiz_id' => $quiz->id,
            'answer' => '3',
            'is_correct' => false,
        ]);
        QuizAttempt::create([
            'user_id' => $classFourStudent->id,
            'quiz_id' => $quiz->id,
            'answer' => '2',
            'is_correct' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard', ['kelas' => 4]))
            ->assertSee('Siswa Kelas Empat')
            ->assertDontSee('Siswa Kelas Satu')
            ->assertSee('Kelas 1 SD')
            ->assertSee('1 siswa · 1 percobaan')
            ->assertSee('Kelas 4 SD')
            ->assertSee('50%')
            ->assertSee('100%');
    }

    public function test_student_cannot_access_admin_dashboard(): void
    {
        $student = User::factory()->create(['role' => 'siswa']);

        $this->actingAs($student)
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('siswa.dashboard'));
    }

    public function test_admin_dashboard_rejects_an_unknown_grade_filter(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.dashboard', ['kelas' => '1 or 1=1']))
            ->assertSessionHasErrors('kelas');
    }
}
