@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-3xl p-8 text-white shadow-xl space-y-2">
        <span class="bg-white/20 text-white font-extrabold text-xs px-3 py-1 rounded-full uppercase">Latihan Interaktif AI</span>
        <h1 class="text-3xl sm:text-4xl font-black">Pilih Kuis Latihan Logika! 🎯</h1>
        <p class="text-amber-100 font-semibold text-base">Uji pemahamanmu, kumpulkan poin, dan raih lencana bintang!</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($topics as $topic)
            <div class="bg-white rounded-3xl border-2 border-slate-200 p-6 space-y-4 shadow-sm flex items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-amber-50 flex items-center justify-center text-4xl flex-shrink-0 border-2 border-amber-200">
                        {{ $topic->icon }}
                    </div>
                    <div>
                        <h3 class="font-extrabold text-xl text-slate-800">{{ $topic->title }}</h3>
                        <p class="text-xs text-slate-500 font-bold mt-1">Tersedia {{ $topic->quizzes_count }} soal latihan</p>
                    </div>
                </div>

                <a href="{{ route('siswa.quiz.play', $topic) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-black px-6 py-3 rounded-2xl shadow-md hover:shadow-lg transition-all text-sm whitespace-nowrap">
                    Mulai 🚀
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
