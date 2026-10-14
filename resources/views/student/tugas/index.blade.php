@extends('layouts.app')

@section('title', 'Tugas Saya - RoboMath')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Banner -->
    <div class="rounded-3xl bg-gradient-to-br from-violet-600 to-indigo-600 p-8 text-white shadow-xl">
        <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-black uppercase tracking-wider mb-2">
            📝 Tugas Belajar
        </span>
        <h1 class="text-3xl font-black">Tugas Saya</h1>
        <p class="text-indigo-100 text-sm mt-1">Kumpulkan tugas tepat waktu dan raih nilai terbaik! 🚀</p>
    </div>

    <!-- List Tugas -->
    @forelse($tugasList as $tugas)
    <div class="bg-white border-2 {{ $tugas->pengumpulan ? 'border-emerald-200' : 'border-amber-200/60' }} rounded-3xl p-6 shadow-sm hover:shadow-md transition-all duration-200">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl {{ $tugas->pengumpulan ? 'bg-emerald-50' : 'bg-indigo-50' }} flex items-center justify-center text-2xl flex-shrink-0">
                    {{ $tugas->pengumpulan ? '✅' : '📋' }}
                </div>
                <div>
                    <h3 class="font-black text-slate-800 text-lg">{{ $tugas->judul }}</h3>
                    <p class="text-sm text-slate-500 mt-0.5">🏫 {{ $tugas->classRoom->name }}</p>
                    <div class="flex flex-wrap items-center gap-2 mt-2">
                        @if($tugas->tenggat_waktu)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 {{ now()->gt($tugas->tenggat_waktu) && !$tugas->pengumpulan ? 'bg-rose-50 text-rose-700 border-rose-100' : 'bg-slate-50 text-slate-600 border-slate-100' }} rounded-full text-xs font-bold border">
                            ⏰ {{ $tugas->tenggat_waktu->format('d M Y, H:i') }}
                        </span>
                        @endif
                        @if($tugas->pengumpulan)
                            @if($tugas->pengumpulan->skor !== null)
                            <span class="px-2.5 py-1 bg-amber-50 text-amber-700 rounded-full text-xs font-black border border-amber-100">
                                🎯 Nilai: {{ $tugas->pengumpulan->skor }}
                            </span>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex-shrink-0">
                @if($tugas->pengumpulan)
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-700 font-bold rounded-2xl border border-emerald-200 text-sm">
                        ✅ Sudah Dikumpulkan
                    </span>
                @elseif($tugas->tenggat_waktu && now()->gt($tugas->tenggat_waktu))
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-rose-50 text-rose-700 font-bold rounded-2xl border border-rose-200 text-sm">
                        ⏰ Kadaluarsa
                    </span>
                @else
                    <form action="{{ route('siswa.tugas.kumpulkan', $tugas) }}" method="POST"
                          onsubmit="return confirm('Kumpulkan tugas ini sekarang?')">
                        @csrf
                        <button type="submit"
                                class="px-5 py-2.5 bg-indigo-500 hover:bg-indigo-600 text-white font-bold rounded-2xl transition-all duration-200 hover:scale-105 text-sm shadow-sm">
                            📤 Kumpulkan
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white border-2 border-dashed border-amber-200 rounded-3xl p-16 text-center">
        <div class="text-6xl mb-4">📭</div>
        <h3 class="font-black text-slate-700 text-xl">Belum Ada Tugas</h3>
        <p class="text-slate-500 mt-1 text-sm">Guru Anda belum membuat tugas. Pastikan Anda sudah bergabung ke kelas!</p>
        <a href="{{ route('siswa.kelas.join') }}"
           class="inline-block mt-5 px-6 py-2.5 bg-amber-400 text-amber-900 font-bold rounded-2xl hover:bg-amber-500 transition-colors">
            Gabung Kelas
        </a>
    </div>
    @endforelse

</div>
@endsection
