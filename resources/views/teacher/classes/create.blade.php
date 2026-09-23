@extends('layouts.app')

@section('title', 'Buat Kelas Baru - Guru RoboMath')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center gap-3">
        <a href="{{ route('guru.classes.index') }}" class="p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition font-black">
            &larr; Kembali
        </a>
        <h1 class="text-2xl font-black text-slate-800">Buat Kelas Baru</h1>
    </div>

    <div class="bg-white rounded-3xl border-2 border-slate-200 p-8 shadow-sm">
        <form action="{{ route('guru.classes.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-black text-slate-700 mb-2">Nama Kelas</label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}"
                       placeholder="Contoh: Matematika Ceria 1-A"
                       class="w-full px-4 py-3 rounded-2xl border-2 border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 outline-none font-bold text-slate-800"
                       required>
                @error('name')
                    <p class="text-rose-500 text-xs font-bold mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="kelas_level" class="block text-sm font-black text-slate-700 mb-2">Tingkatan Kelas (SD)</label>
                <select id="kelas_level" name="kelas_level" class="w-full px-4 py-3 rounded-2xl border-2 border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 outline-none font-bold text-slate-800" required>
                    <option value="">Pilih Tingkat Kelas</option>
                    @for($i = 1; $i <= 6; $i++)
                        <option value="{{ $i }}" {{ old('kelas_level') == $i ? 'selected' : '' }}>Kelas {{ $i }} SD</option>
                    @endfor
                </select>
                @error('kelas_level')
                    <p class="text-rose-500 text-xs font-bold mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-black text-slate-700 mb-2">Pilih Topik Materi yang Ditugaskan (Opsional)</label>
                <p class="text-xs text-slate-500 mb-3">Siswa dalam kelas ini akan difokuskan pada materi-materi berikut:</p>
                <div class="space-y-2 max-h-56 overflow-y-auto p-3 border-2 border-slate-100 rounded-2xl bg-slate-50">
                    @forelse($topics as $topic)
                        <label class="flex items-center gap-3 p-2 bg-white rounded-xl border border-slate-200 hover:border-emerald-400 cursor-pointer transition">
                            <input type="checkbox" name="topic_ids[]" value="{{ $topic->id }}" 
                                   {{ is_array(old('topic_ids')) && in_array($topic->id, old('topic_ids')) ? 'checked' : '' }}
                                   class="rounded text-emerald-600 focus:ring-emerald-500 w-4 h-4">
                            <span class="text-sm font-bold text-slate-800">
                                {{ $topic->title }} 
                                <span class="text-xs text-slate-400 font-normal">(Kelas {{ $topic->kelas_level }})</span>
                            </span>
                        </label>
                    @empty
                        <p class="text-xs text-slate-400 p-2">Belum ada topik materi yang tersedia.</p>
                    @endforelse
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3">
                <a href="{{ route('guru.classes.index') }}" class="px-5 py-3 rounded-2xl border-2 border-slate-200 font-bold text-sm text-slate-600 hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-7 py-3 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-black text-sm shadow-md transition transform active:scale-95">
                    ✨ Buat Kelas & Dapatkan Kode
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
