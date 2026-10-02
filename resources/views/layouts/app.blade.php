<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'RoboMath - Aplikasi Web Berbasis AI untuk Pengembangan Pembelajaran Matematika Anak Sekolah Dasar' }}</title>
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,600&display=swap" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/sf-pro-display" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-[#FAF6EF] font-sans text-slate-800 antialiased min-h-screen flex flex-col selection:bg-amber-200 selection:text-amber-900">

    <!-- Top Navbar -->
    @include('components.navbar')

    <div class="flex-1 flex max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 gap-6">
        <!-- Sidebar Navigation -->
        @include('components.sidebar')

        <!-- Main Content Area -->
        <main class="flex-1 min-w-0" id="main-content">
            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border-2 border-emerald-300 text-emerald-800 font-bold flex items-center gap-3 shadow-sm">
                    <svg class="w-5 h-5 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- AI Chatbot Assistant Floating Widget (for Siswa) -->
    @if(auth()->check() && auth()->user()->isSiswa())
        @include('components.chatbot-widget')
    @endif

    <footer class="bg-white/80 backdrop-blur-md border-t border-amber-200/70 py-5 text-center text-sm text-slate-500 mt-auto">
        <div class="flex flex-col items-center gap-1.5">
            <img src="{{ asset('images/logo.png') }}" alt="RoboMath" class="h-7 w-auto hover:scale-105 transition-transform duration-200">
            <div class="flex items-center gap-1 text-[10px] font-black text-amber-900/60 uppercase tracking-widest">
                <span class="text-red-500">R</span><span class="text-orange-500">o</span><span class="text-amber-500">b</span><span class="text-blue-500">o</span><span class="text-emerald-500">M</span><span class="text-teal-500">a</span><span class="text-cyan-500">t</span><span class="text-indigo-500">h</span>
            </div>
            <p class="text-xs">© {{ date('Y') }} <strong>RoboMath</strong> — Aplikasi Web Berbasis AI untuk Pengembangan Pembelajaran Matematika Anak Sekolah Dasar</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
