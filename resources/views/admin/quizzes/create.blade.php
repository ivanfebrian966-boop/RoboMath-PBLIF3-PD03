@extends('layouts.admin')

@section('title', 'Tambah Soal Baru - RoboMath')

@section('content')
<div class="max-w-3xl space-y-6">

    <div class="flex items-center gap-3">
        <a href="{{ route('admin.quizzes.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-amber-200 text-slate-700 hover:text-amber-800 hover:bg-amber-50 rounded-2xl transition font-black text-xs shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar Soal
        </a>
    </div>

    <div class="bg-white border-2 border-amber-200/80 rounded-3xl p-8 shadow-sm">
        <div class="mb-6 pb-4 border-b-2 border-amber-100">
            <h2 class="text-xl font-black text-slate-800">Form Tambah Soal Baru</h2>
            <p class="text-xs text-slate-500 font-semibold mt-1">Buat butir latihan interaktif untuk melatih logika berpikir matematika siswa SD.</p>
        </div>

        <form action="{{ route('admin.quizzes.store') }}" method="POST" class="space-y-6">
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
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">Tipe Soal</label>
                    <select name="type" required class="w-full bg-amber-50/30 border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-3 text-xs font-bold focus:border-amber-400 focus:outline-none">
                        <option value="pilihan_ganda" {{ old('type') === 'pilihan_ganda' ? 'selected' : '' }}>Pilihan Ganda</option>
                        <option value="drag_drop" {{ old('type') === 'drag_drop' ? 'selected' : '' }}>Drag & Drop</option>
                        <option value="cerita_logika" {{ old('type') === 'cerita_logika' ? 'selected' : '' }}>Soal Cerita Logika</option>
                    </select>
                    @error('type') <p class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">Pertanyaan / Soal</label>
                <textarea name="question" rows="3" required placeholder="Tuliskan bunyi pertanyaan matematika..." class="w-full bg-amber-50/30 border-2 border-amber-200 text-slate-800 rounded-2xl p-4 text-xs font-bold focus:border-amber-400 focus:outline-none">{{ old('question') }}</textarea>
                @error('question') <p class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <!-- Pilihan Jawaban (A, B, C, D) -->
            <div class="space-y-4 bg-amber-50/40 p-6 rounded-2xl border-2 border-amber-200">
                <label class="block text-xs font-black text-amber-900 uppercase tracking-wider">Pilihan Opsi Jawaban</label>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <span class="text-xs font-black text-slate-600">Opsi A:</span>
                        <input type="text" name="options[A]" value="{{ old('options.A') }}" required placeholder="Jawaban A" class="w-full bg-white border-2 border-amber-200 text-slate-800 rounded-xl px-3 py-2 text-xs font-bold mt-1 focus:border-amber-400 focus:outline-none">
                    </div>
                    <div>
                        <span class="text-xs font-black text-slate-600">Opsi B:</span>
                        <input type="text" name="options[B]" value="{{ old('options.B') }}" required placeholder="Jawaban B" class="w-full bg-white border-2 border-amber-200 text-slate-800 rounded-xl px-3 py-2 text-xs font-bold mt-1 focus:border-amber-400 focus:outline-none">
                    </div>
                    <div>
                        <span class="text-xs font-black text-slate-600">Opsi C:</span>
                        <input type="text" name="options[C]" value="{{ old('options.C') }}" required placeholder="Jawaban C" class="w-full bg-white border-2 border-amber-200 text-slate-800 rounded-xl px-3 py-2 text-xs font-bold mt-1 focus:border-amber-400 focus:outline-none">
                    </div>
                    <div>
                        <span class="text-xs font-black text-slate-600">Opsi D:</span>
                        <input type="text" name="options[D]" value="{{ old('options.D') }}" required placeholder="Jawaban D" class="w-full bg-white border-2 border-amber-200 text-slate-800 rounded-xl px-3 py-2 text-xs font-bold mt-1 focus:border-amber-400 focus:outline-none">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">Kunci Jawaban Benar</label>
                    <input type="text" name="correct_answer" value="{{ old('correct_answer') }}" required placeholder="Contoh: A atau teks jawaban" class="w-full bg-amber-50/30 border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-3 text-xs font-bold focus:border-amber-400 focus:outline-none">
                    @error('correct_answer') <p class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">Tingkat Kesulitan</label>
                    <select name="difficulty" required class="w-full bg-amber-50/30 border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-3 text-xs font-bold focus:border-amber-400 focus:outline-none">
                        <option value="mudah" {{ old('difficulty') === 'mudah' ? 'selected' : '' }}>Mudah</option>
                        <option value="sedang" {{ old('difficulty', 'sedang') === 'sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="sulit" {{ old('difficulty') === 'sulit' ? 'selected' : '' }}>Sulit</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">Poin Skor</label>
                    <input type="number" name="points" value="{{ old('points', 10) }}" min="1" max="100" required class="w-full bg-amber-50/30 border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-3 text-xs font-bold focus:border-amber-400 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">Penjelasan / Pembahasan Logika RoboBot</label>
                <textarea name="explanation" rows="2" placeholder="Jelaskan alasan jawaban ini benar dengan bahasa ramah anak..." class="w-full bg-amber-50/30 border-2 border-amber-200 text-slate-800 rounded-2xl p-4 text-xs font-bold focus:border-amber-400 focus:outline-none">{{ old('explanation') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t-2 border-amber-100">
                <a href="{{ route('admin.quizzes.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl text-xs font-black transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-black text-xs rounded-2xl shadow-md transition transform active:scale-95">
                    Simpan Soal Baru
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
