@extends('layouts.app')

@section('title', 'Latihan Soal Logika - RoboMath')

@section('content')
<div x-data="quizIndex()" class="max-w-6xl mx-auto space-y-6 sm:space-y-7 pb-12">

    <!-- 1. HERO HEADER BANNER (Biru Solid Sesuai Mockup) -->
    <div class="relative overflow-hidden rounded-[28px] sm:rounded-[32px] bg-[#2563EB] p-6 sm:p-8 text-white shadow-[0_8px_24px_rgba(37,99,235,0.22)]">
        <div class="relative z-10 space-y-2 max-w-3xl">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-black tracking-tight text-white">
                Pilih Soal Latihan Logika!
            </h1>
            <p class="text-white/90 font-medium text-xs sm:text-sm leading-relaxed">
                Kamu dapat melihat seluruh soal dan memilih soal mana pun yang ingin kamu kerjakan secara bebas. Selesaikan tantangannya dan raih poin maksimal!
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
                   placeholder="Cari kata kunci soal latihan..." 
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
                Semua
            </button>
            <button type="button" 
                    @click="statusFilter = 'uncompleted'" 
                    :class="statusFilter === 'uncompleted' ? 'bg-[#001738] text-white shadow-xs' : 'text-slate-500 hover:text-slate-800 bg-transparent'"
                    class="px-3.5 py-1.5 rounded-full text-xs font-bold transition-all">
                Belum selesai
            </button>
            <button type="button" 
                    @click="statusFilter = 'completed'" 
                    :class="statusFilter === 'completed' ? 'bg-[#001738] text-white shadow-xs' : 'text-slate-500 hover:text-slate-800 bg-transparent'"
                    class="px-3.5 py-1.5 rounded-full text-xs font-bold transition-all">
                Selesai
            </button>
        </div>
    </div>

    <!-- 4. TOPIC FILTER PILLS (Semua topik, Urutan, Pola, dll.) -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
        {{-- Semua topik --}}
        <button type="button" 
                @click="activeTopicId = 'all'" 
                :class="activeTopicId === 'all' ? 'bg-[#001738] text-white shadow-xs' : 'bg-white border border-slate-200/90 text-slate-700 hover:border-slate-300'"
                class="px-4 py-1.5 rounded-full font-black text-xs whitespace-nowrap transition-all flex items-center gap-1.5 flex-shrink-0 shadow-2xs">
            <span>Semua topik</span>
            <span :class="activeTopicId === 'all' ? 'text-white' : 'text-slate-500'" class="font-black">{{ $totalQuizzes }}</span>
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
                <span>{{ $style['short'] }}</span>
                <span :class="activeTopicId === {{ $topic->id }} ? 'text-white' : 'text-slate-500'" class="font-black">{{ $topic->quizzes_count }}</span>
            </button>
        @endforeach
    </div>

    <!-- 5. DAFTAR SOAL PER TOPIK (GROUPED BY TOPIC) -->
    <div class="space-y-6">
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
            <div x-show="isTopicVisible({{ $topic->id }})" 
                 x-transition 
                 class="bg-white rounded-[24px] sm:rounded-[28px] border border-amber-100/70 overflow-hidden shadow-xs">
                
                {{-- Header Strip Topik Sesuai Mockup --}}
                <div class="{{ $style['header_bg'] }} px-5 sm:px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-black/5">
                    <div class="flex items-center gap-3.5">
                        {{-- Icon Squircle Putih --}}
                        <div class="w-10 h-10 rounded-2xl bg-white shadow-2xs flex items-center justify-center text-slate-700 flex-shrink-0">
                            @if($topic->id === 1)
                                {{-- Urutan List SVG --}}
                                <svg class="w-5 h-5 text-slate-800" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                                </svg>
                            @elseif($topic->id === 2)
                                {{-- Pola Grid SVG --}}
                                <svg class="w-5 h-5 text-slate-800" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <rect x="4" y="4" width="6" height="6" rx="1.5" />
                                    <rect x="14" y="4" width="6" height="6" rx="1.5" />
                                    <rect x="4" y="14" width="6" height="6" rx="1.5" />
                                    <rect x="14" y="14" width="6" height="6" rx="1.5" />
                                </svg>
                            @elseif($topic->id === 3)
                                {{-- Percabangan Branch SVG --}}
                                <svg class="w-5 h-5 text-slate-800" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 3v12m0 0a3 3 0 1 0 3 3m-3-3a3 3 0 0 1 3-3h6a3 3 0 1 0 0-6" />
                                </svg>
                            @elseif($topic->id === 4)
                                {{-- Pengulangan Repeat SVG --}}
                                <svg class="w-5 h-5 text-slate-800" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            @else
                                {{-- Dekomposisi Puzzle/Cube SVG --}}
                                <svg class="w-5 h-5 text-slate-800" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            @endif
                        </div>

                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="font-black text-sm sm:text-base text-slate-900 leading-tight">{{ $topic->title }}</h2>
                                <span class="text-[10px] font-black px-2.5 py-0.5 rounded-full {{ $style['badge_bg'] }}">
                                    {{ $topic->quizzes_count }} soal
                                </span>
                            </div>
                            <span class="text-xs text-slate-500 font-semibold block mt-0.5">
                                {{ $topic->completed_count }} dari {{ $topic->quizzes_count }} selesai
                            </span>
                        </div>
                    </div>

                    {{-- Tombol Kerjakan Semua Topik Ini --}}
                    <div>
                        <a href="{{ route('siswa.quiz.play', $topic) }}" 
                           class="inline-flex items-center justify-center bg-[#001738] hover:bg-slate-800 text-white font-black text-xs px-4 py-2 rounded-full transition shadow-2xs whitespace-nowrap active:scale-95">
                            Kerjakan semua topik ini
                        </a>
                    </div>
                </div>

                {{-- Daftar Soal (Single Column Rows Sesuai Mockup) --}}
                <div class="divide-y divide-slate-100">
                    @forelse($topic->quizzes as $quiz)
                        <div x-show="isQuizVisible({{ $topic->id }}, {{ $quiz->id }}, {{ $quiz->user_is_correct ? 'true' : 'false' }}, {{ json_encode($quiz->question) }})" 
                             class="px-5 sm:px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 hover:bg-[#FAF9F5]/60 transition">
                            
                            {{-- Kiri: Nomor & Pertanyaan & Meta --}}
                            <div class="flex items-start gap-3 sm:gap-3.5 flex-1 min-w-0">
                                {{-- Nomor Squircle Badge Sesuai Warna Topik --}}
                                <div class="w-8 h-8 rounded-xl {{ $style['num_bg'] }} font-black text-xs flex items-center justify-center flex-shrink-0 mt-0.5">
                                    {{ $loop->iteration }}
                                </div>

                                <div class="space-y-1 min-w-0">
                                    {{-- Teks Pertanyaan Soal --}}
                                    <h3 class="font-bold text-[#001738] text-xs sm:text-sm leading-snug">
                                        {{ $quiz->question }}
                                    </h3>

                                    {{-- Meta: Dot Tingkat Kesulitan + Tipe Soal --}}
                                    <div class="flex items-center gap-1.5 text-[11px] text-slate-400 font-medium">
                                        @if($quiz->difficulty === 'mudah')
                                            <span class="w-2 h-2 rounded-full bg-[#10B981] flex-shrink-0"></span>
                                            <span>Mudah · {{ $quiz->type_label ?? 'Pilihan ganda' }}</span>
                                        @elseif($quiz->difficulty === 'sedang')
                                            <span class="w-2 h-2 rounded-full bg-[#F59E0B] flex-shrink-0"></span>
                                            <span>Sedang · {{ $quiz->type_label ?? 'Pilihan ganda' }}</span>
                                        @else
                                            <span class="w-2 h-2 rounded-full bg-[#EF4444] flex-shrink-0"></span>
                                            <span>Sulit · {{ $quiz->type_label ?? 'Pilihan ganda' }}</span>
                                        @endif

                                        @if($quiz->user_is_correct)
                                            <span class="text-[#10B981] font-bold ml-1">· Selesai ✓</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Kanan: Badge Poin & Tombol Aksi Oranye --}}
                            <div class="flex items-center gap-2.5 self-end sm:self-center flex-shrink-0">
                                {{-- Badge Poin Kuning Lembut --}}
                                <span class="px-3 py-1 rounded-full text-xs font-black bg-[#FEF3C7] text-[#D97706] whitespace-nowrap shadow-2xs">
                                    +{{ $quiz->points }} poin
                                </span>

                                {{-- Tombol Pilih & Kerjakan Oranye --}}
                                <a href="{{ route('siswa.quiz.play', [$topic, 'quiz_id' => $quiz->id]) }}" 
                                   class="inline-flex items-center justify-center bg-[#FF8A00] hover:bg-[#EA580C] text-white font-black text-xs px-5 py-2.5 rounded-full shadow-xs transition active:scale-95 whitespace-nowrap">
                                    {{ $quiz->user_is_correct ? 'Ulangi' : 'Pilih & Kerjakan' }}
                                </a>
                            </div>

                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-slate-400 font-bold text-xs">
                            Belum ada soal pada topik ini.
                        </div>
                    @endforelse
                </div>

            </div>
        @endforeach
    </div>

</div>

<script>
    function quizIndex() {
        return {
            searchQuery: '',
            activeTopicId: 'all',
            statusFilter: 'all',
            
            isTopicVisible(topicId) {
                if (this.activeTopicId !== 'all' && this.activeTopicId !== topicId) {
                    return false;
                }
                return true;
            },

            isQuizVisible(topicId, quizId, isCorrect, questionText) {
                // Topic filter
                if (this.activeTopicId !== 'all' && this.activeTopicId !== topicId) {
                    return false;
                }

                // Status filter
                if (this.statusFilter === 'completed' && !isCorrect) {
                    return false;
                }
                if (this.statusFilter === 'uncompleted' && isCorrect) {
                    return false;
                }

                // Search query
                if (this.searchQuery.trim().length > 0) {
                    const q = this.searchQuery.toLowerCase().trim();
                    const text = (questionText || '').toLowerCase();
                    if (!text.includes(q)) {
                        return false;
                    }
                }

                return true;
            }
        }
    }
</script>
@endsection

