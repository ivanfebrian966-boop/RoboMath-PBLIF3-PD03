@extends('layouts.guest')

@section('title', 'RoboMath - Belajar Matematika SD Jadi Lebih Interaktif & Seru')

@section('content')
<div class="relative overflow-hidden selection:bg-amber-200 selection:text-amber-900 min-h-screen bg-[#FAF6EF]">

    <!-- Ambient Subtle Warm Light Background Glows -->
    <div class="fixed top-0 left-1/4 w-[450px] h-[450px] bg-gradient-to-tr from-amber-200/40 via-orange-200/30 to-pink-200/20 rounded-full blur-[120px] pointer-events-none z-0"></div>
    <div class="fixed top-1/2 right-1/4 w-[500px] h-[500px] bg-gradient-to-tr from-indigo-200/30 via-purple-200/20 to-teal-200/20 rounded-full blur-[130px] pointer-events-none z-0"></div>

    <!-- ============================================================ -->
    <!-- 1. FLOATING PILL NAVBAR (Image 2 Top Reference) -->
    <!-- ============================================================ -->
    <header class="sticky top-4 z-50 max-w-4xl mx-auto px-4"
            x-data="{
                activeNav: 'hero',
                hoveredNav: null,
                init() {
                    const sections = ['hero', 'fitur', 'modul', 'prestasi'];
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                this.activeNav = entry.target.id;
                            }
                        });
                    }, { threshold: 0.3, rootMargin: '-60px 0px -40% 0px' });

                    sections.forEach(id => {
                        const el = document.getElementById(id);
                        if (el) observer.observe(el);
                    });
                }
            }">
        <nav class="bg-white/90 backdrop-blur-md rounded-full px-4 sm:px-5 py-2 flex items-center justify-between border border-amber-200/70 shadow-[0_4px_25px_rgba(0,0,0,0.06)] transition-all">
            
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 group flex-shrink-0">
                <img src="{{ asset('images/logo.png') }}" alt="RoboMath Logo" class="h-7 sm:h-8 w-auto group-hover:scale-105 transition-transform duration-200">
            </a>

            <!-- Animated Expanding Pill Nav Links (After Effects Inspired) -->
            <div class="flex items-center gap-1 sm:gap-1.5 p-1 bg-slate-100/80 rounded-full border border-amber-200/50 shadow-inner">
                
                <!-- 1. Beranda -->
                <a href="#hero"
                   @click="activeNav = 'hero'"
                   @mouseenter="hoveredNav = 'hero'"
                   @mouseleave="hoveredNav = null"
                   class="relative flex items-center h-8 sm:h-9 rounded-full transition-all duration-300 ease-out cursor-pointer"
                   :class="(activeNav === 'hero')
                        ? 'bg-red-50/90 border border-red-400 text-[#FF3B30] shadow-xs px-3 sm:px-3.5 font-black'
                        : (hoveredNav === 'hero'
                            ? 'bg-white border border-red-200 text-[#FF3B30] shadow-2xs px-3 font-bold'
                            : 'text-slate-600 hover:text-slate-900 px-2 sm:px-2.5 border border-transparent')">
                    <svg class="w-4 h-4 flex-shrink-0 transition-transform duration-200"
                         :class="(activeNav === 'hero' || hoveredNav === 'hero') ? 'scale-110 text-[#FF3B30]' : 'text-slate-500'"
                         fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                    </svg>
                    <span class="overflow-hidden transition-all duration-300 ease-out whitespace-nowrap text-xs inline-block"
                          :class="(activeNav === 'hero' || (hoveredNav === 'hero' && hoveredNav !== activeNav)) ? 'max-w-28 opacity-100 ml-1.5' : 'max-w-0 opacity-0 ml-0'">
                        Beranda
                    </span>
                </a>

                <!-- 2. Fitur AI -->
                <a href="#fitur"
                   @click="activeNav = 'fitur'"
                   @mouseenter="hoveredNav = 'fitur'"
                   @mouseleave="hoveredNav = null"
                   class="relative flex items-center h-8 sm:h-9 rounded-full transition-all duration-300 ease-out cursor-pointer"
                   :class="(activeNav === 'fitur')
                        ? 'bg-red-50/90 border border-red-400 text-[#FF3B30] shadow-xs px-3 sm:px-3.5 font-black'
                        : (hoveredNav === 'fitur'
                            ? 'bg-white border border-red-200 text-[#FF3B30] shadow-2xs px-3 font-bold'
                            : 'text-slate-600 hover:text-slate-900 px-2 sm:px-2.5 border border-transparent')">
                    <svg class="w-4 h-4 flex-shrink-0 transition-transform duration-200"
                         :class="(activeNav === 'fitur' || hoveredNav === 'fitur') ? 'scale-110 text-[#FF3B30]' : 'text-slate-500'"
                         fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z"/>
                    </svg>
                    <span class="overflow-hidden transition-all duration-300 ease-out whitespace-nowrap text-xs inline-block"
                          :class="(activeNav === 'fitur' || (hoveredNav === 'fitur' && hoveredNav !== activeNav)) ? 'max-w-28 opacity-100 ml-1.5' : 'max-w-0 opacity-0 ml-0'">
                        Fitur AI
                    </span>
                </a>

                <!-- 3. Modul SD -->
                <a href="#modul"
                   @click="activeNav = 'modul'"
                   @mouseenter="hoveredNav = 'modul'"
                   @mouseleave="hoveredNav = null"
                   class="relative flex items-center h-8 sm:h-9 rounded-full transition-all duration-300 ease-out cursor-pointer"
                   :class="(activeNav === 'modul')
                        ? 'bg-red-50/90 border border-red-400 text-[#FF3B30] shadow-xs px-3 sm:px-3.5 font-black'
                        : (hoveredNav === 'modul'
                            ? 'bg-white border border-red-200 text-[#FF3B30] shadow-2xs px-3 font-bold'
                            : 'text-slate-600 hover:text-slate-900 px-2 sm:px-2.5 border border-transparent')">
                    <svg class="w-4 h-4 flex-shrink-0 transition-transform duration-200"
                         :class="(activeNav === 'modul' || hoveredNav === 'modul') ? 'scale-110 text-[#FF3B30]' : 'text-slate-500'"
                         fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                    </svg>
                    <span class="overflow-hidden transition-all duration-300 ease-out whitespace-nowrap text-xs inline-block"
                          :class="(activeNav === 'modul' || (hoveredNav === 'modul' && hoveredNav !== activeNav)) ? 'max-w-28 opacity-100 ml-1.5' : 'max-w-0 opacity-0 ml-0'">
                        Modul SD
                    </span>
                </a>

                <!-- 4. Prestasi -->
                <a href="#prestasi"
                   @click="activeNav = 'prestasi'"
                   @mouseenter="hoveredNav = 'prestasi'"
                   @mouseleave="hoveredNav = null"
                   class="relative flex items-center h-8 sm:h-9 rounded-full transition-all duration-300 ease-out cursor-pointer"
                   :class="(activeNav === 'prestasi')
                        ? 'bg-red-50/90 border border-red-400 text-[#FF3B30] shadow-xs px-3 sm:px-3.5 font-black'
                        : (hoveredNav === 'prestasi'
                            ? 'bg-white border border-red-200 text-[#FF3B30] shadow-2xs px-3 font-bold'
                            : 'text-slate-600 hover:text-slate-900 px-2 sm:px-2.5 border border-transparent')">
                    <svg class="w-4 h-4 flex-shrink-0 transition-transform duration-200"
                         :class="(activeNav === 'prestasi' || hoveredNav === 'prestasi') ? 'scale-110 text-[#FF3B30]' : 'text-slate-500'"
                         fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"/>
                    </svg>
                    <span class="overflow-hidden transition-all duration-300 ease-out whitespace-nowrap text-xs inline-block"
                          :class="(activeNav === 'prestasi' || (hoveredNav === 'prestasi' && hoveredNav !== activeNav)) ? 'max-w-28 opacity-100 ml-1.5' : 'max-w-0 opacity-0 ml-0'">
                        Prestasi
                    </span>
                </a>

            </div>

            <!-- Auth Buttons -->
            <div class="flex items-center gap-2 font-bold text-xs flex-shrink-0">
                @auth
                    <a href="{{ auth()->user()->isGuru() ? route('guru.dashboard') : (auth()->user()->isOrangtua() ? route('orangtua.dashboard') : (auth()->user()->isAdmin() ? route('admin.dashboard') : route('siswa.dashboard'))) }}"
                       class="bg-slate-950 hover:bg-slate-800 text-white px-4 py-2 rounded-full shadow-sm flex items-center gap-1.5 transition">
                        <span>Dashboard</span>
                        <span>&rarr;</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="border border-red-300 text-red-600 hover:bg-red-50 px-4 py-1.5 rounded-full font-bold transition shadow-2xs">
                        Masuk
                    </a>
                @endauth
            </div>

        </nav>
    </header>


    <!-- ============================================================ -->
    <!-- 2. HERO SECTION & ORBIT MASCOT (Image 2 Column 1) -->
    <!-- ============================================================ -->
    <section id="hero" class="relative pt-10 sm:pt-14 pb-16 text-center z-10">
        <div class="max-w-3xl mx-auto px-6 space-y-4">

            <!-- Big Main Headline -->
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-[1.2] max-w-2xl mx-auto">
                Belajar Matematika SD<br>
                Jadi Lebih 
                <span class="inline-block whitespace-nowrap">
                    <span class="text-[#FF3B30] inline-block hover:scale-110 hover:-translate-y-1 transition-transform duration-200 cursor-default">I</span><span class="text-[#FF9500] inline-block hover:scale-110 hover:-translate-y-1 transition-transform duration-200 cursor-default">n</span><span class="text-[#F59E0B] inline-block hover:scale-110 hover:-translate-y-1 transition-transform duration-200 cursor-default">t</span><span class="text-[#10B981] inline-block hover:scale-110 hover:-translate-y-1 transition-transform duration-200 cursor-default">e</span><span class="text-[#00A88F] inline-block hover:scale-110 hover:-translate-y-1 transition-transform duration-200 cursor-default">r</span><span class="text-[#0284C7] inline-block hover:scale-110 hover:-translate-y-1 transition-transform duration-200 cursor-default">a</span><span class="text-[#3478F6] inline-block hover:scale-110 hover:-translate-y-1 transition-transform duration-200 cursor-default">k</span><span class="text-[#7C3AED] inline-block hover:scale-110 hover:-translate-y-1 transition-transform duration-200 cursor-default">t</span><span class="text-[#EC4899] inline-block hover:scale-110 hover:-translate-y-1 transition-transform duration-200 cursor-default">i</span><span class="text-[#FF3B30] inline-block hover:scale-110 hover:-translate-y-1 transition-transform duration-200 cursor-default">f</span>
                </span>
                <span class="text-[#F59E0B] inline-block mx-1 sm:mx-1.5 hover:scale-110 hover:-translate-y-1 transition-transform duration-200 cursor-default">&amp;</span>
                <span class="inline-block whitespace-nowrap">
                    <span class="text-[#FF9500] inline-block hover:scale-110 hover:-translate-y-1 transition-transform duration-200 cursor-default">S</span><span class="text-[#10B981] inline-block hover:scale-110 hover:-translate-y-1 transition-transform duration-200 cursor-default">e</span><span class="text-[#0284C7] inline-block hover:scale-110 hover:-translate-y-1 transition-transform duration-200 cursor-default">r</span><span class="text-[#7C3AED] inline-block hover:scale-110 hover:-translate-y-1 transition-transform duration-200 cursor-default">u</span>
                </span>
            </h1>
            <!-- Subtitle -->
            <p class="text-xs sm:text-sm text-slate-600 font-semibold max-w-xl mx-auto leading-relaxed">
                Dari penjumlahan dasar hingga pemecahan logika tingkat lanjut, RoboMath membantu siswa SD belajar mandiri dengan <strong>Asisten AI RoboBot</strong> dan gamifikasi poin.
            </p>
        </div>
    </section>


    <!-- ============================================================ -->
    <!-- 3. FITUR UTAMA 6-GRID CARDS -->
    <!-- ============================================================ -->
    <section id="fitur" class="max-w-6xl mx-auto px-6 py-14 relative z-10">
        
        <div class="text-center max-w-2xl mx-auto mb-12 space-y-3">
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                Fitur Utama RoboMath
            </h2>
            <p class="text-sm text-slate-500 font-medium max-w-lg mx-auto leading-relaxed">
                Dirancang untuk siswa SD belajar mandiri dengan AI adaptif, gamifikasi, dan pemantauan guru secara real-time.
            </p>
        </div>

        <!-- 6 Feature Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

            <!-- Card 1: Rekomendasi Soal Adaptif (RoboMath Red) -->
            <div class="bg-[#FF3B30] text-white p-7 rounded-3xl shadow-sm flex flex-col justify-between group hover:-translate-y-1 transition-all duration-200 min-h-[240px]">
                <div class="space-y-3">
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
                    </div>
                    <h3 class="text-base font-black leading-snug">Rekomendasi Soal Adaptif Berbasis AI</h3>
                    <p class="text-xs font-medium text-red-100/85 leading-relaxed">
                        AI menganalisis kelemahan siswa dan memberi soal pilihan ganda yang sesuai dengan tingkat kemampuannya.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-white/20 flex items-center justify-between text-[11px] font-semibold text-red-100">
                    <span>Penyesuaian otomatis</span>
                    <span class="bg-white/15 px-2.5 py-0.5 rounded-md font-bold text-white text-[10px]">Level Personal</span>
                </div>
            </div>

            <!-- Card 2: Latihan Soal Pilihan Ganda (RoboMath Blue) -->
            <div class="bg-[#3478F6] text-white p-7 rounded-3xl shadow-sm flex flex-col justify-between group hover:-translate-y-1 transition-all duration-200 min-h-[240px]">
                <div class="space-y-3">
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
                    </div>
                    <h3 class="text-base font-black leading-snug">Latihan Soal Pilihan Ganda</h3>
                    <p class="text-xs font-medium text-blue-100/85 leading-relaxed">
                        Soal dengan feedback langsung setelah menjawab, lengkap dengan penjelasan jawaban yang mudah dipahami.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-white/20 flex items-center justify-between text-[11px] font-semibold text-blue-100">
                    <span>Feedback instan</span>
                    <span class="bg-white/15 px-2.5 py-0.5 rounded-md font-bold text-white text-[10px]">A, B, C, D</span>
                </div>
            </div>

            <!-- Card 3: Gamifikasi (RoboMath Orange) -->
            <div class="bg-[#FF9500] text-white p-7 rounded-3xl shadow-sm flex flex-col justify-between group hover:-translate-y-1 transition-all duration-200 min-h-[240px]">
                <div class="space-y-3">
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"/></svg>
                    </div>
                    <h3 class="text-base font-black leading-snug">Gamifikasi</h3>
                    <p class="text-xs font-medium text-orange-50/90 leading-relaxed">
                        Skor, badge, dan leaderboard untuk meningkatkan motivasi dan semangat belajar siswa secara menyenangkan.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-white/20 flex items-center justify-between text-[11px] font-semibold text-orange-50">
                    <span>Badge &amp; Leaderboard</span>
                    <span class="bg-white/20 px-2.5 py-0.5 rounded-md font-bold text-white text-[10px]">Top Siswa</span>
                </div>
            </div>

            <!-- Card 4: Dashboard Guru (RoboMath Green) -->
            <div class="bg-[#72C043] text-white p-7 rounded-3xl shadow-sm flex flex-col justify-between group hover:-translate-y-1 transition-all duration-200 min-h-[240px]">
                <div class="space-y-3">
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                    </div>
                    <h3 class="text-base font-black leading-snug">Dashboard Guru</h3>
                    <p class="text-xs font-medium text-green-50/90 leading-relaxed">
                        Pantau progres siswa secara real-time, kelola kelas, pilih topik pembelajaran, dan unduh laporan PDF/Excel.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-white/20 flex items-center justify-between text-[11px] font-semibold text-green-50">
                    <span>Monitoring real-time</span>
                    <span class="bg-white/20 px-2.5 py-0.5 rounded-md font-bold text-white text-[10px]">Export PDF/XLS</span>
                </div>
            </div>

            <!-- Card 5: Panel Admin + AI Generation (RoboMath Sky) -->
            <div class="bg-[#3897D3] text-white p-7 rounded-3xl shadow-sm flex flex-col justify-between group hover:-translate-y-1 transition-all duration-200 min-h-[240px]">
                <div class="space-y-3">
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                    </div>
                    <h3 class="text-base font-black leading-snug">Panel Admin + AI Generation</h3>
                    <p class="text-xs font-medium text-sky-100/85 leading-relaxed">
                        Upload materi, AI generate soal otomatis, review topik &amp; soal, lalu finalisasi konten siap latih.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-white/20 flex items-center justify-between text-[11px] font-semibold text-sky-100">
                    <span>Generate otomatis</span>
                    <span class="bg-white/15 px-2.5 py-0.5 rounded-md font-bold text-white text-[10px]">Review &amp; Publish</span>
                </div>
            </div>

            <!-- Card 6: Progress Tracking (RoboMath Teal) -->
            <div class="bg-[#00A88F] text-white p-7 rounded-3xl shadow-sm flex flex-col justify-between group hover:-translate-y-1 transition-all duration-200 min-h-[240px]">
                <div class="space-y-3">
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/></svg>
                    </div>
                    <h3 class="text-base font-black leading-snug">Progress Tracking Siswa</h3>
                    <p class="text-xs font-medium text-teal-50/90 leading-relaxed">
                        Lihat kemajuan belajar melalui progress bar interaktif, skor latihan, dan perolehan badge prestasi.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-white/20">
                    <div class="flex items-center gap-2">
                        <div class="flex-1 bg-white/25 rounded-full h-1.5 overflow-hidden">
                            <div class="bg-white h-1.5 rounded-full w-[78%]"></div>
                        </div>
                        <span class="text-[11px] font-bold text-teal-50">78%</span>
                        <span class="bg-white/15 px-2.5 py-0.5 rounded-md font-bold text-white text-[10px]">Naik Level</span>
                    </div>
                </div>
            </div>

        </div>

    </section>


    <!-- ============================================================ -->
    <!-- 5. MODUL PEMBELAJARAN SD (Image 2 Column 3 Top) -->
    <!-- ============================================================ -->
    <section id="modul" class="max-w-5xl mx-auto px-6 py-14 relative z-10" x-data="{ activeKelas: 'all' }">
        
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-8">
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                Modul Pembelajaran SD
            </h2>

            <!-- Filter Dropdown Button -->
            <div class="relative">
                <select x-model="activeKelas" class="appearance-none bg-white border-2 border-slate-200 text-slate-800 font-bold text-xs px-5 py-2.5 pr-8 rounded-2xl shadow-sm focus:border-amber-400 focus:outline-none cursor-pointer">
                    <option value="all">Filter Pembelajaran: Semua SD</option>
                    <option value="1">Filter Pembelajaran: SD 1</option>
                    <option value="2">Filter Pembelajaran: SD 2</option>
                    <option value="3">Filter Pembelajaran: SD 3</option>
                    <option value="4">Filter Pembelajaran: SD 4</option>
                </select>
                <div class="absolute right-3 top-3 pointer-events-none text-xs text-slate-500 font-black">▾</div>
            </div>
        </div>

        <!-- 4 Modules Row Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            
            <!-- SD 1 Card (Apple/Lime Green) -->
            <div class="bg-[#86EFAC]/80 hover:bg-[#86EFAC] rounded-3xl p-5 border-2 border-emerald-300 shadow-sm flex flex-col justify-between min-h-[220px] transition duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <span class="font-black text-emerald-950 text-base">SD 1</span>
                    <span class="text-xl">📒</span>
                </div>
                <div class="my-auto py-2">
                    <h4 class="font-black text-slate-900 text-base leading-snug">Mengenal<br>Angka</h4>
                </div>
                <div class="flex justify-end text-3xl font-black text-emerald-800/40 select-none">
                    6 4
                </div>
            </div>

            <!-- SD 2 Card (Golden Yellow) -->
            <div class="bg-[#FDE047]/80 hover:bg-[#FDE047] rounded-3xl p-5 border-2 border-amber-300 shadow-sm flex flex-col justify-between min-h-[220px] transition duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <span class="font-black text-amber-950 text-base">SD 2</span>
                    <span class="text-xl">🧮</span>
                </div>
                <div class="my-auto py-2">
                    <h4 class="font-black text-slate-900 text-base leading-snug">Operasi<br>Dasar</h4>
                </div>
                <div class="flex justify-end text-2xl font-black text-amber-800/40 select-none gap-2">
                    <span>+</span><span>-</span><span>×</span>
                </div>
            </div>

            <!-- SD 3 Card (Mint/Cyan) -->
            <div class="bg-[#99F6E4]/80 hover:bg-[#99F6E4] rounded-3xl p-5 border-2 border-teal-300 shadow-sm flex flex-col justify-between min-h-[220px] transition duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <span class="font-black text-teal-950 text-base">SD 3</span>
                    <span class="text-xl">📊</span>
                </div>
                <div class="my-auto py-2">
                    <h4 class="font-black text-slate-900 text-base leading-snug">Pecahan<br>Sederhana</h4>
                </div>
                <div class="flex justify-end text-2xl font-black text-teal-800/40 select-none">
                    ½ ⅔
                </div>
            </div>

            <!-- SD 4 Card (Coral/Peach) -->
            <div class="bg-[#FECDD3]/80 hover:bg-[#FECDD3] rounded-3xl p-5 border-2 border-rose-300 shadow-sm flex flex-col justify-between min-h-[220px] transition duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <span class="font-black text-rose-950 text-base">SD 4</span>
                    <span class="text-xl">🍕</span>
                </div>
                <div class="my-auto py-2">
                    <h4 class="font-black text-slate-900 text-base leading-snug">Pecahan<br>Lanjutan</h4>
                </div>
                <div class="flex justify-end text-2xl font-black text-rose-800/40 select-none">
                    ¾ ⅓
                </div>
            </div>

        </div>

        <div class="text-center mt-8">
            <a href="{{ route('siswa.topics') }}" class="inline-flex items-center gap-2 bg-slate-950 hover:bg-slate-800 text-white font-black text-xs px-7 py-3 rounded-full shadow-md transition transform active:scale-95">
                <span>Explore Lebih Modul</span>
                <span>&rarr;</span>
            </a>
        </div>

    </section>

    <!-- ============================================================ -->
    <!-- 7. LENCANA PRESTASI (Image 2 Column 3 Lower) -->
    <!-- ============================================================ -->
    <section id="prestasi" class="max-w-4xl mx-auto px-6 py-10 relative z-10 text-center">
        
        <div class="max-w-xl mx-auto mb-8 space-y-1">
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Lencana Prestasi</h2>
            <p class="text-xs text-slate-500 font-semibold">Siswa merayakan prestasinya melalui gamifikasi.</p>
        </div>

        <!-- 4 Badges Row -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            
            <div class="bg-white p-5 rounded-3xl border-2 border-slate-200 shadow-sm flex flex-col items-center text-center space-y-2 group hover:border-amber-400 transition">
                <div class="w-14 h-14 rounded-2xl bg-amber-100 flex items-center justify-center text-3xl shadow-xs group-hover:scale-110 transition">
                    🥇
                </div>
                <h4 class="font-black text-slate-900 text-xs">Master Perkalian</h4>
                <p class="text-[10px] text-slate-400 font-semibold">Lulus 5 Kuis Cepat</p>
            </div>

            <div class="bg-white p-5 rounded-3xl border-2 border-slate-200 shadow-sm flex flex-col items-center text-center space-y-2 group hover:border-orange-400 transition">
                <div class="w-14 h-14 rounded-2xl bg-orange-100 flex items-center justify-center text-3xl shadow-xs group-hover:scale-110 transition">
                    📐
                </div>
                <h4 class="font-black text-slate-900 text-xs">Jenius Geometri</h4>
                <p class="text-[10px] text-slate-400 font-semibold">Kuasai Bangun Datar</p>
            </div>

            <div class="bg-white p-5 rounded-3xl border-2 border-slate-200 shadow-sm flex flex-col items-center text-center space-y-2 group hover:border-teal-400 transition">
                <div class="w-14 h-14 rounded-2xl bg-teal-100 flex items-center justify-center text-3xl shadow-xs group-hover:scale-110 transition">
                    🧩
                </div>
                <h4 class="font-black text-slate-900 text-xs">Dukun Pecahan</h4>
                <p class="text-[10px] text-slate-400 font-semibold">Akurasi 100% Topik</p>
            </div>

            <div class="bg-white p-5 rounded-3xl border-2 border-slate-200 shadow-sm flex flex-col items-center text-center space-y-2 group hover:border-indigo-400 transition">
                <div class="w-14 h-14 rounded-2xl bg-indigo-100 flex items-center justify-center text-3xl shadow-xs group-hover:scale-110 transition">
                    🎓
                </div>
                <h4 class="font-black text-slate-900 text-xs">Lencana Prestasi</h4>
                <p class="text-[10px] text-slate-400 font-semibold">Capai Level 5</p>
            </div>

        </div>

    </section>

    <!-- ============================================================ -->
    <!-- 8. FOOTER CARD (High Contrast, RoboMath Visual & Moodboard) -->
    <!-- ============================================================ -->
    <footer class="relative z-10 pt-10 pb-16 px-4 sm:px-6">
        <div class="max-w-6xl mx-auto bg-white rounded-[32px] border-2 border-amber-200/90 shadow-[0_12px_40px_rgba(245,158,11,0.08)] p-8 sm:p-12 lg:p-14">
            
            <div class="flex flex-col lg:flex-row gap-12 lg:gap-16">

                <!-- LEFT: Brand, Tag, Headline & CTA -->
                <div class="lg:w-[45%] flex-shrink-0 space-y-6">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo.png') }}" alt="RoboMath Logo" class="h-8 w-auto">
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-amber-100 border border-amber-300 text-amber-900 text-[11px] font-black tracking-wide uppercase">
                            Hubungi Kami
                        </span>
                    </div>

                    <!-- Headline: Clear, Dark, High-Contrast Text -->
                    <h2 class="text-3xl sm:text-4xl font-black leading-[1.2] tracking-tight text-slate-900">
                        Mari Wujudkan<br>
                        Belajar Menyenangkan<br>
                        <span class="text-[#3478F6]">Bersama Kami.</span>
                    </h2>

                    <p class="text-xs sm:text-sm font-semibold text-slate-600 max-w-sm leading-relaxed">
                        Aplikasi pembelajaran matematika SD berbasis AI adaptif dengan asisten pintar RoboBot dan gamifikasi seru untuk anak.
                    </p>

                    <!-- CTA Button: Big, Crisp, Easy to Click -->
                    <div>
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center gap-2.5 bg-[#FF3B30] hover:bg-[#E03126] text-white text-xs sm:text-sm font-black px-7 py-3.5 rounded-full shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                            <span>Mulai Sekarang</span>
                            <span>→</span>
                        </a>
                    </div>
                </div>

                <!-- RIGHT: Link Columns -->
                <div class="flex-1 grid grid-cols-2 gap-8 sm:gap-12 lg:pt-2">

                    <!-- Tautan Cepat -->
                    <div class="space-y-4">
                        <h4 class="text-xs font-black uppercase tracking-wider text-slate-900 pb-2 border-b-2 border-amber-200 inline-block">
                            Tautan Cepat
                        </h4>
                        <ul class="space-y-3">
                            <li>
                                <a href="#hero" class="text-sm font-bold text-slate-700 hover:text-[#FF3B30] transition-colors duration-150 block">
                                    Beranda
                                </a>
                            </li>
                            <li>
                                <a href="#fitur" class="text-sm font-bold text-slate-700 hover:text-[#FF3B30] transition-colors duration-150 block">
                                    Fitur AI
                                </a>
                            </li>
                            <li>
                                <a href="#modul" class="text-sm font-bold text-slate-700 hover:text-[#FF3B30] transition-colors duration-150 block">
                                    Modul SD
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('login') }}" class="text-sm font-bold text-slate-700 hover:text-[#FF3B30] transition-colors duration-150 block">
                                    Masuk
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('register') }}" class="text-sm font-bold text-slate-700 hover:text-[#FF3B30] transition-colors duration-150 block">
                                    Daftar
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Informasi (No Orang Tua) -->
                    <div class="space-y-4">
                        <h4 class="text-xs font-black uppercase tracking-wider text-slate-900 pb-2 border-b-2 border-amber-200 inline-block">
                            Informasi
                        </h4>
                        <ul class="space-y-3">
                            <li>
                                <a href="{{ route('siswa.dashboard') }}" class="text-sm font-bold text-slate-700 hover:text-[#3478F6] transition-colors duration-150 block">
                                    Dashboard Siswa
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('guru.dashboard') }}" class="text-sm font-bold text-slate-700 hover:text-[#3478F6] transition-colors duration-150 block">
                                    Dashboard Guru
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-sm font-bold text-slate-700 hover:text-[#3478F6] transition-colors duration-150 block">
                                    Kebijakan Privasi
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-sm font-bold text-slate-700 hover:text-[#3478F6] transition-colors duration-150 block">
                                    Syarat &amp; Ketentuan
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>

            </div>

            <!-- Bottom Bar Inside Card -->
            <div class="mt-12 pt-6 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                
                <!-- Copyright -->
                <p class="text-xs font-bold text-slate-600">
                    © {{ date('Y') }} <strong class="text-slate-900">RoboMath</strong>. Hak Cipta Dilindungi.
                </p>

                <!-- Social Icons -->
                <div class="flex items-center gap-2.5">
                    <!-- Instagram -->
                    <a href="#" aria-label="Instagram RoboMath"
                       class="w-9 h-9 rounded-full bg-slate-100 hover:bg-[#E1306C] border border-slate-200 text-slate-700 hover:text-white flex items-center justify-center transition-all duration-200 shadow-2xs">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                        </svg>
                    </a>
                    <!-- YouTube -->
                    <a href="#" aria-label="YouTube RoboMath"
                       class="w-9 h-9 rounded-full bg-slate-100 hover:bg-[#FF0000] border border-slate-200 text-slate-700 hover:text-white flex items-center justify-center transition-all duration-200 shadow-2xs">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.495 6.205a3.007 3.007 0 00-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 00.527 6.205a31.247 31.247 0 00-.522 5.805 31.247 31.247 0 00.522 5.783 3.007 3.007 0 002.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 002.088-2.088 31.247 31.247 0 00.5-5.783 31.247 31.247 0 00-.5-5.805zM9.609 15.601V8.408l6.264 3.602z"/>
                        </svg>
                    </a>
                    <!-- GitHub -->
                    <a href="#" aria-label="GitHub RoboMath"
                       class="w-9 h-9 rounded-full bg-slate-100 hover:bg-[#24292F] border border-slate-200 text-slate-700 hover:text-white flex items-center justify-center transition-all duration-200 shadow-2xs">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/>
                        </svg>
                    </a>
                    <!-- TikTok -->
                    <a href="#" aria-label="TikTok RoboMath"
                       class="w-9 h-9 rounded-full bg-slate-100 hover:bg-[#000000] border border-slate-200 text-slate-700 hover:text-white flex items-center justify-center transition-all duration-200 shadow-2xs">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                        </svg>
                    </a>
                </div>

            </div>

        </div>
    </footer>

</div>
@endsection
