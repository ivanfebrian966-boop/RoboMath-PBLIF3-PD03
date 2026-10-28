<aside class="w-64 flex-shrink-0 hidden md:block self-stretch" id="app-sidebar">
    <div class="bg-white rounded-[32px] p-5 border-2 border-amber-200/80 shadow-sm sticky top-20 flex flex-col justify-between gap-6 min-h-[480px] max-h-[calc(100vh-6rem)] overflow-y-auto">
        
        <!-- Bagian Atas: Label & Navigasi -->
        <div class="space-y-3">
            @auth
                <!-- Label Menu Sesuai Peran -->
                <div class="text-xs font-black text-[#A16207] uppercase tracking-wider px-1">
                    @if(auth()->user()->isSiswa())
                        MENU SISWA
                    @elseif(auth()->user()->isGuru())
                        MENU GURU
                    @else
                        MENU ADMIN
                    @endif
                </div>

                <!-- 1. Navigasi Siswa (Sesuai Mockup Gambar) -->
                @if(auth()->user()->isSiswa())
                    <nav class="space-y-2">
                        {{-- Dashboard Siswa --}}
                        @php $isActive = request()->routeIs('siswa.dashboard') || request()->routeIs('siswa.achievements'); @endphp
                        <a href="{{ route('siswa.dashboard') }}"
                           class="flex items-center gap-3.5 px-3 py-2.5 rounded-[22px] text-sm transition-all duration-200 {{ $isActive ? 'bg-[#FF8A00] text-[#001738] font-black shadow-[0_12px_24px_rgba(255,138,0,0.38)]' : 'text-[#001738] font-black border border-transparent hover:border-orange-200 hover:bg-orange-50 hover:shadow-xs' }} active:scale-95 group">
                            <span class="w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0 transition-transform {{ $isActive ? 'bg-[#FFA834] text-[#001738]' : 'bg-orange-100 text-orange-600 group-hover:scale-105' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5 12 3l9 7.5v10.5a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V10.5Z" />
                                </svg>
                            </span>
                            <span>Dashboard Siswa</span>
                        </a>

                        {{-- Latihan Soal --}}
                        @php $isActive = request()->routeIs('siswa.quiz*') || request()->routeIs('siswa.topics*') || request()->routeIs('siswa.lessons*'); @endphp
                        <a href="{{ route('siswa.quiz') }}"
                           class="flex items-center gap-3.5 px-3 py-2.5 rounded-[22px] text-sm transition-all duration-200 {{ $isActive ? 'bg-[#2563EB] text-white font-black shadow-[0_12px_24px_rgba(37,99,235,0.35)]' : 'text-[#001738] font-black border border-transparent hover:border-blue-200 hover:bg-blue-50 hover:shadow-xs' }} active:scale-95 group">
                            <span class="w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0 transition-transform {{ $isActive ? 'bg-blue-100 text-blue-700' : 'bg-[#E0EDFF] text-[#2563EB] group-hover:scale-105' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-1.5m-3.2-3.3A5.5 5.5 0 1 1 15.2 13.2c-.7.7-1.2 1.6-1.2 2.6H10c0-1-.5-1.9-1.2-2.6ZM9.5 20.5h5" />
                                </svg>
                            </span>
                            <span>Latihan Soal</span>
                        </a>

                        {{-- Kode Kelas (Topik Soal AI dari Guru) --}}
                        @php $isActive = request()->routeIs('siswa.kelas*'); @endphp
                        <a href="{{ route('siswa.kelas.join') }}"
                           class="flex items-center gap-3.5 px-3 py-2.5 rounded-[22px] text-sm transition-all duration-200 {{ $isActive ? 'bg-[#00A88F] text-white font-black shadow-[0_12px_24px_rgba(0,168,143,0.35)]' : 'text-[#001738] font-black border border-transparent hover:border-teal-200 hover:bg-teal-50 hover:shadow-xs' }} active:scale-95 group">
                            <span class="w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0 transition-transform {{ $isActive ? 'bg-[#008f7a] text-white' : 'bg-[#CCFBF1] text-[#00A88F] group-hover:scale-105' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                                </svg>
                            </span>
                            <span>Kode Kelas</span>
                        </a>

                        
                        {{-- Leaderboard --}}
                        @php $isActive = request()->routeIs('siswa.leaderboard*'); @endphp
                        <a href="{{ route('siswa.leaderboard') }}"
                           class="flex items-center gap-3.5 px-3 py-2.5 rounded-[22px] text-sm transition-all duration-200 {{ $isActive ? 'bg-[#D97706] text-white font-black shadow-[0_12px_24px_rgba(217,119,6,0.35)]' : 'text-[#001738] font-black border border-transparent hover:border-amber-200 hover:bg-amber-50 hover:shadow-xs' }} active:scale-95 group">
                            <span class="w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0 transition-transform {{ $isActive ? 'bg-amber-100 text-amber-700' : 'bg-[#FEF3C7] text-[#D97706] group-hover:scale-105' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 21h8m-4-4v4m-5-9.5V7a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v4.5a5 5 0 0 1-5 5 5 5 0 0 1-5-5Zm0-2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h2m10 4.5h2a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                                </svg>
                            </span>
                            <span>Leaderboard</span>
                        </a>

                        {{-- Profil --}}
                        @php $isActive = request()->routeIs('profil*'); @endphp
                        <a href="{{ route('profil.show') }}"
                           class="flex items-center gap-3.5 px-3 py-2.5 rounded-[22px] text-sm transition-all duration-200 {{ $isActive ? 'bg-[#059669] text-white font-black shadow-[0_12px_24px_rgba(5,150,105,0.35)]' : 'text-[#001738] font-black border border-transparent hover:border-emerald-200 hover:bg-emerald-50 hover:shadow-xs' }} active:scale-95 group">
                            <span class="w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0 transition-transform {{ $isActive ? 'bg-emerald-100 text-emerald-700' : 'bg-[#DCFCE7] text-[#059669] group-hover:scale-105' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                            </span>
                            <span>Profil</span>
                        </a>
                    </nav>

                <!-- 2. Navigasi Guru (Warna-Warni Sesuai Modul) -->
                @elseif(auth()->user()->isGuru())
                    <nav class="space-y-2">
                        {{-- Dashboard Guru (Tema Indigo) --}}
                        @php $isActive = request()->routeIs('guru.dashboard') || request()->routeIs('guru.students*'); @endphp
                        <a href="{{ route('guru.dashboard') }}"
                           class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm transition-all duration-200 {{ $isActive ? 'bg-[#4F46E5] text-white font-black shadow-[0_8px_20px_rgba(79,70,229,0.35)] hover:scale-[1.02]' : 'text-slate-800 font-bold hover:bg-indigo-50/70 hover:text-indigo-950 hover:scale-[1.01]' }} active:scale-95 group">
                            <span class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 {{ $isActive ? 'bg-white/20 text-white' : 'bg-indigo-100 text-indigo-600 group-hover:scale-105' }} transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </span>
                            <span>Dashboard Guru</span>
                        </a>

                        {{-- Kelola Kelas (Tema Hijau Emerald) --}}
                        @php $isActive = request()->routeIs('guru.classes*'); @endphp
                        <a href="{{ route('guru.classes.index') }}"
                           class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm transition-all duration-200 {{ $isActive ? 'bg-[#059669] text-white font-black shadow-[0_8px_20px_rgba(5,150,105,0.35)] hover:scale-[1.02]' : 'text-slate-800 font-bold hover:bg-emerald-50/70 hover:text-emerald-950 hover:scale-[1.01]' }} active:scale-95 group">
                            <span class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 {{ $isActive ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-600 group-hover:scale-105' }} transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </span>
                            <span>Kelola Kelas</span>
                        </a>

                        {{-- Progress Siswa (Tema Kuning Emas/Amber) --}}
                        @php $isActive = request()->routeIs('guru.leaderboard*'); @endphp
                        <a href="{{ route('guru.leaderboard') }}"
                           class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm transition-all duration-200 {{ $isActive ? 'bg-[#F59E0B] text-slate-950 font-black shadow-[0_8px_20px_rgba(245,158,11,0.35)] hover:scale-[1.02]' : 'text-slate-800 font-bold hover:bg-amber-50/70 hover:text-amber-950 hover:scale-[1.01]' }} active:scale-95 group">
                            <span class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 {{ $isActive ? 'bg-amber-200/60 text-slate-950' : 'bg-amber-100 text-amber-700 group-hover:scale-105' }} transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                </svg>
                            </span>
                            <span>Progress Siswa</span>
                        </a>

                        {{-- Profil (Tema Hijau Toska / Teal) --}}
                        @php $isActive = request()->routeIs('profil*'); @endphp
                        <a href="{{ route('profil.show') }}"
                           class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm transition-all duration-200 {{ $isActive ? 'bg-[#00A88F] text-white font-black shadow-[0_8px_20px_rgba(0,168,143,0.35)] hover:scale-[1.02]' : 'text-slate-800 font-bold hover:bg-teal-50/70 hover:text-teal-950 hover:scale-[1.01]' }} active:scale-95 group">
                            <span class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 {{ $isActive ? 'bg-white/20 text-white' : 'bg-teal-100 text-teal-600 group-hover:scale-105' }} transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </span>
                            <span>Profil</span>
                        </a>
                    </nav>

                <!-- 3. Navigasi Admin (Warna-Warni Sesuai Modul) -->
                @elseif(auth()->user()->isAdmin())
                    <nav class="space-y-2">
                        {{-- Dashboard Admin --}}
                        @php $isActive = request()->routeIs('admin.dashboard') || request()->routeIs('admin.reports*'); @endphp
                        <a href="{{ route('admin.dashboard') }}"
                           class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm transition-all duration-200 {{ $isActive ? 'bg-[#FF9500] text-white font-black shadow-[0_8px_20px_rgba(255,149,0,0.32)] hover:scale-[1.02]' : 'text-slate-800 font-bold hover:bg-orange-50/70 hover:text-orange-950 hover:scale-[1.01]' }} active:scale-95 group">
                            <span class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 {{ $isActive ? 'bg-white/20 text-white' : 'bg-orange-100 text-orange-600 group-hover:scale-105' }} transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                            </span>
                            <span>Dashboard Admin</span>
                        </a>

                        {{-- Kelola Materi (Tema Oranye) --}}
                        @php $isActive = request()->routeIs('admin.document-summarizer*') || request()->routeIs('admin.lessons*'); @endphp
                        <a href="{{ route('admin.document-summarizer.index') }}"
                           class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm transition-all duration-200 {{ $isActive ? 'bg-[#FF9500] text-white font-black shadow-[0_8px_20px_rgba(255,149,0,0.32)] hover:scale-[1.02]' : 'text-slate-800 font-bold hover:bg-orange-50/70 hover:text-orange-950 hover:scale-[1.01]' }} active:scale-95 group">
                            <span class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 {{ $isActive ? 'bg-white/20 text-white' : 'bg-orange-100 text-orange-600 group-hover:scale-105' }} transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </span>
                            <span>Kelola Materi</span>
                        </a>

                        {{-- Review AI (Tema Biru Muda) --}}
                        @php $isActive = request()->routeIs('admin.ai-recommendations*') || request()->routeIs('admin.quizzes*'); @endphp
                        <a href="{{ route('admin.ai-recommendations.index') }}"
                           class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm transition-all duration-200 {{ $isActive ? 'bg-[#3B82F6] text-white font-black shadow-[0_8px_20px_rgba(59,130,246,0.35)] hover:scale-[1.02]' : 'text-slate-800 font-bold hover:bg-blue-50/70 hover:text-blue-950 hover:scale-[1.01]' }} active:scale-95 group">
                            <span class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 {{ $isActive ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-500 group-hover:scale-105' }} transition-transform">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l2.4 7.2L22 12l-7.6 2.8L12 22l-2.4-7.2L2 12l7.6-2.8z"/>
                                </svg>
                            </span>
                            <span>Review AI</span>
                            @php
                                $pendingCount = \App\Models\Quiz::where('review_status', 'pending')->count() + \App\Models\AiRecommendation::where('status', 'pending')->count();
                            @endphp
                            @if($pendingCount > 0 && !$isActive)
                                <span class="ml-auto text-[10px] font-black px-2 py-0.5 rounded-full bg-rose-500 text-white">{{ $pendingCount }}</span>
                            @endif
                        </a>

                        {{-- Kelola Pengguna (Tema Hijau Mint / Emerald) --}}
                        @php $isActive = request()->routeIs('admin.users*'); @endphp
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm transition-all duration-200 {{ $isActive ? 'bg-[#10B981] text-white font-black shadow-[0_8px_20px_rgba(16,185,129,0.35)] hover:scale-[1.02]' : 'text-slate-800 font-bold hover:bg-emerald-50/70 hover:text-emerald-950 hover:scale-[1.01]' }} active:scale-95 group">
                            <span class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 {{ $isActive ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-600 group-hover:scale-105' }} transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </span>
                            <span>Kelola Pengguna</span>
                        </a>

                        {{-- Profil (Tema Kuning / Amber) --}}
                        @php $isActive = request()->routeIs('profil*'); @endphp
                        <a href="{{ route('profil.show') }}"
                           class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm transition-all duration-200 {{ $isActive ? 'bg-[#F59E0B] text-slate-950 font-black shadow-[0_8px_20px_rgba(245,158,11,0.35)] hover:scale-[1.02]' : 'text-slate-800 font-bold hover:bg-amber-50/70 hover:text-amber-950 hover:scale-[1.01]' }} active:scale-95 group">
                            <span class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 {{ $isActive ? 'bg-amber-200/60 text-slate-950' : 'bg-amber-100 text-amber-600 group-hover:scale-105' }} transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </span>
                            <span>Profil</span>
                        </a>
                    </nav>
                @endif
            @endauth
        </div>

        <!-- Bagian Bawah: Card Level (Siswa), Garis Warna-Warni, & Tombol Keluar -->
        <div class="space-y-4 pt-2">
            @auth
                @if(auth()->user()->isSiswa())
                    @php
                        $score = auth()->user()->total_score ?? 0;
                        $lvl = auth()->user()->level ?? 1;
                        $nextTarget = match($lvl) {
                            1 => 500,
                            2 => 1500,
                            3 => 3000,
                            4 => 5000,
                            default => 5000,
                        };
                        $prevTarget = match($lvl) {
                            1 => 0,
                            2 => 500,
                            3 => 1500,
                            4 => 3000,
                            default => 5000,
                        };
                        $diff = max(1, $nextTarget - $prevTarget);
                        $progressPercent = $lvl >= 5 ? 100 : min(100, max(5, round((($score - $prevTarget) / $diff) * 100)));
                        $remaining = max(0, $nextTarget - $score);
                    @endphp
                @endif
            @endauth

            {{-- 4 Garis Warna-Warni Horisontal --}}
            <div class="flex items-center gap-1.5 h-1 w-full" aria-hidden="true">
                <div class="flex-1 h-full rounded-full bg-[#FF3B30]"></div>
                <div class="flex-1 h-full rounded-full bg-[#FF9500]"></div>
                <div class="flex-1 h-full rounded-full bg-[#10B981]"></div>
                <div class="flex-1 h-full rounded-full bg-[#3478F6]"></div>
            </div>

            {{-- Tombol Keluar --}}
            @auth
                <form action="{{ route('logout') }}" method="POST" class="w-full m-0">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2.5 py-3 rounded-full font-black text-sm bg-[#FFF0F0] border border-rose-200/80 text-rose-700 hover:bg-rose-100 hover:border-rose-300 transition-all duration-200 active:scale-95 cursor-pointer shadow-2xs">
                        <svg class="w-4 h-4 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            @endauth
        </div>

    </div>
</aside>

<!-- ========================================== -->
<!-- MOBILE & iOS SIDEBAR DRAWER (OFF-CANVAS)   -->
<!-- HANYA TAMPIL DI UKURAN MOBILE (< md)       -->
<!-- ========================================== -->
@auth
<div class="hidden max-md:block md:!hidden"
     x-cloak
     x-show="mobileSidebarOpen"
     @keydown.window.escape="mobileSidebarOpen = false">

    <!-- Backdrop Overlay dengan efek blur & fade -->
    <div x-show="mobileSidebarOpen"
         x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileSidebarOpen = false"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 transition-opacity"
         aria-hidden="true"></div>

    <!-- Panel Sidebar Drawer Bergerak Halus dari Kiri -->
    <div x-show="mobileSidebarOpen"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 left-0 w-[86%] max-w-[320px] bg-white z-50 shadow-2xl flex flex-col justify-between overflow-y-auto border-r-2 border-amber-200/80 pt-safe pb-safe touch-scroll"
         id="mobile-sidebar-drawer">
         
        <!-- Bagian Atas: Header, Profil Card & Navigasi -->
        <div class="p-5 space-y-4">
            <!-- Header Drawer: Logo + Role Badge + Tombol Tutup (X) -->
            <div class="flex items-center justify-between pb-3 border-b border-amber-100">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" alt="RoboMath Logo" class="h-7 w-auto drop-shadow-sm">
                    @if(auth()->user()->isAdmin())
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black bg-[#FEF3C7] text-[#92400E] border border-[#FDE68A]">
                            Admin
                        </span>
                    @elseif(auth()->user()->isGuru())
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black bg-[#DBEAFE] text-[#1E40AF] border border-[#BFDBFE]">
                            Guru
                        </span>
                    @elseif(auth()->user()->isSiswa())
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black bg-[#D1FAE5] text-[#065F46] border border-[#A7F3D0]">
                            Siswa
                        </span>
                    @endif
                </div>

                <!-- Tombol Tutup (X) dengan target tap iOS 44px -->
                <button type="button"
                        @click="mobileSidebarOpen = false"
                        class="w-10 h-10 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition-all duration-150 active:scale-90 cursor-pointer focus:outline-none"
                        aria-label="Tutup Menu">
                    <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Profile Card Pengguna (Mobile) -->
            <div class="bg-gradient-to-br from-amber-50/80 via-orange-50/40 to-white rounded-2xl p-3.5 border border-amber-200/70 shadow-2xs">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-[#FF8A00] flex items-center justify-center text-[#001738] font-black text-base shadow-xs flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <h4 class="font-black text-slate-900 text-sm truncate leading-tight">{{ auth()->user()->name }}</h4>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-[#A15822] truncate mt-0.5">{{ auth()->user()->role }}</p>
                    </div>
                </div>

                @if(auth()->user()->isSiswa())
                    <!-- Siswa XP Stats & Level Progress (Mobile) -->
                    <div class="mt-3 pt-2.5 border-t border-amber-200/60 space-y-1.5">
                        <div class="flex items-center justify-between text-xs font-black">
                            <span class="text-[#001738] flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-[#F59E0B]" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                                {{ auth()->user()->total_score ?? 0 }} Poin
                            </span>
                            <span class="bg-[#FF8A00] text-[#001738] text-[10px] px-2 py-0.5 rounded-full font-black">
                                Level {{ auth()->user()->level ?? 1 }}
                            </span>
                        </div>

                        <!-- Progress Bar -->
                        <div class="w-full bg-amber-100/90 rounded-full h-2 overflow-hidden">
                            <div class="bg-gradient-to-r from-amber-500 to-orange-500 h-full rounded-full transition-all duration-500"
                                 style="width: {{ $progressPercent ?? 10 }}%"></div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Label Menu Sesuai Peran -->
            <div class="text-[11px] font-black text-[#A16207] uppercase tracking-wider px-1">
                @if(auth()->user()->isSiswa())
                    MENU SISWA
                @elseif(auth()->user()->isGuru())
                    MENU GURU
                @else
                    MENU ADMIN
                @endif
            </div>

            <!-- 1. Navigasi Siswa (Mobile) -->
            @if(auth()->user()->isSiswa())
                <nav class="space-y-1.5">
                    {{-- Dashboard Siswa --}}
                    @php $isActive = request()->routeIs('siswa.dashboard') || request()->routeIs('siswa.achievements'); @endphp
                    <a href="{{ route('siswa.dashboard') }}"
                       @click="mobileSidebarOpen = false"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-2xl text-sm transition-all duration-200 {{ $isActive ? 'bg-[#FF8A00] text-[#001738] font-black shadow-[0_8px_20px_rgba(255,138,0,0.35)]' : 'text-[#001738] font-bold hover:bg-orange-50 hover:text-orange-900' }} active:scale-95 group">
                        <span class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 transition-transform {{ $isActive ? 'bg-[#FFA834] text-[#001738]' : 'bg-orange-100 text-orange-600 group-hover:scale-105' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5 12 3l9 7.5v10.5a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V10.5Z" />
                            </svg>
                        </span>
                        <span>Dashboard Siswa</span>
                    </a>

                    {{-- Latihan Soal --}}
                    @php $isActive = request()->routeIs('siswa.quiz*') || request()->routeIs('siswa.topics*') || request()->routeIs('siswa.lessons*'); @endphp
                    <a href="{{ route('siswa.quiz') }}"
                       @click="mobileSidebarOpen = false"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-2xl text-sm transition-all duration-200 {{ $isActive ? 'bg-[#2563EB] text-white font-black shadow-[0_8px_20px_rgba(37,99,235,0.35)]' : 'text-[#001738] font-bold hover:bg-blue-50 hover:text-blue-900' }} active:scale-95 group">
                        <span class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 transition-transform {{ $isActive ? 'bg-blue-100 text-blue-700' : 'bg-[#E0EDFF] text-[#2563EB] group-hover:scale-105' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-1.5m-3.2-3.3A5.5 5.5 0 1 1 15.2 13.2c-.7.7-1.2 1.6-1.2 2.6H10c0-1-.5-1.9-1.2-2.6ZM9.5 20.5h5" />
                            </svg>
                        </span>
                        <span>Latihan Soal</span>
                    </a>

                    {{-- Kode Kelas (Topik Soal AI dari Guru) --}}
                    @php $isActive = request()->routeIs('siswa.kelas*'); @endphp
                    <a href="{{ route('siswa.kelas.join') }}"
                       @click="mobileSidebarOpen = false"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-2xl text-sm transition-all duration-200 {{ $isActive ? 'bg-[#00A88F] text-white font-black shadow-[0_8px_20px_rgba(0,168,143,0.35)]' : 'text-[#001738] font-bold hover:bg-teal-50 hover:text-teal-900' }} active:scale-95 group">
                        <span class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 transition-transform {{ $isActive ? 'bg-[#008f7a] text-white' : 'bg-[#CCFBF1] text-[#00A88F] group-hover:scale-105' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                            </svg>
                        </span>
                        <span>Kode Kelas</span>
                    </a>

                    {{-- Leaderboard --}}
                    @php $isActive = request()->routeIs('siswa.leaderboard*'); @endphp
                    <a href="{{ route('siswa.leaderboard') }}"
                       @click="mobileSidebarOpen = false"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-2xl text-sm transition-all duration-200 {{ $isActive ? 'bg-[#D97706] text-white font-black shadow-[0_8px_20px_rgba(217,119,6,0.35)]' : 'text-[#001738] font-bold hover:bg-amber-50 hover:text-amber-900' }} active:scale-95 group">
                        <span class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 transition-transform {{ $isActive ? 'bg-amber-100 text-amber-700' : 'bg-[#FEF3C7] text-[#D97706] group-hover:scale-105' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 21h8m-4-4v4m-5-9.5V7a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v4.5a5 5 0 0 1-5 5 5 5 0 0 1-5-5Zm0-2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h2m10 4.5h2a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                            </svg>
                        </span>
                        <span>Leaderboard</span>
                    </a>

                    {{-- Profil --}}
                    @php $isActive = request()->routeIs('profil*'); @endphp
                    <a href="{{ route('profil.show') }}"
                       @click="mobileSidebarOpen = false"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-2xl text-sm transition-all duration-200 {{ $isActive ? 'bg-[#059669] text-white font-black shadow-[0_8px_20px_rgba(5,150,105,0.35)]' : 'text-[#001738] font-bold hover:bg-emerald-50 hover:text-emerald-900' }} active:scale-95 group">
                        <span class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 transition-transform {{ $isActive ? 'bg-emerald-100 text-emerald-700' : 'bg-[#DCFCE7] text-[#059669] group-hover:scale-105' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </span>
                        <span>Profil Saya</span>
                    </a>
                </nav>

            <!-- 2. Navigasi Guru (Mobile) -->
            @elseif(auth()->user()->isGuru())
                <nav class="space-y-1.5">
                    {{-- Dashboard Guru --}}
                    @php $isActive = request()->routeIs('guru.dashboard') || request()->routeIs('guru.students*'); @endphp
                    <a href="{{ route('guru.dashboard') }}"
                       @click="mobileSidebarOpen = false"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm transition-all duration-200 {{ $isActive ? 'bg-[#4F46E5] text-white font-black shadow-[0_8px_20px_rgba(79,70,229,0.35)]' : 'text-slate-800 font-bold hover:bg-indigo-50 hover:text-indigo-950' }} active:scale-95 group">
                        <span class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 {{ $isActive ? 'bg-white/20 text-white' : 'bg-indigo-100 text-indigo-600 group-hover:scale-105' }} transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </span>
                        <span>Dashboard Guru</span>
                    </a>

                    {{-- Kelola Kelas --}}
                    @php $isActive = request()->routeIs('guru.classes*'); @endphp
                    <a href="{{ route('guru.classes.index') }}"
                       @click="mobileSidebarOpen = false"
                       class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-2xl text-sm transition-all duration-200 {{ $isActive ? 'bg-[#059669] text-white font-black shadow-[0_8px_20px_rgba(5,150,105,0.35)]' : 'text-slate-800 font-bold hover:bg-emerald-50 hover:text-emerald-950' }} active:scale-95 group">
                        <span class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 {{ $isActive ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-600 group-hover:scale-105' }} transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </span>
                        <span>Kelola Kelas</span>
                    </a>

                    {{-- Progress Siswa --}}
                    @php $isActive = request()->routeIs('guru.leaderboard*'); @endphp
                    <a href="{{ route('guru.leaderboard') }}"
                       @click="mobileSidebarOpen = false"
                       class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-2xl text-sm transition-all duration-200 {{ $isActive ? 'bg-[#F59E0B] text-slate-950 font-black shadow-[0_8px_20px_rgba(245,158,11,0.35)]' : 'text-slate-800 font-bold hover:bg-amber-50 hover:text-amber-950' }} active:scale-95 group">
                        <span class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 {{ $isActive ? 'bg-amber-200/60 text-slate-950' : 'bg-amber-100 text-amber-700 group-hover:scale-105' }} transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                        </span>
                        <span>Progress Siswa</span>
                    </a>

                    {{-- Profil --}}
                    @php $isActive = request()->routeIs('profil*'); @endphp
                    <a href="{{ route('profil.show') }}"
                       @click="mobileSidebarOpen = false"
                       class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-2xl text-sm transition-all duration-200 {{ $isActive ? 'bg-[#00A88F] text-white font-black shadow-[0_8px_20px_rgba(0,168,143,0.35)]' : 'text-slate-800 font-bold hover:bg-teal-50 hover:text-teal-950' }} active:scale-95 group">
                        <span class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 {{ $isActive ? 'bg-white/20 text-white' : 'bg-teal-100 text-teal-600 group-hover:scale-105' }} transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </span>
                        <span>Profil</span>
                    </a>
                </nav>

            <!-- 3. Navigasi Admin (Mobile) -->
            @elseif(auth()->user()->isAdmin())
                <nav class="space-y-1.5">
                    {{-- Dashboard Admin --}}
                    @php $isActive = request()->routeIs('admin.dashboard') || request()->routeIs('admin.reports*'); @endphp
                    <a href="{{ route('admin.dashboard') }}"
                       @click="mobileSidebarOpen = false"
                       class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-2xl text-sm transition-all duration-200 {{ $isActive ? 'bg-[#FF9500] text-white font-black shadow-[0_8px_20px_rgba(255,149,0,0.32)]' : 'text-slate-800 font-bold hover:bg-orange-50 hover:text-orange-950' }} active:scale-95 group">
                        <span class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 {{ $isActive ? 'bg-white/20 text-white' : 'bg-orange-100 text-orange-600 group-hover:scale-105' }} transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                        </span>
                        <span>Dashboard Admin</span>
                    </a>

                    {{-- Kelola Materi --}}
                    @php $isActive = request()->routeIs('admin.document-summarizer*') || request()->routeIs('admin.lessons*'); @endphp
                    <a href="{{ route('admin.document-summarizer.index') }}"
                       @click="mobileSidebarOpen = false"
                       class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-2xl text-sm transition-all duration-200 {{ $isActive ? 'bg-[#FF9500] text-white font-black shadow-[0_8px_20px_rgba(255,149,0,0.32)]' : 'text-slate-800 font-bold hover:bg-orange-50 hover:text-orange-950' }} active:scale-95 group">
                        <span class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 {{ $isActive ? 'bg-white/20 text-white' : 'bg-orange-100 text-orange-600 group-hover:scale-105' }} transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </span>
                        <span>Kelola Materi</span>
                    </a>

                    {{-- Review AI --}}
                    @php $isActive = request()->routeIs('admin.ai-recommendations*') || request()->routeIs('admin.quizzes*'); @endphp
                    <a href="{{ route('admin.ai-recommendations.index') }}"
                       @click="mobileSidebarOpen = false"
                       class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-2xl text-sm transition-all duration-200 {{ $isActive ? 'bg-[#3B82F6] text-white font-black shadow-[0_8px_20px_rgba(59,130,246,0.35)]' : 'text-slate-800 font-bold hover:bg-blue-50 hover:text-blue-950' }} active:scale-95 group">
                        <span class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 {{ $isActive ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-500 group-hover:scale-105' }} transition-transform">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l2.4 7.2L22 12l-7.6 2.8L12 22l-2.4-7.2L2 12l7.6-2.8z"/>
                            </svg>
                        </span>
                        <span>Review AI</span>
                        @php
                            $pendingCount = \App\Models\Quiz::where('review_status', 'pending')->count() + \App\Models\AiRecommendation::where('status', 'pending')->count();
                        @endphp
                        @if($pendingCount > 0 && !$isActive)
                            <span class="ml-auto text-[10px] font-black px-2 py-0.5 rounded-full bg-rose-500 text-white">{{ $pendingCount }}</span>
                        @endif
                    </a>

                    {{-- Kelola Pengguna --}}
                    @php $isActive = request()->routeIs('admin.users*'); @endphp
                    <a href="{{ route('admin.users.index') }}"
                       @click="mobileSidebarOpen = false"
                       class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-2xl text-sm transition-all duration-200 {{ $isActive ? 'bg-[#10B981] text-white font-black shadow-[0_8px_20px_rgba(16,185,129,0.35)]' : 'text-slate-800 font-bold hover:bg-emerald-50 hover:text-emerald-950' }} active:scale-95 group">
                        <span class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 {{ $isActive ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-600 group-hover:scale-105' }} transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </span>
                        <span>Kelola Pengguna</span>
                    </a>

                    {{-- Profil --}}
                    @php $isActive = request()->routeIs('profil*'); @endphp
                    <a href="{{ route('profil.show') }}"
                       @click="mobileSidebarOpen = false"
                       class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-2xl text-sm transition-all duration-200 {{ $isActive ? 'bg-[#F59E0B] text-slate-950 font-black shadow-[0_8px_20px_rgba(245,158,11,0.35)]' : 'text-slate-800 font-bold hover:bg-amber-50 hover:text-amber-950' }} active:scale-95 group">
                        <span class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 {{ $isActive ? 'bg-amber-200/60 text-slate-950' : 'bg-amber-100 text-amber-600 group-hover:scale-105' }} transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </span>
                        <span>Profil</span>
                    </a>
                </nav>
            @endif
        </div>

        <!-- Bagian Bawah: Garis Warna-Warni & Tombol Keluar (Mobile) -->
        <div class="p-5 space-y-3.5 border-t border-amber-100 bg-[#FFFDF9]">
            {{-- 4 Garis Warna-Warni Horisontal --}}
            <div class="flex items-center gap-1.5 h-1 w-full" aria-hidden="true">
                <div class="flex-1 h-full rounded-full bg-[#FF3B30]"></div>
                <div class="flex-1 h-full rounded-full bg-[#FF9500]"></div>
                <div class="flex-1 h-full rounded-full bg-[#10B981]"></div>
                <div class="flex-1 h-full rounded-full bg-[#3478F6]"></div>
            </div>

            {{-- Tombol Keluar --}}
            <form action="{{ route('logout') }}" method="POST" class="w-full m-0">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2.5 py-3 rounded-full font-black text-sm bg-[#FFF0F0] border border-rose-200/80 text-rose-700 hover:bg-rose-100 hover:border-rose-300 transition-all duration-200 active:scale-95 cursor-pointer shadow-2xs">
                    <svg class="w-4 h-4 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Keluar</span>
                </button>
            </form>
        </div>

    </div>
</div>
@endauth
