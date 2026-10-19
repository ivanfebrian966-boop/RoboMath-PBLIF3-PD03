@extends('layouts.admin')

@section('title', 'Kelola Pengguna - RoboMath')

@section('content')
<div class="space-y-6" x-data="{ createModal: false, editModal: false, editUser: { id: '', name: '', email: '', role: 'siswa', kelas: 1, is_active: true } }">

    <!-- ======================================================== -->
    <!-- 1. HERO BANNER HIJAU (Sesuai Tone Menu Kelola Pengguna) -->
    <!-- ======================================================== -->
    <div class="rounded-3xl p-6 sm:p-7 text-white shadow-md relative overflow-hidden"
         style="background: linear-gradient(135deg, #10B981 0%, #059669 100%);">
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight text-white mb-1.5">
            Kelola Pengguna
        </h1>
        <p class="text-emerald-100 text-xs sm:text-sm font-medium leading-relaxed max-w-2xl">
            Kelola akun pengguna: tambah, edit data, reset sandi, ubah status, dan hapus akun.
        </p>
    </div>

    <!-- ======================================================== -->
    <!-- 2. KPI SUMMARY CARDS (3 KARTU RINGKASAN) -->
    <!-- ======================================================== -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
        {{-- Card 1: Total Siswa --}}
        <div class="bg-white rounded-2xl p-5 border border-amber-200/90 shadow-2xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 text-white shadow-xs"
                 style="background-color: #FF9500;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <div class="text-[10px] font-black uppercase tracking-wider text-slate-400">TOTAL SISWA</div>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight mt-0.5">{{ $totalSiswa }}</div>
                <div class="text-xs text-slate-400 font-medium mt-0.5">Akun siswa terdaftar aktif</div>
            </div>
        </div>

        {{-- Card 2: Total Guru --}}
        <div class="bg-white rounded-2xl p-5 border border-blue-200/90 shadow-2xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 text-white shadow-xs"
                 style="background-color: #3B82F6;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm0 0v6"/>
                </svg>
            </div>
            <div>
                <div class="text-[10px] font-black uppercase tracking-wider text-slate-400">TOTAL GURU</div>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight mt-0.5">{{ $totalGuru }}</div>
                <div class="text-xs text-slate-400 font-medium mt-0.5">Pendidik pengampu kelas</div>
            </div>
        </div>

        {{-- Card 3: Akun Nonaktif --}}
        <div class="bg-white rounded-2xl p-5 border border-rose-200/90 shadow-2xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 text-white shadow-xs"
                 style="background-color: #EF4444;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>
            <div>
                <div class="text-[10px] font-black uppercase tracking-wider text-slate-400">AKUN NONAKTIF</div>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight mt-0.5">{{ $totalInactive }}</div>
                <div class="text-xs text-slate-400 font-medium mt-0.5">Akses ditangguhkan</div>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- 3. ACTION BAR: SEARCH, FILTER AKTOR (GURU/SISWA) & TAMBAH -->
    <!-- ======================================================== -->
    <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3 pt-1">
        {{-- Search Input (Lebar & Nyaman) --}}
        <form action="{{ route('admin.users.index') }}" method="GET" class="relative flex-1 max-w-md">
            @if($roleFilter !== 'all')
                <input type="hidden" name="role" value="{{ $roleFilter }}">
            @endif
            <div class="relative">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau email pengguna..."
                       class="w-full bg-white border border-slate-200 text-slate-800 rounded-2xl pl-12 pr-10 py-3 text-sm font-semibold placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/25 focus:border-emerald-500 shadow-xs transition-all">
                <svg class="w-5 h-5 text-slate-400 absolute left-4 top-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                @if($search)
                    <a href="{{ route('admin.users.index', array_filter(['role' => $roleFilter !== 'all' ? $roleFilter : null])) }}"
                       class="absolute right-4 top-3.5 text-slate-400 hover:text-slate-600 text-sm font-bold"
                       title="Hapus pencarian">✕</a>
                @endif
            </div>
        </form>

        {{-- Filter Aktor (Guru & Siswa) + Tombol Tambah --}}
        <div class="flex flex-wrap items-center gap-3 justify-between lg:justify-end">
            {{-- Filter Aktor Segmented Control --}}
            <div class="inline-flex p-1 bg-slate-100/90 rounded-2xl border border-slate-200/80 gap-1 flex-shrink-0">
                {{-- Semua Aktor --}}
                <a href="{{ route('admin.users.index', array_filter(['role' => 'all', 'search' => $search])) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-black transition-all flex items-center gap-1.5 cursor-pointer {{ $roleFilter === 'all' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500 hover:text-slate-900' }}">
                    <span>Semua</span>
                    <span class="text-[11px] {{ $roleFilter === 'all' ? 'text-slate-500' : 'text-slate-400' }}">({{ $totalSiswa + $totalGuru }})</span>
                </a>

                {{-- Guru --}}
                <a href="{{ route('admin.users.index', array_filter(['role' => 'guru', 'search' => $search])) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-black transition-all flex items-center gap-1.5 cursor-pointer {{ $roleFilter === 'guru' ? 'bg-white text-blue-600 shadow-xs' : 'text-slate-500 hover:text-slate-900' }}">
                    <span class="w-2 h-2 rounded-full" style="background-color: #3B82F6;"></span>
                    <span>Guru</span>
                    <span class="text-[11px] {{ $roleFilter === 'guru' ? 'text-blue-500' : 'text-slate-400' }}">({{ $totalGuru }})</span>
                </a>

                {{-- Siswa --}}
                <a href="{{ route('admin.users.index', array_filter(['role' => 'siswa', 'search' => $search])) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-black transition-all flex items-center gap-1.5 cursor-pointer {{ $roleFilter === 'siswa' ? 'bg-white text-amber-600 shadow-xs' : 'text-slate-500 hover:text-slate-900' }}">
                    <span class="w-2 h-2 rounded-full" style="background-color: #FF9500;"></span>
                    <span>Siswa</span>
                    <span class="text-[11px] {{ $roleFilter === 'siswa' ? 'text-amber-500' : 'text-slate-400' }}">({{ $totalSiswa }})</span>
                </a>
            </div>

            {{-- Tombol Tambah Pengguna Baru --}}
            <button type="button" @click="createModal = true"
                    class="inline-flex items-center justify-center gap-2 text-white font-black px-5 py-3 rounded-2xl text-xs sm:text-sm shadow-md shadow-emerald-600/20 hover:shadow-lg transition transform active:scale-95 cursor-pointer flex-shrink-0"
                    style="background: linear-gradient(135deg, #10B981 0%, #059669 100%);">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Pengguna Baru</span>
            </button>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- 4. DAFTAR AKUN PENGGUNA TABLE CARD -->
    <!-- ======================================================== -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden p-5 sm:p-6 space-y-4">
        
        {{-- Card Header --}}
        <div class="flex items-center justify-between pb-1">
            <h2 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">Daftar Akun Pengguna</h2>
            <span class="px-3.5 py-1 rounded-full text-xs font-black shadow-2xs"
                  style="background-color: #FEF3C7; color: #B45309;">
                {{ $users->total() }} Pengguna
            </span>
        </div>

        @if($users->isEmpty())
            <div class="p-12 text-center text-slate-400 font-semibold text-xs sm:text-sm bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                Tidak ada pengguna yang sesuai dengan pencarian.
            </div>
        @else
            <div class="overflow-x-auto -mx-5 sm:-mx-6">
                <table class="w-full text-left text-xs min-w-[700px]">
                    <thead class="bg-[#FAF8F5] border-y border-slate-100 text-[10px] sm:text-[11px] font-black tracking-wider text-slate-400 uppercase">
                        <tr>
                            <th class="py-3 px-5 sm:px-6">NAMA & EMAIL</th>
                            <th class="py-3 px-4">PERAN</th>
                            <th class="py-3 px-4">DETAIL</th>
                            <th class="py-3 px-4">STATUS</th>
                            <th class="py-3 px-5 sm:px-6 text-right">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($users as $index => $u)
                            @php
                                $bgColors = ['#E0F2FE', '#FFEDD5', '#DCFCE7', '#FCE7F3'];
                                $textColors = ['#0284C7', '#EA580C', '#16A34A', '#DB2777'];
                                $idx = ($u->id + $loop->index) % 4;
                                $avatarBg = $bgColors[$idx];
                                $avatarColor = $textColors[$idx];

                                $parts = preg_split('/\s+/', trim($u->name));
                                $first = $parts[0] ?? '';
                                $second = $parts[1] ?? '';
                                $initials = strtoupper(substr($first, 0, 1) . (strlen($second) > 0 ? substr($second, 0, 1) : substr($first, 1, 1)));
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition">
                                {{-- NAMA & EMAIL --}}
                                <td class="py-3.5 px-5 sm:px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-black text-xs flex-shrink-0"
                                             style="background-color: {{ $avatarBg }}; color: {{ $avatarColor }};">
                                            {{ $initials ?: 'RM' }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-black text-sm text-slate-900 leading-tight truncate">{{ $u->name }}</div>
                                            <div class="text-xs text-slate-400 font-medium truncate mt-0.5">{{ $u->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- PERAN (CAPSULE PILL) --}}
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    @if($u->role === 'guru')
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider inline-block"
                                              style="background-color: #DBEAFE; color: #2563EB;">
                                            GURU
                                        </span>
                                    @elseif($u->role === 'siswa')
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider inline-block"
                                              style="background-color: #FEF3C7; color: #D97706;">
                                            SISWA
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider inline-block"
                                              style="background-color: #F1F5F9; color: #475569;">
                                            ADMIN
                                        </span>
                                    @endif
                                </td>

                                {{-- DETAIL --}}
                                <td class="py-3.5 px-4 whitespace-nowrap text-xs font-semibold text-slate-700">
                                    @if($u->role === 'siswa')
                                        <span>Kelas {{ $u->kelas ?? '-' }} SD · <strong class="font-extrabold" style="color: #D97706;">{{ number_format($u->total_score) }} Poin</strong></span>
                                    @else
                                        <span>Pendidik Pengampu</span>
                                    @endif
                                </td>

                                {{-- STATUS (CAPSULE BADGE) --}}
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    @if($u->is_active)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold"
                                              style="background-color: #D1FAE5; color: #059669;">
                                            <span class="w-1.5 h-1.5 rounded-full" style="background-color: #10B981;"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold"
                                              style="background-color: #FFE4E6; color: #E11D48;">
                                            <span class="w-1.5 h-1.5 rounded-full" style="background-color: #F43F5E;"></span>
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                {{-- AKSI (3 TOMBOL BULAT PASTEL RAPI) --}}
                                <td class="py-3.5 px-5 sm:px-6 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2 justify-end">
                                        {{-- Edit Button --}}
                                        <button type="button"
                                                @click="editUser = {
                                                    id: '{{ $u->id }}',
                                                    name: '{{ addslashes($u->name) }}',
                                                    email: '{{ addslashes($u->email) }}',
                                                    role: '{{ $u->role }}',
                                                    kelas: '{{ $u->kelas ?? 1 }}',
                                                    is_active: {{ $u->is_active ? 'true' : 'false' }}
                                                }; editModal = true;"
                                                title="Edit Data"
                                                class="w-8 h-8 rounded-full flex items-center justify-center transition hover:scale-110 active:scale-95 cursor-pointer shadow-2xs"
                                                style="background-color: #EFF6FF; color: #3B82F6;">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                        </button>

                                        {{-- Toggle Status / Ban Button --}}
                                        <form action="{{ route('admin.users.toggle', $u) }}" method="POST" class="inline-block m-0"
                                              onsubmit="return confirm('{{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun {{ addslashes($u->name) }}?')">
                                            @csrf
                                            <button type="submit"
                                                    title="{{ $u->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}"
                                                    class="w-8 h-8 rounded-full flex items-center justify-center transition hover:scale-110 active:scale-95 cursor-pointer shadow-2xs"
                                                    style="background-color: #FEF3C7; color: #D97706;">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                </svg>
                                            </button>
                                        </form>

                                        {{-- Delete Button --}}
                                        <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="inline-block m-0"
                                              onsubmit="return confirm('Yakin ingin menghapus akun {{ addslashes($u->name) }} secara permanen?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    title="Hapus Akun"
                                                    class="w-8 h-8 rounded-full flex items-center justify-center transition hover:scale-110 active:scale-95 cursor-pointer shadow-2xs"
                                                    style="background-color: #FEE2E2; color: #EF4444;">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination Bar (Menggunakan Ikon Chevron Tanpa Teks Sebelumnya/Berikutnya) --}}
            <div class="pt-5 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-semibold text-slate-500">
                <div>
                    Menampilkan <span class="font-bold text-slate-800">{{ $users->firstItem() ?? 0 }}</span> - <span class="font-bold text-slate-800">{{ $users->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-800">{{ $users->total() }}</span> pengguna
                </div>

                <div class="inline-flex items-center gap-1.5">
                    {{-- Tombol Halaman Sebelumnya (Ikon Chevron Kiri) --}}
                    @if($users->onFirstPage())
                        <span class="w-8 h-8 rounded-xl border border-slate-200 bg-slate-50 text-slate-300 flex items-center justify-center cursor-not-allowed" title="Halaman sebelumnya">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}"
                           class="w-8 h-8 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 flex items-center justify-center transition shadow-2xs"
                           title="Halaman sebelumnya">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </a>
                    @endif

                    {{-- Angka Nomor Halaman --}}
                    @php
                        $start = max(1, $users->currentPage() - 2);
                        $end = min($users->lastPage(), $users->currentPage() + 2);
                    @endphp
                    @for($i = $start; $i <= $end; $i++)
                        @if($i == $users->currentPage())
                            <span class="w-8 h-8 rounded-xl font-black text-xs text-white flex items-center justify-center shadow-xs"
                                  style="background-color: #10B981;">
                                {{ $i }}
                            </span>
                        @else
                            <a href="{{ $users->url($i) }}"
                               class="w-8 h-8 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs flex items-center justify-center transition shadow-2xs">
                                {{ $i }}
                            </a>
                        @endif
                    @endfor

                    {{-- Tombol Halaman Berikutnya (Ikon Chevron Kanan) --}}
                    @if($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}"
                           class="w-8 h-8 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 flex items-center justify-center transition shadow-2xs"
                           title="Halaman berikutnya">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @else
                        <span class="w-8 h-8 rounded-xl border border-slate-200 bg-slate-50 text-slate-300 flex items-center justify-center cursor-not-allowed" title="Halaman berikutnya">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </span>
                    @endif
                </div>
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
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 border border-slate-100 shadow-2xl relative space-y-5"
             @click.away="createModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-black text-slate-900">Tambah Akun Pengguna</h3>
                    <p class="text-xs text-slate-400 font-medium">Buat akun untuk siswa atau pendidik baru.</p>
                </div>
                <button type="button" @click="createModal = false" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center text-lg font-bold transition">&times;</button>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4" x-data="{ role: 'siswa' }">
                @csrf
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" required placeholder="Contoh: Budi Santoso"
                           class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl px-4 py-2.5 text-xs font-bold focus:border-emerald-500 focus:bg-white focus:outline-none transition-all">
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase mb-1.5">Email</label>
                    <input type="email" name="email" required placeholder="nama@robomath.id"
                           class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl px-4 py-2.5 text-xs font-bold focus:border-emerald-500 focus:bg-white focus:outline-none transition-all">
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase mb-1.5">Kata Sandi (Password)</label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter"
                           class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl px-4 py-2.5 text-xs font-bold focus:border-emerald-500 focus:bg-white focus:outline-none transition-all">
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase mb-1.5">Peran (Role)</label>
                    <select name="role" x-model="role" required
                            class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl px-4 py-2.5 text-xs font-bold focus:border-emerald-500 focus:bg-white focus:outline-none transition-all">
                        <option value="siswa">Siswa</option>
                        <option value="guru">Guru</option>
                    </select>
                </div>

                <div x-show="role === 'siswa'">
                    <label class="block text-xs font-black text-slate-700 uppercase mb-1.5">Kelas SD</label>
                    <select name="kelas"
                            class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl px-4 py-2.5 text-xs font-bold focus:border-emerald-500 focus:bg-white focus:outline-none transition-all">
                        @for($i = 1; $i <= 6; $i++)
                            <option value="{{ $i }}">Kelas {{ $i }} SD</option>
                        @endfor
                    </select>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3">
                    <button type="button" @click="createModal = false"
                            class="px-5 py-2.5 rounded-2xl text-xs font-black text-slate-600 bg-slate-100 hover:bg-slate-200 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-6 py-2.5 rounded-2xl text-xs font-black text-white shadow-md shadow-emerald-500/25 transition cursor-pointer"
                            style="background: linear-gradient(135deg, #10B981 0%, #059669 100%);">
                        Simpan Pengguna
                    </button>
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
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 border border-slate-100 shadow-2xl relative space-y-5"
             @click.away="editModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-black text-slate-900">Edit Akun Pengguna</h3>
                    <p class="text-xs text-slate-400 font-medium">Perbarui profil dan wewenang pengguna.</p>
                </div>
                <button type="button" @click="editModal = false" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center text-lg font-bold transition">&times;</button>
            </div>

            <form :action="'{{ url('admin/users') }}/' + editUser.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" x-model="editUser.name" required
                           class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl px-4 py-2.5 text-xs font-bold focus:border-emerald-500 focus:bg-white focus:outline-none transition-all">
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase mb-1.5">Email</label>
                    <input type="email" name="email" x-model="editUser.email" required
                           class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl px-4 py-2.5 text-xs font-bold focus:border-emerald-500 focus:bg-white focus:outline-none transition-all">
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase mb-1.5">Kata Sandi Baru <span class="text-slate-400 font-normal">(opsional)</span></label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak diubah"
                           class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl px-4 py-2.5 text-xs font-bold focus:border-emerald-500 focus:bg-white focus:outline-none transition-all">
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase mb-1.5">Peran (Role)</label>
                    <select name="role" x-model="editUser.role" required
                            class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl px-4 py-2.5 text-xs font-bold focus:border-emerald-500 focus:bg-white focus:outline-none transition-all">
                        <option value="siswa">Siswa</option>
                        <option value="guru">Guru</option>
                    </select>
                </div>

                <div x-show="editUser.role === 'siswa'">
                    <label class="block text-xs font-black text-slate-700 uppercase mb-1.5">Kelas SD</label>
                    <select name="kelas" x-model="editUser.kelas"
                            class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl px-4 py-2.5 text-xs font-bold focus:border-emerald-500 focus:bg-white focus:outline-none transition-all">
                        @for($i = 1; $i <= 6; $i++)
                            <option value="{{ $i }}">Kelas {{ $i }} SD</option>
                        @endfor
                    </select>
                </div>

                <div class="flex items-center gap-2.5 pt-1">
                    <input type="checkbox" name="is_active" id="edit_is_active" value="1" :checked="editUser.is_active"
                           class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300 cursor-pointer">
                    <label for="edit_is_active" class="text-xs font-bold text-slate-700 select-none cursor-pointer">Akun Aktif</label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3">
                    <button type="button" @click="editModal = false"
                            class="px-5 py-2.5 rounded-2xl text-xs font-black text-slate-600 bg-slate-100 hover:bg-slate-200 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-6 py-2.5 rounded-2xl text-xs font-black text-white shadow-md shadow-emerald-500/25 transition cursor-pointer"
                            style="background: linear-gradient(135deg, #10B981 0%, #059669 100%);">
                        Perbarui Akun
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
