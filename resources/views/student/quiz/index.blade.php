@extends('layouts.app')

@section('title', 'Latihan Soal Logika - RoboMath')

@section('content')
<div x-data="quizIndex()" class="max-w-6xl mx-auto space-y-6 sm:space-y-7 pb-12">

    <!-- 1. HERO HEADER BANNER (Biru Solid Sesuai Mockup) -->
    <div class="relative overflow-hidden rounded-[28px] sm:rounded-[32px] bg-[#2563EB] p-6 sm:p-8 text-white shadow-[0_8px_24px_rgba(37,99,235,0.22)]">
        <div class="relative z-10 space-y-2 max-w-3xl">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-black tracking-tight text-white">
                Pilih Topik Latihan Soal Logika!
            </h1>
            <p class="text-white/90 font-medium text-xs sm:text-sm leading-relaxed">
                Pilih topik matematika yang ingin kamu pelajari, lalu kerjakan semua paket soal di dalamnya untuk mengumpulkan poin dan mengasah logika berpikirmu!
            </p>
        </div>
    </div>

    <!-- 2. CARD STATISTIK / KPI (Putih 4 Kolom Sesuai Mockup) -->
    <div class="bg-white rounded-[24px] sm:rounded-[26px] p-5 sm:p-6 shadow-xs border border-amber-100/50">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-6 divide-y sm:divide-y-0 sm:divide-x divide-slate-100">
            {{-- Total Soal --}}
            <div class="space-y-1">
                <span class="text-[10px] sm:text-[11px] font-black uppercase text-slate-400 tracking-wider">TOTAL SOAL</span>
                <div class="text-2xl sm:text-3xl font-black text-[#EF4444] leading-tight">
                    {{ $totalQuizzes }}
                </div>
                <span class="text-[11px] text-slate-400 font-semibold block">Soal</span>
            </div>

            {{-- Soal Selesai --}}
            <div class="space-y-1 pt-3 sm:pt-0 sm:pl-6">
                <span class="text-[10px] sm:text-[11px] font-black uppercase text-slate-400 tracking-wider">SOAL SELESAI</span>
                <div class="text-2xl sm:text-3xl font-black leading-tight flex items-baseline gap-1">
                    <span class="text-[#10B981]">{{ $completedQuizzesCount }}</span>
                    <span class="text-slate-400 font-bold text-base sm:text-lg">/ {{ $totalQuizzes }}</span>
                </div>
                <span class="text-[11px] text-slate-400 font-semibold block">Soal</span>
            </div>

            {{-- Poin Diperoleh --}}
            <div class="space-y-1 pt-3 sm:pt-0 sm:pl-6">
                <span class="text-[10px] sm:text-[11px] font-black uppercase text-slate-400 tracking-wider">POIN DIPEROLEH</span>
                <div class="text-2xl sm:text-3xl font-black text-[#F59E0B] leading-tight">
                    +{{ $totalEarnedPoints }}
                </div>
                <span class="text-[11px] text-slate-400 font-semibold block">Poin</span>
            </div>

            {{-- Kemajuan --}}
            <div class="space-y-1 pt-3 sm:pt-0 sm:pl-6">
                <span class="text-[10px] sm:text-[11px] font-black uppercase text-slate-400 tracking-wider">KEMAJUAN</span>
                <div class="text-2xl sm:text-3xl font-black text-[#2563EB] leading-tight">
                    {{ $overallProgressPercent }}%
                </div>
                <div class="w-full bg-[#E5E7EB] h-1.5 rounded-full overflow-hidden mt-2.5">
                    <div class="bg-[#2563EB] h-full rounded-full transition-all duration-500" style="width: {{ $overallProgressPercent }}%;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. PENCARIAN & STATUS FILTER -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
        {{-- Search Input (Pill Bulat Putih) --}}
        <div class="relative flex-1 max-w-md">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </span>
            <input type="text" 
                   x-model="searchQuery" 
                   placeholder="Cari topik materi latihan..." 
                   class="w-full pl-9 pr-9 py-2.5 rounded-full bg-white border border-slate-200/90 text-xs font-semibold text-slate-800 placeholder-slate-400 outline-none focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/10 transition shadow-2xs">
            <button x-show="searchQuery.length > 0" 
                    @click="searchQuery = ''" 
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 font-bold text-xs"
                    type="button">
                ✕
            </button>
        </div>

        {{-- Status Filter Buttons (Semua, Belum selesai, Selesai) --}}
        <div class="flex items-center gap-1.5 self-end sm:self-auto">
            <button type="button" 
                    @click="statusFilter = 'all'" 
                    :class="statusFilter === 'all' ? 'bg-[#001738] text-white shadow-xs' : 'text-slate-500 hover:text-slate-800 bg-transparent'"
                    class="px-4 py-1.5 rounded-full text-xs font-black transition-all">
                Semua Topik
            </button>
            <button type="button" 
                    @click="statusFilter = 'uncompleted'" 
                    :class="statusFilter === 'uncompleted' ? 'bg-[#001738] text-white shadow-xs' : 'text-slate-500 hover:text-slate-800 bg-transparent'"
                    class="px-3.5 py-1.5 rounded-full text-xs font-bold transition-all">
                Belum Selesai
            </button>
            <button type="button" 
                    @click="statusFilter = 'completed'" 
                    :class="statusFilter === 'completed' ? 'bg-[#001738] text-white shadow-xs' : 'text-slate-500 hover:text-slate-800 bg-transparent'"
                    class="px-3.5 py-1.5 rounded-full text-xs font-bold transition-all">
                Selesai
            </button>
        </div>
    </div>

    <!-- 4. TOPIC FILTER PILLS -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
        {{-- Semua topik --}}
        <button type="button" 
                @click="activeTopicId = 'all'" 
                :class="activeTopicId === 'all' ? 'bg-[#001738] text-white shadow-xs' : 'bg-white border border-slate-200/90 text-slate-700 hover:border-slate-300'"
                class="px-4 py-1.5 rounded-full font-black text-xs whitespace-nowrap transition-all flex items-center gap-1.5 flex-shrink-0 shadow-2xs">
            <span>Semua</span>
            <span :class="activeTopicId === 'all' ? 'text-white' : 'text-slate-500'" class="font-black">{{ $topics->count() }}</span>
        </button>

        @php
            $topicPalette = [
                1 => ['dot' => 'bg-[#EF4444]', 'short' => 'Urutan', 'header_bg' => 'bg-[#FFEBEB]', 'badge_bg' => 'bg-[#FFD6D6] text-[#B91C1C]', 'num_bg' => 'bg-[#FEE2E2] text-[#EF4444]'],
                2 => ['dot' => 'bg-[#F59E0B]', 'short' => 'Pola & Pattern', 'header_bg' => 'bg-[#FEF9C3]', 'badge_bg' => 'bg-[#FEF08A] text-[#92400E]', 'num_bg' => 'bg-[#FEF3C7] text-[#D97706]'],
                3 => ['dot' => 'bg-[#10B981]', 'short' => 'Percabangan', 'header_bg' => 'bg-[#DCFCE7]', 'badge_bg' => 'bg-[#BBF7D0] text-[#166534]', 'num_bg' => 'bg-[#DCFCE7] text-[#15803D]'],
                4 => ['dot' => 'bg-[#3B82F6]', 'short' => 'Pengulangan', 'header_bg' => 'bg-[#DBEAFE]', 'badge_bg' => 'bg-[#BFDBFE] text-[#1E40AF]', 'num_bg' => 'bg-[#DBEAFE] text-[#1D4ED8]'],
                5 => ['dot' => 'bg-[#8B5CF6]', 'short' => 'Dekomposisi', 'header_bg' => 'bg-[#F3E8FF]', 'badge_bg' => 'bg-[#E9D5FF] text-[#6B21A8]', 'num_bg' => 'bg-[#F3E8FF] text-[#7E22CE]'],
            ];
        @endphp

        @foreach($topics as $topic)
            @php
                $style = $topicPalette[$topic->id] ?? [
                    'dot' => 'bg-[#3B82F6]',
                    'short' => $topic->title,
                    'header_bg' => 'bg-[#DBEAFE]',
                    'badge_bg' => 'bg-[#BFDBFE] text-[#1E40AF]',
                    'num_bg' => 'bg-[#DBEAFE] text-[#1D4ED8]',
                ];
            @endphp
            <button type="button" 
                    @click="activeTopicId = {{ $topic->id }}" 
                    :class="activeTopicId === {{ $topic->id }} ? 'bg-[#001738] text-white shadow-xs' : 'bg-white border border-slate-200/90 text-slate-700 hover:border-slate-300'"
                    class="px-3.5 py-1.5 rounded-full font-bold text-xs whitespace-nowrap transition-all flex items-center gap-2 flex-shrink-0 shadow-2xs">
                <span class="w-2 h-2 rounded-full {{ $style['dot'] }} flex-shrink-0"></span>
                <span>{{ $topic->title }}</span>
                <span :class="activeTopicId === {{ $topic->id }} ? 'text-white' : 'text-slate-500'" class="font-black">{{ $topic->quizzes_count }}</span>
            </button>
        @endforeach
    </div>

    <!-- 5. GRID KARTU TOPIK (CARD KERJAKAN SEMUA INI) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
        @forelse($topics as $topic)
            @php
                $style = $topicPalette[$topic->id] ?? [
                    'dot' => 'bg-[#3B82F6]',
                    'short' => $topic->title,
                    'header_bg' => 'bg-[#DBEAFE]',
                    'badge_bg' => 'bg-[#BFDBFE] text-[#1E40AF]',
                    'num_bg' => 'bg-[#DBEAFE] text-[#1D4ED8]',
                ];
                $isFullyCompleted = ($topic->quizzes_count > 0 && $topic->completed_count === $topic->quizzes_count);
                $totalTopicPoints = $topic->quizzes->sum('points');
            @endphp
            
            <div x-show="isTopicVisible({{ $topic->id }}, {{ json_encode($topic->title) }}, {{ json_encode($topic->description) }}, {{ $topic->completed_count }}, {{ $topic->quizzes_count }})"
                 x-transition
                 class="bg-white rounded-[26px] sm:rounded-[30px] border-2 {{ $isFullyCompleted ? 'border-emerald-200 shadow-emerald-500/5' : 'border-slate-200/90 shadow-sm' }} overflow-hidden flex flex-col justify-between hover:shadow-xl hover:scale-[1.01] transition-all duration-200 group">
                
                <div class="p-6 space-y-4">
                    <!-- Top Row: Icon + Category Badge & Status -->
                    <div class="flex items-start justify-between gap-3">
                        <div class="w-14 h-14 rounded-2xl {{ $style['header_bg'] }} flex items-center justify-center text-3xl shadow-sm flex-shrink-0 group-hover:scale-105 transition-transform">
                            {{ $topic->icon ?: '📘' }}
                        </div>

                        <div class="flex flex-col items-end gap-1.5">
                            <span class="text-[11px] font-black px-3 py-1 rounded-full uppercase tracking-wider {{ $style['badge_bg'] }}">
                                {{ $topic->category ?: 'Logika' }}
                            </span>
                            @if($isFullyCompleted)
                                <span class="inline-flex items-center gap-1 text-[11px] font-black px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    ✓ Selesai Semua
                                </span>
                            @elseif($topic->completed_count > 0)
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-900 border border-amber-200">
                                    Sedang Belajar
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Title & Description -->
                    <div class="space-y-1.5">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-black text-slate-400 uppercase tracking-wider">Kelas {{ $topic->kelas_level }} SD</span>
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 leading-snug group-hover:text-indigo-600 transition-colors">
                            {{ $topic->title }}
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-500 font-medium leading-relaxed line-clamp-2">
                            {{ $topic->description ?: 'Latihan logika dan matematika interaktif untuk meningkatkan kemampuan berpikir kritis.' }}
                        </p>
                    </div>

                    <!-- Stats Pill Row -->
                    <div class="grid grid-cols-2 gap-2 pt-2">
                        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-2.5 text-center">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Total Soal</span>
                            <span class="text-sm font-black text-slate-800">{{ $topic->quizzes_count }} Soal</span>
                        </div>
                        <div class="bg-amber-50/70 border border-amber-100 rounded-2xl p-2.5 text-center">
                            <span class="text-[10px] font-black text-amber-700 uppercase tracking-wider block">Poin Tersedia</span>
                            <span class="text-sm font-black text-amber-600">+{{ $totalTopicPoints }} Poin</span>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="space-y-1.5 pt-1">
                        <div class="flex items-center justify-between text-xs font-bold text-slate-600">
                            <span>Kemajuan</span>
                            <span class="font-black {{ $isFullyCompleted ? 'text-emerald-600' : 'text-slate-800' }}">{{ $topic->completed_count }}/{{ $topic->quizzes_count }} Soal ({{ $topic->progress_percent }}%)</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500 {{ $isFullyCompleted ? 'bg-emerald-500' : 'bg-gradient-to-r from-blue-500 to-indigo-600' }}"
                                 style="width: {{ $topic->progress_percent }}%;"></div>
                        </div>
                    </div>
                </div>

                <!-- Footer Card Action: Kerjakan Semua Ini -->
                <div class="p-6 pt-0">
                    @if($topic->quizzes_count > 0)
                        <a href="{{ route('siswa.quiz.play', $topic) }}"
                           style="background-color: {{ $isFullyCompleted ? '#059669' : '#001738' }}; color: #ffffff !important;"
                           class="w-full flex items-center justify-center gap-2 py-3.5 px-5 rounded-2xl text-xs sm:text-sm font-black shadow-md transition-all duration-200 active:scale-95 hover:opacity-90">
                            @if($isFullyCompleted)
                                <span style="color: #ffffff !important;" class="font-black">✓ Selesai Semua (Lihat Hasil)</span>
                            @else
                                <span style="color: #ffffff !important;" class="font-black">Kerjakan Semua Soal Ini</span>
                                <span class="text-base">🚀</span>
                            @endif
                        </a>
                    @else
                        <button disabled
                                class="w-full py-3.5 px-5 rounded-2xl text-xs font-black bg-slate-100 text-slate-400 cursor-not-allowed">
                            Belum Ada Soal
                        </button>
                    @endif
                </div>

            </div>
        @empty
            <div class="col-span-full py-16 text-center space-y-3 bg-white rounded-3xl border border-slate-200">
                <div class="text-4xl">📚</div>
                <h3 class="text-base font-black text-slate-800">Belum Ada Topik Latihan</h3>
                <p class="text-xs text-slate-400">Topik latihan soal akan muncul di sini setelah disiapkan oleh guru.</p>
            </div>
        @endforelse
    </div>

</div>

<script>
    function quizIndex() {
        return {
            searchQuery: '',
            activeTopicId: 'all',
            statusFilter: 'all',
            
            isTopicVisible(topicId, title, description, completedCount, totalCount) {
                // Topic filter
                if (this.activeTopicId !== 'all' && this.activeTopicId !== topicId) {
                    return false;
                }

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
