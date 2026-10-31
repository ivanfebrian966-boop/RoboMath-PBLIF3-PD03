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
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        // 1. Ringkasan Sistem
        $totalSiswa = User::where('role', 'siswa')->where('is_active', true)->count();
        $totalGuru = User::where('role', 'guru')->where('is_active', true)->count();
        $totalSoal = Quiz::where('is_active', true)->count();
        $totalMateri = Lesson::where('is_active', true)->count();
        $totalAttempts = QuizAttempt::count();
        $correctAttempts = QuizAttempt::where('is_correct', true)->count();
        $overallAccuracy = $totalAttempts > 0 ? round(($correctAttempts / $totalAttempts) * 100) : 0;
        $pendingAiRecommendations = AiRecommendation::where('status', 'pending')->count() + Quiz::where('review_status', 'pending')->count();
        $kelasOptions = ['all', '1', '2', '3', '4', '5', '6'];
        $validated = $request->validate([
            'kelas' => ['sometimes', Rule::in($kelasOptions)],
        ]);
        $kelasFilter = $validated['kelas'] ?? 'all';

        $studentsQuery = User::query()
            ->where('role', 'siswa')
            ->where('is_active', true)
            ->withCount([
                'quizAttempts',
                'quizAttempts as correct_attempts_count' => fn ($query) => $query->where('is_correct', true),
                'progress as completed_lessons_count' => fn ($query) => $query->where('status', 'selesai'),
            ]);
        if ($kelasFilter !== 'all') {
            $studentsQuery->where('kelas', (int) $kelasFilter);
        }

        $performanceStudents = $studentsQuery
            ->orderByDesc('total_score')
            ->orderBy('name')
            ->paginate(5)
            ->withQueryString()
            ->through(function ($student) {
                $student->period_attempts = $student->quiz_attempts_count;
                $student->period_correct = $student->correct_attempts_count;
                $student->period_accuracy = $student->period_attempts > 0
                    ? (int) round(($student->period_correct / $student->period_attempts) * 100)
                    : 0;
                $student->completed_lessons = $student->completed_lessons_count;

                return $student;
            });

        $classPerformance = DB::table('pengguna as students')
            ->leftJoin('percobaan_soal as attempts', 'attempts.user_id', '=', 'students.id')
            ->where('students.role', 'siswa')
            ->where('students.is_active', true)
            ->whereNotNull('students.kelas')
            ->selectRaw('
                students.kelas as kelas,
                COUNT(DISTINCT students.id) as student_count,
                COUNT(attempts.id) as attempt_count,
                SUM(CASE WHEN attempts.is_correct = 1 THEN 1 ELSE 0 END) as correct_count
            ')
            ->groupBy('students.kelas')
            ->orderBy('students.kelas')
            ->get()
            ->map(function ($class) {
                $class->accuracy = $class->attempt_count > 0
                    ? (int) round(($class->correct_count / $class->attempt_count) * 100)
                    : 0;

                return $class;
            });

        return view('admin.dashboard', compact(
            'user',
            'totalSiswa',
            'totalGuru',
            'totalSoal',
            'totalMateri',
            'totalAttempts',
            'overallAccuracy',
            'pendingAiRecommendations',
            'performanceStudents',
            'classPerformance',
            'kelasFilter'
        ));
    }
}
