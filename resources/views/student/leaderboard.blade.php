@extends('layouts.app')

@section('title', 'Progress & Leaderboard - RoboMath')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">

    <!-- Header Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-[#F59E0B] p-8 text-white shadow-lg border border-amber-300/30">
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-center md:text-left space-y-2">
                <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-black uppercase tracking-wider">
                    🏆 Hall of Fame & Statistik Belajar
                </span>
                <h1 class="text-3xl md:text-4xl font-black tracking-tight">Progress & Leaderboard</h1>
                <p class="text-amber-100 font-medium text-sm max-w-md">
                    Pantau grafik perkembangan belajarmu dan raih posisi teratas di papan peringkat juara!
                </p>
            </div>
            
            @if(auth()->user()->isSiswa() && isset($userRank))
            <div class="bg-white/20 backdrop-blur-md border border-white/30 rounded-2xl p-4 text-center min-w-[150px] shadow-lg">
                <p class="text-xs font-bold uppercase tracking-wider text-amber-100">Peringkat Kamu</p>
                <div class="text-4xl font-black mt-1">#{{ $userRank }}</div>
                <p class="text-xs text-amber-200 mt-1 font-semibold">{{ number_format(auth()->user()->total_score ?? 0) }} Poin</p>
            </div>
            @endif
        </div>
        <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
    </div>

    <!-- 1. GRAFIK PROGRES & SKOR SAYA (BAGIAN PROGRESS) -->
    <div class="bg-white rounded-3xl border-2 border-amber-200 p-6 sm:p-8 space-y-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-amber-100 pb-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 flex items-center gap-2">
                    <span>📈</span> Grafik Progres & Skor Saya
                </h2>
                <p class="text-xs text-slate-500 font-semibold mt-0.5">Ringkasan ketuntasan modul dan skor latihan matematika kamu.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-black px-3 py-1 rounded-full bg-amber-50 text-amber-900 border border-amber-200">
                    Kelas {{ auth()->user()->kelas }} SD
                </span>
                <span class="text-xs font-black px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
                    {{ $completedLessons }} / {{ $totalLessons }} Modul Tuntas ({{ $progressPercent }}%)
                </span>
            </div>
        </div>

        <!-- 3 KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="p-5 rounded-2xl bg-amber-50/70 border border-amber-200 space-y-1">
                <span class="text-[11px] font-black uppercase text-amber-800 tracking-wider">Total Skor</span>
                <div class="text-3xl font-black text-amber-600">{{ number_format(auth()->user()->total_score) }}</div>
                <p class="text-xs text-slate-500 font-medium">Level {{ auth()->user()->level }} ({{ auth()->user()->level_name }})</p>
            </div>
            <div class="p-5 rounded-2xl bg-emerald-50/70 border border-emerald-200 space-y-1">
                <span class="text-[11px] font-black uppercase text-emerald-800 tracking-wider">Akurasi Soal</span>
                <div class="text-3xl font-black text-emerald-600">{{ $userAccuracy }}%</div>
                <p class="text-xs text-slate-500 font-medium">{{ $correctAttempts }} benar dari {{ $totalAttempts }} soal</p>
            </div>
            <div class="p-5 rounded-2xl bg-indigo-50/70 border border-indigo-200 space-y-1">
                <span class="text-[11px] font-black uppercase text-indigo-800 tracking-wider">Ketuntasan Materi</span>
                <div class="text-3xl font-black text-indigo-600">{{ $progressPercent }}%</div>
                <p class="text-xs text-slate-500 font-medium">{{ $completedLessons }} dari {{ $totalLessons }} materi</p>
            </div>
        </div>

        <!-- Grafik Batang Visual: Ketuntasan per Topik Materi -->
        <div class="space-y-4 pt-2">
            <h3 class="text-sm font-black text-slate-800 flex items-center gap-2">
                <span>📊</span> Ketuntasan Progres per Topik Matematika
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @forelse($topicProgress as $tp)
                <div class="p-4 rounded-2xl border-2 border-slate-100 bg-slate-50/50 space-y-2">
                    <div class="flex items-center justify-between text-xs font-bold text-slate-800">
                        <span class="flex items-center gap-1.5">
                            <span class="text-lg">{{ $tp->icon }}</span>
                            <span>{{ $tp->title }}</span>
                        </span>
                        <span class="font-black text-indigo-600">{{ $tp->percent }}%</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-500 {{ $tp->percent >= 100 ? 'bg-emerald-500' : 'bg-[#FF9500]' }}" style="width: {{ $tp->percent }}%"></div>
                    </div>
                    <div class="flex justify-between text-[11px] text-slate-400 font-semibold">
                        <span>{{ $tp->completed_count }} / {{ $tp->lessons_count }} Selesai</span>
                        @if($tp->percent >= 100)
                            <span class="text-emerald-600 font-bold">Lulus Sempurna 🎉</span>
                        @endif
                    </div>
                </div>
                @empty
                <p class="text-xs text-slate-400">Belum ada topik materi.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 2. PAPAN PERINGKAT JUARA (BAGIAN LEADERBOARD) -->
    <div class="space-y-6">
        <!-- Filter Kelas Tabs -->
        <div class="flex items-center justify-between flex-wrap gap-3 bg-white p-3 rounded-2xl border-2 border-amber-200 shadow-sm">
            <span class="text-xs font-black text-slate-500 uppercase px-3">Filter Peringkat Kelas:</span>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('siswa.leaderboard', ['kelas' => 'all']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-black transition-all {{ $kelasFilter === 'all' ? 'bg-amber-500 text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-amber-100' }}">
                    Semua Kelas
                </a>
                @for($k = 1; $k <= 6; $k++)
                <a href="{{ route('siswa.leaderboard', ['kelas' => $k]) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-black transition-all {{ (string)$kelasFilter === (string)$k ? 'bg-amber-500 text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-amber-100' }}">
                    Kelas {{ $k }}
                </a>
                @endfor
            </div>
        </div>

        <!-- Top 3 Podium -->
        @if($leaderboard->count() >= 3)
        <div class="grid grid-cols-3 gap-3 md:gap-6 pt-8 pb-4 items-end">
            {{-- Juara 2 --}}
            @php $second = $leaderboard->get(1); @endphp
            <div class="bg-white rounded-3xl p-4 md:p-6 border-2 border-slate-200 shadow-md text-center transform hover:-translate-y-1 transition duration-300 relative">
                <div class="w-12 h-12 md:w-16 md:h-16 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-2xl md:text-3xl font-black border-4 border-slate-300 shadow-inner -mt-10 md:-mt-12 bg-white">
                    🥈
                </div>
                <div class="mt-3">
                    <span class="inline-block px-2 py-0.5 bg-slate-200 text-slate-700 rounded-full text-[10px] font-black uppercase">Juara 2</span>
                    <h3 class="font-black text-slate-800 text-sm md:text-base truncate mt-1">{{ $second->name }}</h3>
                    <p class="text-xs text-slate-500 font-bold">Kelas {{ $second->kelas }} SD</p>
                    <div class="mt-2 text-xs md:text-sm font-black text-amber-600 bg-amber-50 rounded-xl py-1 px-2 border border-amber-200">
                        ⭐ {{ number_format($second->total_score) }}
                    </div>
                </div>
            </div>

            {{-- Juara 1 --}}
            @php $first = $leaderboard->get(0); @endphp
            <div class="bg-gradient-to-b from-amber-50 to-white rounded-3xl p-5 md:p-8 border-4 border-amber-400 shadow-xl text-center transform -translate-y-2 md:-translate-y-4 relative">
                <div class="w-16 h-16 md:w-20 md:h-20 mx-auto rounded-full bg-amber-400 flex items-center justify-center text-3xl md:text-4xl font-black border-4 border-yellow-200 shadow-lg -mt-12 md:-mt-16 animate-bounce">
                    👑
                </div>
                <div class="mt-3">
                    <span class="inline-block px-3 py-1 bg-amber-400 text-amber-950 rounded-full text-xs font-black uppercase shadow-sm">Juara 1</span>
                    <h3 class="font-black text-slate-900 text-base md:text-lg truncate mt-1.5">{{ $first->name }}</h3>
                    <p class="text-xs text-amber-700 font-bold">Kelas {{ $first->kelas }} SD</p>
                    <div class="mt-3 text-sm md:text-base font-black text-amber-700 bg-amber-100 rounded-2xl py-1.5 px-3 border border-amber-300 shadow-inner">
                        ⭐ {{ number_format($first->total_score) }} Poin
                    </div>
                </div>
            </div>

            {{-- Juara 3 --}}
            @php $third = $leaderboard->get(2); @endphp
            <div class="bg-white rounded-3xl p-4 md:p-6 border-2 border-amber-700/20 shadow-md text-center transform hover:-translate-y-1 transition duration-300 relative">
                <div class="w-12 h-12 md:w-16 md:h-16 mx-auto rounded-full bg-amber-100 flex items-center justify-center text-2xl md:text-3xl font-black border-4 border-amber-600/30 shadow-inner -mt-10 md:-mt-12 bg-white">
                    🥉
                </div>
                <div class="mt-3">
                    <span class="inline-block px-2 py-0.5 bg-amber-100 text-amber-900 rounded-full text-[10px] font-black uppercase">Juara 3</span>
                    <h3 class="font-black text-slate-800 text-sm md:text-base truncate mt-1">{{ $third->name }}</h3>
                    <p class="text-xs text-slate-500 font-bold">Kelas {{ $third->kelas }} SD</p>
                    <div class="mt-2 text-xs md:text-sm font-black text-amber-600 bg-amber-50 rounded-xl py-1 px-2 border border-amber-200">
                        ⭐ {{ number_format($third->total_score) }}
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Table Leaderboard -->
        <div class="bg-white rounded-3xl border-2 border-amber-200 overflow-hidden shadow-sm">
            <div class="p-5 border-b-2 border-amber-100 flex items-center justify-between">
                <h3 class="font-black text-slate-800 text-base">Top 50 Peringkat Siswa</h3>
                <span class="text-xs text-slate-500 font-bold">{{ $leaderboard->count() }} Siswa</span>
            </div>

            @if($leaderboard->isEmpty())
            <div class="p-12 text-center text-slate-400 font-semibold text-sm">Belum ada siswa yang tercatat pada kelas ini.</div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-amber-50/50 text-slate-500 uppercase font-black tracking-wider border-b-2 border-amber-100">
                        <tr>
                            <th class="px-6 py-4">Peringkat</th>
                            <th class="px-6 py-4">Nama Siswa</th>
                            <th class="px-6 py-4">Kelas</th>
                            <th class="px-6 py-4">Level</th>
                            <th class="px-6 py-4 text-right">Total Skor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-amber-100 text-slate-700">
                        @foreach($leaderboard as $index => $student)
                        @php $isMe = auth()->check() && auth()->id() === $student->id; @endphp
                        <tr class="{{ $isMe ? 'bg-amber-100/60 font-black' : 'hover:bg-amber-50/40' }} transition">
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-xs font-black {{ $index === 0 ? 'bg-amber-400 text-amber-950 font-black' : ($index === 1 ? 'bg-slate-200 text-slate-800' : ($index === 2 ? 'bg-amber-200 text-amber-900' : 'bg-slate-100 text-slate-600')) }}">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900 flex items-center gap-2">
                                    <span>{{ $student->name }}</span>
                                    @if($isMe)
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-amber-500 text-white">Kamu ⭐</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-xl text-[10px] font-black bg-slate-100 text-slate-700">
                                    Kelas {{ $student->kelas ?? '-' }} SD
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                Level {{ $student->level ?? 1 }}
                            </td>
                            <td class="px-6 py-4 text-right font-black text-amber-600 text-sm">
                                {{ number_format($student->total_score) }} Poin
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection
