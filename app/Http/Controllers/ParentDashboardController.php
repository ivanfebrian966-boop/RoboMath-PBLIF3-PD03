<?php

namespace App\Http\Controllers;

use App\Models\Progress;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;

class ParentDashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $children = $user->students()->get();

        $childrenData = $children->map(function ($child) {
            $totalAttempts = QuizAttempt::where('user_id', $child->id)->count();
            $correctAttempts = QuizAttempt::where('user_id', $child->id)->where('is_correct', true)->count();
            $child->accuracy = $totalAttempts > 0 ? round(($correctAttempts / $totalAttempts) * 100) : 0;
            $child->completed_lessons = Progress::where('user_id', $child->id)->where('status', 'selesai')->count();
            $child->total_badges = $child->badges()->count();

            // Recent activity
            $child->recent_attempts = QuizAttempt::where('user_id', $child->id)
                ->with('quiz.topic')
                ->latest()
                ->take(5)
                ->get();

            return $child;
        });

        return view('parent.dashboard', compact('user', 'childrenData'));
    }
}
