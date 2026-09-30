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

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-[#FAF6EF] font-sans text-slate-800 antialiased min-h-screen flex flex-col selection:bg-amber-200 selection:text-amber-900">

    <!-- ====== RoboMath Loading Screen ====== -->
    <div id="robomath-loader" class="fixed inset-0 z-[9999] flex flex-col items-center justify-center gap-6"
         style="background: linear-gradient(135deg, #FAF6EF 0%, #FFF8ED 50%, #F0F4FF 100%);">

        <style>
            /* Follow-the-leader orbit animation — adapted for RoboMath */
            @keyframes robo-orbit {
                0%   { transform: rotate(0deg)   translateY(-28px); }
                60%, 100% { transform: rotate(360deg) translateY(-28px); }
            }

            .robo-leader {
                position: relative;
                width: 56px;
                height: 56px;
            }

            .robo-leader div {
                animation: robo-orbit 1.875s infinite backwards;
                border-radius: 100%;
                height: 10px;
                width: 10px;
                position: absolute;
                top: 50%;
                left: 50%;
                margin: -5px 0 0 -5px;
            }

            .robo-leader div:nth-child(1) { animation-delay: 0.00s; background: #FF5733; box-shadow: 0 0 8px #FF5733aa; }
            .robo-leader div:nth-child(2) { animation-delay: 0.15s; background: #FF9500; box-shadow: 0 0 8px #FF9500aa; }
            .robo-leader div:nth-child(3) { animation-delay: 0.30s; background: #FFCC00; box-shadow: 0 0 8px #FFCC00aa; }
            .robo-leader div:nth-child(4) { animation-delay: 0.45s; background: #3478F6; box-shadow: 0 0 8px #3478F6aa; }
            .robo-leader div:nth-child(5) { animation-delay: 0.60s; background: #6C63FF; box-shadow: 0 0 8px #6C63FFaa; }

            @keyframes loader-mascot-float {
                0%, 100% { transform: translateY(0px) rotate(-2deg); }
                50%       { transform: translateY(-8px) rotate(2deg); }
            }

            #robomath-loader .mascot-img {
                animation: loader-mascot-float 2.4s ease-in-out infinite;
                filter: drop-shadow(0 12px 24px rgba(108, 99, 255, 0.25));
            }

            #robomath-loader.fade-out {
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.5s ease-out;
            }
        </style>

        <!-- Mascot -->
        <img src="{{ asset('images/maskot.png') }}" alt="RoboBot" class="mascot-img w-24 h-24 object-contain">

        <!-- Logo -->
        <img src="{{ asset('images/logo.png') }}" alt="RoboMath" class="h-8 w-auto">

        <!-- Orbit Animation -->
        <div class="robo-leader">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>

        <!-- Loading Text -->
        <p class="text-sm font-black text-slate-500 tracking-widest uppercase animate-pulse">
            Memuat RoboMath…
        </p>
    </div>

    <script>
        window.addEventListener('load', function () {
            var loader = document.getElementById('robomath-loader');
            if (loader) {
                loader.classList.add('fade-out');
                setTimeout(function () { loader.style.display = 'none'; }, 520);
            }
        });
    </script>
    <!-- ====== End Loading Screen ====== -->

    <!-- Top Navbar -->
    @include('components.navbar')

    <div class="flex-1 flex max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 gap-6">
        <!-- Sidebar Navigation -->
        @include('components.sidebar')

        <!-- Main Content Area -->
        <main class="flex-1 min-w-0">
            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border-2 border-emerald-300 text-emerald-800 font-bold flex items-center gap-3 animate-bounce shadow-sm">
                    <span class="text-2xl">🎉</span>
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
            <p class="text-xs">© {{ date('Y') }} <strong>RoboMath</strong> - Aplikasi Web Berbasis AI untuk Pengembangan Pembelajaran Matematika Anak Sekolah Dasar 🤖✨</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
