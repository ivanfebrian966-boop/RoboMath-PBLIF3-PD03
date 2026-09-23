@extends('layouts.app')

@section('title', 'Gabung Kelas - RoboMath')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Header Banner -->
    <div class="rounded-3xl bg-gradient-to-r from-cyan-500 via-blue-600 to-indigo-600 p-8 text-white shadow-xl">
        <div class="flex flex-col md:flex-row items-center gap-6">
            <div class="text-5xl">🏫</div>
            <div>
                <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-black uppercase tracking-wider mb-2">
                    Kelas RoboMath
                </span>
                <h1 class="text-3xl font-black">Gabung ke Kelas Gurumu</h1>
                <p class="text-cyan-100 text-sm mt-1">Masukkan 6 digit kode unik yang diberikan gurumu untuk belajar bersama teman sekelas!</p>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border-2 border-emerald-300 text-emerald-800 rounded-2xl font-bold flex items-center gap-3">
        <span class="text-2xl">🎉</span>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Form Masukkan Kode -->
    <div class="bg-white rounded-3xl border-2 border-cyan-200 p-8 shadow-sm">
        <form action="{{ route('siswa.kelas.join.store') }}" method="POST" class="max-w-md mx-auto space-y-5 text-center">
            @csrf
            <div>
                <label for="code" class="block font-black text-slate-700 text-base mb-2">Kode Kelas (6 Huruf / Angka)</label>
                <input type="text" 
                       id="code" 
                       name="code" 
                       maxlength="6"
                       placeholder="Contoh: RM7A9K" 
                       value="{{ old('code') }}"
                       class="w-full text-center tracking-widest text-3xl font-black uppercase py-4 px-6 rounded-2xl border-2 border-cyan-300 focus:border-blue-500 focus:ring-4 focus:ring-cyan-100 outline-none transition uppercase"
                       required autocomplete="off">
                @error('code')
                    <p class="text-rose-500 text-xs font-bold mt-2">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full py-4 bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 text-white font-black text-base rounded-2xl shadow-lg hover:shadow-xl transition transform active:scale-95">
                🚀 Masuk ke Kelas Sekarang
            </button>
        </form>
    </div>

    <!-- Kelas yang Diikuti -->
    <div class="bg-white rounded-3xl border-2 border-slate-200 p-6 shadow-sm">
        <h2 class="font-black text-slate-800 text-lg mb-4 flex items-center gap-2">
            <span>📚</span> Kelas yang Sudah Kamu Ikuti
        </h2>

        @if($myClasses->isEmpty())
        <div class="p-8 text-center bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
            <span class="text-4xl">🎒</span>
            <p class="mt-2 font-bold text-slate-600 text-sm">Kamu belum bergabung di kelas mana pun.</p>
            <p class="text-xs text-slate-400 mt-1">Minta kode kelas pada gurumu untuk bergabung!</p>
        </div>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($myClasses as $class)
            <div class="p-5 rounded-2xl border-2 border-blue-100 bg-gradient-to-br from-blue-50/50 to-white hover:border-blue-300 transition">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="px-2.5 py-0.5 bg-blue-100 text-blue-700 font-black rounded-lg text-xs">
                            Kelas {{ $class->kelas_level }}
                        </span>
                        <h3 class="font-black text-slate-800 text-base mt-2">{{ $class->name }}</h3>
                        <p class="text-xs text-slate-500 mt-1">Guru: <strong>{{ $class->teacher->name ?? 'Guru' }}</strong></p>
                    </div>
                    <span class="px-2.5 py-1 bg-slate-100 text-slate-700 font-mono font-bold text-xs rounded-lg border border-slate-200">
                        {{ $class->code }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>
@endsection
