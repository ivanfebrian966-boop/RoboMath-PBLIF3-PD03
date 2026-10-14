<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SimpleXlsxExporter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $kelasFilter = $request->query('kelas', 'all');
        $periodFilter = $request->query('period', 'all'); // all, week, month

        $query = User::where('role', 'siswa')->where('is_active', true);

        if ($kelasFilter !== 'all') {
            $query->where('kelas', (int) $kelasFilter);
        }

        $students = $query->orderBy('kelas')->orderBy('name')->get()->map(function ($student) use ($periodFilter) {
            $attemptsQuery = $student->quizAttempts();

            if ($periodFilter === 'week') {
                $attemptsQuery->where('created_at', '>=', now()->subWeek());
            } elseif ($periodFilter === 'month') {
                $attemptsQuery->where('created_at', '>=', now()->subMonth());
            }

            $attempts = $attemptsQuery->get();
            $student->period_attempts = $attempts->count();
            $student->period_correct = $attempts->where('is_correct', true)->count();
            $student->period_accuracy = $student->period_attempts > 0
                ? round(($student->period_correct / $student->period_attempts) * 100) : 0;
            $student->period_points = $attempts->sum('points_earned');
            $student->completed_lessons = $student->progress()->where('status', 'selesai')->count();

            return $student;
        });

        $totalStudents = $students->count();
        $avgAccuracy = $students->avg('period_accuracy') ?? 0;
        $avgScore = $students->avg('total_score') ?? 0;

        return view('admin.reports.index', compact(
            'students', 'kelasFilter', 'periodFilter',
            'totalStudents', 'avgAccuracy', 'avgScore'
        ));
    }

    public function export(Request $request): BinaryFileResponse
    {
        $kelasFilter = $request->query('kelas', 'all');
        $periodFilter = $request->query('period', 'all');

        $query = User::where('role', 'siswa')->where('is_active', true);
        if ($kelasFilter !== 'all') {
            $query->where('kelas', (int) $kelasFilter);
        }

        $students = $query->orderBy('kelas')->orderBy('name')->get();
        $filename = 'laporan-performa-'.date('Y-m-d').'.xlsx';

        $headers = ['Nama', 'Email', 'Kelas', 'Total Skor', 'Level', 'Materi Selesai', 'Soal Dijawab', 'Jawaban Benar', 'Akurasi (%)', 'Poin Diperoleh'];

        $rows = [];
        foreach ($students as $student) {
            $attemptsQuery = $student->quizAttempts();
            if ($periodFilter === 'week') {
                $attemptsQuery->where('created_at', '>=', now()->subWeek());
            } elseif ($periodFilter === 'month') {
                $attemptsQuery->where('created_at', '>=', now()->subMonth());
            }
            $attempts = $attemptsQuery->get();
            $total = $attempts->count();
            $correct = $attempts->where('is_correct', true)->count();
            $accuracy = $total > 0 ? round(($correct / $total) * 100) : 0;
            $completedLessons = $student->progress()->where('status', 'selesai')->count();

            $rows[] = [
                $student->name,
                $student->email,
                "Kelas {$student->kelas} SD",
                $student->total_score,
                $student->level_name,
                $completedLessons,
                $total,
                $correct,
                "{$accuracy}%",
                $attempts->sum('points_earned'),
            ];
        }

        return SimpleXlsxExporter::download($filename, $headers, $rows, 'Laporan Performa');
    }
}
