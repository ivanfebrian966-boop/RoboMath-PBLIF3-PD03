<?php

namespace Tests\Feature;

use App\Models\ClassRoom;
use App\Models\Lesson;
use App\Models\Progress;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class TeacherDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_dashboard_displays_student_lesson_completion_charts(): void
    {
        $slugSuffix = Str::lower(Str::random(8));
        $teacher = User::factory()->create(['role' => 'guru']);
        $student = User::factory()->create([
            'name' => 'Siswa Uji',
            'role' => 'siswa',
            'kelas' => 4,
        ]);
        $classRoom = ClassRoom::create([
            'teacher_id' => $teacher->id,
            'name' => 'Kelas Uji',
            'code' => Str::upper(Str::random(8)),
            'kelas_level' => 4,
        ]);
        $classRoom->students()->attach($student->id);

        $topic = Topic::create([
            'title' => 'Topik Uji',
            'slug' => "topik-uji-{$slugSuffix}",
            'description' => 'Topik untuk menguji grafik ketuntasan.',
            'kelas_level' => 4,
            'order' => 1,
            'category' => 'pola',
            'is_active' => true,
            'review_status' => 'approved',
        ]);
        $classRoom->topics()->attach($topic->id);

        $lessons = collect(range(1, 4))->map(fn (int $number): Lesson => Lesson::create([
            'topic_id' => $topic->id,
            'title' => "Materi Uji {$number}",
            'slug' => "materi-uji-{$slugSuffix}-{$number}",
            'content' => 'Isi materi pengujian.',
            'order' => $number,
        ]));

        foreach ($lessons->take(2) as $lesson) {
            Progress::create([
                'user_id' => $student->id,
                'topic_id' => $topic->id,
                'lesson_id' => $lesson->id,
                'status' => 'selesai',
            ]);
        }

        $response = $this->actingAs($teacher)->get(route('guru.dashboard'));

        $response->assertSee('Ketuntasan per siswa')
            ->assertSee('Ketuntasan seluruh kelas')
            ->assertSee('Siswa Uji')
            ->assertSee('aria-valuenow="50"', false)
            ->assertSee('50%')
            ->assertSee('2 materi')
            ->assertSee('Ringkasan tingkat kelas')
            ->assertSee('Aktivitas latihan siswa terkini')
            ->assertSee('Sesi belajar terbaru')
            ->assertSee('Daftar siswa');
    }
}
