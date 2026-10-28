@extends('layouts.app')

@section('title', 'Dashboard Siswa - RoboMath')

@section('content')
<div class="space-y-8">
    
    <!-- Welcome Header Banner (Merah - sesuai warna sidebar Dashboard Siswa) -->
    <div class="relative overflow-hidden rounded-[28px] bg-gradient-to-r from-[#FF3B30] via-[#FF4D42] to-[#FF6057] p-6 sm:p-8 text-white shadow-[0_8px_24px_rgba(255,59,48,0.22)] border border-red-400/40">
        <div class="relative z-10 space-y-2 max-w-xl">
            <h1 class="text-3xl sm:text-4xl font-black">Halo, {{ $user->name }}!</h1>
            <p class="text-red-100 font-semibold text-base">
                Siap petualangan matematika hari ini? Selesaikan soal kuis, kumpulkan lencana, dan raih skor tertinggi!
            </p>
        </div>
        <div class="absolute -right-8 -bottom-8 w-44 h-44 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
    </div>

    <!-- Quick Menu Navigasi Utama Siswa -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('siswa.quiz') }}" class="group bg-white p-5 rounded-3xl border-2 border-blue-200 hover:border-blue-500 shadow-sm transition transform hover:-translate-y-1 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-2xl group-hover:scale-110 transition flex-shrink-0">
                🧩
            </div>
            <div>
                <h4 class="font-black text-slate-800 text-sm">Latihan Soal AI</h4>
                <p class="text-xs text-slate-500 font-medium">Uji kemampuan & terima pembahasan</p>
            </div>
        </a>

        <a href="{{ route('siswa.kelas.join') }}" class="group bg-white p-5 rounded-3xl border-2 border-teal-200 hover:border-teal-500 shadow-sm transition transform hover:-translate-y-1 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-teal-100 text-[#00A88F] flex items-center justify-center text-2xl group-hover:scale-110 transition flex-shrink-0">
                🔑
            </div>
            <div>
                <h4 class="font-black text-slate-800 text-sm">Masukan Kode Kelas</h4>
                <p class="text-xs text-slate-500 font-medium">Buka soal topik AI dari gurumu</p>
            </div>
        </a>

        <a href="{{ route('siswa.leaderboard') }}" class="group bg-white p-5 rounded-3xl border-2 border-amber-200 hover:border-amber-500 shadow-sm transition transform hover:-translate-y-1 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-2xl group-hover:scale-110 transition flex-shrink-0">
                📊
            </div>
            <div>
                <h4 class="font-black text-slate-800 text-sm">Progress & Leaderboard</h4>
                <p class="text-xs text-slate-500 font-medium">Grafik progres & peringkat juara</p>
            </div>
        </a>

        <a href="{{ route('profil.show') }}" class="group bg-white p-5 rounded-3xl border-2 border-emerald-200 hover:border-emerald-500 shadow-sm transition transform hover:-translate-y-1 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl group-hover:scale-110 transition flex-shrink-0">
                👤
            </div>
            <div>
                <h4 class="font-black text-slate-800 text-sm">Profil Akun Saya</h4>
                <p class="text-xs text-slate-500 font-medium">Ubah nama, email, dan sandi</p>
            </div>
        </a>
    </div>

    <!-- Ringkasan Progres & Skor -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- Poin -->
        <div class="bg-white p-5 rounded-3xl border-2 border-amber-200 shadow-sm space-y-1">
            <div class="flex items-center justify-between text-slate-400 font-extrabold text-xs uppercase">
                <span>Total Poin</span>
                <span class="text-2xl">🏆</span>
            </div>
            <div class="text-3xl font-black text-amber-600">{{ number_format($user->total_score) }}</div>
            <div class="text-xs text-slate-500 font-semibold">Level {{ $user->level }} ({{ $user->level_name }})</div>
        </div>

        <!-- Akurasi Soal -->
        <div class="bg-white p-5 rounded-3xl border-2 border-emerald-200 shadow-sm space-y-1">
            <div class="flex items-center justify-between text-slate-400 font-extrabold text-xs uppercase">
                <span>Akurasi Soal</span>
                <span class="text-2xl">🎯</span>
            </div>
            <div class="text-3xl font-black text-emerald-600">{{ $accuracy }}%</div>
            <div class="text-xs text-slate-500 font-semibold">{{ $totalAttempts }} soal dikerjakan</div>
        </div>

        <!-- Materi Selesai -->
        <div class="bg-white p-5 rounded-3xl border-2 border-indigo-200 shadow-sm space-y-1">
            <div class="flex items-center justify-between text-slate-400 font-extrabold text-xs uppercase">
                <span>Materi Tuntas</span>
                <span class="text-2xl">📚</span>
            </div>
            <div class="text-3xl font-black text-indigo-600">{{ $completedLessons }} / {{ $totalLessons }}</div>
            <div class="text-xs text-slate-500 font-semibold">Modul dikuasai</div>
        </div>

        <!-- Lencana -->
        <div class="bg-white p-5 rounded-3xl border-2 border-rose-200 shadow-sm space-y-1">
            <div class="flex items-center justify-between text-slate-400 font-extrabold text-xs uppercase">
                <span>Lencana Koleksi</span>
                <span class="text-2xl">🎖️</span>
            </div>
            <div class="text-3xl font-black text-rose-600">{{ $totalBadges }} / {{ $allBadges->count() }}</div>
            <div class="text-xs text-slate-500 font-semibold">Bintang prestasi</div>
        </div>
    </div>

    <!-- AI Recommendation Callout if there are weak topics -->
    @if($weakTopics->count() > 0)
        <div class="bg-amber-50 border-2 border-amber-300 p-6 rounded-3xl space-y-3 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-amber-400 p-0.5 shadow-sm overflow-hidden flex-shrink-0">
                    <img src="{{ asset('images/maskot.png') }}" alt="RoboBot AI" class="w-full h-full object-contain rounded-xl">
                </div>
                <div>
                    <h3 class="font-extrabold text-amber-900 text-lg">Rekomendasi Latihan dari AI RoboBot!</h3>
                    <p class="text-xs text-amber-800 font-semibold">Berdasarkan hasil latihan kamu, yuk tingkatkan kemampuan di topik berikut:</p>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
                @foreach($weakTopics as $weak)
                    <a href="{{ route('siswa.quiz.play', $weak) }}" class="bg-white p-4 rounded-2xl border border-amber-200 hover:border-amber-400 shadow-sm flex items-center justify-between font-bold text-slate-800 transition-all">
                        <span>{{ $weak->icon }} {{ $weak->title }}</span>
                        <span class="text-xs text-rose-500 font-black">{{ $weak->accuracy }}% Akurasi</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- KOLEKSI BADGE (GABUNGAN DASHBOARD + KOLEKSI BADGE) -->
    <div class="bg-white rounded-3xl border-2 border-rose-200 p-6 sm:p-8 space-y-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-rose-100 pb-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 flex items-center gap-2">
                    <span>🎖️</span> Koleksi Lencana & Prestasi Kamu
                </h2>
                <p class="text-xs text-slate-500 font-semibold mt-0.5">Kumpulkan semua lencana prestasi dengan rajin berlatih matematika.</p>
            </div>
            <span class="text-xs font-black px-3 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200 self-start sm:self-auto">
                {{ $totalBadges }} dari {{ $allBadges->count() }} Terbuka
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($allBadges as $b)
                @php $isEarned = in_array($b->id, $earnedIds); @endphp
                <div class="p-4 rounded-2xl border-2 text-center transition-all relative group {{ $isEarned ? 'bg-amber-50/70 border-amber-300 shadow-sm' : 'bg-slate-50/60 border-slate-200/80 opacity-60 grayscale' }}">
                    <div class="text-4xl mb-2 group-hover:scale-110 transition transform">
                        {{ $b->icon ?? '⭐' }}
                    </div>
                    <div class="font-black text-xs text-slate-800 leading-tight">{{ $b->name }}</div>
                    <div class="text-[10px] text-slate-500 mt-1 line-clamp-2">{{ $b->description }}</div>
                    <div class="mt-2">
                        @if($isEarned)
                            <span class="inline-block px-2 py-0.5 rounded-full text-[9px] font-black bg-emerald-100 text-emerald-800">Diraih ✨</span>
                        @else
                            <span class="inline-block px-2 py-0.5 rounded-full text-[9px] font-black bg-slate-200 text-slate-600">Terkunci 🔒</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modul Pembelajaran Matematika SD -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-xl sm:text-2xl font-black text-slate-900">Modul Belajar Matematika SD 📖</h2>
            <a href="{{ route('siswa.topics') }}" class="font-extrabold text-indigo-600 hover:underline text-sm">Lihat Semua Modul &rarr;</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($topics as $topic)
                <div class="bg-white rounded-3xl border-2 border-slate-200 p-6 space-y-4 shadow-sm flex flex-col justify-between hover:shadow-md transition" style="border-top-color: {{ $topic->color }}; border-top-width: 6px;">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-4xl">{{ $topic->icon }}</span>
                            <span class="text-xs font-black px-3 py-1 rounded-full bg-slate-100 text-slate-600">
                                {{ $topic->lessons_count }} Materi
                            </span>
                        </div>
                        <h3 class="font-black text-xl text-slate-800">{{ $topic->title }}</h3>
                        <p class="text-xs text-slate-500 font-semibold leading-relaxed line-clamp-2">
                            {{ $topic->description }}
                        </p>
                    </div>

                    <!-- Progress Bar -->
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs font-black text-slate-600">
                            <span>Progress Belajar</span>
                            <span>{{ $topic->progress_percent }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                            <div class="bg-indigo-600 h-full rounded-full transition-all duration-500" style="width: {{ $topic->progress_percent }}%"></div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 mt-2">
                            <a href="{{ route('siswa.topics.show', $topic) }}" class="text-center bg-slate-100 hover:bg-slate-200 font-extrabold text-xs py-2.5 rounded-xl transition">
                                Baca Materi 📖
                            </a>
                            <a href="{{ route('siswa.quiz.play', $topic) }}" class="text-center bg-[#FF9500] hover:bg-[#e08600] text-white font-extrabold text-xs py-2.5 rounded-xl transition shadow-sm">
                                Main Kuis 🚀
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
