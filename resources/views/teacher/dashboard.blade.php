@extends('layouts.app')

@section('title', 'Dashboard Guru - RoboMath')

@section('content')
<div class="space-y-4 sm:space-y-5 font-poppins">
    
    <!-- Teacher Header -->
    <div class="rounded-[28px] bg-[#087A5A] px-6 py-6 sm:px-8 sm:py-7 text-white shadow-[0_12px_28px_rgba(8,122,90,0.2)]">
        <div class="max-w-2xl space-y-2">
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Dashboard Guru</h1>
            <p class="max-w-xl text-xs sm:text-sm font-medium leading-relaxed text-emerald-50">
                Ringkasan kelas, daftar siswa aktif, dan pantauan aktivitas latihan matematika secara real-time.
            </p>
        </div>
    </div>

    <!-- Overview Statistics -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-3">
        <div class="grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-[#F1EDE5] overflow-hidden rounded-[24px] border border-slate-100 bg-white shadow-[0_8px_24px_rgba(15,23,42,0.06)] lg:col-span-3">
            <div class="space-y-1 px-5 py-4 sm:px-6">
                <span class="block text-[9px] font-extrabold uppercase tracking-[0.12em] text-slate-500">Total siswa terpantau</span>
                <div class="text-3xl font-extrabold leading-none text-[#EF4444]">{{ $totalStudents }} <span class="text-sm font-bold text-slate-500">siswa</span></div>
                <p class="text-[10px] font-medium text-slate-500">Di seluruh kelas binaan</p>
            </div>
            <div class="space-y-1 px-5 py-4 sm:px-6">
                <span class="block text-[9px] font-extrabold uppercase tracking-[0.12em] text-slate-500">Rata-rata skor</span>
                <div class="text-3xl font-extrabold leading-none text-amber-700">{{ number_format(round($avgScore)) }} <span class="text-sm font-bold text-slate-500">poin</span></div>
                <p class="text-[10px] font-medium text-slate-500">Hasil latihan keseluruhan</p>
            </div>
            <div class="space-y-1 px-5 py-4 sm:px-6">
                <span class="block text-[9px] font-extrabold uppercase tracking-[0.12em] text-slate-500">Akurasi keseluruhan</span>
                <div class="text-3xl font-extrabold leading-none text-blue-700">{{ $overallAccuracy }}%</div>
                <p class="text-[10px] font-medium text-slate-500">Tingkat kebenaran jawaban</p>
            </div>
        </div>

        <a href="{{ route('guru.leaderboard') }}" class="flex items-center justify-between gap-3 rounded-[24px] border border-emerald-200 bg-[#CFFAE9] px-5 py-4 shadow-[0_8px_24px_rgba(15,23,42,0.05)] transition hover:bg-[#B8F4DB] lg:col-span-2">
            <span class="space-y-1">
                <span class="block text-[9px] font-extrabold uppercase tracking-[0.12em] text-emerald-800">Progress & leaderboard</span>
                <span class="block text-sm font-extrabold text-emerald-950">Data progres siswa</span>
                <span class="block text-[10px] font-semibold text-emerald-900">Buka halaman <span aria-hidden="true">&rarr;</span></span>
            </span>
            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-white/70 text-emerald-700" aria-hidden="true">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 19V5m0 14h16M8 15l3-4 3 2 5-7" />
                </svg>
            </span>
        </a>
    </div>

    <!-- Student Lesson Completion Charts -->
    <section class="space-y-3" aria-labelledby="completion-charts-heading">
        <div class="space-y-0.5">
            <h2 id="completion-charts-heading" class="text-base sm:text-lg font-extrabold tracking-tight text-slate-900">Visualisasi ketuntasan materi</h2>
            <p class="text-[10px] sm:text-[11px] font-medium text-slate-500">Persentase materi yang telah diselesaikan siswa dari total materi di kelasnya.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-3">
            <div class="lg:col-span-3 rounded-[22px] border border-slate-100 bg-white p-4 sm:p-5 shadow-[0_8px_24px_rgba(15,23,42,0.06)] space-y-3">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h3 class="font-extrabold text-slate-900">Ketuntasan per siswa</h3>
                        <p class="text-[10px] font-semibold text-slate-400">Progres penyelesaian materi siswa</p>
                    </div>
                    <span class="shrink-0 rounded-full bg-slate-100 px-2.5 py-1 text-[9px] font-extrabold text-slate-600">
                        {{ $studentCompletionStats->max('total_lessons') ?? 0 }} materi
                    </span>
                </div>

                <div class="space-y-3 max-h-80 overflow-y-auto pr-1">
                    @forelse($studentCompletionStats as $stat)
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="truncate text-[11px] font-extrabold text-slate-900">{{ $stat['name'] }}</p>
                                    <p class="truncate text-[9px] font-medium text-slate-500">
                                        {{ $stat['email'] }} · Kelas {{ $stat['kelas'] }} SD
                                    </p>
                                </div>
                                <span class="shrink-0 text-[10px] font-extrabold text-emerald-700">{{ $stat['percentage'] }}%</span>
                            </div>
                            <div
                                class="h-1.5 overflow-hidden rounded-full bg-[#F0E8D8]"
                                role="progressbar"
                                aria-label="Ketuntasan materi {{ $stat['name'] }}"
                                aria-valuemin="0"
                                aria-valuemax="100"
                                aria-valuenow="{{ $stat['percentage'] }}"
                            >
                                <div class="h-full rounded-full bg-emerald-500 transition-all" style="width: {{ $stat['percentage'] }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="rounded-xl border border-amber-100 bg-[#FFF8E8] p-3 text-[10px] leading-relaxed text-amber-900">
                            Belum ada siswa yang tuntas. Bar akan terisi setelah siswa menyelesaikan materi.
                        </p>
                    @endforelse
                </div>
            </div>

            <div class="lg:col-span-2 flex flex-col items-center justify-center gap-3 rounded-[22px] border border-slate-100 bg-white p-4 sm:p-5 shadow-[0_8px_24px_rgba(15,23,42,0.06)]">
                <div class="w-full space-y-1">
                    <h3 class="text-xs font-extrabold text-slate-900">Ketuntasan seluruh kelas</h3>
                </div>

                <div
                    class="relative grid h-32 w-32 place-items-center rounded-full"
                    role="img"
                    aria-label="Ketuntasan keseluruhan {{ $overallCompletionPercentage }} persen"
                    style="background: conic-gradient(#10b981 0% {{ $overallCompletionPercentage }}%, #f0e8d8 {{ $overallCompletionPercentage }}% 100%)"
                >
                    <div class="grid h-24 w-24 place-items-center rounded-full bg-white text-center">
                        <div>
                            <div class="text-2xl font-extrabold text-slate-900">{{ $overallCompletionPercentage }}%</div>
                            <div class="text-[8px] font-extrabold uppercase tracking-wider text-slate-500">Tuntas</div>
                        </div>
                    </div>
                </div>

                <div class="grid w-full grid-cols-2 gap-2 text-xs">
                    <div class="flex flex-col gap-0.5 rounded-xl bg-[#CFFAE9] p-2.5">
                        <span class="text-[8px] font-extrabold uppercase tracking-wider text-emerald-800">Selesai</span>
                        <span class="text-xs font-extrabold text-emerald-950">{{ $totalCompletedLessons }} materi</span>
                    </div>
                    <div class="flex flex-col gap-0.5 rounded-xl bg-slate-100 p-2.5">
                        <span class="text-[8px] font-extrabold uppercase tracking-wider text-slate-500">Belum</span>
                        <span class="text-xs font-extrabold text-slate-800">{{ $totalRemainingLessons }} materi</span>
                    </div>
                </div>

                @if($totalAvailableLessons === 0)
                    <p class="text-center text-xs font-semibold text-slate-400">Belum ada materi aktif yang tersedia untuk siswa.</p>
                @else
                    <p class="text-center text-[9px] font-medium text-slate-500">{{ $totalStudents }} siswa · {{ $totalAvailableLessons }} materi</p>
                @endif
            </div>
        </div>
    </section>

    <!-- Classroom, Activity, and Study Session Summaries -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
        <section class="rounded-[22px] border border-slate-100 bg-white p-4 shadow-[0_8px_24px_rgba(15,23,42,0.06)] space-y-3">
            <div class="flex items-center gap-3">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-blue-100 text-blue-700" aria-hidden="true">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m3 10 9-7 9 7M5 9v11h14V9M9 20v-6h6v6" />
                    </svg>
                </span>
                <h3 class="text-[11px] font-extrabold leading-tight text-slate-900">Ringkasan tingkat kelas</h3>
            </div>
            @forelse($classStats as $cs)
                <div class="flex items-center justify-between gap-3 rounded-2xl bg-[#FAF8F3] p-3">
                    <div>
                        <p class="text-[11px] font-extrabold text-slate-900">Kelas {{ $cs['kelas'] }} SD</p>
                        <p class="text-[9px] font-medium text-slate-500">{{ $cs['count'] }} siswa · akurasi {{ $cs['accuracy'] }}%</p>
                    </div>
                    <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-[9px] font-black text-emerald-800">Aktif</span>
                </div>
            @empty
                <p class="rounded-2xl border border-dashed border-slate-200 p-3 text-xs text-slate-500">Belum ada siswa di kelas yang Anda ajar.</p>
            @endforelse
            <a href="{{ route('guru.classes.index') }}" class="inline-flex items-center gap-1 text-[9px] font-extrabold text-emerald-700 hover:text-emerald-900">
                Kelola kelas & kode kelas <span aria-hidden="true">&rarr;</span>
            </a>
        </section>

        <section class="rounded-[22px] border border-slate-100 bg-white p-4 shadow-[0_8px_24px_rgba(15,23,42,0.06)] space-y-3">
            <div class="flex items-center gap-3">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-amber-100 text-amber-700" aria-hidden="true">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m13 2-9 12h7l-1 8 10-13h-7l1-7Z" />
                    </svg>
                </span>
                <h3 class="text-[11px] font-extrabold leading-tight text-slate-900">Aktivitas latihan siswa terkini</h3>
            </div>
            @forelse($recentActivities->take(2) as $act)
                <div class="rounded-2xl border border-dashed border-amber-200 bg-amber-50/50 p-3">
                    <p class="text-xs font-black text-slate-800">{{ $act->user->name ?? 'Siswa' }}</p>
                    <p class="text-[10px] text-slate-600">Topik: {{ $act->quiz->topic->title ?? 'Matematika' }}</p>
                    <div class="mt-1 flex items-center justify-between gap-2">
                        <span class="text-[10px] text-slate-400">{{ $act->created_at->diffForHumans() }}</span>
                        @if($act->is_correct)
                            <span class="text-[10px] font-black text-emerald-700">+{{ $act->points_earned }} poin</span>
                        @else
                            <span class="text-[10px] font-black text-rose-700">Perlu latihan</span>
                        @endif
                    </div>
                </div>
            @empty
                <p class="rounded-2xl border border-dashed border-amber-200 p-3 text-xs text-slate-500">Belum ada aktivitas pengerjaan soal terbaru.</p>
            @endforelse
            <a href="{{ route('guru.leaderboard') }}" class="inline-flex items-center gap-1 text-[9px] font-extrabold text-emerald-700 hover:text-emerald-900">
                Pantau progres siswa <span aria-hidden="true">&rarr;</span>
            </a>
        </section>

        <section class="rounded-[22px] border border-slate-100 bg-white p-4 shadow-[0_8px_24px_rgba(15,23,42,0.06)] space-y-3">
            <div class="flex items-center gap-3">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-violet-100 text-violet-700" aria-hidden="true">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2" />
                    </svg>
                </span>
                <h3 class="text-[11px] font-extrabold leading-tight text-slate-900">Sesi belajar terbaru</h3>
            </div>
            @forelse($recentSessions->take(2) as $session)
                <div class="rounded-2xl border border-dashed border-violet-200 bg-violet-50/40 p-3">
                    <p class="text-xs font-black text-slate-800">{{ $session->user->name ?? 'Siswa' }}</p>
                    <p class="text-[10px] text-slate-600">Topik: {{ $session->topic->title ?? 'Matematika' }}</p>
                    <div class="mt-1 flex items-center justify-between gap-2">
                        <span class="text-[10px] text-slate-400">{{ $session->dimulai_pada->diffForHumans() }}</span>
                        <span class="text-[10px] font-black text-amber-700">{{ $session->poin_diperoleh }} poin</span>
                    </div>
                </div>
            @empty
                <p class="rounded-2xl border border-dashed border-violet-200 p-3 text-xs text-slate-500">Belum ada riwayat sesi belajar dari siswa.</p>
            @endforelse
            <p class="text-[9px] font-medium text-slate-400">Siswa dari kelas yang Anda ajar</p>
        </section>
    </div>

    <!-- Students Table -->
    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between gap-3 bg-[#DBEAFE] px-5 py-4 sm:px-6">
            <div class="flex items-center gap-3">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-white text-blue-700" aria-hidden="true">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 19a4 4 0 0 0-8 0m11 0a3 3 0 0 0-4-2.83M5 19a3 3 0 0 1 4-2.83M15 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm5 3a2 2 0 1 1-4 0 2 2 0 0 1 4 0ZM8 10a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z" />
                    </svg>
                </span>
                <h2 class="text-sm sm:text-base font-extrabold text-slate-900">Daftar siswa & perkembangan</h2>
            </div>
            <span class="shrink-0 rounded-full bg-white px-3 py-1 text-[10px] font-black text-blue-800">{{ $students->count() }} siswa</span>
        </div>

        <div class="overflow-x-auto p-3 sm:p-5">
            <table class="w-full text-left text-sm font-semibold">
                <thead>
                    <tr class="border-b border-slate-100 text-[10px] text-slate-500 font-black uppercase tracking-wider">
                        <th class="py-3 px-4">Nama Siswa</th>
                        <th class="py-3 px-4">Kelas</th>
                        <th class="py-3 px-4">Total Poin</th>
                        <th class="py-3 px-4">Level</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($students as $student)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-xl bg-emerald-100 text-xs font-black text-emerald-800">{{ strtoupper(substr($student->name, 0, 1)) }}</span>
                                    <span class="min-w-0">
                                        <span class="block truncate font-extrabold text-slate-900">{{ $student->name }}</span>
                                        <span class="block truncate text-[10px] font-medium text-slate-400">{{ $student->email }}</span>
                                    </span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="whitespace-nowrap rounded-full bg-blue-100 px-2.5 py-1 text-[10px] font-black text-blue-800">
                                    Kelas {{ $student->kelas }} SD
                                </span>
                            </td>
                            <td class="whitespace-nowrap py-3.5 px-4 font-black text-amber-700">★ {{ number_format($student->total_score) }}</td>
                            <td class="py-3.5 px-4">
                                <span class="whitespace-nowrap rounded-full bg-amber-100 px-2.5 py-1 text-[10px] font-extrabold text-amber-900">
                                    Level {{ $student->level }} ({{ $student->level_name }})
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('guru.students.show', $student) }}" class="whitespace-nowrap rounded-full bg-slate-900 px-4 py-2 text-[10px] font-black text-white shadow-sm transition hover:bg-slate-700">
                                    Detail progres
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm font-semibold text-slate-400">
                                Belum ada siswa yang bergabung ke kelas Anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
