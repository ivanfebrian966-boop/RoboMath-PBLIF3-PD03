@extends('layouts.app')

@section('title', 'Profil Saya - RoboMath')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Profile Header Card -->
    <div class="rounded-3xl bg-gradient-to-br from-amber-400 via-orange-400 to-amber-500 p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full -translate-y-1/4 translate-x-1/4"></div>
        <div class="relative flex items-center gap-6">
            <div class="w-20 h-20 rounded-3xl bg-white/30 backdrop-blur flex items-center justify-center text-4xl font-black shadow-lg">
                {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-3xl font-black">{{ $user->name }}</h1>
                <p class="text-amber-100 mt-0.5">{{ $user->email }}</p>
                <div class="flex flex-wrap items-center gap-2 mt-2">
                    <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-black uppercase tracking-wider">
                        {{ ucfirst($user->role) }}
                    </span>
                    @if($user->isSiswa())
                    <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-black uppercase tracking-wider">
                        Kelas {{ $user->kelas }} SD
                    </span>
                    @endif
                    <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-black uppercase tracking-wider">
                        🏆 {{ $user->total_score ?? 0 }} poin
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Lengkap -->
    <div class="bg-white border-2 border-amber-200/70 rounded-3xl p-8 shadow-sm space-y-5">
        <h2 class="font-black text-slate-800 text-lg">📋 Informasi Akun</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <p class="text-xs font-black text-slate-500 uppercase tracking-wider mb-1">Nama Lengkap</p>
                <p class="font-bold text-slate-800">{{ $user->name }}</p>
            </div>
            <div>
                <p class="text-xs font-black text-slate-500 uppercase tracking-wider mb-1">Email</p>
                <p class="font-bold text-slate-800">{{ $user->email }}</p>
            </div>
            <div>
                <p class="text-xs font-black text-slate-500 uppercase tracking-wider mb-1">Peran</p>
                <p class="font-bold text-slate-800">{{ ucfirst($user->role) }}</p>
            </div>
            @if($user->isSiswa())
            <div>
                <p class="text-xs font-black text-slate-500 uppercase tracking-wider mb-1">Kelas</p>
                <p class="font-bold text-slate-800">Kelas {{ $user->kelas }} SD</p>
            </div>
            <div>
                <p class="text-xs font-black text-slate-500 uppercase tracking-wider mb-1">Total Poin</p>
                <p class="font-black text-amber-600 text-xl">{{ number_format($user->total_score ?? 0) }}</p>
            </div>
            <div>
                <p class="text-xs font-black text-slate-500 uppercase tracking-wider mb-1">Streak Saat Ini</p>
                <p class="font-black text-orange-500 text-xl">🔥 {{ $user->streak_saat_ini ?? 0 }} hari</p>
            </div>
            @endif
            <div>
                <p class="text-xs font-black text-slate-500 uppercase tracking-wider mb-1">Status Akun</p>
                @if($user->is_active)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-bold border border-emerald-200">● Aktif</span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 text-rose-700 rounded-full text-xs font-bold border border-rose-200">● Nonaktif</span>
                @endif
            </div>
            <div>
                <p class="text-xs font-black text-slate-500 uppercase tracking-wider mb-1">Bergabung</p>
                <p class="font-bold text-slate-800">{{ $user->created_at->format('d M Y') }}</p>
            </div>
        </div>

        <div class="pt-4 border-t border-amber-100">
            <a href="{{ route('profil.edit') }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-amber-400 hover:bg-amber-500 text-amber-900 font-black rounded-2xl shadow-sm transition-all duration-200 hover:scale-105">
                ✏️ Edit Profil
            </a>
        </div>
    </div>
</div>
@endsection
