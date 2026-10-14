@extends('layouts.admin')

@section('title', 'Dashboard Admin - RoboMath')

@section('content')
<div class="space-y-8">

    <!-- Hero Banner Admin -->
    <div class="bg-[#FF9500] rounded-3xl p-8 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-6 border-2 border-amber-300/40">
        <div class="space-y-2">
            <span class="bg-white/20 text-white font-extrabold text-xs px-3 py-1 rounded-full uppercase tracking-wider">PANEL ADMINISTRATOR ROBOMATH</span>
            <h1 class="text-3xl sm:text-4xl font-black">Dashboard & Laporan Performa</h1>
            <p class="text-amber-100 font-semibold text-sm max-w-xl">Ringkasan menyeluruh kesehatan sistem, statistik pengguna, diagnostik AI, serta laporan performa siswa SD.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.reports.export') }}" class="inline-flex items-center gap-2 bg-white text-amber-900 font-black px-5 py-3 rounded-2xl text-xs shadow-md hover:bg-amber-50 transition transform active:scale-95">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>Unduh Laporan Excel (.xlsx)</span>
            </a>
            <a href="{{ route('admin.document-summarizer.index') }}" class="inline-flex items-center gap-2 bg-indigo-900/40 hover:bg-indigo-900/60 text-white font-black px-5 py-3 rounded-2xl text-xs shadow-md transition transform active:scale-95 border border-white/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Kelola Materi AI &rarr;
            </a>
        </div>
    </div>

    <!-- 1. RINGKASAN SISTEM (KPI) -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-black text-slate-800 flex items-center gap-2">
                <span>📊</span> Ringkasan Sistem RoboMath
            </h2>
            <span class="text-xs text-slate-500 font-bold">Pembaruan Waktu Nyata</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white border-2 border-amber-200/80 rounded-3xl p-6 shadow-sm space-y-1">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Total Siswa</span>
                    <div class="w-10 h-10 rounded-2xl bg-amber-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                </div>
                <div class="text-3xl font-black text-slate-900 mt-2">{{ $totalSiswa }}</div>
                <p class="text-xs text-slate-500">Siswa aktif belajar</p>
            </div>

            <div class="bg-white border-2 border-amber-200/80 rounded-3xl p-6 shadow-sm space-y-1">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Total Guru</span>
                    <div class="w-10 h-10 rounded-2xl bg-indigo-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                </div>
                <div class="text-3xl font-black text-indigo-600 mt-2">{{ $totalGuru }}</div>
                <p class="text-xs text-slate-500">Pendidik pengampu kelas</p>
            </div>

            <div class="bg-white border-2 border-amber-200/80 rounded-3xl p-6 shadow-sm space-y-1">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Modul & Bank Soal</span>
                    <div class="w-10 h-10 rounded-2xl bg-emerald-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                </div>
                <div class="text-3xl font-black text-emerald-600 mt-2">{{ $totalMateri }} <span class="text-sm font-semibold text-slate-400">/ {{ $totalSoal }} Soal</span></div>
                <p class="text-xs text-slate-500">Unit materi & butir soal kuis</p>
            </div>

            <div class="bg-white border-2 border-amber-200/80 rounded-3xl p-6 shadow-sm space-y-1">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Akurasi Rata-rata</span>
                    <div class="w-10 h-10 rounded-2xl bg-blue-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                </div>
                <div class="text-3xl font-black text-blue-600 mt-2">{{ $overallAccuracy }}%</div>
                <p class="text-xs text-slate-500">{{ number_format($totalAttempts) }} total percobaan latihan</p>
            </div>
        </div>
    </div>

    <!-- 2. STATISTIK PENGGUNA & DISTRIBUSI -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Distribusi Siswa per Tingkatan SD -->
        <div class="bg-white border-2 border-amber-200/80 rounded-3xl p-6 shadow-sm space-y-4">
            <h3 class="font-black text-slate-800 text-sm flex items-center gap-2">
                <span>🏫</span> Statistik Pengguna per Kelas SD
            </h3>
            <div class="space-y-3">
                @forelse($classDistribution as $row)
                <div>
                    <div class="flex justify-between text-xs font-bold text-slate-700 mb-1">
                        <span>Kelas {{ $row->kelas ?? '-' }} SD</span>
                        <span class="text-amber-800 font-extrabold">{{ $row->total }} Siswa • Rata-rata {{ round($row->avg_score) }} Poin</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-[#FF9500] h-full rounded-full" style="width: {{ $totalSiswa > 0 ? min(100, round(($row->total / $totalSiswa) * 100)) : 0 }}%"></div>
                    </div>
                </div>
                @empty
                <p class="text-xs text-slate-400">Belum ada data distribusi kelas.</p>
                @endforelse
            </div>
        </div>

        <!-- 5 Siswa Berprestasi -->
        <div class="bg-white border-2 border-amber-200/80 rounded-3xl overflow-hidden shadow-sm">
            <div class="p-5 border-b-2 border-amber-100 flex items-center justify-between">
                <h3 class="font-black text-slate-900 text-sm flex items-center gap-2">
                    <span>🏆</span> 5 Siswa Berprestasi
                </h3>
            </div>
            <div class="divide-y divide-amber-100">
                @forelse($topSiswa as $index => $siswa)
                <div class="p-3.5 flex items-center justify-between hover:bg-amber-50/40 transition">
                    <div class="flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-lg flex items-center justify-center font-black text-xs {{ $index == 0 ? 'bg-amber-400 text-amber-950 font-black' : 'bg-slate-100 text-slate-700' }}">
                            {{ $index + 1 }}
                        </span>
                        <div>
                            <div class="text-xs font-bold text-slate-800">{{ $siswa->name }}</div>
                            <div class="text-[10px] text-slate-400">Kelas {{ $siswa->kelas ?? '-' }} SD</div>
                        </div>
                    </div>
                    <div class="text-amber-800 font-black text-xs bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-md">
                        {{ number_format($siswa->total_score) }} Poin
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-slate-400 text-xs">Belum ada siswa terdaftar.</div>
                @endforelse
            </div>
        </div>

        <!-- AI Review Quick Box -->
        <div class="bg-white border-2 border-amber-200/80 rounded-3xl p-6 shadow-sm flex flex-col justify-between space-y-4">
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black text-rose-600 uppercase tracking-wider flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                        Diagnostik AI Review
                    </span>
                    <span class="text-xs font-black bg-rose-100 text-rose-800 px-2.5 py-0.5 rounded-full">{{ $pendingAiRecommendations }} Pending</span>
                </div>
                <h4 class="text-base font-black text-slate-800">Tinjau Rekomendasi Adaptif Siswa</h4>
                <p class="text-xs text-slate-500 leading-relaxed">AI RoboMath mendeteksi siswa yang butuh materi penguatan saat akurasi latihan di bawah standar.</p>
            </div>
            <a href="{{ route('admin.ai-recommendations.index') }}" class="w-full text-center py-2.5 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-black text-xs transition">
                Buka Halaman Review AI &rarr;
            </a>
        </div>
    </div>

    <!-- 3. LAPORAN PERFORMA SISWA (GABUNGAN DASHBOARD + LAPORAN PERFORMA) -->
    <div class="bg-white border-2 border-amber-200/80 rounded-3xl overflow-hidden shadow-sm space-y-0">
        <div class="p-6 border-b-2 border-amber-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="font-black text-slate-800 text-base flex items-center gap-2">
                    <span>📈</span> Laporan Performa & Progres Siswa
                </h3>
                <p class="text-xs text-slate-500 font-semibold mt-0.5">Pantau metrik latihan soal, akurasi jawaban, dan kelulusan modul tiap siswa.</p>
            </div>
            <div class="flex items-center gap-3">
                <form action="{{ route('admin.dashboard') }}" method="GET" class="flex items-center gap-2">
                    <select name="kelas" onchange="this.form.submit()" class="bg-slate-50 border-2 border-amber-200 text-slate-800 rounded-xl px-3 py-1.5 text-xs font-bold focus:border-amber-400 focus:outline-none shadow-sm">
                        <option value="all" {{ $kelasFilter === 'all' ? 'selected' : '' }}>Semua Kelas</option>
                        @for($k = 1; $k <= 6; $k++)
                            <option value="{{ $k }}" {{ (string)$kelasFilter === (string)$k ? 'selected' : '' }}>Kelas {{ $k }} SD</option>
                        @endfor
                    </select>
                </form>
                <a href="{{ route('admin.reports.export', ['kelas' => $kelasFilter]) }}" class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-black px-4 py-1.5 rounded-xl text-xs shadow transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Ekspor Excel (.xlsx)</span>
                </a>
            </div>
        </div>

        @if($performanceStudents->isEmpty())
        <div class="p-12 text-center text-slate-400 font-semibold text-xs">Belum ada catatan aktivitas belajar pada filter ini.</div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-amber-50/50 text-slate-500 uppercase font-black tracking-wider border-b-2 border-amber-100">
                    <tr>
                        <th class="px-6 py-4">Nama Siswa</th>
                        <th class="px-6 py-4">Tingkat</th>
                        <th class="px-6 py-4">Total Skor</th>
                        <th class="px-6 py-4">Materi Tuntas</th>
                        <th class="px-6 py-4">Latihan Soal</th>
                        <th class="px-6 py-4">Akurasi Jawaban</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber-100 text-slate-700">
                    @foreach($performanceStudents as $st)
                    <tr class="hover:bg-amber-50/40 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">{{ $st->name }}</div>
                            <div class="text-[11px] text-slate-400">{{ $st->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-xl text-[10px] font-black bg-amber-100 text-amber-900 border border-amber-300">
                                Kelas {{ $st->kelas ?? '-' }} SD
                            </span>
                        </td>
                        <td class="px-6 py-4 font-black text-amber-600 text-sm">
                            {{ number_format($st->total_score) }} Poin
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-800">
                            {{ $st->completed_lessons }} Modul
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ $st->period_attempts }} Soal Dikerjakan
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-20 bg-slate-100 rounded-full h-2 overflow-hidden">
                                    <div class="h-full rounded-full {{ $st->period_accuracy >= 80 ? 'bg-emerald-500' : ($st->period_accuracy >= 50 ? 'bg-amber-500' : 'bg-rose-500') }}" style="width: {{ $st->period_accuracy }}%"></div>
                                </div>
                                <span class="font-black text-xs {{ $st->period_accuracy >= 80 ? 'text-emerald-700' : ($st->period_accuracy >= 50 ? 'text-amber-700' : 'text-rose-700') }}">
                                    {{ $st->period_accuracy }}%
                                </span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>
@endsection
