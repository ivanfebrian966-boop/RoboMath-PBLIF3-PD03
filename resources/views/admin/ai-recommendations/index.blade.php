@extends('layouts.admin')

@section('title', 'Verifikasi Soal AI - RoboMath')

@section('content')
<style>
    .review-ai-page,
    .review-ai-page *,
    .review-ai-page button,
    .review-ai-page input,
    .review-ai-page select,
    .review-ai-page textarea {
        font-family: 'Poppins', sans-serif !important;
    }
</style>

<div class="review-ai-page space-y-5 sm:space-y-6">

    {{-- ============================= --}}
    {{-- 1. HEADER BANNER              --}}
    {{-- ============================= --}}
    <div class="rounded-[28px] bg-[#1A3C9C] p-6 sm:p-8 text-white shadow-md flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">
                Verifikasi Soal AI &amp; Rekomendasi
            </h1>
            <p class="text-blue-200 text-xs sm:text-sm font-normal mt-2 max-w-2xl leading-relaxed">
                Tinjau jawaban dan pembahasan buatan AI, lalu setujui sebelum soal tampil ke siswa.
            </p>
        </div>
        <div class="flex items-center gap-3 flex-shrink-0">
            <div class="bg-[#DC2626] text-white rounded-2xl px-5 py-3 text-center shadow">
                <div class="text-2xl font-black leading-none">{{ $pendingQuizzesCount }}</div>
                <div class="text-[10px] font-bold uppercase tracking-wider mt-1 text-red-100">Menunggu Review</div>
            </div>
            <div class="bg-[#056B48] text-white rounded-2xl px-5 py-3 text-center shadow">
                <div class="text-2xl font-black leading-none">{{ $approvedQuizzesCount }}</div>
                <div class="text-[10px] font-bold uppercase tracking-wider mt-1 text-emerald-100">Disetujui</div>
            </div>
        </div>
    </div>

    {{-- ============================= --}}
    {{-- 2. DUA KOLOM UTAMA            --}}
    {{-- ============================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        {{-- ========================= --}}
        {{-- KOLOM KIRI: MATERI        --}}
        {{-- ========================= --}}
        <div class="lg:col-span-4 space-y-4">
            <h2 class="text-base font-bold text-slate-800">Materi menunggu
                <span class="ml-2 px-2 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                    {{ $pendingTopics->count() }} Materi
                </span>
            </h2>

            @forelse($pendingTopics as $topic)
            @php
                $topicStats      = $quizStatsByTopic[$topic->id] ?? ['pending' => 0, 'approved' => 0, 'rejected' => 0];
                $pendingInTopic  = $topicStats['pending'];
                $approvedInTopic = $topicStats['approved'];
                $rejectedInTopic = $topicStats['rejected'];
                $aiSummary       = $topic->documentSummary?->ai_summary
                                    ?? 'Ringkasan AI: materi ini sedang menunggu verifikasi jawaban dan pembahasan soal.';
            @endphp
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-3">
                {{-- Topic Title --}}
                <div>
                    <p class="font-bold text-slate-900 text-sm">{{ $topic->title }}</p>
                    <span class="inline-block mt-1 px-2.5 py-0.5 bg-blue-100 text-blue-700 text-[11px] font-bold rounded-full">
                        Kelas {{ $topic->kelas_level }} SD
                    </span>
                </div>

                {{-- AI Summary --}}
                <p class="text-xs text-slate-500 leading-relaxed line-clamp-3">
                    {{ Str::limit($aiSummary, 160) }}
                </p>

                {{-- Tombol Setujui Materi / Tolak --}}
                <div class="flex items-center gap-2 pt-1">
                    @if($topic->review_status !== 'approved')
                    <form action="{{ route('admin.ai-recommendations.topics.approve', $topic) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-[#056B48] hover:bg-[#044d34] text-white text-xs font-bold rounded-xl transition">
                            Setujui materi
                        </button>
                    </form>
                    @else
                    <span class="px-4 py-2 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-xl">
                        ✓ Disetujui
                    </span>
                    @endif

                    <form action="{{ route('admin.ai-recommendations.topics.reject', $topic) }}" method="POST"
                          onsubmit="return confirm('Tolak topik ini dan semua soal pending-nya?');">
                        @csrf
                        <button type="submit" class="px-4 py-2 border border-slate-300 text-slate-600 text-xs font-bold rounded-xl hover:bg-slate-50 transition">
                            Tolak
                        </button>
                    </form>
                </div>

                {{-- Stats Menunggu / Disetujui / Ditolak --}}
                <div class="flex items-center gap-4 text-xs text-slate-500 pt-1 border-t border-slate-100">
                    <span>Menunggu <strong class="text-slate-800">{{ $pendingInTopic }}</strong></span>
                    <span>Disetujui <strong class="text-slate-800">{{ $approvedInTopic }}</strong></span>
                    <span>Ditolak <strong class="text-slate-800">{{ $rejectedInTopic }}</strong></span>
                </div>

                {{-- Amber bulk box jika ada soal pending --}}
                @if($pendingInTopic > 0)
                <div class="bg-[#FFF8EC] border border-amber-200 rounded-xl p-4 space-y-2">
                    <p class="text-xs font-bold text-slate-800">{{ $pendingInTopic }} soal menunggu persetujuan</p>
                    <p class="text-[11px] text-slate-500">Periksa jawaban dan pembahasan sebelum disetujui.</p>
                    <div class="flex items-center gap-2 pt-1">
                        <form action="{{ route('admin.ai-recommendations.topics.approve-all', $topic) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-[#056B48] hover:bg-[#044d34] text-white text-[11px] font-bold rounded-xl transition">
                                Setujui semua
                            </button>
                        </form>
                        <form action="{{ route('admin.ai-recommendations.topics.reject-all', $topic) }}" method="POST"
                              onsubmit="return confirm('Tolak semua soal pending untuk topik ini?');">
                            @csrf
                            <button type="submit" class="px-4 py-2 border border-slate-300 text-slate-600 text-[11px] font-bold rounded-xl hover:bg-slate-50 transition">
                                Tolak semua
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>
            @empty
            <div class="bg-white border border-slate-200 rounded-2xl p-8 text-center text-slate-400 shadow-sm">
                <div class="text-4xl mb-2">🎉</div>
                <p class="text-sm font-bold text-slate-700">Semua materi sudah ditinjau!</p>
                <p class="text-xs mt-1">Tidak ada materi yang menunggu persetujuan.</p>
            </div>
            @endforelse
        </div>

        {{-- ========================= --}}
        {{-- KOLOM KANAN: BANK SOAL   --}}
        {{-- ========================= --}}
        <div class="lg:col-span-8 space-y-4">
            <div class="flex items-center justify-between flex-wrap gap-2">
                <h2 class="text-base font-bold text-slate-800">Daftar bank soal AI</h2>
                <span class="text-xs text-slate-400 font-medium">
                    Menampilkan {{ $quizzes->firstItem() }}–{{ $quizzes->lastItem() }} dari {{ $quizzes->total() }}
                </span>
            </div>

            {{-- Filter Pills --}}
            <div class="flex items-center gap-2 flex-wrap text-xs">
                <a href="{{ route('admin.ai-recommendations.index', ['status' => 'pending']) }}"
                   class="px-3.5 py-1.5 rounded-full font-bold transition {{ $statusFilter === 'pending' ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Menunggu
                </a>
                <a href="{{ route('admin.ai-recommendations.index', ['status' => 'approved']) }}"
                   class="px-3.5 py-1.5 rounded-full font-bold transition {{ $statusFilter === 'approved' ? 'bg-[#056B48] text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Disetujui
                </a>
                <a href="{{ route('admin.ai-recommendations.index', ['status' => 'rejected']) }}"
                   class="px-3.5 py-1.5 rounded-full font-bold transition {{ $statusFilter === 'rejected' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Ditolak
                </a>
                <a href="{{ route('admin.ai-recommendations.index', ['status' => 'all']) }}"
                   class="px-3.5 py-1.5 rounded-full font-bold transition {{ $statusFilter === 'all' ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua
                </a>

                {{-- Topic filter --}}
                @foreach($topics->take(5) as $t)
                <a href="{{ route('admin.ai-recommendations.index', ['status' => $statusFilter, 'topic_id' => $t->id]) }}"
                   class="px-3.5 py-1.5 rounded-full font-bold transition border {{ $topicFilter == $t->id ? 'bg-[#1A3C9C] text-white border-transparent' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
                    {{ $t->title }}
                </a>
                @endforeach
            </div>

            {{-- Quiz Cards Grid --}}
            @if($quizzes->isEmpty())
            <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center text-slate-400 shadow-sm">
                <div class="text-4xl mb-2">📋</div>
                <p class="text-sm font-bold text-slate-700">Tidak ada soal ditemukan.</p>
                <p class="text-xs mt-1">Pilih filter lain untuk melihat soal.</p>
            </div>
            @else
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($quizzes as $i => $quiz)
                @php
                    $opts = [];
                    if (is_array($quiz->options)) {
                        $keys = ['A', 'B', 'C', 'D'];
                        $j = 0;
                        foreach ($quiz->options as $k => $val) {
                            $key  = (is_string($k) && strlen($k) === 1 && ctype_alpha($k)) ? strtoupper($k) : ($keys[$j] ?? chr(65 + $j));
                            $text = is_array($val) ? ($val['text'] ?? $val['label'] ?? json_encode($val)) : (string) $val;
                            $opts[] = ['key' => $key, 'text' => $text];
                            $j++;
                        }
                    }
                    $correct     = strtoupper(trim($quiz->correct_answer ?? 'A'));
                    $statusColor = match($quiz->review_status) {
                        'approved' => 'bg-emerald-100 text-emerald-700',
                        'rejected' => 'bg-rose-100 text-rose-700',
                        default    => 'bg-amber-100 text-amber-700',
                    };
                    $statusLabel = match($quiz->review_status) {
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default    => 'Menunggu',
                    };
                @endphp
                <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm space-y-3 flex flex-col">

                    {{-- Card Header: badges + status --}}
                    <div class="flex items-center flex-wrap gap-1.5">
                        <span class="px-2.5 py-0.5 bg-blue-100 text-blue-700 text-[11px] font-bold rounded-full">
                            Soal {{ $quizzes->firstItem() + $i }}
                        </span>
                        <span class="px-2.5 py-0.5 bg-slate-100 text-slate-600 text-[11px] font-bold rounded-full">
                            {{ $quiz->type === 'essay' ? 'Uraian' : 'Pilihan ganda' }}
                        </span>
                        <span class="ml-auto px-2.5 py-0.5 text-[11px] font-bold rounded-full {{ $statusColor }}">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    {{-- Question --}}
                    <p class="text-sm font-bold text-slate-800 leading-relaxed">
                        {{ $quiz->question }}
                    </p>

                    {{-- Options grid 2x2 --}}
                    @if(!empty($opts))
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($opts as $opt)
                        <div class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-medium
                            {{ $opt['key'] === $correct
                                ? 'bg-[#E1F8EC] border border-[#059669] text-[#056B48] font-bold'
                                : 'bg-slate-50 border border-slate-100 text-slate-700' }}">
                            <span class="font-bold flex-shrink-0">{{ $opt['key'] }}.</span>
                            <span class="line-clamp-2">{{ $opt['text'] }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    {{-- Explanation --}}
                    @if($quiz->explanation)
                    <div class="rounded-xl bg-[#E1F8EC] border border-emerald-200 px-3 py-2 text-[11px] text-slate-700 leading-relaxed">
                        <strong class="text-[#056B48]">Jawaban {{ $correct }}.</strong>
                        {{ Str::limit($quiz->explanation, 120) }}
                    </div>
                    @endif

                    {{-- Actions --}}
                    <div class="flex items-center justify-end gap-2 pt-1 mt-auto">
                        <form action="{{ route('admin.ai-recommendations.quizzes.reject', $quiz) }}" method="POST"
                              onsubmit="return confirm('Tolak soal ini?');">
                            @csrf
                            <button type="submit"
                                    class="px-4 py-2 border border-rose-200 text-rose-600 text-xs font-bold rounded-xl hover:bg-rose-50 transition">
                                Tolak
                            </button>
                        </form>
                        <form action="{{ route('admin.ai-recommendations.quizzes.approve', $quiz) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="px-4 py-2 bg-[#056B48] hover:bg-[#044d34] text-white text-xs font-bold rounded-xl transition">
                                Setujui
                            </button>
                        </form>
                    </div>

                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="pt-2 text-xs">
                {{ $quizzes->withQueryString()->links() }}
            </div>
            @endif

        </div>{{-- /kolom kanan --}}

    </div>{{-- /grid utama --}}

</div>{{-- /review-ai-page --}}
@endsection
