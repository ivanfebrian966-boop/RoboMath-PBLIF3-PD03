<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiRecommendation;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalSiswa = User::where('role', 'siswa')->count();
        $totalGuru = User::where('role', 'guru')->count();
        $totalSoal = Quiz::where('is_active', true)->count();
        $totalMateri = Lesson::where('is_active', true)->count();
        $totalAttempts = QuizAttempt::count();
        $correctAttempts = QuizAttempt::where('is_correct', true)->count();
        $overallAccuracy = $totalAttempts > 0 ? round(($correctAttempts / $totalAttempts) * 100) : 0;
        $pendingAiRecommendations = AiRecommendation::where('status', 'pending')->count();
        $inactiveUsers = User::where('is_active', false)->count();

        // Top siswa
        $topSiswa = User::where('role', 'siswa')
            ->orderByDesc('total_score')
            ->take(5)
            ->get();

        // Recent AI recommendations
        $recentRecommendations = AiRecommendation::with(['student', 'topic'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'user', 'totalSiswa', 'totalGuru', 'totalSoal', 'totalMateri',
            'totalAttempts', 'overallAccuracy', 'pendingAiRecommendations',
            'inactiveUsers', 'topSiswa', 'recentRecommendations'
        ));
    }
}
