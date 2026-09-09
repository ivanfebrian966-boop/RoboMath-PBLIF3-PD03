@extends('layouts.app')

@section('content')
<div class="space-y-8">
    
    <!-- Welcome Header Banner -->
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="relative z-10 space-y-2 max-w-xl">
            <span class="bg-white/20 text-white font-extrabold text-xs px-3 py-1 rounded-full uppercase tracking-wider">
                Kelas {{ $user->kelas }} SD
            </span>
            <h1 class="text-3xl sm:text-4xl font-black">Halo, {{ $user->name }}! 🎒✨</h1>
            <p class="text-indigo-100 font-semibold text-base">
                Siap petualangan logika hari ini? Selesaikan materi dan dapatkan skor terbanyak!
            </p>
        </div>
        <div class="absolute right-4 bottom-0 opacity-20 sm:opacity-40 text-9xl pointer-events-none select-none">
            🚀
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- Poin -->
        <div class="bg-white p-5 rounded-3xl border-2 border-amber-200 shadow-sm space-y-1">
            <div class="flex items-center justify-between text-slate-400 font-extrabold text-xs uppercase">
                <span>Total Poin</span>
                <span class="text-2xl">🏆</span>
            </div>
            <div class="text-3xl font-black text-amber-600">{{ $user->total_score }}</div>
            <div class="text-xs text-slate-500 font-semibold">Level {{ $user->level }} ({{ $user->level_name }})</div>
        </div>

        <!-- Akurasi Soal -->
        <div class="bg-white p-5 rounded-3xl border-2 border-emerald-200 shadow-sm space-y-1">
            <div class="flex items-center justify-between text-slate-400 font-extrabold text-xs uppercase">
                <span>Akurasi Soal</span>
                <span class="text-2xl">🎯</span>
            </div>
            <div class="text-3xl font-black text-emerald-600">{{ $accuracy }}%</div>
            <div class="text-xs text-slate-500 font-semibold">Dari seluruh latihan</div>
        </div>

        <!-- Materi Selesai -->
        <div class="bg-white p-5 rounded-3xl border-2 border-indigo-200 shadow-sm space-y-1">
            <div class="flex items-center justify-between text-slate-400 font-extrabold text-xs uppercase">
                <span>Materi Selesai</span>
                <span class="text-2xl">📚</span>
            </div>
            <div class="text-3xl font-black text-indigo-600">{{ $completedLessons }} / {{ $totalLessons }}</div>
            <div class="text-xs text-slate-500 font-semibold">Modul dikuasai</div>
        </div>

        <!-- Lencana -->
        <div class="bg-white p-5 rounded-3xl border-2 border-rose-200 shadow-sm space-y-1">
            <div class="flex items-center justify-between text-slate-400 font-extrabold text-xs uppercase">
                <span>Lencana Diraih</span>
                <span class="text-2xl">🎖️</span>
            </div>
            <div class="text-3xl font-black text-rose-600">{{ $totalBadges }}</div>
            <div class="text-xs text-slate-500 font-semibold">Bintang Prestasi</div>
        </div>
    </div>

    <!-- AI Recommendation Callout if there are weak topics -->
    @if($weakTopics->count() > 0)
        <div class="bg-amber-50 border-2 border-amber-300 p-6 rounded-3xl space-y-3">
            <div class="flex items-center gap-3">
                <span class="text-3xl animate-spin">🤖</span>
                <div>
                    <h3 class="font-extrabold text-amber-900 text-lg">Rekomendasi Latihan dari AI AlgoBot!</h3>
                    <p class="text-xs text-amber-800 font-semibold">Berdasarkan hasil kuis kamu, yuk tingkatkan kemampuan di topik berikut:</p>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
                @foreach($weakTopics as $weak)
                    <a href="{{ route('siswa.quiz.play', $weak) }}" class="bg-white p-4 rounded-2xl border border-amber-200 hover:border-amber-400 shadow-sm flex items-center justify-between font-bold text-slate-800 transition-all">
                        <span>{{ $weak->icon }} {{ $weak->title }}</span>
                        <span class="text-xs text-rose-500 font-black">{{ $weak->accuracy }}% Akurasi</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Topics Grid -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-black text-slate-900">Modul Pembelajaran Logika 📖</h2>
            <a href="{{ route('siswa.topics') }}" class="font-extrabold text-indigo-600 hover:underline text-sm">Lihat Semua ➡️</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($topics as $topic)
                <div class="bg-white rounded-3xl border-2 border-slate-200 p-6 space-y-4 fun-card shadow-sm flex flex-col justify-between" style="border-top-color: {{ $topic->color }}; border-top-width: 6px;">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-4xl">{{ $topic->icon }}</span>
                            <span class="text-xs font-black px-3 py-1 rounded-full bg-slate-100 text-slate-600">
                                {{ $topic->lessons_count }} Materi
                            </span>
                        </div>
                        <h3 class="font-black text-xl text-slate-800">{{ $topic->title }}</h3>
                        <p class="text-xs text-slate-500 font-semibold leading-relaxed line-clamp-2">
                            {{ $topic->description }}
                        </p>
                    </div>

                    <!-- Progress Bar -->
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs font-black text-slate-600">
                            <span>Progress Belajar</span>
                            <span>{{ $topic->progress_percent }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                            <div class="bg-indigo-600 h-full rounded-full transition-all duration-500" style="width: {{ $topic->progress_percent }}%"></div>
                        </div>

                        <a href="{{ route('siswa.topics.show', $topic) }}" class="block w-full text-center bg-slate-100 hover:bg-indigo-600 hover:text-white font-extrabold text-sm py-3 rounded-2xl transition-all mt-2">
                            Mulai Belajar 🚀
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
