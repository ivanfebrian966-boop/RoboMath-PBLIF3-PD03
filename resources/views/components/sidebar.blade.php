<aside class="w-64 flex-shrink-0 hidden md:block">
    <div class="bg-white rounded-3xl p-5 border-2 border-slate-200 shadow-sm sticky top-28 space-y-6">
        
        @auth
            @if(auth()->user()->isSiswa())
                <div class="text-xs font-black text-slate-400 uppercase tracking-wider px-3">Menu Utama Siswa</div>
                <nav class="space-y-2">
                    <a href="{{ route('siswa.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('siswa.dashboard') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                        <span class="text-xl">🏠</span> Dashboard
                    </a>
                    <a href="{{ route('siswa.topics') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('siswa.topics*') || request()->routeIs('siswa.lessons*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                        <span class="text-xl">📚</span> Modul Belajar
                    </a>
                    <a href="{{ route('siswa.quiz') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('siswa.quiz*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                        <span class="text-xl">🎯</span> Latihan Soal AI
                    </a>
                    <a href="{{ route('siswa.achievements') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('siswa.achievements') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                        <span class="text-xl">🏆</span> Lencana & Skor
                    </a>
                </nav>
            @elseif(auth()->user()->isGuru())
                <div class="text-xs font-black text-slate-400 uppercase tracking-wider px-3">Menu Utama Guru</div>
                <nav class="space-y-2">
                    <a href="{{ route('guru.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('guru.dashboard') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                        <span class="text-xl">📊</span> Monitoring Kelas
                    </a>
                </nav>
            @elseif(auth()->user()->isOrangtua())
                <div class="text-xs font-black text-slate-400 uppercase tracking-wider px-3">Menu Orang Tua</div>
                <nav class="space-y-2">
                    <a href="{{ route('orangtua.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('orangtua.dashboard') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                        <span class="text-xl">👨‍👩‍👧‍👦</span> Progress Anak
                    </a>
                </nav>
            @endif
        @endauth

        <!-- Banner Bantuan AI -->
        <div class="p-4 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white space-y-2 shadow-md">
            <div class="flex items-center gap-2 font-black text-sm">
                <span>🤖</span> Asisten AI AlgoBot
            </div>
            <p class="text-xs text-indigo-100 leading-relaxed">Punya pertanyaan tentang algoritma? Tanya AlgoBot kapan saja di pojok kanan bawah!</p>
        </div>

    </div>
</aside>
