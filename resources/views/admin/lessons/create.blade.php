@extends('layouts.admin')

@section('page-title', 'Tambah Materi Pelajaran')
@section('page-subtitle', 'Buat materi matematika baru untuk kurikulum siswa')

@section('content')
<div class="max-w-3xl space-y-6">

    <div class="flex items-center gap-3">
        <a href="{{ route('admin.lessons.index') }}" class="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition font-bold text-xs">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8 shadow-sm">
        <form action="{{ route('admin.lessons.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-black text-slate-300 uppercase tracking-wider mb-2">Topik Pembelajaran</label>
                    <select name="topic_id" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-4 py-3 text-xs font-semibold focus:border-amber-400 focus:outline-none">
                        <option value="">Pilih Topik</option>
                        @foreach($topics as $topic)
                            <option value="{{ $topic->id }}" {{ old('topic_id') == $topic->id ? 'selected' : '' }}>
                                Kelas {{ $topic->kelas_level }} - {{ $topic->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('topic_id') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-300 uppercase tracking-wider mb-2">Urutan Tampil (#)</label>
                    <input type="number" name="order" value="{{ old('order', 1) }}" min="0" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-4 py-3 text-xs font-semibold focus:border-amber-400 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-black text-slate-300 uppercase tracking-wider mb-2">Judul Materi</label>
                <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Pengenalan Angka 1 sampai 10" class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-4 py-3 text-xs font-semibold focus:border-amber-400 focus:outline-none">
                @error('title') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-black text-slate-300 uppercase tracking-wider mb-2">Isi Konten Materi (Teks, Penjelasan, Rumus Sederhana)</label>
                <textarea name="content" rows="8" required placeholder="Tulis materi pembelajaran secara terstruktur..." class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl p-4 text-xs font-semibold focus:border-amber-400 focus:outline-none leading-relaxed">{{ old('content') }}</textarea>
                @error('content') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-black text-slate-300 uppercase tracking-wider mb-2">Tingkat Kesulitan</label>
                    <select name="difficulty" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-4 py-3 text-xs font-semibold focus:border-amber-400 focus:outline-none">
                        <option value="mudah" {{ old('difficulty') === 'mudah' ? 'selected' : '' }}>Mudah</option>
                        <option value="sedang" {{ old('difficulty', 'sedang') === 'sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="sulit" {{ old('difficulty') === 'sulit' ? 'selected' : '' }}>Sulit</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-300 uppercase tracking-wider mb-2">Estimasi Waktu Belajar (Menit)</label>
                    <input type="number" name="estimated_minutes" value="{{ old('estimated_minutes', 10) }}" min="1" max="180" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-4 py-3 text-xs font-semibold focus:border-amber-400 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-300 uppercase tracking-wider mb-2">Video Pendukung (URL Opsional)</label>
                    <input type="url" name="video_url" value="{{ old('video_url') }}" placeholder="https://youtube.com/..." class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-4 py-3 text-xs font-semibold focus:border-amber-400 focus:outline-none">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route('admin.lessons.index') }}" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-bold transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-black text-xs rounded-xl shadow-lg transition">
                    Simpan Materi
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
