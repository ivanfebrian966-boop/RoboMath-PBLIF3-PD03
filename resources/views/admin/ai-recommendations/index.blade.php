@extends('layouts.admin')

@section('title', 'Rekomendasi Diagnostik AI - RoboMath')

@section('content')
<div class="space-y-6">

    <!-- Filter Status Tabs -->
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.ai-recommendations.index', ['status' => 'pending']) }}" class="px-4 py-2.5 rounded-2xl text-xs font-black transition {{ $statusFilter === 'pending' ? 'bg-gradient-to-r from-rose-500 to-pink-600 text-white shadow-md' : 'bg-white border-2 border-amber-200 text-slate-700 hover:bg-amber-50' }}">
                Menunggu Tinjauan ({{ $pendingCount }})
            </a>
            <a href="{{ route('admin.ai-recommendations.index', ['status' => 'approved']) }}" class="px-4 py-2.5 rounded-2xl text-xs font-black transition {{ $statusFilter === 'approved' ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-md' : 'bg-white border-2 border-amber-200 text-slate-700 hover:bg-amber-50' }}">
                Disetujui
            </a>
            <a href="{{ route('admin.ai-recommendations.index', ['status' => 'rejected']) }}" class="px-4 py-2.5 rounded-2xl text-xs font-black transition {{ $statusFilter === 'rejected' ? 'bg-slate-700 text-white shadow-md' : 'bg-white border-2 border-amber-200 text-slate-700 hover:bg-amber-50' }}">
                Ditolak
            </a>
            <a href="{{ route('admin.ai-recommendations.index', ['status' => 'all']) }}" class="px-4 py-2.5 rounded-2xl text-xs font-black transition {{ $statusFilter === 'all' ? 'bg-gradient-to-r from-orange-500 to-amber-500 text-white shadow-md' : 'bg-white border-2 border-amber-200 text-slate-700 hover:bg-amber-50' }}">
                Semua Status
            </a>
        </div>
    </div>

    <!-- Recommendations Table -->
    <div class="bg-white border-2 border-amber-200/80 rounded-3xl overflow-hidden shadow-sm">
        <div class="p-6 border-b-2 border-amber-100 flex items-center justify-between">
            <h3 class="font-black text-slate-800 text-base">Log Analisis Sistem Adaptif AI</h3>
            <span class="text-xs text-slate-500 font-extrabold bg-amber-50 border border-amber-200 px-3 py-1 rounded-full">{{ $recommendations->total() }} Log</span>
        </div>

        @if($recommendations->isEmpty())
        <div class="p-12 text-center text-slate-400 font-semibold text-sm">
            Tidak ada rekomendasi dalam status ini.
        </div>
        @else
        <div class="divide-y divide-amber-100">
            @foreach($recommendations as $rec)
            <div class="p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 hover:bg-amber-50/30 transition">
                <div class="space-y-3 max-w-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center font-black shadow-sm flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-sm">{{ $rec->student->name ?? 'Siswa' }}</h4>
                            <p class="text-xs text-slate-500 font-medium">Kelas {{ $rec->student->kelas ?? '-' }} SD • Topik: <strong class="text-amber-800">{{ $rec->topic->title ?? '-' }}</strong></p>
                        </div>
                    </div>

                    <div class="p-4 bg-rose-50 border-2 border-rose-200/80 rounded-2xl space-y-1">
                        <p class="text-xs text-rose-800 font-bold flex items-center gap-2">
                            <svg class="w-4 h-4 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span><strong>Pemicu Diagnostik AI:</strong> {{ $rec->reason }}</span>
                        </p>
                        @if($rec->quiz)
                        <p class="text-[11px] text-slate-600 mt-1 truncate">
                            Contoh soal terkait: "{!! strip_tags($rec->quiz->question) !!}"
                        </p>
                        @endif
                    </div>

                    @if($rec->admin_notes)
                    <p class="text-xs text-slate-500 italic">
                        Catatan Admin: "{{ $rec->admin_notes }}" (oleh {{ $rec->reviewer->name ?? 'Admin' }})
                    </p>
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row items-end sm:items-center gap-3 w-full md:w-auto">
                    @if($rec->status === 'pending')
                    <form action="{{ route('admin.ai-recommendations.approve', $rec) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-2xl text-xs font-black shadow-md transition transform active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Setujui Rekomendasi
                        </button>
                    </form>
                    <form action="{{ route('admin.ai-recommendations.reject', $rec) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-slate-100 hover:bg-rose-50 text-slate-700 hover:text-rose-600 border border-slate-200 rounded-2xl text-xs font-black transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            Tolak
                        </button>
                    </form>
                    @else
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-black uppercase {{ $rec->status === 'approved' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-slate-100 text-slate-600 border border-slate-300' }}">
                        Status: {{ $rec->status === 'approved' ? 'Disetujui' : 'Ditolak' }}
                    </span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <div class="p-5 border-t-2 border-amber-100">
            {{ $recommendations->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
