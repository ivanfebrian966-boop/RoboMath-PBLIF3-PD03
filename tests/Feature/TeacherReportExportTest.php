<?php

namespace Tests\Feature;

use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser;
use Tests\TestCase;
use ZipArchive;

class TeacherReportExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_can_download_a_pdf_containing_only_their_students_in_the_selected_class(): void
    {
        $teacher = User::factory()->create(['role' => 'guru']);
        $otherTeacher = User::factory()->create(['role' => 'guru']);
        $student = User::factory()->create([
            'name' => 'Siswa Pantauan',
            'email' => 'pantauan@example.test',
            'role' => 'siswa',
            'kelas' => 4,
        ]);
        $otherClassStudent = User::factory()->create([
            'name' => 'Siswa Kelas Lima',
            'role' => 'siswa',
            'kelas' => 5,
        ]);
        $otherTeacherStudent = User::factory()->create([
            'name' => 'Siswa Guru Lain',
            'role' => 'siswa',
            'kelas' => 4,
        ]);
        $this->assignStudentToTeacher($teacher, $student, 4);
        $this->assignStudentToTeacher($teacher, $otherClassStudent, 5);
        $this->assignStudentToTeacher($otherTeacher, $otherTeacherStudent, 4);

        $response = $this->actingAs($teacher)->get(route('guru.laporan.pdf', ['kelas' => 4]));

        $response->assertOk()
            ->assertHeader('content-type', 'application/pdf');
        $this->assertStringContainsString('laporan-siswa-kelas4-', $response->headers->get('content-disposition'));
        $this->assertStringStartsWith('%PDF-', $response->getContent());

        $reportText = (new Parser)->parseContent($response->getContent())->getText();
        $this->assertStringContainsString('Siswa Pantauan', $reportText);
        $this->assertStringNotContainsString('Siswa Kelas Lima', $reportText);
        $this->assertStringNotContainsString('Siswa Guru Lain', $reportText);
    }

    public function test_teacher_can_download_the_excel_report_for_the_selected_class(): void
    {
        $teacher = User::factory()->create(['role' => 'guru']);
        $student = User::factory()->create([
            'name' => 'Siswa Pantauan',
            'role' => 'siswa',
            'kelas' => 4,
        ]);
        $otherClassStudent = User::factory()->create([
            'name' => 'Siswa Kelas Lima',
            'role' => 'siswa',
            'kelas' => 5,
        ]);
        $this->assignStudentToTeacher($teacher, $student, 4);
        $this->assignStudentToTeacher($teacher, $otherClassStudent, 5);

        $response = $this->actingAs($teacher)->get(route('guru.laporan.download', ['kelas' => 4]));

        $response->assertOk()
            ->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->assertStringContainsString('laporan-siswa-kelas4-', $response->headers->get('content-disposition'));

        $temporaryFile = $response->baseResponse->getFile()->getPathname();
        $archive = new ZipArchive;

        try {
            $this->assertSame(true, $archive->open($temporaryFile));
            $worksheet = $archive->getFromName('xl/worksheets/sheet1.xml');
            $this->assertIsString($worksheet);
            $this->assertStringContainsString('Siswa Pantauan', $worksheet);
            $this->assertStringNotContainsString('Siswa Kelas Lima', $worksheet);
        } finally {
            $archive->close();
            if (is_file($temporaryFile)) {
                unlink($temporaryFile);
            }
        }
    }

    public function test_report_exports_reject_an_invalid_class_filter(): void
    {
        $teacher = User::factory()->create(['role' => 'guru']);

        $this->actingAs($teacher)
            ->get(route('guru.laporan.pdf', ['kelas' => 'invalid']))
            ->assertNotFound();
    }

    private function assignStudentToTeacher(User $teacher, User $student, int $grade): void
    {
        $classRoom = ClassRoom::create([
            'teacher_id' => $teacher->id,
            'name' => "Kelas {$grade} Pantauan",
            'code' => Str::upper(Str::random(8)),
            'kelas_level' => $grade,
        ]);
        $classRoom->students()->attach($student->id);
    }
}
