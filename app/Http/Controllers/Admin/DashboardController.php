<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiRecommendation;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        // 1. Ringkasan Sistem
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalGuru = User::where('role', 'guru')->count();
        $totalSoal = Quiz::where('is_active', true)->count();
        $totalMateri = Lesson::where('is_active', true)->count();
        $totalAttempts = QuizAttempt::count();
        $correctAttempts = QuizAttempt::where('is_correct', true)->count();
        $overallAccuracy = $totalAttempts > 0 ? round(($correctAttempts / $totalAttempts) * 100) : 0;
        $pendingAiRecommendations = AiRecommendation::where('status', 'pending')->count();
        $inactiveUsers = User::where('is_active', false)->count();

        // 2. Statistik Pengguna
        $topSiswa = User::where('role', 'siswa')
            ->orderByDesc('total_score')
            ->take(5)
            ->get();

        $recentRecommendations = AiRecommendation::with(['student', 'topic'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // Distribusi Kelas Siswa
        $classDistribution = User::where('role', 'siswa')
            ->selectRaw('kelas, count(*) as total, avg(total_score) as avg_score')
            ->groupBy('kelas')
            ->orderBy('kelas')
            ->get();

        // 3. Laporan Performa (Gabungan Laporan Performa)
        $kelasFilter = $request->query('kelas', 'all');
        $studentsQuery = User::where('role', 'siswa')->where('is_active', true);
        if ($kelasFilter !== 'all') {
            $studentsQuery->where('kelas', (int) $kelasFilter);
        }

        $performanceStudents = $studentsQuery->orderByDesc('total_score')->take(10)->get()->map(function ($student) {
            $attempts = $student->quizAttempts;
            $student->period_attempts = $attempts->count();
            $student->period_correct = $attempts->where('is_correct', true)->count();
            $student->period_accuracy = $student->period_attempts > 0
                ? round(($student->period_correct / $student->period_attempts) * 100) : 0;
            $student->completed_lessons = $student->progress()->where('status', 'selesai')->count();

            return $student;
        });

        $avgScore = User::where('role', 'siswa')->avg('total_score') ?? 0;

        return view('admin.dashboard', compact(
            'user', 'totalSiswa', 'totalGuru', 'totalSoal', 'totalMateri',
            'totalAttempts', 'overallAccuracy', 'pendingAiRecommendations',
            'inactiveUsers', 'topSiswa', 'recentRecommendations', 'classDistribution',
            'performanceStudents', 'avgScore', 'kelasFilter'
        ));
    }
}
