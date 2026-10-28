@extends('layouts.app')

@section('title', 'Hasil Latihan - ' . $topic->title . ' - RoboMath')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 sm:space-y-7 pb-12 font-poppins">

    @php
        $percentage = $totalAttempts > 0 ? round(($totalCorrect / $totalAttempts) * 100) : 0;
    @endphp

    <!-- 1. HERO BLUE BANNER (Sesuai Mockup) -->
    <div class="rounded-[28px] sm:rounded-[32px] bg-[#2563EB] p-7 sm:p-9 text-white shadow-[0_8px_24px_rgba(37,99,235,0.22)] font-poppins relative overflow-hidden">
        <div class="relative z-10 space-y-1.5 max-w-2xl">
            <span class="text-[11px] font-black uppercase tracking-wider text-white/80 font-poppins block">
                HASIL LATIHAN · {{ mb_strtoupper($topic->title) }}
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight font-poppins">
                {{ $percentage >= 80 ? 'Luar biasa, juara!' : ($percentage >= 50 ? 'Semangat belajar!' : 'Tetap semangat!') }}
            </h1>
            <p class="text-white/90 text-xs sm:text-sm font-medium leading-relaxed font-poppins pt-1">
                Kamu menjawab {{ $totalCorrect }} dari {{ $totalAttempts }} soal dengan benar. Baca pembahasan di bawah supaya jawabanmu makin tepat di latihan berikutnya.
            </p>
        </div>
    </div>

    <!-- 2. CARD KPI STATISTIK 3 KOLOM (Sesuai Mockup) -->
    <div class="bg-white rounded-[24px] sm:rounded-[28px] p-5 sm:p-6 border border-slate-100 shadow-xs font-poppins">
        <div class="grid grid-cols-3 gap-4 sm:gap-6 divide-x divide-slate-100">
            <!-- BENAR -->
            <div class="space-y-1">
                <span class="text-[10px] sm:text-[11px] font-black uppercase text-slate-400 tracking-wider block font-poppins">BENAR</span>
                <div class="text-2xl sm:text-3xl font-extrabold leading-tight flex items-baseline gap-1 font-poppins">
                    <span class="text-[#10B981]">{{ $totalCorrect }}</span>
                    <span class="text-slate-400 font-bold text-base sm:text-lg">/ {{ $totalAttempts }}</span>
                </div>
                <span class="text-[11px] text-slate-400 font-semibold block font-poppins">Soal</span>
            </div>

            <!-- NILAI -->
            <div class="space-y-1 pl-4 sm:pl-6">
                <span class="text-[10px] sm:text-[11px] font-black uppercase text-slate-400 tracking-wider block font-poppins">NILAI</span>
                <div class="text-2xl sm:text-3xl font-extrabold text-[#2563EB] leading-tight font-poppins">
                    {{ $percentage }}%
                </div>
                <div class="w-full bg-[#E5E7EB] h-2 rounded-full overflow-hidden mt-2.5">
                    <div class="bg-[#2563EB] h-full rounded-full transition-all duration-500" style="width: {{ $percentage }}%;"></div>
                </div>
            </div>

            <!-- POIN DIPEROLEH -->
            <div class="space-y-1 pl-4 sm:pl-6">
                <span class="text-[10px] sm:text-[11px] font-black uppercase text-slate-400 tracking-wider block font-poppins">POIN DIPEROLEH</span>
                <div class="text-2xl sm:text-3xl font-extrabold text-[#F59E0B] leading-tight font-poppins">
                    +{{ $totalPoints }}
                </div>
                <span class="text-[11px] text-slate-400 font-semibold block font-poppins">Poin</span>
            </div>
        </div>
    </div>

    <!-- 3. HEADER PEMBAHASAN SOAL (Kuning Lembut Sesuai Mockup) -->
    <div class="bg-[#FEF3C7]/60 rounded-[22px] p-4 sm:p-5 flex items-center justify-between font-poppins border border-amber-200/40">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-[14px] bg-white shadow-2xs flex items-center justify-center text-slate-800 flex-shrink-0">
                <svg class="w-5 h-5 text-slate-800" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    <line x1="8" y1="8" x2="16" y2="8"></line>
                    <line x1="8" y1="12" x2="13" y2="12"></line>
                </svg>
            </div>
            <h2 class="font-extrabold text-lg sm:text-xl text-slate-900 font-poppins">
                Pembahasan soal
            </h2>
        </div>
        
        <span class="bg-white text-slate-700 font-extrabold text-xs px-3.5 py-1.5 rounded-full shadow-2xs font-poppins">
            {{ $attempts->count() }} soal
        </span>
    </div>

    <!-- 4. LIST SOAL & PEMBAHASAN DETAIL (Sesuai Mockup Gambar 3) -->
    <div class="space-y-5 font-poppins">
        @foreach($attempts as $index => $attempt)
            @php
                $correctKey = $attempt->quiz->correct_answer ?? null;
                $correctOptionText = $attempt->quiz->options[$correctKey] ?? null;
                $steps = [];
                if ($correctOptionText) {
                    $rawSteps = preg_split('/(\x{27a1}\x{fe0f}|\x{27a1}|->|→|&rarr;)/u', $correctOptionText);
                    if (count($rawSteps) > 1) {
                        $steps = array_map('trim', $rawSteps);
                    }
                }
            @endphp

            <div class="bg-white rounded-[26px] sm:rounded-[30px] border border-slate-200/80 p-6 sm:p-7 space-y-4 font-poppins shadow-xs">
                
                {{-- Top Row: Nomor Soal + Badge Status Benar/Salah --}}
                <div class="flex items-center justify-between">
                    <div class="w-8 h-8 rounded-xl font-extrabold text-xs sm:text-sm flex items-center justify-center {{ $attempt->is_correct ? 'bg-[#DCFCE7] text-[#10B981]' : 'bg-[#FEE2E2] text-[#EF4444]' }}">
                        {{ $index + 1 }}
                    </div>

                    @if($attempt->is_correct)
                        <span class="bg-[#DCFCE7] text-[#10B981] font-extrabold text-xs px-3.5 py-1 rounded-full font-poppins">
                            Benar · +{{ $attempt->points_earned }} poin
                        </span>
                    @else
                        <span class="bg-[#FEE2E2] text-[#EF4444] font-extrabold text-xs px-3.5 py-1 rounded-full font-poppins">
                            Salah
                        </span>
                    @endif
                </div>

                {{-- Question Text --}}
                <h3 class="font-extrabold text-base sm:text-lg text-slate-900 leading-snug font-poppins">
                    {!! $attempt->quiz->question ?? '-' !!}
                </h3>

                {{-- Badges Jawaban Siswa & Kunci Jawaban Model --}}
                <div class="flex items-center gap-2.5 flex-wrap pt-1 font-poppins">
                    @if($attempt->is_correct)
                        <span class="px-3.5 py-1 rounded-full text-xs font-extrabold border border-emerald-300 bg-emerald-50/60 text-emerald-700 font-poppins">
                            Jawabanmu: {{ $attempt->answer }}
                        </span>
                    @else
                        <span class="px-3.5 py-1 rounded-full text-xs font-extrabold border border-rose-300 bg-rose-50/60 text-rose-600 font-poppins">
                            Jawabanmu: {{ $attempt->answer }}
                        </span>
                    @endif

                    @if($attempt->quiz && $attempt->quiz->correct_answer)
                        <span class="px-3.5 py-1 rounded-full text-xs font-extrabold border border-emerald-300 bg-emerald-50/60 text-emerald-700 font-poppins">
                            Kunci: {{ $attempt->quiz->correct_answer }}
                        </span>
                    @endif

                    @if($attempt->quiz && in_array($attempt->quiz->type, ['essay', 'uraian']))
                        <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase bg-purple-100 text-purple-800 border border-purple-200">
                            ✍️ Soal Essay
                        </span>
                    @endif
                </div>

                {{-- URUTAN YANG BENAR (Jika Berisi Langkah-Langkah Berurutan) --}}
                @if(!empty($steps))
                    <div class="space-y-2 pt-2 font-poppins">
                        <span class="text-[11px] font-black uppercase text-slate-700 tracking-wider block font-poppins">
                            URUTAN YANG BENAR
                        </span>
                        <div class="flex flex-wrap items-center gap-2">
                            @foreach($steps as $sIdx => $step)
                                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full border border-slate-200 bg-white shadow-2xs text-xs font-bold text-slate-800 font-poppins">
                                    <span class="w-5 h-5 rounded-full bg-[#0F172A] text-white text-[11px] font-bold flex items-center justify-center flex-shrink-0">
                                        {{ $sIdx + 1 }}
                                    </span>
                                    <span>{{ $step }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Penjelasan Box (Kuning Lembut Sesuai Mockup) --}}
                @if($attempt->quiz && $attempt->quiz->explanation)
                    <div class="bg-[#FFFBEB] rounded-[20px] p-4 sm:p-5 border border-amber-200/60 flex items-start gap-3.5 font-poppins">
                        <div class="text-[#D97706] text-xl pt-0.5 flex-shrink-0">
                            💡
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-[#D97706] block font-poppins">
                                PENJELASAN
                            </span>
                            <p class="text-xs sm:text-sm text-slate-700 font-medium leading-relaxed font-poppins">
                                {{ $attempt->quiz->explanation }}
                            </p>
                        </div>
                    </div>
                @endif

                {{-- AI Feedback (Jika Jawaban Essay / Ada Feedback AI) --}}
                @if($attempt->ai_feedback)
                    <div class="bg-emerald-50/80 rounded-[20px] p-4 sm:p-5 border border-emerald-200/80 flex items-start gap-3.5 font-poppins">
                        <div class="text-emerald-600 text-xl pt-0.5 flex-shrink-0">
                            🤖
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-emerald-800 block font-poppins">
                                UMPAN BALIK GURU AI
                            </span>
                            <p class="text-xs sm:text-sm text-emerald-950 font-medium leading-relaxed font-poppins">
                                {{ $attempt->ai_feedback }}
                            </p>
                        </div>
                    </div>
                @endif

            </div>
        @endforeach
    </div>

    <!-- 5. TOMBOL AKSI BAWAH (Sesuai Mockup Gambar 3) -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-3 font-poppins">
        <a href="{{ route('siswa.quiz') }}" 
           class="w-full sm:w-auto px-7 py-3.5 bg-[#0F172A] hover:bg-slate-800 text-white font-extrabold text-sm rounded-full text-center shadow-md transition flex items-center justify-center gap-2 font-poppins">
            <span>←</span>
            <span>Kembali ke Latihan Soal</span>
        </a>
        
        <div class="flex items-center gap-3 w-full sm:w-auto font-poppins">
            <a href="{{ route('siswa.leaderboard') }}" 
               class="flex-1 sm:flex-none px-7 py-3.5 bg-[#FF9500] hover:bg-[#EA580C] text-white font-extrabold text-sm rounded-full text-center shadow-[0_4px_14px_rgba(255,149,0,0.35)] transition font-poppins">
                Lihat peringkat
            </a>
            <a href="{{ route('siswa.dashboard') }}" 
               class="flex-1 sm:flex-none px-6 py-3.5 bg-white hover:bg-slate-50 text-slate-800 font-extrabold text-sm rounded-full text-center border border-slate-200 shadow-2xs transition font-poppins">
                Ke dashboard
            </a>
        </div>
    </div>

</div>
@endsection
