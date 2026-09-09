<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Topic;
use App\Services\GamificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $topics = Topic::where('kelas_level', $user->kelas)
            ->where('is_active', true)
            ->withCount('quizzes')
            ->orderBy('order')
            ->get();

        return view('student.quiz.index', compact('topics'));
    }

    public function play(Topic $topic)
    {
        $user = Auth::user();

        // Get quizzes for this topic, excluding already correctly answered
        $answeredCorrectly = QuizAttempt::where('user_id', $user->id)
            ->where('is_correct', true)
            ->pluck('quiz_id');

        $quizzes = Quiz::where('topic_id', $topic->id)
            ->where('is_active', true)
            ->whereNotIn('id', $answeredCorrectly)
            ->inRandomOrder()
            ->take(10)
            ->get();

        if ($quizzes->isEmpty()) {
            // All quizzes answered - allow retry
            $quizzes = Quiz::where('topic_id', $topic->id)
                ->where('is_active', true)
                ->inRandomOrder()
                ->take(10)
                ->get();
        }

        return view('student.quiz.play', compact('topic', 'quizzes'));
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
        $pointsEarned = $isCorrect ? $quiz->points : 0;

        $attempt = QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'answer' => $request->answer,
            'is_correct' => $isCorrect,
            'time_spent' => $request->time_spent ?? 0,
            'points_earned' => $pointsEarned,
        ]);

        if ($isCorrect) {
            $user->addScore($pointsEarned);
        }

        // Check badges
        app(GamificationService::class)->checkBadges($user);

        return response()->json([
            'correct' => $isCorrect,
            'points' => $pointsEarned,
            'explanation' => $quiz->explanation,
            'correct_answer' => $quiz->correct_answer,
            'total_score' => $user->fresh()->total_score,
        ]);
    }

    public function result(Topic $topic)
    {
        $user = Auth::user();
        $attempts = QuizAttempt::where('user_id', $user->id)
            ->whereHas('quiz', fn($q) => $q->where('topic_id', $topic->id))
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
