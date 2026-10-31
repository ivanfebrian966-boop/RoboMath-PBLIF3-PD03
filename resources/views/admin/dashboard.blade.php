@extends('layouts.admin')

@section('title', 'Dashboard Admin - RoboMath')

@section('content')
<div class="space-y-4 sm:space-y-5">
    <header class="rounded-[26px] bg-[#FFA502] px-6 py-6 text-white sm:px-8 sm:py-7">
        <h1 class="text-2xl font-black leading-tight sm:text-3xl">Dashboard &amp; Laporan Performa</h1>
        <p class="mt-2 max-w-2xl text-sm font-semibold leading-relaxed text-white/95">
            Ringkasan menyeluruh kesehatan sistem, statistik pengguna, diagnostik AI, serta laporan performa siswa SD.
        </p>
    </header>

    <section class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4" aria-label="Ringkasan sistem">
        <article class="flex items-center gap-4 rounded-3xl border border-amber-200 bg-white p-4 shadow-sm">
            <span class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-2xl bg-[#FFA502] text-white">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m3 8 9-5 9 5-9 5-9-5Zm3.5 2v5c3.4 2.7 7.6 2.7 11 0v-5M21 8v6" />
                </svg>
            </span>
            <div class="min-w-0">
                <p class="text-[10px] font-black uppercase tracking-wider text-slate-500">Total Siswa</p>
                <p class="text-3xl font-black leading-tight text-slate-800">{{ $totalSiswa }}</p>
                <p class="text-xs font-medium text-slate-500">Siswa aktif belajar</p>
            </div>
        </article>

        <article class="flex items-center gap-4 rounded-3xl border border-violet-200 bg-white p-4 shadow-sm">
            <span class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-2xl bg-violet-700 text-white">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="5" r="2.5" />
                    <circle cx="6" cy="18" r="2.5" />
                    <circle cx="18" cy="18" r="2.5" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5v3m0 0-6 4m6-4 6 4" />
                </svg>
            </span>
            <div class="min-w-0">
                <p class="text-[10px] font-black uppercase tracking-wider text-slate-500">Total Guru</p>
                <p class="text-3xl font-black leading-tight text-slate-800">{{ $totalGuru }}</p>
                <p class="text-xs font-medium text-slate-500">Pendidik pengampu kelas</p>
            </div>
        </article>

        <article class="flex items-center gap-4 rounded-3xl border border-emerald-200 bg-white p-4 shadow-sm">
            <span class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-2xl bg-emerald-700 text-white">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.5 5.5c3-.9 5.8-.4 8.5 1.5 2.7-1.9 5.5-2.4 8.5-1.5v13c-3-.9-5.8-.4-8.5 1.5-2.7-1.9-5.5-2.4-8.5-1.5v-13ZM12 7v13" />
                </svg>
            </span>
            <div class="min-w-0">
                <p class="text-[10px] font-black uppercase tracking-wider text-slate-500">Modul &amp; Bank Soal</p>
                <p class="text-3xl font-black leading-tight text-slate-800">
                    {{ $totalMateri }} <span class="text-sm font-bold text-slate-500">/ {{ $totalSoal }} soal</span>
                </p>
                <p class="text-xs font-medium text-slate-500">Unit materi &amp; butir soal kuis</p>
            </div>
        </article>

        <article class="flex items-center gap-4 rounded-3xl border border-blue-200 bg-white p-4 shadow-sm">
            <span class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-2xl bg-blue-600 text-white">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <rect x="4" y="4" width="6" height="6" rx="1.5" />
                    <rect x="14" y="4" width="6" height="6" rx="1.5" />
                    <rect x="4" y="14" width="6" height="6" rx="1.5" />
                    <rect x="14" y="14" width="6" height="6" rx="1.5" />
                </svg>
            </span>
            <div class="min-w-0">
                <p class="text-[10px] font-black uppercase tracking-wider text-slate-500">Akurasi Rata-rata</p>
                <p class="text-3xl font-black leading-tight text-slate-800">{{ $overallAccuracy }}%</p>
                <p class="text-xs font-medium text-slate-500">{{ number_format($totalAttempts) }} total percobaan latihan</p>
            </div>
        </article>
    </section>

    <div class="grid items-start gap-4 xl:grid-cols-[2fr_1fr]">
        <section class="overflow-hidden rounded-3xl border border-amber-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-amber-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div>
                    <h2 class="text-lg font-black text-slate-800">Laporan Performa &amp; Progres Siswa</h2>
                    <p class="mt-1 text-xs font-medium text-slate-500">Pantau skor, akurasi jawaban, dan kelulusan modul tiap siswa.</p>
                </div>
                <form action="{{ route('admin.dashboard') }}" method="GET" class="flex-shrink-0">
                    <label for="student-grade-filter" class="sr-only">Filter siswa berdasarkan kelas</label>
                    <select
                        id="student-grade-filter"
                        name="kelas"
                        onchange="this.form.submit()"
                        class="rounded-full border border-amber-200 bg-[#FBF8F2] px-4 py-2 text-xs font-bold text-slate-800 focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-100"
                    >
                        <option value="all" {{ $kelasFilter === 'all' ? 'selected' : '' }}>Semua Kelas</option>
                        @for($kelas = 1; $kelas <= 6; $kelas++)
                            <option value="{{ $kelas }}" {{ (string) $kelasFilter === (string) $kelas ? 'selected' : '' }}>
                                Kelas {{ $kelas }} SD
                            </option>
                        @endfor
                    </select>
                </form>
            </div>

            @if($performanceStudents->isEmpty())
                <div class="px-5 py-12 text-center text-sm font-semibold text-slate-500">
                    Belum ada siswa aktif pada kelas yang dipilih.
                </div>
            @else
                <div class="overflow-x-auto px-4 sm:px-6">
                    <table class="w-full min-w-[760px] text-left text-xs">
                        <thead>
                            <tr class="bg-[#FBF8F2] text-[10px] font-black uppercase tracking-wider text-slate-500">
                                <th scope="col" class="rounded-l-xl px-3 py-3">Nama Siswa</th>
                                <th scope="col" class="px-3 py-3">Tingkat</th>
                                <th scope="col" class="px-3 py-3">Total Skor</th>
                                <th scope="col" class="px-3 py-3">Materi</th>
                                <th scope="col" class="px-3 py-3">Latihan</th>
                                <th scope="col" class="rounded-r-xl px-3 py-3">Akurasi Jawaban</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-amber-100">
                            @php
                                $avatarColors = [
                                    'bg-orange-100 text-orange-800',
                                    'bg-blue-100 text-blue-800',
                                    'bg-emerald-100 text-emerald-800',
                                    'bg-violet-100 text-violet-800',
                                    'bg-rose-100 text-rose-800',
                                ];
                            @endphp
                            @foreach($performanceStudents as $student)
                                @php
                                    $avatarColor = $avatarColors[$loop->index % count($avatarColors)];
                                    $accuracyColor = $student->period_accuracy >= 80
                                        ? 'bg-emerald-700'
                                        : ($student->period_accuracy >= 50 ? 'bg-orange-700' : 'bg-slate-200');
                                    $accuracyTextColor = $student->period_accuracy >= 80
                                        ? 'text-emerald-700'
                                        : ($student->period_accuracy >= 50 ? 'text-orange-700' : 'text-rose-700');
                                @endphp
                                <tr class="transition hover:bg-amber-50/40">
                                    <td class="px-3 py-3">
                                        <div class="flex min-w-0 items-center gap-2.5">
                                            <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full text-[11px] font-black {{ $avatarColor }}">
                                                {{ strtoupper(mb_substr($student->name, 0, 2)) }}
                                            </span>
                                            <div class="min-w-0">
                                                <p class="max-w-[170px] truncate font-extrabold text-slate-800">{{ $student->name }}</p>
                                                <p class="max-w-[170px] truncate text-[10px] font-medium text-slate-500">{{ $student->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-3">
                                        <span class="rounded-full border border-amber-200 bg-amber-100 px-2.5 py-1 text-[10px] font-black text-amber-800">
                                            Kelas {{ $student->kelas ?? '-' }} SD
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-3 font-black text-orange-700">
                                        {{ number_format($student->total_score) }} Poin
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-3 font-semibold text-slate-700">
                                        {{ $student->completed_lessons }} Modul
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-3 font-semibold text-slate-700">
                                        {{ $student->period_attempts }} Soal
                                    </td>
                                    <td class="px-3 py-3">
                                        <div class="flex min-w-[120px] items-center gap-2">
                                            <div class="h-2 flex-1 overflow-hidden rounded-full bg-[#F1EBDD]">
                                                <div class="h-full rounded-full {{ $accuracyColor }}" style="width: {{ $student->period_accuracy }}%"></div>
                                            </div>
                                            <span class="w-8 text-right font-black {{ $accuracyTextColor }}">{{ $student->period_accuracy }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <footer class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 sm:px-6">
                    <p class="text-xs font-semibold text-slate-600">
                        Menampilkan {{ $performanceStudents->firstItem() }}–{{ $performanceStudents->lastItem() }} dari {{ $performanceStudents->total() }} siswa
                    </p>
                    @if($performanceStudents->hasPages())
                        <nav class="flex items-center gap-2" aria-label="Navigasi halaman siswa">
                            @if($performanceStudents->onFirstPage())
                                <span class="flex h-9 w-9 items-center justify-center rounded-full border border-amber-100 text-slate-300" aria-disabled="true" aria-label="Halaman sebelumnya">
                                    &lsaquo;
                                </span>
                            @else
                                <a href="{{ $performanceStudents->previousPageUrl() }}" class="flex h-9 w-9 items-center justify-center rounded-full border border-amber-100 text-slate-600 transition hover:border-emerald-300 hover:bg-emerald-50" aria-label="Halaman sebelumnya">
                                    &lsaquo;
                                </a>
                            @endif
                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-700 text-sm font-black text-white" aria-current="page">
                                {{ $performanceStudents->currentPage() }}
                            </span>
                            @if($performanceStudents->hasMorePages())
                                <a href="{{ $performanceStudents->nextPageUrl() }}" class="flex h-9 w-9 items-center justify-center rounded-full border border-amber-100 text-slate-600 transition hover:border-emerald-300 hover:bg-emerald-50" aria-label="Halaman berikutnya">
                                    &rsaquo;
                                </a>
                            @else
                                <span class="flex h-9 w-9 items-center justify-center rounded-full border border-amber-100 text-slate-300" aria-disabled="true" aria-label="Halaman berikutnya">
                                    &rsaquo;
                                </span>
                            @endif
                        </nav>
                    @endif
                </footer>
            @endif
        </section>

        <aside class="space-y-4">
            <section class="flex items-center gap-4 rounded-3xl border border-blue-200 bg-white p-5 shadow-sm sm:p-6">
                <span class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-2xl bg-blue-600 text-white">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m12 3 1.7 5.3L19 10l-5.3 1.7L12 17l-1.7-5.3L5 10l5.3-1.7L12 3Zm6 12 .9 2.1L21 18l-2.1.9L18 21l-.9-2.1L15 18l2.1-.9L18 15Z" />
                    </svg>
                </span>
                <div class="min-w-0">
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-500">Menunggu Review AI</p>
                    <p class="text-3xl font-black leading-tight text-slate-800">{{ $pendingAiRecommendations }}</p>
                    <p class="text-xs font-medium text-slate-500">soal dan rekomendasi menunggu persetujuan</p>
                    <a href="{{ route('admin.ai-recommendations.index') }}" class="mt-2 inline-flex items-center gap-2 rounded-full bg-emerald-700 px-4 py-2 text-xs font-black text-white transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        Buka Review AI
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </section>

            <section class="space-y-4 rounded-3xl border border-amber-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-lg font-black text-slate-800">Akurasi per Tingkat</h2>
                    <span class="whitespace-nowrap rounded-full bg-amber-100 px-3 py-1 text-[10px] font-black text-amber-800">
                        {{ number_format($totalAttempts) }} percobaan
                    </span>
                </div>

                @forelse($classPerformance as $class)
                    @php
                        $classAccuracyBarColor = $class->accuracy >= 80
                            ? 'bg-emerald-700'
                            : ($class->accuracy >= 50 ? 'bg-orange-700' : 'bg-slate-200');
                        $classAccuracyTextColor = $class->accuracy >= 80
                            ? 'text-emerald-700'
                            : ($class->accuracy >= 50 ? 'text-orange-700' : 'text-rose-700');
                    @endphp
                    <div class="space-y-3 border-b border-amber-100 pb-4 last:border-b-0 last:pb-0">
                        <div class="flex items-center justify-between gap-2">
                            <h3 class="text-sm font-extrabold text-slate-800">Kelas {{ $class->kelas }} SD</h3>
                            <p class="text-[11px] font-medium text-slate-500">
                                {{ $class->student_count }} siswa · {{ $class->attempt_count }} percobaan
                            </p>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <div class="h-2 flex-1 overflow-hidden rounded-full bg-[#F1EBDD]">
                                <div class="h-full rounded-full {{ $classAccuracyBarColor }}" style="width: {{ $class->accuracy }}%"></div>
                            </div>
                            <span class="w-9 text-right text-xs font-black {{ $classAccuracyTextColor }}">{{ $class->accuracy }}%</span>
                        </div>
                    </div>
                @empty
                    <p class="rounded-2xl bg-[#FBF8F2] px-4 py-5 text-center text-xs font-semibold text-slate-500">
                        Belum ada data siswa dengan tingkat kelas.
                    </p>
                @endforelse

                <div class="flex items-center justify-between rounded-2xl border border-amber-200 bg-[#FBF8F2] px-4 py-3">
                    <span class="text-xs font-bold text-slate-700">Rata-rata keseluruhan</span>
                    <span class="text-lg font-black text-orange-700">{{ $overallAccuracy }}%</span>
                </div>
            </section>
        </aside>
    </div>
</div>
@endsection
