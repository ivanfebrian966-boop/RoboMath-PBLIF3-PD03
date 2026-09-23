@extends('layouts.admin')

@section('page-title', 'Dashboard Overview')
@section('page-subtitle', 'Statistik dan ringkasan ekosistem RoboMath')

@section('content')
<div class="space-y-8">

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-black text-slate-400 uppercase tracking-wider">Total Siswa</span>
                <span class="text-2xl">🎒</span>
            </div>
            <div class="text-3xl font-black text-white mt-2">{{ $totalSiswa }}</div>
            <p class="text-xs text-slate-500 mt-1">Siswa terdaftar aktif</p>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-black text-slate-400 uppercase tracking-wider">Bank Soal</span>
                <span class="text-2xl">🎯</span>
            </div>
            <div class="text-3xl font-black text-amber-400 mt-2">{{ $totalSoal }}</div>
            <p class="text-xs text-slate-500 mt-1">Soal latihan aktif</p>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-black text-slate-400 uppercase tracking-wider">Modul Materi</span>
                <span class="text-2xl">📚</span>
            </div>
            <div class="text-3xl font-black text-emerald-400 mt-2">{{ $totalMateri }}</div>
            <p class="text-xs text-slate-500 mt-1">Unit materi kurikulum SD</p>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-black text-slate-400 uppercase tracking-wider">Akurasi Rata-rata</span>
                <span class="text-2xl">📈</span>
            </div>
            <div class="text-3xl font-black text-indigo-400 mt-2">{{ $overallAccuracy }}%</div>
            <p class="text-xs text-slate-500 mt-1">{{ number_format($totalAttempts) }} total percobaan latihan</p>
        </div>
    </div>

    <!-- Secondary KPI Alert Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-500/20 text-rose-400 flex items-center justify-center text-2xl font-black">
                    🤖
                </div>
                <div>
                    <h3 class="text-sm font-black text-white">Rekomendasi Adaptif AI Menunggu Review</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Siswa yang terdeteksi kesulitan pada topik kuis tertentu.</p>
                </div>
            </div>
            <div class="text-right">
                <span class="text-2xl font-black text-rose-400">{{ $pendingAiRecommendations }}</span>
                <div class="mt-1">
                    <a href="{{ route('admin.ai-recommendations.index') }}" class="text-xs font-bold text-amber-400 hover:underline">
                        Review &rarr;
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-2xl font-black">
                    👥
                </div>
                <div>
                    <h3 class="text-sm font-black text-white">Total Guru & Status Pengguna</h3>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $totalGuru }} Guru Terdaftar • {{ $inactiveUsers }} Pengguna Nonaktif</p>
                </div>
            </div>
            <div>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-xs font-bold transition">
                    Kelola Pengguna &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- Two-column tables: Top Siswa & Recent AI Alerts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Top 5 Siswa Teratas -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-sm">
            <div class="p-5 border-b border-slate-800 flex items-center justify-between">
                <h3 class="font-black text-white text-sm flex items-center gap-2">
                    <span>🏆</span> Top 5 Siswa Berprestasi
                </h3>
                <a href="{{ route('admin.reports.index') }}" class="text-xs text-amber-400 hover:underline font-bold">Laporan Lengkap &rarr;</a>
            </div>
            <div class="divide-y divide-slate-800">
                @forelse($topSiswa as $index => $siswa)
                <div class="p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-lg flex items-center justify-center font-black text-xs {{ $index == 0 ? 'bg-amber-500 text-slate-950 font-black' : 'bg-slate-800 text-slate-300' }}">
                            {{ $index + 1 }}
                        </span>
                        <div>
                            <div class="text-sm font-bold text-white">{{ $siswa->name }}</div>
                            <div class="text-xs text-slate-500">Kelas {{ $siswa->kelas ?? '-' }} • Level {{ $siswa->level ?? 1 }}</div>
                        </div>
                    </div>
                    <div class="text-amber-400 font-black text-xs bg-amber-500/10 px-2.5 py-1 rounded-lg">
                        ⭐ {{ number_format($siswa->total_score) }} Poin
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-500 text-xs">Belum ada siswa terdaftar.</div>
                @endforelse
            </div>
        </div>

        <!-- Rekomendasi AI Menunggu Persetujuan -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-sm">
            <div class="p-5 border-b border-slate-800 flex items-center justify-between">
                <h3 class="font-black text-white text-sm flex items-center gap-2">
                    <span>🤖</span> AI Diagnostic Logs
                </h3>
                <a href="{{ route('admin.ai-recommendations.index') }}" class="text-xs text-rose-400 hover:underline font-bold">Semua Rekomendasi &rarr;</a>
            </div>
            <div class="divide-y divide-slate-800">
                @forelse($recentRecommendations as $rec)
                <div class="p-4 flex items-center justify-between gap-4">
                    <div>
                        <div class="text-sm font-bold text-white">{{ $rec->student->name ?? 'Siswa' }}</div>
                        <div class="text-xs text-rose-400 font-semibold mt-0.5">{{ $rec->reason }}</div>
                        <div class="text-[11px] text-slate-500">Topik: {{ $rec->topic->title ?? '-' }}</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <form action="{{ route('admin.ai-recommendations.approve', $rec) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold transition">
                                Setujui
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-500 text-xs">Semua siswa lancar, tidak ada rekomendasi remedial pending.</div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
