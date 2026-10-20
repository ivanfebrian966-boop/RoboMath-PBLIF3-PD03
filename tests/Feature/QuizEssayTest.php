<?php

namespace Tests\Feature;

use App\Models\Quiz;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizEssayTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_essay_quiz(): void
    {
        $this->seed();

        $admin = User::where('role', 'admin')->first();
        $topic = Topic::first();

        $response = $this->actingAs($admin)->post(route('admin.quizzes.store'), [
            'topic_id' => $topic->id,
            'type' => 'essay',
            'question' => 'Budi mempunyai 10 permen. Diberikan ke Siti 3 permen. Berapa sisa permen Budi dan tuliskan langkahnya?',
            'correct_answer' => 'Sisa permen Budi adalah 7 buah. Langkah: 10 - 3 = 7.',
            'explanation' => 'Pengurangan 10 dengan 3 menghasilkan 7.',
            'difficulty' => 'mudah',
            'points' => 15,
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.quizzes.index'));

        $this->assertDatabaseHas('quizzes', [
            'topic_id' => $topic->id,
            'type' => 'essay',
            'question' => 'Budi mempunyai 10 permen. Diberikan ke Siti 3 permen. Berapa sisa permen Budi dan tuliskan langkahnya?',
            'options' => null,
            'points' => 15,
        ]);
    }

    public function test_admin_can_generate_ai_essay_quizzes(): void
    {
        $this->seed();

        $admin = User::where('role', 'admin')->first();
        $topic = Topic::first();

        $response = $this->actingAs($admin)->postJson(route('admin.quizzes.generate-ai'), [
            'topic_id' => $topic->id,
            'type' => 'essay',
            'count' => 2,
            'difficulty' => 'sedang',
            'prompt' => 'Soal cerita tentang pembagian buah apel di kelas',
            'auto_save' => true,
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'auto_saved' => true,
            'saved_count' => 2,
        ]);

        $this->assertDatabaseHas('quizzes', [
            'topic_id' => $topic->id,
            'type' => 'essay',
        ]);
    }

    public function test_student_can_answer_essay_quiz_and_get_evaluation(): void
    {
        $this->seed();

        $student = User::where('role', 'siswa')->first();
        $topic = Topic::first();

        $quiz = Quiz::create([
            'topic_id' => $topic->id,
            'type' => 'essay',
            'question' => 'Ibu membeli 8 kue bolu dan memberikan 3 kue ke adik. Berapa sisa kue ibu?',
            'options' => null,
            'correct_answer' => 'Sisa kue ibu adalah 5 buah. Caranya 8 - 3 = 5.',
            'explanation' => '8 dikurangi 3 hasilnya 5.',
            'difficulty' => 'mudah',
            'points' => 10,
            'is_active' => true,
        ]);

        $response = $this->actingAs($student)->postJson(route('siswa.quiz.submit'), [
            'quiz_id' => $quiz->id,
            'answer' => 'Caranya adalah 8 - 3 = 5 kue bolu yang tersisa pada ibu.',
            'time_spent' => 20,
        ]);

        $response->assertOk();
        $response->assertJson([
            'correct' => true,
            'is_essay' => true,
        ]);

        $this->assertDatabaseHas('quiz_attempts', [
            'user_id' => $student->id,
            'quiz_id' => $quiz->id,
            'is_correct' => true,
            'answer' => 'Caranya adalah 8 - 3 = 5 kue bolu yang tersisa pada ibu.',
        ]);
    }
}
