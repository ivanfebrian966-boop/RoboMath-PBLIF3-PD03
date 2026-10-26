@extends('layouts.guest')

@section('title', 'RoboMath - Belajar Matematika SD Jadi Lebih Interaktif & Seru')

@section('content')

<style>
/* Cursor Particle Effect */
.cursor-particle {
    position: fixed;
    pointer-events: none;
    border-radius: 50%;
    transform: translate(-50%, -50%);
    animation: particleFade 0.65s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
    z-index: 99999;
}
@keyframes particleFade {
    0%   { opacity: 1; transform: translate(-50%, -50%) scale(1); }
    100% { opacity: 0; transform: translate(calc(-50% + var(--dx, 0px)), calc(-50% + var(--dy, -20px))) scale(0.15); }
}
@media (prefers-reduced-motion: reduce) {
    .cursor-particle { display: none !important; }
}

/* Floating Animations for Hero Math Symbols */
@keyframes floatA {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50%      { transform: translateY(-12px) rotate(4deg); }
}
@keyframes floatB {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50%      { transform: translateY(-16px) rotate(-5deg); }
}
@keyframes floatC {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50%      { transform: translateY(-10px) rotate(6deg); }
}
.float-a { animation: floatA 4s ease-in-out infinite; }
.float-b { animation: floatB 4.6s ease-in-out infinite 0.6s; }
.float-c { animation: floatC 3.8s ease-in-out infinite 1.2s; }

/* Subtle card lift */
.card-hover {
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.card-hover:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 30px -10px rgba(0, 0, 0, 0.08);
}

/* Hide scrollbar */
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>

