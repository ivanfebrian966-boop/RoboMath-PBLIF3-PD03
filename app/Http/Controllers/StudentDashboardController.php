<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\Lesson;
use App\Models\Progress;
use App\Models\QuizAttempt;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentDashboardController extends Controller
{
    public function dashboard(): View
    {
        $user = Auth::user();

        $totalTopics = Topic::where('kelas_level', $user->kelas)->where('is_active', true)->count();
        $completedLessons = Progress::where('user_id', $user->id)->where('status', 'selesai')->count();
        $totalLessons = Lesson::whereHas('topic', fn ($q) => $q->where('kelas_level', $user->kelas))->count();
        $totalAttempts = QuizAttempt::where('user_id', $user->id)->count();
        $correctAttempts = QuizAttempt::where('user_id', $user->id)->where('is_correct', true)->count();
        $accuracy = $totalAttempts > 0 ? round(($correctAttempts / $totalAttempts) * 100) : 0;

        // Koleksi Badge (Dashboard + Koleksi Badge)
        $earnedBadges = $user->badges()->latest('earned_at')->get();
        $allBadges = Badge::all();
        $earnedIds = $earnedBadges->pluck('id')->toArray();
        $totalBadges = $earnedBadges->count();

        // Progress per topic
        $topics = Topic::where('kelas_level', $user->kelas)
            ->where('is_active', true)
            ->withCount(['lessons', 'quizzes'])
            ->orderBy('order')
            ->get()
            ->map(function ($topic) use ($user) {
                $completedInTopic = Progress::where('user_id', $user->id)
                    ->where('topic_id', $topic->id)
                    ->where('status', 'selesai')
                    ->count();
                $topic->progress_percent = $topic->lessons_count > 0
                    ? round(($completedInTopic / $topic->lessons_count) * 100)
                    : 0;

                return $topic;
            });

        // Recent activity
        $recentAttempts = QuizAttempt::where('user_id', $user->id)
            ->with('quiz.topic')
            ->latest()
            ->take(5)
            ->get();

        // Weak topics (accuracy < 60%)
        $weakTopics = Topic::where('kelas_level', $user->kelas)
            ->where('is_active', true)
            ->get()
            ->map(function ($topic) use ($user) {
                $attempts = QuizAttempt::where('user_id', $user->id)
                    ->whereHas('quiz', fn ($q) => $q->where('topic_id', $topic->id))
                    ->get();
                $total = $attempts->count();
                $correct = $attempts->where('is_correct', true)->count();
                $topic->accuracy = $total > 0 ? round(($correct / $total) * 100) : null;

                return $topic;
            })
            ->filter(fn ($t) => $t->accuracy !== null && $t->accuracy < 60)
            ->take(3);

        return view('student.dashboard', compact(
            'user', 'totalTopics', 'completedLessons', 'totalLessons',
            'accuracy', 'earnedBadges', 'allBadges', 'earnedIds', 'totalBadges',
            'topics', 'recentAttempts', 'weakTopics'
        ));
    }

    public function achievements(): View
    {
        $user = Auth::user();
        $earnedBadges = $user->badges()->get();
        $allBadges = Badge::all();
        $earnedIds = $earnedBadges->pluck('id')->toArray();

        return view('student.achievements', compact('user', 'earnedBadges', 'allBadges', 'earnedIds'));
    }
}
