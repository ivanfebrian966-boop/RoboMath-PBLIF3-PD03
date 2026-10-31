@extends('layouts.app')

@section('title', 'Dashboard Siswa - RoboMath')

@section('content')
<div class="space-y-4 sm:space-y-5">
    <section class="relative overflow-hidden rounded-[28px] bg-[#EF383A] p-6 text-white shadow-[0_8px_24px_rgba(239,56,58,0.22)] sm:p-8">
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

    <section class="grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-4" aria-label="Ringkasan progres">
        <article class="flex items-center gap-3 rounded-3xl border-2 border-amber-200 bg-white p-3.5 shadow-sm sm:gap-4 sm:p-4">
            <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-2xl bg-orange-700 text-white sm:h-12 sm:w-12">
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

        <article class="flex items-center gap-3 rounded-3xl border-2 border-emerald-200 bg-white p-3.5 shadow-sm sm:gap-4 sm:p-4">
            <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-2xl bg-emerald-700 text-white sm:h-12 sm:w-12">
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

        <article class="flex items-center gap-3 rounded-3xl border-2 border-blue-200 bg-white p-3.5 shadow-sm sm:gap-4 sm:p-4">
            <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-2xl bg-blue-600 text-white sm:h-12 sm:w-12">
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

        <article class="flex items-center gap-3 rounded-3xl border-2 border-pink-200 bg-white p-3.5 shadow-sm sm:gap-4 sm:p-4">
            <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-2xl bg-pink-700 text-white sm:h-12 sm:w-12">
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

    <div class="grid items-start gap-4 lg:grid-cols-[1.4fr_1fr]">
        <section class="space-y-4 rounded-3xl border-2 border-amber-200 bg-white p-4 shadow-sm sm:p-5">
            <div class="flex items-center justify-between gap-3">
                <h2 class="text-lg font-black text-slate-900 sm:text-xl">Modul Belajar Matematika SD</h2>
                <span class="whitespace-nowrap rounded-full bg-amber-100 px-3 py-1 text-[10px] font-black text-amber-800 sm:text-xs">
                    {{ $topics->total() }} Modul
                </span>
            </div>

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                @forelse($topics as $topic)
                    <article class="flex min-h-[148px] flex-col justify-between gap-3 rounded-[24px] border border-[#F2E6D5] border-t-4 p-3.5 sm:p-4" style="border-top-color: {{ $topic->color }};">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2.5">
                                <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-2xl text-xl text-white" style="background-color: {{ $topic->color }};">{{ $topic->icon }}</span>
                                <a href="{{ route('siswa.topics.show', $topic) }}" class="min-w-0 flex-1 text-sm font-black leading-tight text-slate-800 hover:text-rose-600 focus:outline-none focus:underline">
                                    {{ $topic->title }}
                                </a>
                                <span class="whitespace-nowrap rounded-full bg-amber-100 px-2.5 py-1 text-[10px] font-black text-amber-800">{{ $topic->lessons_count }} Materi</span>
                            </div>
                            <p class="line-clamp-2 text-xs font-medium leading-relaxed text-slate-600">{{ $topic->description }}</p>
                        </div>
                        <div class="space-y-1.5">
                            <div class="flex justify-between text-[11px] font-bold text-slate-700">
                                <span>Progress Belajar</span>
                                <span>{{ $topic->progress_percent }}%</span>
                            </div>
                            <div class="h-1.5 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full transition-all duration-500" style="width: {{ $topic->progress_percent }}%; background-color: {{ $topic->color }};"></div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-3xl border-2 border-dashed border-[#E9DDCA] px-4 py-6 text-center text-xs font-semibold text-slate-500 sm:col-span-2">
                        Modul baru dari gurumu akan muncul di sini.
                    </div>
                @endforelse
            </div>

            @if($topics->hasPages())
                <div class="flex flex-wrap items-center justify-between gap-3 pt-1">
                    <p class="text-xs font-semibold text-slate-600">
                        Menampilkan {{ $topics->firstItem() }}–{{ $topics->lastItem() }} dari {{ $topics->total() }} modul
                    </p>
                    <nav class="flex items-center gap-2" aria-label="Navigasi modul">
                        @if($topics->onFirstPage())
                            <span class="flex h-9 w-9 items-center justify-center rounded-full border border-[#F2E6D5] text-slate-300" aria-disabled="true" aria-label="Halaman sebelumnya">
                                &lsaquo;
                            </span>
                        @else
                            <a href="{{ $topics->previousPageUrl() }}" class="flex h-9 w-9 items-center justify-center rounded-full border border-[#F2E6D5] text-slate-600 transition hover:border-rose-300 hover:bg-rose-50 hover:text-rose-600" aria-label="Halaman sebelumnya">
                                &lsaquo;
                            </a>
                        @endif
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#00815F] text-sm font-black text-white" aria-current="page">{{ $topics->currentPage() }}</span>
                        @if($topics->hasMorePages())
                            <a href="{{ $topics->nextPageUrl() }}" class="flex h-9 w-9 items-center justify-center rounded-full border border-[#F2E6D5] text-slate-600 transition hover:border-rose-300 hover:bg-rose-50 hover:text-rose-600" aria-label="Halaman berikutnya">
                                &rsaquo;
                            </a>
                        @else
                            <span class="flex h-9 w-9 items-center justify-center rounded-full border border-[#F2E6D5] text-slate-300" aria-disabled="true" aria-label="Halaman berikutnya">
                                &rsaquo;
                            </span>
                        @endif
                    </nav>
                </div>
            @endif
        </section>

        <div class="space-y-4">
            <nav class="grid grid-cols-3 gap-2.5" aria-label="Menu cepat">
                <a href="{{ route('siswa.quiz') }}" class="group flex min-h-28 flex-col items-start justify-between rounded-3xl border-2 border-blue-200 bg-white p-3 shadow-sm transition hover:-translate-y-1 hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-100 text-blue-700 transition group-hover:scale-105">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14 5 5 5M4 20l4.5-1 10-10a2.12 2.12 0 0 0-3-3l-10 10L4 20Z" />
                        </svg>
                    </span>
                    <span class="text-xs font-black leading-tight text-slate-800">Latihan Soal AI</span>
                </a>
                <a href="{{ route('siswa.leaderboard') }}" class="group flex min-h-28 flex-col items-start justify-between rounded-3xl border-2 border-amber-200 bg-white p-3 shadow-sm transition hover:-translate-y-1 hover:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-300">
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-100 text-amber-700 transition group-hover:scale-105">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 19V9m5 10V5m5 14v-7m5 7V3M2 19h20" />
                        </svg>
                    </span>
                    <span class="text-xs font-black leading-tight text-slate-800">Progress & Leaderboard</span>
                </a>
                <a href="{{ route('profil.show') }}" class="group flex min-h-28 flex-col items-start justify-between rounded-3xl border-2 border-emerald-200 bg-white p-3 shadow-sm transition hover:-translate-y-1 hover:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700 transition group-hover:scale-105">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="12" cy="8" r="3.5" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 20a7.5 7.5 0 0 1 15 0" />
                        </svg>
                    </span>
                    <span class="text-xs font-black leading-tight text-slate-800">Profil Akun Saya</span>
                </a>
            </nav>

            <section class="space-y-4 rounded-3xl border-2 border-amber-200 bg-white p-4 shadow-sm sm:p-5">
                <div class="flex items-center justify-between gap-2">
                    <h2 class="text-base font-black text-slate-900 sm:text-lg">Koleksi Lencana & Prestasi</h2>
                    <span class="whitespace-nowrap rounded-full bg-rose-100 px-3 py-1 text-[10px] font-black text-rose-700 sm:text-xs">
                        {{ $totalBadges }} dari {{ $allBadges->count() }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-2.5 sm:grid-cols-3">
                    @foreach($allBadges as $b)
                        @php $isEarned = in_array($b->id, $earnedIds); @endphp
                        <article class="relative flex min-h-32 flex-col items-center justify-center rounded-3xl border p-2.5 text-center {{ $isEarned ? 'border-amber-300 bg-amber-50' : 'border-[#F2E6D5] bg-[#FBF8F2]' }}">
                            <div class="mb-1.5 flex h-12 w-12 items-center justify-center rounded-full {{ $isEarned ? 'bg-amber-100' : 'bg-[#EEEAE2]' }}">
                                <span class="text-2xl {{ $isEarned ? '' : 'grayscale' }}">{{ $b->icon ?? '⭐' }}</span>
                            </div>
                            @unless($isEarned)
                                <span class="absolute right-2 top-9 flex h-5 w-5 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500" aria-label="Terkunci">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <rect width="14" height="11" x="5" y="10" rx="2" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 10V7a4 4 0 1 1 8 0v3" />
                                    </svg>
                                </span>
                            @endunless
                            <h3 class="text-[11px] font-black leading-tight text-slate-800">{{ $b->name }}</h3>
                            <span class="mt-1.5 rounded-full px-2.5 py-1 text-[10px] font-black {{ $isEarned ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600' }}">
                                {{ $isEarned ? 'Diraih' : 'Terkunci' }}
                            </span>
                        </article>
                    @endforeach

                    <article class="flex min-h-32 flex-col items-center justify-center rounded-3xl border border-amber-300 bg-amber-100 p-2.5 text-center">
                        <span class="mb-1.5 flex h-12 w-12 items-center justify-center rounded-full bg-orange-600 text-white">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m13 2-9 12h7l-1 8 10-13h-7l1-7Z" />
                            </svg>
                        </span>
                        <h3 class="text-[11px] font-black text-slate-900">Tantangan hari ini</h3>
                        <a href="{{ route('siswa.quiz') }}" class="mt-1.5 rounded-xl bg-emerald-700 px-3 py-1.5 text-[10px] font-black text-white transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-300">Mulai</a>
                    </article>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
