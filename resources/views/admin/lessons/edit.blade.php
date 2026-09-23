@extends('layouts.admin')

@section('page-title', 'Edit Materi')
@section('page-subtitle', 'Perbarui konten modul matematika')

@section('content')
<div class="max-w-3xl space-y-6">

    <div class="flex items-center gap-3">
        <a href="{{ route('admin.lessons.index') }}" class="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition font-bold text-xs">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8 shadow-sm">
        <form action="{{ route('admin.lessons.update', $lesson) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-black text-slate-300 uppercase tracking-wider mb-2">Topik Pembelajaran</label>
                    <select name="topic_id" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-4 py-3 text-xs font-semibold focus:border-amber-400 focus:outline-none">
                        @foreach($topics as $topic)
                            <option value="{{ $topic->id }}" {{ old('topic_id', $lesson->topic_id) == $topic->id ? 'selected' : '' }}>
                                Kelas {{ $topic->kelas_level }} - {{ $topic->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-300 uppercase tracking-wider mb-2">Urutan Tampil (#)</label>
                    <input type="number" name="order" value="{{ old('order', $lesson->order) }}" min="0" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-4 py-3 text-xs font-semibold focus:border-amber-400 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-black text-slate-300 uppercase tracking-wider mb-2">Judul Materi</label>
                <input type="text" name="title" value="{{ old('title', $lesson->title) }}" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-4 py-3 text-xs font-semibold focus:border-amber-400 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-black text-slate-300 uppercase tracking-wider mb-2">Isi Konten Materi</label>
                <textarea name="content" rows="8" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl p-4 text-xs font-semibold focus:border-amber-400 focus:outline-none leading-relaxed">{{ old('content', $lesson->content) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-black text-slate-300 uppercase tracking-wider mb-2">Tingkat Kesulitan</label>
                    <select name="difficulty" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-4 py-3 text-xs font-semibold focus:border-amber-400 focus:outline-none">
                        <option value="mudah" {{ old('difficulty', $lesson->difficulty) === 'mudah' ? 'selected' : '' }}>Mudah</option>
                        <option value="sedang" {{ old('difficulty', $lesson->difficulty) === 'sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="sulit" {{ old('difficulty', $lesson->difficulty) === 'sulit' ? 'selected' : '' }}>Sulit</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-300 uppercase tracking-wider mb-2">Estimasi Waktu (Menit)</label>
                    <input type="number" name="estimated_minutes" value="{{ old('estimated_minutes', $lesson->estimated_minutes) }}" min="1" max="180" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-4 py-3 text-xs font-semibold focus:border-amber-400 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-300 uppercase tracking-wider mb-2">Video Pendukung (URL)</label>
                    <input type="url" name="video_url" value="{{ old('video_url', $lesson->video_url) }}" class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-4 py-3 text-xs font-semibold focus:border-amber-400 focus:outline-none">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route('admin.lessons.index') }}" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-bold transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-black text-xs rounded-xl shadow-lg transition">
                    Perbarui Materi
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
