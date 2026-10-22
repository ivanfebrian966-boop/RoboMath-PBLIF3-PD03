@extends('layouts.admin')

@section('title', 'Review AI & Rekomendasi - RoboMath')

@section('content')
<div class="space-y-5">

    <!-- Header Banner -->
    <div class="grid grid-cols-1 md:grid-cols-[minmax(0,1fr)_180px] gap-3">
        <div class="rounded-3xl bg-[#2861E8] p-6 sm:p-7 text-white shadow-lg shadow-blue-900/10">
            <div class="space-y-2 max-w-2xl">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black bg-white text-blue-700">
                    🤖 Pusat Kurasi & Review AI
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-white leading-tight">
                    Verifikasi Soal AI<br class="hidden sm:block"> & Rekomendasi
                </h1>
                <p class="text-blue-100 text-xs sm:text-sm font-semibold leading-relaxed max-w-xl">
                    Tinjau, setujui, atau tolak butir soal yang dibuat AI dari dokumen materi sebelum ditampilkan ke siswa.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-1 gap-3">
            <div class="rounded-3xl bg-[#FF9500] p-4 sm:p-5 flex flex-col justify-center">
                <div class="text-3xl sm:text-4xl font-black text-slate-950">{{ $pendingQuizzesCount }}</div>
                <div class="text-[11px] font-black text-slate-950 mt-1">Soal menunggu tinjauan</div>
            </div>
            <div class="rounded-3xl bg-[#7CC043] p-4 sm:p-5 flex flex-col justify-center">
                <div class="text-3xl sm:text-4xl font-black text-slate-950">{{ $pendingRecommendationsCount }}</div>
                <div class="text-[11px] font-black text-slate-950 mt-1">Remedial siswa</div>
            </div>
        </div>
    </div>

    @if($pendingTopics->isNotEmpty())
    <section class="space-y-3">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-slate-800">Topik dari dokumen yang menunggu persetujuan</h2>
            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-black text-amber-900">{{ $pendingTopics->count() }} topik</span>
        </div>
        <div class="grid grid-cols-1 gap-3 lg:grid-cols-2">
            @foreach($pendingTopics as $topic)
            <article class="flex flex-col justify-between gap-4 rounded-2xl border-2 border-amber-200 bg-white p-4 sm:flex-row sm:items-center">
                <div>
                    <h3 class="font-black text-slate-900">{{ $topic->title }}</h3>
                    <p class="mt-1 text-xs font-semibold text-slate-500">
                        Kelas {{ $topic->kelas_level }} · {{ $topic->category_label }}
                        @if($topic->documentSummary)
                            · Sumber: {{ $topic->documentSummary->original_filename }}
                        @endif
                    </p>
                    <p class="mt-2 text-sm text-slate-600">{{ $topic->description }}</p>
                </div>
                <div class="flex shrink-0 gap-2">
                    <form action="{{ route('admin.ai-recommendations.topics.approve', $topic) }}" method="POST">
                        @csrf
                        <button class="rounded-full bg-emerald-700 px-4 py-2 text-xs font-black text-white hover:bg-emerald-800">
                            Setujui topik
                        </button>
                    </form>
                    <form action="{{ route('admin.ai-recommendations.topics.reject', $topic) }}" method="POST">
                        @csrf
                        <button class="rounded-full border border-rose-300 bg-rose-50 px-4 py-2 text-xs font-black text-rose-700 hover:bg-rose-100">
                            Tolak
                        </button>
                    </form>
                </div>
            </article>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Navigasi Tab Utama -->
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('admin.ai-recommendations.index', ['tab' => 'quiz', 'status' => $statusFilter, 'topic_id' => $topicFilter]) }}"
           class="inline-flex items-center gap-2.5 px-4 py-3 rounded-full text-xs sm:text-sm font-black transition-all duration-200 {{ $activeTab === 'quiz' ? 'bg-slate-950 text-white shadow-md' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}">
            <span>Soal AI dari modul materi</span>
            @if($pendingQuizzesCount > 0)
                <span class="px-2 py-0.5 text-xs rounded-full bg-[#FF9500] text-slate-950">
                    {{ $pendingQuizzesCount }}
                </span>
            @endif
        </a>

        <a href="{{ route('admin.ai-recommendations.index', ['tab' => 'student', 'status' => $statusFilter, 'topic_id' => $topicFilter]) }}"
           class="inline-flex items-center gap-2.5 px-4 py-3 rounded-full text-xs sm:text-sm font-black transition-all duration-200 {{ $activeTab === 'student' ? 'bg-slate-950 text-white shadow-md' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}">
            <span>Rekomendasi remedial siswa</span>
            @if($pendingRecommendationsCount > 0)
                <span class="px-2 py-0.5 text-xs rounded-full bg-[#7CC043] text-slate-950">
                    {{ $pendingRecommendationsCount }}
                </span>
            @endif
        </a>
    </div>

    @if($activeTab === 'quiz')
    <!-- ======================================================== -->
    <!-- TAB 1: REVIEW SOAL AI DARI MODUL / KELOLA MATERI -->
    <!-- ======================================================== -->

    <!-- Filter Bar (Topik & Status) -->
    <div class="bg-white rounded-3xl p-3 sm:p-4 border-2 border-[#E9E0D4] shadow-sm space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
            <!-- Filter Status -->
            @php $pendingQuizFilterCount = $topicFilter ? ($pendingQuizzesByTopic[$topicFilter] ?? 0) : $pendingQuizzesCount; @endphp
            <div class="flex flex-wrap items-center gap-1.5">
                <a href="{{ route('admin.ai-recommendations.index', ['tab' => 'quiz', 'status' => 'pending', 'topic_id' => $topicFilter]) }}"
                   class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-full text-xs font-black transition {{ $statusFilter === 'pending' ? 'bg-[#FF9500] text-slate-950 border border-orange-600 shadow-sm' : 'text-slate-600 border border-[#E9E0D4] hover:bg-amber-50' }}">
                    <span>Menunggu</span>
                    <span class="px-2 py-0.5 text-[10px] rounded-full bg-white/80 text-slate-900">{{ $pendingQuizFilterCount }}</span>
                </a>
                <a href="{{ route('admin.ai-recommendations.index', ['tab' => 'quiz', 'status' => 'approved', 'topic_id' => $topicFilter]) }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-full text-xs font-black transition {{ $statusFilter === 'approved' ? 'bg-emerald-600 text-white border border-emerald-700 shadow-sm' : 'text-slate-600 border border-[#E9E0D4] hover:bg-emerald-50' }}">
                    <span>Disetujui</span>
                </a>
                <a href="{{ route('admin.ai-recommendations.index', ['tab' => 'quiz', 'status' => 'rejected', 'topic_id' => $topicFilter]) }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-full text-xs font-black transition {{ $statusFilter === 'rejected' ? 'bg-rose-600 text-white border border-rose-700 shadow-sm' : 'text-slate-600 border border-[#E9E0D4] hover:bg-rose-50' }}">
                    <span>Ditolak</span>
                </a>
                <a href="{{ route('admin.ai-recommendations.index', ['tab' => 'quiz', 'status' => 'all', 'topic_id' => $topicFilter]) }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-full text-xs font-black transition {{ $statusFilter === 'all' ? 'bg-slate-800 text-white border border-slate-900 shadow-sm' : 'text-slate-600 border border-[#E9E0D4] hover:bg-slate-50' }}">
                    <span>Semua status</span>
                </a>
            </div>

            <!-- Filter Topik Dropdown -->
            <form action="{{ route('admin.ai-recommendations.index') }}" method="GET" class="flex items-center gap-2">
                <input type="hidden" name="tab" value="quiz">
                <input type="hidden" name="status" value="{{ $statusFilter }}">
                <label for="topic_id" class="text-[10px] font-black text-slate-500 uppercase tracking-wider whitespace-nowrap">Topik</label>
                <select name="topic_id" id="topic_id" onchange="this.form.submit()"
                        class="min-w-0 max-w-full px-3.5 py-2.5 rounded-full border-2 border-[#E9E0D4] text-xs font-bold text-slate-800 bg-[#FAF6EF] focus:border-amber-500 focus:ring-2 focus:ring-amber-100 outline-none">
                    <option value="">Semua topik</option>
                    @foreach($topics as $t)
                        @php $pendingCountInTopic = $pendingQuizzesByTopic[$t->id] ?? 0; @endphp
                        <option value="{{ $t->id }}" {{ $topicFilter == $t->id ? 'selected' : '' }}>
                            {{ $t->icon }} {{ $t->title }} (Kelas {{ $t->kelas_level }}) {{ $pendingCountInTopic > 0 ? "({$pendingCountInTopic} pending)" : '' }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- Banner Aksi Massal per Topik (Jika ada topik spesifik yang dipilih dan ada pending) -->
        @if($topicFilter && isset($pendingQuizzesByTopic[$topicFilter]) && $pendingQuizzesByTopic[$topicFilter] > 0)
        @php
            $currentTopic = $topics->firstWhere('id', $topicFilter);
            $countPendingTopic = $pendingQuizzesByTopic[$topicFilter];
        @endphp
        <div class="p-4 rounded-2xl bg-[#FFF3D9] border-2 border-[#FFB84D] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-xl flex-shrink-0">
                    ⚡
                </div>
                <div>
                    <h4 class="text-xs sm:text-sm font-black text-slate-900">
                        {{ $countPendingTopic }} soal menunggu persetujuan di topik "{{ $currentTopic->title }}"
                    </h4>
                    <p class="text-[11px] text-amber-900 font-medium">
                        Setelah disetujui, soal langsung masuk ke latihan siswa.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-shrink-0">
                <form action="{{ route('admin.ai-recommendations.topics.approve-all', $currentTopic) }}" method="POST" id="form-approve-topic-{{ $currentTopic->id }}">
                    @csrf
                    <button type="button" onclick="confirmTopicBulk('approve', {{ $currentTopic->id }}, '{{ addslashes($currentTopic->title) }}', {{ $countPendingTopic }})"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white rounded-xl text-xs font-black shadow-md transition transform active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>Setujui semua ({{ $countPendingTopic }})</span>
                    </button>
                </form>

                <form action="{{ route('admin.ai-recommendations.topics.reject-all', $currentTopic) }}" method="POST" id="form-reject-topic-{{ $currentTopic->id }}">
                    @csrf
                    <button type="button" onclick="confirmTopicBulk('reject', {{ $currentTopic->id }}, '{{ addslashes($currentTopic->title) }}', {{ $countPendingTopic }})"
                            class="inline-flex items-center gap-1 px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-black transition">
                        <span>Tolak semua</span>
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>

    <!-- Daftar Soal AI -->
    <div>
        <div class="pb-3 flex items-center justify-between">
            <h3 class="font-black text-slate-800 text-base flex items-center gap-2">
                Daftar butir soal AI
            </h3>
            <span class="text-xs text-slate-500 font-extrabold">
                {{ $quizzes->total() }} butir soal
            </span>
        </div>

        @if($quizzes->isEmpty())
        <div class="p-16 text-center space-y-3">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center text-3xl mx-auto">
                🎉
            </div>
            <h4 class="text-base font-black text-slate-800">Tidak Ada Soal Ditemukan</h4>
            <p class="text-xs font-semibold text-slate-400 max-w-sm mx-auto">
                Tidak ada butir soal AI dalam status <strong>"{{ $statusFilter }}"</strong>{{ $topicFilter ? ' pada topik ini' : '' }}. Soal yang baru di-generate dari Kelola Materi akan masuk ke sini.
            </p>
            <div class="pt-2">
                <a href="{{ route('admin.document-summarizer.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black shadow-md transition">
                    <span>Unggah Modul di Kelola Materi →</span>
                </a>
            </div>
        </div>
        @else
        <div class="space-y-4">
            @foreach($quizzes as $index => $q)
            <article class="p-5 sm:p-6 space-y-4 bg-white border-2 border-[#E9E0D4] rounded-3xl shadow-sm">
                <!-- Header Kartu Soal -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="w-8 h-8 rounded-xl bg-[#EF342B] text-white font-black text-xs flex items-center justify-center">
                            #{{ $quizzes->firstItem() + $index }}
                        </span>

                        <!-- Badge Topik -->
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black bg-blue-50 text-blue-700 border border-blue-100">
                            {{ $q->topic->icon ?? '📘' }} {{ $q->topic->title ?? 'Topik' }} • Kelas {{ $q->topic->kelas_level ?? '-' }} SD
                        </span>

                        <!-- Badge Tipe Soal -->
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-black bg-slate-100 text-slate-800">
                            {{ $q->isEssay() ? '✍️ Essay / Uraian' : '🔘 Pilihan Ganda' }}
                        </span>

                        <!-- Badge Kesulitan & Poin -->
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-orange-100 text-orange-900">
                            Modul · +{{ $q->points }} poin
                        </span>
                    </div>

                    <!-- Badge Status -->
                    <div>
                        @if($q->review_status === 'pending')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black bg-amber-100 text-amber-900">
                                <span>⏳ Menunggu Tinjauan</span>
                            </span>
                        @elseif($q->review_status === 'approved')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black bg-emerald-100 text-emerald-900">
                                <span>✓ Disetujui & Aktif di Siswa</span>
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black bg-rose-100 text-rose-900">
                                <span>✕ Ditolak</span>
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Modul Sumber (Jika Ada) -->
                @if($q->documentSummary)
                <div class="text-[11px] font-semibold text-slate-500 flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Sumber: {{ $q->documentSummary->original_filename ?: $q->documentSummary->title }}</span>
                </div>
                @endif

                <!-- Pertanyaan -->
                <div class="space-y-3">
                    <div class="text-sm font-extrabold text-slate-900 leading-relaxed">
                        {!! nl2br(e($q->question)) !!}
                    </div>

                    <!-- Opsi Jawaban (Jika Pilihan Ganda) -->
                    @if(!$q->isEssay() && is_array($q->options))
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1">
                        @foreach($q->options as $letter => $optText)
                        <div class="p-2.5 rounded-2xl border-2 border-[#E9E0D4] text-xs font-semibold flex items-start gap-2.5 bg-white text-slate-700">
                            <span class="w-5 h-5 rounded-full flex items-center justify-center font-black flex-shrink-0 bg-[#F2EDE4] text-slate-700">
                                {{ $letter }}
                            </span>
                            <span class="flex-1">{{ $optText }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <!-- Kunci Jawaban -->
                    @if($q->isEssay() || $q->correct_answer)
                    @php
                        $correctOption = is_array($q->options)
                            ? ($q->options[strtoupper((string) $q->correct_answer)] ?? null)
                            : null;
                    @endphp
                    <div class="p-3 rounded-2xl bg-[#E7F4D9] border-2 border-[#63A52E] text-xs flex items-start gap-2.5">
                        <div class="text-emerald-800 flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                        </div>
                        <div class="space-y-0.5">
                            <div class="font-black text-slate-800">
                                Kunci: {{ $q->isEssay() ? $q->correct_answer : strtoupper((string) $q->correct_answer) . '. ' . ($correctOption ?? '') }}
                            </div>
                            @if($q->explanation)
                                <div class="text-slate-700 font-medium leading-relaxed">{{ $q->explanation }}</div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Catatan Reviewer (Jika Ada) -->
                    @if($q->admin_notes)
                    <div class="text-xs text-slate-600 bg-white border border-slate-200 rounded-xl px-3 py-2 flex items-center gap-2">
                        <span class="font-black text-slate-500">Catatan Review:</span>
                        <span class="italic text-slate-800">"{{ $q->admin_notes }}"</span>
                        @if($q->reviewer)
                            <span class="text-[10px] text-slate-400 ml-auto">oleh {{ $q->reviewer->name }}</span>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Aksi Review -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
                    <div class="text-[10px] text-slate-500 font-semibold">
                        Dibuat {{ $q->created_at->translatedFormat('d M Y, H:i') }}
                    </div>

                    <div class="flex items-center gap-2">
                        @if($q->review_status === 'pending')
                            <!-- Tombol Setujui -->
                            <form action="{{ route('admin.ai-recommendations.quizzes.approve', $q) }}" method="POST" id="form-approve-quiz-{{ $q->id }}">
                                @csrf
                                <button type="button" onclick="confirmQuizAction('approve', {{ $q->id }}, '{{ addslashes($q->topic->title ?? 'Topik') }}')"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-full text-xs font-black shadow-sm transition transform active:scale-95">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <span>Setujui & kirim ke siswa</span>
                                </button>
                            </form>

                            <!-- Tombol Tolak -->
                            <form action="{{ route('admin.ai-recommendations.quizzes.reject', $q) }}" method="POST" id="form-reject-quiz-{{ $q->id }}">
                                @csrf
                                <input type="hidden" name="admin_notes" id="reject-notes-{{ $q->id }}" value="">
                                <button type="button" onclick="confirmQuizAction('reject', {{ $q->id }}, '{{ addslashes($q->topic->title ?? 'Topik') }}')"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white hover:bg-rose-50 text-rose-700 border-2 border-rose-600 rounded-full text-xs font-black transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    <span>Tolak</span>
                                </button>
                            </form>
                        @elseif($q->review_status === 'approved')
                            <span class="text-xs font-extrabold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-xl flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <span>Telah Aktif & Dikirim ke Siswa</span>
                            </span>
                            <!-- Opsional: Batalkan persetujuan jika diperlukan -->
                            <form action="{{ route('admin.ai-recommendations.quizzes.reject', $q) }}" method="POST" id="form-reject-quiz-{{ $q->id }}">
                                @csrf
                                <input type="hidden" name="admin_notes" value="Persetujuan dibatalkan oleh pengajar.">
                                <button type="submit" class="text-[11px] font-bold text-rose-600 hover:underline px-2 py-1">
                                    Tarik Soal
                                </button>
                            </form>
                        @else
                            <span class="text-xs font-extrabold text-rose-700 bg-rose-50 border border-rose-200 px-3 py-1.5 rounded-xl">
                                ✕ Ditolak
                            </span>
                            <!-- Opsional: Pulihkan & Setujui -->
                            <form action="{{ route('admin.ai-recommendations.quizzes.approve', $q) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-[11px] font-bold text-emerald-600 hover:underline px-2 py-1">
                                    Pulihkan & Setujui
                                </button>
                            </form>
                        @endif
                </div>
            </div>
            </article>
            @endforeach
        </div>

        <div class="pt-4">
            {{ $quizzes->links() }}
        </div>
        @endif
    </div>

    @else
    <!-- ======================================================== -->
    <!-- TAB 2: REKOMENDASI DIAGNOSTIK SISWA REMEDIAL -->
    <!-- ======================================================== -->

    <!-- Filter Status Tabs -->
    <div class="bg-amber-100/70 p-1.5 rounded-2xl border border-amber-200/80 shadow-inner flex flex-wrap gap-1.5">
        <a href="{{ route('admin.ai-recommendations.index', ['tab' => 'student', 'status' => 'pending']) }}"
           class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-black transition {{ $statusFilter === 'pending' ? 'bg-white text-rose-600 shadow-md border border-rose-100' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
            <span>⏳ Menunggu Tinjauan</span>
            <span class="px-2 py-0.5 text-[10px] rounded-full {{ $statusFilter === 'pending' ? 'bg-rose-100 text-rose-800' : 'bg-slate-200 text-slate-700' }}">{{ $pendingRecommendationsCount }}</span>
        </a>
        <a href="{{ route('admin.ai-recommendations.index', ['tab' => 'student', 'status' => 'approved']) }}"
           class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-black transition {{ $statusFilter === 'approved' ? 'bg-white text-emerald-700 shadow-md border border-emerald-100' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
            <span>✓ Disetujui</span>
        </a>
        <a href="{{ route('admin.ai-recommendations.index', ['tab' => 'student', 'status' => 'rejected']) }}"
           class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-black transition {{ $statusFilter === 'rejected' ? 'bg-white text-slate-800 shadow-md border border-slate-200' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
            <span>✕ Ditolak</span>
        </a>
        <a href="{{ route('admin.ai-recommendations.index', ['tab' => 'student', 'status' => 'all']) }}"
           class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-black transition {{ $statusFilter === 'all' ? 'bg-white text-indigo-700 shadow-md border border-indigo-100' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
            <span>📋 Semua Status</span>
        </a>
    </div>

    <!-- Recommendations Container -->
    <div class="bg-white border-2 border-amber-200/80 rounded-3xl overflow-hidden shadow-sm">
        <div class="p-5 sm:p-6 border-b border-amber-100 flex items-center justify-between">
            <h3 class="font-black text-slate-800 text-base flex items-center gap-2">
                <span>📑</span> Log Diagnostik AI Adaptif Siswa
            </h3>
            <span class="text-xs text-slate-500 font-extrabold bg-amber-50 border border-amber-200 px-3 py-1 rounded-full">
                {{ $recommendations->total() }} Catatan
            </span>
        </div>

        @if($recommendations->isEmpty())
        <div class="p-16 text-center space-y-3">
            <div class="w-16 h-16 rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-center text-3xl mx-auto">
                🎉
            </div>
            <h4 class="text-base font-black text-slate-800">Tidak Ada Rekomendasi</h4>
            <p class="text-xs font-semibold text-slate-400 max-w-sm mx-auto">
                Saat ini tidak ada rekomendasi remedial siswa dalam status <strong>"{{ $statusFilter }}"</strong>.
            </p>
        </div>
        @else
        <div class="divide-y divide-amber-100">
            @foreach($recommendations as $rec)
            <div class="p-5 sm:p-6 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-5 hover:bg-amber-50/30 transition">
                <div class="space-y-3 flex-1 max-w-3xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center font-black shadow-sm flex-shrink-0 text-sm">
                            {{ strtoupper(substr($rec->student->name ?? 'S', 0, 1)) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h4 class="font-extrabold text-slate-900 text-sm sm:text-base">{{ $rec->student->name ?? 'Siswa' }}</h4>
                                <span class="bg-indigo-50 text-indigo-700 border border-indigo-200 text-[10px] font-black px-2 py-0.5 rounded-md">
                                    Kelas {{ $rec->student->kelas ?? '-' }} SD
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">
                                Topik Remedial: <strong class="text-amber-900">{{ $rec->topic->title ?? '-' }}</strong>
                            </p>
                        </div>
                    </div>

                    <!-- Reason Trigger Box -->
                    <div class="p-4 bg-rose-50/70 border border-rose-200 rounded-2xl space-y-1.5">
                        <p class="text-xs text-rose-900 font-bold flex items-start gap-2">
                            <svg class="w-4 h-4 text-rose-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span><strong>Pemicu Diagnostik AI:</strong> {{ $rec->reason }}</span>
                        </p>
                        @if($rec->quiz)
                        <p class="text-[11px] text-slate-600 pl-6 leading-relaxed">
                            Contoh butir soal terkait: <span class="italic text-slate-800">"{!! strip_tags($rec->quiz->question) !!}"</span>
                        </p>
                        @endif
                    </div>

                    @if($rec->admin_notes)
                    <div class="text-xs text-slate-600 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 flex items-center gap-2">
                        <span class="font-black text-slate-500">Catatan Review:</span>
                        <span class="italic">"{{ $rec->admin_notes }}"</span>
                        <span class="text-[10px] text-slate-400 ml-auto">oleh {{ $rec->reviewer->name ?? 'Admin' }}</span>
                    </div>
                    @endif
                </div>

                <!-- Action Buttons or Status Badge -->
                <div class="flex items-center gap-2.5 w-full lg:w-auto justify-end pt-2 lg:pt-0">
                    @if($rec->status === 'pending')
                    <form action="{{ route('admin.ai-recommendations.approve', $rec) }}" method="POST" id="form-approve-rec-{{ $rec->id }}">
                        @csrf
                        <button type="button" onclick="confirmRecAction('approve', {{ $rec->id }})"
                            class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-xl text-xs font-black shadow-md transition transform active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span>Setujui</span>
                        </button>
                    </form>

                    <form action="{{ route('admin.ai-recommendations.reject', $rec) }}" method="POST" id="form-reject-rec-{{ $rec->id }}">
                        @csrf
                        <button type="button" onclick="confirmRecAction('reject', {{ $rec->id }})"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-slate-100 hover:bg-rose-50 text-slate-700 hover:text-rose-600 border border-slate-200 hover:border-rose-200 rounded-xl text-xs font-black transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span>Tolak</span>
                        </button>
                    </form>
                    @else
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-black uppercase tracking-wider {{ $rec->status === 'approved' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-slate-100 text-slate-600 border border-slate-300' }}">
                        <span>{{ $rec->status === 'approved' ? '✓' : '✕' }}</span>
                        <span>{{ $rec->status === 'approved' ? 'Disetujui' : 'Ditolak' }}</span>
                    </span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <div class="p-4 border-t border-amber-100 bg-amber-50/20">
            {{ $recommendations->links() }}
        </div>
        @endif
    </div>
    @endif

</div>

@push('scripts')
<script>
    // Konfirmasi Aksi Soal AI Tunggal
    function confirmQuizAction(type, quizId, topicTitle) {
        const isApprove = (type === 'approve');
        if (isApprove) {
            Swal.fire({
                title: 'Setujui Butir Soal AI?',
                html: `<div style="font-size:14px; line-height:1.6;">Soal ini akan disetujui dan <strong>langsung diterbitkan ke siswa</strong> pada topik <strong>"${topicTitle}"</strong>.</div>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#94A3B8',
                confirmButtonText: 'Ya, Setujui & Kirim ke Siswa 🚀',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`form-approve-quiz-${quizId}`).submit();
                }
            });
        } else {
            Swal.fire({
                title: 'Tolak Butir Soal AI?',
                text: 'Berikan catatan atau alasan penolakan (opsional):',
                input: 'textarea',
                inputPlaceholder: 'Contoh: Soal kurang relevan dengan materi kurikulum...',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#E11D48',
                cancelButtonColor: '#94A3B8',
                confirmButtonText: 'Ya, Tolak Soal ✕',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const inputNotes = document.getElementById(`reject-notes-${quizId}`);
                    if (inputNotes) inputNotes.value = result.value || '';
                    document.getElementById(`form-reject-quiz-${quizId}`).submit();
                }
            });
        }
    }

    // Konfirmasi Aksi Massal per Topik
    function confirmTopicBulk(type, topicId, topicTitle, count) {
        const isApprove = (type === 'approve');
        Swal.fire({
            title: isApprove ? `Setujui Seluruh (${count}) Soal Topik?` : `Tolak Seluruh (${count}) Soal Topik?`,
            html: isApprove
                ? `<div style="font-size:14px; line-height:1.6;">Seluruh <strong>${count} butir soal</strong> pada topik <strong>"${topicTitle}"</strong> akan disetujui sekaligus dan langsung dapat dimainkan siswa.</div>`
                : `<div style="font-size:14px; line-height:1.6;">Seluruh <strong>${count} butir soal</strong> pending pada topik <strong>"${topicTitle}"</strong> akan ditolak dan tidak diterbitkan ke siswa.</div>`,
            icon: isApprove ? 'question' : 'warning',
            showCancelButton: true,
            confirmButtonColor: isApprove ? '#059669' : '#E11D48',
            cancelButtonColor: '#94A3B8',
            confirmButtonText: isApprove ? 'Ya, Setujui Semua 🚀' : 'Ya, Tolak Semua ✕',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`form-${type}-topic-${topicId}`).submit();
            }
        });
    }

    // Konfirmasi Rekomendasi Remedial Siswa
    function confirmRecAction(type, recId) {
        const isApprove = (type === 'approve');
        Swal.fire({
            title: isApprove ? 'Setujui Rekomendasi Remedial?' : 'Tolak Rekomendasi Remedial?',
            text: isApprove ? 'Siswa akan diarahkan untuk mempelajari modul remedial ini.' : 'Rekomendasi ini akan diabaikan dari daftar tinjauan.',
            icon: isApprove ? 'question' : 'warning',
            showCancelButton: true,
            confirmButtonColor: isApprove ? '#10B981' : '#E11D48',
            cancelButtonColor: '#94A3B8',
            confirmButtonText: isApprove ? 'Ya, Setujui' : 'Ya, Tolak',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`form-${type}-rec-${recId}`).submit();
            }
        });
    }
</script>
@endpush
@endsection
