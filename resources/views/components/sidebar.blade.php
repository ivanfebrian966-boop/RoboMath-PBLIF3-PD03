<aside class="w-64 flex-shrink-0 hidden md:block">
    <div class="bg-white rounded-3xl p-5 border-2 border-amber-200/80 shadow-sm sticky top-28 space-y-6">
        
        @auth
            @if(auth()->user()->isSiswa())
                <div class="text-xs font-black text-amber-900/60 uppercase tracking-wider px-3 flex items-center justify-between">
                    <span>Menu Utama Siswa</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                </div>
                <nav class="space-y-2">
                    <a href="{{ route('siswa.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('siswa.dashboard') ? 'bg-gradient-to-r from-orange-500 to-amber-500 text-white shadow-md' : 'text-slate-700 hover:bg-amber-50' }}">
                        <span class="text-xl">🏠</span> Dashboard
                    </a>
                    <a href="{{ route('siswa.topics') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('siswa.topics*') || request()->routeIs('siswa.lessons*') ? 'bg-gradient-to-r from-emerald-500 to-teal-600 text-white shadow-md' : 'text-slate-700 hover:bg-emerald-50' }}">
                        <span class="text-xl">📚</span> Modul Belajar
                    </a>
                    <a href="{{ route('siswa.quiz') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('siswa.quiz*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md' : 'text-slate-700 hover:bg-blue-50' }}">
                        <span class="text-xl">🎯</span> Latihan Soal AI
                    </a>
                    <a href="{{ route('siswa.achievements') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('siswa.achievements') ? 'bg-gradient-to-r from-rose-500 to-pink-600 text-white shadow-md' : 'text-slate-700 hover:bg-rose-50' }}">
                        <span class="text-xl">🏆</span> Lencana & Skor
                    </a>
                    <a href="{{ route('siswa.leaderboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('siswa.leaderboard') ? 'bg-gradient-to-r from-amber-500 to-yellow-500 text-white shadow-md' : 'text-slate-700 hover:bg-amber-50' }}">
                        <span class="text-xl">🏅</span> Papan Peringkat
                    </a>
                    <a href="{{ route('siswa.kelas.join') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('siswa.kelas*') ? 'bg-gradient-to-r from-cyan-500 to-blue-600 text-white shadow-md' : 'text-slate-700 hover:bg-cyan-50' }}">
                        <span class="text-xl">🏫</span> Gabung Kelas
                    </a>
                </nav>
            @elseif(auth()->user()->isGuru())
                <div class="text-xs font-black text-amber-900/60 uppercase tracking-wider px-3">Menu Utama Guru</div>
                <nav class="space-y-2">
                    <a href="{{ route('guru.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('guru.dashboard') ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-700 hover:bg-indigo-50' }}">
                        <span class="text-xl">📊</span> Monitoring Siswa
                    </a>
                    <a href="{{ route('guru.classes.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('guru.classes*') ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-md' : 'text-slate-700 hover:bg-emerald-50' }}">
                        <span class="text-xl">🏫</span> Kelola Kelas
                    </a>
                    <a href="{{ route('guru.leaderboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('guru.leaderboard') ? 'bg-gradient-to-r from-amber-500 to-yellow-500 text-white shadow-md' : 'text-slate-700 hover:bg-amber-50' }}">
                        <span class="text-xl">🏅</span> Peringkat Siswa
                    </a>
                </nav>
            @elseif(auth()->user()->isAdmin())
                <div class="text-xs font-black text-amber-900/60 uppercase tracking-wider px-3">Menu Admin</div>
                <nav class="space-y-2">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all bg-gradient-to-r from-indigo-700 to-violet-700 text-white shadow-md">
                        <span class="text-xl">⚙️</span> Panel Admin
                    </a>
                </nav>
            @elseif(auth()->user()->isOrangtua())
                <div class="text-xs font-black text-amber-900/60 uppercase tracking-wider px-3">Menu Orang Tua</div>
                <nav class="space-y-2">
                    <a href="{{ route('orangtua.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('orangtua.dashboard') ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-700 hover:bg-amber-50' }}">
                        <span class="text-xl">👨‍👩‍👧‍👦</span> Progress Anak
                    </a>
                </nav>
            @endif
        @endauth

        <!-- Banner Bantuan AI -->
        <div class="rounded-2xl bg-gradient-to-br from-indigo-600 via-purple-600 to-rose-500 text-white shadow-lg overflow-hidden relative border-2 border-amber-200">
            {{-- Mascot image at top --}}
            <div class="flex justify-center pt-4 pb-1">
                <img src="{{ asset('images/maskot.png') }}"
                     alt="RoboBot Mascot"
                     class="w-20 h-20 object-contain drop-shadow-xl hover:scale-105 transition-transform">
            </div>
            <div class="px-4 pb-4 text-center space-y-2">
                <img src="{{ asset('images/logo.png') }}" alt="RoboMath" class="h-6 w-auto mx-auto bg-white/90 p-1 rounded-lg">
                <p class="text-xs text-white/90 font-semibold leading-relaxed">Ada pertanyaan matematika? Tanya <strong>RoboBot AI</strong> di kanan bawah!</p>
            </div>
        </div>

    </div>
</aside>
