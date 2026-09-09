@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-black text-slate-900">Topik Pembelajaran Logika 📚</h1>
        <p class="text-slate-500 font-semibold">Pilih modul materi yang ingin kamu pelajari hari ini!</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($topics as $topic)
            <div class="bg-white rounded-3xl border-2 border-slate-200 p-6 space-y-4 shadow-sm flex flex-col justify-between" style="border-left-color: {{ $topic->color }}; border-left-width: 8px;">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-5xl">{{ $topic->icon }}</span>
                        <span class="text-xs font-black px-3 py-1 rounded-full bg-slate-100 text-slate-700">
                            {{ $topic->category_label }}
                        </span>
                    </div>
                    <h2 class="text-2xl font-black text-slate-800">{{ $topic->title }}</h2>
                    <p class="text-sm text-slate-600 font-semibold leading-relaxed">
                        {{ $topic->description }}
                    </p>
                </div>

                <div class="space-y-3 pt-2">
                    <div class="flex justify-between text-xs font-black text-slate-600">
                        <span>Progress Modul</span>
                        <span>{{ $topic->progress_percent }}% Selesai</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                        <div class="bg-emerald-500 h-full rounded-full transition-all duration-500" style="width: {{ $topic->progress_percent }}%"></div>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <a href="{{ route('siswa.topics.show', $topic) }}" class="flex-1 text-center bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-sm py-3 rounded-2xl shadow-md transition-all">
                            Buka Materi 📖
                        </a>
                        <a href="{{ route('siswa.quiz.play', $topic) }}" class="flex-1 text-center bg-amber-500 hover:bg-amber-600 text-white font-extrabold text-sm py-3 rounded-2xl shadow-md transition-all">
                            Latihan Soal 🎯
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
