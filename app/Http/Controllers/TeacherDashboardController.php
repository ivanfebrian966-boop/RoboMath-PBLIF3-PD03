<?php

namespace App\Http\Controllers;

use App\Models\Progress;
use App\Models\QuizAttempt;
use App\Models\SesiBelajar;
use App\Models\Topic;
use App\Models\User;
use App\Services\SimpleXlsxExporter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TeacherDashboardController extends Controller
{
    public function dashboard(): View
    {
        $user = Auth::user();

        $studentsQuery = User::where('role', 'siswa');
        if (! $user->isAdmin()) {
            $studentsQuery->whereHas('classRooms', fn ($query) => $query->where('kelas.teacher_id', $user->id));
        }

        $students = $studentsQuery->orderBy('kelas')->orderBy('name')->get();
        $studentIds = $students->modelKeys();
        $totalStudents = $students->count();

        $lessonCompletionByStudent = DB::table('anggota_kelas')
            ->join('kelas', 'kelas.id', '=', 'anggota_kelas.class_room_id')
            ->join('kelas_topik', 'kelas_topik.class_room_id', '=', 'kelas.id')
            ->join('topik', 'topik.id', '=', 'kelas_topik.topic_id')
            ->join('pengguna', 'pengguna.id', '=', 'anggota_kelas.student_id')
            ->join('lessons', 'lessons.topic_id', '=', 'topik.id')
            ->leftJoin('progress', function (JoinClause $join): void {
                $join->on('progress.lesson_id', '=', 'lessons.id')
                    ->on('progress.user_id', '=', 'pengguna.id')
                    ->where('progress.status', 'selesai');
            })
            ->whereIn('pengguna.id', $studentIds)
            ->whereColumn('topik.kelas_level', 'pengguna.kelas')
            ->where('kelas.is_active', true)
            ->where('topik.is_active', true)
            ->where('topik.review_status', 'approved')
            ->groupBy('pengguna.id')
            ->selectRaw(
                'pengguna.id as student_id, COUNT(DISTINCT lessons.id) as total_lessons, COUNT(DISTINCT progress.lesson_id) as completed_lessons'
            )
            ->get()
            ->keyBy('student_id');

        $studentCompletionStats = $students->map(function ($student) use ($lessonCompletionByStudent): array {
            $lessonCounts = $lessonCompletionByStudent->get($student->id);
            $totalLessons = (int) ($lessonCounts->total_lessons ?? 0);
            $completedLessons = (int) ($lessonCounts->completed_lessons ?? 0);

            return [
                'name' => $student->name,
                'email' => $student->email,
                'kelas' => $student->kelas,
                'completed_lessons' => $completedLessons,
                'total_lessons' => $totalLessons,
                'remaining_lessons' => max($totalLessons - $completedLessons, 0),
                'percentage' => $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0,
            ];
        });
        $totalCompletedLessons = $studentCompletionStats->sum('completed_lessons');
        $totalAvailableLessons = $studentCompletionStats->sum('total_lessons');
        $totalRemainingLessons = max($totalAvailableLessons - $totalCompletedLessons, 0);
        $overallCompletionPercentage = $totalAvailableLessons > 0
            ? round(($totalCompletedLessons / $totalAvailableLessons) * 100)
            : 0;

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

        // Recent student activities
        $recentActivities = QuizAttempt::with(['user', 'quiz.topic'])
            ->whereIn('user_id', $studentIds)
            ->latest()
            ->take(6)
            ->get();
        $recentSessions = SesiBelajar::with(['user', 'topic'])
            ->whereIn('user_id', $studentIds)
            ->latest('dimulai_pada')
            ->take(6)
            ->get();

        return view('teacher.dashboard', compact(
            'user', 'totalStudents', 'avgScore', 'overallAccuracy',
            'classStats', 'topStudents', 'students', 'recentActivities', 'recentSessions',
            'studentCompletionStats', 'totalCompletedLessons', 'totalRemainingLessons',
            'totalAvailableLessons', 'overallCompletionPercentage'
        ));
    }

    public function studentDetail(User $student): View
    {
        $user = Auth::user();
        if ($user->isGuru() && ! $student->classRooms()->where('kelas.teacher_id', $user->id)->exists()) {
            abort(404);
        }

        $studentAttempts = QuizAttempt::where('user_id', $student->id);
        $totalAttempts = (clone $studentAttempts)->count();
        $correctAttempts = (clone $studentAttempts)->where('is_correct', true)->count();
        $accuracy = $totalAttempts > 0 ? round(($correctAttempts / $totalAttempts) * 100) : 0;
        $sessionCount = SesiBelajar::where('user_id', $student->id)->count();
        $completedLessons = Progress::where('user_id', $student->id)
            ->where('status', 'selesai')
            ->count();

        $rankQuery = User::where('role', 'siswa')
            ->where('is_active', true)
            ->where('kelas', $student->kelas)
            ->when($user->isGuru(), fn ($query) => $query->whereHas(
                'classRooms',
                fn ($classRooms) => $classRooms->where('kelas.teacher_id', $user->id)
            ));
        $rank = (clone $rankQuery)
            ->where('total_score', '>', $student->total_score)
            ->count() + 1;

        $topics = Topic::where('kelas_level', $student->kelas)
            ->where('is_active', true)
            ->where('review_status', 'approved')
            ->when($user->isGuru(), fn ($query) => $query->whereHas('classRooms', fn ($classRooms) => $classRooms
                ->where('kelas.teacher_id', $user->id)
                ->whereHas('students', fn ($students) => $students->whereKey($student->id))))
            ->withCount('lessons')
            ->orderBy('order')
            ->get();
        $topicIds = $topics->modelKeys();

        $attemptStats = QuizAttempt::query()
            ->join('soal', 'soal.id', '=', 'percobaan_soal.quiz_id')
            ->where('percobaan_soal.user_id', $student->id)
            ->whereIn('soal.topic_id', $topicIds)
            ->selectRaw('soal.topic_id, COUNT(*) as total_attempts, SUM(percobaan_soal.is_correct) as correct_attempts')
            ->groupBy('soal.topic_id')
            ->get()
            ->keyBy('topic_id');

        $lessonStats = Progress::where('user_id', $student->id)
            ->where('status', 'selesai')
            ->whereIn('topic_id', $topicIds)
            ->selectRaw('topic_id, COUNT(*) as completed_lessons')
            ->groupBy('topic_id')
            ->get()
            ->keyBy('topic_id');

        $topics = $topics->map(function ($topic) use ($attemptStats, $lessonStats) {
            $attemptStat = $attemptStats->get($topic->id);
            $totalAttempts = (int) ($attemptStat->total_attempts ?? 0);
            $correctAttempts = (int) ($attemptStat->correct_attempts ?? 0);
            $completedLessons = (int) ($lessonStats->get($topic->id)->completed_lessons ?? 0);

            $topic->accuracy = $totalAttempts > 0
                ? round(($correctAttempts / $totalAttempts) * 100)
                : 0;
            $topic->lesson_progress = $topic->lessons_count > 0
                ? round(($completedLessons / $topic->lessons_count) * 100)
                : 0;

            return $topic;
        });

        $recentSessions = SesiBelajar::where('user_id', $student->id)
            ->with('topic')
            ->latest('dimulai_pada')
            ->take(10)
            ->get();

        return view('teacher.students.show', compact(
            'student',
            'topics',
            'recentSessions',
            'accuracy',
            'sessionCount',
            'completedLessons',
            'rank'
        ));
    }

    /**
     * Download laporan siswa sebagai Excel (.xlsx)
     */
    public function downloadReport(Request $request): BinaryFileResponse
    {
        ['kelasFilter' => $kelasFilter, 'headers' => $headers, 'rows' => $rows] = $this->reportData($request);
        $filename = 'laporan-siswa-'.($kelasFilter !== 'all' ? "kelas{$kelasFilter}-" : '').now()->format('Y-m-d').'.xlsx';

        return SimpleXlsxExporter::download($filename, $headers, $rows, 'Laporan Siswa');
    }

    public function downloadReportPdf(Request $request): Response
    {
        ['kelasFilter' => $kelasFilter, 'headers' => $headers, 'rows' => $rows] = $this->reportData($request);
        $filename = 'laporan-siswa-'.($kelasFilter !== 'all' ? "kelas{$kelasFilter}-" : '').now()->format('Y-m-d').'.pdf';

        return Pdf::loadView('teacher.reports.progress-pdf', [
            'headers' => $headers,
            'rows' => $rows,
            'kelasFilter' => $kelasFilter,
            'generatedAt' => now(),
        ])
            ->setPaper('a4', 'landscape')
            ->download($filename);
    }

    /**
     * @return array{
     *     kelasFilter: string,
     *     headers: list<string>,
     *     rows: list<list<mixed>>
     * }
     */
    private function reportData(Request $request): array
    {
        $kelasFilter = $request->query('kelas', 'all');
        if (! is_string($kelasFilter) || ! in_array($kelasFilter, ['all', '1', '2', '3', '4', '5', '6'], true)) {
            abort(404);
        }

        $user = Auth::user();
        $students = User::query()
            ->where('role', 'siswa')
            ->where('is_active', true)
            ->when($user->isGuru(), fn ($query) => $query->whereHas(
                'classRooms',
                fn ($classRooms) => $classRooms->where('kelas.teacher_id', $user->id)
            ))
            ->when($kelasFilter !== 'all', fn ($query) => $query->where('kelas', (int) $kelasFilter))
            ->withCount([
                'quizAttempts as total_attempts',
                'quizAttempts as correct_attempts' => fn ($query) => $query->where('is_correct', true),
                'progress as completed_lessons' => fn ($query) => $query->where('status', 'selesai'),
                'badges',
            ])
            ->orderBy('kelas')
            ->orderBy('name')
            ->get();

        $headers = ['Nama Siswa', 'Email', 'Kelas', 'Total Poin', 'Level', 'Materi Selesai', 'Akurasi (%)', 'Lencana'];

        $rows = $students->map(fn (User $student): array => [
            $student->name,
            $student->email,
            'Kelas '.($student->kelas ?? '-').' SD',
            $student->total_score,
            "{$student->level_name} (Level {$student->level})",
            $student->completed_lessons,
            ($student->total_attempts > 0 ? round(($student->correct_attempts / $student->total_attempts) * 100) : 0).'%',
            $student->badges_count,
        ])->all();

        return compact('kelasFilter', 'headers', 'rows');
    }
}
