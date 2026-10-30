@extends('layouts.app')

@section('title', 'Dashboard Siswa - RoboMath')

@section('content')
<div class="space-y-4 sm:space-y-5">
    <section class="relative overflow-hidden rounded-[28px] border border-orange-300/50 bg-gradient-to-r from-[#FF3B30] via-[#FF6500] to-[#FF8A00] p-6 text-white shadow-[0_8px_24px_rgba(255,104,0,0.22)] sm:p-8">
        <div class="relative z-10 max-w-xl space-y-3">
            <div class="space-y-1">
                <h1 class="text-3xl font-black sm:text-4xl">Halo, {{ $user->name }}!</h1>
                <p class="max-w-lg text-sm font-semibold leading-relaxed text-orange-50 sm:text-base">
                    Siap petualangan matematika hari ini? Selesaikan soal kuis, kumpulkan lencana, dan raih skor tertinggi!
                </p>
            </div>
            <a href="{{ route('siswa.quiz') }}" class="inline-flex items-center gap-2 rounded-2xl bg-white px-5 py-3 text-sm font-black text-[#C2410C] shadow-sm transition hover:-translate-y-0.5 hover:bg-orange-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8 5 11 7-11 7V5Z" />
                </svg>
                Mulai Latihan Soal
            </a>
        </div>
        <div class="pointer-events-none absolute -right-10 -top-16 h-56 w-56 rounded-full bg-white/10 blur-2xl"></div>
        <div class="pointer-events-none absolute -bottom-20 right-24 h-44 w-44 rounded-full bg-amber-300/20 blur-2xl"></div>
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

    @if($weakTopics->count() > 0)
        <section class="space-y-3 rounded-3xl border-2 border-amber-300 bg-amber-50 p-4 shadow-sm sm:p-5">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-amber-400 p-1 shadow-sm">
                    <img src="{{ asset('images/maskot.png') }}" alt="RoboBot AI" class="h-full w-full rounded-xl object-contain">
                </div>
                <div>
                    <h2 class="font-extrabold text-amber-900">Rekomendasi Latihan dari AI RoboBot!</h2>
                    <p class="text-xs font-semibold text-amber-800">Yuk, tingkatkan kemampuan di topik berikut:</p>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-2 sm:grid-cols-3">
                @foreach($weakTopics as $weak)
                    <a href="{{ route('siswa.quiz.play', $weak) }}" class="flex items-center justify-between gap-2 rounded-2xl border border-amber-200 bg-white p-3 text-sm font-bold text-slate-800 shadow-sm transition hover:border-amber-400">
                        <span>{{ $weak->icon }} {{ $weak->title }}</span>
                        <span class="whitespace-nowrap text-xs font-black text-rose-500">{{ $weak->accuracy }}%</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <div class="grid items-start gap-4 lg:grid-cols-[1.4fr_1fr]">
        <section class="space-y-4 rounded-3xl border-2 border-amber-200 bg-white p-4 shadow-sm sm:p-5">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-black text-slate-900 sm:text-xl">Koleksi Lencana & Prestasi</h2>
                    <p class="mt-0.5 text-xs font-semibold text-slate-500">Terus berlatih untuk membuka semua lencana.</p>
                </div>
                <span class="whitespace-nowrap rounded-full bg-rose-100 px-3 py-1 text-[10px] font-black text-rose-700 sm:text-xs">
                    {{ $totalBadges }} dari {{ $allBadges->count() }} Terbuka
                </span>
            </div>

            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                @foreach($allBadges as $b)
                    @php $isEarned = in_array($b->id, $earnedIds); @endphp
                    <article class="relative flex min-h-40 flex-col items-center justify-center rounded-3xl border p-3 text-center {{ $isEarned ? 'border-amber-300 bg-amber-50' : 'border-[#F2E6D5] bg-[#FBF8F2]' }}">
                        <div class="mb-2 flex h-14 w-14 items-center justify-center rounded-full {{ $isEarned ? 'bg-amber-100' : 'bg-[#EEEAE2]' }}">
                            <span class="text-3xl {{ $isEarned ? '' : 'grayscale' }}">{{ $b->icon ?? '⭐' }}</span>
                        </div>
                        @unless($isEarned)
                            <span class="absolute right-2 top-12 flex h-6 w-6 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500" aria-label="Terkunci">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <rect width="14" height="11" x="5" y="10" rx="2" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 10V7a4 4 0 1 1 8 0v3" />
                                </svg>
                            </span>
                        @endunless
                        <h3 class="text-xs font-black leading-tight text-slate-800">{{ $b->name }}</h3>
                        <p class="mt-1 line-clamp-2 text-[10px] font-medium leading-snug text-slate-500">{{ $b->description }}</p>
                        <span class="mt-2 rounded-full px-2.5 py-1 text-[10px] font-black {{ $isEarned ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600' }}">
                            {{ $isEarned ? 'Diraih' : 'Terkunci' }}
                        </span>
                    </article>
                @endforeach

                <article class="flex min-h-40 flex-col items-center justify-center rounded-3xl border border-amber-300 bg-gradient-to-br from-amber-100 to-orange-200 p-3 text-center">
                    <span class="mb-2 flex h-14 w-14 items-center justify-center rounded-full bg-orange-600 text-white">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m13 2-9 12h7l-1 8 10-13h-7l1-7Z" />
                        </svg>
                    </span>
                    <h3 class="text-xs font-black text-slate-900">Tantangan hari ini</h3>
                    <p class="mt-1 text-[10px] font-medium leading-snug text-amber-950">Kerjakan 5 soal untuk membuka lencana Pencari Jawaban.</p>
                    <a href="{{ route('siswa.quiz') }}" class="mt-2 rounded-xl bg-emerald-700 px-4 py-2 text-xs font-black text-white transition hover:bg-emerald-800">Mulai</a>
                </article>
            </div>
        </section>

        <div class="space-y-4">
            <nav class="grid grid-cols-3 gap-2.5" aria-label="Menu cepat">
                <a href="{{ route('siswa.quiz') }}" class="group flex min-h-28 flex-col items-start justify-between rounded-3xl border-2 border-blue-200 bg-white p-3 shadow-sm transition hover:-translate-y-1 hover:border-blue-400">
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-100 text-blue-700 transition group-hover:scale-105">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14 5 5 5M4 20l4.5-1 10-10a2.12 2.12 0 0 0-3-3l-10 10L4 20Z" />
                        </svg>
                    </span>
                    <span class="text-xs font-black leading-tight text-slate-800">Latihan Soal AI</span>
                </a>
                <a href="{{ route('siswa.leaderboard') }}" class="group flex min-h-28 flex-col items-start justify-between rounded-3xl border-2 border-amber-200 bg-white p-3 shadow-sm transition hover:-translate-y-1 hover:border-amber-400">
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-100 text-amber-700 transition group-hover:scale-105">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 19V9m5 10V5m5 14v-7m5 7V3M2 19h20" />
                        </svg>
                    </span>
                    <span class="text-xs font-black leading-tight text-slate-800">Progress & Leaderboard</span>
                </a>
                <a href="{{ route('profil.show') }}" class="group flex min-h-28 flex-col items-start justify-between rounded-3xl border-2 border-emerald-200 bg-white p-3 shadow-sm transition hover:-translate-y-1 hover:border-emerald-400">
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
                    <h2 class="text-base font-black text-slate-900 sm:text-lg">Modul Belajar Matematika SD</h2>
                    <a href="{{ route('siswa.topics') }}" class="whitespace-nowrap text-xs font-black text-blue-600 hover:underline">Lihat semua &rarr;</a>
                </div>

                <div class="space-y-3">
                    @forelse($topics as $topic)
                        <article class="space-y-3 rounded-3xl border border-blue-200 border-t-4 p-3.5 sm:p-4" style="border-top-color: {{ $topic->color }};">
                            <div class="flex items-start gap-3">
                                <span class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-violet-600 text-2xl text-white">{{ $topic->icon }}</span>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start justify-between gap-2">
                                        <h3 class="font-black text-slate-800">{{ $topic->title }}</h3>
                                        <span class="whitespace-nowrap rounded-full bg-amber-100 px-2.5 py-1 text-[10px] font-black text-amber-800">{{ $topic->lessons_count }} Materi</span>
                                    </div>
                                    <p class="mt-1 line-clamp-2 text-xs font-medium leading-relaxed text-slate-600">{{ $topic->description }}</p>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex justify-between text-xs font-black text-slate-700">
                                    <span>Progress Belajar</span>
                                    <span>{{ $topic->progress_percent }}%</span>
                                </div>
                                <div class="h-2.5 overflow-hidden rounded-full bg-blue-100">
                                    <div class="h-full rounded-full bg-blue-600 transition-all duration-500" style="width: {{ $topic->progress_percent }}%"></div>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('siswa.topics.show', $topic) }}" class="flex-1 rounded-xl bg-slate-100 px-3 py-2 text-center text-xs font-black text-slate-700 transition hover:bg-slate-200">Baca Materi</a>
                                <a href="{{ route('siswa.quiz.play', $topic) }}" class="flex-1 rounded-xl bg-[#FF8A00] px-3 py-2 text-center text-xs font-black text-white transition hover:bg-orange-700">Main Kuis</a>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-3xl border-2 border-dashed border-[#E9DDCA] px-4 py-6 text-center text-xs font-semibold text-slate-500">
                            Modul baru dari gurumu akan muncul di sini.
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
