@extends('layouts.app')

@section('title', 'Hasil Kuis - RoboMath')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Result Celebration Card -->
    @php
        $percentage = $totalAttempts > 0 ? round(($totalCorrect / $totalAttempts) * 100) : 0;
        $isGreat = $percentage >= 80;
        $isGood = $percentage >= 60 && $percentage < 80;
    @endphp

    <div class="rounded-3xl p-8 text-center relative overflow-hidden shadow-xl {{ $isGreat ? 'bg-gradient-to-br from-amber-400 via-orange-500 to-rose-500 text-white' : ($isGood ? 'bg-gradient-to-br from-teal-500 to-emerald-600 text-white' : 'bg-gradient-to-br from-indigo-600 to-purple-700 text-white') }}">
        <div class="relative z-10 space-y-3">
            <div class="text-6xl animate-bounce">
                {{ $isGreat ? '🎉🏆🌟' : ($isGood ? '👏⭐👍' : '💪🤖✨') }}
            </div>
            <h1 class="text-3xl md:text-4xl font-black">
                {{ $isGreat ? 'Luar Biasa, Juara!' : ($isGood ? 'Kerja Bagus, Hebat!' : 'Semangat Belajar!') }}
            </h1>
            <p class="text-white/90 text-sm font-semibold max-w-md mx-auto">
                Kamu telah menyelesaikan latihan soal topik <strong>{{ $topic->title }}</strong>.
            </p>

            <!-- Score Summary Cards -->
            <div class="grid grid-cols-3 gap-3 max-w-md mx-auto pt-4">
                <div class="bg-white/20 backdrop-blur-md rounded-2xl p-3 border border-white/30">
                    <p class="text-[11px] font-black uppercase text-white/80">Benar</p>
                    <p class="text-2xl font-black mt-1">{{ $totalCorrect }} / {{ $totalAttempts }}</p>
                </div>
                <div class="bg-white/20 backdrop-blur-md rounded-2xl p-3 border border-white/30">
                    <p class="text-[11px] font-black uppercase text-white/80">Nilai</p>
                    <p class="text-2xl font-black mt-1">{{ $percentage }}%</p>
                </div>
                <div class="bg-white/20 backdrop-blur-md rounded-2xl p-3 border border-white/30">
                    <p class="text-[11px] font-black uppercase text-white/80">Poin Didapat</p>
                    <p class="text-2xl font-black mt-1">+{{ $totalPoints }} ⭐</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Jawaban -->
    <div class="bg-white rounded-3xl border-2 border-slate-200 overflow-hidden shadow-sm">
        <div class="p-5 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-black text-slate-800 text-base flex items-center gap-2">
                <span>📝</span> Pembahasan Soal
            </h2>
            <span class="text-xs font-bold text-slate-500">{{ $attempts->count() }} Soal Terakhir</span>
        </div>

        <div class="divide-y divide-slate-100">
            @foreach($attempts as $index => $attempt)
            <div class="p-5 space-y-3">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <span class="w-7 h-7 rounded-xl flex items-center justify-center font-black text-xs {{ $attempt->is_correct ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                            {{ $attempt->is_correct ? '✓' : '✗' }}
                        </span>
                        <div>
                            <p class="font-black text-slate-800 text-sm">{!! $attempt->quiz->question ?? '-' !!}</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-xl text-xs font-black {{ $attempt->is_correct ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                        {{ $attempt->is_correct ? '+'.$attempt->points_earned.' Poin' : 'Salah' }}
                    </span>
                </div>

                <div class="pl-10 space-y-1 text-xs">
                    <p class="font-semibold text-slate-600">
                        Jawabanmu: <span class="font-black {{ $attempt->is_correct ? 'text-emerald-600' : 'text-rose-600' }}">{{ $attempt->answer }}</span>
                    </p>
                    @if(!$attempt->is_correct && $attempt->quiz)
                    <p class="font-semibold text-slate-600">
                        Kunci Jawaban: <span class="font-black text-emerald-600">{{ $attempt->quiz->correct_answer }}</span>
                    </p>
                    @endif
                    @if($attempt->quiz && $attempt->quiz->explanation)
                    <div class="mt-2 p-3 bg-amber-50 rounded-xl border border-amber-200 text-amber-900 font-medium leading-relaxed">
                        💡 <strong>Penjelasan RoboBot:</strong> {{ $attempt->quiz->explanation }}
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
        <a href="{{ route('siswa.quiz') }}" class="w-full sm:w-auto px-6 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-black text-sm rounded-2xl text-center transition">
            &larr; Pilih Topik Lain
        </a>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <a href="{{ route('siswa.leaderboard') }}" class="flex-1 sm:flex-none px-6 py-3.5 bg-amber-500 hover:bg-amber-600 text-white font-black text-sm rounded-2xl text-center shadow-md transition">
                🏆 Lihat Peringkat
            </a>
            <a href="{{ route('siswa.quiz.play', $topic) }}" class="flex-1 sm:flex-none px-6 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-black text-sm rounded-2xl text-center shadow-md transition">
                🔄 Coba Lagi
            </a>
        </div>
    </div>

</div>
@endsection
