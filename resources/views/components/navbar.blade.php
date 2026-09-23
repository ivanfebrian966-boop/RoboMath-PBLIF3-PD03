<header class="bg-[#FAF6EF]/90 backdrop-blur-md border-b-2 border-amber-200/70 sticky top-0 z-40 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        
        <!-- Logo RoboMath image -->
        <a href="{{ route('home') }}" class="flex items-center group">
            <img src="{{ asset('images/logo.png') }}" alt="RoboMath Logo"
                 class="h-7 sm:h-8 w-auto drop-shadow-sm group-hover:scale-105 transition-transform duration-200">
        </a>

        <!-- User Profile / Auth Action -->
        <div class="flex items-center gap-4">
            @auth
                @if(auth()->user()->isSiswa())
                    <!-- Score & Level Badge for Siswa -->
                    <div class="hidden sm:flex items-center gap-3 bg-white border-2 border-amber-300 px-4 py-1.5 rounded-full font-bold text-amber-900 shadow-sm">
                        <span class="text-xl">🏆</span>
                        <span class="text-sm font-extrabold text-slate-800">{{ auth()->user()->total_score }} Poin</span>
                        <span class="bg-amber-400 text-xs px-2.5 py-0.5 rounded-full text-amber-950 font-black">Lvl {{ auth()->user()->level }}</span>
                    </div>
                @endif

                <!-- User Profile Dropdown -->
                <div class="flex items-center gap-3 bg-white px-3 py-1.5 rounded-2xl border-2 border-amber-200/80 shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-orange-500 to-indigo-600 flex items-center justify-center text-white font-extrabold text-lg shadow-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="hidden md:block text-left">
                        <div class="font-extrabold text-slate-800 text-sm leading-none">{{ auth()->user()->name }}</div>
                        <div class="text-xs font-bold text-amber-600 uppercase mt-0.5">{{ auth()->user()->role }}</div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="ml-2">
                        @csrf
                        <button type="submit" class="p-2 text-slate-400 hover:text-red-500 transition-colors" title="Keluar">
                            🚪
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="font-bold text-slate-700 hover:text-indigo-600 px-4 py-2">Masuk</a>
                <a href="{{ route('register') }}" class="font-extrabold text-white bg-gradient-to-r from-orange-500 via-amber-500 to-indigo-600 hover:from-orange-600 hover:to-indigo-700 px-5 py-2.5 rounded-2xl shadow-md hover:shadow-lg transition-all">Daftar Gratis</a>
            @endauth
        </div>

    </div>
</header>
