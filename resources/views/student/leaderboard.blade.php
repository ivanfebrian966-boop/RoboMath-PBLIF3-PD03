@extends('layouts.app')

@section('title', 'Papan Peringkat - RoboMath')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-amber-500 via-yellow-500 to-orange-500 p-8 text-white shadow-xl">
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-center md:text-left space-y-2">
                <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-black uppercase tracking-wider">
                    🏆 Hall of Fame RoboMath
                </span>
                <h1 class="text-3xl md:text-4xl font-black tracking-tight">Papan Peringkat Juara</h1>
                <p class="text-amber-100 font-medium text-sm max-w-md">
                    Kumpulkan bintang dan poin sebanyak mungkin dengan menyelesaikan tantangan matematika!
                </p>
            </div>
            
            @if(auth()->user()->isSiswa() && isset($userRank))
            <div class="bg-white/20 backdrop-blur-md border border-white/30 rounded-2xl p-4 text-center min-w-[140px] shadow-lg">
                <p class="text-xs font-bold uppercase tracking-wider text-amber-100">Peringkat Kamu</p>
                <div class="text-4xl font-black mt-1">#{{ $userRank }}</div>
                <p class="text-xs text-amber-200 mt-1 font-semibold">{{ number_format(auth()->user()->total_score ?? 0) }} Poin</p>
            </div>
            @endif
        </div>
        <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
    </div>

    <!-- Filter Kelas Tabs -->
    <div class="flex items-center justify-between flex-wrap gap-3 bg-white p-3 rounded-2xl border-2 border-amber-200 shadow-sm">
        <span class="text-xs font-black text-slate-500 uppercase px-3">Filter Kelas:</span>
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
                <p class="text-xs text-slate-500 font-semibold">Kelas {{ $second->kelas ?? '-' }}</p>
                <div class="mt-2 inline-flex items-center gap-1 text-amber-600 font-black text-xs md:text-sm bg-amber-50 px-2.5 py-1 rounded-xl">
                    ⭐ {{ number_format($second->total_score) }}
                </div>
            </div>
        </div>

        {{-- Juara 1 --}}
        @php $first = $leaderboard->get(0); @endphp
        <div class="bg-gradient-to-b from-amber-50 to-white rounded-3xl p-5 md:p-7 border-3 border-amber-400 shadow-xl text-center transform hover:-translate-y-2 transition duration-300 relative -mt-6">
            <div class="w-16 h-16 md:w-20 md:h-20 mx-auto rounded-full bg-amber-400 flex items-center justify-center text-3xl md:text-4xl font-black border-4 border-yellow-200 shadow-lg -mt-14 md:-mt-16 bg-white animate-bounce">
                👑
            </div>
            <div class="mt-3">
                <span class="inline-block px-3 py-0.5 bg-amber-400 text-amber-950 rounded-full text-[10px] font-black uppercase">Juara 1</span>
                <h3 class="font-black text-amber-950 text-base md:text-lg truncate mt-1">{{ $first->name }}</h3>
                <p class="text-xs text-amber-700 font-semibold">Kelas {{ $first->kelas ?? '-' }}</p>
                <div class="mt-3 inline-flex items-center gap-1.5 text-amber-700 font-black text-sm md:text-base bg-amber-100/80 px-3 py-1 rounded-xl shadow-inner">
                    ⭐ {{ number_format($first->total_score) }} Poin
                </div>
            </div>
        </div>

        {{-- Juara 3 --}}
        @php $third = $leaderboard->get(2); @endphp
        <div class="bg-white rounded-3xl p-4 md:p-6 border-2 border-amber-200 shadow-md text-center transform hover:-translate-y-1 transition duration-300 relative">
            <div class="w-12 h-12 md:w-16 md:h-16 mx-auto rounded-full bg-amber-100 flex items-center justify-center text-2xl md:text-3xl font-black border-4 border-amber-300 shadow-inner -mt-10 md:-mt-12 bg-white">
                🥉
            </div>
            <div class="mt-3">
                <span class="inline-block px-2 py-0.5 bg-amber-200 text-amber-800 rounded-full text-[10px] font-black uppercase">Juara 3</span>
                <h3 class="font-black text-slate-800 text-sm md:text-base truncate mt-1">{{ $third->name }}</h3>
                <p class="text-xs text-slate-500 font-semibold">Kelas {{ $third->kelas ?? '-' }}</p>
                <div class="mt-2 inline-flex items-center gap-1 text-amber-600 font-black text-xs md:text-sm bg-amber-50 px-2.5 py-1 rounded-xl">
                    ⭐ {{ number_format($third->total_score) }}
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Full Leaderboard Table -->
    <div class="bg-white rounded-3xl border-2 border-amber-200 overflow-hidden shadow-sm">
        <div class="p-5 bg-gradient-to-r from-amber-50 to-orange-50 border-b-2 border-amber-100 flex items-center justify-between">
            <h2 class="font-black text-amber-950 text-base">Daftar Peringkat</h2>
            <span class="text-xs font-bold text-slate-500">Menampilkan {{ $leaderboard->count() }} Siswa</span>
        </div>

        @if($leaderboard->isEmpty())
        <div class="p-12 text-center">
            <span class="text-5xl">🎯</span>
            <p class="mt-3 font-bold text-slate-600">Belum ada data nilai untuk filter ini.</p>
        </div>
        @else
        <div class="divide-y divide-amber-100">
            @foreach($leaderboard as $index => $student)
            @php $isCurrentUser = auth()->check() && auth()->id() === $student->id; @endphp
            <div class="p-4 flex items-center justify-between gap-4 transition-colors {{ $isCurrentUser ? 'bg-amber-100/60 border-l-4 border-l-amber-500' : 'hover:bg-slate-50' }}">
                <div class="flex items-center gap-4 min-w-0">
                    <div class="w-9 h-9 rounded-2xl flex items-center justify-center font-black text-sm {{ $index == 0 ? 'bg-amber-400 text-amber-950' : ($index == 1 ? 'bg-slate-300 text-slate-800' : ($index == 2 ? 'bg-amber-200 text-amber-900' : 'bg-slate-100 text-slate-600')) }}">
                        {{ $index + 1 }}
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="font-black text-slate-800 truncate text-sm {{ $isCurrentUser ? 'text-amber-950 font-black' : '' }}">
                                {{ $student->name }}
                            </p>
                            @if($isCurrentUser)
                            <span class="px-2 py-0.5 bg-amber-500 text-white rounded-full text-[10px] font-black">Kamu</span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-400 font-semibold">Kelas {{ $student->kelas ?? '-' }} • Level {{ $student->level ?? 1 }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 font-black text-amber-600 text-sm">
                    <span>⭐</span>
                    <span>{{ number_format($student->total_score) }}</span>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>
@endsection
