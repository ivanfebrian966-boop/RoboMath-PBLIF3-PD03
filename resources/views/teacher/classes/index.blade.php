@extends('layouts.app')

@section('title', 'Manajemen Kelas - Guru RoboMath')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header Banner -->
    <div class="rounded-3xl bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 p-8 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
        <div>
            <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-black uppercase tracking-wider mb-2">
                🏫 Ruang Kelas Guru
            </span>
            <h1 class="text-3xl font-black">Manajemen Kelas Anda</h1>
            <p class="text-emerald-100 text-sm mt-1">Buat kelas belajar, bagikan kode unik kepada siswa, dan atur materi belajar.</p>
        </div>
        <div>
            <a href="{{ route('guru.classes.create') }}" class="inline-flex items-center gap-2 bg-white text-emerald-800 px-6 py-3.5 rounded-2xl font-black text-sm shadow-md hover:bg-emerald-50 transition transform active:scale-95">
                <span class="text-lg">➕</span> Buat Kelas Baru
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border-2 border-emerald-300 text-emerald-800 rounded-2xl font-bold flex items-center gap-3">
        <span class="text-2xl">✅</span>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Daftar Kelas -->
    @if($classRooms->isEmpty())
    <div class="bg-white rounded-3xl border-2 border-dashed border-slate-300 p-12 text-center">
        <span class="text-6xl">🏫</span>
        <h3 class="font-black text-slate-700 text-lg mt-4">Belum Ada Kelas yang Dibuat</h3>
        <p class="text-sm text-slate-500 mt-1 max-w-md mx-auto">Mulai dengan membuat kelas pertama Anda untuk mendapatkan kode kelas yang bisa Anda bagikan ke siswa.</p>
        <a href="{{ route('guru.classes.create') }}" class="mt-6 inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-black px-6 py-3 rounded-2xl text-sm transition">
            <span>➕</span> Buat Kelas Sekarang
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($classRooms as $class)
        <div class="bg-white rounded-3xl border-2 border-slate-200 p-6 shadow-sm hover:border-emerald-300 transition hover:shadow-md flex flex-col justify-between">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 font-black rounded-xl text-xs">
                        Tingkat Kelas {{ $class->kelas_level }}
                    </span>
                    <span class="font-mono bg-slate-100 text-slate-800 font-black text-xs px-3 py-1 rounded-xl border border-slate-200 tracking-wider">
                        Kode: {{ $class->code }}
                    </span>
                </div>

                <div>
                    <h2 class="font-black text-slate-800 text-xl">{{ $class->name }}</h2>
                    <p class="text-xs text-slate-500 mt-1">Dibuat {{ $class->created_at->format('d M Y') }}</p>
                </div>

                <div class="p-3 bg-slate-50 rounded-2xl flex items-center justify-between text-xs font-bold text-slate-600">
                    <span>👥 Jumlah Siswa:</span>
                    <span class="font-black text-emerald-600 text-sm">{{ $class->students_count }} Siswa</span>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                <a href="{{ route('guru.classes.show', $class) }}" class="flex-1 text-center py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-xl font-black text-xs transition">
                    Lihat Detail & Anggota &rarr;
                </a>
                <form action="{{ route('guru.classes.destroy', $class) }}" method="POST" onsubmit="return confirm('Hapus kelas ini? Siswa tidak akan lagi terdaftar di kelas ini.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2.5 text-slate-400 hover:text-rose-600 rounded-xl hover:bg-rose-50 transition" title="Hapus Kelas">
                        🗑️
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>
@endsection
