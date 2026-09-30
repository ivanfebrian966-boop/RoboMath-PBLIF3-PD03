@extends('layouts.admin')

@section('title', 'Laporan Performa Pembelajaran - RoboMath')

@section('content')
<div class="space-y-6">

    <!-- KPI Summary Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white border-2 border-amber-200/80 rounded-3xl p-6 shadow-sm space-y-1">
            <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Total Siswa Terfilter</span>
            <div class="text-3xl font-black text-slate-900 mt-1">{{ $totalStudents }} Siswa</div>
            <p class="text-xs text-slate-400">Dalam cakupan filter aktif</p>
        </div>
        <div class="bg-white border-2 border-amber-200/80 rounded-3xl p-6 shadow-sm space-y-1">
            <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Rata-Rata Akurasi</span>
            <div class="text-3xl font-black text-emerald-600 mt-1">{{ round($avgAccuracy, 1) }}%</div>
            <p class="text-xs text-slate-400">Tingkat ketepatan jawaban kuis</p>
        </div>
        <div class="bg-white border-2 border-amber-200/80 rounded-3xl p-6 shadow-sm space-y-1">
            <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Rata-Rata Skor</span>
            <div class="text-3xl font-black text-amber-600 mt-1">{{ round($avgScore) }} Poin</div>
            <p class="text-xs text-slate-400">Akumulasi perolehan poin siswa</p>
        </div>
    </div>

    <!-- Filters & Export Button -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
            <select name="kelas" onchange="this.form.submit()" class="bg-white border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-2.5 text-xs font-bold focus:border-amber-400 focus:outline-none shadow-sm">
                <option value="all" {{ $kelasFilter === 'all' ? 'selected' : '' }}>Semua Kelas (1-6 SD)</option>
                @for($k = 1; $k <= 6; $k++)
                    <option value="{{ $k }}" {{ (string)$kelasFilter === (string)$k ? 'selected' : '' }}>Kelas {{ $k }} SD</option>
                @endfor
            </select>

            <select name="period" onchange="this.form.submit()" class="bg-white border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-2.5 text-xs font-bold focus:border-amber-400 focus:outline-none shadow-sm">
                <option value="all" {{ $periodFilter === 'all' ? 'selected' : '' }}>Semua Periode Waktu</option>
                <option value="month" {{ $periodFilter === 'month' ? 'selected' : '' }}>30 Hari Terakhir</option>
                <option value="week" {{ $periodFilter === 'week' ? 'selected' : '' }}>7 Hari Terakhir</option>
            </select>
        </form>

        <a href="{{ route('admin.reports.export', ['kelas' => $kelasFilter, 'period' => $periodFilter]) }}" class="w-full sm:w-auto px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-2xl text-xs font-black flex items-center justify-center gap-2 shadow-md transition transform active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Ekspor Laporan CSV
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white border-2 border-amber-200/80 rounded-3xl overflow-hidden shadow-sm">
        <div class="p-6 border-b-2 border-amber-100 flex items-center justify-between">
            <h3 class="font-black text-slate-800 text-base">Tabel Capaian Akademik Siswa</h3>
            <span class="text-xs text-slate-500 font-extrabold bg-amber-50 border border-amber-200 px-3 py-1 rounded-full">{{ $students->count() }} Siswa</span>
        </div>

        @if($students->isEmpty())
        <div class="p-12 text-center text-slate-400 font-semibold text-sm">Belum ada data siswa untuk kriteria ini.</div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-amber-50/50 text-slate-500 uppercase font-black tracking-wider border-b-2 border-amber-100">
                    <tr>
                        <th class="px-6 py-4">Nama Siswa</th>
                        <th class="px-6 py-4">Kelas</th>
                        <th class="px-6 py-4">Materi Selesai</th>
                        <th class="px-6 py-4">Latihan Soal</th>
                        <th class="px-6 py-4">Akurasi Jawaban</th>
                        <th class="px-6 py-4">Total Skor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber-100 text-slate-700">
                    @foreach($students as $student)
                    <tr class="hover:bg-amber-50/40 transition">
                        <td class="px-6 py-4 font-bold text-slate-900">
                            {{ $student->name }}
                            <span class="block text-[10px] text-slate-500 font-normal">{{ $student->email }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-amber-50 border border-amber-200 text-amber-900 font-black rounded-xl text-[10px]">
                                Kelas {{ $student->kelas ?? '-' }} SD
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-emerald-700">
                            {{ $student->completed_lessons }} Modul
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-600">
                            {{ $student->period_correct }} benar dari {{ $student->period_attempts }} soal
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-16 bg-slate-100 rounded-full h-2 overflow-hidden border border-slate-200">
                                    <div class="bg-gradient-to-r from-teal-500 to-indigo-600 h-2 rounded-full" style="width: {{ $student->period_accuracy }}%"></div>
                                </div>
                                <span class="font-extrabold text-slate-800">{{ $student->period_accuracy }}%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-black text-amber-700">
                            {{ number_format($student->total_score) }} Poin
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
