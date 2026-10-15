<header class="sticky top-0 z-40 pt-3 pb-2 px-4 sm:px-6 lg:px-8 bg-[#FAF6EF]/80 backdrop-blur-md" id="app-navbar">
    <div class="max-w-7xl mx-auto h-16 bg-white border-2 border-amber-200/80 rounded-full px-5 sm:px-6 flex items-center justify-between shadow-sm">
        
        <!-- Sisi Kiri: Logo + Divider + Badge Panel -->
        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}" class="flex items-center group">
                <img src="{{ asset('images/logo.png') }}" alt="RoboMath Logo"
                     class="h-7 sm:h-8 w-auto drop-shadow-sm group-hover:scale-105 transition-transform duration-200">
            </a>

            @auth
                {{-- Divider Garis Vertikal --}}
                <div class="hidden sm:block h-6 w-px bg-slate-200 mx-1"></div>

                {{-- Pill Badge Panel Peran --}}
                @if(auth()->user()->isAdmin())
                    <span class="hidden sm:inline-flex items-center px-3.5 py-1 rounded-full text-xs font-black bg-blue-50 text-blue-700 border border-blue-200/60 shadow-2xs">
                        Panel Admin
                    </span>
                @elseif(auth()->user()->isGuru())
                    <span class="hidden sm:inline-flex items-center px-3.5 py-1 rounded-full text-xs font-black bg-emerald-50 text-emerald-700 border border-emerald-200/60 shadow-2xs">
                        Panel Guru
                    </span>
                @elseif(auth()->user()->isSiswa())
                    <span class="hidden sm:inline-flex items-center px-3.5 py-1 rounded-full text-xs font-black bg-amber-50 text-amber-800 border border-amber-200/60 shadow-2xs">
                        Panel Siswa
                    </span>
                @endif
            @endauth
        </div>

        <!-- Sisi Kanan: Poin (Siswa), Profile Card & Tombol Logout Bulat -->
        <div class="flex items-center gap-3">
            @auth
                @if(auth()->user()->isSiswa())
                    <!-- Score & Level Badge for Siswa -->
                    <div class="hidden md:flex items-center gap-2 bg-amber-50/80 border border-amber-200/80 px-3.5 py-1.5 rounded-full font-bold text-amber-900 shadow-2xs text-xs">
                        <svg class="w-3.5 h-3.5 text-amber-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span class="font-extrabold text-slate-800">{{ auth()->user()->total_score }} Poin</span>
                        <span class="bg-amber-400 text-[10px] px-2 py-0.5 rounded-full text-amber-950 font-black">Lvl {{ auth()->user()->level }}</span>
                    </div>
                @endif

                <!-- User Profile Link Sesuai Gambar -->
                <a href="{{ route('profil.show') }}" 
                   class="flex items-center gap-2.5 px-2 py-1 rounded-full hover:bg-slate-50 transition group"
                   title="Lihat Profil">
                    {{-- Avatar Lingkaran Oranye dengan Ring --}}
                    <div class="relative flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-[#FF9500] ring-2 ring-amber-300 ring-offset-2 flex items-center justify-center text-slate-950 font-black text-base shadow-xs group-hover:scale-105 transition-transform">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </div>
                    
                    {{-- Info Nama & Badge Kapsul Peran --}}
                    <div class="hidden sm:block text-left">
                        <div class="text-sm font-black text-slate-900 group-hover:text-amber-600 transition-colors truncate max-w-[150px] leading-tight">
                            {{ auth()->user()->name }}
                        </div>
                        <span class="inline-block mt-0.5 px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ auth()->user()->isAdmin() ? 'bg-amber-100 text-amber-900' : (auth()->user()->isGuru() ? 'bg-emerald-100 text-emerald-900' : 'bg-blue-100 text-blue-900') }}">
                            {{ auth()->user()->role }}
                        </span>
                    </div>
                </a>

                {{-- Tombol Logout Bulat (Sesuai Gambar) --}}
                <form action="{{ route('logout') }}" method="POST" class="m-0 flex items-center">
                    @csrf
                    <button type="submit" 
                            class="w-10 h-10 rounded-full bg-[#FFF0F0] border border-rose-200/80 flex items-center justify-center text-rose-600 hover:bg-rose-100 hover:border-rose-300 hover:text-rose-700 transition-all active:scale-95 cursor-pointer shadow-2xs"
                            title="Keluar">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="font-extrabold text-white bg-gradient-to-r from-[#FF5722] to-[#FFA000] hover:from-[#E64A19] hover:to-[#FF8F00] px-6 py-2 rounded-2xl shadow-md hover:shadow-orange-500/25 transition-all">Masuk</a>
            @endauth
        </div>

    </div>
</header>
