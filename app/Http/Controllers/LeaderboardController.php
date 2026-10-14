<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Progress;
use App\Models\QuizAttempt;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LeaderboardController extends Controller
{
    /**
     * Halaman Siswa: Progress & Leaderboard (Grafik progres + skor + leaderboard)
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $kelasFilter = $request->query('kelas', 'all');

        $query = User::where('role', 'siswa')->where('is_active', true);

        if ($kelasFilter !== 'all') {
            $query->where('kelas', (int) $kelasFilter);
        }

        $leaderboard = $query->orderByDesc('total_score')->take(50)->get();

        // Find user rank
        $userRank = null;
        foreach ($leaderboard as $index => $student) {
            if ($student->id === $user->id) {
                $userRank = $index + 1;
                break;
            }
        }

        // If user not in top 50, calculate their rank
        if ($userRank === null && $user->isSiswa()) {
            $rankQuery = User::where('role', 'siswa')->where('is_active', true);
            if ($kelasFilter !== 'all') {
                $rankQuery->where('kelas', (int) $kelasFilter);
            }
            $userRank = $rankQuery->where('total_score', '>', $user->total_score)->count() + 1;
        }

        // Hitung Data Grafik Progres & Skor Siswa
        $completedLessons = Progress::where('user_id', $user->id)->where('status', 'selesai')->count();
        $totalLessons = Lesson::whereHas('topic', fn ($q) => $q->where('kelas_level', $user->kelas))->count();
        $progressPercent = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;

        $totalAttempts = QuizAttempt::where('user_id', $user->id)->count();
        $correctAttempts = QuizAttempt::where('user_id', $user->id)->where('is_correct', true)->count();
        $userAccuracy = $totalAttempts > 0 ? round(($correctAttempts / $totalAttempts) * 100) : 0;

        // Progres per Topik untuk Bar Grafik
        $topicProgress = Topic::where('kelas_level', $user->kelas)
            ->where('is_active', true)
            ->withCount('lessons')
            ->orderBy('order')
            ->get()
            ->map(function ($topic) use ($user) {
                $completed = Progress::where('user_id', $user->id)
                    ->where('topic_id', $topic->id)
                    ->where('status', 'selesai')
                    ->count();
                $topic->percent = $topic->lessons_count > 0 ? round(($completed / $topic->lessons_count) * 100) : 0;
                $topic->completed_count = $completed;

                return $topic;
            });

        // Riwayat Skor Latihan Terkini
        $recentScoreHistory = QuizAttempt::where('user_id', $user->id)
            ->with('quiz.topic')
            ->latest()
            ->take(6)
            ->get();

        return view('student.leaderboard', compact(
            'leaderboard', 'kelasFilter', 'userRank',
            'completedLessons', 'totalLessons', 'progressPercent',
            'totalAttempts', 'correctAttempts', 'userAccuracy',
            'topicProgress', 'recentScoreHistory'
        ));
    }

    /**
     * Halaman Guru: Progress Siswa (Pantau progres + leaderboard + unduh laporan)
     */
    public function teacherLeaderboard(Request $request): View
    {
        $kelasFilter = $request->query('kelas', 'all');

        $query = User::where('role', 'siswa')->where('is_active', true);

        if ($kelasFilter !== 'all') {
            $query->where('kelas', (int) $kelasFilter);
        }

        $leaderboard = $query->orderByDesc('total_score')->take(50)->get()->map(function ($student) {
            $attempts = $student->quizAttempts;
            $total = $attempts->count();
            $correct = $attempts->where('is_correct', true)->count();
            $student->accuracy = $total > 0 ? round(($correct / $total) * 100) : 0;
            $student->attempts_count = $total;
            $student->completed_lessons = $student->progress()->where('status', 'selesai')->count();

            return $student;
        });

        $totalSiswa = $leaderboard->count();
        $avgScore = $leaderboard->avg('total_score') ?? 0;
        $avgAccuracy = $leaderboard->avg('accuracy') ?? 0;

        return view('teacher.leaderboard', compact('leaderboard', 'kelasFilter', 'totalSiswa', 'avgScore', 'avgAccuracy'));
    }
}
