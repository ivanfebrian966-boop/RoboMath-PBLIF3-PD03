<header class="sticky top-0 z-40 pt-safe pt-2.5 sm:pt-3 pb-2 px-3 sm:px-6 lg:px-8 bg-[#FAF6EF]/85 backdrop-blur-md" id="app-navbar">
    <div class="max-w-7xl mx-auto h-14 sm:h-16 bg-white border border-amber-200/70 rounded-full px-3 sm:px-6 flex items-center justify-between shadow-[0_4px_16px_rgba(234,140,0,0.06)]">
        
        <!-- Sisi Kiri: Tombol Hamburger (Mobile/iOS) + Logo + Divider + Badge Panel -->
        <div class="flex items-center gap-2 sm:gap-3.5">
            @auth
                <!-- Tombol Hamburger Menu (Hanya Terintegrasi pada Ukuran Mobile, Tersembunyi Total di Desktop & Laptop) -->
                <button type="button"
                        @click="mobileSidebarOpen = !mobileSidebarOpen"
                        class="hidden max-md:flex md:!hidden items-center justify-center w-10 h-10 rounded-full bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-200/80 transition-all duration-200 active:scale-90 shadow-2xs focus:outline-none focus:ring-2 focus:ring-[#FF8A00] cursor-pointer"
                        aria-label="Buka Menu Navigasi"
                        title="Buka Menu">
                    <svg class="w-5 h-5 text-amber-900" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            @endauth

            <a href="{{ route('home') }}" class="flex items-center group flex-shrink-0" title="RoboMath">
                <img src="{{ asset('images/logo.png') }}" alt="RoboMath Logo"
                     class="h-6 sm:h-8 w-auto drop-shadow-sm group-hover:scale-105 transition-transform duration-200">
            </a>

            @auth
                {{-- Divider Garis Vertikal Halus (Desktop & Tablet) --}}
                <div class="hidden sm:block h-5 sm:h-6 w-px bg-amber-200/60 mx-0.5 sm:mx-1"></div>

                {{-- Pill Badge Panel Peran Sesuai Mockup --}}
                @if(auth()->user()->isAdmin())
                    <span class="hidden sm:inline-flex items-center px-3 sm:px-3.5 py-1 rounded-full text-xs font-black bg-[#FEF3C7] text-[#92400E] border border-[#FDE68A] shadow-2xs">
                        Panel Admin
                    </span>
                @elseif(auth()->user()->isGuru())
                    <span class="hidden sm:inline-flex items-center px-3 sm:px-3.5 py-1 rounded-full text-xs font-black bg-[#DBEAFE] text-[#1E40AF] border border-[#BFDBFE] shadow-2xs">
                        Panel Guru
                    </span>
                @elseif(auth()->user()->isSiswa())
                    <span class="hidden sm:inline-flex items-center px-3 sm:px-3.5 py-1 rounded-full text-xs font-black bg-[#D1FAE5] text-[#065F46] border border-[#A7F3D0] shadow-2xs">
                        Panel Siswa
                    </span>
                @endif
            @endauth
        </div>

        <!-- Sisi Kanan: Poin & Level (Siswa) + Profile Pill (Tanpa Tombol Logout) -->
        <div class="flex items-center gap-1.5 sm:gap-3.5">
            @auth
                @if(auth()->user()->isSiswa())
                    <!-- Score & Level Badge for Siswa (Desktop/Tablet) -->
                    <div class="hidden sm:flex items-center gap-2 sm:gap-2.5 bg-[#FFF9EC] border border-[#FEEBC8] px-3.5 sm:px-4 py-1.5 rounded-full text-xs sm:text-sm font-black shadow-2xs">
                        <svg class="w-4 h-4 text-[#F59E0B] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        <span class="font-black text-[#001738]">{{ auth()->user()->total_score ?? 0 }} Poin</span>
                        <span class="bg-[#FF8A00] text-[#001738] text-[10px] sm:text-xs px-2.5 py-0.5 rounded-full font-black">Lv. {{ auth()->user()->level ?? 1 }}</span>
                    </div>

                    <!-- Mini Score Badge for Siswa (Mobile) -->
                    <div class="flex sm:hidden items-center gap-1 bg-[#FFF9EC] border border-[#FEEBC8] px-2.5 py-1 rounded-full text-xs font-black shadow-2xs">
                        <svg class="w-3.5 h-3.5 text-[#F59E0B]" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        <span class="font-black text-[#001738] text-[11px]">{{ auth()->user()->total_score ?? 0 }}</span>
                    </div>
                @endif

                <!-- User Profile Pill Sesuai Gambar Mockup -->
                <a href="{{ route('profil.show') }}" 
                   class="flex items-center gap-2 sm:gap-2.5 pl-1 sm:pl-1.5 pr-2.5 sm:pr-4 py-1 rounded-full border border-amber-200/70 bg-white hover:bg-amber-50/40 transition-all duration-200 group shadow-2xs"
                   title="Lihat Profil">
                    {{-- Avatar Bulat Oranye dengan Huruf Awal Berwarna Navy Gelap --}}
                    <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-full bg-[#FF8A00] flex items-center justify-center text-[#001738] font-black text-xs sm:text-base shadow-xs group-hover:scale-105 transition-transform flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    
                    {{-- Info Nama & Label Peran (Desktop / Tablet) --}}
                    <div class="text-left block min-w-0">
                        <div class="text-xs sm:text-sm font-black text-[#001738] group-hover:text-amber-600 transition-colors truncate max-w-[90px] sm:max-w-[170px] leading-tight">
                            {{ auth()->user()->name }}
                        </div>
                        <div class="text-[9px] sm:text-[11px] font-black uppercase tracking-wider text-[#A15822] leading-none mt-0.5">
                            {{ auth()->user()->role }}
                        </div>
                    </div>
                </a>
            @else
                <a href="{{ route('login') }}" class="font-extrabold text-white text-xs sm:text-sm bg-gradient-to-r from-[#FF5722] to-[#FFA000] hover:from-[#E64A19] hover:to-[#FF8F00] px-4 sm:px-6 py-2 rounded-2xl shadow-md hover:shadow-orange-500/25 transition-all">Masuk</a>
            @endauth
        </div>

    </div>
</header>
