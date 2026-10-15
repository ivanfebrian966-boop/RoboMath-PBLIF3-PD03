@extends('layouts.app')

@section('title', 'Progress Siswa - Guru RoboMath')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Header Banner -->
    <div class="rounded-3xl bg-[#4F46E5] p-8 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-6 border-2 border-indigo-400/30">
        <div class="space-y-1">
            <h1 class="text-3xl font-black">Progress Siswa & Leaderboard</h1>
            <p class="text-indigo-100 text-sm max-w-xl">Pantau perkembangan capaian materi, akurasi pengerjaan soal kuis, papan peringkat, dan unduh laporan performa kelas.</p>
        </div>
        <div>
            <a href="{{ route('guru.laporan.download') }}" class="inline-flex items-center gap-2 bg-white text-indigo-700 px-5 py-3 rounded-2xl font-black text-xs shadow-md hover:bg-amber-50 transition transform active:scale-95">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>Unduh Laporan Excel</span>
            </a>
        </div>
    </div>

    <!-- Ringkasan KPI Siswa -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-3xl border-2 border-slate-200 shadow-sm space-y-1">
            <span class="text-slate-400 font-extrabold text-xs uppercase">Total Siswa Terpantau</span>
            <div class="text-3xl font-black text-slate-800">{{ $totalSiswa }} Siswa</div>
            <p class="text-xs text-slate-500">Siswa aktif terdaftar</p>
        </div>
        <div class="bg-white p-5 rounded-3xl border-2 border-slate-200 shadow-sm space-y-1">
            <span class="text-slate-400 font-extrabold text-xs uppercase">Rata-Rata Skor</span>
            <div class="text-3xl font-black text-amber-600">{{ number_format($avgScore) }} Poin</div>
            <p class="text-xs text-slate-500">Akumulasi hasil latihan</p>
        </div>
        <div class="bg-white p-5 rounded-3xl border-2 border-slate-200 shadow-sm space-y-1">
            <span class="text-slate-400 font-extrabold text-xs uppercase">Rata-Rata Akurasi</span>
            <div class="text-3xl font-black text-emerald-600">{{ round($avgAccuracy) }}%</div>
            <p class="text-xs text-slate-500">Tingkat ketepatan jawaban kuis</p>
        </div>
    </div>

    <!-- Filter Kelas Tabs -->
    <div class="flex items-center justify-between flex-wrap gap-3 bg-white p-3 rounded-2xl border-2 border-slate-200 shadow-sm">
        <span class="text-xs font-black text-slate-500 uppercase px-3">Filter Tingkat Kelas:</span>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('guru.leaderboard', ['kelas' => 'all']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-black transition-all {{ $kelasFilter === 'all' ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-indigo-50' }}">
                Semua Kelas
            </a>
            @for($k = 1; $k <= 6; $k++)
            <a href="{{ route('guru.leaderboard', ['kelas' => $k]) }}" 
               class="px-4 py-2 rounded-xl text-xs font-black transition-all {{ (string)$kelasFilter === (string)$k ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-indigo-50' }}">
                Kelas {{ $k }}
            </a>
            @endfor
        </div>
    </div>

    <!-- Table Progress & Leaderboard -->
    <div class="bg-white rounded-3xl border-2 border-slate-200 overflow-hidden shadow-sm">
        <div class="p-5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h2 class="font-black text-slate-800 text-base">Pantauan Progres & Papan Peringkat Siswa</h2>
            <span class="text-xs font-bold text-slate-500">{{ $leaderboard->count() }} siswa ditampilkan</span>
        </div>

        @if($leaderboard->isEmpty())
        <div class="p-12 text-center text-slate-500 font-bold">
            Belum ada data siswa untuk kriteria ini.
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-[11px] font-black border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Peringkat</th>
                        <th class="px-6 py-4">Nama Siswa</th>
                        <th class="px-6 py-4">Kelas</th>
                        <th class="px-6 py-4">Materi Tuntas</th>
                        <th class="px-6 py-4">Akurasi Soal</th>
                        <th class="px-6 py-4">Total Skor</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($leaderboard as $index => $student)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl font-black text-xs {{ $index == 0 ? 'bg-amber-400 text-amber-950 shadow-sm' : ($index == 1 ? 'bg-slate-300 text-slate-800' : ($index == 2 ? 'bg-amber-200 text-amber-900' : 'bg-slate-100 text-slate-600')) }}">
                                {{ $index + 1 }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800">{{ $student->name }}</div>
                            <div class="text-[11px] text-slate-400">{{ $student->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-indigo-50 text-indigo-700 font-black rounded-lg text-xs">
                                Kelas {{ $student->kelas ?? '-' }} SD
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-700">
                            {{ $student->completed_lessons }} Modul
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-16 bg-slate-100 rounded-full h-2 overflow-hidden">
                                    <div class="h-full rounded-full {{ $student->accuracy >= 80 ? 'bg-emerald-500' : ($student->accuracy >= 50 ? 'bg-amber-500' : 'bg-rose-500') }}" style="width: {{ $student->accuracy }}%"></div>
                                </div>
                                <span class="font-black text-xs {{ $student->accuracy >= 80 ? 'text-emerald-700' : ($student->accuracy >= 50 ? 'text-amber-700' : 'text-rose-700') }}">
                                    {{ $student->accuracy }}%
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 font-black text-amber-600">
                                ⭐ {{ number_format($student->total_score) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('guru.students.show', $student) }}" class="inline-block px-3 py-1.5 bg-slate-100 hover:bg-indigo-50 text-indigo-600 rounded-xl font-bold text-xs transition">
                                Detail Progres &rarr;
                            </a>
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
