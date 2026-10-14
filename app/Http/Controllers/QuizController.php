<?php

namespace App\Http\Controllers;

use App\Models\AiRecommendation;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Topic;
use App\Services\GamificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $topics = Topic::where('is_active', true)
            ->when($user->kelas, fn ($q) => $q->where('kelas_level', $user->kelas))
            ->with(['quizzes' => function ($q) {
                $q->where('is_active', true)->orderBy('id');
            }])
            ->withCount(['quizzes' => function ($q) {
                $q->where('is_active', true);
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

        $quizzes = Quiz::where('topic_id', $topic->id)
            ->where('is_active', true)
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
            $quizStatuses[$quiz->id] = [
                'is_correct' => $quizAttempts->contains('is_correct', true),
                'attempts_count' => $quizAttempts->count(),
            ];
        }

        return view('student.quiz.play', compact('topic', 'quizzes', 'startIndex', 'quizStatuses'));
    }

    public function submit(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'answer' => 'required|string',
            'time_spent' => 'nullable|integer',
        ]);

        $quiz = Quiz::findOrFail($request->quiz_id);
        $isCorrect = strtolower(trim($request->answer)) === strtolower(trim($quiz->correct_answer));

        $previouslyCorrect = QuizAttempt::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->where('is_correct', true)
            ->exists();

        $pointsEarned = ($isCorrect && ! $previouslyCorrect) ? $quiz->points : 0;

        $attempt = QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'answer' => $request->answer,
            'is_correct' => $isCorrect,
            'time_spent' => $request->time_spent ?? 0,
            'points_earned' => $pointsEarned,
        ]);

        if ($pointsEarned > 0) {
            $user->addScore($pointsEarned);
        }

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
            'points' => $pointsEarned,
            'already_earned' => $previouslyCorrect,
            'explanation' => $quiz->explanation,
            'correct_answer' => $quiz->correct_answer,
            'total_score' => $user->fresh()->total_score,
        ]);
    }

    public function result(Topic $topic)
    {
        $user = Auth::user();
        $attempts = QuizAttempt::where('user_id', $user->id)
            ->whereHas('quiz', fn ($q) => $q->where('topic_id', $topic->id))
            ->with('quiz')
            ->latest()
            ->take(10)
            ->get();

        $totalCorrect = $attempts->where('is_correct', true)->count();
        $totalAttempts = $attempts->count();
        $totalPoints = $attempts->sum('points_earned');

        return view('student.quiz.result', compact('topic', 'attempts', 'totalCorrect', 'totalAttempts', 'totalPoints'));
    }
}
