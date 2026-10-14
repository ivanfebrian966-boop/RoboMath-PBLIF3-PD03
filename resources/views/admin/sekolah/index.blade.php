@extends('layouts.admin')

@section('title', 'Manajemen Sekolah - RoboMath')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">🏫 Manajemen Sekolah</h1>
            <p class="text-sm text-slate-500 mt-0.5">Kelola data sekolah yang terdaftar di RoboMath</p>
        </div>
        <a href="{{ route('admin.sekolah.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-400 hover:bg-amber-500 text-amber-900 font-bold rounded-2xl shadow-sm transition-all duration-200 hover:scale-105 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Tambah Sekolah
        </a>
    </div>

    <!-- KPI -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white border-2 border-amber-200/80 rounded-3xl p-6 shadow-sm">
            <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Total Sekolah</span>
            <div class="text-3xl font-black text-slate-900 mt-1">{{ $sekolahList->count() }}</div>
        </div>
        <div class="bg-white border-2 border-emerald-200/80 rounded-3xl p-6 shadow-sm">
            <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Sekolah Aktif</span>
            <div class="text-3xl font-black text-emerald-600 mt-1">{{ $sekolahList->where('status','A')->count() }}</div>
        </div>
        <div class="bg-white border-2 border-rose-200/80 rounded-3xl p-6 shadow-sm">
            <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Nonaktif</span>
            <div class="text-3xl font-black text-rose-600 mt-1">{{ $sekolahList->where('status','N')->count() }}</div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white border-2 border-amber-200/60 rounded-3xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-amber-100">
            <h2 class="font-black text-slate-800">Daftar Sekolah</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-amber-50/80">
                    <tr>
                        <th class="text-left px-5 py-3 font-black text-slate-600 uppercase text-xs tracking-wider">#</th>
                        <th class="text-left px-5 py-3 font-black text-slate-600 uppercase text-xs tracking-wider">Nama Sekolah</th>
                        <th class="text-left px-5 py-3 font-black text-slate-600 uppercase text-xs tracking-wider">Kota</th>
                        <th class="text-left px-5 py-3 font-black text-slate-600 uppercase text-xs tracking-wider">Pengguna</th>
                        <th class="text-left px-5 py-3 font-black text-slate-600 uppercase text-xs tracking-wider">Kelas</th>
                        <th class="text-left px-5 py-3 font-black text-slate-600 uppercase text-xs tracking-wider">Status</th>
                        <th class="text-right px-5 py-3 font-black text-slate-600 uppercase text-xs tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber-100/70">
                    @forelse($sekolahList as $sekolah)
                    <tr class="hover:bg-amber-50/40 transition-colors">
                        <td class="px-5 py-4 text-slate-400 font-bold">{{ $loop->iteration }}</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-2xl bg-amber-100 flex items-center justify-center text-lg">🏫</div>
                                <span class="font-bold text-slate-800">{{ $sekolah->nama_sekolah }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-slate-600">{{ $sekolah->nama_kota ?? '-' }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-bold">
                                👤 {{ $sekolah->users_count }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs font-bold">
                                🚪 {{ $sekolah->class_rooms_count }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            @if($sekolah->status === 'A')
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-bold border border-emerald-200">● Aktif</span>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-xs font-bold border border-slate-200">● Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.sekolah.edit', $sekolah) }}"
                                   class="px-3 py-1.5 text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200 rounded-xl hover:bg-amber-100 transition-colors">
                                    Edit
                                </a>
                                <form action="{{ route('admin.sekolah.destroy', $sekolah) }}" method="POST"
                                      onsubmit="return confirm('Hapus sekolah ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 text-xs font-bold text-rose-700 bg-rose-50 border border-rose-200 rounded-xl hover:bg-rose-100 transition-colors">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-slate-400">
                            <div class="text-5xl mb-3">🏫</div>
                            <p class="font-bold">Belum ada sekolah terdaftar</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
