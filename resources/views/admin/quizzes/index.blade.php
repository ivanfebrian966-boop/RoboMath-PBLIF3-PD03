@extends('layouts.admin')

@section('page-title', 'Manajemen Bank Soal')
@section('page-subtitle', 'Kelola pertanyaan latihan, pilihan ganda, drag-and-drop, dan cerita logika')

@section('content')
<div class="space-y-6">

    <!-- Header Actions & Filter -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Filters -->
        <form action="{{ route('admin.quizzes.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <select name="topic_id" onchange="this.form.submit()" class="bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-2.5 text-xs font-semibold focus:border-amber-400 focus:outline-none">
                <option value="">Semua Topik</option>
                @foreach($topics as $topic)
                    <option value="{{ $topic->id }}" {{ $topicFilter == $topic->id ? 'selected' : '' }}>
                        Kelas {{ $topic->kelas_level }} - {{ $topic->title }}
                    </option>
                @endforeach
            </select>

            <select name="type" onchange="this.form.submit()" class="bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-2.5 text-xs font-semibold focus:border-amber-400 focus:outline-none">
                <option value="">Semua Tipe Soal</option>
                <option value="pilihan_ganda" {{ $typeFilter === 'pilihan_ganda' ? 'selected' : '' }}>Pilihan Ganda</option>
                <option value="drag_drop" {{ $typeFilter === 'drag_drop' ? 'selected' : '' }}>Drag & Drop</option>
                <option value="cerita_logika" {{ $typeFilter === 'cerita_logika' ? 'selected' : '' }}>Cerita Logika</option>
            </select>

            @if($topicFilter || $typeFilter)
                <a href="{{ route('admin.quizzes.index') }}" class="text-xs text-rose-400 hover:underline font-bold">Reset</a>
            @endif
        </form>

        <!-- Tambah Button -->
        <a href="{{ route('admin.quizzes.create') }}" class="w-full md:w-auto px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-black flex items-center justify-center gap-2 shadow-lg transition">
            <span>➕</span> Tambah Soal Baru
        </a>
    </div>

    <!-- Table -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-slate-800 flex items-center justify-between">
            <h3 class="font-bold text-white text-sm">Daftar Pertanyaan Kuis</h3>
            <span class="text-xs text-slate-400 font-semibold">{{ $quizzes->total() }} Total Soal</span>
        </div>

        @if($quizzes->isEmpty())
        <div class="p-12 text-center text-slate-500 font-semibold text-sm">
            Tidak ada soal yang sesuai dengan filter pencarian.
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950 text-slate-400 uppercase font-black tracking-wider border-b border-slate-800">
                    <tr>
                        <th class="px-6 py-4">Soal</th>
                        <th class="px-6 py-4">Topik</th>
                        <th class="px-6 py-4">Tipe</th>
                        <th class="px-6 py-4">Kunci Jawaban</th>
                        <th class="px-6 py-4">Kesulitan</th>
                        <th class="px-6 py-4">Poin</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 text-slate-300">
                    @foreach($quizzes as $quiz)
                    <tr class="hover:bg-slate-800/40 transition">
                        <td class="px-6 py-4 font-bold text-white max-w-xs truncate">
                            {!! strip_tags($quiz->question) !!}
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-slate-300 font-medium">{{ $quiz->topic->title ?? '-' }}</span>
                            <span class="block text-[10px] text-slate-500">Kelas {{ $quiz->topic->kelas_level ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase {{ $quiz->type === 'pilihan_ganda' ? 'bg-blue-900/60 text-blue-300' : ($quiz->type === 'drag_drop' ? 'bg-purple-900/60 text-purple-300' : 'bg-emerald-900/60 text-emerald-300') }}">
                                {{ str_replace('_', ' ', $quiz->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-mono font-bold text-amber-400">
                            {{ $quiz->correct_answer }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase {{ $quiz->difficulty === 'mudah' ? 'bg-emerald-900/50 text-emerald-400' : ($quiz->difficulty === 'sedang' ? 'bg-amber-900/50 text-amber-400' : 'bg-rose-900/50 text-rose-400') }}">
                                {{ $quiz->difficulty }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-black text-amber-400">
                            ⭐ {{ $quiz->points }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="inline-block px-2.5 py-1 bg-slate-800 hover:bg-slate-700 text-amber-400 rounded-lg font-bold text-[11px] transition">
                                Edit
                            </a>
                            <form action="{{ route('admin.quizzes.destroy', $quiz) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus soal ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2.5 py-1 bg-slate-800 hover:bg-rose-900/50 text-rose-400 rounded-lg font-bold text-[11px] transition">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-5 border-t border-slate-800">
            {{ $quizzes->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
