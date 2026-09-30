@extends('layouts.admin')

@section('title', 'Edit Materi - RoboMath')

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
            <h2 class="text-xl font-black text-slate-800">Edit Materi Modul</h2>
            <p class="text-xs text-slate-500 font-semibold mt-1">Perbarui penjelasan materi matematika atau urutan modul.</p>
        </div>

        <form action="{{ route('admin.lessons.update', $lesson) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">Topik Pembelajaran</label>
                    <select name="topic_id" required class="w-full bg-amber-50/30 border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-3 text-xs font-bold focus:border-amber-400 focus:outline-none">
                        @foreach($topics as $topic)
                            <option value="{{ $topic->id }}" {{ old('topic_id', $lesson->topic_id) == $topic->id ? 'selected' : '' }}>
                                Kelas {{ $topic->kelas_level }} - {{ $topic->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">Urutan Tampil (#)</label>
                    <input type="number" name="order" value="{{ old('order', $lesson->order) }}" min="0" required class="w-full bg-amber-50/30 border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-3 text-xs font-bold focus:border-amber-400 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">Judul Materi</label>
                <input type="text" name="title" value="{{ old('title', $lesson->title) }}" required class="w-full bg-amber-50/30 border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-3 text-xs font-bold focus:border-amber-400 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">Isi Konten Materi</label>
                <textarea name="content" rows="8" required class="w-full bg-amber-50/30 border-2 border-amber-200 text-slate-800 rounded-2xl p-4 text-xs font-bold focus:border-amber-400 focus:outline-none leading-relaxed">{{ old('content', $lesson->content) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">Tingkat Kesulitan</label>
                    <select name="difficulty" required class="w-full bg-amber-50/30 border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-3 text-xs font-bold focus:border-amber-400 focus:outline-none">
                        <option value="mudah" {{ old('difficulty', $lesson->difficulty) === 'mudah' ? 'selected' : '' }}>Mudah</option>
                        <option value="sedang" {{ old('difficulty', $lesson->difficulty) === 'sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="sulit" {{ old('difficulty', $lesson->difficulty) === 'sulit' ? 'selected' : '' }}>Sulit</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">Estimasi Waktu (Menit)</label>
                    <input type="number" name="estimated_minutes" value="{{ old('estimated_minutes', $lesson->estimated_minutes) }}" min="1" max="180" required class="w-full bg-amber-50/30 border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-3 text-xs font-bold focus:border-amber-400 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">Video Pendukung (URL)</label>
                    <input type="url" name="video_url" value="{{ old('video_url', $lesson->video_url) }}" class="w-full bg-amber-50/30 border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-3 text-xs font-bold focus:border-amber-400 focus:outline-none">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t-2 border-amber-100">
                <a href="{{ route('admin.lessons.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl text-xs font-black transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-black text-xs rounded-2xl shadow-md transition transform active:scale-95">
                    Perbarui Materi
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
