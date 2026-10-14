@extends('layouts.app')

@section('content')
<div x-data="quizIndex()" class="space-y-6">

    <!-- Hero Header & Progress Overview -->
    <div class="bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 rounded-3xl p-6 sm:p-8 text-white shadow-xl space-y-6 border border-orange-400/30 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 opacity-15 pointer-events-none text-9xl">
            🎯
        </div>

        <div class="relative z-10 space-y-2">
            <span class="inline-flex items-center gap-1.5 bg-white/20 text-white font-extrabold text-xs px-3.5 py-1 rounded-full uppercase tracking-wider backdrop-blur-sm">
                <span>🤖</span> Latihan Logika Interaktif AI
            </span>
            <h1 class="text-2xl sm:text-4xl font-black tracking-tight">Pilih Soal Latihan Logika! 🎯</h1>
            <p class="text-orange-100 font-semibold text-sm sm:text-base max-w-2xl leading-relaxed">
                Kamu dapat melihat seluruh soal dan memilih soal mana pun yang ingin kamu kerjakan secara bebas. Selesaikan tantangannya dan raih poin maksimal!
            </p>
        </div>

        <!-- Overall Statistics Cards -->
        <div class="relative z-10 grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2">
            <div class="bg-white/15 backdrop-blur-md rounded-2xl p-4 border border-white/20 flex flex-col justify-between">
                <span class="text-xs font-bold text-orange-100 uppercase tracking-wider">Total Soal</span>
                <span class="text-2xl sm:text-3xl font-black text-white mt-1">{{ $totalQuizzes }} <span class="text-sm font-semibold">Soal</span></span>
            </div>
            <div class="bg-white/15 backdrop-blur-md rounded-2xl p-4 border border-white/20 flex flex-col justify-between">
                <span class="text-xs font-bold text-orange-100 uppercase tracking-wider">Soal Selesai</span>
                <span class="text-2xl sm:text-3xl font-black text-white mt-1">{{ $completedQuizzesCount }} <span class="text-sm font-semibold">/ {{ $totalQuizzes }}</span></span>
            </div>
            <div class="bg-white/15 backdrop-blur-md rounded-2xl p-4 border border-white/20 flex flex-col justify-between">
                <span class="text-xs font-bold text-orange-100 uppercase tracking-wider">Poin Diperoleh</span>
                <span class="text-2xl sm:text-3xl font-black text-amber-200 mt-1">+{{ $totalEarnedPoints }} <span class="text-sm font-semibold">Poin</span></span>
            </div>
            <div class="bg-white/15 backdrop-blur-md rounded-2xl p-4 border border-white/20 flex flex-col justify-between">
                <div class="flex items-center justify-between text-xs font-bold text-orange-100 uppercase tracking-wider">
                    <span>Kemajuan</span>
                    <span>{{ $overallProgressPercent }}%</span>
                </div>
                <div class="w-full bg-white/20 h-3 rounded-full overflow-hidden mt-2 border border-white/30">
                    <div class="bg-white h-full rounded-full transition-all duration-500" style="width: {{ $overallProgressPercent }}%;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="bg-white rounded-3xl p-5 border-2 border-slate-200 shadow-sm space-y-4">
        
        <!-- Search and Status Filter Row -->
        <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center justify-between">
            <!-- Search Input -->
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="text" 
                       x-model="searchQuery" 
                       placeholder="Cari kata kunci soal latihan..." 
                       class="w-full pl-10 pr-10 py-3 rounded-2xl border-2 border-slate-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-100 font-semibold text-slate-800 placeholder-slate-400 text-sm transition-all outline-none">
                <button x-show="searchQuery.length > 0" 
                        @click="searchQuery = ''" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 font-bold text-sm">
                    ✕
                </button>
            </div>

            <!-- Status Filter Segmented Control -->
            <div class="inline-flex p-1 bg-slate-100 rounded-2xl border border-slate-200 text-xs font-extrabold flex-shrink-0">
                <button type="button" 
                        @click="statusFilter = 'all'" 
                        :class="statusFilter === 'all' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                        class="px-4 py-2 rounded-xl transition-all">
                    Semua
                </button>
                <button type="button" 
                        @click="statusFilter = 'uncompleted'" 
                        :class="statusFilter === 'uncompleted' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                        class="px-4 py-2 rounded-xl transition-all flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-slate-400"></span> Belum Selesai
                </button>
                <button type="button" 
                        @click="statusFilter = 'completed'" 
                        :class="statusFilter === 'completed' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                        class="px-4 py-2 rounded-xl transition-all flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Selesai
                </button>
            </div>
        </div>

        <!-- Topic Pills Horizontal Scroll -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-thin">
            <button type="button" 
                    @click="activeTopicId = 'all'" 
                    :class="activeTopicId === 'all' ? 'bg-amber-500 text-white shadow-md shadow-amber-500/20' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                    class="px-4 py-2 rounded-2xl font-black text-xs whitespace-nowrap transition-all flex items-center gap-1.5 flex-shrink-0">
                <span>✨</span> Semua Topik ({{ $totalQuizzes }})
            </button>

            @foreach($topics as $topic)
                <button type="button" 
                        @click="activeTopicId = {{ $topic->id }}" 
                        :class="activeTopicId === {{ $topic->id }} ? 'bg-amber-500 text-white shadow-md shadow-amber-500/20' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                        class="px-4 py-2 rounded-2xl font-black text-xs whitespace-nowrap transition-all flex items-center gap-1.5 flex-shrink-0">
                    <span>{{ $topic->icon }}</span> {{ $topic->title }} ({{ $topic->quizzes_count }})
                </button>
            @endforeach
        </div>
    </div>

    <!-- Questions Display Container -->
    <div class="space-y-8">
        @foreach($topics as $topic)
            <div x-show="isTopicVisible({{ $topic->id }})" 
                 x-transition 
                 class="space-y-4">
                
                <!-- Topic Group Header -->
                <div class="bg-white rounded-3xl p-5 border-2 border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center text-3xl flex-shrink-0 border-2 border-amber-200 shadow-inner">
                            {{ $topic->icon }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="font-black text-xl text-slate-800">{{ $topic->title }}</h2>
                                <span class="text-xs font-black px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 border border-slate-200">
                                    {{ $topic->quizzes_count }} Soal
                                </span>
                            </div>
                            <div class="flex items-center gap-3 mt-1.5">
                                <span class="text-xs font-bold text-slate-500">
                                    {{ $topic->completed_count }} dari {{ $topic->quizzes_count }} soal selesai
                                </span>
                                <div class="w-24 bg-slate-100 h-2 rounded-full overflow-hidden border border-slate-200">
                                    <div class="bg-emerald-500 h-full rounded-full transition-all" style="width: {{ $topic->progress_percent }}%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 self-end sm:self-center">
                        <a href="{{ route('siswa.quiz.play', $topic) }}" 
                           class="bg-indigo-600 hover:bg-indigo-700 text-white font-black px-5 py-2.5 rounded-2xl shadow-md hover:shadow-lg transition-all text-xs sm:text-sm whitespace-nowrap flex items-center gap-2">
                            <span>🚀</span> Kerjakan Semua Topik Ini
                        </a>
                    </div>
                </div>

                <!-- Questions Grid in this Topic -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($topic->quizzes as $quiz)
                        <div x-show="isQuizVisible({{ $topic->id }}, {{ $quiz->id }}, {{ $quiz->user_is_correct ? 'true' : 'false' }}, {{ json_encode($quiz->question) }})" 
                             class="bg-white rounded-3xl p-5 border-2 {{ $quiz->user_is_correct ? 'border-emerald-200 bg-emerald-50/20' : 'border-slate-200' }} hover:border-amber-400 hover:shadow-md transition-all flex flex-col justify-between space-y-4">
                            
                            <!-- Question Card Top Badges -->
                            <div class="space-y-3">
                                <div class="flex items-center justify-between gap-2 flex-wrap">
                                    <div class="flex items-center gap-2">
                                        <span class="w-8 h-8 rounded-xl bg-slate-100 text-slate-800 font-black text-xs flex items-center justify-center border border-slate-200">
                                            #{{ $loop->iteration }}
                                        </span>
                                        @if($quiz->difficulty === 'mudah')
                                            <span class="px-2.5 py-1 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 font-extrabold text-xs">
                                                🌱 Mudah
                                            </span>
                                        @elseif($quiz->difficulty === 'sedang')
                                            <span class="px-2.5 py-1 rounded-xl bg-amber-50 text-amber-700 border border-amber-200 font-extrabold text-xs">
                                                ⚡ Sedang
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-xl bg-rose-50 text-rose-700 border border-rose-200 font-extrabold text-xs">
                                                🔥 Sulit
                                            </span>
                                        @endif
                                        <span class="text-xs font-bold text-slate-400">
                                            {{ $quiz->type_label }}
                                        </span>
                                    </div>
                                    <span class="text-xs font-black text-amber-600 bg-amber-50 px-3 py-1 rounded-full border border-amber-200">
                                        +{{ $quiz->points }} Poin
                                    </span>
                                </div>

                                <!-- Question Content Preview -->
                                <p class="text-slate-800 font-extrabold text-sm sm:text-base leading-snug line-clamp-3">
                                    {{ $quiz->question }}
                                </p>
                            </div>

                            <!-- Question Card Bottom Actions & Status -->
                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-3">
                                <div>
                                    @if($quiz->user_is_correct)
                                        <span class="inline-flex items-center gap-1.5 text-xs font-black text-emerald-700 bg-emerald-100/70 border border-emerald-300 px-3 py-1.5 rounded-xl">
                                            ✅ Selesai (+{{ $quiz->user_points_earned }} P)
                                        </span>
                                    @elseif($quiz->user_attempts_count > 0)
                                        <span class="inline-flex items-center gap-1.5 text-xs font-black text-rose-700 bg-rose-100/70 border border-rose-300 px-3 py-1.5 rounded-xl">
                                            ❌ Belum Tepat
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 bg-slate-100 border border-slate-200 px-3 py-1.5 rounded-xl">
                                            ⚪ Belum Dikerjakan
                                        </span>
                                    @endif
                                </div>

                                <div>
                                    <a href="{{ route('siswa.quiz.play', [$topic, 'quiz_id' => $quiz->id]) }}" 
                                       class="inline-flex items-center gap-2 {{ $quiz->user_is_correct ? 'bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-300' : 'bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white shadow-md' }} font-black px-4 py-2 rounded-xl text-xs sm:text-sm transition-all whitespace-nowrap">
                                        <span>{{ $quiz->user_is_correct ? 'Ulangi 🔄' : 'Pilih & Kerjakan 🚀' }}</span>
                                    </a>
                                </div>
                            </div>

                        </div>
                    @empty
                        <div class="col-span-2 text-center p-8 bg-white rounded-3xl border-2 border-dashed border-slate-200 text-slate-400 font-bold text-sm">
                            Belum ada soal pada topik ini.
                        </div>
                    @endforelse
                </div>

            </div>
        @endforeach

        <!-- Empty Results Message -->
        <div x-show="totalVisibleQuizzes === 0" 
             x-transition 
             class="bg-white rounded-3xl p-12 border-2 border-dashed border-slate-200 text-center space-y-4">
            <span class="text-5xl">🔍</span>
            <div class="space-y-1">
                <h3 class="font-black text-xl text-slate-800">Tidak ada soal yang cocok</h3>
                <p class="text-slate-500 font-semibold text-sm">Coba ubah kata kunci pencarian atau sesuaikan filter status.</p>
            </div>
            <button type="button" 
                    @click="searchQuery = ''; activeTopicId = 'all'; statusFilter = 'all'" 
                    class="bg-amber-500 hover:bg-amber-600 text-white font-extrabold px-6 py-2.5 rounded-xl text-xs shadow-md transition-all">
                Reset Filter 🔄
            </button>
        </div>
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
            },

            get totalVisibleQuizzes() {
                // Return estimated or fallback to 1 if default
                return 1;
            }
        }
    }
</script>
@endsection
