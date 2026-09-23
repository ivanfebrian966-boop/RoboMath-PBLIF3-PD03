@extends('layouts.app')

@section('title', $classRoom->name . ' - Guru RoboMath')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('guru.classes.index') }}" class="p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition font-black">
                &larr; Kembali
            </a>
            <div>
                <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 font-black rounded-lg text-xs">
                    Kelas {{ $classRoom->kelas_level }} SD
                </span>
                <h1 class="text-2xl font-black text-slate-800 mt-1">{{ $classRoom->name }}</h1>
            </div>
        </div>

        <div class="flex items-center gap-3 bg-white p-3 rounded-2xl border-2 border-emerald-200 shadow-sm">
            <span class="text-xs font-bold text-slate-500">Kode Gabung Siswa:</span>
            <span class="font-mono text-xl font-black text-emerald-700 bg-emerald-50 px-3 py-1 rounded-xl tracking-widest border border-emerald-200">
                {{ $classRoom->code }}
            </span>
        </div>
    </div>

    <!-- Grid Info Kelas & Topik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Topik yang Ditugaskan -->
        <div class="bg-white rounded-3xl border-2 border-slate-200 p-6 shadow-sm md:col-span-1 space-y-4">
            <h2 class="font-black text-slate-800 text-base flex items-center gap-2">
                <span>📚</span> Topik Pembelajaran
            </h2>

            @if($topics->isEmpty())
                <p class="text-xs text-slate-400 font-medium">Belum ada topik khusus yang dipilih untuk kelas ini.</p>
            @else
                <div class="space-y-2">
                    @foreach($topics as $topic)
                    <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 flex items-center gap-3">
                        <span class="text-2xl">{{ $topic->icon ?? '📖' }}</span>
                        <div>
                            <h4 class="font-bold text-slate-800 text-xs">{{ $topic->title }}</h4>
                            <p class="text-[10px] text-slate-400">Kelas {{ $topic->kelas_level }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Daftar Siswa yang Sudah Bergabung -->
        <div class="bg-white rounded-3xl border-2 border-slate-200 overflow-hidden shadow-sm md:col-span-2">
            <div class="p-5 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                <h2 class="font-black text-slate-800 text-base flex items-center gap-2">
                    <span>👥</span> Siswa Terdaftar ({{ $classRoom->students->count() }})
                </h2>
            </div>

            @if($classRoom->students->isEmpty())
            <div class="p-12 text-center text-slate-400">
                <span class="text-4xl">🎒</span>
                <p class="mt-2 font-bold text-slate-600 text-sm">Belum ada siswa yang bergabung.</p>
                <p class="text-xs mt-1">Berikan kode <strong>{{ $classRoom->code }}</strong> kepada siswa Anda.</p>
            </div>
            @else
            <div class="divide-y divide-slate-100">
                @foreach($classRoom->students as $index => $student)
                <div class="p-4 flex items-center justify-between hover:bg-slate-50 transition">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 font-black text-xs flex items-center justify-center">
                            {{ $index + 1 }}
                        </span>
                        <div>
                            <h4 class="font-black text-slate-800 text-sm">{{ $student->name }}</h4>
                            <p class="text-xs text-slate-400">{{ $student->email }} • Bergabung {{ $student->pivot->joined_at ? \Carbon\Carbon::parse($student->pivot->joined_at)->diffForHumans() : '-' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="font-black text-amber-600 text-xs bg-amber-50 px-2.5 py-1 rounded-xl">
                            ⭐ {{ number_format($student->total_score) }} Poin
                        </span>
                        <a href="{{ route('guru.students.show', $student) }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-2.5 py-1 rounded-xl transition">
                            Lihat Rapor &rarr;
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

</div>
@endsection
