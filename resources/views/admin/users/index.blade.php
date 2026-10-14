@extends('layouts.admin')

@section('title', 'Kelola Pengguna - RoboMath')

@section('content')
<div class="space-y-6" x-data="{ createModal: false, editModal: false, editUser: { id: '', name: '', email: '', role: 'siswa', kelas: 1, is_active: true } }">

    <!-- Header & Action Row -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-800">Kelola Pengguna</h1>
            <p class="text-xs sm:text-sm text-slate-500 font-semibold mt-0.5">Kelola akun pengguna (tambah, edit data, reset sandi, ubah status, dan hapus akun).</p>
        </div>
        <button type="button" @click="createModal = true" class="inline-flex items-center gap-2 bg-[#FF9500] hover:bg-[#e08600] text-white font-black px-5 py-2.5 rounded-2xl text-xs shadow-md shadow-orange-500/20 transition transform active:scale-95 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Pengguna Baru</span>
        </button>
    </div>

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
                        <th class="px-6 py-4 text-right">Aksi (Kelola)</th>
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
                                Pendidik Pengampu
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black {{ $u->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $u->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="inline-flex items-center gap-2">
                                {{-- Tombol Edit --}}
                                <button type="button"
                                        @click="editUser = {
                                            id: '{{ $u->id }}',
                                            name: '{{ addslashes($u->name) }}',
                                            email: '{{ addslashes($u->email) }}',
                                            role: '{{ $u->role }}',
                                            kelas: '{{ $u->kelas ?? 1 }}',
                                            is_active: {{ $u->is_active ? 'true' : 'false' }}
                                        }; editModal = true;"
                                        class="px-3 py-1.5 rounded-xl font-black text-xs bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 transition">
                                    Edit
                                </button>

                                {{-- Tombol Toggle Status --}}
                                <form action="{{ route('admin.users.toggle', $u) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun {{ addslashes($u->name) }}?')">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 rounded-xl font-black text-xs transition {{ $u->is_active ? 'bg-slate-100 hover:bg-slate-200 text-slate-700' : 'bg-emerald-600 hover:bg-emerald-700 text-white' }}">
                                        {{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus akun {{ addslashes($u->name) }} secara permanen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-xl font-black text-xs bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
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

    <!-- ========================================================= -->
    <!-- MODAL TAMBAH PENGGUNA BARU -->
    <!-- ========================================================= -->
    <div x-show="createModal"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4"
         style="display: none;">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 border-2 border-amber-200 shadow-2xl relative space-y-5"
             @click.away="createModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-amber-100">
                <h3 class="text-xl font-black text-slate-800">Tambah Akun Pengguna</h3>
                <button type="button" @click="createModal = false" class="text-slate-400 hover:text-slate-600 text-2xl font-bold">&times;</button>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4" x-data="{ role: 'siswa' }">
                @csrf
                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase mb-1">Nama Lengkap</label>
                    <input type="text" name="name" required placeholder="Contoh: Budi Santoso" class="w-full bg-slate-50 border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-2.5 text-xs font-bold focus:border-amber-400 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase mb-1">Email</label>
                    <input type="email" name="email" required placeholder="nama@robomath.id" class="w-full bg-slate-50 border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-2.5 text-xs font-bold focus:border-amber-400 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase mb-1">Kata Sandi (Password)</label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter" class="w-full bg-slate-50 border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-2.5 text-xs font-bold focus:border-amber-400 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase mb-1">Peran (Role)</label>
                    <select name="role" x-model="role" required class="w-full bg-slate-50 border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-2.5 text-xs font-bold focus:border-amber-400 focus:outline-none">
                        <option value="siswa">Siswa</option>
                        <option value="guru">Guru</option>
                    </select>
                </div>

                <div x-show="role === 'siswa'">
                    <label class="block text-xs font-black text-slate-600 uppercase mb-1">Kelas SD</label>
                    <select name="kelas" class="w-full bg-slate-50 border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-2.5 text-xs font-bold focus:border-amber-400 focus:outline-none">
                        @for($i = 1; $i <= 6; $i++)
                            <option value="{{ $i }}">Kelas {{ $i }} SD</option>
                        @endfor
                    </select>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3">
                    <button type="button" @click="createModal = false" class="px-5 py-2.5 rounded-2xl text-xs font-black text-slate-600 bg-slate-100 hover:bg-slate-200 transition">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-2xl text-xs font-black text-white bg-[#FF9500] hover:bg-[#e08600] shadow-md transition">Simpan Pengguna</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================= -->
    <!-- MODAL EDIT PENGGUNA -->
    <!-- ========================================================= -->
    <div x-show="editModal"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4"
         style="display: none;">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 border-2 border-amber-200 shadow-2xl relative space-y-5"
             @click.away="editModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-amber-100">
                <h3 class="text-xl font-black text-slate-800">Edit Akun Pengguna</h3>
                <button type="button" @click="editModal = false" class="text-slate-400 hover:text-slate-600 text-2xl font-bold">&times;</button>
            </div>

            <form :action="'{{ url('admin/users') }}/' + editUser.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase mb-1">Nama Lengkap</label>
                    <input type="text" name="name" x-model="editUser.name" required class="w-full bg-slate-50 border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-2.5 text-xs font-bold focus:border-amber-400 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase mb-1">Email</label>
                    <input type="email" name="email" x-model="editUser.email" required class="w-full bg-slate-50 border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-2.5 text-xs font-bold focus:border-amber-400 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase mb-1">Kata Sandi Baru (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" placeholder="Minimal 6 karakter" class="w-full bg-slate-50 border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-2.5 text-xs font-bold focus:border-amber-400 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase mb-1">Peran (Role)</label>
                    <select name="role" x-model="editUser.role" required class="w-full bg-slate-50 border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-2.5 text-xs font-bold focus:border-amber-400 focus:outline-none">
                        <option value="siswa">Siswa</option>
                        <option value="guru">Guru</option>
                    </select>
                </div>

                <div x-show="editUser.role === 'siswa'">
                    <label class="block text-xs font-black text-slate-600 uppercase mb-1">Kelas SD</label>
                    <select name="kelas" x-model="editUser.kelas" class="w-full bg-slate-50 border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-2.5 text-xs font-bold focus:border-amber-400 focus:outline-none">
                        @for($i = 1; $i <= 6; $i++)
                            <option value="{{ $i }}">Kelas {{ $i }} SD</option>
                        @endfor
                    </select>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" name="is_active" id="edit_is_active" value="1" :checked="editUser.is_active" class="rounded text-amber-500 focus:ring-amber-400">
                    <label for="edit_is_active" class="text-xs font-bold text-slate-700">Akun Aktif</label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3">
                    <button type="button" @click="editModal = false" class="px-5 py-2.5 rounded-2xl text-xs font-black text-slate-600 bg-slate-100 hover:bg-slate-200 transition">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-2xl text-xs font-black text-white bg-indigo-600 hover:bg-indigo-700 shadow-md transition">Perbarui Akun</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
