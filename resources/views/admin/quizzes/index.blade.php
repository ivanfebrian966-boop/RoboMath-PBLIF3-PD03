@extends('layouts.admin')

@section('title', 'Manajemen Bank Soal - RoboMath')

@section('content')
<div class="space-y-6">

    <!-- Header Actions & Filter -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Filters -->
        <form action="{{ route('admin.quizzes.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <select name="topic_id" onchange="this.form.submit()" class="bg-white border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-2.5 text-xs font-bold focus:border-amber-400 focus:outline-none shadow-sm">
                <option value="">Semua Topik</option>
                @foreach($topics as $topic)
                    <option value="{{ $topic->id }}" {{ $topicFilter == $topic->id ? 'selected' : '' }}>
                        Kelas {{ $topic->kelas_level }} - {{ $topic->title }}
                    </option>
                @endforeach
            </select>

            <select name="type" onchange="this.form.submit()" class="bg-white border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-2.5 text-xs font-bold focus:border-amber-400 focus:outline-none shadow-sm">
                <option value="">Semua Tipe Soal</option>
                <option value="pilihan_ganda" {{ $typeFilter === 'pilihan_ganda' ? 'selected' : '' }}>Pilihan Ganda</option>
                <option value="drag_drop" {{ $typeFilter === 'drag_drop' ? 'selected' : '' }}>Drag & Drop</option>
                <option value="cerita_logika" {{ $typeFilter === 'cerita_logika' ? 'selected' : '' }}>Cerita Logika</option>
            </select>

            @if($topicFilter || $typeFilter)
                <a href="{{ route('admin.quizzes.index') }}" class="text-xs text-rose-500 hover:underline font-bold">Reset Filter</a>
            @endif
        </form>

        <!-- Tambah Button -->
        <a href="{{ route('admin.quizzes.create') }}" class="w-full md:w-auto px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-2xl text-xs font-black flex items-center justify-center gap-2 shadow-md transition transform active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Tambah Soal Baru
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white border-2 border-amber-200/80 rounded-3xl overflow-hidden shadow-sm">
        <div class="p-6 border-b-2 border-amber-100 flex items-center justify-between">
            <h3 class="font-black text-slate-800 text-base">Daftar Pertanyaan Latihan</h3>
            <span class="text-xs text-slate-500 font-extrabold bg-amber-50 border border-amber-200 px-3 py-1 rounded-full">{{ $quizzes->total() }} Total Soal</span>
        </div>

        @if($quizzes->isEmpty())
        <div class="p-12 text-center text-slate-400 font-semibold text-sm">
            Tidak ada soal yang sesuai dengan filter pencarian.
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-amber-50/50 text-slate-500 uppercase font-black tracking-wider border-b-2 border-amber-100">
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
                <tbody class="divide-y divide-amber-100 text-slate-700">
                    @foreach($quizzes as $quiz)
                    <tr class="hover:bg-amber-50/40 transition">
                        <td class="px-6 py-4 font-bold text-slate-900 max-w-xs truncate">
                            {!! strip_tags($quiz->question) !!}
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-slate-800 font-extrabold">{{ $quiz->topic->title ?? '-' }}</span>
                            <span class="block text-[10px] text-slate-500">Kelas {{ $quiz->topic->kelas_level ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase {{ $quiz->type === 'pilihan_ganda' ? 'bg-blue-100 text-blue-800' : ($quiz->type === 'drag_drop' ? 'bg-purple-100 text-purple-800' : 'bg-emerald-100 text-emerald-800') }}">
                                {{ str_replace('_', ' ', $quiz->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-mono font-bold text-indigo-700 bg-indigo-50/50 px-2 rounded">
                            {{ $quiz->correct_answer }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase {{ $quiz->difficulty === 'mudah' ? 'bg-emerald-100 text-emerald-800' : ($quiz->difficulty === 'sedang' ? 'bg-amber-100 text-amber-900' : 'bg-rose-100 text-rose-800') }}">
                                {{ $quiz->difficulty }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-black text-amber-700">
                            {{ $quiz->points }} Poin
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="inline-block px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-900 rounded-xl font-black text-xs transition border border-amber-200">
                                Edit
                            </a>
                            <form action="{{ route('admin.quizzes.destroy', $quiz) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus soal ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl font-black text-xs transition border border-red-200">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-5 border-t-2 border-amber-100">
            {{ $quizzes->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
