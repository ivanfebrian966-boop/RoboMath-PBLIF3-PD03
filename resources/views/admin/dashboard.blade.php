@extends('layouts.admin')

@section('title', 'Dasbor Administrator - RoboMath')

@section('content')
<div class="space-y-8">

    <!-- Hero Banner Admin (Sesuai dengan gaya banner aktor lain seperti Guru) -->
    <div class="bg-[#FF9500] rounded-3xl p-8 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-2">
            <span class="bg-white/20 text-white font-extrabold text-xs px-3 py-1 rounded-full uppercase tracking-wider">PANEL ADMINISTRATOR ROBOMATH</span>
            <h1 class="text-3xl sm:text-4xl font-black">Ringkasan Dasbor Admin</h1>
            <p class="text-amber-100 font-semibold text-sm max-w-xl">Pantau metrik ekosistem, kelola konten kurikulum matematika SD, dan tinjau diagnostik adaptif AI.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.quizzes.create') }}" class="inline-flex items-center gap-2 bg-white text-amber-900 font-black px-5 py-3 rounded-2xl text-xs shadow-md hover:bg-amber-50 transition transform active:scale-95">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Soal Baru
            </a>
            <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center gap-2 bg-indigo-700/80 hover:bg-indigo-700 text-white font-black px-5 py-3 rounded-2xl text-xs shadow-md transition transform active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Laporan Lengkap
            </a>
        </div>
    </div>

    <!-- Kartu Metrik KPI -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white border-2 border-amber-200/80 rounded-3xl p-6 shadow-sm space-y-1">
            <div class="flex items-center justify-between">
                <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Total Siswa</span>
                <div class="w-10 h-10 rounded-2xl bg-amber-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black text-slate-900 mt-2">{{ $totalSiswa }}</div>
            <p class="text-xs text-slate-500">Siswa terdaftar aktif</p>
        </div>

        <div class="bg-white border-2 border-amber-200/80 rounded-3xl p-6 shadow-sm space-y-1">
            <div class="flex items-center justify-between">
                <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Bank Soal</span>
                <div class="w-10 h-10 rounded-2xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black text-blue-600 mt-2">{{ $totalSoal }}</div>
            <p class="text-xs text-slate-500">Soal latihan aktif</p>
        </div>

        <div class="bg-white border-2 border-amber-200/80 rounded-3xl p-6 shadow-sm space-y-1">
            <div class="flex items-center justify-between">
                <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Modul Materi</span>
                <div class="w-10 h-10 rounded-2xl bg-emerald-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black text-emerald-600 mt-2">{{ $totalMateri }}</div>
            <p class="text-xs text-slate-500">Unit materi kurikulum SD</p>
        </div>

        <div class="bg-white border-2 border-amber-200/80 rounded-3xl p-6 shadow-sm space-y-1">
            <div class="flex items-center justify-between">
                <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Akurasi Rata-rata</span>
                <div class="w-10 h-10 rounded-2xl bg-indigo-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black text-indigo-600 mt-2">{{ $overallAccuracy }}%</div>
            <p class="text-xs text-slate-500">{{ number_format($totalAttempts) }} total percobaan latihan</p>
        </div>
    </div>

    <!-- Baris KPI Sekunder -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white border-2 border-amber-200/80 rounded-3xl p-6 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-black text-slate-800">Rekomendasi AI Menunggu Tinjauan</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Siswa yang terdeteksi memerlukan remedial pada materi tertentu.</p>
                </div>
            </div>
            <div class="text-right">
                <span class="text-2xl font-black text-rose-600">{{ $pendingAiRecommendations }}</span>
                <div class="mt-1">
                    <a href="{{ route('admin.ai-recommendations.index') }}" class="text-xs font-bold text-rose-600 hover:underline">
                        Tinjau &rarr;
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white border-2 border-amber-200/80 rounded-3xl p-6 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-black text-slate-800">Total Guru & Status Pengguna</h3>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $totalGuru }} Guru Terdaftar • {{ $inactiveUsers }} Akun Nonaktif</p>
                </div>
            </div>
            <div>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-[#FF9500] hover:bg-[#e08600] text-white rounded-xl text-xs font-black shadow transition">
                    Kelola Pengguna &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- Dua tabel kolom: Peringkat Siswa & Log AI Terkini -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Peringkat 5 Siswa Teratas -->
        <div class="bg-white border-2 border-amber-200/80 rounded-3xl overflow-hidden shadow-sm">
            <div class="p-5 border-b-2 border-amber-100 flex items-center justify-between">
                <h3 class="font-black text-slate-900 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                    5 Siswa Berprestasi
                </h3>
                <a href="{{ route('admin.reports.index') }}" class="text-xs text-amber-600 hover:underline font-bold">Laporan Lengkap &rarr;</a>
            </div>
            <div class="divide-y divide-amber-100">
                @forelse($topSiswa as $index => $siswa)
                <div class="p-4 flex items-center justify-between hover:bg-amber-50/40 transition">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-lg flex items-center justify-center font-black text-xs {{ $index == 0 ? 'bg-amber-400 text-amber-950 font-black' : 'bg-slate-100 text-slate-700' }}">
                            {{ $index + 1 }}
                        </span>
                        <div>
                            <div class="text-sm font-bold text-slate-800">{{ $siswa->name }}</div>
                            <div class="text-xs text-slate-500">Kelas {{ $siswa->kelas ?? '-' }} • Level {{ $siswa->level ?? 1 }}</div>
                        </div>
                    </div>
                    <div class="text-amber-800 font-black text-xs bg-amber-100 px-2.5 py-1 rounded-lg">
                        {{ number_format($siswa->total_score) }} Poin
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-400 text-xs">Belum ada siswa terdaftar.</div>
                @endforelse
            </div>
        </div>

        <!-- Log Rekomendasi AI Terkini -->
        <div class="bg-white border-2 border-amber-200/80 rounded-3xl overflow-hidden shadow-sm">
            <div class="p-5 border-b-2 border-amber-100 flex items-center justify-between">
                <h3 class="font-black text-slate-900 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    Log Diagnostik AI
                </h3>
                <a href="{{ route('admin.ai-recommendations.index') }}" class="text-xs text-rose-600 hover:underline font-bold">Semua Rekomendasi &rarr;</a>
            </div>
            <div class="divide-y divide-amber-100">
                @forelse($recentRecommendations as $rec)
                <div class="p-4 flex items-center justify-between gap-4 hover:bg-rose-50/30 transition">
                    <div>
                        <div class="text-sm font-bold text-slate-800">{{ $rec->student->name ?? 'Siswa' }}</div>
                        <div class="text-xs text-rose-600 font-semibold mt-0.5">{{ $rec->reason }}</div>
                        <div class="text-[11px] text-slate-500">Topik: {{ $rec->topic->title ?? '-' }}</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <form action="{{ route('admin.ai-recommendations.approve', $rec) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-black shadow-sm transition">
                                Setujui
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-400 text-xs">Semua siswa lancar, tidak ada rekomendasi remedial pending.</div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
