<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiRecommendation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AiRecommendationController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->query('status', 'pending');

        $recommendations = AiRecommendation::with(['student', 'topic', 'quiz', 'reviewer'])
            ->when($statusFilter !== 'all', fn ($q) => $q->where('status', $statusFilter))
            ->latest()
            ->paginate(20);

        $pendingCount = AiRecommendation::where('status', 'pending')->count();

        return view('admin.ai-recommendations.index', compact('recommendations', 'statusFilter', 'pendingCount'));
    }

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

        return back()->with('success', 'Rekomendasi AI disetujui dan akan diteruskan ke siswa. ✅');
    }

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

        return back()->with('success', 'Rekomendasi AI ditolak dan dicatat. ❌');
    }
}
