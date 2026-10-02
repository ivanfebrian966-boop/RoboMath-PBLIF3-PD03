<header class="bg-[#FAF6EF]/90 backdrop-blur-md border-b-2 border-amber-200/70 sticky top-0 z-40 shadow-sm" id="app-navbar">
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
                    <div class="hidden sm:flex items-center gap-2.5 bg-white border-2 border-amber-300 px-4 py-1.5 rounded-full font-bold text-amber-900 shadow-sm">
                        <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" style="display:none;"/>
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span class="text-sm font-extrabold text-slate-800">{{ auth()->user()->total_score }} Poin</span>
                        <span class="bg-amber-400 text-xs px-2.5 py-0.5 rounded-full text-amber-950 font-black">Lvl {{ auth()->user()->level }}</span>
                    </div>
                @endif

                <!-- User Profile Dropdown -->
                <div class="flex items-center gap-3 bg-white px-3 py-1.5 rounded-2xl border-2 border-amber-200/80 shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-[#FF9500] flex items-center justify-center text-white font-extrabold text-lg shadow-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="hidden md:block text-left">
                        <div class="font-extrabold text-slate-800 text-sm leading-none">{{ auth()->user()->name }}</div>
                        <div class="text-xs font-bold text-amber-600 uppercase mt-0.5">{{ auth()->user()->role }}</div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="ml-2">
                        @csrf
                        <button type="submit" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all" title="Keluar">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="font-bold text-slate-700 hover:text-indigo-600 px-4 py-2">Masuk</a>
                <a href="{{ route('register') }}" class="font-extrabold text-white bg-[#FF3B30] hover:bg-[#e02d23] px-5 py-2.5 rounded-2xl shadow-md hover:shadow-lg transition-all">Daftar Gratis</a>
            @endauth
        </div>

    </div>
</header>
