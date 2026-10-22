@extends('layouts.app')

@section('title', 'Dashboard Guru - RoboMath')

@section('content')
<div class="space-y-8">
    
    <!-- Teacher Header -->
    <div class="bg-[#059669] rounded-3xl p-8 text-white shadow-lg flex flex-col md:flex-row items-center justify-between gap-6 border border-emerald-400/30">
        <div class="space-y-2">
            <h1 class="text-3xl sm:text-4xl font-black">Dashboard Guru</h1>
            <p class="text-emerald-100 font-semibold text-sm max-w-xl">Ringkasan kelas, daftar siswa aktif, serta pantauan aktivitas latihan matematika waktu nyata.</p>
        </div>
        <div class="flex flex-wrap gap-3">
        </div>
    </div>

    <!-- Overview Stats & Ringkasan Kelas -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-3xl border-2 border-slate-200 shadow-sm space-y-1">
            <span class="text-slate-400 font-extrabold text-xs uppercase">Total Siswa Terdaftar</span>
            <div class="text-3xl font-black text-slate-800">{{ $totalStudents }} Siswa</div>
            <p class="text-xs text-slate-400">Di seluruh kelas SD</p>
        </div>
        <div class="bg-white p-6 rounded-3xl border-2 border-slate-200 shadow-sm space-y-1">
            <span class="text-slate-400 font-extrabold text-xs uppercase">Rata-Rata Skor</span>
            <div class="text-3xl font-black text-amber-600">{{ round($avgScore) }} Poin</div>
            <p class="text-xs text-slate-400">Hasil pengerjaan kuis</p>
        </div>
        <div class="bg-white p-6 rounded-3xl border-2 border-slate-200 shadow-sm space-y-1">
            <span class="text-slate-400 font-extrabold text-xs uppercase">Akurasi Keseluruhan</span>
            <div class="text-3xl font-black text-emerald-600">{{ $overallAccuracy }}%</div>
            <p class="text-xs text-slate-400">Tingkat ketepatan jawaban</p>
        </div>
        <div class="bg-white p-6 rounded-3xl border-2 border-slate-200 shadow-sm space-y-1 flex flex-col justify-between">
            <span class="text-slate-400 font-extrabold text-xs uppercase">Progress & Leaderboard</span>
            <a href="{{ route('guru.leaderboard') }}" class="inline-flex items-center gap-1.5 font-black text-indigo-600 hover:text-indigo-800 text-sm">
                Buka Progress Siswa &rarr;
            </a>
        </div>
    </div>

    <!-- Ringkasan Kelas SD Cards -->
    <div class="bg-white rounded-3xl border-2 border-slate-200 p-6 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-black text-slate-800 text-base flex items-center gap-2">
                <span>🏫</span> Ringkasan Tingkat Kelas SD
            </h3>
            <a href="{{ route('guru.classes.index') }}" class="text-xs font-bold text-emerald-700 hover:underline">Kelola Ruang Kelas & Kode Gabung &rarr;</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
            @foreach($classStats as $cs)
            <div class="p-4 rounded-2xl bg-emerald-50/60 border border-emerald-200 text-center space-y-1">
                <span class="text-xs font-black text-emerald-900">Kelas {{ $cs['kelas'] }} SD</span>
                <div class="text-2xl font-black text-slate-800">{{ $cs['count'] }}</div>
                <div class="text-[11px] text-emerald-700 font-bold">Akurasi: {{ $cs['accuracy'] }}%</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Aktivitas Pengerjaan Kuis Siswa Terkini -->
    <div class="bg-white rounded-3xl border-2 border-slate-200 p-6 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-black text-slate-800 text-base flex items-center gap-2">
                <span>⚡</span> Aktivitas Latihan Siswa Terkini
            </h3>
            <span class="text-xs font-bold text-slate-400">Pembaruan aktivitas real-time</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($recentActivities as $act)
            <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50 flex items-center justify-between">
                <div>
                    <div class="font-black text-slate-800 text-xs">{{ $act->user->name ?? 'Siswa' }}</div>
                    <div class="text-[11px] text-slate-500">Topik: {{ $act->quiz->topic->title ?? 'Matematika' }}</div>
                    <div class="text-[10px] text-slate-400">{{ $act->created_at->diffForHumans() }}</div>
                </div>
                <div class="text-right">
                    @if($act->is_correct)
                        <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800">
                            +{{ $act->points_earned }} Benar ✨
                        </span>
                    @else
                        <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-black bg-rose-100 text-rose-800">
                            Perlu Latihan ❌
                        </span>
                    @endif
                </div>
            </div>
            @empty
            <p class="text-xs text-slate-400 col-span-3">Belum ada aktivitas pengerjaan kuis hari ini.</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-3xl border-2 border-slate-200 p-6 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-black text-slate-800 text-base flex items-center gap-2">
                <span>📚</span> Sesi Belajar Terbaru
            </h3>
            <span class="text-xs font-bold text-slate-400">Siswa dari kelas yang Anda ajar</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($recentSessions as $session)
            <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50 flex items-center justify-between gap-3">
                <div>
                    <div class="font-black text-slate-800 text-xs">{{ $session->user->name ?? 'Siswa' }}</div>
                    <div class="text-[11px] text-slate-500">Topik: {{ $session->topic->title ?? 'Matematika' }}</div>
                    <div class="text-[10px] text-slate-400">{{ $session->dimulai_pada->diffForHumans() }}</div>
                </div>
                <div class="text-right">
                    <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-black bg-amber-100 text-amber-800">
                        {{ $session->poin_diperoleh }} poin
                    </span>
                    <div class="text-[10px] font-bold text-slate-500 mt-1">
                        {{ $session->selesai_pada ? 'Selesai' : 'Berlangsung' }}
                    </div>
                </div>
            </div>
            @empty
            <p class="text-xs text-slate-400 col-span-3">Belum ada sesi belajar dari siswa kelas Anda.</p>
            @endforelse
        </div>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-3xl border-2 border-slate-200 p-6 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-black text-slate-800">Daftar Siswa & Perkembangan</h2>
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