<div class="relative overflow-x-hidden selection:bg-amber-200 selection:text-amber-900 bg-[#FAF6EF] min-h-screen">

    {{-- NAVBAR PILL --}}
    <header class="sticky top-safe top-3 sm:top-4 z-50 max-w-3xl mx-auto px-3 sm:px-4"
            x-data="{
                activeNav: 'hero',
                mobileMenuOpen: false,
                init() {
                    const sections = ['hero', 'fitur', 'modul', 'prestasi'];
                    const updateActive = () => {
                        const scrollY = window.scrollY;
                        // Bottom of page check
                        if ((window.innerHeight + scrollY) >= (document.documentElement.scrollHeight - 100)) {
                            this.activeNav = 'prestasi';
                            return;
                        }
                        const offset = 220;
                        for (let i = sections.length - 1; i >= 0; i--) {
                            const el = document.getElementById(sections[i]);
                            if (el && scrollY + offset >= el.offsetTop) {
                                this.activeNav = sections[i];
                                return;
                            }
                        }
                        this.activeNav = 'hero';
                    };
                    window.addEventListener('scroll', updateActive, { passive: true });
                    window.addEventListener('resize', updateActive, { passive: true });
                    updateActive();
                }
            }"
            @click.outside="mobileMenuOpen = false"
            @keydown.window.escape="mobileMenuOpen = false">
        <nav class="bg-white/95 backdrop-blur-md rounded-full px-3.5 sm:px-5 py-2 sm:py-2.5 flex items-center justify-between border transition-all duration-300 shadow-md"
             :class="{
                 'border-red-200/90 shadow-[0_6px_25px_rgba(255,59,48,0.12)]': activeNav === 'hero',
                 'border-blue-200/90 shadow-[0_6px_25px_rgba(52,120,246,0.12)]': activeNav === 'fitur',
                 'border-emerald-200/90 shadow-[0_6px_25px_rgba(16,185,129,0.12)]': activeNav === 'modul',
                 'border-amber-200/90 shadow-[0_6px_25px_rgba(255,149,0,0.12)]': activeNav === 'prestasi',
             }">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 group flex-shrink-0">
                <img src="{{ asset('images/logo.png') }}" alt="RoboMath Logo" class="h-6 sm:h-7 w-auto transition-transform duration-200 group-hover:scale-105">
            </a>

            {{-- Nav Links Desktop / Tablet --}}
            <div class="hidden md:flex items-center gap-1 sm:gap-2 text-xs font-semibold overflow-x-auto no-scrollbar py-0.5">
                {{-- Beranda: Merah #FF3B30 --}}
                <a href="#hero"
                   @click="activeNav = 'hero'"
                   :class="activeNav === 'hero' ? 'border border-[#FF3B30] text-[#FF3B30] bg-red-50/80 shadow-xs font-bold px-3.5 py-1 rounded-full' : 'text-slate-600 hover:text-[#FF3B30] hover:bg-red-50/50 px-3 py-1 rounded-full transition-colors'"
                   class="transition-all duration-200 whitespace-nowrap">
                    Beranda
                </a>

                {{-- Fitur AI: Biru #3478F6 --}}
                <a href="#fitur"
                   @click="activeNav = 'fitur'"
                   :class="activeNav === 'fitur' ? 'border border-[#3478F6] text-[#3478F6] bg-blue-50/80 shadow-xs font-bold px-3.5 py-1 rounded-full' : 'text-slate-600 hover:text-[#3478F6] hover:bg-blue-50/50 px-3 py-1 rounded-full transition-colors'"
                   class="transition-all duration-200 whitespace-nowrap">
                    Fitur AI
                </a>

                {{-- Modul SD: Hijau #10B981 --}}
                <a href="#modul"
                   @click="activeNav = 'modul'"
                   :class="activeNav === 'modul' ? 'border border-[#10B981] text-[#10B981] bg-emerald-50/80 shadow-xs font-bold px-3.5 py-1 rounded-full' : 'text-slate-600 hover:text-[#10B981] hover:bg-emerald-50/50 px-3 py-1 rounded-full transition-colors'"
                   class="transition-all duration-200 whitespace-nowrap">
                    Modul SD
                </a>

                {{-- Prestasi: Oranye #FF9500 --}}
                <a href="#prestasi"
                   @click="activeNav = 'prestasi'"
                   :class="activeNav === 'prestasi' ? 'border border-[#FF9500] text-[#FF9500] bg-amber-50/80 shadow-xs font-bold px-3.5 py-1 rounded-full' : 'text-slate-600 hover:text-[#FF9500] hover:bg-amber-50/50 px-3 py-1 rounded-full transition-colors'"
                   class="transition-all duration-200 whitespace-nowrap">
                    Prestasi
                </a>
            </div>

            {{-- Auth Action & Hamburger Toggle --}}
            <div class="flex items-center gap-1.5 sm:gap-2 text-xs font-bold flex-shrink-0">
                @auth
                    <a href="{{ auth()->user()->isGuru() ? route('guru.dashboard') : (auth()->user()->isAdmin() ? route('admin.dashboard') : route('siswa.dashboard')) }}"
                       class="bg-slate-900 hover:bg-slate-800 text-white px-3 sm:px-4 py-1.5 rounded-full transition-all text-xs">
                        Dashboard &rarr;
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       :class="{
                           'border-[#FF3B30]/60 text-[#FF3B30] hover:bg-[#FF3B30]': activeNav === 'hero',
                           'border-[#3478F6]/60 text-[#3478F6] hover:bg-[#3478F6]': activeNav === 'fitur',
                           'border-[#10B981]/60 text-[#10B981] hover:bg-[#10B981]': activeNav === 'modul',
                           'border-[#FF9500]/60 text-[#FF9500] hover:bg-[#FF9500]': activeNav === 'prestasi',
                       }"
                       class="border hover:text-white px-3 sm:px-4 py-1.5 rounded-full transition-all duration-200 whitespace-nowrap">
                        Masuk
                    </a>
                @endauth

                {{-- Mobile Hamburger Toggle Button --}}
                <button type="button"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        class="md:hidden flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-800 transition-all duration-200 active:scale-95 focus:outline-none"
                        aria-label="Toggle Menu Navigasi">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24" x-show="!mobileMenuOpen">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24" x-show="mobileMenuOpen" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </nav>

        {{-- Mobile Dropdown Menu Card (Mobile & iOS) --}}
        <div x-show="mobileMenuOpen"
             x-cloak
             x-transition:enter="transition ease-out duration-200 transform"
             x-transition:enter-start="opacity-0 -translate-y-3 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-150 transform"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 -translate-y-3 scale-95"
             class="md:hidden mt-2 bg-white/95 backdrop-blur-md rounded-3xl border border-amber-200/80 shadow-xl p-3 space-y-1.5">
            {{-- Beranda --}}
            <a href="#hero"
               @click="activeNav = 'hero'; mobileMenuOpen = false"
               :class="activeNav === 'hero' ? 'bg-red-50 text-[#FF3B30] font-black border border-red-200' : 'text-slate-700 hover:bg-red-50/50 font-bold'"
               class="flex items-center gap-2.5 px-4 py-2.5 rounded-2xl text-sm transition-all duration-200">
                <span class="w-2 h-2 rounded-full bg-[#FF3B30]"></span>
                <span>Beranda</span>
            </a>

            {{-- Fitur AI --}}
            <a href="#fitur"
               @click="activeNav = 'fitur'; mobileMenuOpen = false"
               :class="activeNav === 'fitur' ? 'bg-blue-50 text-[#3478F6] font-black border border-blue-200' : 'text-slate-700 hover:bg-blue-50/50 font-bold'"
               class="flex items-center gap-2.5 px-4 py-2.5 rounded-2xl text-sm transition-all duration-200">
                <span class="w-2 h-2 rounded-full bg-[#3478F6]"></span>
                <span>Fitur AI Interaktif</span>
            </a>

            {{-- Modul SD --}}
            <a href="#modul"
               @click="activeNav = 'modul'; mobileMenuOpen = false"
               :class="activeNav === 'modul' ? 'bg-emerald-50 text-[#10B981] font-black border border-emerald-200' : 'text-slate-700 hover:bg-emerald-50/50 font-bold'"
               class="flex items-center gap-2.5 px-4 py-2.5 rounded-2xl text-sm transition-all duration-200">
                <span class="w-2 h-2 rounded-full bg-[#10B981]"></span>
                <span>Modul Matematika SD</span>
            </a>

            {{-- Prestasi --}}
            <a href="#prestasi"
               @click="activeNav = 'prestasi'; mobileMenuOpen = false"
               :class="activeNav === 'prestasi' ? 'bg-amber-50 text-[#FF9500] font-black border border-amber-200' : 'text-slate-700 hover:bg-amber-50/50 font-bold'"
               class="flex items-center gap-2.5 px-4 py-2.5 rounded-2xl text-sm transition-all duration-200">
                <span class="w-2 h-2 rounded-full bg-[#FF9500]"></span>
                <span>Prestasi & Juara</span>
            </a>
        </div>
    </header>

    {{-- HERO SECTION --}}
    <section id="hero" class="relative pt-12 pb-16 px-4 text-center scroll-mt-24">
        {{-- Headline --}}
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-black tracking-tight text-slate-900 leading-[1.2] max-w-4xl mx-auto mb-4">
            Belajar Matematika SD<br>
            Jadi Lebih <span class="inline-flex font-black">
                <span class="text-[#FF3B30]">I</span><span class="text-[#FF9500]">n</span><span class="text-[#10B981]">t</span><span class="text-[#00A88F]">e</span><span class="text-[#3478F6]">r</span><span class="text-[#3478F6]">a</span><span class="text-[#7C3AED]">k</span><span class="text-[#EC4899]">t</span><span class="text-[#FF3B30]">i</span><span class="text-[#FF3B30]">f</span>
                <span class="mx-1.5 text-[#FF9500]">&amp;</span>
                <span class="text-[#FF9500]">S</span><span class="text-[#10B981]">e</span><span class="text-[#3478F6]">r</span><span class="text-[#7C3AED]">u</span>
            </span>
        </h1>

        {{-- Subtitle --}}
        <p class="text-sm sm:text-base text-slate-600 font-medium max-w-xl mx-auto leading-relaxed mb-7">
            Dari penjumlahan dasar hingga pemecahan masalah, RoboMath membantu siswa SD belajar mandiri dengan latihan soal adaptif berbasis AI serta gamifikasi poin, badge, dan leaderboard.
        </p>

        {{-- Action Buttons --}}
        <div class="flex items-center justify-center gap-3 mb-12">
            <a href="{{ route('login') }}"
               class="bg-[#FF3B30] hover:bg-[#e03126] text-white text-xs sm:text-sm font-bold px-6 sm:px-7 py-2.5 sm:py-3 rounded-full shadow-md hover:shadow-lg transition-all duration-200 hover:-translate-y-0.5">
                Mulai Belajar
            </a>
            <a href="#fitur"
               class="bg-white hover:bg-slate-50 text-slate-800 text-xs sm:text-sm font-bold px-6 sm:px-7 py-2.5 sm:py-3 rounded-full border border-slate-200/90 shadow-sm transition-all duration-200 hover:-translate-y-0.5">
                Lihat Fitur
            </a>
        </div>

        {{-- Interactive Math Card Mockup with Floating Elements --}}
        <div class="relative max-w-md mx-auto pt-4 pb-2 px-6">
            {{-- Red '+' on left --}}
            <div class="absolute -left-4 sm:-left-8 top-12 w-12 sm:w-14 h-12 sm:h-14 bg-[#FF3B30] rounded-2xl flex items-center justify-center text-white text-2xl sm:text-3xl font-black shadow-lg shadow-red-500/25 select-none z-10">
                +
            </div>

            {{-- Yellow-orange '+' on upper right --}}
            <div class="absolute -right-2 sm:-right-6 -top-2 w-10 sm:w-12 h-10 sm:h-12 bg-[#FF9500] rounded-2xl flex items-center justify-center text-white text-xl sm:text-2xl font-black shadow-md shadow-orange-500/25 select-none z-10">
                +
            </div>

            {{-- Blue 'x' on lower right --}}
            <div class="absolute -right-3 sm:-right-8 bottom-6 w-12 sm:w-14 h-12 sm:h-14 bg-[#3478F6] rounded-2xl flex items-center justify-center text-white text-xl sm:text-2xl font-black shadow-lg shadow-blue-500/25 select-none z-10">
                &#10006;
            </div>

            {{-- Central Card --}}
            <div class="bg-white rounded-3xl p-6 sm:p-7 shadow-[0_15px_40px_rgba(0,0,0,0.06)] border border-slate-100 text-center relative z-0">
                <div class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-wide mb-6">
                    7 &times; 8 = ?
                </div>

                <div class="grid grid-cols-4 gap-2 sm:gap-2.5 mb-4">
                    <div class="py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-bold text-slate-600 bg-white hover:bg-slate-50 transition cursor-default">
                        49
                    </div>
                    <div class="py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-bold text-slate-600 bg-white hover:bg-slate-50 transition cursor-default">
                        54
                    </div>
                    <div class="py-2.5 rounded-xl border-2 border-[#10B981] bg-emerald-50/60 text-xs sm:text-sm font-black text-[#10B981] shadow-xs cursor-default">
                        56
                    </div>
                    <div class="py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-bold text-slate-600 bg-white hover:bg-slate-50 transition cursor-default">
                        63
                    </div>
                </div>

                <div class="text-[11px] sm:text-xs text-slate-500 font-medium">
                    Benar! 7 &times; 8 = 56. Kamu dapat <span class="font-bold text-[#10B981]">+10 poin</span>.
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION: FITUR UTAMA ROBOMATH --}}
    <section id="fitur" class="max-w-5xl mx-auto px-4 py-16 scroll-mt-24">
        <div class="text-center max-w-xl mx-auto mb-10">
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mb-2">
                Fitur Utama RoboMath
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 font-medium leading-relaxed">
                Dirancang untuk siswa SD belajar mandiri dengan AI adaptif, gamifikasi, dan pemantauan guru secara real-time.
            </p>
        </div>

        {{-- BENTO GRID --}}
        <div class="space-y-4">
            {{-- Row 1: Left tall red card (5 cols) + Right stack (7 cols) --}}
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                {{-- Card 1: Red (Adaptive Questions) --}}
                <div class="card-hover md:col-span-6 bg-[#FF3B30] text-white p-7 rounded-3xl flex flex-col justify-between min-h-[260px] relative overflow-hidden">
                    <div>
                        <h3 class="text-xl sm:text-2xl font-black leading-snug">
                            Soal yang menyesuaikan kemampuan anak
                        </h3>
                        <p class="text-xs sm:text-sm font-medium text-white/90 leading-relaxed mt-3 max-w-md">
                            AI menganalisis kelemahan siswa dan memberi soal pilihan ganda yang sesuai dengan tingkat kemampuannya.
                        </p>
                    </div>

                    {{-- Rising Bar Chart Graphic --}}
                    <div class="flex items-end gap-2.5 h-20 pt-4 mt-6">
                        <div class="flex-1 bg-white/40 rounded-t-lg h-[28%]"></div>
                        <div class="flex-1 bg-white/45 rounded-t-lg h-[40%]"></div>
                        <div class="flex-1 bg-white/50 rounded-t-lg h-[35%]"></div>
                        <div class="flex-1 bg-white/60 rounded-t-lg h-[60%]"></div>
                        <div class="flex-1 bg-white/75 rounded-t-lg h-[80%]"></div>
                        <div class="flex-1 bg-white rounded-t-lg h-[100%] shadow-sm"></div>
                    </div>
                </div>

                {{-- Right Column Stack (Blue + Orange) --}}
                <div class="md:col-span-6 flex flex-col gap-4">
                    {{-- Card 2: Blue (Multiple Choice) --}}
                    <div class="card-hover flex-1 bg-[#3478F6] text-white p-6 rounded-3xl flex items-center justify-between min-h-[120px]">
                        <div class="pr-4">
                            <h3 class="text-base sm:text-lg font-black leading-snug">
                                Latihan Soal Pilihan Ganda
                            </h3>
                            <p class="text-xs text-blue-50/90 font-medium leading-relaxed mt-1">
                                Feedback langsung setelah menjawab, lengkap dengan penjelasan yang mudah dipahami.
                            </p>
                        </div>
                        {{-- 2x2 Circle Pills (A, B, C, D) --}}
                        <div class="grid grid-cols-2 gap-1.5 flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-xs font-black">A</div>
                            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-xs font-black">B</div>
                            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-xs font-black">C</div>
                            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-xs font-black">D</div>
                        </div>
                    </div>

                    {{-- Card 3: Orange (Gamification) --}}
                    <div class="card-hover flex-1 bg-[#FF9500] text-white p-6 rounded-3xl flex items-center justify-between min-h-[120px]">
                        <div class="pr-4">
                            <h3 class="text-base sm:text-lg font-black leading-snug">
                                Gamifikasi
                            </h3>
                            <p class="text-xs text-orange-50/90 font-medium leading-relaxed mt-1">
                                Skor, badge, dan leaderboard yang membuat semangat belajar terus naik.
                            </p>
                        </div>
                        <div class="text-3xl sm:text-4xl font-black text-white flex-shrink-0 tracking-tight">
                            +120
                        </div>
                    </div>
                </div>
            </div>

            {{-- Row 2: 3 Cards (Green, Blue, Teal) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Card 4: Green (Dashboard Guru) --}}
                <div class="card-hover bg-[#65B741] text-white p-6 rounded-3xl flex flex-col justify-between min-h-[160px]">
                    <div>
                        <h3 class="text-base sm:text-lg font-black leading-snug">
                            Dashboard Guru
                        </h3>
                        <p class="text-xs text-green-50/90 font-medium leading-relaxed mt-2">
                            Pantau progres siswa, kelola kelas, pilih topik, dan unduh laporan PDF atau Excel.
                        </p>
                    </div>
                </div>

                {{-- Card 5: Ocean Blue (Panel Admin + AI) --}}
                <div class="card-hover bg-[#0284C7] text-white p-6 rounded-3xl flex flex-col justify-between min-h-[160px]">
                    <div>
                        <h3 class="text-base sm:text-lg font-black leading-snug">
                            Panel Admin + AI
                        </h3>
                        <p class="text-xs text-sky-50/90 font-medium leading-relaxed mt-2">
                            Unggah materi, AI membuat soal otomatis, lalu tinjau dan terbitkan.
                        </p>
                    </div>
                </div>

                {{-- Card 6: Teal (Progres Siswa) --}}
                <div class="card-hover bg-[#0D9488] text-white p-6 rounded-3xl flex flex-col justify-between min-h-[160px]">
                    <div>
                        <h3 class="text-base sm:text-lg font-black leading-snug">
                            Progres Siswa
                        </h3>
                        <p class="text-xs text-teal-50/90 font-medium leading-relaxed mt-2">
                            Lihat kemajuan lewat progress bar, skor latihan, dan badge.
                        </p>
                    </div>
                    <div class="flex items-center gap-2 mt-4 pt-2">
                        <div class="flex-1 bg-white/25 rounded-full h-2 overflow-hidden">
                            <div class="bg-white h-2 rounded-full" style="width: 78%"></div>
                        </div>
                        <span class="text-xs font-black text-white">78%</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION: MODUL PEMBELAJARAN SD --}}
    <section id="modul" class="max-w-5xl mx-auto px-4 py-12 scroll-mt-24">
        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 text-center tracking-tight mb-8">
            Modul Pembelajaran SD
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-8">
            {{-- SD 1: Mint green --}}
            <div class="card-hover bg-[#A7F3D0] rounded-3xl p-6 border border-emerald-300 flex flex-col justify-between min-h-[200px] relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-lg font-black text-slate-900 leading-snug">
                        Mengenal Angka
                    </h3>
                </div>
                <div class="text-4xl font-black text-emerald-900/25 select-none tracking-widest mt-6">
                    1 2 3
                </div>
            </div>
            <div class="card-hover bg-[#FEF08A] rounded-3xl p-6 border border-amber-300 flex flex-col justify-between min-h-[200px] relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-lg font-black text-slate-900 leading-snug">
                        Operasi Dasar
                    </h3>
                </div>
                <div class="text-3xl font-black text-amber-900/25 select-none space-x-2 mt-6">
                    <span>+</span><span>&minus;</span><span>&times;</span>
                </div>
            </div>
            <div class="card-hover bg-[#A5F3FC] rounded-3xl p-6 border border-cyan-300 flex flex-col justify-between min-h-[200px] relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-lg font-black text-slate-900 leading-snug">
                        Pecahan Sederhana
                    </h3>
                </div>
                <div class="text-3xl font-black text-cyan-900/25 select-none space-x-2 mt-6">
                    <span>&frac12;</span><span>&#8532;</span>
                </div>
            </div>
            <div class="card-hover bg-[#FECDD3] rounded-3xl p-6 border border-rose-300 flex flex-col justify-between min-h-[200px] relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-lg font-black text-slate-900 leading-snug">
                        Pecahan Lanjutan
                    </h3>
                </div>
                <div class="text-3xl font-black text-rose-900/25 select-none space-x-2 mt-6">
                    <span>&frac34;</span><span>&#8531;</span>
                </div>
            </div>
        </div>

        {{-- Center Button --}}
        <div class="text-center">
            <a href="{{ route('login') }}"
               class="inline-block bg-[#0F172A] hover:bg-slate-800 text-white text-xs sm:text-sm font-bold px-7 py-2.5 rounded-full shadow-md transition-all duration-200 hover:-translate-y-0.5">
                Jelajahi semua modul
            </a>
        </div>
    </section>

    {{-- SECTION: LENCANA PRESTASI --}}
    <section id="prestasi" class="max-w-4xl mx-auto px-4 py-12 scroll-mt-24">
        <div class="text-center max-w-xl mx-auto mb-8">
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mb-1">
                Lencana Prestasi
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 font-medium">
                Siswa merayakan prestasinya melalui gamifikasi.
            </p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            {{-- Badge 1 --}}
            <div class="card-hover bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_16px_rgba(0,0,0,0.03)] flex flex-col items-center text-center">
                <div class="w-12 h-12 rounded-full bg-[#FF9500] text-white flex items-center justify-center text-xl font-black select-none">
                    &#10006;
                </div>
                <h4 class="font-bold text-xs text-slate-900 mt-3">Master Perkalian</h4>
                <p class="text-[11px] text-slate-500 mt-0.5">Lulus 5 kuis cepat</p>
            </div>

            {{-- Badge 2 --}}
            <div class="card-hover bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_16px_rgba(0,0,0,0.03)] flex flex-col items-center text-center">
                <div class="w-12 h-12 rounded-full bg-[#FF9500] text-white flex items-center justify-center text-xl font-black select-none">
                    &#9650;
                </div>
                <h4 class="font-bold text-xs text-slate-900 mt-3">Jenius Geometri</h4>
                <p class="text-[11px] text-slate-500 mt-0.5">Kuasai bangun datar</p>
            </div>

            {{-- Badge 3 --}}
            <div class="card-hover bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_16px_rgba(0,0,0,0.03)] flex flex-col items-center text-center">
                <div class="w-12 h-12 rounded-full bg-[#00A88F] text-white flex items-center justify-center text-base font-black select-none">
                    &frac12;
                </div>
                <h4 class="font-bold text-xs text-slate-900 mt-3">Ahli Pecahan</h4>
                <p class="text-[11px] text-slate-500 mt-0.5">Akurasi 100% satu topik</p>
            </div>

            {{-- Badge 4 --}}
            <div class="card-hover bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_16px_rgba(0,0,0,0.03)] flex flex-col items-center text-center">
                <div class="w-12 h-12 rounded-full bg-[#7C3AED] text-white flex items-center justify-center text-xl font-black select-none">
                    5
                </div>
                <h4 class="font-bold text-xs text-slate-900 mt-3">Level Lima</h4>
                <p class="text-[11px] text-slate-500 mt-0.5">Capai level 5</p>
            </div>
        </div>
    </section>

    {{-- FOOTER CARD --}}
    <footer class="pt-8 pb-12 px-4 sm:px-6">
        <div class="max-w-5xl mx-auto bg-white rounded-[32px] border border-slate-200/80 shadow-[0_10px_35px_rgba(0,0,0,0.04)] p-8 sm:p-12">
            <div class="flex flex-col lg:flex-row gap-8 lg:gap-16 justify-between">
                {{-- Left Side --}}
                <div class="lg:w-1/2 space-y-4">
                    <img src="{{ asset('images/logo.png') }}" alt="RoboMath Logo" class="h-8 w-auto">
                    <h2 class="text-3xl sm:text-4xl font-black leading-tight text-slate-900">
                        Mari Wujudkan Belajar<br>
                        Menyenangkan<br>
                        <span class="inline-flex flex-wrap font-black">
                            <span class="text-[#FF3B30]">B</span><span class="text-[#FF9500]">e</span><span class="text-[#FFCC00]">r</span><span class="text-[#10B981]">s</span><span class="text-[#00A88F]">a</span><span class="text-[#3478F6]">m</span><span class="text-[#7C3AED]">a</span>
                            <span class="mx-1.5"></span>
                            <span class="text-[#EC4899]">K</span><span class="text-[#FF3B30]">a</span><span class="text-[#FF9500]">m</span><span class="text-[#10B981]">i</span><span class="text-[#3478F6]">.</span>
                        </span>
                    </h2>
                    <p class="text-xs sm:text-sm font-medium text-slate-500 leading-relaxed max-w-sm">
                        Aplikasi pembelajaran matematika SD berbasis AI adaptif dengan asisten pintar RoboBot dan gamifikasi seru untuk anak.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('login') }}"
                           class="inline-block bg-[#FF3B30] hover:bg-[#e03126] text-white text-xs sm:text-sm font-bold px-7 py-2.5 rounded-full shadow-md transition-all duration-200 hover:-translate-y-0.5">
                            Mulai Sekarang
                        </a>
                    </div>
                </div>

                {{-- Right Side Columns --}}
                <div class="flex gap-12 sm:gap-20 pt-2">
                    {{-- Tautan Cepat --}}
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-wider text-slate-900 mb-4">
                            TAUTAN CEPAT
                        </h4>
                        <ul class="space-y-2.5 text-xs font-medium text-slate-600">
                            <li><a href="#hero" class="hover:text-[#FF3B30] transition-colors">Beranda</a></li>
                            <li><a href="#fitur" class="hover:text-[#FF3B30] transition-colors">Fitur AI</a></li>
                            <li><a href="#modul" class="hover:text-[#FF3B30] transition-colors">Modul SD</a></li>
                            <li><a href="{{ route('login') }}" class="hover:text-[#FF3B30] transition-colors">Masuk</a></li>
                        </ul>
                    </div>

                    {{-- Informasi --}}
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-wider text-slate-900 mb-4">
                            INFORMASI
                        </h4>
                        <ul class="space-y-2.5 text-xs font-medium text-slate-600">
                            <li><a href="{{ route('siswa.dashboard') }}" class="hover:text-[#3478F6] transition-colors">Dashboard Siswa</a></li>
                            <li><a href="{{ route('guru.dashboard') }}" class="hover:text-[#3478F6] transition-colors">Dashboard Guru</a></li>
                            <li><a href="#" class="hover:text-[#3478F6] transition-colors">Kebijakan Privasi</a></li>
                            <li><a href="#" class="hover:text-[#3478F6] transition-colors">Syarat &amp; Ketentuan</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Rainbow Line Divider --}}
            <div class="h-1 rounded-full overflow-hidden flex my-7">
                <div class="flex-1 bg-[#FF3B30]"></div>
                <div class="flex-1 bg-[#FF9500]"></div>
                <div class="flex-1 bg-[#FFCC00]"></div>
                <div class="flex-1 bg-[#10B981]"></div>
                <div class="flex-1 bg-[#00A88F]"></div>
                <div class="flex-1 bg-[#3478F6]"></div>
                <div class="flex-1 bg-[#7C3AED]"></div>
            </div>

            {{-- Card Bottom Copyright --}}
            <div class="text-[11px] text-slate-500 font-medium">
                &copy; {{ date('Y') }} <strong class="text-slate-800">RoboMath</strong> Hak cipta dilindungi.
            </div>
        </div>
    </footer>

</div>

{{-- Cursor Particle Script --}}
<script>
(function() {
    var colors = ['#FF3B30', '#FF9500', '#FFCC00', '#10B981', '#00A88F', '#3478F6', '#7C3AED', '#EC4899'];
    var lastX = 0, lastY = 0, lastTime = 0;

    window.addEventListener('mousemove', function(e) {
        var now = Date.now();
        var dist = Math.hypot(e.clientX - lastX, e.clientY - lastY);

        if (now - lastTime > 40 && dist > 8) {
            lastTime = now;
            lastX = e.clientX;
            lastY = e.clientY;

            var p = document.createElement('div');
            p.className = 'cursor-particle';
            var size = Math.floor(Math.random() * 6) + 4;
            var color = colors[Math.floor(Math.random() * colors.length)];
            var dx = (Math.random() - 0.5) * 30;
            var dy = -(Math.random() * 20 + 10);

            p.style.cssText = 'width:' + size + 'px;height:' + size + 'px;background:' + color + ';left:' + e.clientX + 'px;top:' + e.clientY + 'px;--dx:' + dx + 'px;--dy:' + dy + 'px';
            document.body.appendChild(p);

            setTimeout(function() {
                p.remove();
            }, 650);
        }
    });
})();
</script>

@endsection
