@extends('layouts.app')

@section('title', 'Daftar Tugas - Guru RoboMath')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header Banner -->
    <div class="rounded-3xl bg-gradient-to-br from-indigo-600 to-indigo-500 p-8 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
        <div>
            <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-black uppercase tracking-wider mb-2">
                📝 Tugas Kelas
            </span>
            <h1 class="text-3xl font-black">Manajemen Tugas</h1>
            <p class="text-indigo-100 text-sm mt-1">Buat, kelola, dan pantau pengumpulan tugas dari siswa kelas Anda.</p>
        </div>
        <a href="{{ route('guru.tugas.create') }}"
           class="flex items-center gap-2 px-6 py-3 bg-white text-indigo-700 font-black rounded-2xl shadow-lg hover:bg-indigo-50 transition-all duration-200 hover:scale-105 whitespace-nowrap">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Buat Tugas
        </a>
    </div>

    <!-- List Tugas -->
    @forelse($tugasList as $tugas)
    <div class="bg-white border-2 border-amber-200/60 rounded-3xl p-6 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-2xl flex-shrink-0">📋</div>
                <div>
                    <h3 class="font-black text-slate-800 text-lg">{{ $tugas->judul }}</h3>
                    <p class="text-sm text-slate-500 mt-0.5">
                        🏫 {{ $tugas->classRoom->name }}
                        @if($tugas->topic)
                            · 📚 {{ $tugas->topic->title }}
                        @endif
                    </p>
                    <div class="flex flex-wrap items-center gap-3 mt-2">
                        @if($tugas->tenggat_waktu)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 text-rose-700 rounded-full text-xs font-bold border border-rose-100">
                            ⏰ Tenggat: {{ $tugas->tenggat_waktu->format('d M Y, H:i') }}
                        </span>
                        @endif
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-700 rounded-full text-xs font-bold border border-amber-100">
                            🎯 Nilai Lulus: {{ $tugas->nilai_lulus }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="{{ route('guru.tugas.show', $tugas) }}"
                   class="px-4 py-2 bg-indigo-50 text-indigo-700 font-bold rounded-xl border border-indigo-100 hover:bg-indigo-100 transition-colors text-sm">
                    Lihat Detail
                </a>
                <form action="{{ route('guru.tugas.destroy', $tugas) }}" method="POST"
                      onsubmit="return confirm('Hapus tugas ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-rose-50 text-rose-700 font-bold rounded-xl border border-rose-100 hover:bg-rose-100 transition-colors text-sm">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white border-2 border-dashed border-amber-200 rounded-3xl p-16 text-center">
        <div class="text-6xl mb-4">📝</div>
        <h3 class="font-black text-slate-700 text-xl">Belum Ada Tugas</h3>
        <p class="text-slate-500 mt-1 text-sm">Mulai buat tugas pertama Anda untuk kelas!</p>
        <a href="{{ route('guru.tugas.create') }}"
           class="inline-block mt-5 px-6 py-2.5 bg-indigo-500 text-white font-bold rounded-2xl hover:bg-indigo-600 transition-colors">
            Buat Tugas Sekarang
        </a>
    </div>
    @endforelse

</div>
@endsection
