@extends('layouts.app')

@section('title', 'Progress Siswa - Guru RoboMath')

@section('content')
<div class="mx-auto max-w-6xl space-y-4 sm:space-y-5">
    <header class="flex flex-col items-start justify-between gap-4 rounded-3xl bg-[#F59E0B] px-5 py-5 text-white shadow-lg sm:flex-row sm:items-center sm:px-7 sm:py-6">
        <div class="space-y-1.5">
            <h1 class="text-3xl font-black leading-tight sm:text-4xl">Leaderboard</h1>
            <p class="max-w-xl text-xs font-semibold leading-relaxed text-amber-50 sm:text-sm">Pantau capaian materi, akurasi kuis, dan peringkat siswa. Unduh laporan performa kelas kapan saja.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('guru.laporan.download', ['kelas' => $kelasFilter]) }}" class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-4 py-2.5 text-xs font-black text-white transition hover:bg-slate-700 active:scale-95">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-1m-4-4-4 4m0 0-4-4m4 4V4"/></svg>
                <span>Unduh Excel</span>
            </a>
            <a href="{{ route('guru.laporan.pdf', ['kelas' => $kelasFilter]) }}" class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2.5 text-xs font-black text-slate-900 transition hover:bg-amber-50 active:scale-95">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7 3.75h7l5 5v11.5H7a2 2 0 0 1-2-2v-12.5a2 2 0 0 1 2-2Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M14 3.75v5h5M8.5 14h7m-7 3h5"/></svg>
                <span>Unduh PDF</span>
            </a>
        </div>
    </header>

    <section class="grid grid-cols-1 overflow-hidden rounded-3xl border border-white bg-white shadow-sm sm:grid-cols-3" aria-label="Ringkasan performa siswa">
        <div class="space-y-0.5 border-b border-slate-100 px-5 py-4 sm:border-b-0 sm:border-r">
            <span class="block text-[10px] font-black uppercase tracking-wider text-slate-500">Total siswa terpantau</span>
            <div class="flex items-baseline gap-1.5"><span class="text-3xl font-black text-rose-500">{{ $totalSiswa }}</span><span class="text-xs font-bold text-slate-500">siswa</span></div>
            <p class="text-[10px] text-slate-500">Siswa aktif terdaftar</p>
        </div>
        <div class="space-y-0.5 border-b border-slate-100 px-5 py-4 sm:border-b-0 sm:border-r">
            <span class="block text-[10px] font-black uppercase tracking-wider text-slate-500">Rata-rata skor</span>
            <div class="flex items-baseline gap-1.5"><span class="text-3xl font-black text-amber-700">{{ number_format($avgScore) }}</span><span class="text-xs font-bold text-slate-500">poin</span></div>
            <p class="text-[10px] text-slate-500">Akumulasi hasil latihan</p>
        </div>
        <div class="space-y-0.5 px-5 py-4">
            <span class="block text-[10px] font-black uppercase tracking-wider text-slate-500">Rata-rata akurasi</span>
            <div class="flex items-baseline gap-1.5"><span class="text-3xl font-black text-blue-700">{{ round($avgAccuracy) }}%</span></div>
            <p class="text-[10px] text-slate-500">Ketepatan jawaban kuis</p>
        </div>
    </section>

    <nav class="flex flex-wrap items-center gap-2" aria-label="Filter tingkat kelas">
        <span class="mr-1 text-[10px] font-black uppercase tracking-wider text-slate-500">Tingkat kelas</span>
        <a href="{{ route('guru.leaderboard', ['kelas' => 'all']) }}"
           @if($kelasFilter === 'all') aria-current="page" @endif
           class="rounded-full border px-3.5 py-1.5 text-[10px] font-black transition {{ $kelasFilter === 'all' ? 'border-slate-900 bg-slate-900 text-white' : 'border-slate-200 bg-white text-slate-700 hover:bg-amber-50' }}">
            Semua kelas
        </a>
        @for($k = 1; $k <= 6; $k++)
            <a href="{{ route('guru.leaderboard', ['kelas' => $k]) }}"
               @if((string) $kelasFilter === (string) $k) aria-current="page" @endif
               class="rounded-full border px-3.5 py-1.5 text-[10px] font-black transition {{ (string) $kelasFilter === (string) $k ? 'border-slate-900 bg-slate-900 text-white' : 'border-slate-200 bg-white text-slate-700 hover:bg-amber-50' }}">
                Kelas {{ $k }}
            </a>
        @endfor
    </nav>

    <div class="grid grid-cols-1 items-start gap-3 sm:gap-4 md:grid-cols-[minmax(0,1.8fr)_minmax(230px,1fr)]">
        <section class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-sm">
            <div class="flex items-center justify-between gap-3 bg-amber-100 px-4 py-3 sm:px-5">
                <h2 class="text-sm font-black text-slate-900 sm:text-base">Papan peringkat siswa</h2>
                <span class="shrink-0 rounded-full bg-white px-3 py-1 text-[10px] font-black text-amber-800">{{ $leaderboard->count() }} siswa</span>
            </div>

            @if($leaderboard->isEmpty())
                <div class="p-10 text-center text-sm font-bold text-slate-500">Belum ada data siswa untuk kriteria ini.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[450px] text-left">
                        <thead class="border-b border-slate-100 bg-[#FAF8F3] text-[9px] font-black uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-4 py-3">#</th>
                                <th class="px-3 py-3">Siswa</th>
                                <th class="px-3 py-3">Akurasi</th>
                                <th class="px-3 py-3">Skor</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-amber-100">
                            @foreach($leaderboard as $index => $student)
                                <tr class="transition hover:bg-amber-50/40">
                                    <td class="px-4 py-3.5">
                                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg text-[10px] font-black {{ $index === 0 ? 'bg-amber-100 text-amber-800' : ($index === 1 ? 'bg-slate-200 text-slate-700' : ($index === 2 ? 'bg-orange-100 text-orange-800' : 'bg-slate-100 text-slate-600')) }}">{{ $index + 1 }}</span>
                                    </td>
                                    <td class="px-3 py-3.5">
                                        <div class="max-w-[165px] truncate text-[11px] font-black text-slate-900">{{ $student->name }}</div>
                                        <div class="mt-0.5 max-w-[165px] truncate text-[9px] leading-relaxed text-slate-500">{{ $student->email }}</div>
                                        <div class="text-[9px] leading-relaxed text-slate-500">Kelas {{ $student->kelas ?? '-' }} SD · {{ $student->completed_lessons }} modul tuntas</div>
                                    </td>
                                    <td class="px-3 py-3.5">
                                        <div class="flex items-center gap-1.5">
                                            <div class="h-1.5 w-12 overflow-hidden rounded-full bg-[#F0E8D8] sm:w-14" role="progressbar" aria-label="Akurasi soal {{ $student->name }}" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $student->accuracy }}">
                                                <div class="h-full rounded-full {{ $student->accuracy >= 80 ? 'bg-emerald-500' : ($student->accuracy >= 50 ? 'bg-amber-500' : 'bg-rose-500') }}" style="width: {{ $student->accuracy }}%"></div>
                                            </div>
                                            <span class="text-[10px] font-black text-blue-700">{{ $student->accuracy }}%</span>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-3.5">
                                        <span class="inline-flex items-center gap-1 text-[10px] font-black text-amber-700">
                                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="m12 2.5 2.93 5.94 6.56.95-4.75 4.63 1.12 6.54L12 17.47l-5.86 3.09 1.12-6.54L2.5 9.39l6.57-.95L12 2.5Z"/></svg>
                                            {{ number_format($student->total_score) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3.5 text-right">
                                        <a href="{{ route('guru.students.show', $student) }}" class="inline-flex rounded-full bg-slate-900 px-3 py-2 text-[9px] font-black text-white transition hover:bg-slate-700">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        <aside class="space-y-3">
            <section class="rounded-3xl border border-amber-300 bg-white p-4 shadow-sm" aria-labelledby="class-champion-title">
                <div class="flex items-center gap-2.5">
                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-amber-500 text-slate-950">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 21h8m-4-4v4m-5-9.5V7a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v4.5a5 5 0 0 1-5 5 5 5 0 0 1-5-5Zm0-2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h2m10 4.5h2a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/></svg>
                    </span>
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-wider text-amber-800">Juara kelas</p>
                        <h2 id="class-champion-title" class="text-sm font-black text-slate-900">{{ $topStudent?->name ?? 'Belum ada juara' }}</h2>
                    </div>
                </div>
                @if($topStudent)
                    <p class="mt-2 text-3xl font-black leading-none text-amber-700">{{ number_format($topStudent->total_score) }} <span class="text-xs">poin</span></p>
                    @if($runnerUp)
                        <p class="mt-3 text-[10px] leading-relaxed text-amber-950">Unggul {{ number_format($topStudent->total_score - $runnerUp->total_score) }} poin dari peringkat kedua.</p>
                    @else
                        <p class="mt-3 text-[10px] leading-relaxed text-amber-950">Peringkat teratas pada kelas yang dipilih.</p>
                    @endif
                @else
                    <p class="mt-3 text-[10px] text-slate-500">Belum ada siswa pada kelas ini.</p>
                @endif
            </section>

            <section class="rounded-3xl border border-slate-100 bg-white p-4 shadow-sm" aria-labelledby="attention-title">
                <div class="mb-3 flex items-center gap-2">
                    <span class="grid h-8 w-8 place-items-center rounded-xl bg-rose-100 text-rose-600" aria-hidden="true">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.86 2.82 17.2A2 2 0 0 0 4.57 20h14.86a2 2 0 0 0 1.75-2.8L13.7 3.86a2 2 0 0 0-3.4 0Z"/></svg>
                    </span>
                    <h2 id="attention-title" class="text-xs font-black text-slate-900">Perlu perhatian</h2>
                </div>
                <ul class="space-y-2">
                    @forelse($studentsNeedingAttention as $student)
                        <li class="flex items-center justify-between gap-2 rounded-xl border border-orange-100 bg-[#FAF8F3] px-2.5 py-2">
                            <a href="{{ route('guru.students.show', $student) }}" class="min-w-0 truncate text-[9px] font-bold text-slate-800 hover:text-amber-700">{{ $student->name }}</a>
                            <span class="shrink-0 rounded-full bg-rose-100 px-2 py-1 text-[8px] font-black text-rose-700">Akurasi {{ $student->accuracy }}%</span>
                        </li>
                    @empty
                        <li class="rounded-xl border border-emerald-100 bg-emerald-50 px-3 py-2 text-[10px] font-semibold text-emerald-800">Tidak ada siswa dengan akurasi 0%.</li>
                    @endforelse
                </ul>
            </section>
        </aside>
    </div>
</div>
@endsection
