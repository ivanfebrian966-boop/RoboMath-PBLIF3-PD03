@extends('layouts.app')

@section('title', 'Detail Tugas - Guru RoboMath')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('guru.tugas.index') }}" class="text-sm text-indigo-600 font-bold hover:underline">← Kembali</a>
            <h1 class="text-2xl font-black text-slate-900 mt-1">📋 {{ $tugas->judul }}</h1>
            <p class="text-sm text-slate-500 mt-0.5">
                Kelas: <strong>{{ $tugas->classRoom->name }}</strong>
                @if($tugas->tenggat_waktu)
                · Tenggat: <strong>{{ $tugas->tenggat_waktu->format('d M Y, H:i') }}</strong>
                @endif
            </p>
        </div>
        <span class="px-4 py-2 bg-amber-50 text-amber-700 font-bold rounded-2xl border border-amber-200 text-sm">
            🎯 KKM: {{ $tugas->nilai_lulus }}
        </span>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white border-2 border-amber-200/80 rounded-3xl p-6 shadow-sm text-center">
            <div class="text-3xl font-black text-slate-900">{{ $pengumpulan->count() }}</div>
            <p class="text-xs font-black text-slate-500 uppercase tracking-wider mt-1">Sudah Kumpul</p>
        </div>
        <div class="bg-white border-2 border-emerald-200/80 rounded-3xl p-6 shadow-sm text-center">
            <div class="text-3xl font-black text-emerald-600">{{ $pengumpulan->where('skor', '>=', $tugas->nilai_lulus)->where('skor', '!=', null)->count() }}</div>
            <p class="text-xs font-black text-slate-500 uppercase tracking-wider mt-1">Lulus</p>
        </div>
        <div class="bg-white border-2 border-rose-200/80 rounded-3xl p-6 shadow-sm text-center">
            <div class="text-3xl font-black text-rose-600">{{ $pengumpulan->where('skor', '<', $tugas->nilai_lulus)->where('skor', '!=', null)->count() }}</div>
            <p class="text-xs font-black text-slate-500 uppercase tracking-wider mt-1">Tidak Lulus</p>
        </div>
    </div>

    <!-- Daftar Pengumpulan -->
    <div class="bg-white border-2 border-amber-200/60 rounded-3xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-amber-100">
            <h2 class="font-black text-slate-800">📥 Daftar Pengumpulan Siswa</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-amber-50/80">
                    <tr>
                        <th class="text-left px-5 py-3 font-black text-slate-600 uppercase text-xs tracking-wider">Siswa</th>
                        <th class="text-left px-5 py-3 font-black text-slate-600 uppercase text-xs tracking-wider">Status</th>
                        <th class="text-left px-5 py-3 font-black text-slate-600 uppercase text-xs tracking-wider">Skor</th>
                        <th class="text-left px-5 py-3 font-black text-slate-600 uppercase text-xs tracking-wider">Dikumpul</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber-100/70">
                    @forelse($pengumpulan as $p)
                    <tr class="hover:bg-amber-50/40 transition-colors">
                        <td class="px-5 py-4">
                            <div class="font-bold text-slate-800">{{ $p->student->name }}</div>
                            <div class="text-xs text-slate-400">{{ $p->student->email }}</div>
                        </td>
                        <td class="px-5 py-4">
                            @if($p->status === 'dinilai')
                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-bold border border-emerald-100">✅ Dinilai</span>
                            @elseif($p->status === 'terlambat')
                                <span class="px-2.5 py-1 bg-rose-50 text-rose-700 rounded-full text-xs font-bold border border-rose-100">⏰ Terlambat</span>
                            @else
                                <span class="px-2.5 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-bold border border-blue-100">📤 Dikirim</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            @if($p->skor !== null)
                                <span class="font-black text-lg {{ $p->skor >= $tugas->nilai_lulus ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $p->skor }}
                                </span>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-slate-500">{{ $p->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-12 text-center text-slate-400">
                            <div class="text-4xl mb-2">📭</div>
                            <p class="font-bold">Belum ada siswa yang mengumpulkan tugas</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
