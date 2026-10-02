@extends('layouts.app')

@section('title', 'Dasbor Guru - RoboMath')

@section('content')
<div class="space-y-8">
    
    <!-- Teacher Header -->
    <div class="bg-[#059669] rounded-3xl p-8 text-white shadow-lg flex flex-col md:flex-row items-center justify-between gap-6 border border-emerald-400/30">
        <div class="space-y-2">
            <span class="bg-white/20 text-white font-extrabold text-xs px-3 py-1 rounded-full uppercase tracking-wider">Dasbor Monitoring Guru</span>
            <h1 class="text-3xl sm:text-4xl font-black">Monitoring Perkembangan Siswa</h1>
            <p class="text-emerald-100 font-semibold text-sm max-w-xl">Pantau hasil latihan, akurasi jawaban, dan topik materi yang memerlukan bimbingan tambahan.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('guru.laporan.download') }}" class="inline-flex items-center gap-2 bg-white text-emerald-800 font-black px-5 py-3 rounded-2xl text-xs shadow-md hover:bg-emerald-50 transition transform active:scale-95">
                <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Unduh Laporan CSV
            </a>
            <a href="{{ route('guru.classes.create') }}" class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-400 text-white font-black px-5 py-3 rounded-2xl text-xs shadow-md transition transform active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Buat Kelas Baru
            </a>
        </div>
    </div>

    <!-- Overview Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-3xl border-2 border-slate-200 shadow-sm space-y-1">
            <span class="text-slate-400 font-extrabold text-xs uppercase">Total Siswa</span>
            <div class="text-3xl font-black text-slate-800">{{ $totalStudents }} Siswa</div>
        </div>
        <div class="bg-white p-6 rounded-3xl border-2 border-slate-200 shadow-sm space-y-1">
            <span class="text-slate-400 font-extrabold text-xs uppercase">Rata-Rata Skor</span>
            <div class="text-3xl font-black text-amber-600">{{ round($avgScore) }} Poin</div>
        </div>
        <div class="bg-white p-6 rounded-3xl border-2 border-slate-200 shadow-sm space-y-1">
            <span class="text-slate-400 font-extrabold text-xs uppercase">Akurasi Keseluruhan</span>
            <div class="text-3xl font-black text-emerald-600">{{ $overallAccuracy }}%</div>
        </div>
        <div class="bg-white p-6 rounded-3xl border-2 border-slate-200 shadow-sm space-y-1 flex flex-col justify-between">
            <span class="text-slate-400 font-extrabold text-xs uppercase">Papan Peringkat</span>
            <a href="{{ route('guru.leaderboard') }}" class="inline-flex items-center gap-1.5 font-black text-indigo-600 hover:text-indigo-800 text-sm">
                Lihat Top 50 Siswa &rarr;
            </a>
        </div>
    </div>

    <!-- Quick Navigation to Kelas -->
    <div class="bg-teal-50 rounded-3xl p-6 border-2 border-teal-200 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-teal-600 text-white flex items-center justify-center text-xl font-black shadow-md flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div>
                <h3 class="font-black text-slate-800 text-base">Kelola Ruang Kelas & Kode Unik Siswa</h3>
                <p class="text-xs text-slate-500 font-semibold">Buat kode kelas untuk membagikan topik modul khusus kepada rombongan belajar.</p>
            </div>
        </div>
        <a href="{{ route('guru.classes.index') }}" class="px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-black text-xs rounded-xl shadow transition">
            Buka Manajemen Kelas &rarr;
        </a>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-3xl border-2 border-slate-200 p-6 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-black text-slate-800">Daftar Perkembangan Siswa</h2>
            <span class="text-xs font-bold text-slate-400">{{ $students->count() }} Siswa</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm font-semibold">
                <thead>
                    <tr class="border-b-2 border-slate-100 text-slate-400 font-black text-xs uppercase">
                        <th class="py-3 px-4">Nama Siswa</th>
                        <th class="py-3 px-4">Kelas</th>
                        <th class="py-3 px-4">Total Poin</th>
                        <th class="py-3 px-4">Level</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @foreach($students as $student)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3.5 px-4 font-extrabold text-slate-900">{{ $student->name }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg text-xs font-bold">
                                    Kelas {{ $student->kelas }} SD
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-black text-amber-600">{{ number_format($student->total_score) }} Poin</td>
                            <td class="py-3.5 px-4">
                                <span class="bg-amber-100 text-amber-900 font-extrabold text-xs px-2.5 py-1 rounded-full">
                                    Level {{ $student->level }} ({{ $student->level_name }})
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('guru.students.show', $student) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-black px-4 py-2 rounded-xl text-xs shadow-sm transition">
                                    Detail Progres &rarr;
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
