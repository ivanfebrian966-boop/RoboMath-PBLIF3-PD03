@extends('layouts.app')

@section('title', 'Profil Saya - RoboMath')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 sm:space-y-7 font-poppins">

    {{-- 1. HEADER PROFILE CARD (HIJAU VIBRANT) --}}
    <div class="rounded-[28px] sm:rounded-[32px] bg-[#00BA88] p-6 sm:p-8 text-slate-900 shadow-[0_8px_24px_rgba(0,186,136,0.20)] relative overflow-hidden">
        <div class="relative flex items-center gap-5 sm:gap-6">
            {{-- Avatar Squircle Mint/Light Green --}}
            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-[22px] sm:rounded-[26px] bg-[#A7F3D0] flex items-center justify-center text-4xl sm:text-5xl font-extrabold text-slate-900 shadow-xs flex-shrink-0 font-poppins">
                {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
            </div>

            {{-- Nama & Email --}}
            <div class="min-w-0">
                <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight truncate font-poppins">
                    {{ $user->name }}
                </h1>
                <p class="text-slate-900/80 text-base sm:text-lg font-medium mt-1 truncate font-poppins">
                    {{ $user->email }}
                </p>
            </div>
        </div>
    </div>

    {{-- 2. TIGA KARTU STATISTIK (SESUAI MOCKUP FIGMA DENGAN FONT POPPINS) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
        
        {{-- KARTU 1: TOTAL POIN / TOTAL KELAS / TOTAL DOKUMEN (BORDER KUNING) --}}
        <div class="bg-white border-2 border-[#FCD34D] rounded-[28px] sm:rounded-[32px] p-5 sm:p-6 flex items-center gap-4.5 sm:gap-5 shadow-xs">
            <div class="w-14 h-14 rounded-[20px] bg-[#FF9500] flex items-center justify-center text-[#0F172A] flex-shrink-0 shadow-xs">
                {{-- Outline Star Icon (Dark stroke sesuai mockup) --}}
                <svg class="w-7 h-7 text-[#0F172A]" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 font-poppins">
                    @if($user->isGuru())
                        TOTAL KELAS
                    @elseif($user->isAdmin())
                        TOTAL DOKUMEN
                    @else
                        TOTAL POIN
                    @endif
                </p>
                <p class="text-3xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight leading-tight mt-0.5 font-poppins">
                    @if($user->isGuru())
                        {{ $user->teachingClassRooms->count() ?: 1 }}
                    @elseif($user->isAdmin())
                        {{ \App\Models\Dokumen::count() ?: 0 }}
                    @else
                        {{ number_format($user->total_score ?? 0) }}
                    @endif
                </p>
                <p class="text-xs sm:text-sm font-medium text-slate-500 mt-0.5 truncate font-poppins">
                    @if($user->isGuru())
                        Kelas diampu
                    @elseif($user->isAdmin())
                        Materi & Soal AI
                    @else
                        Level {{ $user->level ?? 1 }}
                    @endif
                </p>
            </div>
        </div>

        {{-- KARTU 2: STREAK SAAT INI (BORDER MERAH/PINK) --}}
        <div class="bg-white border-2 border-[#FCA5A5] rounded-[28px] sm:rounded-[32px] p-5 sm:p-6 flex items-center gap-4.5 sm:gap-5 shadow-xs">
            <div class="w-14 h-14 rounded-[20px] bg-[#FF3B30] flex items-center justify-center text-white flex-shrink-0 shadow-xs">
                {{-- Outline Flame Icon (White stroke sesuai mockup) --}}
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M12 2c-.5 2-2 4-4 6-2.5 2.5-3.5 5.5-3.5 8.5a7.5 7.5 0 0 0 15 0c0-3.5-1.5-6.5-4-9-1.5-1.5-2.5-3.5-3.5-5.5z"/>
                    <path d="M12 13a2.5 2.5 0 0 0-2.5 2.5c0 1.5 1 2.5 2.5 2.5s2.5-1 2.5-2.5A2.5 2.5 0 0 0 12 13z"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 font-poppins">STREAK SAAT INI</p>
                <p class="text-3xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight leading-tight mt-0.5 font-poppins">
                    {{ max(1, $user->streak_saat_ini ?? 0) }} hari
                </p>
                <p class="text-xs sm:text-sm font-medium text-slate-500 mt-0.5 truncate font-poppins">
                    @if($user->isGuru())
                        Konsistensi mengajar
                    @elseif($user->isAdmin())
                        Aktivitas sistem aktif
                    @else
                        Belajar lagi besok untuk lanjut
                    @endif
                </p>
            </div>
        </div>

        {{-- KARTU 3: STATUS AKUN (BORDER HIJAU MINT) --}}
        <div class="bg-white border-2 border-[#6EE7B7] rounded-[28px] sm:rounded-[32px] p-5 sm:p-6 flex items-center gap-4.5 sm:gap-5 shadow-xs">
            <div class="w-14 h-14 rounded-[20px] bg-[#10B981] flex items-center justify-center text-[#0F172A] flex-shrink-0 shadow-xs">
                {{-- Outline Checkmark Icon (Dark stroke sesuai mockup) --}}
                <svg class="w-7 h-7 text-[#0F172A]" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 font-poppins">STATUS AKUN</p>
                <p class="text-3xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight leading-tight mt-0.5 font-poppins">
                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                </p>
                <p class="text-xs sm:text-sm font-medium text-slate-500 mt-0.5 truncate font-poppins">
                    Bergabung {{ $user->created_at ? $user->created_at->format('d M Y') : '23 Okt 2026' }}
                </p>
            </div>
        </div>

    </div>

    {{-- 3. KARTU INFORMASI AKUN (GRID 2x2 DENGAN TOMBOL EDIT PROFIL MERAH) --}}
    <div class="bg-white border border-slate-200/90 rounded-[28px] sm:rounded-[32px] p-6 sm:p-8 shadow-xs space-y-5 sm:space-y-6">
        
        {{-- Header Bar --}}
        <div class="flex items-center justify-between">
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight font-poppins">Informasi akun</h2>
            
            <a href="{{ route('profil.edit') }}"
               class="inline-flex items-center justify-center px-6 py-2.5 rounded-full bg-[#EF4444] hover:bg-[#DC2626] text-white font-bold text-sm tracking-wide shadow-[0_4px_14px_rgba(239,68,68,0.30)] transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] font-poppins">
                Edit profil
            </a>
        </div>

        {{-- 2x2 Grid Detail Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            
            {{-- ITEM 1: NAMA LENGKAP --}}
            <div class="border border-slate-200/90 rounded-[22px] p-4 sm:p-5 flex items-center gap-4 sm:gap-5 bg-white hover:border-slate-300 transition-colors">
                <div class="w-12 h-12 rounded-[16px] bg-[#DBEAFE] flex items-center justify-center text-[#3B82F6] flex-shrink-0">
                    {{-- User Icon --}}
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
                    {{-- Mail Icon --}}
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
                    {{-- Shield Icon --}}
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 font-poppins">PERAN</p>
                    <p class="text-base sm:text-lg font-bold text-slate-900 mt-0.5 truncate font-poppins">{{ ucfirst($user->role) }}</p>
                </div>
            </div>

            {{-- ITEM 4: KELAS (DIPASTIKAN PERSIS SESUAI DESAIN SEMUA AKTOR) --}}
            <div class="border border-slate-200/90 rounded-[22px] p-4 sm:p-5 flex items-center gap-4 sm:gap-5 bg-white hover:border-slate-300 transition-colors">
                <div class="w-12 h-12 rounded-[16px] bg-[#D1FAE5] flex items-center justify-center text-[#059669] flex-shrink-0">
                    {{-- Graduation Cap Icon --}}
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14v7" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 font-poppins">KELAS</p>
                    <p class="text-base sm:text-lg font-bold text-slate-900 mt-0.5 truncate font-poppins">
                        @if($user->isSiswa())
                            Kelas {{ $user->kelas ?? 1 }} SD
                        @elseif($user->isGuru())
                            {{ $user->sekolah->nama_sekolah ?? ($user->teachingClassRooms->pluck('name')->first() ?? 'Kelas 1 SD') }}
                        @else
                            Semua Kelas (Admin)
                        @endif
                    </p>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
