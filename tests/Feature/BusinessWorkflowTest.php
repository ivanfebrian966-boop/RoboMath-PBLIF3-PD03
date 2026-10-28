<?php

namespace Tests\Feature;

use App\Models\AnggotaKelas;
use App\Models\ClassRoom;
use App\Models\Dokumen;
use App\Models\Kelas;
use App\Models\Lesson;
use App\Models\Misi;
use App\Models\MisiPengguna;
use App\Models\Pencapaian;
use App\Models\PencapaianPengguna;
use App\Models\Pengguna;
use App\Models\PengumpulanTugas;
use App\Models\PercobaanSoal;
use App\Models\PotonganDokumen;
use App\Models\Sekolah;
use App\Models\SesiBelajar;
use App\Models\Soal;
use App\Models\Topic;
use App\Models\Topik;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusinessWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_robomath_models_use_the_tables_from_the_domain_diagram(): void
    {
        $models = [
            Pengguna::class => 'pengguna',
            Dokumen::class => 'dokumen',
            PotonganDokumen::class => 'potongan_dokumen',
            Topik::class => 'topik',
            Soal::class => 'soal',
            PercobaanSoal::class => 'percobaan_soal',
            Pencapaian::class => 'pencapaian',
            PencapaianPengguna::class => 'pencapaian_pengguna',
            Sekolah::class => 'sekolah',
            Kelas::class => 'kelas',
            AnggotaKelas::class => 'anggota_kelas',
            Tugas::class => 'tugas',
            PengumpulanTugas::class => 'pengumpulan_tugas',
            Misi::class => 'misi',
            MisiPengguna::class => 'misi_pengguna',
            SesiBelajar::class => 'sesi_belajar',
        ];

        foreach ($models as $model => $table) {
            $this->assertSame($table, (new $model)->getTable());
        }
    }

    public function test_database_seeder_populates_demo_data_without_duplicating_quizzes(): void
    {
        $this->seed();
        $quizCount = Soal::count();

        $this->seed();

        $this->assertSame(5, Topik::count());
        $this->assertSame(6, Lesson::count());
        $this->assertSame(8, Soal::count());
        $this->assertSame($quizCount, Soal::count());
        $this->assertSame(5, Pencapaian::count());
        $this->assertSame(3, Misi::count());
        $this->assertSame(1, Kelas::where('code', 'MATH101')->count());
        $this->assertSame(3, AnggotaKelas::count());
    }

    public function test_teacher_can_only_assign_approved_topics_at_the_class_grade(): void
    {
        $teacher = User::factory()->create(['role' => 'guru']);
        $approvedTopic = $this->createTopic('approved-topic', 4, 'approved', true);
        $pendingTopic = $this->createTopic('pending-topic', 4, 'pending', false);

        $this->actingAs($teacher)
            ->post(route('guru.classes.store'), [
                'name' => 'Kelas Pecahan',
                'kelas_level' => 4,
                'topic_ids' => [$pendingTopic->id],
            ])
            ->assertSessionHasErrors('topic_ids');

        $this->assertDatabaseCount('kelas', 0);

        $this->post(route('guru.classes.store'), [
            'name' => 'Kelas Pecahan',
            'kelas_level' => 4,
            'topic_ids' => [$approvedTopic->id],
        ])->assertRedirect(route('guru.classes.index'));

        $classRoom = ClassRoom::where('teacher_id', $teacher->id)->firstOrFail();
        $this->assertSame([$approvedTopic->id], $classRoom->topics()->pluck('topik.id')->all());

        $this->get(route('guru.classes.index'))
            ->assertSee('Kelas Kelas Pecahan berhasil dibuat!')
            ->assertSee('Bagikan kode ini kepada siswa')
            ->assertSee($classRoom->code);
    }

    public function test_student_can_join_matching_grade_class_and_cannot_join_another_grade(): void
    {
        $teacher = User::factory()->create(['role' => 'guru']);
        $matchingStudent = User::factory()->create(['role' => 'siswa', 'kelas' => 4]);
        $otherGradeStudent = User::factory()->create(['role' => 'siswa', 'kelas' => 3]);
        $classRoom = ClassRoom::create([
            'teacher_id' => $teacher->id,
            'name' => 'Kelas 4A',
            'code' => 'JOIN42',
            'kelas_level' => 4,
        ]);

        $this->actingAs($matchingStudent)
            ->post(route('siswa.kelas.join.store'), ['code' => 'join42'])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('anggota_kelas', [
            'class_room_id' => $classRoom->id,
            'student_id' => $matchingStudent->id,
        ]);

        $this->actingAs($otherGradeStudent)
            ->post(route('siswa.kelas.join.store'), ['code' => 'JOIN42'])
            ->assertSessionHasErrors('code');

        $this->assertDatabaseMissing('anggota_kelas', [
            'class_room_id' => $classRoom->id,
            'student_id' => $otherGradeStudent->id,
        ]);
    }

    public function test_user_mission_status_is_linked_to_the_mission_catalog(): void
    {
        $student = User::factory()->create(['role' => 'siswa']);
        $mission = Misi::create([
            'judul' => 'Latihan harian',
            'deskripsi' => 'Selesaikan satu latihan.',
            'poin' => 10,
            'is_active' => true,
        ]);

        $userMission = $student->misiPengguna()->create([
            'misi_id' => $mission->id,
            'judul' => $mission->judul,
            'deskripsi' => $mission->deskripsi,
            'status' => 'aktif',
        ]);

        $this->assertSame($mission->id, $userMission->misi->id);
        $this->assertTrue($mission->penggunaMisi()->whereKey($userMission->id)->exists());
    }

    public function test_user_achievements_use_the_named_pivot_model_and_table(): void
    {
        $student = User::factory()->create(['role' => 'siswa']);
        $achievement = Pencapaian::create([
            'name' => 'Langkah Pertama',
            'slug' => 'langkah-pertama',
            'description' => 'Menyelesaikan latihan pertama.',
            'icon' => 'star',
            'requirement_type' => 'score',
            'requirement_value' => 10,
        ]);

        $student->badges()->attach($achievement->id, ['earned_at' => now()]);

        $this->assertTrue($student->badges()->whereKey($achievement->id)->exists());
        $this->assertDatabaseHas('pencapaian_pengguna', [
            'user_id' => $student->id,
            'badge_id' => $achievement->id,
        ]);
    }

    public function test_teacher_dashboard_only_shows_sessions_from_students_in_their_classes(): void
    {
        $teacher = User::factory()->create(['role' => 'guru']);
        $otherTeacher = User::factory()->create(['role' => 'guru']);
        $teacherStudent = User::factory()->create(['role' => 'siswa', 'kelas' => 2, 'name' => 'Siswa Kelas Saya']);
        $otherStudent = User::factory()->create(['role' => 'siswa', 'kelas' => 2, 'name' => 'Siswa Kelas Lain']);
        $topic = $this->createTopic('dashboard-topic', 2, 'approved', true);
        $teacherClass = ClassRoom::create([
            'teacher_id' => $teacher->id,
            'name' => 'Kelas Guru',
            'code' => 'TEACH1',
            'kelas_level' => 2,
        ]);
        $otherClass = ClassRoom::create([
            'teacher_id' => $otherTeacher->id,
            'name' => 'Kelas Guru Lain',
            'code' => 'TEACH2',
            'kelas_level' => 2,
        ]);
        $teacherClass->students()->attach($teacherStudent->id, ['joined_at' => now()]);
        $otherClass->students()->attach($otherStudent->id, ['joined_at' => now()]);
        SesiBelajar::create([
            'user_id' => $teacherStudent->id,
            'topic_id' => $topic->id,
            'dimulai_pada' => now(),
        ]);
        SesiBelajar::create([
            'user_id' => $otherStudent->id,
            'topic_id' => $topic->id,
            'dimulai_pada' => now(),
        ]);

        $response = $this->actingAs($teacher)->get(route('guru.dashboard'));

        $response->assertOk();
        $response->assertSee('Siswa Kelas Saya');
        $response->assertDontSee('Siswa Kelas Lain');
    }

    private function createTopic(string $slug, int $grade, string $reviewStatus, bool $isActive): Topic
    {
        return Topic::create([
            'title' => str_replace('-', ' ', $slug),
            'slug' => $slug,
            'description' => 'Topik pengujian alur kelas.',
            'kelas_level' => $grade,
            'order' => 1,
            'category' => 'pola',
            'is_active' => $isActive,
            'review_status' => $reviewStatus,
        ]);
    }
}
