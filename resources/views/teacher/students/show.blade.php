@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header Back -->
    <a href="{{ route('guru.dashboard') }}" class="inline-flex items-center gap-2 text-indigo-600 font-extrabold text-sm hover:underline">
        ⬅️ Kembali ke Monitoring Kelas
    </a>

    <!-- Student Profile Header Card -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border-2 border-slate-200 shadow-sm flex items-center justify-between">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-3xl bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center text-white text-3xl font-black shadow-md">
                {{ strtoupper(substr($student->name, 0, 1)) }}
            </div>
            <div>
                <span class="text-xs font-black uppercase text-slate-400">Detail Perkembangan Siswa</span>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900">{{ $student->name }}</h1>
                <p class="text-xs font-bold text-slate-500 mt-0.5">Kelas {{ $student->kelas }} SD • {{ $student->email }}</p>
            </div>
        </div>

        <div class="text-right">
            <div class="text-2xl font-black text-amber-600">{{ $student->total_score }} Poin</div>
            <div class="text-xs font-extrabold text-amber-900 bg-amber-100 px-3 py-1 rounded-full inline-block mt-1">
                Level {{ $student->level }} ({{ $student->level_name }})
            </div>
        </div>
    </div>

    <!-- Topics Mastery Cards -->
    <div class="space-y-4">
        <h2 class="text-xl font-black text-slate-800">Masteri Topik & Akurasi Latihan 📈</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($topics as $topic)
                <div class="bg-white p-5 rounded-3xl border-2 border-slate-200 space-y-3 shadow-sm">
                    <div class="flex items-center justify-between">
                        <span class="font-extrabold text-slate-800 text-base flex items-center gap-2">
                            <span>{{ $topic->icon }}</span> {{ $topic->title }}
                        </span>
                        <span class="text-xs font-black px-2.5 py-1 rounded-full {{ $topic->accuracy >= 70 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                            {{ $topic->accuracy }}% Akurasi
                        </span>
                    </div>

                    <div class="space-y-1">
                        <div class="flex justify-between text-xs font-bold text-slate-500">
                            <span>Materi Dibaca</span>
                            <span>{{ $topic->lesson_progress }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-indigo-600 h-full rounded-full" style="width: {{ $topic->lesson_progress }}%"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
