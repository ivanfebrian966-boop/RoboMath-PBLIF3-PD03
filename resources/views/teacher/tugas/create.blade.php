@extends('layouts.app')

@section('title', 'Buat Tugas - Guru RoboMath')

@section('content')
<div class="max-w-xl mx-auto space-y-6">

    <div>
        <h1 class="text-2xl font-black text-slate-900">📝 Buat Tugas Baru</h1>
        <p class="text-sm text-slate-500 mt-0.5">Buat tugas untuk kelas yang Anda ajar</p>
    </div>

    <div class="bg-white border-2 border-amber-200/70 rounded-3xl p-8 shadow-sm">
        <form action="{{ route('guru.tugas.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-black text-slate-700 mb-1.5">Kelas <span class="text-rose-500">*</span></label>
                <select name="class_room_id"
                        class="w-full px-4 py-3 border-2 border-amber-200 rounded-2xl focus:outline-none focus:border-amber-400 bg-amber-50/30 font-medium text-slate-800 transition-colors @error('class_room_id') border-rose-400 bg-rose-50 @enderror">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($classes as $kelas)
                        <option value="{{ $kelas->id }}" {{ old('class_room_id') == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->name }}
                        </option>
                    @endforeach
                </select>
                @error('class_room_id')<p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-black text-slate-700 mb-1.5">Judul Tugas <span class="text-rose-500">*</span></label>
                <input type="text" name="judul" value="{{ old('judul') }}"
                       placeholder="contoh: Latihan Penjumlahan 1-10"
                       class="w-full px-4 py-3 border-2 border-amber-200 rounded-2xl focus:outline-none focus:border-amber-400 bg-amber-50/30 font-medium text-slate-800 placeholder-slate-300 transition-colors @error('judul') border-rose-400 bg-rose-50 @enderror">
                @error('judul')<p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-black text-slate-700 mb-1.5">Tenggat Waktu</label>
                <input type="datetime-local" name="tenggat_waktu" value="{{ old('tenggat_waktu') }}"
                       class="w-full px-4 py-3 border-2 border-amber-200 rounded-2xl focus:outline-none focus:border-amber-400 bg-amber-50/30 font-medium text-slate-800 transition-colors">
            </div>

            <div>
                <label class="block text-sm font-black text-slate-700 mb-1.5">Nilai Lulus (0-100)</label>
                <input type="number" name="nilai_lulus" value="{{ old('nilai_lulus', 70) }}"
                       min="0" max="100" step="1"
                       class="w-full px-4 py-3 border-2 border-amber-200 rounded-2xl focus:outline-none focus:border-amber-400 bg-amber-50/30 font-medium text-slate-800 transition-colors">
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="flex-1 py-3 bg-indigo-500 hover:bg-indigo-600 text-white font-black rounded-2xl shadow-sm transition-all duration-200 hover:scale-[1.02]">
                    Buat Tugas
                </button>
                <a href="{{ route('guru.tugas.index') }}"
                   class="flex-1 py-3 text-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-2xl transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
