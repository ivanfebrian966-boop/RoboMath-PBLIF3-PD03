@extends('layouts.app')

@section('title', 'Progress & Leaderboard - RoboMath')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 sm:space-y-7 pb-10">

    <!-- 1. HEADER BANNER (Tone Kuning Visual RoboMath) -->
    <div class="relative overflow-hidden rounded-[28px] bg-gradient-to-r from-[#FFC72C] via-[#FFB800] to-[#FFAE00] p-6 sm:p-8 text-[#001738] shadow-[0_8px_24px_rgba(255,184,0,0.22)] border border-amber-300/60">
        <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
            <div class="space-y-1.5 max-w-xl">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-black tracking-tight text-[#001738]">
                    Progress & Leaderboard
                </h1>
                <p class="text-[#3B2900]/80 font-bold text-xs sm:text-sm leading-relaxed">
                    Pantau grafik perkembangan belajarmu dan raih posisi teratas di papan peringkat juara!
                </p>
            </div>
            
            @if(auth()->user()->isSiswa() && isset($userRank))
            <div class="bg-white rounded-2xl p-4 sm:p-5 text-center min-w-[130px] sm:min-w-[150px] shadow-sm border border-amber-200/60 flex-shrink-0 self-center sm:self-auto">
                <p class="text-[10px] font-black uppercase tracking-wider text-slate-500">Peringkat Kamu</p>
                <div class="text-3xl sm:text-4xl font-black text-[#001738] mt-0.5 leading-tight">#{{ $userRank }}</div>
                <p class="text-xs text-slate-500 mt-0.5 font-bold">{{ number_format(auth()->user()->total_score ?? 0) }} Poin</p>
            </div>
            @endif
        </div>
        {{-- Aksen Dekorasi Cahaya Halus --}}
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/20 rounded-full blur-2xl pointer-events-none"></div>
    </div>

    <!-- 2. TIGA KPI CARDS (Total Skor, Akurasi Soal, Ketuntasan Materi) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        {{-- Card 1: Total Skor --}}
        <div class="bg-white rounded-2xl p-4 sm:p-4.5 border-2 border-[#FDE68A] shadow-xs flex items-center gap-3.5 hover:shadow-md transition">
            <div class="w-12 h-12 rounded-xl bg-[#FF8A00] flex items-center justify-center text-[#001738] flex-shrink-0 shadow-2xs">
                <svg class="w-5 h-5 text-[#001738]" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <div class="text-[10px] font-black uppercase text-slate-500 tracking-wider">Total Skor</div>
                <div class="text-2xl font-black text-[#001738] leading-tight">{{ number_format(auth()->user()->total_score ?? 0) }}</div>
                <div class="text-[11px] text-slate-500 font-semibold truncate">Level {{ auth()->user()->level ?? 1 }} ({{ auth()->user()->level_name ?? 'Pemula' }})</div>
            </div>
        </div>

        {{-- Card 2: Akurasi Soal --}}
        <div class="bg-white rounded-2xl p-4 sm:p-4.5 border-2 border-[#A7F3D0] shadow-xs flex items-center gap-3.5 hover:shadow-md transition">
            <div class="w-12 h-12 rounded-xl bg-[#10B981] flex items-center justify-center text-white flex-shrink-0 shadow-2xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 3"/>
                </svg>
            </div>
            <div class="min-w-0">
                <div class="text-[10px] font-black uppercase text-slate-500 tracking-wider">Akurasi Soal</div>
                <div class="text-2xl font-black text-[#001738] leading-tight">{{ $userAccuracy }}%</div>
                <div class="text-[11px] text-slate-500 font-semibold truncate">{{ $correctAttempts }} benar dari {{ $totalAttempts }} soal</div>
            </div>
        </div>

        {{-- Card 3: Ketuntasan Materi --}}
        <div class="bg-white rounded-2xl p-4 sm:p-4.5 border-2 border-[#BFDBFE] shadow-xs flex items-center gap-3.5 hover:shadow-md transition">
            <div class="w-12 h-12 rounded-xl bg-[#3B82F6] flex items-center justify-center text-white flex-shrink-0 shadow-2xs">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div class="min-w-0">
                <div class="text-[10px] font-black uppercase text-slate-500 tracking-wider">Ketuntasan Materi</div>
                <div class="text-2xl font-black text-[#001738] leading-tight">{{ $progressPercent }}%</div>
                <div class="text-[11px] text-slate-500 font-semibold truncate">{{ $completedLessons }} dari {{ $totalLessons }} materi</div>
            </div>
        </div>
    </div>

    <!-- 3. KETUNTASAN PROGRES PER TOPIK -->
    <div class="bg-white rounded-[28px] border border-amber-200/70 p-6 sm:p-7 shadow-xs space-y-4">
        <h2 class="text-base sm:text-lg font-black text-[#001738]">
            Ketuntasan progres per topik
        </h2>

        @php
            $dotPalette = [
                0 => ['dot' => 'bg-[#EF4444]', 'bar' => 'bg-[#EF4444]'], // Merah
                1 => ['dot' => 'bg-[#F59E0B]', 'bar' => 'bg-[#F59E0B]'], // Oranye/Amber
                2 => ['dot' => 'bg-[#10B981]', 'bar' => 'bg-[#10B981]'], // Hijau
                3 => ['dot' => 'bg-[#3B82F6]', 'bar' => 'bg-[#3B82F6]'], // Biru
                4 => ['dot' => 'bg-[#8B5CF6]', 'bar' => 'bg-[#8B5CF6]'], // Ungu
            ];
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($topicProgress as $index => $tp)
                @php
                    $colors = $dotPalette[$index % count($dotPalette)];
                @endphp
                <div class="rounded-2xl border border-amber-100 bg-white p-4 sm:p-4.5 space-y-2.5 shadow-2xs hover:border-amber-300 transition">
                    <div class="flex items-center justify-between text-xs sm:text-sm font-black text-[#001738]">
                        <span class="flex items-center gap-2 truncate">
                            <span class="w-2.5 h-2.5 rounded-full {{ $colors['dot'] }} flex-shrink-0"></span>
                            <span class="truncate">{{ $tp->title }}</span>
                        </span>
                        <span class="font-black text-slate-700 ml-2">{{ $tp->percent }}%</span>
                    </div>

                    {{-- Track Progress Halus --}}
                    <div class="w-full bg-[#F5EFE6] rounded-full h-2 overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-500 {{ $colors['bar'] }}" style="width: {{ $tp->percent }}%"></div>
                    </div>

                    <div class="text-[11px] text-slate-400 font-semibold">
                        {{ $tp->completed_count }} / {{ $tp->lessons_count }} soal selesai
                    </div>
                </div>
            @empty
                <div class="col-span-full p-8 text-center text-slate-400 font-bold text-xs">
                    Belum ada topik materi yang tersedia.
                </div>
            @endforelse
        </div>
    </div>

    <!-- 4. FILTER KELAS & PAPAN PERINGKAT -->
    <div class="space-y-6 pt-2">
        <!-- Filter Baris Pill Sesuai Mockup -->
        <div class="flex items-center flex-wrap gap-2.5 items-center">
            <span class="text-[10px] font-black uppercase text-slate-500 tracking-wider mr-1">Filter Kelas:</span>
            
            <a href="{{ route('siswa.leaderboard', ['kelas' => 'all']) }}" 
               class="px-4 py-1.5 rounded-full text-xs font-black transition-all duration-200 {{ $kelasFilter === 'all' ? 'bg-[#001738] text-white shadow-xs' : 'bg-white border border-amber-200/80 text-slate-700 hover:border-amber-400 hover:text-amber-900' }}">
                Semua kelas
            </a>

            @for($k = 1; $k <= 6; $k++)
            <a href="{{ route('siswa.leaderboard', ['kelas' => $k]) }}" 
               class="px-4 py-1.5 rounded-full text-xs font-black transition-all duration-200 {{ (string)$kelasFilter === (string)$k ? 'bg-[#001738] text-white shadow-xs' : 'bg-white border border-amber-200/80 text-slate-700 hover:border-amber-400 hover:text-amber-900' }}">
                Kelas {{ $k }}
            </a>
            @endfor
        </div>

        <!-- 5. TOP 3 PODIUM CARDS SESUAI MOCKUP -->
        @php
            $first = $leaderboard->get(0);
            $second = $leaderboard->get(1);
            $third = $leaderboard->get(2);
        @endphp

        @if($first)
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5 pt-4 items-end">
            
            {{-- Juara 2 (Kiri - Biru Muda) --}}
            @if($second)
            <div class="order-2 sm:order-1 bg-[#F0F6FF] rounded-[24px] p-5 sm:p-6 border-2 border-[#DCE8FC] text-center flex flex-col justify-between items-center shadow-xs hover:-translate-y-1 transition duration-300 min-h-[175px]">
                <div class="w-9 h-9 rounded-full bg-[#D1E0F5] text-slate-700 font-black text-sm flex items-center justify-center mx-auto mb-2 shadow-2xs">
                    2
                </div>
                <div class="space-y-0.5 w-full">
                    <h3 class="font-black text-[#001738] text-sm sm:text-base truncate">{{ $second->name }}</h3>
                    <p class="text-xs text-slate-500 font-semibold">Kelas {{ $second->kelas ?? '-' }} SD</p>
                </div>
                <div class="w-full mt-3 bg-white text-slate-900 font-black text-xs py-2 px-4 rounded-full border border-blue-100 shadow-2xs">
                    {{ number_format($second->total_score) }} poin
                </div>
            </div>
            @else
            <div class="order-2 sm:order-1 opacity-0 pointer-events-none hidden sm:block"></div>
            @endif

            {{-- Juara 1 (Tengah - Kuning/Emas dengan Mahkota Mengambang Sesuai Mockup) --}}
            <div class="order-1 sm:order-2 bg-[#FFF9E6] rounded-[26px] p-5 sm:p-6 border-2 border-[#FDE68A] text-center flex flex-col justify-between items-center shadow-sm relative sm:-translate-y-2 hover:-translate-y-3 transition duration-300 min-h-[195px]">
                {{-- Mahkota Mengambang Tepat di Garis Atas Kartu Sesuai Mockup --}}
                <div class="absolute -top-4 sm:-top-4.5 left-1/2 -translate-x-1/2 w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-[#FF8A00] flex items-center justify-center border-2 border-white shadow-xs">
                    <svg class="w-4 h-4 text-[#001738]" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 17h16M4 17l2-7 4 3 2-5 2 5 4-3 2 7H4z" />
                    </svg>
                </div>

                {{-- Nomor 1 Squircle/Pill Badge --}}
                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-[#FF8A00] text-white font-black text-sm flex items-center justify-center mx-auto mt-1 mb-2 shadow-2xs">
                    1
                </div>

                <div class="space-y-0.5 w-full">
                    <h3 class="font-black text-[#001738] text-base sm:text-lg truncate">{{ $first->name }}</h3>
                    <p class="text-xs text-slate-500 font-semibold">Kelas {{ $first->kelas ?? '-' }} SD</p>
                </div>

                <div class="w-full mt-3 bg-white text-slate-900 font-black text-xs sm:text-sm py-2 px-4 rounded-full border border-amber-200 shadow-2xs">
                    {{ number_format($first->total_score) }} poin
                </div>
            </div>

            {{-- Juara 3 (Kanan - Peach/Oranye Lembut) --}}
            @if($third)
            <div class="order-3 sm:order-3 bg-[#FFF3E8] rounded-[24px] p-5 sm:p-6 border-2 border-[#FFDEC4] text-center flex flex-col justify-between items-center shadow-xs hover:-translate-y-1 transition duration-300 min-h-[175px]">
                <div class="w-9 h-9 rounded-full bg-[#FFCC9E] text-amber-950 font-black text-sm flex items-center justify-center mx-auto mb-2 shadow-2xs">
                    3
                </div>
                <div class="space-y-0.5 w-full">
                    <h3 class="font-black text-[#001738] text-sm sm:text-base truncate">{{ $third->name }}</h3>
                    <p class="text-xs text-slate-500 font-semibold">Kelas {{ $third->kelas ?? '-' }} SD</p>
                </div>
                <div class="w-full mt-3 bg-white text-slate-900 font-black text-xs py-2 px-4 rounded-full border border-orange-100 shadow-2xs">
                    {{ number_format($third->total_score) }} poin
                </div>
            </div>
            @else
            <div class="order-3 sm:order-3 opacity-0 pointer-events-none hidden sm:block"></div>
            @endif

        </div>
        @endif

        <!-- 6. TABEL TOP 50 PERINGKAT SISWA -->
        <div class="bg-white rounded-[28px] border border-amber-200/70 overflow-hidden shadow-xs">
            <div class="p-5 sm:p-6 border-b border-amber-100 flex items-center justify-between">
                <h3 class="font-black text-[#001738] text-sm sm:text-base">Top 50 Peringkat Siswa</h3>
                <span class="text-xs font-black px-3 py-1 rounded-full bg-[#FEF3C7] text-[#92400E]">
                    {{ $leaderboard->count() }} Siswa
                </span>
            </div>

            @if($leaderboard->isEmpty())
                <div class="p-12 text-center text-slate-400 font-semibold text-sm">
                    Belum ada siswa yang tercatat pada kelas ini.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-[#FAF6EF]/60 text-slate-400 uppercase font-black tracking-wider text-[10px] border-b border-amber-100">
                            <tr>
                                <th class="px-5 sm:px-6 py-3.5 w-20">Peringkat</th>
                                <th class="px-5 sm:px-6 py-3.5">Nama Siswa</th>
                                <th class="px-5 sm:px-6 py-3.5">Kelas</th>
                                <th class="px-5 sm:px-6 py-3.5">Level</th>
                                <th class="px-5 sm:px-6 py-3.5 text-right">Total Skor</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-amber-100/70 text-slate-700">
                            @foreach($leaderboard as $index => $student)
                            @php
                                $isMe = auth()->check() && auth()->id() === $student->id;
                                
                                // Inisial 2 Huruf untuk Avatar
                                $words = preg_split('/\s+/', trim($student->name));
                                $in1 = substr($words[0] ?? '', 0, 1);
                                $in2 = isset($words[1]) ? substr($words[1], 0, 1) : '';
                                $initials = strtoupper($in1 . $in2);

                                // Variasi Warna Avatar Lembut Sesuai Mockup
                                $avatarStyles = [
                                    0 => 'bg-[#FFEDD5] text-[#C2410C]',
                                    1 => 'bg-[#DCFCE7] text-[#15803D]',
                                    2 => 'bg-[#FCE7F3] text-[#BE185D]',
                                    3 => 'bg-[#DBEAFE] text-[#1D4ED8]',
                                    4 => 'bg-[#FEF3C7] text-[#B45309]',
                                    5 => 'bg-[#F3E8FF] text-[#7E22CE]',
                                ];
                                $avatarColor = $avatarStyles[$index % count($avatarStyles)];
                            @endphp
                            <tr class="transition-colors {{ $isMe ? 'bg-[#FFF9EC] border-l-4 border-[#FF8A00]' : 'hover:bg-amber-50/30' }}">
                                {{-- Kolom Peringkat (Squircle Badge) --}}
                                <td class="px-5 sm:px-6 py-4">
                                    @if($index === 0)
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-xl text-xs font-black bg-[#FDE047] text-[#713F12]">1</span>
                                    @elseif($index === 1)
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-xl text-xs font-black bg-[#E2E8F0] text-slate-700">2</span>
                                    @elseif($index === 2)
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-xl text-xs font-black bg-[#FED7AA] text-[#9A3412]">3</span>
                                    @else
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-xl text-xs font-bold bg-slate-100 text-slate-600">{{ $index + 1 }}</span>
                                    @endif
                                </td>

                                {{-- Kolom Nama Siswa & Badge Kamu --}}
                                <td class="px-5 sm:px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl {{ $avatarColor }} font-black text-xs flex items-center justify-center flex-shrink-0 shadow-2xs">
                                            {{ $initials ?: 'RM' }}
                                        </div>
                                        <div class="font-black text-[#001738] text-xs sm:text-sm flex items-center gap-2">
                                            <span>{{ $student->name }}</span>
                                            @if($isMe)
                                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-[#FF8A00] text-white shadow-2xs">Kamu</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Kolom Kelas (Pill Biru) --}}
                                <td class="px-5 sm:px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-[11px] font-black bg-[#E0F2FE] text-[#0284C7]">
                                        Kelas {{ $student->kelas ?? '-' }} SD
                                    </span>
                                </td>

                                {{-- Kolom Level --}}
                                <td class="px-5 sm:px-6 py-4 text-slate-500 font-bold text-xs">
                                    Level {{ $student->level ?? 1 }}
                                </td>

                                {{-- Kolom Total Skor --}}
                                <td class="px-5 sm:px-6 py-4 text-right font-black text-[#C2410C] text-xs sm:text-sm">
                                    {{ number_format($student->total_score) }} poin
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
