<?php

namespace Tests\Feature;

use App\Models\DocumentSummary;
use App\Models\Quiz;
use App\Models\Topic;
use App\Models\User;
use App\Services\DocumentSummarizerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class DocumentSummarizerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_document_summarizer(): void
    {
        $response = $this->get(route('admin.document-summarizer.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_siswa_cannot_access_document_summarizer(): void
    {
        $siswa = User::factory()->create([
            'role' => 'siswa',
        ]);

        $response = $this->actingAs($siswa)->get(route('admin.document-summarizer.index'));

        $response->assertRedirect(route('siswa.dashboard'));
    }

    public function test_admin_can_view_document_summarizer_page(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.document-summarizer.index'));

        $response->assertStatus(200);
        $response->assertSee('Ringkas Dokumen Jadi Soal AI');
        $response->assertSee('Unggah modul, silabus, lembar kerja');
        $response->assertSee('Ajukan soal ke Review AI');
        $response->assertSee('Mengekstrak materi dokumen...');
        $response->assertSee('Tingkat kesulitan');
    }

    public function test_admin_can_summarize_direct_text_content(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->postJson(route('admin.document-summarizer.summarize'), [
            'direct_text' => 'Pecahan adalah bagian dari keseluruhan. Pembilang menunjukkan bagian yang diambil sedangkan penyebut menunjukkan jumlah seluruh bagian sama besar. Contohnya adalah setengah atau satu per dua.',
            'custom_title' => 'Konsep Dasar Pecahan',
            'save_history' => true,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'title' => 'Konsep Dasar Pecahan',
        ]);

        $this->assertDatabaseHas('dokumen', [
            'title' => 'Konsep Dasar Pecahan',
            'user_id' => $admin->id,
            'summary_style' => 'kuis_latihan',
        ]);
    }

    public function test_document_processing_creates_chunks_and_pending_review_content(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->postJson(route('admin.document-summarizer.summarize'), [
            'direct_text' => 'Pecahan merupakan bagian dari keseluruhan. Pembilang menunjukkan jumlah bagian yang diambil. Penyebut menunjukkan jumlah seluruh bagian yang sama besar. Contohnya satu per dua.',
            'custom_title' => 'Konsep Pecahan',
            'kelas_level' => 4,
            'topic_category' => 'pola',
        ]);

        $response->assertOk();
        $response->assertJsonPath('status', 'selesai');
        $response->assertJsonPath('topic_title', 'Konsep Pecahan');
        $quizCount = $response->json('quiz_count');
        $this->assertGreaterThan(0, $quizCount);

        $summary = DocumentSummary::firstOrFail();
        $topic = $summary->topics()->firstOrFail();

        $this->assertSame('selesai', $summary->status);
        $this->assertGreaterThan(0, $summary->chunks()->count());
        $this->assertSame('pending', $topic->review_status);
        $this->assertFalse($topic->is_active);
        $this->assertSame(4, $topic->kelas_level);
        $this->assertSame($quizCount, $topic->quizzes()->count());
        $this->assertSame($quizCount, $topic->quizzes()->where('review_status', 'pending')->count());
        $this->assertSame(0, $topic->quizzes()->where('is_active', true)->count());
    }

    public function test_document_with_insufficient_text_is_recorded_as_failed(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->postJson(route('admin.document-summarizer.summarize'), [
            'direct_text' => 'Too short',
            'custom_title' => 'Dokumen Tidak Lengkap',
        ]);

        $response->assertUnprocessable();
        $this->assertDatabaseHas('dokumen', [
            'user_id' => $admin->id,
            'title' => 'Dokumen Tidak Lengkap',
            'status' => 'gagal',
            'error_message' => 'Dokumen tidak memuat teks yang cukup untuk dianalisis oleh AI.',
        ]);
    }

    public function test_ai_processing_failure_marks_document_as_failed(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->mock(DocumentSummarizerService::class)
            ->shouldReceive('summarize')
            ->once()
            ->andThrow(new \RuntimeException('AI provider unavailable.'));

        $response = $this->actingAs($admin)->postJson(route('admin.document-summarizer.summarize'), [
            'direct_text' => 'Pecahan merupakan bagian dari keseluruhan. Pembilang menunjukkan jumlah bagian yang diambil sedangkan penyebut menunjukkan jumlah seluruh bagian yang sama besar.',
            'custom_title' => 'Materi Pecahan',
        ]);

        $response->assertServerError();
        $this->assertDatabaseHas('dokumen', [
            'user_id' => $admin->id,
            'title' => 'Materi Pecahan',
            'status' => 'gagal',
            'error_message' => 'Gagal memproses dokumen.',
        ]);
    }

    public function test_admin_must_approve_topic_before_ai_quizzes_can_be_published(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $topic = Topic::create([
            'title' => 'Topik Tinjauan',
            'slug' => 'topik-tinjauan',
            'description' => 'Topik untuk menguji proses persetujuan.',
            'kelas_level' => 2,
            'order' => 1,
            'category' => 'pola',
            'is_active' => false,
            'review_status' => 'pending',
        ]);
        $quiz = Quiz::create([
            'topic_id' => $topic->id,
            'type' => 'pilihan_ganda',
            'question' => 'Berapakah 2 + 2?',
            'options' => ['A' => '3', 'B' => '4'],
            'correct_answer' => 'B',
            'difficulty' => 'mudah',
            'points' => 10,
            'is_active' => false,
            'review_status' => 'pending',
        ]);

        $blockedResponse = $this->actingAs($admin)
            ->post(route('admin.ai-recommendations.quizzes.approve', $quiz));

        $blockedResponse->assertSessionHasErrors('topic');
        $this->assertDatabaseHas('soal', [
            'id' => $quiz->id,
            'review_status' => 'pending',
            'is_active' => false,
        ]);

        $this->post(route('admin.ai-recommendations.topics.approve', $topic))
            ->assertRedirect();
        $this->post(route('admin.ai-recommendations.quizzes.approve', $quiz))
            ->assertRedirect();

        $this->assertDatabaseHas('topik', [
            'id' => $topic->id,
            'review_status' => 'approved',
            'is_active' => true,
            'reviewed_by' => $admin->id,
        ]);
        $this->assertDatabaseHas('soal', [
            'id' => $quiz->id,
            'review_status' => 'approved',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_summarize_with_custom_prompt(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->postJson(route('admin.document-summarizer.summarize'), [
            'direct_text' => 'Pecahan adalah bagian dari keseluruhan. Pembilang menunjukkan bagian yang diambil sedangkan penyebut menunjukkan jumlah seluruh bagian sama besar. Contohnya adalah setengah atau satu per dua.',
            'custom_title' => 'Soal HOTS Pecahan',
            'custom_prompt' => 'Buatkan 5 soal cerita HOTS pecahan kelas 4 SD',
            'save_history' => true,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'title' => 'Soal HOTS Pecahan',
        ]);

        $this->assertDatabaseHas('dokumen', [
            'title' => 'Soal HOTS Pecahan',
            'user_id' => $admin->id,
        ]);
    }

    public function test_admin_can_generate_essay_questions_with_custom_prompt(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->postJson(route('admin.document-summarizer.summarize'), [
            'direct_text' => 'Pecahan adalah bagian dari keseluruhan. Pembilang menunjukkan bagian yang diambil sedangkan penyebut menunjukkan jumlah seluruh bagian sama besar. Contohnya adalah setengah atau satu per dua.',
            'custom_title' => 'Soal Uraian Pecahan',
            'custom_prompt' => 'Buatkan 5 butir soal essay uraian konsep pecahan',
            'save_history' => true,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'title' => 'Soal Uraian Pecahan',
        ]);

        $this->assertDatabaseHas('dokumen', [
            'title' => 'Soal Uraian Pecahan',
            'user_id' => $admin->id,
        ]);
    }

    public function test_admin_can_generate_six_pure_essay_questions_without_multiple_choice(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->postJson(route('admin.document-summarizer.summarize'), [
            'direct_text' => 'Pecahan adalah bagian dari keseluruhan. Pembilang menunjukkan bagian yang diambil sedangkan penyebut menunjukkan jumlah seluruh bagian sama besar. Contohnya adalah setengah atau satu per dua.',
            'custom_title' => '6 Soal Essay Pecahan',
            'custom_prompt' => 'buatkan 6 soal essay',
            'save_history' => true,
        ]);

        $response->assertStatus(200);
        $summary = $response->json('summary_markdown');

        $this->assertStringContainsString('Soal 6', $summary);
        $this->assertStringNotContainsString('- A.', $summary);
        $this->assertStringNotContainsString('- B.', $summary);
    }

    public function test_admin_can_summarize_uploaded_text_file(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $file = UploadedFile::fake()->createWithContent(
            'matematika_dasar.txt',
            'Penjumlahan dan pengurangan merupakan operasi aritmatika dasar dalam matematika sekolah dasar yang sangat penting dipelajari sejak dini.'
        );

        $response = $this->actingAs($admin)->postJson(route('admin.document-summarizer.summarize'), [
            'document_file' => $file,
            'save_history' => true,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('dokumen', [
            'original_filename' => 'matematika_dasar.txt',
            'user_id' => $admin->id,
            'file_type' => 'txt',
        ]);
    }

    public function test_admin_can_download_and_delete_summary(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $summary = DocumentSummary::create([
            'user_id' => $admin->id,
            'title' => 'Materi Pola Bilangan',
            'original_filename' => 'pola.txt',
            'file_type' => 'txt',
            'file_size' => 500,
            'summary' => '## Ringkasan Pola Bilangan\nPola bilangan adalah susunan angka yang teratur.',
            'key_points' => ['Pola aritmatika', 'Pola geometri'],
            'summary_style' => 'ringkasan_eksekutif',
            'summary_length' => 'sedang',
            'word_count_original' => 100,
            'word_count_summary' => 20,
            'ai_provider' => 'mock',
        ]);

        // Download test
        $downloadResponse = $this->actingAs($admin)->get(route('admin.document-summarizer.download', $summary->id));
        $downloadResponse->assertStatus(200);

        // Delete test
        $deleteResponse = $this->actingAs($admin)->delete(route('admin.document-summarizer.destroy', $summary->id));
        $deleteResponse->assertRedirect();

        $this->assertDatabaseMissing('dokumen', [
            'id' => $summary->id,
        ]);
    }

    public function test_admin_can_publish_mcq_quizzes_to_students(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $topic = Topic::create([
            'title' => 'Operasi Hitung',
            'slug' => 'operasi-hitung',
            'description' => 'Belajar operasi hitung dasar',
            'icon' => '➕',
            'color' => '#6366F1',
            'kelas_level' => 1,
            'order' => 1,
            'category' => 'urutan',
            'is_active' => true,
        ]);

        $markdown = <<<'MD'
## 🌟 Paket Soal: Operasi Bilangan

### 📝 Soal 1
Berapa hasil dari 5 + 3?
- A. 6
- B. 7
- C. 8
- D. 9

> **🔑 Kunci Jawaban:** C. 8
> **💡 Pembahasan:** 5 ditambah 3 sama dengan 8.
MD;

        $response = $this->actingAs($admin)->postJson(route('admin.document-summarizer.publish-to-students'), [
            'topic_id' => $topic->id,
            'markdown' => $markdown,
            'difficulty' => 'mudah',
            'points' => 10,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'count' => 1,
        ]);

        $this->assertDatabaseHas('soal', [
            'topic_id' => $topic->id,
            'correct_answer' => 'C',
        ]);
    }

    public function test_admin_reuses_pending_quizzes_generated_from_the_document_when_submitting_to_review(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $summary = DocumentSummary::create([
            'user_id' => $admin->id,
            'title' => 'Materi Pecahan',
            'original_filename' => 'pecahan.docx',
            'file_type' => 'docx',
            'file_size' => 500,
            'summary' => 'Ringkasan materi pecahan untuk menguji pengajuan soal.',
            'summary_style' => 'kuis_latihan',
            'summary_length' => '5_soal',
            'word_count_original' => 50,
            'word_count_summary' => 20,
            'ai_provider' => 'local',
        ]);
        $generatedTopic = Topic::create([
            'document_summary_id' => $summary->id,
            'title' => 'Materi Pecahan',
            'slug' => 'materi-pecahan-dari-dokumen',
            'description' => 'Topik sementara dari dokumen.',
            'icon' => '📘',
            'color' => '#2563EB',
            'kelas_level' => 4,
            'order' => 1,
            'category' => 'pola',
            'is_active' => false,
            'review_status' => 'pending',
        ]);
        $targetTopic = Topic::create([
            'title' => 'Pecahan Kelas 4',
            'slug' => 'pecahan-kelas-4',
            'description' => 'Topik pecahan aktif.',
            'icon' => '➗',
            'color' => '#10B981',
            'kelas_level' => 4,
            'order' => 2,
            'category' => 'pola',
            'is_active' => true,
        ]);
        Quiz::create([
            'topic_id' => $generatedTopic->id,
            'document_summary_id' => $summary->id,
            'type' => 'pilihan_ganda',
            'question' => 'Pertanyaan sementara?',
            'options' => ['A' => '1', 'B' => '2', 'C' => '3', 'D' => '4'],
            'correct_answer' => 'B',
            'explanation' => 'Penjelasan sementara.',
            'difficulty' => 'sedang',
            'points' => 10,
            'is_active' => false,
            'review_status' => 'pending',
        ]);
        $markdown = <<<'MD'
## Paket Soal Pecahan

### Soal 1
Jelaskan arti satu per dua dengan bahasamu sendiri.

> **Kunci Jawaban:** Satu dari dua bagian yang sama besar.
> **Pembahasan:** Satu per dua menunjukkan satu bagian dari keseluruhan yang dibagi dua sama besar.
MD;

        $response = $this->actingAs($admin)->postJson(route('admin.document-summarizer.publish-to-students'), [
            'topic_id' => $targetTopic->id,
            'summary_id' => $summary->id,
            'markdown' => $markdown,
            'difficulty' => 'mudah',
            'points' => 15,
            'as_essay' => true,
            'type' => 'essay',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'count' => 1,
                'topic' => 'Pecahan Kelas 4',
            ]);
        $this->assertDatabaseCount('soal', 1);
        $this->assertDatabaseHas('soal', [
            'topic_id' => $targetTopic->id,
            'document_summary_id' => $summary->id,
            'type' => 'essay',
            'difficulty' => 'mudah',
            'points' => 15,
            'review_status' => 'pending',
            'is_active' => false,
        ]);
        $this->assertDatabaseMissing('topik', [
            'id' => $generatedTopic->id,
        ]);
    }

    public function test_admin_can_publish_essay_quizzes_to_students_with_automatic_option_synthesis(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $topic = Topic::create([
            'title' => 'Jadwal Presentasi',
            'slug' => 'jadwal-presentasi',
            'description' => 'Materi jadwal presentasi',
            'icon' => '📅',
            'color' => '#8B5CF6',
            'kelas_level' => 2,
            'order' => 2,
            'category' => 'pola',
            'is_active' => true,
        ]);

        $essayMarkdown = <<<'MD'
## 🌟 Paket Soal Uraian

### 📝 Soal 1
Perhatikan jadwal untuk Kelas Malam. Berapa total nama kelompok Presenter yang ada?

> **🔑 Kunci Jawaban:** Total kelompok presenter di kelas malam adalah 4 kelompok.
> **💡 Pembahasan:** Menghitung jumlah kelompok presenter pada jadwal kelas malam diperoleh 4 kelompok.
MD;

        $response = $this->actingAs($admin)->postJson(route('admin.document-summarizer.publish-to-students'), [
            'topic_id' => $topic->id,
            'markdown' => $essayMarkdown,
            'difficulty' => 'sedang',
            'points' => 15,
            'as_essay' => false,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'count' => 1,
        ]);

        $quiz = Quiz::where('topic_id', $topic->id)->latest()->first();
        $this->assertNotNull($quiz);
        $this->assertCount(4, $quiz->options);
        $this->assertArrayHasKey('A', $quiz->options);
        $this->assertArrayHasKey('B', $quiz->options);
        $this->assertArrayHasKey('C', $quiz->options);
        $this->assertArrayHasKey('D', $quiz->options);
        $this->assertContains($quiz->correct_answer, ['A', 'B', 'C', 'D']);
    }

    public function test_admin_can_publish_native_essay_quizzes_without_option_synthesis(): void
    {
        $this->seed();

        $admin = User::where('role', 'admin')->first();
        $topic = Topic::first();

        $essayMarkdown = <<<'MD'
## 🌟 Paket Soal: Penalaran Matematika

### 📝 Soal 1
Berapa banyak siswa yang mengikuti upacara jika terdapat 5 barisan dan setiap barisan ada 10 siswa? Jelaskan perkaliannya!

> **🔑 Kunci Jawaban:** Total siswa adalah 50 anak. Caranya 5 x 10 = 50 siswa.
> **💡 Pembahasan:** Perkalian 5 baris dengan 10 siswa menghasilkan 50 siswa.
MD;

        $response = $this->actingAs($admin)->postJson(route('admin.document-summarizer.publish-to-students'), [
            'topic_id' => $topic->id,
            'markdown' => $essayMarkdown,
            'difficulty' => 'sedang',
            'points' => 15,
            'as_essay' => true,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'count' => 1,
        ]);

        $quiz = Quiz::where('topic_id', $topic->id)->latest('id')->first();
        $this->assertNotNull($quiz);
        $this->assertEquals('essay', $quiz->type);
        $this->assertNull($quiz->options);
        $this->assertStringContainsString('Total siswa adalah 50 anak', $quiz->correct_answer);
        $this->assertEquals('pending', $quiz->review_status);
        $this->assertFalse($quiz->is_active);
    }

    public function test_admin_can_approve_and_reject_ai_quizzes_in_review_ai(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $topic = Topic::create([
            'title' => 'Geometri Dasar',
            'slug' => 'geometri-dasar',
            'description' => 'Materi bangun datar',
            'icon' => '📐',
            'color' => '#3B82F6',
            'kelas_level' => 3,
            'order' => 1,
            'category' => 'pola',
            'is_active' => true,
        ]);

        $quiz = Quiz::create([
            'topic_id' => $topic->id,
            'type' => 'pilihan_ganda',
            'question' => 'Berapa jumlah sisi pada segitiga sama sisi?',
            'options' => ['A' => '2', 'B' => '3', 'C' => '4', 'D' => '5'],
            'correct_answer' => 'B',
            'explanation' => 'Segitiga memiliki 3 sisi.',
            'difficulty' => 'mudah',
            'points' => 10,
            'is_active' => false,
            'review_status' => 'pending',
        ]);

        // 1. Visit Review AI page
        $resIndex = $this->actingAs($admin)->get(route('admin.ai-recommendations.index', ['tab' => 'quiz']));
        $resIndex->assertStatus(200);
        $resIndex->assertSee('Berapa jumlah sisi pada segitiga sama sisi?');

        // 2. Approve Quiz
        $resApprove = $this->actingAs($admin)->post(route('admin.ai-recommendations.quizzes.approve', $quiz));
        $resApprove->assertSessionHas('success');
        $quiz->refresh();
        $this->assertEquals('approved', $quiz->review_status);
        $this->assertTrue($quiz->is_active);
        $this->assertEquals($admin->id, $quiz->reviewed_by);

        // 3. Reject Quiz
        $resReject = $this->actingAs($admin)->post(route('admin.ai-recommendations.quizzes.reject', $quiz), [
            'admin_notes' => 'Perlu revisi redaksi soal.',
        ]);
        $resReject->assertSessionHas('success');
        $quiz->refresh();
        $this->assertEquals('rejected', $quiz->review_status);
        $this->assertFalse($quiz->is_active);
        $this->assertEquals('Perlu revisi redaksi soal.', $quiz->admin_notes);
    }

    public function test_admin_can_bulk_approve_topic_quizzes(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $topic = Topic::create([
            'title' => 'Pengukuran Sudut',
            'slug' => 'pengukuran-sudut',
            'description' => 'Materi sudut',
            'icon' => '📐',
            'color' => '#10B981',
            'kelas_level' => 4,
            'order' => 1,
            'category' => 'dekomposisi',
            'is_active' => true,
        ]);

        Quiz::create([
            'topic_id' => $topic->id,
            'type' => 'essay',
            'question' => 'Apa itu sudut siku-siku?',
            'correct_answer' => 'Sudut yang berukuran 90 derajat.',
            'is_active' => false,
            'review_status' => 'pending',
        ]);

        Quiz::create([
            'topic_id' => $topic->id,
            'type' => 'essay',
            'question' => 'Apa itu sudut lancip?',
            'correct_answer' => 'Sudut yang berukuran kurang dari 90 derajat.',
            'is_active' => false,
            'review_status' => 'pending',
        ]);

        $this->assertEquals(2, Quiz::where('topic_id', $topic->id)->where('review_status', 'pending')->count());

        $res = $this->actingAs($admin)->post(route('admin.ai-recommendations.topics.approve-all', $topic));
        $res->assertSessionHas('success');

        $this->assertEquals(0, Quiz::where('topic_id', $topic->id)->where('review_status', 'pending')->count());
        $this->assertEquals(2, Quiz::where('topic_id', $topic->id)->where('review_status', 'approved')->where('is_active', true)->count());
    }
}
