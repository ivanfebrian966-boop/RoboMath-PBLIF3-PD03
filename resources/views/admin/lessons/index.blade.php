@extends('layouts.admin')

@section('page-title', 'Manajemen Materi Modul')
@section('page-subtitle', 'Kelola modul materi matematika kurikulum SD kelas 1 hingga 6')

@section('content')
<div class="space-y-6">

    <!-- Header Actions & Filter -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Filter Topik -->
        <form action="{{ route('admin.lessons.index') }}" method="GET" class="flex items-center gap-3 w-full md:w-auto">
            <select name="topic_id" onchange="this.form.submit()" class="bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-2.5 text-xs font-semibold focus:border-amber-400 focus:outline-none">
                <option value="">Semua Topik</option>
                @foreach($topics as $topic)
                    <option value="{{ $topic->id }}" {{ $topicFilter == $topic->id ? 'selected' : '' }}>
                        Kelas {{ $topic->kelas_level }} - {{ $topic->title }}
                    </option>
                @endforeach
            </select>
            @if($topicFilter)
                <a href="{{ route('admin.lessons.index') }}" class="text-xs text-rose-400 hover:underline font-bold">Reset</a>
            @endif
        </form>

        <a href="{{ route('admin.lessons.create') }}" class="w-full md:w-auto px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-black flex items-center justify-center gap-2 shadow-lg transition">
            <span>➕</span> Tambah Materi Baru
        </a>
    </div>

    <!-- Table -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-slate-800 flex items-center justify-between">
            <h3 class="font-bold text-white text-sm">Daftar Modul Pembelajaran</h3>
            <span class="text-xs text-slate-400 font-semibold">{{ $lessons->total() }} Total Modul</span>
        </div>

        @if($lessons->isEmpty())
        <div class="p-12 text-center text-slate-500 font-semibold text-sm">
            Tidak ada materi yang ditemukan.
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950 text-slate-400 uppercase font-black tracking-wider border-b border-slate-800">
                    <tr>
                        <th class="px-6 py-4">Urutan</th>
                        <th class="px-6 py-4">Judul Materi</th>
                        <th class="px-6 py-4">Topik</th>
                        <th class="px-6 py-4">Kesulitan</th>
                        <th class="px-6 py-4">Estimasi Waktu</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 text-slate-300">
                    @foreach($lessons as $lesson)
                    <tr class="hover:bg-slate-800/40 transition">
                        <td class="px-6 py-4 font-mono font-bold text-slate-400">
                            #{{ $lesson->order }}
                        </td>
                        <td class="px-6 py-4 font-bold text-white">
                            {{ $lesson->title }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-slate-300 font-medium">{{ $lesson->topic->title ?? '-' }}</span>
                            <span class="block text-[10px] text-slate-500">Kelas {{ $lesson->topic->kelas_level ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase {{ $lesson->difficulty === 'mudah' ? 'bg-emerald-900/50 text-emerald-400' : ($lesson->difficulty === 'sedang' ? 'bg-amber-900/50 text-amber-400' : 'bg-rose-900/50 text-rose-400') }}">
                                {{ $lesson->difficulty }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-400">
                            ⏱️ {{ $lesson->estimated_minutes }} Menit
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.lessons.edit', $lesson) }}" class="inline-block px-2.5 py-1 bg-slate-800 hover:bg-slate-700 text-amber-400 rounded-lg font-bold text-[11px] transition">
                                Edit
                            </a>
                            <form action="{{ route('admin.lessons.destroy', $lesson) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus materi ini?')">
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
            {{ $lessons->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
