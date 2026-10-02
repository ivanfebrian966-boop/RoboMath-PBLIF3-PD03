@extends('layouts.admin')

@section('title', 'Rekomendasi Diagnostik AI - RoboMath')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-rose-500 via-pink-600 to-purple-600 p-6 sm:p-7 text-white shadow-xl shadow-rose-500/10 border border-white/20">
        <div class="absolute -right-12 -top-12 w-56 h-56 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="space-y-2 max-w-xl">
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-white/20 backdrop-blur-md text-white border border-white/30 uppercase tracking-wider">
                        🤖 Sistem Adaptif AI
                    </span>
                    @if($pendingCount > 0)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-black bg-white text-rose-600 shadow-sm animate-pulse">
                            {{ $pendingCount }} Butuh Tinjauan
                        </span>
                    @endif
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white">
                    Rekomendasi Diagnostik Siswa
                </h1>
                <p class="text-rose-100 text-xs sm:text-sm font-medium leading-relaxed">
                    Sistem otomatis RoboMath menganalisis riwayat kuis siswa yang membutuhkan remedial, lalu mengajukan rekomendasi materi untuk diverifikasi Guru atau Admin.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/20 text-center min-w-[120px]">
                    <div class="text-2xl sm:text-3xl font-black text-white">{{ $pendingCount }}</div>
                    <div class="text-[11px] font-bold text-rose-100 uppercase tracking-wider mt-0.5">Pending</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/20 text-center min-w-[120px]">
                    <div class="text-2xl sm:text-3xl font-black text-white">{{ $recommendations->total() }}</div>
                    <div class="text-[11px] font-bold text-rose-100 uppercase tracking-wider mt-0.5">Total Log</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Status Tabs -->
    <div class="bg-amber-100/70 p-1.5 rounded-2xl border border-amber-200/80 shadow-inner flex flex-wrap gap-1.5">
        <a href="{{ route('admin.ai-recommendations.index', ['status' => 'pending']) }}"
           class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-black transition {{ $statusFilter === 'pending' ? 'bg-white text-rose-600 shadow-md border border-rose-100' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
            <span>⏳ Menunggu Tinjauan</span>
            <span class="px-2 py-0.5 text-[10px] rounded-full {{ $statusFilter === 'pending' ? 'bg-rose-100 text-rose-800' : 'bg-slate-200 text-slate-700' }}">{{ $pendingCount }}</span>
        </a>
        <a href="{{ route('admin.ai-recommendations.index', ['status' => 'approved']) }}"
           class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-black transition {{ $statusFilter === 'approved' ? 'bg-white text-emerald-700 shadow-md border border-emerald-100' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
            <span>✓ Disetujui</span>
        </a>
        <a href="{{ route('admin.ai-recommendations.index', ['status' => 'rejected']) }}"
           class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-black transition {{ $statusFilter === 'rejected' ? 'bg-white text-slate-800 shadow-md border border-slate-200' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
            <span>✕ Ditolak</span>
        </a>
        <a href="{{ route('admin.ai-recommendations.index', ['status' => 'all']) }}"
           class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-black transition {{ $statusFilter === 'all' ? 'bg-white text-indigo-700 shadow-md border border-indigo-100' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
            <span>📋 Semua Status</span>
        </a>
    </div>

    <!-- Recommendations Container -->
    <div class="bg-white border-2 border-amber-200/80 rounded-3xl overflow-hidden shadow-sm">
        <div class="p-5 sm:p-6 border-b border-amber-100 flex items-center justify-between">
            <h3 class="font-black text-slate-800 text-base flex items-center gap-2">
                <span>📑</span> Log Diagnostik AI Adaptif
            </h3>
            <span class="text-xs text-slate-500 font-extrabold bg-amber-50 border border-amber-200 px-3 py-1 rounded-full">
                {{ $recommendations->total() }} Catatan
            </span>
        </div>

        @if($recommendations->isEmpty())
        <div class="p-16 text-center space-y-3">
            <div class="w-16 h-16 rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-center text-3xl mx-auto">
                🎉
            </div>
            <h4 class="text-base font-black text-slate-800">Tidak Ada Rekomendasi</h4>
            <p class="text-xs font-semibold text-slate-400 max-w-sm mx-auto">
                Saat ini tidak ada rekomendasi sistem adaptif dalam status <strong>"{{ $statusFilter }}"</strong>.
            </p>
        </div>
        @else
        <div class="divide-y divide-amber-100">
            @foreach($recommendations as $rec)
            <div class="p-5 sm:p-6 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-5 hover:bg-amber-50/30 transition">
                <div class="space-y-3 flex-1 max-w-3xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center font-black shadow-sm flex-shrink-0 text-sm">
                            {{ strtoupper(substr($rec->student->name ?? 'S', 0, 1)) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h4 class="font-extrabold text-slate-900 text-sm sm:text-base">{{ $rec->student->name ?? 'Siswa' }}</h4>
                                <span class="bg-indigo-50 text-indigo-700 border border-indigo-200 text-[10px] font-black px-2 py-0.5 rounded-md">
                                    Kelas {{ $rec->student->kelas ?? '-' }} SD
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">
                                Topik Remedial: <strong class="text-amber-900">{{ $rec->topic->title ?? '-' }}</strong>
                            </p>
                        </div>
                    </div>

                    <!-- Reason Trigger Box -->
                    <div class="p-4 bg-rose-50/70 border border-rose-200 rounded-2xl space-y-1.5">
                        <p class="text-xs text-rose-900 font-bold flex items-start gap-2">
                            <svg class="w-4 h-4 text-rose-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span><strong>Pemicu Diagnostik AI:</strong> {{ $rec->reason }}</span>
                        </p>
                        @if($rec->quiz)
                        <p class="text-[11px] text-slate-600 pl-6 leading-relaxed">
                            Contoh butir soal terkait: <span class="italic text-slate-800">"{!! strip_tags($rec->quiz->question) !!}"</span>
                        </p>
                        @endif
                    </div>

                    @if($rec->admin_notes)
                    <div class="text-xs text-slate-600 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 flex items-center gap-2">
                        <span class="font-black text-slate-500">Catatan Review:</span>
                        <span class="italic">"{{ $rec->admin_notes }}"</span>
                        <span class="text-[10px] text-slate-400 ml-auto">oleh {{ $rec->reviewer->name ?? 'Admin' }}</span>
                    </div>
                    @endif
                </div>

                <!-- Action Buttons or Status Badge -->
                <div class="flex items-center gap-2.5 w-full lg:w-auto justify-end pt-2 lg:pt-0">
                    @if($rec->status === 'pending')
                    <form action="{{ route('admin.ai-recommendations.approve', $rec) }}" method="POST" id="form-approve-{{ $rec->id }}">
                        @csrf
                        <button type="button" onclick="confirmAction('approve', {{ $rec->id }})"
                            class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-xl text-xs font-black shadow-md transition transform active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span>Setujui</span>
                        </button>
                    </form>

                    <form action="{{ route('admin.ai-recommendations.reject', $rec) }}" method="POST" id="form-reject-{{ $rec->id }}">
                        @csrf
                        <button type="button" onclick="confirmAction('reject', {{ $rec->id }})"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-slate-100 hover:bg-rose-50 text-slate-700 hover:text-rose-600 border border-slate-200 hover:border-rose-200 rounded-xl text-xs font-black transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span>Tolak</span>
                        </button>
                    </form>
                    @else
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-black uppercase tracking-wider {{ $rec->status === 'approved' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-slate-100 text-slate-600 border border-slate-300' }}">
                        <span>{{ $rec->status === 'approved' ? '✓' : '✕' }}</span>
                        <span>{{ $rec->status === 'approved' ? 'Disetujui' : 'Ditolak' }}</span>
                    </span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <div class="p-4 border-t border-amber-100 bg-amber-50/20">
            {{ $recommendations->links() }}
        </div>
        @endif
    </div>

</div>

@push('scripts')
<script>
    function confirmAction(type, recId) {
        const isApprove = (type === 'approve');
        Swal.fire({
            title: isApprove ? 'Setujui Rekomendasi AI?' : 'Tolak Rekomendasi AI?',
            text: isApprove ? 'Siswa akan diarahkan untuk mempelajari modul remedial ini.' : 'Rekomendasi ini akan diabaikan dari daftar tinjauan.',
            icon: isApprove ? 'question' : 'warning',
            showCancelButton: true,
            confirmButtonColor: isApprove ? '#10B981' : '#E11D48',
            cancelButtonColor: '#94A3B8',
            confirmButtonText: isApprove ? 'Ya, Setujui' : 'Ya, Tolak',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`form-${type}-${recId}`).submit();
            }
        });
    }
</script>
@endpush
@endsection
