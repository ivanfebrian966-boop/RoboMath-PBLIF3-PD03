@extends('layouts.admin')

@section('page-title', 'Manajemen Pengguna')
@section('page-subtitle', 'Kelola akun Siswa, Guru, dan Orang Tua serta kontrol akses')

@section('content')
<div class="space-y-6">

    <!-- KPI Summary Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <span class="text-xs font-black text-slate-400 uppercase tracking-wider">Total Siswa</span>
            <div class="text-3xl font-black text-white mt-1">{{ $totalSiswa }}</div>
        </div>
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <span class="text-xs font-black text-slate-400 uppercase tracking-wider">Total Guru</span>
            <div class="text-3xl font-black text-indigo-400 mt-1">{{ $totalGuru }}</div>
        </div>
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <span class="text-xs font-black text-slate-400 uppercase tracking-wider">Akun Nonaktif</span>
            <div class="text-3xl font-black text-rose-400 mt-1">{{ $totalInactive }}</div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau email..." class="bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-2.5 text-xs font-semibold focus:border-amber-400 focus:outline-none w-56">

            <select name="role" onchange="this.form.submit()" class="bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-2.5 text-xs font-semibold focus:border-amber-400 focus:outline-none">
                <option value="all" {{ $roleFilter === 'all' ? 'selected' : '' }}>Semua Peran</option>
                <option value="siswa" {{ $roleFilter === 'siswa' ? 'selected' : '' }}>Siswa</option>
                <option value="guru" {{ $roleFilter === 'guru' ? 'selected' : '' }}>Guru</option>
                <option value="orangtua" {{ $roleFilter === 'orangtua' ? 'selected' : '' }}>Orang Tua</option>
            </select>

            <select name="status" onchange="this.form.submit()" class="bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-2.5 text-xs font-semibold focus:border-amber-400 focus:outline-none">
                <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>Semua Status</option>
                <option value="active" {{ $statusFilter === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ $statusFilter === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>

            @if($search || $roleFilter !== 'all' || $statusFilter !== 'all')
                <a href="{{ route('admin.users.index') }}" class="text-xs text-rose-400 hover:underline font-bold">Reset</a>
            @endif
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-slate-800 flex items-center justify-between">
            <h3 class="font-bold text-white text-sm">Daftar Akun Pengguna</h3>
            <span class="text-xs text-slate-400 font-semibold">{{ $users->total() }} Pengguna</span>
        </div>

        @if($users->isEmpty())
        <div class="p-12 text-center text-slate-500 text-sm">Tidak ada pengguna yang sesuai.</div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950 text-slate-400 uppercase font-black tracking-wider border-b border-slate-800">
                    <tr>
                        <th class="px-6 py-4">Nama & Email</th>
                        <th class="px-6 py-4">Peran (Role)</th>
                        <th class="px-6 py-4">Detail</th>
                        <th class="px-6 py-4">Status Akun</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 text-slate-300">
                    @foreach($users as $u)
                    <tr class="hover:bg-slate-800/40 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-white">{{ $u->name }}</div>
                            <div class="text-[11px] text-slate-500">{{ $u->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase {{ $u->role === 'siswa' ? 'bg-amber-900/60 text-amber-300' : ($u->role === 'guru' ? 'bg-indigo-900/60 text-indigo-300' : 'bg-emerald-900/60 text-emerald-300') }}">
                                {{ $u->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-400">
                            @if($u->role === 'siswa')
                                Kelas {{ $u->kelas ?? '-' }} • ⭐ {{ number_format($u->total_score) }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black {{ $u->is_active ? 'bg-emerald-900/50 text-emerald-300' : 'bg-rose-900/50 text-rose-300' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $u->is_active ? 'bg-emerald-400' : 'bg-rose-400' }}"></span>
                                {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('admin.users.toggle', $u) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun ini?')">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 rounded-xl font-bold text-[11px] transition {{ $u->is_active ? 'bg-slate-800 hover:bg-rose-900/50 text-rose-400' : 'bg-emerald-700 hover:bg-emerald-600 text-white' }}">
                                    {{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-5 border-t border-slate-800">
            {{ $users->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
