@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border-2 border-slate-200 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6" style="border-top-color: {{ $topic->color }}; border-top-width: 6px;">
        <div class="flex items-center gap-5">
            <span class="text-6xl">{{ $topic->icon }}</span>
            <div>
                <span class="text-xs font-black uppercase text-indigo-600 tracking-wider">Topik Pembelajaran</span>
                <h1 class="text-3xl font-black text-slate-900">{{ $topic->title }}</h1>
                <p class="text-sm text-slate-500 font-semibold mt-1">{{ $topic->description }}</p>
            </div>
        </div>
        <a href="{{ route('siswa.quiz.play', $topic) }}" class="bg-amber-500 hover:bg-amber-600 text-white font-extrabold px-6 py-3.5 rounded-2xl shadow-lg transition-all text-sm whitespace-nowrap">
            Mulai Kuis Topik Ini 🎯
        </a>
    </div>

    <!-- Lessons List -->
    <div class="space-y-4">
        <h2 class="text-2xl font-black text-slate-800">Daftar Modul Sub-Materi 📝</h2>

        <div class="space-y-3">
            @foreach($lessons as $index => $lesson)
                <div class="bg-white p-5 rounded-2xl border-2 border-slate-200 shadow-sm flex items-center justify-between gap-4 hover:border-indigo-300 transition-all">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-black text-lg {{ $lesson->user_status === 'selesai' ? 'bg-emerald-100 text-emerald-700 border-2 border-emerald-300' : 'bg-slate-100 text-slate-600' }}">
                            {{ $lesson->user_status === 'selesai' ? '✓' : ($index + 1) }}
                        </div>
                        <div>
                            <h3 class="font-extrabold text-lg text-slate-800">{{ $lesson->title }}</h3>
                            <div class="flex items-center gap-3 text-xs font-bold text-slate-500 mt-1">
                                <span>⏱️ ~{{ $lesson->estimated_minutes }} Menit</span>
                                <span>•</span>
                                <span>{{ $lesson->difficulty_label }}</span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('siswa.lessons.show', $lesson) }}" class="px-5 py-2.5 rounded-2xl font-black text-sm transition-all {{ $lesson->user_status === 'selesai' ? 'bg-slate-100 text-slate-700 hover:bg-slate-200' : 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-md' }}">
                        {{ $lesson->user_status === 'selesai' ? 'Baca Ulang 📖' : 'Pelajari Materi 🚀' }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
