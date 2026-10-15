@extends('layouts.admin')

@section('title', 'Dashboard Admin - RoboMath')

@section('content')
<div class="space-y-8">

    <!-- Hero Banner Admin -->
    <div class="bg-[#FF9500] rounded-3xl p-8 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-6 border-2 border-amber-300/40">
        <div class="space-y-2">
            <h1 class="text-3xl sm:text-4xl font-black">Dashboard & Laporan Performa</h1>
            <p class="text-amber-100 font-semibold text-sm max-w-xl">Ringkasan menyeluruh kesehatan sistem, statistik pengguna, diagnostik AI, serta laporan performa siswa SD.</p>
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

    <!-- Laporan Performa Siswa -->
    <div class="bg-white border-2 border-amber-200/80 rounded-3xl overflow-hidden shadow-sm space-y-0">
        <div class="p-6 border-b-2 border-amber-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="font-black text-slate-800 text-base flex items-center gap-2">
             Laporan Performa & Progres Siswa
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
