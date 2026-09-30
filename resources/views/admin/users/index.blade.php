@extends('layouts.admin')

@section('title', 'Manajemen Pengguna - RoboMath')

@section('content')
<div class="space-y-6">

    <!-- KPI Summary Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white border-2 border-amber-200/80 rounded-3xl p-6 shadow-sm space-y-1">
            <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Total Siswa</span>
            <div class="text-3xl font-black text-slate-900 mt-1">{{ $totalSiswa }}</div>
            <p class="text-xs text-slate-400">Akun siswa terdaftar aktif</p>
        </div>
        <div class="bg-white border-2 border-amber-200/80 rounded-3xl p-6 shadow-sm space-y-1">
            <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Total Guru</span>
            <div class="text-3xl font-black text-indigo-600 mt-1">{{ $totalGuru }}</div>
            <p class="text-xs text-slate-400">Pendidik pengampu kelas</p>
        </div>
        <div class="bg-white border-2 border-amber-200/80 rounded-3xl p-6 shadow-sm space-y-1">
            <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Akun Nonaktif</span>
            <div class="text-3xl font-black text-rose-600 mt-1">{{ $totalInactive }}</div>
            <p class="text-xs text-slate-400">Akses ditangguhkan</p>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
            <div class="relative w-64">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau email..." class="w-full bg-white border-2 border-amber-200 text-slate-800 rounded-2xl pl-10 pr-4 py-2.5 text-xs font-bold focus:border-amber-400 focus:outline-none shadow-sm">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <select name="role" onchange="this.form.submit()" class="bg-white border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-2.5 text-xs font-bold focus:border-amber-400 focus:outline-none shadow-sm">
                <option value="all" {{ $roleFilter === 'all' ? 'selected' : '' }}>Semua Peran</option>
                <option value="siswa" {{ $roleFilter === 'siswa' ? 'selected' : '' }}>Siswa</option>
                <option value="guru" {{ $roleFilter === 'guru' ? 'selected' : '' }}>Guru</option>
                <option value="orangtua" {{ $roleFilter === 'orangtua' ? 'selected' : '' }}>Orang Tua</option>
            </select>

            <select name="status" onchange="this.form.submit()" class="bg-white border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-2.5 text-xs font-bold focus:border-amber-400 focus:outline-none shadow-sm">
                <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>Semua Status</option>
                <option value="active" {{ $statusFilter === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ $statusFilter === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>

            @if($search || $roleFilter !== 'all' || $statusFilter !== 'all')
                <a href="{{ route('admin.users.index') }}" class="text-xs text-rose-500 hover:underline font-bold">Reset Filter</a>
            @endif
        </form>
    </div>

    <!-- Users Table Card -->
    <div class="bg-white border-2 border-amber-200/80 rounded-3xl overflow-hidden shadow-sm">
        <div class="p-6 border-b-2 border-amber-100 flex items-center justify-between">
            <h3 class="font-black text-slate-800 text-base">Daftar Akun Pengguna</h3>
            <span class="text-xs text-slate-500 font-extrabold bg-amber-50 border border-amber-200 px-3 py-1 rounded-full">{{ $users->total() }} Pengguna</span>
        </div>

        @if($users->isEmpty())
        <div class="p-12 text-center text-slate-400 font-semibold text-sm">Tidak ada pengguna yang sesuai pencarian.</div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-amber-50/50 text-slate-500 uppercase font-black tracking-wider border-b-2 border-amber-100">
                    <tr>
                        <th class="px-6 py-4">Nama & Email</th>
                        <th class="px-6 py-4">Peran (Role)</th>
                        <th class="px-6 py-4">Detail</th>
                        <th class="px-6 py-4">Status Akun</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber-100 text-slate-700">
                    @foreach($users as $u)
                    <tr class="hover:bg-amber-50/40 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">{{ $u->name }}</div>
                            <div class="text-[11px] text-slate-500">{{ $u->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-xl text-[10px] font-black uppercase {{ $u->role === 'siswa' ? 'bg-amber-100 text-amber-900 border border-amber-300' : ($u->role === 'guru' ? 'bg-indigo-100 text-indigo-900 border border-indigo-300' : 'bg-emerald-100 text-emerald-900 border border-emerald-300') }}">
                                {{ $u->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            @if($u->role === 'siswa')
                                Kelas {{ $u->kelas ?? '-' }} SD • <span class="font-bold text-amber-700">{{ number_format($u->total_score) }} Poin</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black {{ $u->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $u->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('admin.users.toggle', $u) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun ini?')">
                                @csrf
                                <button type="submit" class="px-3.5 py-1.5 rounded-xl font-black text-xs transition {{ $u->is_active ? 'bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200' : 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm' }}">
                                    {{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-5 border-t-2 border-amber-100">
            {{ $users->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
