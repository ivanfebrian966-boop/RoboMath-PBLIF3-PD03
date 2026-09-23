<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    public function index(Request $request)
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

        return view('student.leaderboard', compact('leaderboard', 'kelasFilter', 'userRank'));
    }

    public function teacherLeaderboard(Request $request)
    {
        $kelasFilter = $request->query('kelas', 'all');

        $query = User::where('role', 'siswa')->where('is_active', true);

        if ($kelasFilter !== 'all') {
            $query->where('kelas', (int) $kelasFilter);
        }

        $leaderboard = $query->orderByDesc('total_score')->take(50)->get();

        return view('teacher.leaderboard', compact('leaderboard', 'kelasFilter'));
    }
}
