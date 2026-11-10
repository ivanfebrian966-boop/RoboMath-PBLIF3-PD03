@extends($user->isAdmin() ? 'layouts.admin' : 'layouts.app')

@section('title', 'Profil Saya - RoboMath')

@section('content')
<div class="w-full space-y-6 sm:space-y-7 font-poppins">

    @if($user->isAdmin())
        {{-- ======================================================== --}}
        {{-- TAMPILAN KHUSUS ADMIN (Sesuai Mockup Gambar Admin)       --}}
        {{-- ======================================================== --}}

        {{-- 1. TIGA KARTU STATISTIK ADMIN --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
            {{-- KARTU 1: TOTAL DOKUMEN (BORDER KUNING / AMBER) --}}
            <div class="bg-white border-2 border-[#FCD34D] rounded-[28px] sm:rounded-[32px] p-5 sm:p-6 flex items-center gap-4.5 sm:gap-5 shadow-xs">
                <div class="w-14 h-14 rounded-[20px] bg-[#FF9500] flex items-center justify-center text-white flex-shrink-0 shadow-xs">
                    {{-- Document with folded corner icon --}}
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 font-poppins">
                        TOTAL DOKUMEN
                    </p>
                    <p class="text-3xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight leading-tight mt-0.5 font-poppins">
                        {{ \App\Models\DocumentSummary::count() }}
                    </p>
                    <p class="text-xs sm:text-sm font-medium text-slate-500 mt-0.5 truncate font-poppins">
                        Materi dan soal AI
                    </p>
                </div>
            </div>

            {{-- KARTU 2: TOTAL PENGGUNA (BORDER BIRU) --}}
            <div class="bg-white border-2 border-[#93C5FD] rounded-[28px] sm:rounded-[32px] p-5 sm:p-6 flex items-center gap-4.5 sm:gap-5 shadow-xs">
                <div class="w-14 h-14 rounded-[20px] bg-[#2563EB] flex items-center justify-center text-white flex-shrink-0 shadow-xs">
                    {{-- Users Icon --}}
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 font-poppins">
                        TOTAL PENGGUNA
                    </p>
                    <p class="text-3xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight leading-tight mt-0.5 font-poppins">
                        {{ \App\Models\User::count() }} akun
                    </p>
                    <p class="text-xs sm:text-sm font-medium text-slate-500 mt-0.5 truncate font-poppins">
                        Siswa, guru, dan admin
                    </p>
                </div>
            </div>

            {{-- KARTU 3: STATUS AKUN (BORDER HIJAU MINT) --}}
            <div class="bg-white border-2 border-[#6EE7B7] rounded-[28px] sm:rounded-[32px] p-5 sm:p-6 flex items-center gap-4.5 sm:gap-5 shadow-xs">
                <div class="w-14 h-14 rounded-[20px] bg-[#059669] flex items-center justify-center text-white flex-shrink-0 shadow-xs">
                    {{-- Checkmark Icon --}}
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 font-poppins">
                        STATUS AKUN
                    </p>
                    <p class="text-3xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight leading-tight mt-0.5 font-poppins">
                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </p>
                    <p class="text-xs sm:text-sm font-medium text-slate-500 mt-0.5 truncate font-poppins">
                        Bergabung {{ $user->created_at ? $user->created_at->translatedFormat('d M Y') : '29 Okt 2026' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- 2. KARTU INFORMASI AKUN (ADMIN) --}}
        <div class="bg-white border border-slate-200/90 rounded-[28px] sm:rounded-[32px] p-6 sm:p-8 shadow-xs space-y-5 sm:space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight font-poppins">
                    Informasi akun
                </h2>
                <a href="{{ route('profil.edit') }}"
                   class="inline-flex items-center justify-center px-6 py-2.5 rounded-full bg-[#EF4444] hover:bg-[#DC2626] text-white font-bold text-sm tracking-wide shadow-[0_4px_14px_rgba(239,68,68,0.30)] transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] font-poppins">
                    Edit profil
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- ITEM 1: NAMA LENGKAP --}}
                <div class="border border-slate-200/90 rounded-[22px] p-4 sm:p-5 flex items-center gap-4 sm:gap-5 bg-white hover:border-slate-300 transition-colors">
                    <div class="w-12 h-12 rounded-[16px] bg-[#DBEAFE] flex items-center justify-center text-[#3B82F6] flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 font-poppins">NAMA LENGKAP</p>
                        <p class="text-base sm:text-lg font-bold text-slate-900 mt-0.5 truncate font-poppins">{{ $user->name }}</p>
                    </div>
                </div>

                {{-- ITEM 2: EMAIL --}}
                <div class="border border-slate-200/90 rounded-[22px] p-4 sm:p-5 flex items-center gap-4 sm:gap-5 bg-white hover:border-slate-300 transition-colors">
                    <div class="w-12 h-12 rounded-[16px] bg-[#FEF3C7] flex items-center justify-center text-[#D97706] flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 font-poppins">EMAIL</p>
                        <p class="text-base sm:text-lg font-bold text-slate-900 mt-0.5 truncate font-poppins">{{ $user->email }}</p>
                    </div>
                </div>

                {{-- ITEM 3: PERAN --}}
                <div class="border border-slate-200/90 rounded-[22px] p-4 sm:p-5 flex items-center gap-4 sm:gap-5 bg-white hover:border-slate-300 transition-colors">
                    <div class="w-12 h-12 rounded-[16px] bg-[#FEE2E2] flex items-center justify-center text-[#EF4444] flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 font-poppins">PERAN</p>
                        <p class="text-base sm:text-lg font-bold text-slate-900 mt-0.5 truncate font-poppins">{{ ucfirst($user->role) }}</p>
                    </div>
                </div>

                {{-- ITEM 4: BERGABUNG --}}
                <div class="border border-slate-200/90 rounded-[22px] p-4 sm:p-5 flex items-center gap-4 sm:gap-5 bg-white hover:border-slate-300 transition-colors">
                    <div class="w-12 h-12 rounded-[16px] bg-[#D1FAE5] flex items-center justify-center text-[#059669] flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 font-poppins">BERGABUNG</p>
                        <p class="text-base sm:text-lg font-bold text-slate-900 mt-0.5 truncate font-poppins">
                            {{ $user->created_at ? $user->created_at->translatedFormat('d M Y') : '29 Okt 2026' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

    @elseif($user->isGuru())
        {{-- ======================================================== --}}
        {{-- TAMPILAN KHUSUS GURU (Sesuai Mockup Gambar Guru)         --}}
        {{-- ======================================================== --}}
        @php
            $totalClasses = $user->teachingClassRooms()->count();
            $totalStudents = \App\Models\User::where('role', 'siswa')
                ->whereHas('classRooms', fn($q) => $q->where('kelas.teacher_id', $user->id))
                ->count();
        @endphp

        {{-- 1. TIGA KARTU STATISTIK GURU --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
            {{-- KARTU 1: TOTAL KELAS (BORDER BIRU) --}}
            <div class="bg-white border-2 border-[#93C5FD] rounded-[28px] sm:rounded-[32px] p-5 sm:p-6 flex items-center gap-4.5 sm:gap-5 shadow-xs">
                <div class="w-14 h-14 rounded-[20px] bg-[#2563EB] flex items-center justify-center text-white flex-shrink-0 shadow-xs">
                    {{-- School / Classroom Building Icon --}}
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 font-poppins">
                        TOTAL KELAS
                    </p>
                    <p class="text-3xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight leading-tight mt-0.5 font-poppins">
                        {{ $totalClasses }} kelas
                    </p>
                    <p class="text-xs sm:text-sm font-medium text-slate-500 mt-0.5 truncate font-poppins">
                        Kelas diampu
                    </p>
                </div>
            </div>

            {{-- KARTU 2: SISWA TERPANTAU (BORDER KUNING / AMBER) --}}
            <div class="bg-white border-2 border-[#FCD34D] rounded-[28px] sm:rounded-[32px] p-5 sm:p-6 flex items-center gap-4.5 sm:gap-5 shadow-xs">
                <div class="w-14 h-14 rounded-[20px] bg-[#FF9500] flex items-center justify-center text-white flex-shrink-0 shadow-xs">
                    {{-- Users Icon --}}
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 font-poppins">
                        SISWA TERPANTAU
                    </p>
                    <p class="text-3xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight leading-tight mt-0.5 font-poppins">
                        {{ $totalStudents }} siswa
                    </p>
                    <p class="text-xs sm:text-sm font-medium text-slate-500 mt-0.5 truncate font-poppins">
                        Siswa aktif terdaftar
                    </p>
                </div>
            </div>

            {{-- KARTU 3: STATUS AKUN (BORDER HIJAU MINT) --}}
            <div class="bg-white border-2 border-[#6EE7B7] rounded-[28px] sm:rounded-[32px] p-5 sm:p-6 flex items-center gap-4.5 sm:gap-5 shadow-xs">
                <div class="w-14 h-14 rounded-[20px] bg-[#059669] flex items-center justify-center text-white flex-shrink-0 shadow-xs">
                    {{-- Checkmark Icon --}}
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 font-poppins">
                        STATUS AKUN
                    </p>
                    <p class="text-3xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight leading-tight mt-0.5 font-poppins">
                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </p>
                    <p class="text-xs sm:text-sm font-medium text-slate-500 mt-0.5 truncate font-poppins">
                        Bergabung {{ $user->created_at ? $user->created_at->translatedFormat('d M Y') : '29 Okt 2026' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- 2. KARTU INFORMASI AKUN (GURU) --}}
        <div class="bg-white border border-slate-200/90 rounded-[28px] sm:rounded-[32px] p-6 sm:p-8 shadow-xs space-y-5 sm:space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight font-poppins">
                    Informasi akun
                </h2>
                <a href="{{ route('profil.edit') }}"
                   class="inline-flex items-center justify-center px-6 py-2.5 rounded-full bg-[#EF4444] hover:bg-[#DC2626] text-white font-bold text-sm tracking-wide shadow-[0_4px_14px_rgba(239,68,68,0.30)] transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] font-poppins">
                    Edit profil
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- ITEM 1: NAMA LENGKAP --}}
                <div class="border border-slate-200/90 rounded-[22px] p-4 sm:p-5 flex items-center gap-4 sm:gap-5 bg-white hover:border-slate-300 transition-colors">
                    <div class="w-12 h-12 rounded-[16px] bg-[#DBEAFE] flex items-center justify-center text-[#3B82F6] flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 font-poppins">NAMA LENGKAP</p>
                        <p class="text-base sm:text-lg font-bold text-slate-900 mt-0.5 truncate font-poppins">{{ $user->name }}</p>
                    </div>
                </div>

                {{-- ITEM 2: EMAIL --}}
                <div class="border border-slate-200/90 rounded-[22px] p-4 sm:p-5 flex items-center gap-4 sm:gap-5 bg-white hover:border-slate-300 transition-colors">
                    <div class="w-12 h-12 rounded-[16px] bg-[#FEF3C7] flex items-center justify-center text-[#D97706] flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 font-poppins">EMAIL</p>
                        <p class="text-base sm:text-lg font-bold text-slate-900 mt-0.5 truncate font-poppins">{{ $user->email }}</p>
                    </div>
                </div>

                {{-- ITEM 3: PERAN --}}
                <div class="border border-slate-200/90 rounded-[22px] p-4 sm:p-5 flex items-center gap-4 sm:gap-5 bg-white hover:border-slate-300 transition-colors">
                    <div class="w-12 h-12 rounded-[16px] bg-[#FEE2E2] flex items-center justify-center text-[#EF4444] flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 font-poppins">PERAN</p>
                        <p class="text-base sm:text-lg font-bold text-slate-900 mt-0.5 truncate font-poppins">{{ ucfirst($user->role) }}</p>
                    </div>
                </div>

                {{-- ITEM 4: SEKOLAH --}}
                <div class="border border-slate-200/90 rounded-[22px] p-4 sm:p-5 flex items-center gap-4 sm:gap-5 bg-white hover:border-slate-300 transition-colors">
                    <div class="w-12 h-12 rounded-[16px] bg-[#D1FAE5] flex items-center justify-center text-[#059669] flex-shrink-0">
                        {{-- Graduation / Academic Cap Icon --}}
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14v7" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 font-poppins">SEKOLAH</p>
                        <p class="text-base sm:text-lg font-bold text-slate-900 mt-0.5 truncate font-poppins">
                            {{ $user->sekolah->nama_sekolah ?? ($user->teachingClassRooms->first()?->school?->nama_sekolah ?? 'SDN 1 Bandung') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

    @else
        {{-- ======================================================== --}}
        {{-- TAMPILAN KHUSUS SISWA (Sesuai Mockup Gambar Siswa)       --}}
        {{-- ======================================================== --}}
        @php
            $totalQuizzes = \App\Models\Quiz::whereHas('topic', fn($q) => $q->availableToStudent($user))
                ->where('is_active', true)
                ->where('review_status', 'approved')
                ->count();
            if ($totalQuizzes === 0) {
                $totalQuizzes = \App\Models\Quiz::where('is_active', true)->count() ?: 8;
            }
            $completedQuizzes = \App\Models\QuizAttempt::where('user_id', $user->id)
                ->where('is_correct', true)
                ->distinct('quiz_id')
                ->count('quiz_id');
            $progressPercentage = $totalQuizzes > 0 ? round(($completedQuizzes / $totalQuizzes) * 100) : 0;
        @endphp

        {{-- 1. HEADER PROFILE CARD (HIJAU VIBRANT) DENGAN STREAK & TOMBOL EDIT PROFIL --}}
        <div class="rounded-[28px] sm:rounded-[32px] bg-[#00BA88] p-6 sm:p-8 text-slate-900 shadow-[0_8px_24px_rgba(0,186,136,0.20)] relative overflow-hidden">
            <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5 sm:gap-6">
                <div class="flex items-center gap-5 sm:gap-6">
                    {{-- Avatar Squircle Mint/Light Green --}}
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-[22px] sm:rounded-[26px] bg-[#A7F3D0] flex items-center justify-center text-4xl sm:text-5xl font-extrabold text-slate-900 shadow-xs flex-shrink-0 font-poppins">
                        {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                    </div>

                    {{-- Nama, Email & Streak Badge --}}
                    <div class="min-w-0">
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight truncate font-poppins">
                            {{ $user->name }}
                        </h1>
                        <p class="text-slate-900/80 text-base sm:text-lg font-medium mt-1 truncate font-poppins">
                            {{ $user->email }}
                        </p>

                        {{-- Streak Badge di Card Header --}}
                        <div class="inline-flex items-center gap-2 mt-2.5 px-3.5 py-1.5 rounded-full bg-white/25 backdrop-blur-xs text-slate-950 text-xs sm:text-sm font-bold border border-white/40 shadow-xs">
                            <span class="text-base sm:text-lg leading-none">🔥</span>
                            <span>{{ max(1, $user->streak_saat_ini ?? 1) }} Hari Streak</span>
                        </div>
                    </div>
                </div>

                {{-- Tombol Edit Profil di Card Header --}}
                <div class="flex items-center sm:self-center">
                    <a href="{{ route('profil.edit') }}"
                       class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-full bg-[#EF4444] hover:bg-[#DC2626] text-white font-bold text-sm tracking-wide shadow-[0_4px_14px_rgba(239,68,68,0.30)] transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] font-poppins">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                        </svg>
                        <span>Edit profil</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- 2. TIGA KARTU STATISTIK SISWA (Sesuai Mockup Gambar: TOTAL POIN, SOAL SELESAI, STATUS AKUN) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
            {{-- KARTU 1: TOTAL POIN (BORDER KUNING) --}}
            <div class="bg-white border-2 border-[#FCD34D] rounded-[28px] sm:rounded-[32px] p-5 sm:p-6 flex items-center gap-4.5 sm:gap-5 shadow-xs">
                <div class="w-14 h-14 rounded-[20px] bg-[#FF9500] flex items-center justify-center text-[#0F172A] flex-shrink-0 shadow-xs">
                    {{-- Star Icon with dark stroke --}}
                    <svg class="w-7 h-7 text-[#0F172A]" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 font-poppins">
                        TOTAL POIN
                    </p>
                    <p class="text-3xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight leading-tight mt-0.5 font-poppins">
                        {{ number_format($user->total_score ?? 0) }}
                    </p>
                    <p class="text-xs sm:text-sm font-medium text-slate-500 mt-0.5 truncate font-poppins">
                        Level {{ $user->level ?? 1 }}
                    </p>
                </div>
            </div>

            {{-- KARTU 2: SOAL SELESAI (BORDER BIRU) --}}
            <div class="bg-white border-2 border-[#93C5FD] rounded-[28px] sm:rounded-[32px] p-5 sm:p-6 flex items-center gap-4.5 sm:gap-5 shadow-xs">
                <div class="w-14 h-14 rounded-[20px] bg-[#2563EB] flex items-center justify-center text-white flex-shrink-0 shadow-xs">
                    {{-- Bulleted List / Checklist Icon --}}
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <line x1="8" y1="6" x2="21" y2="6"></line>
                        <line x1="8" y1="12" x2="21" y2="12"></line>
                        <line x1="8" y1="18" x2="21" y2="18"></line>
                        <line x1="3" y1="6" x2="3.01" y2="6"></line>
                        <line x1="3" y1="12" x2="3.01" y2="12"></line>
                        <line x1="3" y1="18" x2="3.01" y2="18"></line>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 font-poppins">
                        SOAL SELESAI
                    </p>
                    <p class="text-3xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight leading-tight mt-0.5 font-poppins">
                        {{ $completedQuizzes }} / {{ $totalQuizzes }}
                    </p>
                    <p class="text-xs sm:text-sm font-medium text-slate-500 mt-0.5 truncate font-poppins">
                        Kemajuan {{ $progressPercentage }}%
                    </p>
                </div>
            </div>

            {{-- KARTU 3: STATUS AKUN (BORDER HIJAU MINT) --}}
            <div class="bg-white border-2 border-[#6EE7B7] rounded-[28px] sm:rounded-[32px] p-5 sm:p-6 flex items-center gap-4.5 sm:gap-5 shadow-xs">
                <div class="w-14 h-14 rounded-[20px] bg-[#059669] flex items-center justify-center text-white flex-shrink-0 shadow-xs">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 font-poppins">
                        STATUS AKUN
                    </p>
                    <p class="text-3xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight leading-tight mt-0.5 font-poppins">
                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </p>
                    <p class="text-xs sm:text-sm font-medium text-slate-500 mt-0.5 truncate font-poppins">
                        Bergabung {{ $user->created_at ? $user->created_at->translatedFormat('d M Y') : '23 Okt 2026' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- 3. KARTU INFORMASI AKUN (SISWA) --}}
        <div class="bg-white border border-slate-200/90 rounded-[28px] sm:rounded-[32px] p-6 sm:p-8 shadow-xs space-y-5 sm:space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight font-poppins">
                    Informasi akun
                </h2>
                <a href="{{ route('profil.edit') }}"
                   class="inline-flex items-center justify-center px-6 py-2.5 rounded-full bg-[#EF4444] hover:bg-[#DC2626] text-white font-bold text-sm tracking-wide shadow-[0_4px_14px_rgba(239,68,68,0.30)] transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] font-poppins">
                    Edit profil
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- ITEM 1: NAMA LENGKAP --}}
                <div class="border border-slate-200/90 rounded-[22px] p-4 sm:p-5 flex items-center gap-4 sm:gap-5 bg-white hover:border-slate-300 transition-colors">
                    <div class="w-12 h-12 rounded-[16px] bg-[#DBEAFE] flex items-center justify-center text-[#3B82F6] flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 font-poppins">NAMA LENGKAP</p>
                        <p class="text-base sm:text-lg font-bold text-slate-900 mt-0.5 truncate font-poppins">{{ $user->name }}</p>
                    </div>
                </div>

                {{-- ITEM 2: EMAIL --}}
                <div class="border border-slate-200/90 rounded-[22px] p-4 sm:p-5 flex items-center gap-4 sm:gap-5 bg-white hover:border-slate-300 transition-colors">
                    <div class="w-12 h-12 rounded-[16px] bg-[#FEF3C7] flex items-center justify-center text-[#D97706] flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 font-poppins">EMAIL</p>
                        <p class="text-base sm:text-lg font-bold text-slate-900 mt-0.5 truncate font-poppins">{{ $user->email }}</p>
                    </div>
                </div>

                {{-- ITEM 3: PERAN --}}
                <div class="border border-slate-200/90 rounded-[22px] p-4 sm:p-5 flex items-center gap-4 sm:gap-5 bg-white hover:border-slate-300 transition-colors">
                    <div class="w-12 h-12 rounded-[16px] bg-[#FEE2E2] flex items-center justify-center text-[#EF4444] flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 font-poppins">PERAN</p>
                        <p class="text-base sm:text-lg font-bold text-slate-900 mt-0.5 truncate font-poppins">{{ ucfirst($user->role) }}</p>
                    </div>
                </div>

                {{-- ITEM 4: KELAS (SISWA) --}}
                <div class="border border-slate-200/90 rounded-[22px] p-4 sm:p-5 flex items-center gap-4 sm:gap-5 bg-white hover:border-slate-300 transition-colors">
                    <div class="w-12 h-12 rounded-[16px] bg-[#D1FAE5] flex items-center justify-center text-[#059669] flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14v7" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 font-poppins">KELAS</p>
                        <p class="text-base sm:text-lg font-bold text-slate-900 mt-0.5 truncate font-poppins">
                            Kelas {{ $user->kelas ?? 1 }} SD
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
