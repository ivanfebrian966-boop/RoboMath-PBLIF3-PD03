@extends('layouts.app')

@section('title', 'Detail Progress Siswa - Guru RoboMath')

@section('content')
<div class="mx-auto max-w-6xl space-y-3 sm:space-y-4" x-data="{ selectedCategory: 'all', showAllSessions: false }">
    <a href="{{ route('guru.leaderboard') }}" class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-white px-4 py-2 text-[10px] font-black text-slate-800 shadow-sm transition hover:bg-amber-50">
        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6M9 12h12"/></svg>
        Kembali ke Progress Siswa
    </a>

    <header class="flex flex-col justify-between gap-4 rounded-3xl bg-[#F59E0B] px-5 py-5 text-white shadow-lg sm:flex-row sm:items-center sm:px-7">
        <div class="space-y-1">
            <p class="text-[9px] font-black uppercase tracking-wider text-amber-50">Detail pemantauan siswa</p>
            <h1 class="text-2xl font-black leading-tight sm:text-3xl">{{ $student->name }}</h1>
            <p class="text-[10px] font-semibold text-amber-50">Kelas {{ $student->kelas ?? '-' }} SD · {{ $student->email }}</p>
        </div>
        <div class="flex items-center gap-2">
            <div class="min-w-[62px] rounded-2xl bg-white px-3 py-2 text-center text-slate-900">
                <p class="text-xl font-black leading-tight">{{ number_format($student->total_score) }}</p>
                <p class="text-[9px] font-bold text-slate-500">poin</p>
            </div>
            <div class="min-w-[62px] rounded-2xl bg-slate-900 px-3 py-2 text-center text-white">
                <p class="text-xl font-black leading-tight">Lv.{{ $student->level }}</p>
                <p class="text-[9px] font-bold text-slate-300">{{ $student->level === 1 ? 'Pemula' : $student->level_name }}</p>
            </div>
        </div>
    </header>

    <section class="grid grid-cols-2 overflow-hidden rounded-3xl border border-white bg-white shadow-sm sm:grid-cols-4" aria-label="Ringkasan progres siswa">
        <div class="border-b border-slate-100 px-4 py-3 sm:border-b-0 sm:border-r">
            <p class="text-[9px] font-black uppercase tracking-wider text-slate-500">Akurasi</p>
            <p class="mt-0.5 text-2xl font-black leading-tight text-blue-700">{{ $accuracy }}%</p>
        </div>
        <div class="border-b border-slate-100 px-4 py-3 sm:border-b-0 sm:border-r">
            <p class="text-[9px] font-black uppercase tracking-wider text-slate-500">Sesi belajar</p>
            <p class="mt-0.5 text-2xl font-black leading-tight text-amber-700">{{ $sessionCount }}</p>
        </div>
        <div class="border-r border-slate-100 px-4 py-3">
            <p class="text-[9px] font-black uppercase tracking-wider text-slate-500">Materi tuntas</p>
            <p class="mt-0.5 flex items-baseline gap-1 text-2xl font-black leading-tight text-emerald-700">{{ $completedLessons }} <span class="text-[10px] font-bold text-slate-500">modul</span></p>
        </div>
        <div class="px-4 py-3">
            <p class="text-[9px] font-black uppercase tracking-wider text-slate-500">Peringkat</p>
            <p class="mt-0.5 text-2xl font-black leading-tight text-rose-500">#{{ $rank }}</p>
        </div>
    </section>

    <div class="grid grid-cols-1 items-start gap-3 sm:gap-4 md:grid-cols-[minmax(0,1.8fr)_minmax(230px,1fr)]">
        <section id="session-history" class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-sm">
            <div class="flex items-center justify-between gap-3 bg-amber-100 px-4 py-3 sm:px-5">
                <h2 class="text-sm font-black text-slate-900 sm:text-base">Riwayat sesi belajar</h2>
                @if($recentSessions->count() > 6)
                    <button type="button" @click="showAllSessions = !showAllSessions" class="shrink-0 text-[9px] font-black text-amber-800 hover:text-amber-950" x-text="showAllSessions ? 'Tampilkan lebih sedikit' : 'Lihat semua {{ $recentSessions->count() }} sesi terbaru'"></button>
                @else
                    <span class="shrink-0 text-[9px] font-black text-amber-800">{{ $sessionCount }} sesi</span>
                @endif
            </div>

            <div class="flex flex-wrap gap-1.5 px-4 pb-2 pt-3 sm:px-5" aria-label="Filter sesi berdasarkan topik">
                <button type="button" @click="selectedCategory = 'all'; showAllSessions = false" :aria-pressed="selectedCategory === 'all'" class="rounded-full border px-3 py-1.5 text-[9px] font-black transition" :class="selectedCategory === 'all' ? 'border-slate-900 bg-slate-900 text-white' : 'border-slate-200 bg-white text-slate-600 hover:bg-amber-50'">Semua topik</button>
                @foreach($topics->unique('category') as $topicCategory)
                    <button type="button" @click="selectedCategory = '{{ $topicCategory->category }}'; showAllSessions = true" :aria-pressed="selectedCategory === '{{ $topicCategory->category }}'" class="rounded-full border border-slate-200 bg-white px-3 py-1.5 text-[9px] font-black text-slate-600 transition hover:bg-amber-50" :class="selectedCategory === '{{ $topicCategory->category }}' ? 'border-slate-900 bg-slate-900 text-white' : ''">{{ $topicCategory->category_label }}</button>
                @endforeach
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[430px] text-left">
                    <thead class="border-b border-slate-100 bg-white text-[9px] font-black uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-4 py-2.5">Topik</th>
                            <th class="px-3 py-2.5">Mulai</th>
                            <th class="px-3 py-2.5">Durasi</th>
                            <th class="px-4 py-2.5 text-right">Poin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-orange-50">
                        @forelse($recentSessions as $session)
                            <tr data-category="{{ $session->topic->category ?? '' }}"
                                x-show="(selectedCategory === 'all' || selectedCategory === $el.dataset.category) && (showAllSessions || selectedCategory !== 'all' || {{ $loop->index }} < 6)">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="h-2 w-2 shrink-0 rounded-full {{ match ($session->topic->category ?? '') { 'urutan' => 'bg-blue-500', 'pola' => 'bg-violet-500', 'percabangan' => 'bg-emerald-500', 'pengulangan' => 'bg-rose-500', default => 'bg-slate-400' } }}" aria-hidden="true"></span>
                                        <span class="max-w-[155px] text-[10px] font-bold leading-tight text-slate-800">{{ $session->topic->title ?? 'Matematika' }}</span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 text-[9px] text-slate-500">{{ $session->dimulai_pada->translatedFormat('d M Y, H:i') }}</td>
                                <td class="whitespace-nowrap px-3 py-3">
                                    <span class="rounded-full px-2 py-1 text-[8px] font-black {{ $session->selesai_pada ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $session->selesai_pada ? gmdate('H:i:s', $session->durasi_detik) : 'Berlangsung' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right text-[10px] font-black text-amber-700">{{ $session->poin_diperoleh }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-[10px] font-semibold text-slate-500">Belum ada sesi belajar tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rounded-3xl border border-slate-100 bg-white p-4 shadow-sm sm:p-5" aria-labelledby="topic-accuracy-title">
            <h2 id="topic-accuracy-title" class="mb-3 text-sm font-black text-slate-900 sm:text-base">Akurasi per topik</h2>
            <div class="space-y-2.5">
                @forelse($topics as $topic)
                    <article class="space-y-1.5 rounded-2xl border border-orange-100 bg-[#FAF8F3] p-3">
                        <div class="flex items-center justify-between gap-2">
                            <h3 class="min-w-0 truncate text-[10px] font-black text-slate-800">{{ $topic->category_label }}</h3>
                            <span class="shrink-0 rounded-full px-2 py-1 text-[8px] font-black {{ $topic->accuracy >= 70 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-700' }}">{{ $topic->accuracy }}%</span>
                        </div>
                        <div class="h-1.5 overflow-hidden rounded-full bg-[#F0E8D8]" role="progressbar" aria-label="Akurasi {{ $topic->category_label }}" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $topic->accuracy }}">
                            <div class="h-full rounded-full {{ $topic->accuracy >= 70 ? 'bg-emerald-500' : 'bg-rose-500' }}" style="width: {{ $topic->accuracy }}%"></div>
                        </div>
                        <p class="text-[9px] text-slate-500">Materi dikuasai: {{ $topic->lesson_progress }}%</p>
                    </article>
                @empty
                    <p class="rounded-2xl bg-[#FAF8F3] p-3 text-[10px] text-slate-500">Belum ada topik aktif untuk kelas siswa ini.</p>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
