@extends('layouts.admin')

@section('page-title', 'Rekomendasi Diagnostik AI')
@section('page-subtitle', 'Review pola kesalahan siswa dan persetujuan tindak lanjut adaptif')

@section('content')
<div class="space-y-6">

    <!-- Filter Status Tabs -->
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.ai-recommendations.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl text-xs font-black transition {{ $statusFilter === 'pending' ? 'bg-rose-600 text-white shadow-md' : 'bg-slate-900 border border-slate-800 text-slate-400 hover:text-white' }}">
                Menunggu Review ({{ $pendingCount }})
            </a>
            <a href="{{ route('admin.ai-recommendations.index', ['status' => 'approved']) }}" class="px-4 py-2 rounded-xl text-xs font-black transition {{ $statusFilter === 'approved' ? 'bg-emerald-600 text-white shadow-md' : 'bg-slate-900 border border-slate-800 text-slate-400 hover:text-white' }}">
                Disetujui
            </a>
            <a href="{{ route('admin.ai-recommendations.index', ['status' => 'rejected']) }}" class="px-4 py-2 rounded-xl text-xs font-black transition {{ $statusFilter === 'rejected' ? 'bg-slate-700 text-white shadow-md' : 'bg-slate-900 border border-slate-800 text-slate-400 hover:text-white' }}">
                Ditolak
            </a>
            <a href="{{ route('admin.ai-recommendations.index', ['status' => 'all']) }}" class="px-4 py-2 rounded-xl text-xs font-black transition {{ $statusFilter === 'all' ? 'bg-amber-500 text-slate-950 shadow-md' : 'bg-slate-900 border border-slate-800 text-slate-400 hover:text-white' }}">
                Semua Status
            </a>
        </div>
    </div>

    <!-- Recommendations Table -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-slate-800 flex items-center justify-between">
            <h3 class="font-bold text-white text-sm">Log Analisis Sistem Adaptif AI</h3>
            <span class="text-xs text-slate-400 font-semibold">{{ $recommendations->total() }} Log</span>
        </div>

        @if($recommendations->isEmpty())
        <div class="p-12 text-center text-slate-500 text-sm">
            Tidak ada rekomendasi dalam status ini.
        </div>
        @else
        <div class="divide-y divide-slate-800">
            @foreach($recommendations as $rec)
            <div class="p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 hover:bg-slate-800/30 transition">
                <div class="space-y-2 max-w-xl">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-sm">
                            🤖
                        </span>
                        <div>
                            <h4 class="font-bold text-white text-sm">{{ $rec->student->name ?? 'Siswa' }}</h4>
                            <p class="text-xs text-slate-400">Kelas {{ $rec->student->kelas ?? '-' }} • Topik: <strong class="text-amber-400">{{ $rec->topic->title ?? '-' }}</strong></p>
                        </div>
                    </div>

                    <div class="p-3 bg-slate-950 border border-slate-800 rounded-xl">
                        <p class="text-xs text-rose-300 font-semibold">
                            ⚠️ <strong>Pemicu AI:</strong> {{ $rec->reason }}
                        </p>
                        @if($rec->quiz)
                        <p class="text-[11px] text-slate-400 mt-1 truncate">
                            Contoh soal: "{!! strip_tags($rec->quiz->question) !!}"
                        </p>
                        @endif
                    </div>

                    @if($rec->admin_notes)
                    <p class="text-xs text-slate-400">
                        Catatan Admin: <em>"{{ $rec->admin_notes }}"</em> (oleh {{ $rec->reviewer->name ?? 'Admin' }})
                    </p>
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row items-end sm:items-center gap-3 w-full md:w-auto">
                    @if($rec->status === 'pending')
                    <form action="{{ route('admin.ai-recommendations.approve', $rec) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-black shadow transition">
                            ✓ Setujui Rekomendasi
                        </button>
                    </form>
                    <form action="{{ route('admin.ai-recommendations.reject', $rec) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-rose-900/50 text-rose-400 rounded-xl text-xs font-black transition">
                            ✕ Tolak
                        </button>
                    </form>
                    @else
                    <span class="px-3 py-1 rounded-xl text-xs font-black uppercase {{ $rec->status === 'approved' ? 'bg-emerald-900/60 text-emerald-300' : 'bg-slate-800 text-slate-400' }}">
                        Status: {{ $rec->status }}
                    </span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <div class="p-5 border-t border-slate-800">
            {{ $recommendations->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
