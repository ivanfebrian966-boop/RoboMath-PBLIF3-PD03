@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Breadcrumb & Back -->
    <div class="flex items-center justify-between">
        <a href="{{ route('siswa.topics.show', $lesson->topic) }}" class="inline-flex items-center gap-2 text-indigo-600 font-extrabold text-sm hover:underline">
            ⬅️ Kembali ke {{ $lesson->topic->title }}
        </a>
        <span class="text-xs font-black bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full">
            {{ $lesson->topic->category_label }}
        </span>
    </div>

    <!-- Main Lesson Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-10 border-4 border-indigo-100 shadow-xl space-y-8">
        
        <div class="border-b pb-6 space-y-2">
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900 leading-tight">{{ $lesson->title }}</h1>
            <div class="flex items-center gap-4 text-xs font-bold text-slate-500">
                <span>⏱️ Waktu baca: ~{{ $lesson->estimated_minutes }} menit</span>
                <span>•</span>
                <span>Tingkat: {{ $lesson->difficulty_label }}</span>
            </div>
        </div>

        <!-- Rich Text HTML Content -->
        <div class="prose prose-slate max-w-none text-base sm:text-lg leading-relaxed">
            {!! $lesson->content !!}
        </div>

        <!-- Action / Complete Button -->
        <div class="pt-6 border-t flex flex-col sm:flex-row items-center justify-between gap-4">
            @if($prevLesson)
                <a href="{{ route('siswa.lessons.show', $prevLesson) }}" class="w-full sm:w-auto bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold px-6 py-3.5 rounded-2xl text-center">
                    ⬅️ Materi Sebelumnya
                </a>
            @else
                <div></div>
            @endif

            <form action="{{ route('siswa.lessons.complete', $lesson) }}" method="POST" class="w-full sm:w-auto">
                @csrf
                <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-black text-base px-8 py-4 rounded-2xl shadow-xl transform hover:scale-105 transition-all text-center">
                    Selesai Membaca (+20 Poin) 🎉
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
