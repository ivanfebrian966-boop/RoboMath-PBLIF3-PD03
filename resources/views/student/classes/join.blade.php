@extends('layouts.app')

@section('title', 'Masukan Kode Kelas - RoboMath')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 sm:space-y-8 pb-12">

    <!-- 1. Header Hero Banner (Teal & Emerald Sesuai Mockup RoboMath) -->
    <div class="relative overflow-hidden rounded-[28px] sm:rounded-[32px] bg-gradient-to-r from-[#00A88F] via-[#059669] to-[#10B981] p-6 sm:p-8 text-white shadow-[0_8px_24px_rgba(0,168,143,0.22)] border border-teal-300/30">
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="space-y-2 max-w-xl">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-black uppercase tracking-wider text-teal-100 border border-white/20">
                    <span>✨</span> Buka Topik & Paket Soal AI
                </span>
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-black tracking-tight text-white">
                    Masukan Kode Kelas dari Gurumu
                </h1>
                <p class="text-teal-50 text-xs sm:text-sm font-medium leading-relaxed">
                    Masukkan 6 digit kode unik yang diberikan oleh bapak/ibu gurumu untuk langsung membuka topik pembelajaran dan paket soal latihan matematika berbasis AI!
                </p>
            </div>

            <div class="hidden sm:flex flex-col items-center justify-center w-24 h-24 rounded-3xl bg-white/15 backdrop-blur-md border border-white/30 text-5xl flex-shrink-0 shadow-inner">
                🏫
            </div>
        </div>

        {{-- Lingkaran dekoratif blur --}}
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
    </div>

    @if(session('success'))
        <div class="p-4 sm:p-5 rounded-2xl bg-emerald-50 border-2 border-emerald-300 text-emerald-900 font-bold flex items-center gap-3.5 shadow-xs">
            <span class="text-3xl flex-shrink-0">🎉</span>
            <div class="text-xs sm:text-sm">
                <span class="font-black text-emerald-950 block">Berhasil Bergabung!</span>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('info'))
        <div class="p-4 sm:p-5 rounded-2xl bg-amber-50 border-2 border-amber-300 text-amber-900 font-bold flex items-center gap-3.5 shadow-xs">
            <span class="text-3xl flex-shrink-0">ℹ️</span>
            <div class="text-xs sm:text-sm">
                <span class="font-black text-amber-950 block">Informasi Kelas</span>
                <span>{{ session('info') }}</span>
            </div>
        </div>
    @endif

    <!-- 2. Card Input Kode Kelas Unik -->
    <div class="bg-white rounded-[28px] sm:rounded-[32px] border-2 border-teal-200/90 p-6 sm:p-8 shadow-sm">
        <div class="max-w-lg mx-auto text-center space-y-5">
            <div class="space-y-1">
                <span class="inline-block p-3 rounded-2xl bg-teal-50 text-[#00A88F] text-2xl font-black mb-1">
                    🔑
                </span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-800">
                    Punya Kode Kelas dari Guru?
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 font-medium">
                    Ketik 6 digit kode unik di bawah ini (huruf kapital dan angka):
                </p>
            </div>

            <form action="{{ route('siswa.kelas.join.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-2">
                    <label for="code" class="sr-only">Kode Unik Kelas (6 Karakter)</label>
                    <div class="relative">
                        <input type="text" 
                               id="code" 
                               name="code" 
                               maxlength="6"
                               placeholder="RM7A9K" 
                               value="{{ old('code') }}"
                               oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')"
                               class="w-full text-center tracking-[0.35em] sm:tracking-[0.5em] text-3xl sm:text-4xl font-black uppercase py-4 px-6 rounded-2xl border-2 border-teal-300 bg-teal-50/30 text-slate-900 placeholder:text-slate-300 focus:bg-white focus:border-[#00A88F] focus:ring-4 focus:ring-teal-100 outline-none transition-all shadow-inner font-mono"
                               required 
                               autocomplete="off"
                               autofocus>
                    </div>

                    @error('code')
                        <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 text-xs font-bold text-left flex items-center gap-2">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <button type="submit" 
                        class="w-full py-4 px-6 bg-gradient-to-r from-[#00A88F] to-[#059669] hover:from-[#00907A] hover:to-[#047857] text-white font-black text-sm sm:text-base rounded-2xl shadow-[0_8px_20px_rgba(0,168,143,0.32)] hover:shadow-xl transition-all duration-200 transform active:scale-95 flex items-center justify-center gap-2 cursor-pointer">
                    <span>🚀 Masuk Kelas & Buka Soal AI</span>
                </button>
            </form>

            <div class="pt-2 text-center">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-50 border border-slate-200/80 text-[11px] font-bold text-slate-500">
                    💡 Kode berupa 6 huruf/angka unik yang dibuat oleh guru saat mengajar.
                </span>
            </div>
        </div>
    </div>

    <!-- 3. Tiga Langkah Membuka Soal AI -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-2xs space-y-2">
            <div class="w-9 h-9 rounded-xl bg-orange-100 text-orange-600 font-black text-sm flex items-center justify-center">
                1
            </div>
            <h3 class="font-black text-slate-800 text-sm">Dapatkan Kode Guru</h3>
            <p class="text-xs text-slate-500 font-medium leading-relaxed">
                Minta 6 digit kode unik kepada gurumu saat membuat kelas matematika.
            </p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-2xs space-y-2">
            <div class="w-9 h-9 rounded-xl bg-teal-100 text-[#00A88F] font-black text-sm flex items-center justify-center">
                2
            </div>
            <h3 class="font-black text-slate-800 text-sm">Masukkan & Gabung</h3>
            <p class="text-xs text-slate-500 font-medium leading-relaxed">
                Ketik kodenya di formulir atas dan klik tombol untuk memverifikasi kelasmu.
            </p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-2xs space-y-2">
            <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 font-black text-sm flex items-center justify-center">
                3
            </div>
            <h3 class="font-black text-slate-800 text-sm">Kerjakan Soal AI</h3>
            <p class="text-xs text-slate-500 font-medium leading-relaxed">
                Topik dan soal latihan AI otomatis terbuka dan bisa langsung kamu kerjakan!
            </p>
        </div>
    </div>

    <!-- 4. Daftar Kelas & Topik Soal AI yang Sudah Kamu Buka -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 flex items-center gap-2">
                    <span>📚</span> Kelas & Paket Soal AI Kamu
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">
                    Daftar kelas yang kamu ikuti beserta paket soal AI yang telah disiapkan gurumu.
                </p>
            </div>
            <span class="text-xs font-black px-3 py-1 rounded-full bg-teal-50 text-[#00A88F] border border-teal-200">
                {{ $myClasses->count() }} Kelas Diikuti
            </span>
        </div>

        @if($myClasses->isEmpty())
            <div class="p-8 sm:p-12 text-center bg-white rounded-3xl border-2 border-dashed border-slate-200 space-y-3">
                <span class="text-5xl block">🎒</span>
                <h3 class="font-black text-slate-800 text-base sm:text-lg">Kamu Belum Bergabung ke Kelas Mana Pun</h3>
                <p class="text-xs sm:text-sm text-slate-500 font-medium max-w-md mx-auto leading-relaxed">
                    Minta kode unik kelas kepada bapak/ibu gurumu untuk membuka paket soal latihan matematika berbasis AI!
                </p>
            </div>
        @else
            <div class="space-y-6">
                @foreach($myClasses as $class)
                    <div class="bg-white rounded-3xl border-2 border-slate-200/90 p-5 sm:p-6 shadow-sm space-y-5">
                        
                        {{-- Header Kelas --}}
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-3">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="px-2.5 py-0.5 bg-blue-100 text-blue-700 font-black rounded-lg text-xs">
                                        Kelas {{ $class->kelas_level }} SD
                                    </span>
                                    <span class="px-2.5 py-0.5 bg-slate-100 text-slate-600 font-mono font-bold text-xs rounded-lg border border-slate-200">
                                        Kode: {{ $class->code }}
                                    </span>
                                </div>
                                <h3 class="font-black text-slate-900 text-lg sm:text-xl">{{ $class->name }}</h3>
                                <p class="text-xs text-slate-500 font-medium flex items-center gap-1.5">
                                    <span>Guru Pengajar:</span>
                                    <strong class="text-slate-700">{{ $class->teacher->name ?? 'Bapak/Ibu Guru' }}</strong>
                                </p>
                            </div>

                            <div class="self-start sm:self-auto text-left sm:text-right">
                                <span class="text-[11px] font-bold text-slate-400 block">Status:</span>
                                <span class="inline-flex items-center gap-1 text-xs font-black text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                                    ✓ Aktif Terhubung
                                </span>
                            </div>
                        </div>

                        {{-- Daftar Topik Soal AI Dalam Kelas Ini --}}
                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-black text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                                    <span>🤖</span> Topik Soal AI yang Ditugaskan ({{ $class->topics->count() }} Topik)
                                </span>
                            </div>

                            @if($class->topics->isEmpty())
                                <div class="p-5 rounded-2xl bg-amber-50/60 border border-amber-200/80 text-center space-y-1">
                                    <p class="text-xs font-bold text-amber-900">Gurumu belum menambahkan topik materi untuk kelas ini.</p>
                                    <p class="text-[11px] text-amber-700">Topik latihan soal AI akan muncul secara otomatis di sini setelah disiapkan oleh gurumu.</p>
                                </div>
                            @else
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                                    @foreach($class->topics as $topic)
                                        @php
                                            $totalQuizzes = $topic->quizzes_count ?? $topic->quizzes->count();
                                            $completedCount = $completedQuizCountByTopic[$topic->id] ?? 0;
                                            $isCompleted = ($totalQuizzes > 0 && $completedCount >= $totalQuizzes);
                                        @endphp

                                        <div class="p-4 rounded-2xl border-2 {{ $isCompleted ? 'border-emerald-200 bg-emerald-50/30' : 'border-slate-200 bg-slate-50/50 hover:border-teal-300' }} transition-all flex flex-col justify-between gap-3">
                                            
                                            <div class="space-y-2">
                                                <div class="flex items-start justify-between gap-2">
                                                    <div class="flex items-center gap-2.5 min-w-0">
                                                        <span class="text-2xl flex-shrink-0">{{ $topic->icon ?? '📘' }}</span>
                                                        <div class="min-w-0">
                                                            <h4 class="font-black text-slate-800 text-sm truncate" title="{{ $topic->title }}">
                                                                {{ $topic->title }}
                                                            </h4>
                                                            <span class="text-[10px] font-bold text-slate-400 block uppercase">
                                                                {{ $topic->category_label ?? ($topic->category ?: 'Matematika') }}
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-black bg-blue-50 text-blue-700 border border-blue-200 flex-shrink-0">
                                                        🤖 Topik AI
                                                    </span>
                                                </div>

                                                <p class="text-xs text-slate-500 font-medium line-clamp-2 leading-relaxed">
                                                    {{ $topic->description ?: 'Latihan soal logika matematika interaktif dari dokumen materi gurumu.' }}
                                                </p>
                                            </div>

                                            <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between gap-3">
                                                <div class="text-xs">
                                                    <span class="text-[11px] font-bold text-slate-400">Total Soal:</span>
                                                    <strong class="font-black text-slate-800">{{ $totalQuizzes }} Butir</strong>
                                                </div>

                                                @if($totalQuizzes > 0)
                                                    <a href="{{ route('siswa.quiz.play', $topic) }}"
                                                       class="px-4 py-2 rounded-xl text-xs font-black {{ $isCompleted ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-[#00A88F] hover:bg-[#00907A]' }} text-white shadow-xs transition-transform active:scale-95 flex items-center gap-1.5 flex-shrink-0">
                                                        <span>{{ $isCompleted ? '✓ Lihat Hasil' : 'Mulai Soal AI 🚀' }}</span>
                                                    </a>
                                                @else
                                                    <span class="px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-200 text-slate-500 cursor-not-allowed">
                                                        Belum Ada Soal
                                                    </span>
                                                @endif
                                            </div>

                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
