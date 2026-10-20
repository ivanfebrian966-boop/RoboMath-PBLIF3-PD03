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
                           class="flex items-center gap-3.5 px-3 py-2.5 rounded-[22px] text-sm transition-all duration-200 {{ $isActive ? 'bg-[#FF8A00] text-[#001738] font-black shadow-[0_12px_24px_rgba(255,138,0,0.38)]' : 'text-[#001738] font-black border border-transparent hover:border-[#FDE68A] hover:bg-[#FFFBF5] hover:shadow-xs' }} active:scale-95 group">
                            <span class="w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0 transition-transform {{ $isActive ? 'bg-[#FFA834] text-[#001738]' : 'bg-[#FFEBEB] text-[#FF3B30] group-hover:scale-105' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5 12 3l9 7.5v10.5a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V10.5Z" />
                                </svg>
                            </span>
                            <span>Dashboard Siswa</span>
                        </a>

                        {{-- Latihan Soal --}}
                        @php $isActive = request()->routeIs('siswa.quiz*') || request()->routeIs('siswa.topics*') || request()->routeIs('siswa.lessons*'); @endphp
                        <a href="{{ route('siswa.quiz') }}"
                           class="flex items-center gap-3.5 px-3 py-2.5 rounded-[22px] text-sm transition-all duration-200 {{ $isActive ? 'bg-[#FF8A00] text-[#001738] font-black shadow-[0_12px_24px_rgba(255,138,0,0.38)]' : 'text-[#001738] font-black border border-transparent hover:border-[#FDE68A] hover:bg-[#FFFBF5] hover:shadow-xs' }} active:scale-95 group">
                            <span class="w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0 transition-transform {{ $isActive ? 'bg-[#FFA834] text-[#001738]' : 'bg-[#E0EDFF] text-[#2563EB] group-hover:scale-105' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-1.5m-3.2-3.3A5.5 5.5 0 1 1 15.2 13.2c-.7.7-1.2 1.6-1.2 2.6H10c0-1-.5-1.9-1.2-2.6ZM9.5 20.5h5" />
                                </svg>
                            </span>
                            <span>Latihan Soal</span>
                        </a>

                        
                        {{-- Leaderboard --}}
                        @php $isActive = request()->routeIs('siswa.leaderboard*'); @endphp
                        <a href="{{ route('siswa.leaderboard') }}"
                           class="flex items-center gap-3.5 px-3 py-2.5 rounded-[22px] text-sm transition-all duration-200 {{ $isActive ? 'bg-[#FF8A00] text-[#001738] font-black shadow-[0_12px_24px_rgba(255,138,0,0.38)]' : 'text-[#001738] font-black border border-transparent hover:border-[#FDE68A] hover:bg-[#FFFBF5] hover:shadow-xs' }} active:scale-95 group">
                            <span class="w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0 transition-transform {{ $isActive ? 'bg-[#FFA834] text-[#001738]' : 'bg-[#FEF3C7] text-[#D97706] group-hover:scale-105' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 21h8m-4-4v4m-5-9.5V7a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v4.5a5 5 0 0 1-5 5 5 5 0 0 1-5-5Zm0-2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h2m10 4.5h2a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                                </svg>
                            </span>
                            <span>Leaderboard</span>
                        </a>

                        {{-- Profil --}}
                        @php $isActive = request()->routeIs('profil*'); @endphp
                        <a href="{{ route('profil.show') }}"
                           class="flex items-center gap-3.5 px-3 py-2.5 rounded-[22px] text-sm transition-all duration-200 {{ $isActive ? 'bg-[#FF8A00] text-[#001738] font-black shadow-[0_12px_24px_rgba(255,138,0,0.38)]' : 'text-[#001738] font-black border border-transparent hover:border-[#FDE68A] hover:bg-[#FFFBF5] hover:shadow-xs' }} active:scale-95 group">
                            <span class="w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0 transition-transform {{ $isActive ? 'bg-[#FFA834] text-[#001738]' : 'bg-[#DCFCE7] text-[#059669] group-hover:scale-105' }}">
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
                            <span class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 {{ $isActive ? 'bg-white/20 text-white' : 'bg-rose-100 text-rose-500 group-hover:scale-105' }} transition-transform">
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
                            @php $pendingCount = \App\Models\AiRecommendation::where('status', 'pending')->count(); @endphp
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
