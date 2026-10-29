@extends('layouts.app')

@section('title', $classRoom->name . ' - Guru RoboMath')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- Back link --}}
    <a href="{{ route('guru.classes.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-800 transition font-medium">
        &larr; Kembali ke Kelola Kelas
    </a>

    {{-- Header: Class name + code --}}
    <div class="flex items-start justify-between flex-wrap gap-4">
        <div>
            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded-full mb-2">
                Kelas {{ $classRoom->kelas_level }} SD
            </span>
            <h1 class="text-3xl font-black text-slate-900">{{ $classRoom->name }}</h1>
        </div>
        <div class="flex items-center gap-3 bg-white border border-slate-200 rounded-2xl px-5 py-3 shadow-sm">
            <div>
                <p class="text-[10px] font-bold text-slate-400 tracking-widest uppercase">Kode Gabung Siswa</p>
                <p class="font-mono text-2xl font-black text-amber-700 tracking-[0.25em] leading-tight">{{ $classRoom->code }}</p>
            </div>
            <button
                onclick="navigator.clipboard.writeText('{{ $classRoom->code }}').then(() => { this.textContent = 'Tersalin!'; setTimeout(() => this.textContent = 'Salin kode', 1500); })"
                class="px-4 py-2 bg-[#056B48] hover:bg-[#044d34] text-white text-sm font-bold rounded-xl transition">
                Salin kode
            </button>
        </div>
    </div>

    {{-- KPI Cards --}}
    @php
        $pendingCount = \App\Models\Quiz::whereHas('topic', fn($q) => $q->whereIn('id', $classRoom->topics->pluck('id')))
            ->where('review_status', 'pending')
            ->count();
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Siswa Terdaftar --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <p class="text-[11px] font-bold text-slate-400 tracking-widest uppercase mb-1">Siswa Terdaftar</p>
            <p class="text-4xl font-black text-slate-800">{{ $classRoom->students->count() }}</p>
        </div>
        {{-- Topik Diajarkan --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <p class="text-[11px] font-bold text-slate-400 tracking-widest uppercase mb-1">Topik Diajarkan</p>
            <p class="text-4xl font-black text-slate-800">{{ $classRoom->topics->count() }}</p>
        </div>
        {{-- Soal AI Menunggu Review --}}
        <div class="bg-[#FFF8EC] border border-amber-200 rounded-2xl p-5 shadow-sm">
            <p class="text-[11px] font-bold text-amber-600 tracking-widest uppercase mb-1">Soal AI Menunggu Review</p>
            <div class="flex items-baseline gap-3">
                <p class="text-4xl font-black text-slate-800">{{ $pendingCount }}</p>
                <a href="{{ route('admin.ai-recommendations.index') }}"
                   class="text-sm font-bold text-amber-600 hover:text-amber-800 transition">
                    Periksa sekarang
                </a>
            </div>
        </div>
    </div>

    {{-- Two-column: Topics + Students --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Left: Topik Pembelajaran --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-bold text-slate-800">Topik pembelajaran</h2>
                <a href="{{ route('guru.classes.create') }}"
                   class="px-3 py-1.5 bg-[#E1F8EC] hover:bg-emerald-100 text-[#056B48] text-xs font-bold rounded-lg transition">
                    Tambah topik
                </a>
            </div>

            @if($topics->isEmpty())
                <p class="text-sm text-slate-400">Belum ada topik yang ditugaskan.</p>
            @else
                <div class="space-y-3">
                    @foreach($topics as $i => $topic)
                    <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="w-7 h-7 rounded-lg bg-slate-200 text-slate-600 text-xs font-black flex items-center justify-center flex-shrink-0">
                            {{ $i + 1 }}
                        </span>
                        <div>
                            <p class="text-sm font-bold text-slate-800">{{ $topic->title }}</p>
                            <p class="text-xs text-slate-400">Kelas {{ $topic->kelas_level }} · {{ $topic->quizzes()->count() }} soal AI</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Right: Siswa Terdaftar --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <h2 class="text-base font-bold text-slate-800 mb-4">Siswa terdaftar ({{ $classRoom->students->count() }})</h2>

            @if($classRoom->students->isEmpty())
                <div class="border-2 border-dashed border-slate-200 rounded-2xl p-8 text-center space-y-3">
                    <p class="text-base font-black text-slate-700">Belum ada siswa yang bergabung</p>
                    <p class="text-sm text-slate-500">
                        Bagikan kode <span class="font-black text-amber-700">{{ $classRoom->code }}</span> kepada siswa agar mereka bisa masuk ke kelas ini.
                    </p>
                    <button
                        onclick="navigator.clipboard.writeText('{{ $classRoom->code }}').then(() => { this.textContent = 'Tersalin!'; setTimeout(() => this.textContent = 'Salin kode kelas', 1500); })"
                        class="px-5 py-2.5 bg-[#056B48] hover:bg-[#044d34] text-white text-sm font-bold rounded-xl transition">
                        Salin kode kelas
                    </button>
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($classRoom->students as $index => $student)
                    <div class="py-3 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 font-black text-xs flex items-center justify-center">
                                {{ $index + 1 }}
                            </span>
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ $student->name }}</p>
                                <p class="text-xs text-slate-400">{{ $student->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-lg">
                                ⭐ {{ number_format($student->total_score) }} Poin
                            </span>
                            <a href="{{ route('guru.students.show', $student) }}"
                               class="text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-2.5 py-1 rounded-lg transition">
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
