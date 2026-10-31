<?php

namespace Tests\Feature;

use App\Models\ClassRoom;
use App\Models\Lesson;
use App\Models\Progress;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\SesiBelajar;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class TeacherStudentDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_can_view_student_progress_summary_sessions_and_topic_accuracy(): void
    {
        $teacher = User::factory()->create(['role' => 'guru']);
        $student = User::factory()->create([
            'name' => 'Budi Pratama',
            'email' => 'budi@example.test',
            'role' => 'siswa',
            'kelas' => 1,
            'total_score' => 175,
            'level' => 1,
        ]);
        $classRoom = ClassRoom::create([
            'teacher_id' => $teacher->id,
            'name' => 'Kelas Satu',
            'code' => Str::upper(Str::random(8)),
            'kelas_level' => 1,
        ]);
        $classRoom->students()->attach($student->id);
        $classRoom->students()->attach(User::factory()->create([
            'role' => 'siswa',
            'kelas' => 1,
            'total_score' => 250,
        ])->id);

        $topic = Topic::create([
            'title' => 'Urutan (Sequencing)',
            'slug' => 'urutan-sequencing-detail',
            'description' => 'Topik urutan untuk detail progres.',
            'kelas_level' => 1,
            'order' => 1,
            'category' => 'urutan',
            'is_active' => true,
            'review_status' => 'approved',
        ]);
        $classRoom->topics()->attach($topic->id);
        $lessons = collect(range(1, 2))->map(fn (int $number): Lesson => Lesson::create([
            'topic_id' => $topic->id,
            'title' => "Materi Urutan {$number}",
            'slug' => "materi-urutan-detail-{$number}",
            'content' => 'Isi materi pengujian.',
            'order' => $number,
        ]));
        Progress::create([
            'user_id' => $student->id,
            'topic_id' => $topic->id,
            'lesson_id' => $lessons->first()->id,
            'status' => 'selesai',
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
            'user_id' => $student->id,
            'quiz_id' => $quiz->id,
            'answer' => '2',
            'is_correct' => true,
        ]);
        QuizAttempt::create([
            'user_id' => $student->id,
            'quiz_id' => $quiz->id,
            'answer' => '3',
            'is_correct' => false,
        ]);
        SesiBelajar::create([
            'user_id' => $student->id,
            'topic_id' => $topic->id,
            'dimulai_pada' => now()->subMinutes(30),
            'selesai_pada' => now()->subMinutes(27),
            'poin_diperoleh' => 10,
        ]);
        SesiBelajar::create([
            'user_id' => $student->id,
            'topic_id' => $topic->id,
            'dimulai_pada' => now(),
            'poin_diperoleh' => 5,
        ]);

        $response = $this->actingAs($teacher)
            ->get(route('guru.students.show', $student))
            ->assertOk()
            ->assertSee('Kembali ke Progress Siswa')
            ->assertSee('Detail pemantauan siswa')
            ->assertSee('Budi Pratama')
            ->assertSee('175')
            ->assertSee('Lv.1')
            ->assertSee('Pemula')
            ->assertSee('Akurasi')
            ->assertSee('50%')
            ->assertSee('Sesi belajar')
            ->assertSee('Materi tuntas')
            ->assertSee('Peringkat')
            ->assertSee('#2')
            ->assertSee('Riwayat sesi belajar')
            ->assertSee('Berlangsung')
            ->assertSee('Urutan (Sequencing)')
            ->assertSee('Akurasi per topik')
            ->assertSee('Materi dikuasai: 50%');

        $response->assertSee('aria-current="page"', false)
            ->assertSee('bg-[#F59E0B] text-slate-950 font-black', false)
            ->assertDontSee('bg-[#316CE5] text-white font-black', false);
    }

    public function test_teacher_cannot_view_a_student_outside_their_classes(): void
    {
        $teacher = User::factory()->create(['role' => 'guru']);
        $otherTeacher = User::factory()->create(['role' => 'guru']);
        $student = User::factory()->create(['role' => 'siswa', 'kelas' => 1]);
        $otherClass = ClassRoom::create([
            'teacher_id' => $otherTeacher->id,
            'name' => 'Kelas Guru Lain',
            'code' => Str::upper(Str::random(8)),
            'kelas_level' => 1,
        ]);
        $otherClass->students()->attach($student->id);

        $this->actingAs($teacher)
            ->get(route('guru.students.show', $student))
            ->assertNotFound();
    }
}
