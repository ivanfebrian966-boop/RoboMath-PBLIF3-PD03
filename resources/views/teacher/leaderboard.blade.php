@extends('layouts.app')

@section('title', 'Peringkat Siswa - Guru RoboMath')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header Banner -->
    <div class="rounded-3xl bg-gradient-to-r from-indigo-600 via-purple-600 to-amber-500 p-8 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
        <div>
            <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-black uppercase tracking-wider mb-2">
                🏆 Leaderboard Global
            </span>
            <h1 class="text-3xl font-black">Peringkat Prestasi Siswa</h1>
            <p class="text-indigo-100 text-sm mt-1">Pantau siswa dengan perolehan skor tertinggi di seluruh tingkatan kelas.</p>
        </div>
        <div>
            <a href="{{ route('guru.laporan.download') }}" class="inline-flex items-center gap-2 bg-white text-indigo-700 px-5 py-3 rounded-2xl font-black text-sm shadow-md hover:bg-amber-50 transition">
                <span>📥</span> Unduh Laporan CSV
            </a>
        </div>
    </div>

    <!-- Filter Kelas Tabs -->
    <div class="flex items-center justify-between flex-wrap gap-3 bg-white p-3 rounded-2xl border-2 border-slate-200 shadow-sm">
        <span class="text-xs font-black text-slate-500 uppercase px-3">Filter Kelas:</span>
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

    <!-- Table -->
    <div class="bg-white rounded-3xl border-2 border-slate-200 overflow-hidden shadow-sm">
        <div class="p-5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h2 class="font-black text-slate-800 text-base">Top 50 Siswa Teratas</h2>
            <span class="text-xs font-bold text-slate-500">{{ $leaderboard->count() }} siswa terdaftar</span>
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
                        <th class="px-6 py-4">Level</th>
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
                        <td class="px-6 py-4 font-bold text-slate-800">
                            {{ $student->name }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-indigo-50 text-indigo-700 font-black rounded-lg text-xs">
                                Kelas {{ $student->kelas ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-600">
                            Lv. {{ $student->level ?? 1 }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 font-black text-amber-600">
                                ⭐ {{ number_format($student->total_score) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('guru.students.show', $student) }}" class="inline-block px-3 py-1.5 bg-slate-100 hover:bg-indigo-50 text-indigo-600 rounded-xl font-bold text-xs transition">
                                Detail Profil &rarr;
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
