@extends('layouts.app')

@section('content')
<div class="space-y-8">
    
    <!-- User Level & Score Hero Banner -->
    <div class="bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 rounded-3xl p-8 text-white shadow-xl flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="space-y-2 text-center sm:text-left">
            <span class="bg-white/20 text-white font-extrabold text-xs px-3 py-1 rounded-full uppercase">Koleksi Bintang Prestasi</span>
            <h1 class="text-3xl sm:text-4xl font-black">Lencana & Level Prestasi 🏆</h1>
            <p class="text-amber-100 font-semibold text-base">Kumpulkan semua lencana dengan rajin membaca dan berlatih!</p>
        </div>

        <div class="bg-white text-slate-800 p-6 rounded-3xl shadow-xl text-center space-y-2 min-w-[200px] border-4 border-amber-200">
            <div class="text-4xl">🎖️</div>
            <div class="font-black text-2xl text-amber-600">Level {{ $user->level }}</div>
            <div class="text-xs font-black uppercase text-amber-800 bg-amber-100 px-3 py-1 rounded-full inline-block">
                {{ $user->level_name }} Tier
            </div>
            <p class="text-xs text-slate-500 font-bold pt-1">{{ $user->total_score }} Total Poin</p>
        </div>
    </div>

    <!-- Badges Grid -->
    <div class="space-y-4">
        <h2 class="text-2xl font-black text-slate-800">Daftar Lencana Bintang 🌟</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($allBadges as $badge)
                @php $isEarned = in_array($badge->id, $earnedIds); @endphp
                <div class="bg-white rounded-3xl border-2 p-6 text-center space-y-3 relative transition-all {{ $isEarned ? 'border-amber-300 shadow-md hover:shadow-xl' : 'border-slate-200 opacity-60 grayscale' }}">
                    
                    @if($isEarned)
                        <span class="absolute top-4 right-4 bg-emerald-500 text-white text-xs font-black px-2.5 py-1 rounded-full">
                            ✓ Diraih
                        </span>
                    @else
                        <span class="absolute top-4 right-4 bg-slate-200 text-slate-600 text-xs font-bold px-2.5 py-1 rounded-full">
                            🔒 Terkunci
                        </span>
                    @endif

                    <div class="w-20 h-20 mx-auto rounded-3xl bg-amber-50 flex items-center justify-center text-5xl border-2 border-amber-200 shadow-inner">
                        {{ $badge->icon }}
                    </div>

                    <h3 class="font-black text-lg text-slate-800">{{ $badge->name }}</h3>
                    <p class="text-xs text-slate-500 font-semibold leading-relaxed">
                        {{ $badge->description }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
