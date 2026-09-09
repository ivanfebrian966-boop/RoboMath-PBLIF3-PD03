@extends('layouts.guest')

@section('content')
<!-- Header Navbar -->
<nav class="max-w-7xl mx-auto px-6 py-6 w-full flex items-center justify-between">
    <div class="flex items-center gap-2">
        <span class="text-4xl font-black tracking-tight">
            <span class="text-[#FF4757]">A</span><span class="text-[#70A1FF]">l</span><span class="text-[#FFA502]">g</span><span class="text-[#ECCC68]">o</span><span class="text-[#FF6B81]">K</span><span class="text-[#84CC16]">i</span><span class="text-[#2ED573]">d</span><span class="text-[#1E90FF]">s</span>
        </span>
    </div>
    <div class="flex items-center gap-4 font-extrabold">
        @auth
            <a href="{{ auth()->user()->isGuru() ? route('guru.dashboard') : (auth()->user()->isOrangtua() ? route('orangtua.dashboard') : route('siswa.dashboard')) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-2xl shadow-lg transition-all">
                Buka Dashboard 🚀
            </a>
        @else
            <a href="{{ route('login') }}" class="text-slate-700 hover:text-indigo-600 px-4 py-2">Masuk</a>
            <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-2xl shadow-lg transition-all">
                Mulai Gratis 🌟
            </a>
        @endauth
    </div>
</nav>

<!-- Hero Section -->
<section class="max-w-7xl mx-auto px-6 py-12 md:py-20 flex flex-col md:flex-row items-center justify-between gap-12">
    <div class="flex-1 space-y-6 text-center md:text-left">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-100 border border-amber-300 text-amber-900 font-extrabold text-xs uppercase tracking-wider">
            <span>✨ Platform Belajar Logika & Algoritma SD Berbasis AI</span>
        </div>
        <h1 class="text-4xl md:text-6xl font-black text-slate-900 leading-tight">
            Belajar Berpikir Logis & Algoritmis Jadi <span class="text-indigo-600 underline decoration-wavy decoration-amber-400">Sangat Seru!</span> 🎮
        </h1>
        <p class="text-lg md:text-xl text-slate-600 font-semibold max-w-2xl leading-relaxed">
            AlgoKids menghadirkan cara belajar visual dan interaktif untuk anak Sekolah Dasar. Didukung **Asisten AI**, **Latihan Adaptif**, dan **Gamifikasi Lencana**!
        </p>
        <div class="flex flex-col sm:flex-row items-center gap-4 pt-4 justify-center md:justify-start">
            <a href="{{ route('register') }}" class="w-full sm:w-auto bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-black text-lg px-8 py-4 rounded-3xl shadow-xl transform hover:-translate-y-1 transition-all text-center">
                Mulai Belajar Sekarang 🎉
            </a>
            <a href="#fitur" class="w-full sm:w-auto bg-white border-2 border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-lg px-6 py-4 rounded-3xl text-center">
                Lihat Fitur Utama 👇
            </a>
        </div>
    </div>

    <!-- Hero Illustration Card -->
    <div class="flex-1 w-full max-w-lg">
        <div class="bg-white p-6 rounded-3xl border-4 border-indigo-100 shadow-2xl space-y-6 relative overflow-hidden">
            <div class="flex items-center justify-between border-b pb-4">
                <div class="flex items-center gap-3">
                    <span class="text-3xl">🤖</span>
                    <div>
                        <h4 class="font-extrabold text-slate-800">AlgoBot Asisten Pintar</h4>
                        <p class="text-xs text-emerald-600 font-bold">● Online 24/7 Siap Membantu</p>
                    </div>
                </div>
                <span class="bg-indigo-100 text-indigo-700 font-extrabold text-xs px-3 py-1 rounded-full">Kelas 1-6 SD</span>
            </div>

            <!-- Preview Interaction -->
            <div class="space-y-3 font-medium text-sm">
                <div class="bg-slate-100 p-3 rounded-2xl text-slate-700">
                    "Apa itu Percabangan JIKA-MAKA?"
                </div>
                <div class="bg-indigo-50 border border-indigo-200 p-4 rounded-2xl text-indigo-950 space-y-2">
                    <p class="font-bold">🤖 AlgoBot:</p>
                    <p class="text-xs leading-relaxed">"Percabangan itu seperti: JIKA hujan ☔ -> Bawa Payung! JIKA TIDAK hujan ☀️ -> Pakai Topi!"</p>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3 pt-2">
                <div class="bg-amber-50 p-3 rounded-2xl text-center border border-amber-200">
                    <span class="text-2xl">📋</span>
                    <p class="text-xs font-extrabold text-amber-900 mt-1">Urutan</p>
                </div>
                <div class="bg-emerald-50 p-3 rounded-2xl text-center border border-emerald-200">
                    <span class="text-2xl">🔀</span>
                    <p class="text-xs font-extrabold text-emerald-900 mt-1">Percabangan</p>
                </div>
                <div class="bg-purple-50 p-3 rounded-2xl text-center border border-purple-200">
                    <span class="text-2xl">🔄</span>
                    <p class="text-xs font-extrabold text-purple-900 mt-1">Pengulangan</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Grid Section -->
<section id="fitur" class="max-w-7xl mx-auto px-6 py-16">
    <div class="text-center max-w-3xl mx-auto mb-12 space-y-3">
        <h2 class="text-3xl md:text-5xl font-black text-slate-900">Mengapa Memilih AlgoKids?</h2>
        <p class="text-slate-600 font-semibold text-base">Solusi edukatif lengkap yang menghubungkan Siswa, Guru, dan Orang Tua!</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Feature 1 -->
        <div class="bg-white p-8 rounded-3xl border-2 border-slate-200 shadow-sm hover:shadow-xl transition-all space-y-4">
            <div class="w-16 h-16 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-3xl font-bold">
                🎮
            </div>
            <h3 class="text-xl font-extrabold text-slate-800">Modul Visual & Gamifikasi</h3>
            <p class="text-slate-600 text-sm leading-relaxed">
                Materi dikemas secara visual dan dilengkapi sistem poin, level (Bronze-Diamond), serta lencana prestasi yang memotivasi anak terus belajar.
            </p>
        </div>

        <!-- Feature 2 -->
        <div class="bg-white p-8 rounded-3xl border-2 border-slate-200 shadow-sm hover:shadow-xl transition-all space-y-4">
            <div class="w-16 h-16 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-3xl font-bold">
                🤖
            </div>
            <h3 class="text-xl font-extrabold text-slate-800">Rekomendasi AI Adaptif</h3>
            <p class="text-slate-600 text-sm leading-relaxed">
                Sistem AI menganalisis tingkat pemahaman siswa dan secara otomatis merekomendasikan soal latihan sesuai topik yang belum dikuasai.
            </p>
        </div>

        <!-- Feature 3 -->
        <div class="bg-white p-8 rounded-3xl border-2 border-slate-200 shadow-sm hover:shadow-xl transition-all space-y-4">
            <div class="w-16 h-16 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-3xl font-bold">
                👨‍👩‍👧‍👦
            </div>
            <h3 class="text-xl font-extrabold text-slate-800">Dashboard Guru & Orang Tua</h3>
            <p class="text-slate-600 text-sm leading-relaxed">
                Pantau perkembangan anak secara real-time. Lihat statistik nilai, grafik akurasi, dan topik yang masih sulit dengan mudah.
            </p>
        </div>
    </div>
</section>
@endsection
