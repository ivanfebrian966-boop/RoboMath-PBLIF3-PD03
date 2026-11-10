@extends('layouts.app')

@section('title', 'Kelola Kelas - Guru RoboMath')

@section('content')
@php
    $kelasLevels = $classRooms->pluck('kelas_level')->unique()->sort()->values();

    // Tema warna per kartu sesuai gambar persis:
    // Kartu 1: Hijau pastel muda (#D1FBE5), badge hijau teks hijau (#007A4D)
    // Kartu 2: Biru pastel muda (#DBEAFE), badge biru teks biru (#2563EB)
    // Kartu 3: Kuning/cream pastel muda (#FEF3C7), badge kuning teks kecoklatan (#92400E)
    $cardThemes = [
        [
            'header_bg' => 'bg-[#D1FBE5]',
            'badge_bg' => 'bg-white',
            'badge_text' => 'text-[#007A4D]',
        ],
        [
            'header_bg' => 'bg-[#DBEAFE]',
            'badge_bg' => 'bg-white',
            'badge_text' => 'text-[#2563EB]',
        ],
        [
            'header_bg' => 'bg-[#FEF3C7]',
            'badge_bg' => 'bg-white',
            'badge_text' => 'text-[#92400E]',
        ],
    ];
@endphp

<div class="max-w-7xl mx-auto space-y-6" x-data="{
    filter: 'semua',
    search: '',
    filterClass(level, name, code) {
        const matchFilter = this.filter === 'semua' || this.filter == level;
        const q = this.search.toLowerCase().trim();
        const matchSearch = !q || name.toLowerCase().includes(q) || code.toLowerCase().includes(q);
        return matchFilter && matchSearch;
    }
}">

    {{-- Header Banner Hijau Gelap --}}
    <div class="rounded-[2.5rem] bg-[#007A4D] px-10 py-9 text-white shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl md:text-5xl font-black tracking-tight">Kelola Kelas</h1>
            <p class="text-emerald-100 text-sm md:text-base font-semibold mt-2.5">Buat kelas, bagikan kode ke siswa, lalu atur materi belajarnya.</p>
        </div>
        <div>
            <a href="{{ route('guru.classes.create') }}" class="inline-flex items-center gap-2 bg-white text-slate-900 px-7 py-3.5 rounded-full font-black text-sm shadow hover:bg-slate-50 transition active:scale-95 whitespace-nowrap">
                <span class="text-xl font-black leading-none text-slate-900">+</span>
                <span>Buat kelas baru</span>
            </a>
        </div>
    </div>

    {{-- Success Alert (dipertahankan untuk test) --}}
    @if(session('class_created'))
    <div role="alert" class="p-5 bg-emerald-50 border-2 border-emerald-300 text-emerald-900 rounded-3xl shadow-sm flex flex-col sm:flex-row sm:items-center gap-4">
        <span class="text-3xl" aria-hidden="true">✅</span>
        <div class="flex-1">
            <p class="font-extrabold">Kelas {{ session('class_created.name') }} berhasil dibuat!</p>
            <p class="text-sm text-emerald-800 mt-1">Bagikan kode ini kepada siswa agar mereka bisa bergabung:</p>
        </div>
        <div class="font-mono bg-white border-2 border-emerald-300 text-emerald-800 text-xl font-black tracking-widest rounded-2xl px-5 py-3">
            {{ session('class_created.code') }}
        </div>
    </div>
    @endif

    @if(session('success'))
    <div role="alert" class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-sm font-semibold">
        {{ session('success') }}
    </div>
    @endif

    {{-- Filter Bar & Search Container Putih --}}
    <div class="bg-white rounded-full border border-slate-200/80 p-2 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
        {{-- Filter Buttons --}}
        <div class="flex items-center gap-2 overflow-x-auto w-full sm:w-auto px-2">
            <button @click="filter = 'semua'"
                :class="filter === 'semua' ? 'bg-[#101828] text-white shadow' : 'bg-transparent text-slate-700 hover:bg-slate-100'"
                class="px-6 py-2.5 rounded-full text-xs md:text-sm font-black transition whitespace-nowrap">
                Semua
            </button>
            @foreach($kelasLevels as $lvl)
            <button @click="filter = '{{ $lvl }}'"
                :class="filter == '{{ $lvl }}' ? 'bg-[#101828] text-white shadow' : 'bg-transparent text-slate-700 hover:bg-slate-100'"
                class="px-6 py-2.5 rounded-full text-xs md:text-sm font-black transition whitespace-nowrap">
                Kelas {{ $lvl }}
            </button>
            @endforeach
        </div>

        {{-- Search Input Pill --}}
        <div class="relative w-full sm:w-96 pr-2">
            <span class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
            </span>
            <input type="text"
                x-model="search"
                placeholder="Cari nama atau kode"
                class="w-full pl-11 pr-5 py-2.5 bg-[#FAF7F2]/60 border border-slate-200/90 rounded-full text-xs md:text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition">
        </div>
    </div>

    {{-- Empty State --}}
    @if($classRooms->isEmpty())
    <div class="bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 p-12 text-center">
        <div class="w-16 h-16 rounded-full bg-[#D1FBE5] text-[#007A4D] flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M12 5v14m-7-7h14"/>
            </svg>
        </div>
        <h3 class="font-black text-slate-900 text-lg">Belum Ada Kelas yang Dibuat</h3>
        <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">Mulai dengan membuat kelas pertama Anda untuk mendapatkan kode kelas yang bisa Anda bagikan ke siswa.</p>
        <a href="{{ route('guru.classes.create') }}" class="mt-6 inline-flex items-center gap-2 bg-[#007A4D] hover:bg-emerald-800 text-white font-bold px-7 py-3 rounded-full text-sm transition">
            <span>+</span> Buat Kelas Sekarang
        </a>
    </div>
    @else

    {{-- Cards Grid (4 Kolom Sesuai Gambar) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        @foreach($classRooms as $class)
        @php
            $theme = $cardThemes[$loop->index % count($cardThemes)];
        @endphp

        <div x-show="filterClass('{{ $class->kelas_level }}', '{{ addslashes($class->name) }}', '{{ $class->code }}')"
             x-transition
             class="bg-white rounded-[2rem] border border-slate-200/90 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.04)] overflow-hidden flex flex-col justify-between hover:shadow-[0_8px_30px_-4px_rgba(0,0,0,0.08)] transition duration-200">

            {{-- Header Berwarna (Tanpa Icon Rumah, Sesuai Gambar Terbaru) --}}
            <div class="{{ $theme['header_bg'] }} p-6 pb-6 rounded-t-[2rem]">
                <h2 class="font-black text-slate-900 text-lg md:text-xl leading-tight line-clamp-2">
                    {{ $class->name }}
                </h2>
                <div class="flex items-center gap-3 mt-3">
                    <span class="inline-block {{ $theme['badge_bg'] }} {{ $theme['badge_text'] }} text-xs font-black px-3.5 py-1 rounded-full shadow-sm">
                        Kelas {{ $class->kelas_level }}
                    </span>
                    <span class="text-xs text-slate-600 font-semibold">
                        {{ $class->created_at->translatedFormat('d M Y') }}
                    </span>
                </div>
            </div>

            {{-- Card Body --}}
            <div class="p-5 pt-5 space-y-5 flex-1 flex flex-col justify-between">
                {{-- Box Kode Kelas (Border Dashed Kuning Cream Halus) --}}
                <div class="bg-white border border-dashed border-[#E5D7A7] rounded-2xl p-4 flex items-center justify-between">
                    <div>
                        <span class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1">KODE KELAS</span>
                        <span class="font-mono text-base md:text-lg font-black tracking-widest text-slate-900">
                            {{ $class->code }}
                        </span>
                    </div>
                    {{-- Tombol Copy Icon Saja Persegi Bulat (Sesuai Gambar) --}}
                    <button type="button"
                        title="Salin Kode"
                        onclick="navigator.clipboard.writeText('{{ $class->code }}'); this.classList.add('bg-emerald-50', 'border-emerald-400'); setTimeout(() => this.classList.remove('bg-emerald-50', 'border-emerald-400'), 1500)"
                        class="w-10 h-10 flex items-center justify-center bg-white border border-slate-200 hover:border-slate-300 rounded-full text-slate-600 shadow-sm transition active:scale-90">
                        <svg class="w-4 h-4 text-slate-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                        </svg>
                    </button>
                </div>

                {{-- Status Siswa --}}
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-[#EFF6FF] text-[#3B82F6] flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-black text-slate-900 text-sm">
                            {{ $class->students_count }} siswa
                        </div>
                        <p class="text-xs text-slate-400 font-semibold leading-relaxed">
                            @if($class->students_count > 0)
                                Siswa sudah bergabung.
                            @else
                                Bagikan kode agar siswa bisa bergabung.
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Action Buttons: Tombol Hitam "Lihat detail" & Tombol Hapus Outline --}}
                <div class="pt-2 flex items-center gap-2.5">
                    <a href="{{ route('guru.classes.show', $class) }}"
                        class="flex-1 inline-flex items-center justify-center py-3 px-4 bg-[#101828] hover:bg-slate-800 text-white rounded-full text-xs md:text-sm font-black transition shadow-sm">
                        Lihat detail
                    </a>

                    <form action="{{ route('guru.classes.destroy', $class) }}" method="POST" onsubmit="return confirm('Hapus kelas {{ $class->name }}? Siswa tidak akan lagi terdaftar di kelas ini.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            title="Hapus Kelas"
                            class="w-11 h-11 flex items-center justify-center rounded-full border border-slate-200 hover:border-rose-300 hover:bg-rose-50 text-slate-500 hover:text-rose-600 transition flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

        </div>
        @endforeach

        {{-- Card Tambah Kelas Baru (Dashed border tinggi penuh sesuai gambar) --}}
        <a href="{{ route('guru.classes.create') }}"
           class="group bg-white rounded-[2rem] border-2 border-dashed border-[#D2D6DC] hover:border-[#10B981] hover:bg-[#F0FDF4]/30 p-6 flex flex-col items-center justify-center text-center transition min-h-[360px]">
            <div class="w-16 h-16 rounded-full bg-[#D1FBE5] text-[#007A4D] flex items-center justify-center mb-5 transition transform group-hover:scale-110">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M12 5v14m-7-7h14"></path>
                </svg>
            </div>
            <h3 class="font-black text-slate-900 text-base md:text-lg">Buat kelas baru</h3>
            <p class="text-xs text-slate-400 font-semibold mt-1.5 max-w-[190px] leading-relaxed">
                Kode kelas dibuat otomatis, siap dibagikan ke siswa.
            </p>
        </a>
    </div>
    @endif

</div>
@endsection
