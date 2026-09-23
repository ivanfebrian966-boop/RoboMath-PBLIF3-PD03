<?php

namespace App\Http\Controllers;

use App\Models\Progress;
use App\Models\QuizAttempt;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TeacherDashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // Get students in teacher's classes
        $students = User::where('role', 'siswa')->orderBy('kelas')->orderBy('name')->get();
        $totalStudents = $students->count();

        // Overall stats
        $avgScore = $students->avg('total_score') ?: 0;
        $totalAttempts = QuizAttempt::whereIn('user_id', $students->pluck('id'))->count();
        $correctAttempts = QuizAttempt::whereIn('user_id', $students->pluck('id'))->where('is_correct', true)->count();
        $overallAccuracy = $totalAttempts > 0 ? round(($correctAttempts / $totalAttempts) * 100) : 0;

        // Per-class stats
        $classStats = $students->groupBy('kelas')->map(function ($classStudents, $kelas) {
            $ids = $classStudents->pluck('id');
            $total = QuizAttempt::whereIn('user_id', $ids)->count();
            $correct = QuizAttempt::whereIn('user_id', $ids)->where('is_correct', true)->count();

            return [
                'kelas' => $kelas,
                'count' => $classStudents->count(),
                'avg_score' => round($classStudents->avg('total_score')),
                'accuracy' => $total > 0 ? round(($correct / $total) * 100) : 0,
            ];
        })->values();

        // Top performing students
        $topStudents = $students->sortByDesc('total_score')->take(10)->values();

        return view('teacher.dashboard', compact(
            'user', 'totalStudents', 'avgScore', 'overallAccuracy',
            'classStats', 'topStudents', 'students'
        ));
    }

    public function studentDetail(User $student)
    {
        $topics = Topic::where('kelas_level', $student->kelas)
            ->where('is_active', true)
            ->get()
            ->map(function ($topic) use ($student) {
                $attempts = QuizAttempt::where('user_id', $student->id)
                    ->whereHas('quiz', fn ($q) => $q->where('topic_id', $topic->id))
                    ->get();
                $topic->total_attempts = $attempts->count();
                $topic->correct_attempts = $attempts->where('is_correct', true)->count();
                $topic->accuracy = $topic->total_attempts > 0
                    ? round(($topic->correct_attempts / $topic->total_attempts) * 100) : 0;

                $completedLessons = Progress::where('user_id', $student->id)
                    ->where('topic_id', $topic->id)
                    ->where('status', 'selesai')
                    ->count();
                $totalLessons = $topic->lessons()->count();
                $topic->lesson_progress = $totalLessons > 0
                    ? round(($completedLessons / $totalLessons) * 100) : 0;

                return $topic;
            });

        $recentAttempts = QuizAttempt::where('user_id', $student->id)
            ->with('quiz.topic')
            ->latest()
            ->take(10)
            ->get();

        $badges = $student->badges;

        return view('teacher.students.show', compact('student', 'topics', 'recentAttempts', 'badges'));
    }

    /**
     * Download laporan siswa sebagai CSV
     */
    public function downloadReport(Request $request): StreamedResponse
    {
        $kelasFilter = $request->query('kelas', 'all');

        $query = User::where('role', 'siswa')->where('is_active', true)->orderBy('kelas')->orderBy('name');

        if ($kelasFilter !== 'all') {
            $query->where('kelas', (int) $kelasFilter);
        }

        $students = $query->get();

        $filename = 'laporan-siswa-'.($kelasFilter !== 'all' ? "kelas{$kelasFilter}-" : '').date('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($students) {
            $handle = fopen('php://output', 'w');

            // BOM for Excel UTF-8
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, ['Nama Siswa', 'Email', 'Kelas', 'Total Poin', 'Level', 'Materi Selesai', 'Akurasi (%)', 'Lencana']);

            foreach ($students as $student) {
                $totalAttempts = $student->quizAttempts()->count();
                $correctAttempts = $student->quizAttempts()->where('is_correct', true)->count();
                $accuracy = $totalAttempts > 0 ? round(($correctAttempts / $totalAttempts) * 100) : 0;
                $completedLessons = $student->progress()->where('status', 'selesai')->count();
                $badgeCount = $student->badges()->count();

                fputcsv($handle, [
                    $student->name,
                    $student->email,
                    "Kelas {$student->kelas} SD",
                    $student->total_score,
                    "{$student->level_name} (Level {$student->level})",
                    $completedLessons,
                    "{$accuracy}%",
                    $badgeCount,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
