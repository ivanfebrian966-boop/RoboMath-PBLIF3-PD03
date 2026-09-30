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
    <header class="sticky top-4 z-50 max-w-4xl mx-auto px-4">
        <nav class="bg-white/85 backdrop-blur-md rounded-full px-5 py-2.5 flex items-center justify-between border border-amber-200/60 shadow-[0_4px_25px_rgba(0,0,0,0.06)] transition-all">
            
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <img src="{{ asset('images/logo.png') }}" alt="RoboMath Logo" class="h-7 sm:h-8 w-auto group-hover:scale-105 transition-transform duration-200">
            </a>

            <!-- Nav Links -->
            <div class="hidden md:flex items-center gap-2 text-xs font-bold text-slate-700">
                <a href="#hero" class="px-3.5 py-1.5 rounded-full border border-red-400 bg-red-50/80 text-red-500 font-extrabold shadow-xs transition">
                    Beranda
                </a>
                <a href="#fitur" class="px-3.5 py-1.5 rounded-full text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition">
                    Fitur AI
                </a>
                <a href="#modul" class="px-3.5 py-1.5 rounded-full text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition">
                    Modul SD
                </a>
                <a href="#peran" class="px-3.5 py-1.5 rounded-full text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition">
                    Untuk Siapa?
                </a>
            </div>

            <!-- Auth Buttons -->
            <div class="flex items-center gap-2 font-bold text-xs">
                @auth
                    <a href="{{ auth()->user()->isGuru() ? route('guru.dashboard') : (auth()->user()->isOrangtua() ? route('orangtua.dashboard') : (auth()->user()->isAdmin() ? route('admin.dashboard') : route('siswa.dashboard'))) }}"
                       class="bg-slate-950 hover:bg-slate-800 text-white px-4 py-2 rounded-full shadow-sm flex items-center gap-1.5 transition">
                        <span>Dashboard</span>
                        <span>&rarr;</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="border border-red-300 text-red-600 hover:bg-red-50 px-4 py-1.5 rounded-full font-bold transition">
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
                <span class="bg-gradient-to-r from-orange-500 via-amber-500 to-teal-500 bg-clip-text text-transparent">
                    Interaktif &amp; Seru
                </span>
            </h1>
            <!-- Subtitle -->
            <p class="text-xs sm:text-sm text-slate-600 font-semibold max-w-xl mx-auto leading-relaxed">
                Dari penjumlahan dasar hingga pemecahan logika tingkat lanjut, RoboMath membantu siswa SD belajar mandiri dengan <strong>Asisten AI RoboBot</strong> dan gamifikasi poin.
            </p>
        </div>
    </section>


    <!-- ============================================================ -->
    <!-- 3. FITUR UTAMA 4-GRID CARDS (Image 2 Column 2 Top) -->
    <!-- ============================================================ -->
    <section id="fitur" class="max-w-5xl mx-auto px-6 py-14 relative z-10">
        
        <div class="text-center max-w-xl mx-auto mb-10 space-y-2">
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Fitur Utama</h2>
        </div>

        <!-- 4 Colorful Feature Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Card 1: Penjumlahan SD (Golden Yellow) -->
            <div class="bg-gradient-to-br from-[#FBBF24] to-[#F59E0B] text-slate-950 p-7 rounded-[32px] shadow-lg flex items-center justify-between gap-4 overflow-hidden relative group hover:-translate-y-1 transition duration-300">
                <div class="space-y-2 max-w-[65%] z-10">
                    <h3 class="text-xl font-black">Penjumlahan SD</h3>
                    <p class="text-xs font-semibold text-slate-900/80 leading-relaxed">
                        Penjumlahan dasar hingga pemecahan logika dari matematika sekelas.
                    </p>
                </div>
                <!-- 3D Graphic / Calculator Visual -->
                <div class="w-24 h-24 bg-white/20 backdrop-blur-md rounded-2xl border border-white/40 flex flex-col items-center justify-center p-2 shadow-inner transform group-hover:rotate-6 transition">
                    <div class="grid grid-cols-2 gap-1.5 w-full text-center font-black text-slate-900 text-sm">
                        <div class="bg-white/70 rounded-md p-1">+</div>
                        <div class="bg-white/70 rounded-md p-1">-</div>
                        <div class="bg-white/70 rounded-md p-1">×</div>
                        <div class="bg-amber-300 rounded-md p-1">=</div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Perkalian Cepat (Bright Blue) -->
            <div class="bg-gradient-to-br from-[#3B82F6] to-[#2563EB] text-white p-7 rounded-[32px] shadow-lg flex items-center justify-between gap-4 overflow-hidden relative group hover:-translate-y-1 transition duration-300">
                <div class="space-y-2 max-w-[65%] z-10">
                    <h3 class="text-xl font-black">Perkalian Cepat</h3>
                    <p class="text-xs font-semibold text-blue-100 leading-relaxed">
                        Membaca soal perkalian interaktif pintar untuk mencetak selamat.
                    </p>
                </div>
                <!-- Math bubble visual -->
                <div class="w-24 h-24 flex flex-col items-center justify-center relative">
                    <div class="bg-white text-blue-700 font-black text-xs px-3 py-1.5 rounded-2xl shadow-md border-2 border-blue-200">
                        15 + 3 = ?
                    </div>
                    <div class="text-3xl mt-1">🧒✏️</div>
                </div>
            </div>

            <!-- Card 3: Asisten AI 24/7 (Emerald Green) -->
            <div class="bg-gradient-to-br from-[#10B981] to-[#059669] text-white p-7 rounded-[32px] shadow-lg flex items-center justify-between gap-4 overflow-hidden relative group hover:-translate-y-1 transition duration-300">
                <div class="space-y-2 max-w-[65%] z-10">
                    <h3 class="text-xl font-black">Asisten AI 24/7</h3>
                    <p class="text-xs font-semibold text-emerald-100 leading-relaxed">
                        Asisten AI 24/7 cerdas menjawab pertanyaan siswa secara real-time.
                    </p>
                </div>
                <div class="w-24 h-24 flex items-center justify-center">
                    <img src="{{ asset('images/maskot.png') }}" alt="Bot Mascot" class="w-20 h-20 object-contain drop-shadow-md group-hover:scale-110 transition">
                </div>
            </div>

            <!-- Card 4: Skor & Lencana (Coral Red) -->
            <div class="bg-gradient-to-br from-[#EF4444] to-[#DC2626] text-white p-7 rounded-[32px] shadow-lg flex items-center justify-between gap-4 overflow-hidden relative group hover:-translate-y-1 transition duration-300">
                <div class="space-y-2 max-w-[65%] z-10">
                    <h3 class="text-xl font-black">Skor &amp; Lencana</h3>
                    <p class="text-xs font-semibold text-rose-100 leading-relaxed">
                        Memenangkan penghargaan menyelesaikan modul AI Lencana motivasi.
                    </p>
                </div>
                <div class="w-24 h-24 flex items-center justify-center text-5xl drop-shadow-md group-hover:scale-110 transition">
                    🏆
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
    <section class="max-w-4xl mx-auto px-6 py-10 relative z-10 text-center">
        
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
    <!-- 8. FOOTER (Image 2 Column 3 Bottom) -->
    <!-- ============================================================ -->
    <footer class="border-t border-slate-200/80 bg-white/60 backdrop-blur-md py-8 px-6 mt-12 relative z-10">
    </footer>

</div>
@endsection
