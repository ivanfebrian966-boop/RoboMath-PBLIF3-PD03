@extends('layouts.admin')

@section('title', 'Tambah Materi Pelajaran - RoboMath')

@section('content')
<div class="max-w-3xl space-y-6">

    <div class="flex items-center gap-3">
        <a href="{{ route('admin.lessons.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-amber-200 text-slate-700 hover:text-amber-800 hover:bg-amber-50 rounded-2xl transition font-black text-xs shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar Materi
        </a>
    </div>

    <div class="bg-white border-2 border-amber-200/80 rounded-3xl p-8 shadow-sm">
        <div class="mb-6 pb-4 border-b-2 border-amber-100">
            <h2 class="text-xl font-black text-slate-800">Form Tambah Materi Modul</h2>
            <p class="text-xs text-slate-500 font-semibold mt-1">Buat unit pembelajaran baru yang terstruktur untuk kurikulum SD.</p>
        </div>

        <form action="{{ route('admin.lessons.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">Topik Pembelajaran</label>
                    <select name="topic_id" required class="w-full bg-amber-50/30 border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-3 text-xs font-bold focus:border-amber-400 focus:outline-none">
                        <option value="">Pilih Topik</option>
                        @foreach($topics as $topic)
                            <option value="{{ $topic->id }}" {{ old('topic_id') == $topic->id ? 'selected' : '' }}>
                                Kelas {{ $topic->kelas_level }} - {{ $topic->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('topic_id') <p class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">Urutan Tampil (#)</label>
                    <input type="number" name="order" value="{{ old('order', 1) }}" min="0" required class="w-full bg-amber-50/30 border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-3 text-xs font-bold focus:border-amber-400 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">Judul Materi</label>
                <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Pengenalan Angka 1 sampai 10" class="w-full bg-amber-50/30 border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-3 text-xs font-bold focus:border-amber-400 focus:outline-none">
                @error('title') <p class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">Isi Konten Materi (Teks, Penjelasan, Rumus Sederhana)</label>
                <textarea name="content" rows="8" required placeholder="Tulis materi pembelajaran secara interaktif dan ramah anak..." class="w-full bg-amber-50/30 border-2 border-amber-200 text-slate-800 rounded-2xl p-4 text-xs font-bold focus:border-amber-400 focus:outline-none leading-relaxed">{{ old('content') }}</textarea>
                @error('content') <p class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">Tingkat Kesulitan</label>
                    <select name="difficulty" required class="w-full bg-amber-50/30 border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-3 text-xs font-bold focus:border-amber-400 focus:outline-none">
                        <option value="mudah" {{ old('difficulty') === 'mudah' ? 'selected' : '' }}>Mudah</option>
                        <option value="sedang" {{ old('difficulty', 'sedang') === 'sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="sulit" {{ old('difficulty') === 'sulit' ? 'selected' : '' }}>Sulit</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">Estimasi Waktu Belajar (Menit)</label>
                    <input type="number" name="estimated_minutes" value="{{ old('estimated_minutes', 10) }}" min="1" max="180" required class="w-full bg-amber-50/30 border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-3 text-xs font-bold focus:border-amber-400 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">Video Pendukung (URL Opsional)</label>
                    <input type="url" name="video_url" value="{{ old('video_url') }}" placeholder="https://youtube.com/..." class="w-full bg-amber-50/30 border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-3 text-xs font-bold focus:border-amber-400 focus:outline-none">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t-2 border-amber-100">
                <a href="{{ route('admin.lessons.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl text-xs font-black transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-black text-xs rounded-2xl shadow-md transition transform active:scale-95">
                    Simpan Materi
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
