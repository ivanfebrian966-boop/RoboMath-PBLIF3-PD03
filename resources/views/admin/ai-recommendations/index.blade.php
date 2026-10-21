@extends('layouts.admin')

@section('title', 'Review AI & Rekomendasi - RoboMath')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 p-6 sm:p-7 text-white shadow-xl shadow-indigo-500/10 border border-white/20">
        <div class="absolute -right-12 -top-12 w-56 h-56 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="space-y-2 max-w-xl">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-white/20 backdrop-blur-md text-white border border-white/30 uppercase tracking-wider">
                        🤖 Pusat Kurasi & Review AI
                    </span>
                    @if($totalPendingCount > 0)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-black bg-amber-400 text-slate-900 shadow-sm animate-pulse">
                            {{ $totalPendingCount }} Perlu Persetujuan
                        </span>
                    @endif
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white">
                    Verifikasi Soal AI & Rekomendasi
                </h1>
                <p class="text-indigo-100 text-xs sm:text-sm font-medium leading-relaxed">
                    Tinjau, setujui, atau tolak butir soal yang di-generate AI dari dokumen materi sebelum diterbitkan ke siswa, serta kelola rekomendasi remedial adaptif.
                </p>
            </div>

            <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 sm:p-4 border border-white/20 text-center min-w-[105px] flex-1 sm:flex-initial">
                    <div class="text-2xl sm:text-3xl font-black text-white">{{ $pendingQuizzesCount }}</div>
                    <div class="text-[11px] font-bold text-indigo-100 uppercase tracking-wider mt-0.5">Soal Pending</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 sm:p-4 border border-white/20 text-center min-w-[105px] flex-1 sm:flex-initial">
                    <div class="text-2xl sm:text-3xl font-black text-white">{{ $pendingRecommendationsCount }}</div>
                    <div class="text-[11px] font-bold text-indigo-100 uppercase tracking-wider mt-0.5">Remedial Siswa</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigasi Tab Utama -->
    <div class="flex items-center gap-2 border-b-2 border-slate-200/80 pb-1">
        <a href="{{ route('admin.ai-recommendations.index', ['tab' => 'quiz', 'status' => $statusFilter, 'topic_id' => $topicFilter]) }}"
           class="inline-flex items-center gap-2.5 px-5 py-3 rounded-2xl text-sm font-black transition-all duration-200 {{ $activeTab === 'quiz' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25 scale-[1.02]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            <span>📝 Soal AI dari Modul Materi</span>
            @if($pendingQuizzesCount > 0)
                <span class="px-2 py-0.5 text-xs rounded-full {{ $activeTab === 'quiz' ? 'bg-white text-indigo-700' : 'bg-rose-500 text-white' }}">
                    {{ $pendingQuizzesCount }}
                </span>
            @endif
        </a>

        <a href="{{ route('admin.ai-recommendations.index', ['tab' => 'student', 'status' => $statusFilter, 'topic_id' => $topicFilter]) }}"
           class="inline-flex items-center gap-2.5 px-5 py-3 rounded-2xl text-sm font-black transition-all duration-200 {{ $activeTab === 'student' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25 scale-[1.02]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            <span>🎯 Rekomendasi Remedial Siswa</span>
            @if($pendingRecommendationsCount > 0)
                <span class="px-2 py-0.5 text-xs rounded-full {{ $activeTab === 'student' ? 'bg-white text-indigo-700' : 'bg-rose-500 text-white' }}">
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
    <div class="bg-white rounded-3xl p-4 sm:p-5 border-2 border-slate-200/80 shadow-sm space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
            <!-- Filter Status -->
            <div class="bg-slate-100 p-1.5 rounded-2xl flex flex-wrap gap-1">
                <a href="{{ route('admin.ai-recommendations.index', ['tab' => 'quiz', 'status' => 'pending', 'topic_id' => $topicFilter]) }}"
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-black transition {{ $statusFilter === 'pending' ? 'bg-white text-indigo-700 shadow-sm border border-slate-200' : 'text-slate-600 hover:text-slate-900' }}">
                    <span>⏳ Menunggu Tinjauan</span>
                    <span class="px-2 py-0.5 text-[10px] rounded-full {{ $statusFilter === 'pending' ? 'bg-amber-100 text-amber-900' : 'bg-slate-200 text-slate-700' }}">{{ $pendingQuizzesCount }}</span>
                </a>
                <a href="{{ route('admin.ai-recommendations.index', ['tab' => 'quiz', 'status' => 'approved', 'topic_id' => $topicFilter]) }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-black transition {{ $statusFilter === 'approved' ? 'bg-white text-emerald-700 shadow-sm border border-slate-200' : 'text-slate-600 hover:text-slate-900' }}">
                    <span>✓ Disetujui (Aktif di Siswa)</span>
                </a>
                <a href="{{ route('admin.ai-recommendations.index', ['tab' => 'quiz', 'status' => 'rejected', 'topic_id' => $topicFilter]) }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-black transition {{ $statusFilter === 'rejected' ? 'bg-white text-rose-700 shadow-sm border border-slate-200' : 'text-slate-600 hover:text-slate-900' }}">
                    <span>✕ Ditolak</span>
                </a>
                <a href="{{ route('admin.ai-recommendations.index', ['tab' => 'quiz', 'status' => 'all', 'topic_id' => $topicFilter]) }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-black transition {{ $statusFilter === 'all' ? 'bg-white text-slate-900 shadow-sm border border-slate-200' : 'text-slate-600 hover:text-slate-900' }}">
                    <span>📋 Semua Status</span>
                </a>
            </div>

            <!-- Filter Topik Dropdown -->
            <form action="{{ route('admin.ai-recommendations.index') }}" method="GET" class="flex items-center gap-2">
                <input type="hidden" name="tab" value="quiz">
                <input type="hidden" name="status" value="{{ $statusFilter }}">
                <label for="topic_id" class="text-xs font-black text-slate-500 uppercase tracking-wider whitespace-nowrap">Filter Topik:</label>
                <select name="topic_id" id="topic_id" onchange="this.form.submit()"
                        class="px-3.5 py-2 rounded-xl border-2 border-slate-200 text-xs font-bold text-slate-800 bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none">
                    <option value="">-- Semua Topik --</option>
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
        <div class="p-4 rounded-2xl bg-gradient-to-r from-amber-50 to-orange-50 border-2 border-amber-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-xl flex-shrink-0">
                    ⚡
                </div>
                <div>
                    <h4 class="text-xs sm:text-sm font-black text-amber-900">
                        {{ $countPendingTopic }} Soal Menunggu Persetujuan pada Topik "{{ $currentTopic->title }}"
                    </h4>
                    <p class="text-[11px] text-amber-700 font-medium">
                        Anda dapat menyetujui seluruh soal topik ini sekaligus agar langsung diterbitkan ke siswa.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-shrink-0">
                <form action="{{ route('admin.ai-recommendations.topics.approve-all', $currentTopic) }}" method="POST" id="form-approve-topic-{{ $currentTopic->id }}">
                    @csrf
                    <button type="button" onclick="confirmTopicBulk('approve', {{ $currentTopic->id }}, '{{ addslashes($currentTopic->title) }}', {{ $countPendingTopic }})"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white rounded-xl text-xs font-black shadow-md transition transform active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>Setujui Semua ({{ $countPendingTopic }}) 🚀</span>
                    </button>
                </form>

                <form action="{{ route('admin.ai-recommendations.topics.reject-all', $currentTopic) }}" method="POST" id="form-reject-topic-{{ $currentTopic->id }}">
                    @csrf
                    <button type="button" onclick="confirmTopicBulk('reject', {{ $currentTopic->id }}, '{{ addslashes($currentTopic->title) }}', {{ $countPendingTopic }})"
                            class="inline-flex items-center gap-1 px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-black transition">
                        <span>Tolak Semua</span>
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>

    <!-- Daftar Soal AI -->
    <div class="bg-white border-2 border-slate-200/80 rounded-3xl overflow-hidden shadow-sm">
        <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-black text-slate-800 text-base flex items-center gap-2">
                <span>📑</span> Daftar Butir Soal AI
            </h3>
            <span class="text-xs text-slate-500 font-extrabold bg-slate-50 border border-slate-200 px-3 py-1 rounded-full">
                {{ $quizzes->total() }} Butir Soal
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
        <div class="divide-y divide-slate-100">
            @foreach($quizzes as $index => $q)
            <div class="p-5 sm:p-6 space-y-4 hover:bg-slate-50/50 transition">
                <!-- Header Kartu Soal -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-700 font-black text-xs flex items-center justify-center">
                            #{{ $quizzes->firstItem() + $index }}
                        </span>

                        <!-- Badge Topik -->
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-black bg-indigo-50 text-indigo-700 border border-indigo-200">
                            {{ $q->topic->icon ?? '📘' }} {{ $q->topic->title ?? 'Topik' }} • Kelas {{ $q->topic->kelas_level ?? '-' }} SD
                        </span>

                        <!-- Badge Tipe Soal -->
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[11px] font-black {{ $q->isEssay() ? 'bg-purple-100 text-purple-800' : 'bg-emerald-100 text-emerald-800' }}">
                            {{ $q->isEssay() ? '✍️ Essay / Uraian' : '🔘 Pilihan Ganda' }}
                        </span>

                        <!-- Badge Kesulitan & Poin -->
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                            ⭐ {{ ucfirst($q->difficulty) }} • {{ $q->points }} Poin
                        </span>
                    </div>

                    <!-- Badge Status -->
                    <div>
                        @if($q->review_status === 'pending')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-amber-100 text-amber-900 border border-amber-300 shadow-sm animate-pulse">
                                <span>⏳ Menunggu Tinjauan</span>
                            </span>
                        @elseif($q->review_status === 'approved')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-emerald-100 text-emerald-900 border border-emerald-300">
                                <span>✓ Disetujui & Aktif di Siswa</span>
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-rose-100 text-rose-900 border border-rose-300">
                                <span>✕ Ditolak</span>
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Modul Sumber (Jika Ada) -->
                @if($q->documentSummary)
                <div class="text-[11px] font-semibold text-slate-500 bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Sumber Dokumen Modul: <strong class="text-slate-800">{{ $q->documentSummary->title }}</strong> ({{ $q->documentSummary->original_filename }})</span>
                </div>
                @endif

                <!-- Pertanyaan -->
                <div class="p-4 rounded-2xl bg-slate-50/80 border border-slate-200/80 space-y-3">
                    <div class="text-sm font-extrabold text-slate-900 leading-relaxed">
                        {!! nl2br(e($q->question)) !!}
                    </div>

                    <!-- Opsi Jawaban (Jika Pilihan Ganda) -->
                    @if(!$q->isEssay() && is_array($q->options))
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1">
                        @foreach($q->options as $letter => $optText)
                        @php $isCorrect = (strtoupper($letter) === strtoupper($q->correct_answer)); @endphp
                        <div class="p-2.5 rounded-xl border text-xs font-semibold flex items-start gap-2.5 {{ $isCorrect ? 'bg-emerald-50 border-emerald-300 text-emerald-950 font-bold' : 'bg-white border-slate-200 text-slate-700' }}">
                            <span class="w-5 h-5 rounded-lg flex items-center justify-center font-black flex-shrink-0 {{ $isCorrect ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600' }}">
                                {{ $letter }}
                            </span>
                            <span class="flex-1">{{ $optText }}</span>
                            @if($isCorrect)
                                <span class="text-[10px] font-black text-emerald-700 bg-emerald-100 px-1.5 py-0.5 rounded uppercase">Kunci</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <!-- Kunci Jawaban Model (Jika Essay) -->
                    @if($q->isEssay())
                    <div class="p-3 rounded-xl bg-purple-50/80 border border-purple-200 text-xs space-y-1">
                        <div class="font-black text-purple-900 flex items-center gap-1.5">
                            <span>🔑 Model Kunci Jawaban Essay:</span>
                        </div>
                        <div class="text-slate-800 font-medium">
                            {{ $q->correct_answer }}
                        </div>
                    </div>
                    @endif

                    <!-- Pembahasan -->
                    @if($q->explanation)
                    <div class="p-3 rounded-xl bg-amber-50/60 border border-amber-200/80 text-xs space-y-1">
                        <div class="font-black text-amber-900 flex items-center gap-1.5">
                            <span>💡 Pembahasan & Langkah Penyelesaian:</span>
                        </div>
                        <div class="text-slate-700 font-medium leading-relaxed">
                            {{ $q->explanation }}
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
                <div class="flex items-center justify-between gap-3 pt-1">
                    <div class="text-xs text-slate-400 font-medium">
                        Diajukan: {{ $q->created_at->translatedFormat('d M Y, H:i') }}
                    </div>

                    <div class="flex items-center gap-2">
                        @if($q->review_status === 'pending')
                            <!-- Tombol Setujui -->
                            <form action="{{ route('admin.ai-recommendations.quizzes.approve', $q) }}" method="POST" id="form-approve-quiz-{{ $q->id }}">
                                @csrf
                                <button type="button" onclick="confirmQuizAction('approve', {{ $q->id }}, '{{ addslashes($q->topic->title ?? 'Topik') }}')"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white rounded-xl text-xs font-black shadow-md transition transform active:scale-95">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <span>Setujui & Kirim ke Siswa</span>
                                </button>
                            </form>

                            <!-- Tombol Tolak -->
                            <form action="{{ route('admin.ai-recommendations.quizzes.reject', $q) }}" method="POST" id="form-reject-quiz-{{ $q->id }}">
                                @csrf
                                <input type="hidden" name="admin_notes" id="reject-notes-{{ $q->id }}" value="">
                                <button type="button" onclick="confirmQuizAction('reject', {{ $q->id }}, '{{ addslashes($q->topic->title ?? 'Topik') }}')"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-100 hover:bg-rose-50 text-slate-700 hover:text-rose-600 border border-slate-200 hover:border-rose-200 rounded-xl text-xs font-black transition">
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
            </div>
            @endforeach
        </div>

        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
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
