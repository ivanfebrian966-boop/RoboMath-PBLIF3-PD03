@extends('layouts.app')

@section('title', 'Progress Siswa - Guru RoboMath')

@section('content')
<div class="max-w-6xl mx-auto space-y-5 sm:space-y-6">

    <!-- Header Banner -->
    <div class="rounded-3xl bg-[#047857] p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-6 border border-emerald-400/30">
        <div class="space-y-2">
            <h1 class="text-3xl sm:text-4xl font-black">Leaderboard</h1>
            <p class="text-emerald-100 text-sm max-w-xl">Pantau capaian materi, akurasi kuis, dan peringkat siswa. Unduh laporan performa kelas kapan saja.</p>
        </div>
        <div>
            <a href="{{ route('guru.laporan.download') }}" class="inline-flex items-center gap-2 bg-white text-emerald-800 px-5 py-3 rounded-full font-black text-xs shadow-md hover:bg-emerald-50 transition transform active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>Unduh Laporan Excel</span>
            </a>
        </div>
    </div>

    <!-- Ringkasan KPI Siswa -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-1">
            <span class="text-slate-400 font-extrabold text-xs uppercase">Total Siswa Terpantau</span>
            <div class="text-3xl font-black text-slate-800">{{ $totalSiswa }} Siswa</div>
            <p class="text-xs text-slate-500">Siswa aktif terdaftar</p>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-1">
            <span class="text-slate-400 font-extrabold text-xs uppercase">Rata-Rata Skor</span>
            <div class="text-3xl font-black text-amber-600">{{ number_format($avgScore) }} Poin</div>
            <p class="text-xs text-slate-500">Akumulasi hasil latihan</p>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-1">
            <span class="text-slate-400 font-extrabold text-xs uppercase">Rata-Rata Akurasi</span>
            <div class="text-3xl font-black text-blue-700">{{ round($avgAccuracy) }}%</div>
            <p class="text-xs text-slate-500">Tingkat ketepatan jawaban kuis</p>
        </div>
    </div>

    <!-- Filter Kelas Tabs -->
    <div class="flex items-center gap-3 flex-wrap">
        <span class="text-[10px] font-black text-slate-500 uppercase tracking-wider">Tingkat kelas</span>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('guru.leaderboard', ['kelas' => 'all']) }}" 
               @if($kelasFilter === 'all') aria-current="page" @endif
               class="px-4 py-2 rounded-full border text-xs font-black transition-all {{ $kelasFilter === 'all' ? 'border-slate-900 bg-slate-900 text-white shadow-sm' : 'border-slate-200 bg-white text-slate-700 hover:bg-emerald-50' }}">
                Semua Kelas
            </a>
            @for($k = 1; $k <= 6; $k++)
            <a href="{{ route('guru.leaderboard', ['kelas' => $k]) }}" 
               @if((string) $kelasFilter === (string) $k) aria-current="page" @endif
               class="px-4 py-2 rounded-full border text-xs font-black transition-all {{ (string) $kelasFilter === (string) $k ? 'border-slate-900 bg-slate-900 text-white shadow-sm' : 'border-slate-200 bg-white text-slate-700 hover:bg-emerald-50' }}">
                Kelas {{ $k }}
            </a>
            @endfor
        </div>
    </div>

    <!-- Table Progress & Leaderboard -->
    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between gap-3 bg-amber-100 px-5 py-4 sm:px-6">
            <div class="flex items-center gap-3">
                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-white text-xl text-amber-700" aria-hidden="true">🏆</span>
                <h2 class="font-black text-slate-900 text-base sm:text-lg">Progres & papan peringkat siswa</h2>
            </div>
            <span class="shrink-0 rounded-full bg-white px-3 py-1 text-[10px] font-black text-amber-800">{{ $leaderboard->count() }} siswa ditampilkan</span>
        </div>

        @if($leaderboard->isEmpty())
        <div class="p-12 text-center text-slate-500 font-bold">
            Belum ada data siswa untuk kriteria ini.
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-[#FAF8F3] text-slate-500 uppercase text-[10px] font-black tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="whitespace-nowrap px-5 py-4">Peringkat</th>
                        <th class="whitespace-nowrap px-5 py-4">Nama Siswa</th>
                        <th class="whitespace-nowrap px-5 py-4">Kelas</th>
                        <th class="whitespace-nowrap px-5 py-4">Materi Tuntas</th>
                        <th class="whitespace-nowrap px-5 py-4">Akurasi Soal</th>
                        <th class="whitespace-nowrap px-5 py-4">Total Skor</th>
                        <th class="whitespace-nowrap px-5 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber-100">
                    @foreach($leaderboard as $index => $student)
                    <tr class="transition hover:bg-amber-50/40">
                        <td class="px-5 py-4">
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl font-black text-xs {{ $index == 0 ? 'bg-amber-100 text-amber-800' : ($index == 1 ? 'bg-slate-200 text-slate-700' : ($index == 2 ? 'bg-orange-100 text-orange-800' : 'bg-slate-100 text-slate-600')) }}">
                                {{ $index + 1 }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="font-black text-slate-900">{{ $student->name }}</div>
                            <div class="text-[11px] font-medium text-slate-500">{{ $student->email }}</div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="whitespace-nowrap rounded-full bg-blue-100 px-3 py-1 text-[10px] font-black text-blue-800">
                                Kelas {{ $student->kelas ?? '-' }} SD
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 font-black text-slate-800">
                            {{ $student->completed_lessons }} modul
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <div class="h-2 w-24 overflow-hidden rounded-full bg-[#F0E8D8]" role="progressbar" aria-label="Akurasi soal {{ $student->name }}" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $student->accuracy }}">
                                    <div class="h-full rounded-full {{ $student->accuracy >= 80 ? 'bg-emerald-500' : ($student->accuracy >= 50 ? 'bg-amber-500' : 'bg-rose-500') }}" style="width: {{ $student->accuracy }}%"></div>
                                </div>
                                <span class="font-black text-xs {{ $student->accuracy >= 80 ? 'text-emerald-700' : ($student->accuracy >= 50 ? 'text-amber-700' : 'text-blue-700') }}">
                                    {{ $student->accuracy }}%
                                </span>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4">
                            <span class="inline-flex items-center gap-1 font-black text-amber-700">
                                ⭐ {{ number_format($student->total_score) }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-right">
                            <a href="{{ route('guru.students.show', $student) }}" class="inline-block rounded-full bg-slate-900 px-4 py-2 text-[10px] font-black text-white transition hover:bg-slate-700">
                                Detail
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
