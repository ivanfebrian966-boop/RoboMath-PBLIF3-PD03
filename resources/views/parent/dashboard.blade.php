@extends('layouts.app')

@section('content')
<div class="space-y-8">
    
    <!-- Parent Banner -->
    <div class="bg-gradient-to-r from-sky-600 via-indigo-600 to-purple-600 rounded-3xl p-8 text-white shadow-xl space-y-2">
        <span class="bg-white/20 text-white font-extrabold text-xs px-3 py-1 rounded-full uppercase">Dashboard Orang Tua</span>
        <h1 class="text-3xl sm:text-4xl font-black">Pantau Progres Belajar Anak 👨‍👩‍👧‍👦</h1>
        <p class="text-sky-100 font-semibold text-base">Lihat aktivitas belajar, lencana yang diraih, dan skor latihan putra-putri Anda secara real-time.</p>
    </div>

    <!-- Children Progress Cards -->
    <div class="space-y-6">
        @forelse($childrenData as $child)
            <div class="bg-white rounded-3xl border-4 border-indigo-100 p-6 sm:p-8 space-y-6 shadow-lg">
                
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b pb-4">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-2xl font-black shadow-md">
                            {{ strtoupper(substr($child->name, 0, 1)) }}
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-slate-900">{{ $child->name }}</h2>
                            <p class="text-xs font-bold text-slate-500">Kelas {{ $child->kelas }} SD</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="bg-amber-50 border border-amber-300 text-amber-900 font-black px-4 py-2 rounded-2xl text-sm">
                            🏆 {{ $child->total_score }} Total Poin
                        </div>
                        <div class="bg-indigo-50 border border-indigo-300 text-indigo-900 font-black px-4 py-2 rounded-2xl text-sm">
                            🎖️ Level {{ $child->level }}
                        </div>
                    </div>
                </div>

                <!-- Stats summary -->
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                        <span class="text-xs font-black uppercase text-slate-400">Akurasi Soal</span>
                        <div class="text-2xl font-black text-emerald-600 mt-1">{{ $child->accuracy }}%</div>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                        <span class="text-xs font-black uppercase text-slate-400">Modul Selesai</span>
                        <div class="text-2xl font-black text-indigo-600 mt-1">{{ $child->completed_lessons }}</div>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                        <span class="text-xs font-black uppercase text-slate-400">Lencana Bintang</span>
                        <div class="text-2xl font-black text-rose-600 mt-1">{{ $child->total_badges }}</div>
                    </div>
                </div>

            </div>
        @empty
            <div class="bg-white rounded-3xl border-2 border-slate-200 p-8 text-center text-slate-500 space-y-2">
                <p class="text-2xl">👨‍👩‍👧‍👦</p>
                <p class="font-extrabold text-base">Belum ada akun anak yang terhubung.</p>
                <p class="text-xs">Hubungi admin atau guru sekolah untuk menghubungkan akun anak Anda.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
