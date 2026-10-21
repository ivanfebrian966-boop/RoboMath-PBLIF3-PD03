<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiRecommendation;
use App\Models\Quiz;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AiRecommendationController extends Controller
{
    public function index(Request $request)
    {
        $activeTab = $request->query('tab', 'quiz');
        $statusFilter = $request->query('status', 'pending');
        $topicFilter = $request->query('topic_id');

        $topics = Topic::where('is_active', true)
            ->orderBy('kelas_level')
            ->orderBy('order')
            ->get();

        // Query Soal AI dari Modul / Kelola Materi
        $quizzesQuery = Quiz::with(['topic', 'documentSummary', 'reviewer'])
            ->when($topicFilter, fn ($q) => $q->where('topic_id', $topicFilter))
            ->when($statusFilter !== 'all', fn ($q) => $q->where('review_status', $statusFilter))
            ->latest();

        $quizzes = $quizzesQuery->paginate(15)->withQueryString();

        // Query Rekomendasi Diagnostik Siswa
        $recommendations = AiRecommendation::with(['student', 'topic', 'quiz', 'reviewer'])
            ->when($topicFilter, fn ($q) => $q->where('topic_id', $topicFilter))
            ->when($statusFilter !== 'all', fn ($q) => $q->where('status', $statusFilter))
            ->latest()
            ->paginate(15, ['*'], 'rec_page')
            ->withQueryString();

        // Pending counts
        $pendingQuizzesCount = Quiz::where('review_status', 'pending')->count();
        $pendingRecommendationsCount = AiRecommendation::where('status', 'pending')->count();
        $totalPendingCount = $pendingQuizzesCount + $pendingRecommendationsCount;

        // Pending count per topic (untuk banner aksi massal)
        $pendingQuizzesByTopic = Quiz::where('review_status', 'pending')
            ->selectRaw('topic_id, count(*) as count')
            ->groupBy('topic_id')
            ->pluck('count', 'topic_id');

        return view('admin.ai-recommendations.index', compact(
            'quizzes',
            'recommendations',
            'topics',
            'activeTab',
            'statusFilter',
            'topicFilter',
            'pendingQuizzesCount',
            'pendingRecommendationsCount',
            'totalPendingCount',
            'pendingQuizzesByTopic'
        ));
    }

    /**
     * Setujui butir soal AI dan terbitkan langsung ke siswa.
     */
    public function approveQuiz(Quiz $quiz, Request $request)
    {
        $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $quiz->update([
            'is_active' => true,
            'review_status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_notes' => $request->admin_notes,
        ]);

        $topicName = $quiz->topic->title ?? 'Topik';

        return back()->with('success', "Soal berhasil disetujui dan langsung aktif untuk siswa pada topik \"{$topicName}\"! ✅");
    }

    /**
     * Tolak butir soal AI (tidak dikirim ke siswa).
     */
    public function rejectQuiz(Quiz $quiz, Request $request)
    {
        $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $quiz->update([
            'is_active' => false,
            'review_status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_notes' => $request->admin_notes ?: 'Ditolak oleh pengajar.',
        ]);

        return back()->with('success', 'Butir soal AI ditolak dan tidak dikirimkan ke siswa. ❌');
    }

    /**
     * Setujui seluruh butir soal pending pada satu topik secara massal.
     */
    public function approveTopicQuizzes(Topic $topic, Request $request)
    {
        $count = Quiz::where('topic_id', $topic->id)
            ->where('review_status', 'pending')
            ->update([
                'is_active' => true,
                'review_status' => 'approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);

        return back()->with('success', "🎉 Seluruh ({$count}) butir soal pending pada topik \"{$topic->title}\" berhasil disetujui dan diterbitkan ke siswa!");
    }

    /**
     * Tolak seluruh butir soal pending pada satu topik secara massal.
     */
    public function rejectTopicQuizzes(Topic $topic, Request $request)
    {
        $count = Quiz::where('topic_id', $topic->id)
            ->where('review_status', 'pending')
            ->update([
                'is_active' => false,
                'review_status' => 'rejected',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'admin_notes' => $request->input('admin_notes', 'Ditolak massal per topik oleh pengajar.'),
            ]);

        return back()->with('success', "Seluruh ({$count}) butir soal pending pada topik \"{$topic->title}\" telah ditolak. ❌");
    }

    /**
     * Setujui rekomendasi diagnostik remedial siswa.
     */
    public function approve(AiRecommendation $aiRecommendation, Request $request)
    {
        $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $aiRecommendation->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'Rekomendasi remedial AI disetujui dan diteruskan ke siswa. ✅');
    }

    /**
     * Tolak rekomendasi diagnostik remedial siswa.
     */
    public function reject(AiRecommendation $aiRecommendation, Request $request)
    {
        $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $aiRecommendation->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'Rekomendasi remedial AI ditolak dan dicatat. ❌');
    }
}
