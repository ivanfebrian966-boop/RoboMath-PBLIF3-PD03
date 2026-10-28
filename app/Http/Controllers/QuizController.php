<?php

namespace App\Http\Controllers;

use App\AI\AIManager;
use App\Models\AiRecommendation;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\SesiBelajar;
use App\Models\Topic;
use App\Models\User;
use App\Services\GamificationService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuizController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $topics = Topic::availableToStudent($user)
            ->with(['quizzes' => function ($q) {
                $q->where('is_active', true)
                    ->where('review_status', 'approved')
                    ->orderBy('id');
            }])
            ->withCount(['quizzes' => function ($q) {
                $q->where('is_active', true)->where('review_status', 'approved');
            }])
            ->orderBy('order')
            ->get();

        // Get all user attempts for these quizzes
        $allQuizIds = $topics->flatMap->quizzes->pluck('id');
        $userAttempts = QuizAttempt::where('user_id', $user->id)
            ->whereIn('quiz_id', $allQuizIds)
            ->get()
            ->groupBy('quiz_id');

        $totalQuizzes = $allQuizIds->count();
        $completedQuizzesCount = 0;
        $totalEarnedPoints = 0;

        foreach ($topics as $topic) {
            $topicCompletedCount = 0;
            foreach ($topic->quizzes as $quiz) {
                $attempts = $userAttempts->get($quiz->id, collect());
                $quiz->user_attempts_count = $attempts->count();
                $quiz->user_is_correct = $attempts->contains('is_correct', true);
                $quiz->user_points_earned = $quiz->user_is_correct
                    ? ($attempts->where('is_correct', true)->max('points_earned') ?: $quiz->points)
                    : 0;

                if ($quiz->user_is_correct) {
                    $topicCompletedCount++;
                    $completedQuizzesCount++;
                    $totalEarnedPoints += $quiz->user_points_earned;
                }
            }
            $topic->completed_count = $topicCompletedCount;
            $topic->progress_percent = $topic->quizzes_count > 0
                ? round(($topicCompletedCount / $topic->quizzes_count) * 100)
                : 0;
        }

        $overallProgressPercent = $totalQuizzes > 0
            ? round(($completedQuizzesCount / $totalQuizzes) * 100)
            : 0;

        return view('student.quiz.index', compact(
            'topics',
            'totalQuizzes',
            'completedQuizzesCount',
            'totalEarnedPoints',
            'overallProgressPercent'
        ));
    }

    public function play(Topic $topic, Request $request)
    {
        $user = Auth::user();

        abort_unless($this->studentHasTopic($user, $topic), 404);

        $quizzes = Quiz::where('topic_id', $topic->id)
            ->where('is_active', true)
            ->where('review_status', 'approved')
            ->orderBy('id')
            ->get();

        if ($quizzes->isEmpty()) {
            return redirect()->route('siswa.quiz')->with('error', 'Belum ada soal pada topik ini.');
        }

        $startIndex = 0;
        if ($request->filled('quiz_id')) {
            $foundIndex = $quizzes->search(fn ($q) => $q->id == $request->quiz_id);
            if ($foundIndex !== false) {
                $startIndex = $foundIndex;
            }
        }

        // Get status of user's previous attempts for each quiz in this topic
        $attempts = QuizAttempt::where('user_id', $user->id)
            ->whereIn('quiz_id', $quizzes->pluck('id'))
            ->get()
            ->groupBy('quiz_id');

        $quizStatuses = [];
        foreach ($quizzes as $quiz) {
            $quizAttempts = $attempts->get($quiz->id, collect());
            $lastAttempt = $quizAttempts->last();
            $quizStatuses[$quiz->id] = [
                'is_correct' => $quizAttempts->contains('is_correct', true),
                'attempts_count' => $quizAttempts->count(),
                'last_answer' => $lastAttempt?->answer,
                'ai_feedback' => $lastAttempt?->ai_feedback,
            ];
        }

        $session = $user->sesiBelajar()
            ->where('topic_id', $topic->id)
            ->whereNull('selesai_pada')
            ->latest('id')
            ->first();

        if (! $session) {
            $session = $user->sesiBelajar()->create([
                'topic_id' => $topic->id,
                'dimulai_pada' => now(),
            ]);
        }

        return view('student.quiz.play', compact('topic', 'quizzes', 'startIndex', 'quizStatuses', 'session'));
    }

    public function submit(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'quiz_id' => 'required|exists:soal,id',
            'answer' => 'required|string',
            'time_spent' => 'nullable|integer|min:0|max:36000',
            'session_id' => ['nullable', 'integer', 'exists:sesi_belajar,id'],
        ]);

        $quiz = Quiz::whereKey($validated['quiz_id'])
            ->where('is_active', true)
            ->where('review_status', 'approved')
            ->whereHas('topic', fn (Builder $topicQuery) => $topicQuery
                ->where('is_active', true)
                ->where('review_status', 'approved'))
            ->with('topic')
            ->firstOrFail();

        abort_unless($this->studentHasTopic($user, $quiz->topic), 404);

        $isEssay = $quiz->isEssay();
        $aiFeedback = null;

        if ($isEssay) {
            $evaluation = $this->evaluateEssayAnswer($quiz, $validated['answer'], $user);
            $isCorrect = $evaluation['is_correct'];
            $aiFeedback = $evaluation['feedback'];
            $scorePercent = $evaluation['score_percent'] ?? ($isCorrect ? 100 : 0);
        } else {
            $isCorrect = strtolower(trim($validated['answer'])) === strtolower(trim($quiz->correct_answer));
            $scorePercent = 100;
        }

        [$attempt, $pointsEarned, $previouslyCorrect, $sessionId] = DB::transaction(function () use (
            $user,
            $quiz,
            $validated,
            $isCorrect,
            $aiFeedback,
            $scorePercent
        ): array {
            $lockedUser = User::whereKey($user->id)->lockForUpdate()->firstOrFail();

            $sessionQuery = SesiBelajar::where('user_id', $lockedUser->id)
                ->where('topic_id', $quiz->topic_id)
                ->whereNull('selesai_pada');

            if (! empty($validated['session_id'])) {
                $sessionQuery->whereKey($validated['session_id']);
            }

            $session = $sessionQuery->lockForUpdate()->latest('id')->first();

            if (! $session && empty($validated['session_id'])) {
                $session = $lockedUser->sesiBelajar()->create([
                    'topic_id' => $quiz->topic_id,
                    'dimulai_pada' => now(),
                ]);
            }

            if (! $session) {
                abort(404);
            }

            $previouslyCorrect = QuizAttempt::where('user_id', $lockedUser->id)
                ->where('quiz_id', $quiz->id)
                ->where('is_correct', true)
                ->exists();
            $pointsEarned = $isCorrect && ! $previouslyCorrect
                ? ($quiz->isEssay() ? max(5, (int) round(($scorePercent / 100) * $quiz->points)) : $quiz->points)
                : 0;

            $attempt = QuizAttempt::create([
                'user_id' => $lockedUser->id,
                'quiz_id' => $quiz->id,
                'sesi_belajar_id' => $session->id,
                'answer' => $validated['answer'],
                'is_correct' => $isCorrect,
                'ai_feedback' => $aiFeedback,
                'time_spent' => $validated['time_spent'] ?? 0,
                'points_earned' => $pointsEarned,
            ]);

            if ($pointsEarned > 0) {
                $lockedUser->addScore($pointsEarned);
                $session->increment('poin_diperoleh', $pointsEarned);
            }

            return [$attempt, $pointsEarned, $previouslyCorrect, $session->id];
        });

        // Check badges
        app(GamificationService::class)->checkBadges($user);

        // Generate AI recommendation if student is struggling (3+ wrong on this topic)
        if (! $isCorrect) {
            $wrongCount = QuizAttempt::where('user_id', $user->id)
                ->where('is_correct', false)
                ->whereHas('quiz', fn ($q) => $q->where('topic_id', $quiz->topic_id))
                ->count();

            if ($wrongCount >= 3) {
                // Only create if no pending recommendation for this topic
                $exists = AiRecommendation::where('student_id', $user->id)
                    ->where('topic_id', $quiz->topic_id)
                    ->where('status', 'pending')
                    ->exists();

                if (! $exists) {
                    AiRecommendation::create([
                        'student_id' => $user->id,
                        'topic_id' => $quiz->topic_id,
                        'quiz_id' => $quiz->id,
                        'reason' => "Siswa menjawab salah {$wrongCount}x pada topik ini.",
                        'status' => 'pending',
                    ]);
                }
            }
        }

        return response()->json([
            'correct' => $isCorrect,
            'is_essay' => $isEssay,
            'ai_feedback' => $aiFeedback,
            'points' => $pointsEarned,
            'already_earned' => $previouslyCorrect,
            'session_id' => $sessionId,
            'explanation' => $quiz->explanation,
            'correct_answer' => $quiz->correct_answer,
            'total_score' => $user->fresh()->total_score,
        ]);
    }

    /**
     * Evaluasi jawaban essay siswa menggunakan AI / pencocokan konsep edukatif.
     *
     * @return array{is_correct: bool, score_percent: int, feedback: string}
     */
    protected function evaluateEssayAnswer(Quiz $quiz, string $answer, $user): array
    {
        $cleanAnswer = trim($answer);
        if (mb_strlen($cleanAnswer) < 3) {
            return [
                'is_correct' => false,
                'score_percent' => 0,
                'feedback' => 'Jawabanmu masih terlalu singkat. Coba tuliskan penjelasan atau langkah hitunganmu ya!',
            ];
        }

        // Coba evaluasi menggunakan AI jika provider aktif
        try {
            $aiManager = app(AIManager::class);
            $prompt = <<<PROMPT
Anda adalah Guru Matematika SD yang ramah, hangat, dan bijak di RoboMath.
Tugas: Evaluasi jawaban essay anak SD berikut terhadap kunci jawaban model.

Soal: "{$quiz->question}"
Kunci Jawaban Model: "{$quiz->correct_answer}"
Penjelasan / Pembahasan: "{$quiz->explanation}"
Jawaban Siswa: "{$cleanAnswer}"

Kriteria Penilaian untuk Anak SD:
- Bersikap suportif dan menghargai usaha berpikir anak.
- Jika langkah/intinya benar (walau kalimat berbeda atau ada sedikit tipo hitungan sederhana), berikan nilai tinggi (is_correct: true, score_percent: 80 - 100).
- Jika baru sebagian benar, berikan is_correct: true, score_percent: 60 - 75.
- Jika jawaban sama sekali tidak menjawab pertanyaan atau ngawur, berikan is_correct: false, score_percent: 20 - 40.
- Tuliskan feedback 1-2 kalimat hangat, mendidik, dan memuji usahanya.

Output WAJIB JSON murni tanpa markdown:
{
  "is_correct": true,
  "score_percent": 100,
  "feedback": "Pesan umpan balik hangat untuk siswa SD..."
}
PROMPT;

            $aiRes = $aiManager->chat($prompt, $user, 'json');
            $cleanJson = preg_replace('/^```(?:json)?\s*/i', '', trim($aiRes->text));
            $cleanJson = preg_replace('/\s*```$/', '', $cleanJson);
            $parsed = json_decode($cleanJson, true);

            if (is_array($parsed) && isset($parsed['is_correct'])) {
                return [
                    'is_correct' => (bool) $parsed['is_correct'],
                    'score_percent' => (int) ($parsed['score_percent'] ?? ($parsed['is_correct'] ? 100 : 30)),
                    'feedback' => $parsed['feedback'] ?? 'Jawaban essay kamu sudah diperiksa oleh RoboBot!',
                ];
            }
        } catch (\Throwable $e) {
            Log::warning('Essay AI evaluation failed: '.$e->getMessage());
        }

        // Heuristik cerdas cadangan jika AI offline
        $lowerAnswer = strtolower($cleanAnswer);
        $lowerKey = strtolower($quiz->correct_answer);

        preg_match_all('/\d+/', $lowerKey, $keyNumbers);
        preg_match_all('/\d+/', $lowerAnswer, $ansNumbers);
        $keyNums = $keyNumbers[0] ?? [];
        $ansNums = $ansNumbers[0] ?? [];

        $numMatch = false;
        if (! empty($keyNums) && ! empty($ansNums)) {
            $matchingNums = array_intersect($keyNums, $ansNums);
            $numMatch = count($matchingNums) > 0;
        }

        $lengthSufficient = mb_strlen($cleanAnswer) >= 12;

        if ($numMatch || $lengthSufficient) {
            return [
                'is_correct' => true,
                'score_percent' => $numMatch ? 100 : 85,
                'feedback' => 'Jawaban essay kamu luar biasa! Langkah berpikirmu sudah dicatat dengan baik. Cek pembahasan di bawah untuk melihat model jawaban lengkapnya ya! 🌟',
            ];
        }

        return [
            'is_correct' => true,
            'score_percent' => 70,
            'feedback' => 'Bagus sudah berusaha menjawab! Pelajari kunci jawaban model dan pembahasannya di bawah ya agar semakin jago! 👍',
        ];
    }

    public function result(Topic $topic)
    {
        $user = Auth::user();
        abort_unless($this->studentHasTopic($user, $topic), 404);

        $user->sesiBelajar()
            ->where('topic_id', $topic->id)
            ->whereNull('selesai_pada')
            ->update(['selesai_pada' => now()]);

        $attempts = QuizAttempt::where('user_id', $user->id)
            ->whereHas('quiz', fn ($q) => $q->where('topic_id', $topic->id))
            ->with('quiz')
            ->latest('id')
            ->take(10)
            ->get()
            ->reverse()
            ->values();

        $totalCorrect = $attempts->where('is_correct', true)->count();
        $totalAttempts = $attempts->count();
        $totalPoints = $attempts->sum('points_earned');

        return view('student.quiz.result', compact('topic', 'attempts', 'totalCorrect', 'totalAttempts', 'totalPoints'));
    }

    private function studentHasTopic(User $student, Topic $topic): bool
    {
        return Topic::availableToStudent($student)->whereKey($topic->id)->exists();
    }
}
