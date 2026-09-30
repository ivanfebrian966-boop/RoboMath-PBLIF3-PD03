@extends('layouts.admin')

@section('title', 'Manajemen Materi Modul - RoboMath')

@section('content')
<div class="space-y-6">

    <!-- Header Actions & Filter -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Filter Topik -->
        <form action="{{ route('admin.lessons.index') }}" method="GET" class="flex items-center gap-3 w-full md:w-auto">
            <select name="topic_id" onchange="this.form.submit()" class="bg-white border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-2.5 text-xs font-bold focus:border-amber-400 focus:outline-none shadow-sm">
                <option value="">Semua Topik</option>
                @foreach($topics as $topic)
                    <option value="{{ $topic->id }}" {{ $topicFilter == $topic->id ? 'selected' : '' }}>
                        Kelas {{ $topic->kelas_level }} - {{ $topic->title }}
                    </option>
                @endforeach
            </select>
            @if($topicFilter)
                <a href="{{ route('admin.lessons.index') }}" class="text-xs text-rose-500 hover:underline font-bold">Reset Filter</a>
            @endif
        </form>

        <a href="{{ route('admin.lessons.create') }}" class="w-full md:w-auto px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-2xl text-xs font-black flex items-center justify-center gap-2 shadow-md transition transform active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Tambah Materi Baru
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white border-2 border-amber-200/80 rounded-3xl overflow-hidden shadow-sm">
        <div class="p-6 border-b-2 border-amber-100 flex items-center justify-between">
            <h3 class="font-black text-slate-800 text-base">Daftar Modul Pembelajaran</h3>
            <span class="text-xs text-slate-500 font-extrabold bg-amber-50 border border-amber-200 px-3 py-1 rounded-full">{{ $lessons->total() }} Total Modul</span>
        </div>

        @if($lessons->isEmpty())
        <div class="p-12 text-center text-slate-400 font-semibold text-sm">
            Tidak ada materi yang ditemukan.
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-amber-50/50 text-slate-500 uppercase font-black tracking-wider border-b-2 border-amber-100">
                    <tr>
                        <th class="px-6 py-4">Urutan</th>
                        <th class="px-6 py-4">Judul Materi</th>
                        <th class="px-6 py-4">Topik</th>
                        <th class="px-6 py-4">Kesulitan</th>
                        <th class="px-6 py-4">Estimasi Waktu</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber-100 text-slate-700">
                    @foreach($lessons as $lesson)
                    <tr class="hover:bg-amber-50/40 transition">
                        <td class="px-6 py-4 font-mono font-bold text-slate-500">
                            #{{ $lesson->order }}
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-900">
                            {{ $lesson->title }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-slate-800 font-extrabold">{{ $lesson->topic->title ?? '-' }}</span>
                            <span class="block text-[10px] text-slate-500">Kelas {{ $lesson->topic->kelas_level ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase {{ $lesson->difficulty === 'mudah' ? 'bg-emerald-100 text-emerald-800' : ($lesson->difficulty === 'sedang' ? 'bg-amber-100 text-amber-900' : 'bg-rose-100 text-rose-800') }}">
                                {{ $lesson->difficulty }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-600">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $lesson->estimated_minutes }} Menit
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.lessons.edit', $lesson) }}" class="inline-block px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-900 rounded-xl font-black text-xs transition border border-amber-200">
                                Edit
                            </a>
                            <form action="{{ route('admin.lessons.destroy', $lesson) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus materi ini?')">
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
            {{ $lessons->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
