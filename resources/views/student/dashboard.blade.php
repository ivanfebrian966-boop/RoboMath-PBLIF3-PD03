@extends('layouts.app')

@section('title', 'Dashboard Siswa - RoboMath')

@section('content')
<div class="space-y-5">
    {{-- ======================================================== --}}
    {{-- 1. HEADER HERO BANNER (Warna & Shadow Sesuai Sidebar)    --}}
    {{-- ======================================================== --}}
    <section class="relative overflow-hidden rounded-[28px] bg-[#EF383A] p-6 text-white shadow-[0_12px_28px_rgba(239,56,58,0.30)] sm:p-8">
        {{-- Floating math symbols dekoratif --}}
        <div class="pointer-events-none absolute inset-0 select-none overflow-hidden" aria-hidden="true">
            <span class="absolute right-40 top-4 text-5xl font-black text-white/15 select-none">+</span>
            <span class="absolute right-24 bottom-6 text-5xl font-black text-white/15 select-none">&times;</span>
            <span class="absolute right-72 bottom-3 text-4xl font-black text-white/15 select-none">&divide;</span>
        </div>
        <div class="relative z-10 max-w-xl space-y-3">
            <div class="space-y-1">
                <h1 class="text-3xl font-black sm:text-4xl">Halo, {{ $user->name }}!</h1>
                <p class="max-w-lg text-sm font-semibold leading-relaxed text-white/95 sm:text-base">
                    Siap petualangan matematika hari ini? Selesaikan soal kuis, kumpulkan lencana, dan raih skor tertinggi!
                </p>
            </div>
            <a href="{{ route('siswa.quiz') }}" class="inline-flex items-center gap-2 rounded-2xl bg-white px-5 py-3 text-sm font-black text-[#D9272E] shadow-sm transition hover:-translate-y-0.5 hover:bg-rose-50 focus:outline-none focus:ring-4 focus:ring-white/40">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8 5 11 7-11 7V5Z" />
                </svg>
                Mulai Latihan Soal
            </a>
        </div>
    </section>

    {{-- ======================================================== --}}
    {{-- 2. KPI STATS ROW (4 CARDS)                               --}}
    {{-- ======================================================== --}}
    <section class="grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-4" aria-label="Ringkasan progres">
        {{-- Card 1: Total Poin --}}
        <article class="flex items-center gap-3 rounded-3xl border border-slate-200/80 bg-white p-3.5 shadow-xs sm:gap-4 sm:p-4">
            <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-2xl bg-orange-700 text-white sm:h-12 sm:w-12 shadow-xs">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 3.75h7l5 5v11.5H7a2 2 0 0 1-2-2v-12.5a2 2 0 0 1 2-2Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 3.75v5h5M9 14h6m-6 3.5h6" />
                </svg>
            </span>
            <div class="min-w-0">
                <p class="text-[9px] font-black uppercase tracking-wide text-slate-500 sm:text-[10px]">Total Poin</p>
                <p class="text-2xl font-black leading-tight text-slate-800 sm:text-3xl">{{ number_format($user->total_score) }}</p>
                <p class="truncate text-[10px] font-semibold text-slate-500 sm:text-xs">Level {{ $user->level }} ({{ $user->level_name }})</p>
            </div>
        </article>

        {{-- Card 2: Akurasi Soal --}}
        <article class="flex items-center gap-3 rounded-3xl border border-slate-200/80 bg-white p-3.5 shadow-xs sm:gap-4 sm:p-4">
            <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-2xl bg-[#059669] text-white sm:h-12 sm:w-12 shadow-xs">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="9" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.5 12 2.25 2.25L15.75 9" />
                </svg>
            </span>
            <div class="min-w-0">
                <p class="text-[9px] font-black uppercase tracking-wide text-slate-500 sm:text-[10px]">Akurasi Soal</p>
                <p class="text-2xl font-black leading-tight text-slate-800 sm:text-3xl">{{ $accuracy }}%</p>
                <p class="truncate text-[10px] font-semibold text-slate-500 sm:text-xs">{{ $totalAttempts }} soal dikerjakan</p>
            </div>
        </article>

        {{-- Card 3: Materi Tuntas --}}
        <article class="flex items-center gap-3 rounded-3xl border border-slate-200/80 bg-white p-3.5 shadow-xs sm:gap-4 sm:p-4">
            <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-2xl bg-[#2563EB] text-white sm:h-12 sm:w-12 shadow-xs">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.5c3.1-.9 5.85-.35 8.25 1.65 2.4-2 5.15-2.55 8.25-1.65v13c-3.1-.9-5.85-.35-8.25 1.65-2.4-2-5.15-2.55-8.25-1.65v-13Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.15v13" />
                </svg>
            </span>
            <div class="min-w-0">
                <p class="text-[9px] font-black uppercase tracking-wide text-slate-500 sm:text-[10px]">Materi Tuntas</p>
                <p class="text-2xl font-black leading-tight text-slate-800 sm:text-3xl">{{ $completedLessons }} / {{ $totalLessons }}</p>
                <p class="truncate text-[10px] font-semibold text-slate-500 sm:text-xs">Modul dikuasai</p>
            </div>
        </article>

        {{-- Card 4: Lencana Koleksi --}}
        <article class="flex items-center gap-3 rounded-3xl border border-slate-200/80 bg-white p-3.5 shadow-xs sm:gap-4 sm:p-4">
            <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-2xl bg-[#D946EF] text-white sm:h-12 sm:w-12 shadow-xs">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m12 3 2.7 5.47 6.03.88-4.36 4.25 1.03 6.01L12 16.77l-5.4 2.84 1.03-6.01-4.36-4.25 6.03-.88L12 3Z" />
                </svg>
            </span>
            <div class="min-w-0">
                <p class="text-[9px] font-black uppercase tracking-wide text-slate-500 sm:text-[10px]">Lencana Koleksi</p>
                <p class="text-2xl font-black leading-tight text-slate-800 sm:text-3xl">{{ $totalBadges }} / {{ $allBadges->count() }}</p>
                <p class="truncate text-[10px] font-semibold text-slate-500 sm:text-xs">Bintang prestasi</p>
            </div>
        </article>
    </section>

    {{-- ======================================================== --}}
    {{-- 3. KOLEKSI LENCANA & PRESTASI (Sesuai Mockup Gambar)      --}}
    {{-- ======================================================== --}}
    <section class="bg-white rounded-3xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight">
                Koleksi lencana &amp; prestasi
            </h2>
            <span class="px-3.5 py-1 rounded-full text-xs font-bold bg-[#EBE9FE] text-[#6D28D9]">
                {{ $totalBadges }} dari {{ $allBadges->count() }} terbuka
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5">
            @foreach($allBadges as $b)
                @php
                    $isEarned = in_array($b->id, $earnedIds);
                    $cleanName = trim(preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]/u', '', $b->name));
                @endphp
                <article class="flex flex-col items-center text-center rounded-3xl p-4 transition-all {{ $isEarned ? 'border-2 border-amber-300 bg-[#FFFDF5] shadow-xs' : 'border border-slate-200/80 bg-white' }}">
                    {{-- Icon Container --}}
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-2.5 {{ $isEarned ? 'bg-[#FEF3C7] text-[#D97706]' : 'bg-slate-100 text-slate-400' }}">
                        @if(str_contains($cleanName, 'Langkah Pertama'))
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-4m0 0V5a2 2 0 0 1 2-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 0 0-2 2Zm0 0h6"/>
                            </svg>
                        @elseif(str_contains($cleanName, 'Pencari Jawaban'))
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="9"/>
                                <circle cx="12" cy="12" r="5"/>
                                <circle cx="12" cy="12" r="1.5" fill="currentColor"/>
                            </svg>
                        @elseif(str_contains($cleanName, 'Bintang Sekolah'))
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/>
                            </svg>
                        @elseif(str_contains($cleanName, 'Master Logika'))
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-1.5m-3.2-3.3A5.5 5.5 0 1 1 15.2 13.2c-.7.7-1.2 1.6-1.2 2.6H10c0-1-.5-1.9-1.2-2.6ZM9.5 20.5h5"/>
                            </svg>
                        @elseif(str_contains($cleanName, 'Raja Algoritma'))
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 18h18M4 15l2-8 5 4 5-4 2 8H4Z"/>
                            </svg>
                        @else
                            <span class="text-2xl {{ $isEarned ? '' : 'grayscale' }}">{{ $b->icon ?? '⭐' }}</span>
                        @endif
                    </div>

                    {{-- Title --}}
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900 leading-tight">
                        {{ $cleanName }}
                    </h3>

                    {{-- Description --}}
                    <p class="text-[10px] sm:text-[11px] text-slate-500 leading-tight mt-1 mb-3 line-clamp-2 min-h-[26px]">
                        {{ $b->description ?? 'Selesaikan tantangan untuk membuka.' }}
                    </p>

                    {{-- Status Pill (Diraih vs Terkunci) --}}
                    <span class="mt-auto px-3.5 py-1 rounded-full text-[11px] font-bold {{ $isEarned ? 'bg-[#DCFCE7] text-[#16A34A]' : 'bg-slate-100 text-slate-500' }}">
                        {{ $isEarned ? 'Diraih' : 'Terkunci' }}
                    </span>
                </article>
            @endforeach
        </div>
    </section>

    {{-- ======================================================== --}}
    {{-- 4. DUA KOLOM: MODUL PEMBELAJARAN (KIRI) & PINTASAN (KANAN) --}}
    {{-- ======================================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">

        {{-- ==================================================== --}}
        {{-- KOLOM KIRI: MODUL BELAJAR MATEMATIKA SD              --}}
        {{-- ==================================================== --}}
        <section class="lg:col-span-8 bg-white rounded-3xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight">
                    Modul belajar matematika SD
                </h2>
                <span class="px-3.5 py-1 rounded-full text-xs font-bold bg-[#FEF3C7] text-[#92400E]">
                    {{ $topics->total() }} modul
                </span>
            </div>

            {{-- 2x2 Grid Modul --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @forelse($topics as $topic)
                    @php
                        // Color styling per kategori topik
                        $categoryColors = [
                            'urutan' => [
                                'border_t' => 'border-t-[#EF4444]',
                                'icon_bg'  => 'bg-red-100 text-red-600',
                                'badge'    => 'bg-rose-100 text-rose-700',
                                'bar'      => 'bg-[#EF4444]',
                                'default_title' => 'Urutan (Sequencing)',
                            ],
                            'pola' => [
                                'border_t' => 'border-t-[#F59E0B]',
                                'icon_bg'  => 'bg-amber-100 text-amber-600',
                                'badge'    => 'bg-amber-100 text-amber-700',
                                'bar'      => 'bg-[#F59E0B]',
                                'default_title' => 'Pola & Pattern',
                            ],
                            'percabangan' => [
                                'border_t' => 'border-t-[#10B981]',
                                'icon_bg'  => 'bg-emerald-100 text-emerald-600',
                                'badge'    => 'bg-emerald-100 text-emerald-700',
                                'bar'      => 'bg-[#10B981]',
                                'default_title' => 'Percabangan (If-Else)',
                            ],
                            'pengulangan' => [
                                'border_t' => 'border-t-[#3B82F6]',
                                'icon_bg'  => 'bg-blue-100 text-blue-600',
                                'badge'    => 'bg-blue-100 text-blue-700',
                                'bar'      => 'bg-[#3B82F6]',
                                'default_title' => 'Pengulangan (Looping)',
                            ],
                            'dekomposisi' => [
                                'border_t' => 'border-t-[#8B5CF6]',
                                'icon_bg'  => 'bg-purple-100 text-purple-600',
                                'badge'    => 'bg-purple-100 text-purple-700',
                                'bar'      => 'bg-[#8B5CF6]',
                                'default_title' => 'Dekomposisi',
                            ],
                        ];

                        $cat = $categoryColors[$topic->category] ?? [
                            'border_t' => 'border-t-[#3B82F6]',
                            'icon_bg'  => 'bg-blue-100 text-blue-600',
                            'badge'    => 'bg-blue-100 text-blue-700',
                            'bar'      => 'bg-[#3B82F6]',
                            'default_title' => $topic->title,
                        ];
                    @endphp

                    <article class="flex flex-col justify-between rounded-2xl border border-slate-200/80 border-t-4 {{ $cat['border_t'] }} p-4 bg-white shadow-xs min-h-[160px] transition-all hover:shadow-sm">
                        <div class="space-y-2.5">
                            {{-- Row: Icon + Title + Badge --}}
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <span class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 {{ $cat['icon_bg'] }}">
                                        @if($topic->category === 'urutan')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                                            </svg>
                                        @elseif($topic->category === 'pola')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/>
                                            </svg>
                                        @elseif($topic->category === 'percabangan')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 17h.01M17 12h.01M7 7v10m0-5h6a4 4 0 0 1 4 4v0"/>
                                            </svg>
                                        @elseif($topic->category === 'pengulangan')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>
                                            </svg>
                                        @endif
                                    </span>
                                    <a href="{{ route('siswa.topics.show', $topic) }}" class="min-w-0 font-extrabold text-xs sm:text-sm text-slate-900 hover:text-blue-600 transition leading-snug">
                                        {{ $topic->title }}
                                    </a>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold flex-shrink-0 {{ $cat['badge'] }}">
                                    {{ $topic->lessons_count }} materi
                                </span>
                            </div>

                            {{-- Description --}}
                            <p class="text-[11px] text-slate-500 leading-relaxed line-clamp-2 min-h-[32px]">
                                {{ $topic->description }}
                            </p>
                        </div>

                        {{-- Progress Row --}}
                        <div class="pt-3 mt-1 border-t border-slate-100 space-y-1.5">
                            <div class="flex items-center justify-between text-[11px] text-slate-500 font-semibold">
                                <span>Progres belajar</span>
                                <span>{{ $topic->progress_percent }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500 {{ $cat['bar'] }}"
                                     style="width: {{ $topic->progress_percent }}%;"></div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-2 py-8 text-center text-slate-400">
                        <p class="text-sm font-semibold">Modul baru dari gurumu akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination Footer (Sesuai Mockup) --}}
            <div class="flex items-center justify-between pt-2 text-xs font-semibold text-slate-500">
                <p>
                    Menampilkan {{ $topics->firstItem() ?? 0 }}–{{ $topics->lastItem() ?? 0 }} dari {{ $topics->total() }} modul
                </p>

                <div class="flex items-center gap-1.5">
                    @if($topics->onFirstPage())
                        <span class="w-8 h-8 rounded-full border border-slate-200 text-slate-300 flex items-center justify-center cursor-not-allowed">
                            &lsaquo;
                        </span>
                    @else
                        <a href="{{ $topics->previousPageUrl() }}" class="w-8 h-8 rounded-full border border-slate-200 text-slate-700 hover:bg-slate-100 flex items-center justify-center transition">
                            &lsaquo;
                        </a>
                    @endif

                    <span class="w-8 h-8 rounded-full bg-[#0B1527] text-white flex items-center justify-center font-bold text-xs shadow-xs">
                        {{ $topics->currentPage() }}
                    </span>

                    @if($topics->hasMorePages())
                        <a href="{{ $topics->nextPageUrl() }}" class="w-8 h-8 rounded-full border border-slate-200 text-slate-700 hover:bg-slate-100 flex items-center justify-center transition">
                            &rsaquo;
                        </a>
                    @else
                        <span class="w-8 h-8 rounded-full border border-slate-200 text-slate-300 flex items-center justify-center cursor-not-allowed">
                            &rsaquo;
                        </span>
                    @endif
                </div>
            </div>
        </section>

        {{-- ==================================================== --}}
        {{-- KOLOM KANAN: TANTANGAN & PINTASAN CEPAT             --}}
        {{-- ==================================================== --}}
        <div class="lg:col-span-4 space-y-4">

            {{-- 1. Kartu Tantangan Hari Ini (Amber / Kuning) --}}
            <article class="bg-[#FFF8EC] border border-amber-200 rounded-3xl p-4 sm:p-5 flex items-center justify-between gap-3 shadow-xs">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="w-11 h-11 rounded-2xl bg-[#FF9500] text-white flex items-center justify-center flex-shrink-0 shadow-xs">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                        </svg>
                    </span>
                    <div class="min-w-0">
                        <h3 class="text-xs sm:text-sm font-extrabold text-slate-900 leading-snug">Tantangan hari ini</h3>
                        <p class="text-[11px] text-slate-600 mt-0.5 leading-snug">Selesaikan 1 latihan untuk bonus poin.</p>
                    </div>
                </div>
                <a href="{{ route('siswa.quiz') }}"
                   class="px-4 py-2 rounded-full bg-[#0B1527] hover:bg-slate-800 text-white font-bold text-xs transition-all shadow-xs flex-shrink-0">
                    Mulai
                </a>
            </article>

            {{-- 2. Kartu Pintasan Cepat --}}
            <section class="bg-white border border-slate-200/80 rounded-3xl p-5 sm:p-6 shadow-xs space-y-3">
                <div class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-400 mb-2">
                    PINTASAN CEPAT
                </div>

                {{-- Item 1: Latihan Soal AI --}}
                <a href="{{ route('siswa.quiz') }}"
                   class="flex items-center justify-between p-2 rounded-2xl hover:bg-slate-50 transition-colors group">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <div class="w-10 h-10 rounded-2xl bg-[#E0EDFF] text-[#2563EB] flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14 5 5 5M4 20l4.5-1 10-10a2.12 2.12 0 0 0-3-3l-10 10L4 20Z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-blue-600 transition">
                                Latihan Soal AI
                            </h4>
                            <p class="text-[11px] text-slate-400 truncate">
                                Uji kemampuan dengan soal AI
                            </p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-600 transition flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                    </svg>
                </a>

                {{-- Item 2: Masukkan Kode Kelas --}}
                <button type="button"
                        @click="$dispatch('open-class-join-modal')"
                        class="w-full flex items-center justify-between p-2 rounded-2xl hover:bg-slate-50 transition-colors text-left group cursor-pointer">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <div class="w-10 h-10 rounded-2xl bg-[#E1F8EC] text-[#059669] flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-emerald-600 transition">
                                Masukkan Kode Kelas
                            </h4>
                            <p class="text-[11px] text-slate-400 truncate">
                                Buka soal topik dari guru
                            </p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-600 transition flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                    </svg>
                </button>

                {{-- Item 3: Progres & Leaderboard --}}
                <a href="{{ route('siswa.leaderboard') }}"
                   class="flex items-center justify-between p-2 rounded-2xl hover:bg-slate-50 transition-colors group">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <div class="w-10 h-10 rounded-2xl bg-[#FEF3C7] text-[#D97706] flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-amber-600 transition">
                                Progres &amp; Leaderboard
                            </h4>
                            <p class="text-[11px] text-slate-400 truncate">
                                Lihat grafik dan peringkat
                            </p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-600 transition flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                    </svg>
                </a>

                {{-- Item 4: Profil Akun Saya --}}
                <a href="{{ route('profil.show') }}"
                   class="flex items-center justify-between p-2 rounded-2xl hover:bg-slate-50 transition-colors group">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <div class="w-10 h-10 rounded-2xl bg-[#DCFCE7] text-[#059669] flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-emerald-600 transition">
                                Profil Akun Saya
                            </h4>
                            <p class="text-[11px] text-slate-400 truncate">
                                Ubah nama, email, dan sandi
                            </p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-600 transition flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                    </svg>
                </a>
            </section>

        </div>
    </div>
</div>
@endsection
