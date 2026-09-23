@extends('layouts.admin')

@section('page-title', 'Tambah Soal Baru')
@section('page-subtitle', 'Buat pertanyaan baru untuk latihan siswa')

@section('content')
<div class="max-w-3xl space-y-6">

    <div class="flex items-center gap-3">
        <a href="{{ route('admin.quizzes.index') }}" class="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition font-bold text-xs">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8 shadow-sm">
        <form action="{{ route('admin.quizzes.store') }}" method="POST" class="space-y-6">
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
                    <label class="block text-xs font-black text-slate-300 uppercase tracking-wider mb-2">Tipe Soal</label>
                    <select name="type" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-4 py-3 text-xs font-semibold focus:border-amber-400 focus:outline-none">
                        <option value="pilihan_ganda" {{ old('type') === 'pilihan_ganda' ? 'selected' : '' }}>Pilihan Ganda</option>
                        <option value="drag_drop" {{ old('type') === 'drag_drop' ? 'selected' : '' }}>Drag & Drop</option>
                        <option value="cerita_logika" {{ old('type') === 'cerita_logika' ? 'selected' : '' }}>Soal Cerita Logika</option>
                    </select>
                    @error('type') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-black text-slate-300 uppercase tracking-wider mb-2">Pertanyaan / Soal</label>
                <textarea name="question" rows="3" required placeholder="Tuliskan bunyi pertanyaan..." class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl p-4 text-xs font-semibold focus:border-amber-400 focus:outline-none">{{ old('question') }}</textarea>
                @error('question') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Pilihan Jawaban (A, B, C, D) -->
            <div class="space-y-3 bg-slate-950 p-5 rounded-xl border border-slate-800">
                <label class="block text-xs font-black text-amber-400 uppercase tracking-wider">Pilihan Opsi Jawaban</label>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <span class="text-xs font-bold text-slate-400">Opsi A:</span>
                        <input type="text" name="options[A]" value="{{ old('options.A') }}" required placeholder="Jawaban A" class="w-full bg-slate-900 border border-slate-700 text-white rounded-lg px-3 py-2 text-xs font-semibold mt-1 focus:border-amber-400 focus:outline-none">
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-400">Opsi B:</span>
                        <input type="text" name="options[B]" value="{{ old('options.B') }}" required placeholder="Jawaban B" class="w-full bg-slate-900 border border-slate-700 text-white rounded-lg px-3 py-2 text-xs font-semibold mt-1 focus:border-amber-400 focus:outline-none">
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-400">Opsi C:</span>
                        <input type="text" name="options[C]" value="{{ old('options.C') }}" required placeholder="Jawaban C" class="w-full bg-slate-900 border border-slate-700 text-white rounded-lg px-3 py-2 text-xs font-semibold mt-1 focus:border-amber-400 focus:outline-none">
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-400">Opsi D:</span>
                        <input type="text" name="options[D]" value="{{ old('options.D') }}" required placeholder="Jawaban D" class="w-full bg-slate-900 border border-slate-700 text-white rounded-lg px-3 py-2 text-xs font-semibold mt-1 focus:border-amber-400 focus:outline-none">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-black text-slate-300 uppercase tracking-wider mb-2">Kunci Jawaban Benar</label>
                    <input type="text" name="correct_answer" value="{{ old('correct_answer') }}" required placeholder="Contoh: A atau isi teks jawaban" class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-4 py-3 text-xs font-semibold focus:border-amber-400 focus:outline-none">
                    @error('correct_answer') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-300 uppercase tracking-wider mb-2">Tingkat Kesulitan</label>
                    <select name="difficulty" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-4 py-3 text-xs font-semibold focus:border-amber-400 focus:outline-none">
                        <option value="mudah" {{ old('difficulty') === 'mudah' ? 'selected' : '' }}>Mudah</option>
                        <option value="sedang" {{ old('difficulty', 'sedang') === 'sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="sulit" {{ old('difficulty') === 'sulit' ? 'selected' : '' }}>Sulit</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-300 uppercase tracking-wider mb-2">Poin Skor</label>
                    <input type="number" name="points" value="{{ old('points', 10) }}" min="1" max="100" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-4 py-3 text-xs font-semibold focus:border-amber-400 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-black text-slate-300 uppercase tracking-wider mb-2">Penjelasan / Pembahasan Logika RoboBot (Feedback Jawaban)</label>
                <textarea name="explanation" rows="2" placeholder="Jelaskan alasan jawaban ini benar dengan bahasa ramah anak..." class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl p-4 text-xs font-semibold focus:border-amber-400 focus:outline-none">{{ old('explanation') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route('admin.quizzes.index') }}" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-bold transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-black text-xs rounded-xl shadow-lg transition">
                    Simpan Soal
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
