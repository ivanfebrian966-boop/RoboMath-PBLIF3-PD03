@extends('layouts.app')

@section('title', 'Latihan Soal Logika - RoboMath')

@section('content')
<div x-data="quizIndex()" class="max-w-6xl mx-auto space-y-6 sm:space-y-7 pb-12 font-poppins">

    <!-- 1. HERO HEADER BANNER (Biru Solid Sesuai Mockup) -->
    <div class="relative overflow-hidden rounded-[28px] sm:rounded-[32px] bg-[#2563EB] p-7 sm:p-9 text-white shadow-[0_8px_24px_rgba(37,99,235,0.22)]">
        <div class="relative z-10 space-y-2 max-w-3xl">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold tracking-tight text-white font-poppins">
                Pilih Topik Latihan Soal Logika!
            </h1>
            <p class="text-white/90 font-medium text-xs sm:text-sm leading-relaxed font-poppins">
                Pilih topik yang ingin kamu pelajari, lalu kerjakan semua paket soal di dalamnya untuk mengumpulkan poin dan mengasah logika berpikirmu.
            </p>
        </div>
    </div>

    <!-- 2. CARD STATISTIK / KPI (Putih 4 Kolom Sesuai Mockup) -->
    <div class="bg-white rounded-[24px] sm:rounded-[28px] p-5 sm:p-6 shadow-xs border border-slate-100">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-6 divide-y sm:divide-y-0 sm:divide-x divide-slate-100">
            {{-- Total Soal --}}
            <div class="space-y-1">
                <span class="text-[10px] sm:text-[11px] font-black uppercase text-slate-400 tracking-wider block font-poppins">TOTAL SOAL</span>
                <div class="text-2xl sm:text-3xl font-extrabold text-[#EF4444] leading-tight font-poppins">
                    {{ $totalQuizzes }}
                </div>
                <span class="text-[11px] text-slate-400 font-semibold block font-poppins">Soal</span>
            </div>

            {{-- Soal Selesai --}}
            <div class="space-y-1 pt-3 sm:pt-0 sm:pl-6">
                <span class="text-[10px] sm:text-[11px] font-black uppercase text-slate-400 tracking-wider block font-poppins">SOAL SELESAI</span>
                <div class="text-2xl sm:text-3xl font-extrabold leading-tight flex items-baseline gap-1 font-poppins">
                    <span class="text-[#10B981]">{{ $completedQuizzesCount }}</span>
                    <span class="text-slate-400 font-bold text-base sm:text-lg">/ {{ $totalQuizzes }}</span>
                </div>
                <span class="text-[11px] text-slate-400 font-semibold block font-poppins">Soal</span>
            </div>

            {{-- Poin Diperoleh --}}
            <div class="space-y-1 pt-3 sm:pt-0 sm:pl-6">
                <span class="text-[10px] sm:text-[11px] font-black uppercase text-slate-400 tracking-wider block font-poppins">POIN DIPEROLEH</span>
                <div class="text-2xl sm:text-3xl font-extrabold text-[#F59E0B] leading-tight font-poppins">
                    +{{ $totalEarnedPoints }}
                </div>
                <span class="text-[11px] text-slate-400 font-semibold block font-poppins">Poin</span>
            </div>

            {{-- Kemajuan --}}
            <div class="space-y-1 pt-3 sm:pt-0 sm:pl-6">
                <span class="text-[10px] sm:text-[11px] font-black uppercase text-slate-400 tracking-wider block font-poppins">KEMAJUAN</span>
                <div class="text-2xl sm:text-3xl font-extrabold text-[#2563EB] leading-tight font-poppins">
                    {{ $overallProgressPercent }}%
                </div>
                <div class="w-full bg-[#E5E7EB] h-2 rounded-full overflow-hidden mt-2.5">
                    <div class="bg-[#2563EB] h-full rounded-full transition-all duration-500" style="width: {{ $overallProgressPercent }}%;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. PENCARIAN & STATUS FILTER -->
    <div class="flex flex-wrap items-center justify-between gap-3">
        {{-- Search Input (Pill Bulat Putih) --}}
        <div class="flex min-w-0 flex-1 flex-wrap items-center gap-3">
            <div class="relative w-full min-w-[220px] flex-1 max-w-md">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </span>
                <input type="text"
                       x-model="searchQuery"
                       placeholder="Cari topik latihan..."
                       class="w-full pl-9 pr-9 py-2.5 rounded-full bg-white border border-slate-200/90 text-xs font-semibold text-slate-800 placeholder-slate-400 outline-none focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/10 transition shadow-2xs font-poppins">
                <button x-show="searchQuery.length > 0"
                        @click="searchQuery = ''"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 font-bold text-xs"
                        type="button">
                    ✕
                </button>
            </div>

            <a href="{{ route('siswa.kelas.join') }}"
               class="inline-flex min-h-10 flex-1 items-center justify-center gap-2 rounded-full border border-teal-200 bg-teal-50 px-4 py-2 text-xs font-black text-teal-800 transition hover:border-teal-300 hover:bg-teal-100 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 sm:flex-none"
               aria-label="Masuk kelas dengan kode dari guru untuk mendapatkan akses soal">
                <span>🏫</span>
                <span>Masuk Kelas dengan Kode</span>
                <span aria-hidden="true">&rarr;</span>
            </a>
        </div>

        {{-- Status Filter Buttons (Semua, Belum selesai, Selesai) --}}
        <div class="flex items-center gap-1 bg-slate-100/80 p-1 rounded-full font-poppins self-end sm:self-auto">
            <button type="button" 
                    @click="statusFilter = 'all'" 
                    :class="statusFilter === 'all' ? 'bg-white text-slate-900 shadow-2xs font-extrabold' : 'text-slate-500 hover:text-slate-800 font-bold bg-transparent'"
                    class="px-3.5 py-1.5 rounded-full text-xs transition-all">
                Semua
            </button>
            <button type="button" 
                    @click="statusFilter = 'uncompleted'" 
                    :class="statusFilter === 'uncompleted' ? 'bg-white text-slate-900 shadow-2xs font-extrabold' : 'text-slate-500 hover:text-slate-800 font-bold bg-transparent'"
                    class="px-3.5 py-1.5 rounded-full text-xs transition-all">
                Belum selesai
            </button>
            <button type="button" 
                    @click="statusFilter = 'completed'" 
                    :class="statusFilter === 'completed' ? 'bg-white text-slate-900 shadow-2xs font-extrabold' : 'text-slate-500 hover:text-slate-800 font-bold bg-transparent'"
                    class="px-3.5 py-1.5 rounded-full text-xs transition-all">
                Selesai
            </button>
        </div>
    </div>

    <!-- 4. GRID KARTU TOPIK (CARD KERJAKAN SEMUA INI SESUAI MOCKUP FIGMA) -->
    @php
        $categoryConfig = [
            'urutan' => [
                'badge' => 'URUTAN',
                'header_bg' => 'bg-[#FFF1F2]',
                'badge_bg' => 'bg-[#FEE2E2] text-[#EF4444]',
                'icon_color' => 'text-[#EF4444]',
                'icon_svg' => 'list',
            ],
            'pola' => [
                'badge' => 'POLA',
                'header_bg' => 'bg-[#FEFCE8]',
                'badge_bg' => 'bg-[#FEF08A] text-[#D97706]',
                'icon_color' => 'text-[#F59E0B]',
                'icon_svg' => 'grid',
            ],
            'percabangan' => [
                'badge' => 'PERCABANGAN',
                'header_bg' => 'bg-[#F0FDF4]',
                'badge_bg' => 'bg-[#BBF7D0] text-[#059669]',
                'icon_color' => 'text-[#10B981]',
                'icon_svg' => 'branch',
            ],
            'pengulangan' => [
                'badge' => 'PENGULANGAN',
                'header_bg' => 'bg-[#EFF6FF]',
                'badge_bg' => 'bg-[#BFDBFE] text-[#2563EB]',
                'icon_color' => 'text-[#3B82F6]',
                'icon_svg' => 'loop',
            ],
            'dekomposisi' => [
                'badge' => 'DEKOMPOSISI',
                'header_bg' => 'bg-[#FAF5FF]',
                'badge_bg' => 'bg-[#E9D5FF] text-[#7C3AED]',
                'icon_color' => 'text-[#8B5CF6]',
                'icon_svg' => 'layers',
            ],
        ];
        $defaultConfig = [
            'badge' => 'MODUL',
            'header_bg' => 'bg-[#F8FAFC]',
            'badge_bg' => 'bg-slate-200 text-slate-700',
            'icon_color' => 'text-slate-600',
            'icon_svg' => 'doc',
        ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6 font-poppins">
        @forelse($topics as $topic)
            @php
                $catKey = strtolower($topic->category ?? '');
                $cfg = $categoryConfig[$catKey] ?? $defaultConfig;
                $isFullyCompleted = ($topic->quizzes_count > 0 && $topic->completed_count === $topic->quizzes_count);
                $totalTopicPoints = $topic->quizzes->sum('points');
            @endphp
            
            <div x-show="isTopicVisible({{ json_encode($topic->title) }}, {{ json_encode($topic->description) }}, {{ $topic->completed_count }}, {{ $topic->quizzes_count }})"
                 x-transition
                 class="bg-white rounded-[28px] sm:rounded-[32px] border border-slate-200/80 shadow-xs overflow-hidden flex flex-col justify-between hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 group">
                
                <!-- Card Header (Pastel Color Area with Squircle Icon & Badges) -->
                <div class="{{ $cfg['header_bg'] }} p-5 sm:p-6 flex items-start justify-between border-b border-black/[0.03]">
                    {{-- White Squircle Icon Box --}}
                    <div class="w-12 h-12 rounded-[18px] bg-white shadow-xs flex items-center justify-center {{ $cfg['icon_color'] }} flex-shrink-0 group-hover:scale-105 transition-transform">
                        @if($cfg['icon_svg'] === 'list')
                            {{-- List Icon --}}
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <line x1="8" y1="6" x2="21" y2="6"></line>
                                <line x1="8" y1="12" x2="21" y2="12"></line>
                                <line x1="8" y1="18" x2="21" y2="18"></line>
                                <circle cx="4" cy="6" r="1" fill="currentColor"></circle>
                                <circle cx="4" cy="12" r="1" fill="currentColor"></circle>
                                <circle cx="4" cy="18" r="1" fill="currentColor"></circle>
                            </svg>
                        @elseif($cfg['icon_svg'] === 'grid')
                            {{-- Grid 4 squares icon --}}
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                                <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                                <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                                <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                            </svg>
                        @elseif($cfg['icon_svg'] === 'branch')
                            {{-- Branch / Split Icon --}}
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <line x1="6" y1="3" x2="6" y2="15"></line>
                                <circle cx="18" cy="6" r="3"></circle>
                                <circle cx="6" cy="18" r="3"></circle>
                                <path d="M18 9a9 9 0 0 1-9 9"></path>
                            </svg>
                        @elseif($cfg['icon_svg'] === 'loop')
                            {{-- Loop / Repeat Icon --}}
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <polyline points="17 1 21 5 17 9"></polyline>
                                <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                                <polyline points="7 23 3 19 7 15"></polyline>
                                <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                            </svg>
                        @elseif($cfg['icon_svg'] === 'layers')
                            {{-- Layers / Decomposition Icon --}}
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                                <polyline points="2 17 12 22 22 17"></polyline>
                                <polyline points="2 12 12 17 22 12"></polyline>
                            </svg>
                        @else
                            {{-- Document / Modul Icon --}}
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <rect x="4" y="4" width="16" height="16" rx="2"></rect>
                            </svg>
                        @endif
                    </div>

                    {{-- Badges Column --}}
                    <div class="flex flex-col items-end gap-1.5">
                        <span class="text-[10px] sm:text-[11px] font-black px-3.5 py-1 rounded-full uppercase tracking-wider {{ $cfg['badge_bg'] }}">
                            {{ $cfg['badge'] }}
                        </span>

                        @if($isFullyCompleted)
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold px-3 py-1 rounded-full bg-[#D1FAE5] text-[#059669] shadow-2xs">
                                ✓ Selesai semua
                            </span>
                        @elseif($topic->completed_count > 0)
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold px-3 py-1 rounded-full bg-[#0F172A] text-white shadow-2xs">
                                Sedang belajar
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Card Content --}}
                <div class="p-5 sm:p-6 space-y-4">
                    {{-- Level & Title --}}
                    <div class="space-y-1">
                        <span class="text-[11px] font-black text-slate-400 uppercase tracking-wider block font-poppins">
                            Kelas {{ $topic->kelas_level ?? 1 }} SD
                        </span>
                        <h2 class="text-xl font-extrabold text-slate-900 leading-snug group-hover:text-blue-600 transition-colors font-poppins">
                            {{ $topic->title }}
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-500 font-medium leading-relaxed line-clamp-2 pt-1 font-poppins">
                            {{ $topic->description ?: 'Belum ada deskripsi modul.' }}
                        </p>
                    </div>

                    {{-- 2 Stat Box Grid Sesuai Mockup --}}
                    <div class="grid grid-cols-2 gap-2.5 pt-1">
                        <div class="bg-slate-50 border border-slate-100/90 rounded-[18px] p-3 text-center">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block font-poppins">TOTAL SOAL</span>
                            <span class="text-sm sm:text-base font-extrabold text-slate-900 mt-0.5 block font-poppins">{{ $topic->quizzes_count }} soal</span>
                        </div>
                        <div class="bg-[#FEF9C3]/60 border border-amber-100 rounded-[18px] p-3 text-center">
                            <span class="text-[10px] font-black text-amber-700 uppercase tracking-wider block font-poppins">POIN TERSEDIA</span>
                            <span class="text-sm sm:text-base font-extrabold text-[#D97706] mt-0.5 block font-poppins">+{{ $totalTopicPoints }} poin</span>
                        </div>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="space-y-1.5 pt-1">
                        <div class="flex items-center justify-between text-xs font-bold text-slate-500 font-poppins">
                            <span>Kemajuan</span>
                            <span class="font-extrabold {{ $isFullyCompleted ? 'text-emerald-600' : 'text-slate-700' }}">
                                {{ $topic->completed_count }}/{{ $topic->quizzes_count }} soal ({{ $topic->progress_percent }}%)
                            </span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500 {{ $isFullyCompleted ? 'bg-emerald-500' : 'bg-[#EF4444]' }}"
                                 style="width: {{ $topic->progress_percent }}%;"></div>
                        </div>
                    </div>
                </div>

                <!-- Footer Card Action: Kerjakan Semua Soal Ini Sesuai Mockup -->
                <div class="p-5 sm:p-6 pt-0">
                    @if($topic->quizzes_count > 0)
                        <a href="{{ route('siswa.quiz.play', $topic) }}"
                           class="w-full flex items-center justify-center gap-2 py-3 px-5 rounded-full bg-[#0F172A] hover:bg-slate-800 text-white font-extrabold text-xs sm:text-sm shadow-sm transition-all duration-200 active:scale-[0.98] font-poppins">
                            <span>Kerjakan semua soal ini</span>
                            <span class="text-base leading-none">→</span>
                        </a>
                    @else
                        <button disabled
                                class="w-full py-3 px-5 rounded-full text-xs sm:text-sm font-extrabold bg-[#F1F5F9] text-slate-400 cursor-not-allowed font-poppins">
                            Belum ada soal
                        </button>
                    @endif
                </div>

            </div>
        @empty
            <div class="col-span-full py-12 sm:py-16 text-center space-y-4 bg-white rounded-3xl border border-dashed border-slate-200 p-6 sm:p-10 font-poppins">
                <div class="text-5xl">📚</div>
                <div class="space-y-1.5 max-w-md mx-auto">
                    <h3 class="text-base sm:text-lg font-extrabold text-slate-900">Belum Ada Topik Latihan Soal</h3>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium leading-relaxed">
                        Topik latihan soal sedang disiapkan. Silakan kembali lagi nanti untuk mulai berlatih!
                    </p>
                </div>
            </div>
        @endforelse
    </div>

</div>

<script>
    function quizIndex() {
        return {
            searchQuery: '',
            statusFilter: 'all',
            
            isTopicVisible(title, description, completedCount, totalCount) {
                // Status filter
                if (this.statusFilter === 'completed') {
                    if (totalCount === 0 || completedCount < totalCount) return false;
                }
                if (this.statusFilter === 'uncompleted') {
                    if (totalCount > 0 && completedCount === totalCount) return false;
                }

                // Search query
                if (this.searchQuery.trim().length > 0) {
                    const q = this.searchQuery.toLowerCase().trim();
                    const t = (title || '').toLowerCase();
                    const d = (description || '').toLowerCase();
                    if (!t.includes(q) && !d.includes(q)) {
                        return false;
                    }
                }

                return true;
            }
        };
    }
</script>
@endsection
