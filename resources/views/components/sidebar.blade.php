<aside class="w-64 flex-shrink-0 hidden md:block self-stretch" id="app-sidebar">
    <div class="bg-white rounded-3xl p-5 border-2 border-amber-200/80 shadow-sm sticky top-6 flex flex-col justify-between" style="height: calc(100vh - 2rem); max-height: 100vh;">
        
        <!-- Navigation Section -->
        <div class="flex-1 flex flex-col min-h-0 space-y-4">
            @auth
                @if(auth()->user()->isSiswa())
                    <div class="text-xs font-black text-amber-900/60 uppercase tracking-wider px-3 flex items-center justify-between flex-shrink-0">
                        <span>Menu Utama Siswa</span>
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    </div>
                    <nav class="space-y-1.5 overflow-y-auto pr-1 flex-1">
                        <a href="{{ route('siswa.dashboard') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('siswa.dashboard') ? 'bg-[#FF9500] text-white shadow-md' : 'text-slate-700 hover:bg-amber-50' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Dasbor</span>
                        </a>

                        <a href="{{ route('siswa.topics') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('siswa.topics*') || request()->routeIs('siswa.lessons*') ? 'bg-[#10B981] text-white shadow-md' : 'text-slate-700 hover:bg-emerald-50' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <span>Modul Belajar</span>
                        </a>

                        <a href="{{ route('siswa.quiz') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('siswa.quiz*') ? 'bg-[#3B82F6] text-white shadow-md' : 'text-slate-700 hover:bg-blue-50' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            <span>Latihan Soal AI</span>
                        </a>

                        <a href="{{ route('siswa.achievements') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('siswa.achievements') ? 'bg-[#EC4899] text-white shadow-md' : 'text-slate-700 hover:bg-rose-50' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                            <span>Lencana & Skor</span>
                        </a>

                        <a href="{{ route('siswa.leaderboard') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('siswa.leaderboard') ? 'bg-[#F59E0B] text-white shadow-md' : 'text-slate-700 hover:bg-amber-50' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Papan Peringkat</span>
                        </a>

                        <a href="{{ route('siswa.kelas.join') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('siswa.kelas*') ? 'bg-[#00A88F] text-white shadow-md' : 'text-slate-700 hover:bg-cyan-50' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span>Gabung Kelas</span>
                        </a>
                    </nav>

                @elseif(auth()->user()->isGuru())
                    <div class="text-xs font-black text-amber-900/60 uppercase tracking-wider px-3 flex items-center justify-between flex-shrink-0">
                        <span>Menu Utama Guru</span>
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                    </div>
                    <nav class="space-y-1.5 overflow-y-auto pr-1 flex-1">
                        <a href="{{ route('guru.dashboard') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('guru.dashboard') ? 'bg-[#4F46E5] text-white shadow-md' : 'text-slate-700 hover:bg-indigo-50' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <span>Monitoring Siswa</span>
                        </a>

                        <a href="{{ route('guru.classes.index') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('guru.classes*') ? 'bg-[#059669] text-white shadow-md' : 'text-slate-700 hover:bg-emerald-50' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span>Kelola Kelas</span>
                        </a>

                        <a href="{{ route('guru.leaderboard') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('guru.leaderboard') ? 'bg-[#F59E0B] text-white shadow-md' : 'text-slate-700 hover:bg-amber-50' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                            <span>Peringkat Siswa</span>
                        </a>

                        <a href="{{ route('admin.document-summarizer.index') }}"
                           style="{{ request()->routeIs('admin.document-summarizer*') ? 'background-color: #7C3AED; color: #FFFFFF;' : '' }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('admin.document-summarizer*') ? 'bg-purple-600 text-white shadow-md' : 'text-slate-700 hover:bg-purple-50' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Ringkas Dokumen AI</span>
                            <span class="ml-auto text-[10px] font-black px-1.5 py-0.5 rounded-md {{ request()->routeIs('admin.document-summarizer*') ? 'bg-white/20 text-white' : 'bg-purple-100 text-purple-700' }}">Baru</span>
                        </a>
                    </nav>

                @elseif(auth()->user()->isAdmin())
                    <div class="text-xs font-black text-amber-900/60 uppercase tracking-wider px-3 flex items-center justify-between flex-shrink-0">
                        <span>Menu Utama Admin</span>
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    </div>
                    <nav class="space-y-1.5 overflow-y-auto pr-1 flex-1">
                        <a href="{{ route('admin.dashboard') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-[#FF9500] text-white shadow-md' : 'text-slate-700 hover:bg-amber-50' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Dasbor</span>
                        </a>

                        <a href="{{ route('admin.document-summarizer.index') }}"
                           style="{{ request()->routeIs('admin.document-summarizer*') ? 'background-color: #7C3AED; color: #FFFFFF;' : '' }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('admin.document-summarizer*') ? 'bg-purple-600 text-white shadow-md' : 'text-slate-700 hover:bg-purple-50' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Ringkas Dokumen AI</span>
                            <span class="ml-auto text-[10px] font-black px-1.5 py-0.5 rounded-md {{ request()->routeIs('admin.document-summarizer*') ? 'bg-white/20 text-white' : 'bg-purple-100 text-purple-700' }}">Baru</span>
                        </a>

                        <a href="{{ route('admin.quizzes.index') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('admin.quizzes*') ? 'bg-[#3B82F6] text-white shadow-md' : 'text-slate-700 hover:bg-blue-50' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            <span>Manajemen Soal</span>
                        </a>

                        <a href="{{ route('admin.lessons.index') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('admin.lessons*') ? 'bg-[#10B981] text-white shadow-md' : 'text-slate-700 hover:bg-emerald-50' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <span>Manajemen Materi</span>
                        </a>

                        <a href="{{ route('admin.reports.index') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('admin.reports*') ? 'bg-[#7C3AED] text-white shadow-md' : 'text-slate-700 hover:bg-violet-50' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <span>Laporan Performa</span>
                        </a>

                        <a href="{{ route('admin.ai-recommendations.index') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('admin.ai-recommendations*') ? 'bg-[#FF3B30] text-white shadow-md' : 'text-slate-700 hover:bg-rose-50' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            <span>Rekomendasi AI</span>
                            @php $pendingCount = \App\Models\AiRecommendation::where('status', 'pending')->count(); @endphp
                            @if($pendingCount > 0)
                                <span class="ml-auto bg-rose-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
                            @endif
                        </a>

                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('admin.users*') ? 'bg-[#4F46E5] text-white shadow-md' : 'text-slate-700 hover:bg-indigo-50' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Manajemen Pengguna</span>
                        </a>
                    </nav>

                @endif
            @endauth
        </div>

        <!-- Tombol Keluar di Bagian Paling Bawah dengan After Efek Merah -->
        @auth
            <div class="pt-4 mt-auto border-t border-slate-100 flex-shrink-0">
                <form action="{{ route('logout') }}" method="POST" class="w-full m-0">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2.5 px-4 py-3 rounded-2xl font-bold text-sm text-slate-700 bg-slate-50 border border-slate-200/80 transition-all duration-300 relative overflow-hidden group hover:text-white hover:border-[#FF3B30] hover:shadow-lg hover:shadow-red-500/20 active:scale-95 cursor-pointer">
                        {{-- After-effect: Background sliding red wave from bottom on hover --}}
                        <span class="absolute inset-0 bg-[#FF3B30] translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out z-0"></span>
                        
                        {{-- Icon & Text --}}
                        <svg class="w-5 h-5 flex-shrink-0 text-slate-500 group-hover:text-white transition-colors duration-300 relative z-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span class="relative z-10 font-black group-hover:text-white transition-colors duration-300">Keluar</span>
                    </button>
                </form>
            </div>
        @endauth

    </div>
</aside>
