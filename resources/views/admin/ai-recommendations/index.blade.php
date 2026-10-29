@extends('layouts.admin')

@section('title', 'Review AI - RoboMath')

@section('content')
<style>
    .review-ai-page,
    .review-ai-page *,
    .review-ai-page button,
    .review-ai-page input,
    .review-ai-page select,
    .review-ai-page textarea {
        font-family: 'Poppins', sans-serif !important;
    }
</style>

<div class="review-ai-page space-y-5 sm:space-y-6">

    <!-- ======================================================== -->
    <!-- 1. HEADER HERO BANNER -->
    <!-- ======================================================== -->
    <div class="rounded-[28px] bg-[#0B7A55] p-6 sm:p-8 text-white shadow-md shadow-emerald-950/10">
        <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">
            Review AI
        </h1>
        <p class="text-emerald-100 text-xs sm:text-sm font-normal mt-2 max-w-2xl leading-relaxed">
            Periksa soal buatan AI, setujui yang layak, tolak atau ubah yang kurang tepat.
        </p>
    </div>

    <!-- ======================================================== -->
    <!-- 2. KPI STAT CARDS (MENUNGGU REVIEW | DISETUJUI | DITOLAK) -->
    <!-- ======================================================== -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        {{-- Card 1: Menunggu Review (kuning/amber) --}}
        <div class="bg-white rounded-2xl p-5 shadow-xs border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-[#FEF3C7] text-[#D97706] flex items-center justify-center font-black text-2xl flex-shrink-0">
                !
            </div>
            <div>
                <div class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-500">MENUNGGU REVIEW</div>
                <div class="text-3xl font-bold text-slate-900 leading-tight">{{ $pendingQuizzesCount }}</div>
            </div>
        </div>

        {{-- Card 2: Disetujui (hijau) --}}
        <div class="bg-white rounded-2xl p-5 shadow-xs border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-[#E1F8EC] text-[#059669] flex items-center justify-center font-black text-2xl flex-shrink-0">
                ✓
            </div>
            <div>
                <div class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-500">DISETUJUI</div>
                <div class="text-3xl font-bold text-slate-900 leading-tight">{{ $approvedQuizzesCount }}</div>
            </div>
        </div>

        {{-- Card 3: Ditolak (merah) --}}
        <div class="bg-white rounded-2xl p-5 shadow-xs border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-[#FEE2E2] text-[#DC2626] flex items-center justify-center font-black text-2xl flex-shrink-0">
                ✕
            </div>
            <div>
                <div class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-500">DITOLAK</div>
                <div class="text-3xl font-bold text-slate-900 leading-tight">{{ $rejectedQuizzesCount }}</div>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- 3. PEMBEDA & FILTER TOPIK MATERI (BEDAKAN TOPIK) -->
    <!-- ======================================================== -->
    <div class="bg-white rounded-2xl p-4 shadow-xs border border-slate-100 space-y-2.5">
        <div class="flex items-center justify-between text-xs font-bold text-slate-500">
            <span class="flex items-center gap-2 uppercase tracking-wider text-[11px] text-slate-700">
                <span class="w-2.5 h-2.5 rounded-full bg-[#0B7A55]"></span>
                Pembeda Topik Materi
            </span>
            <span class="text-slate-400 font-normal hidden sm:inline">Pilih topik untuk membedakan dan menyaring butir soal</span>
        </div>
        <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
            {{-- Filter Semua Topik --}}
            <a href="{{ route('admin.ai-recommendations.index', ['tab' => 'quiz', 'status' => $statusFilter]) }}"
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap {{ !$topicFilter ? 'bg-[#0B7A55] text-white shadow-sm' : 'bg-[#F8F5EE] text-slate-700 hover:bg-slate-200' }}">
                Semua Topik
            </a>

            {{-- Topic Pills with Distinct Categories --}}
            @foreach($topics as $t)
                @php
                    $pendingInTopic = $pendingQuizzesByTopic[$t->id] ?? 0;
                    $topicCategoryColors = [
                        'urutan' => 'text-red-700 border-red-200 bg-red-50/70 hover:bg-red-100',
                        'pola' => 'text-amber-800 border-amber-200 bg-amber-50/70 hover:bg-amber-100',
                        'percabangan' => 'text-emerald-800 border-emerald-200 bg-emerald-50/70 hover:bg-emerald-100',
                        'pengulangan' => 'text-blue-800 border-blue-200 bg-blue-50/70 hover:bg-blue-100',
                        'dekomposisi' => 'text-purple-800 border-purple-200 bg-purple-50/70 hover:bg-purple-100',
                    ];
                    $catColor = $topicCategoryColors[$t->category] ?? 'text-slate-700 border-slate-200 bg-slate-50 hover:bg-slate-100';
                @endphp
                <a href="{{ route('admin.ai-recommendations.index', ['tab' => 'quiz', 'status' => $statusFilter, 'topic_id' => $t->id]) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all border whitespace-nowrap flex items-center gap-2 {{ $topicFilter == $t->id ? 'bg-[#0B7A55] text-white border-transparent shadow-sm' : $catColor }}">
                    <span>{{ $t->icon ?? '📘' }}</span>
                    <span>{{ $t->title }}</span>
                    @if($pendingInTopic > 0)
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $topicFilter == $t->id ? 'bg-white/20 text-white' : 'bg-amber-200 text-amber-900' }}">
                            {{ $pendingInTopic }}
                        </span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- 4. DUA KOLOM: ANTREAN SOAL (KIRI) & DETAIL REVIEW (KANAN) -->
    <!-- ======================================================== -->
    <div x-data="{
        items: {{ json_encode($quizzesData) }},
        currentIndex: 0,
        editModalOpen: false,

        get currentItem() {
            return this.items[this.currentIndex] || null;
        },
        selectItem(index) {
            this.currentIndex = index;
        },
        prevItem() {
            if (this.currentIndex > 0) this.currentIndex--;
        },
        nextItem() {
            if (this.currentIndex < this.items.length - 1) this.currentIndex++;
        }
    }" class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">

        <!-- ==================================================== -->
        <!-- KOLOM KIRI: ANTREAN SOAL -->
        <!-- ==================================================== -->
        <div class="lg:col-span-4 bg-white rounded-3xl p-5 sm:p-6 shadow-xs border border-slate-100 space-y-4">
            <h2 class="text-base font-bold text-slate-900">Antrean Soal</h2>

            <!-- Filter Status Button Pills (Semua, Disetujui, Ditolak) -->
            <div class="flex items-center gap-1.5 text-xs">
                <a href="{{ route('admin.ai-recommendations.index', ['tab' => 'quiz', 'status' => 'all', 'topic_id' => $topicFilter]) }}"
                   class="px-3.5 py-1.5 rounded-full font-bold transition-all {{ $statusFilter === 'all' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua ({{ $quizzes->total() }})
                </a>
                <a href="{{ route('admin.ai-recommendations.index', ['tab' => 'quiz', 'status' => 'approved', 'topic_id' => $topicFilter]) }}"
                   class="px-3.5 py-1.5 rounded-full font-bold transition-all {{ $statusFilter === 'approved' ? 'bg-[#0B7A55] text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Disetujui
                </a>
                <a href="{{ route('admin.ai-recommendations.index', ['tab' => 'quiz', 'status' => 'rejected', 'topic_id' => $topicFilter]) }}"
                   class="px-3.5 py-1.5 rounded-full font-bold transition-all {{ $statusFilter === 'rejected' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Ditolak
                </a>
            </div>

            <!-- List Butir Soal -->
            <template x-if="items.length === 0">
                <div class="py-12 text-center text-slate-400 space-y-2">
                    <div class="text-4xl">📋</div>
                    <div class="text-sm font-bold text-slate-700">Tidak ada soal dalam antrean.</div>
                    <p class="text-xs text-slate-400">Pilih status atau topik lain untuk melihat soal.</p>
                </div>
            </template>

            <div class="space-y-2 max-h-[640px] overflow-y-auto pr-1" x-show="items.length > 0">
                <template x-for="(item, idx) in items" :key="item.id">
                    <div @click="selectItem(idx)"
                         :class="currentIndex === idx
                            ? 'bg-[#E1F8EC] border-2 border-[#059669] shadow-xs'
                            : 'bg-white border border-slate-200/80 hover:border-slate-300 hover:bg-slate-50'"
                         class="p-3.5 rounded-2xl cursor-pointer transition-all flex items-start gap-3">

                        <!-- Nomor Soal Badge -->
                        <div :class="currentIndex === idx ? 'bg-[#059669] text-white' : 'bg-[#FCE8D0] text-[#9A621E]'"
                             class="w-7 h-7 rounded-xl font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5"
                             x-text="idx + 1">
                        </div>

                        <!-- Info Soal & Pembeda Topik -->
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-slate-800 line-clamp-2 leading-relaxed" x-text="item.question"></p>
                            
                            <div class="flex items-center gap-1.5 mt-1.5 flex-wrap">
                                <!-- Badge Pembeda Topik -->
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-[#EBF3FF] text-[#2563EB] border border-blue-200 truncate max-w-[150px]"
                                      x-text="item.topic_title"></span>

                                <!-- Status Badge Kecil Jika Disetujui/Ditolak -->
                                <template x-if="item.review_status === 'approved'">
                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-emerald-100 text-emerald-700">✓ Siap</span>
                                </template>
                                <template x-if="item.review_status === 'rejected'">
                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-rose-100 text-rose-700">✕ Ditolak</span>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Pagination Links -->
            <div class="pt-2 border-t border-slate-100 text-xs">
                {{ $quizzes->links() }}
            </div>
        </div>

        <!-- ==================================================== -->
        <!-- KOLOM KANAN: DETAIL BUTIR SOAL & TINDAKAN REVIEW -->
        <!-- ==================================================== -->
        <div class="lg:col-span-8 bg-white rounded-3xl p-6 sm:p-7 shadow-xs border border-slate-100 space-y-5" x-show="currentItem">

            <!-- Baris Header: Tag Kelas, Tipe Soal, Topik & Status -->
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#DBEAFE] text-[#1E40AF]"
                          x-text="'Kelas ' + currentItem.kelas_level + ' SD'"></span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#FCE8D0] text-[#9A621E]"
                          x-text="currentItem.type_label"></span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#F3E8FF] text-[#7E22CE] border border-purple-200"
                          x-text="'Topik: ' + currentItem.topic_title"></span>
                </div>

                <!-- Status Pill -->
                <span :class="{
                    'bg-[#FCE8D0] text-[#9A621E]': currentItem.review_status === 'pending',
                    'bg-[#E1F8EC] text-[#059669]': currentItem.review_status === 'approved',
                    'bg-[#FEE2E2] text-[#DC2626]': currentItem.review_status === 'rejected'
                }" class="px-3.5 py-1 rounded-full text-xs font-bold" x-text="currentItem.status_label"></span>
            </div>

            <!-- Subtitle Materi -->
            <div>
                <p class="text-xs sm:text-sm font-semibold text-slate-700" x-text="'Materi: ' + currentItem.material_title"></p>
            </div>

            <!-- Rekomendasi AI Box (Sesuai Mockup Kuning/Cream) -->
            <div class="rounded-2xl bg-[#FEF3C7] border border-amber-200/80 p-4 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full border-2 border-[#059669] bg-white flex items-center justify-center font-bold text-base text-[#059669] flex-shrink-0 shadow-xs"
                     x-text="currentItem.ai_score">
                    92
                </div>
                <div class="min-w-0">
                    <div class="font-bold text-slate-900 text-xs sm:text-sm" x-text="currentItem.recommendation_title">
                        Rekomendasi AI: layak disetujui
                    </div>
                    <div class="text-xs text-slate-600 mt-0.5 leading-relaxed" x-text="currentItem.recommendation_desc">
                        Bahasa sesuai usia, satu jawaban benar, dan sesuai materi.
                    </div>
                </div>
            </div>

            <!-- Teks Pertanyaan Soal -->
            <div class="pt-2">
                <h3 class="text-base sm:text-lg font-bold text-slate-900 leading-snug"
                    x-text="(currentIndex + 1) + '. ' + currentItem.question"></h3>
            </div>

            <!-- Opsi Pilihan Jawaban (A, B, C, D) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                <template x-for="opt in currentItem.options" :key="opt.key">
                    <div :class="opt.key === currentItem.correct_answer
                            ? 'bg-[#E1F8EC] border-2 border-[#059669] shadow-xs'
                            : 'bg-white border border-slate-200/90'"
                         class="p-4 rounded-2xl flex items-center gap-3 transition-all">

                        <!-- Letter Badge -->
                        <div :class="opt.key === currentItem.correct_answer
                                ? 'bg-[#059669] text-white'
                                : 'bg-slate-100 text-slate-700'"
                             class="w-7 h-7 rounded-xl font-bold text-xs flex items-center justify-center flex-shrink-0"
                             x-text="opt.key">
                        </div>

                        <!-- Option Text -->
                        <div class="text-xs sm:text-sm font-medium text-slate-800 leading-relaxed min-w-0"
                             x-text="opt.text">
                        </div>
                    </div>
                </template>
            </div>

            <!-- Kunci Jawaban & Pembahasan Box (Mint Green) -->
            <div class="rounded-2xl bg-[#E1F8EC] border border-emerald-200/80 p-4 text-xs sm:text-sm font-medium text-slate-800 leading-relaxed">
                <strong class="font-bold text-[#059669]" x-text="'Kunci: ' + currentItem.correct_answer + '.'"></strong>
                <span class="ml-1" x-text="currentItem.explanation"></span>
            </div>

            <!-- Tombol Navigasi & Tindakan Review -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-4 border-t border-slate-100">
                <!-- Navigasi Kiri (Sebelumnya / Berikutnya) -->
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <button type="button" @click="prevItem" :disabled="currentIndex === 0"
                            class="px-5 py-2.5 rounded-xl font-bold text-xs text-slate-700 bg-[#F8F5EE] hover:bg-slate-200 disabled:opacity-40 disabled:cursor-not-allowed transition-all">
                        Sebelumnya
                    </button>
                    <button type="button" @click="nextItem" :disabled="currentIndex >= items.length - 1"
                            class="px-5 py-2.5 rounded-xl font-bold text-xs text-slate-700 bg-[#F8F5EE] hover:bg-slate-200 disabled:opacity-40 disabled:cursor-not-allowed transition-all">
                        Berikutnya
                    </button>
                </div>

                <!-- Aksi Kanan (Ubah / Tolak / Setujui) -->
                <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                    <!-- Tombol Ubah -->
                    <a :href="currentItem.edit_url"
                       class="px-5 py-2.5 rounded-xl font-bold text-xs text-[#2563EB] bg-[#E0EDFF] hover:bg-blue-100 transition-all text-center">
                        Ubah
                    </a>

                    <!-- Tombol Tolak -->
                    <form :action="currentItem.reject_url" method="POST" class="inline m-0"
                          onsubmit="return confirm('Apakah Anda yakin ingin menolak butir soal ini?');">
                        @csrf
                        <button type="submit"
                                class="px-5 py-2.5 rounded-xl font-bold text-xs text-[#DC2626] bg-[#FEE2E2] hover:bg-red-100 transition-all cursor-pointer">
                            Tolak
                        </button>
                    </form>

                    <!-- Tombol Setujui -->
                    <form :action="currentItem.approve_url" method="POST" class="inline m-0">
                        @csrf
                        <button type="submit"
                                class="px-6 py-2.5 rounded-xl font-bold text-xs text-white bg-[#056B48] hover:bg-[#04583B] shadow-md shadow-emerald-950/10 transition-all cursor-pointer">
                            Setujui
                        </button>
                    </form>
                </div>
            </div>

        </div>

        <!-- State Kosong jika tidak ada item sama sekali -->
        <div class="lg:col-span-8 bg-white rounded-3xl p-12 text-center text-slate-400 space-y-2 border border-slate-100" x-show="!currentItem">
            <div class="text-4xl">🎉</div>
            <div class="text-sm font-bold text-slate-700">Semua butir soal telah diperiksa!</div>
            <p class="text-xs text-slate-400">Tidak ada soal dalam antrean untuk filter yang dipilih.</p>
        </div>

    </div>

</div>
@endsection
