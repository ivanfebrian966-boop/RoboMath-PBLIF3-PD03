<?php

namespace Tests\Feature;

use App\Models\DocumentSummary;
use App\Models\User;
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
        $response->assertSee('Ringkasan Dokumen Berbasis AI');
        $response->assertSee('Mulai Ringkas Dokumen dengan AI');
    }

    public function test_admin_can_summarize_direct_text_content(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->postJson(route('admin.document-summarizer.summarize'), [
            'direct_text' => 'Pecahan adalah bagian dari keseluruhan. Pembilang menunjukkan bagian yang diambil sedangkan penyebut menunjukkan jumlah seluruh bagian sama besar. Contohnya adalah setengah atau satu per dua.',
            'custom_title' => 'Konsep Dasar Pecahan',
            'summary_style' => 'ringkasan_eksekutif',
            'summary_length' => 'sedang',
            'save_history' => true,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'title' => 'Konsep Dasar Pecahan',
        ]);

        $this->assertDatabaseHas('document_summaries', [
            'title' => 'Konsep Dasar Pecahan',
            'user_id' => $admin->id,
            'summary_style' => 'ringkasan_eksekutif',
        ]);
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
            'summary_style' => 'anak_sd',
            'summary_length' => 'singkat',
            'save_history' => true,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('document_summaries', [
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

        $this->assertDatabaseMissing('document_summaries', [
            'id' => $summary->id,
        ]);
    }
}
