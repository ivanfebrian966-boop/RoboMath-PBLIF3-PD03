@extends('layouts.admin')

@section('page-title', 'Laporan Performa Pembelajaran')
@section('page-subtitle', 'Analisis komprehensif perkembangan akademik siswa dan ekspor data')

@section('content')
<div class="space-y-6">

    <!-- KPI Summary Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <span class="text-xs font-black text-slate-400 uppercase tracking-wider">Total Siswa Terfilter</span>
            <div class="text-3xl font-black text-white mt-1">{{ $totalStudents }} Siswa</div>
        </div>
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <span class="text-xs font-black text-slate-400 uppercase tracking-wider">Rata-Rata Akurasi</span>
            <div class="text-3xl font-black text-emerald-400 mt-1">{{ round($avgAccuracy, 1) }}%</div>
        </div>
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <span class="text-xs font-black text-slate-400 uppercase tracking-wider">Rata-Rata Skor</span>
            <div class="text-3xl font-black text-amber-400 mt-1">{{ round($avgScore) }} Poin</div>
        </div>
    </div>

    <!-- Filters & Export Button -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
            <select name="kelas" onchange="this.form.submit()" class="bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-2.5 text-xs font-semibold focus:border-amber-400 focus:outline-none">
                <option value="all" {{ $kelasFilter === 'all' ? 'selected' : '' }}>Semua Kelas (1-6)</option>
                @for($k = 1; $k <= 6; $k++)
                    <option value="{{ $k }}" {{ (string)$kelasFilter === (string)$k ? 'selected' : '' }}>Kelas {{ $k }} SD</option>
                @endfor
            </select>

            <select name="period" onchange="this.form.submit()" class="bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-2.5 text-xs font-semibold focus:border-amber-400 focus:outline-none">
                <option value="all" {{ $periodFilter === 'all' ? 'selected' : '' }}>Semua Waktu</option>
                <option value="month" {{ $periodFilter === 'month' ? 'selected' : '' }}>30 Hari Terakhir</option>
                <option value="week" {{ $periodFilter === 'week' ? 'selected' : '' }}>7 Hari Terakhir</option>
            </select>
        </form>

        <a href="{{ route('admin.reports.export', ['kelas' => $kelasFilter, 'period' => $periodFilter]) }}" class="w-full sm:w-auto px-5 py-2.5 bg-purple-600 hover:bg-purple-500 text-white rounded-xl text-xs font-black flex items-center justify-center gap-2 shadow-lg transition">
            <span>📥</span> Ekspor Laporan CSV
        </a>
    </div>

    <!-- Table -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-slate-800 flex items-center justify-between">
            <h3 class="font-bold text-white text-sm">Tabel Capaian Akademik Siswa</h3>
            <span class="text-xs text-slate-400 font-semibold">{{ $students->count() }} Siswa</span>
        </div>

        @if($students->isEmpty())
        <div class="p-12 text-center text-slate-500 text-sm">Belum ada data siswa untuk kriteria ini.</div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950 text-slate-400 uppercase font-black tracking-wider border-b border-slate-800">
                    <tr>
                        <th class="px-6 py-4">Nama Siswa</th>
                        <th class="px-6 py-4">Kelas</th>
                        <th class="px-6 py-4">Materi Selesai</th>
                        <th class="px-6 py-4">Latihan Soal</th>
                        <th class="px-6 py-4">Akurasi Jawaban</th>
                        <th class="px-6 py-4">Total Skor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 text-slate-300">
                    @foreach($students as $student)
                    <tr class="hover:bg-slate-800/40 transition">
                        <td class="px-6 py-4 font-bold text-white">
                            {{ $student->name }}
                            <span class="block text-[10px] text-slate-500 font-normal">{{ $student->email }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-slate-800 text-slate-300 font-black rounded-lg text-[10px]">
                                Kelas {{ $student->kelas ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-emerald-400">
                            {{ $student->completed_lessons }} Modul
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-400">
                            {{ $student->period_correct }} benar dari {{ $student->period_attempts }} soal
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-16 bg-slate-800 rounded-full h-2 overflow-hidden">
                                    <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ $student->period_accuracy }}%"></div>
                                </div>
                                <span class="font-bold text-white">{{ $student->period_accuracy }}%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-black text-amber-400">
                            ⭐ {{ number_format($student->total_score) }}
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
