<header class="bg-white border-b-4 border-slate-100 sticky top-0 z-40 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
        
        <!-- Logo matching image design -->
        <a href="{{ route('home') }}" class="flex items-center gap-2 group">
            <span class="text-3xl sm:text-4xl font-extrabold tracking-tight select-none">
                <span class="text-[#FF4757]">A</span><span class="text-[#70A1FF]">l</span><span class="text-[#FFA502]">g</span><span class="text-[#ECCC68]">o</span><span class="text-[#FF6B81]">K</span><span class="text-[#84CC16]">i</span><span class="text-[#2ED573]">d</span><span class="text-[#1E90FF]">s</span>
            </span>
            <span class="bg-amber-400 text-amber-950 font-extrabold text-xs px-2.5 py-1 rounded-full uppercase tracking-wider transform -rotate-6 shadow-sm">
                AI Logika SD
            </span>
        </a>

        <!-- User Profile / Auth Action -->
        <div class="flex items-center gap-4">
            @auth
                @if(auth()->user()->isSiswa())
                    <!-- Score & Level Badge for Siswa -->
                    <div class="hidden sm:flex items-center gap-3 bg-amber-50 border-2 border-amber-300 px-4 py-1.5 rounded-full font-bold text-amber-900 shadow-sm">
                        <span class="text-xl">🏆</span>
                        <span class="text-sm font-extrabold">{{ auth()->user()->total_score }} Poin</span>
                        <span class="bg-amber-400 text-xs px-2 py-0.5 rounded-full text-amber-950">Lvl {{ auth()->user()->level }}</span>
                    </div>
                @endif

                <!-- User Profile Dropdown -->
                <div class="flex items-center gap-3 bg-slate-100 px-3 py-1.5 rounded-2xl border border-slate-200">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white font-extrabold text-lg shadow-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="hidden md:block text-left">
                        <div class="font-extrabold text-slate-800 text-sm leading-none">{{ auth()->user()->name }}</div>
                        <div class="text-xs font-semibold text-slate-500 uppercase mt-0.5">{{ auth()->user()->role }}</div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="ml-2">
                        @csrf
                        <button type="submit" class="p-2 text-slate-400 hover:text-red-500 transition-colors" title="Keluar">
                            🚪
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="font-bold text-slate-600 hover:text-indigo-600 px-4 py-2">Masuk</a>
                <a href="{{ route('register') }}" class="font-extrabold text-white bg-indigo-600 hover:bg-indigo-700 px-5 py-2.5 rounded-2xl shadow-md hover:shadow-lg transition-all">Daftar Gratis</a>
            @endauth
        </div>

    </div>
</header>
