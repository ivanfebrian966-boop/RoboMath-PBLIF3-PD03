@extends('layouts.admin')

@section('title', 'Ringkasan Dokumen Jadi Soal AI - RoboMath')

@section('content')
<div class="space-y-6 sm:space-y-8">

    <!-- ======================================================== -->
    <!-- 1. HERO BANNER BIRU CERAH (Sesuai Desain Gambar) -->
    <!-- ======================================================== -->
    <div class="rounded-[28px] sm:rounded-[32px] p-6 sm:p-8 text-white shadow-lg relative overflow-hidden"
         style="background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);">
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight text-white mb-2.5">
            Ringkas Dokumen Jadi Soal AI
        </h1>
        <p class="text-blue-100 text-xs sm:text-sm font-medium leading-relaxed max-w-2xl">
            Unggah modul, silabus, lembar kerja, atau masukkan teks materi. AI langsung mengubahnya menjadi butir soal latihan lengkap dengan kunci dan pembahasan.
        </p>
    </div>

    <!-- ======================================================== -->
    <!-- 2. KARTU STATISTIK (3 KOLOM BERGARIS PEMBATAS) -->
    <!-- ======================================================== -->
    <div class="bg-white rounded-[24px] sm:rounded-[28px] border border-slate-100 shadow-sm p-4 sm:p-6 grid grid-cols-3 divide-x divide-slate-100 text-center">
        <div class="px-2 sm:px-4">
            <div class="text-2xl sm:text-3xl font-black text-[#EF4444]" id="stat-total-summaries">{{ $totalSummaries }}</div>
            <div class="text-[11px] sm:text-xs font-semibold text-slate-400 mt-0.5">Dokumen diproses</div>
        </div>
        <div class="px-2 sm:px-4">
            <div class="text-2xl sm:text-3xl font-black text-[#F59E0B]">{{ number_format($totalWordsAnalyzed) }}</div>
            <div class="text-[11px] sm:text-xs font-semibold text-slate-400 mt-0.5">Kata dianalisis</div>
        </div>
        <div class="px-2 sm:px-4">
            <div class="text-2xl sm:text-3xl font-black text-[#3B82F6]">5</div>
            <div class="text-[11px] sm:text-xs font-semibold text-slate-400 mt-0.5">Soal default</div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- 3. KARTU STATUS: MENUNGGU DOKUMEN & HASIL YANG DIBUAT -->
    <!-- ======================================================== -->
    <div class="bg-white rounded-[24px] sm:rounded-[28px] border border-slate-100 shadow-sm p-5 sm:p-6 flex flex-col md:flex-row md:items-center justify-between gap-5">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-[#FEF3C7] text-[#D97706] flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Menunggu dokumen Anda</h3>
                <p class="text-xs text-slate-400 font-medium mt-0.5">Dokumen akan dibaca AI setelah Anda menekan tombol mulai.</p>
            </div>
        </div>

        <div class="md:text-right space-y-1.5 flex-shrink-0">
            <div class="text-[10px] font-black uppercase tracking-wider text-[#D97706]">HASIL YANG DIBUAT</div>
            <div class="flex items-center flex-wrap gap-x-4 gap-y-2 text-xs font-bold text-slate-700">
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-[#EF4444]"></span>
                    Soal otomatis
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-[#F59E0B]"></span>
                    Pilihan ganda A–D
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-[#10B981]"></span>
                    Kunci jawaban
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-[#3B82F6]"></span>
                    Pembahasan
                </span>
            </div>
        </div>
    </div>

    <!-- Hidden compatibility placeholder for JS state -->
    <div id="result-placeholder" class="hidden"></div>

    <!-- ======================================================== -->
    <!-- 4. FORMULIR UTAMA GENERATE SOAL DOKUMEN -->
    <!-- ======================================================== -->
    <div class="bg-white rounded-[28px] sm:rounded-[32px] border border-slate-100 shadow-sm p-6 sm:p-8 space-y-6 sm:space-y-8">
        
        <form id="summarizer-form" enctype="multipart/form-data" method="POST"
              action="{{ route('admin.document-summarizer.summarize') }}"
              data-no-spa class="space-y-6 sm:space-y-8">
            @csrf

            <!-- Hidden fields for format compatibility -->
            <input type="hidden" name="summary_style" value="kuis_latihan">
            <input type="hidden" name="summary_length" id="summary_length" value="5_soal">

            <!-- ================= LANGKAH 1 ================= -->
            <div class="space-y-4">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-full bg-[#FFE4E6] text-[#E11D48] text-xs font-black flex items-center justify-center flex-shrink-0">
                            1
                        </span>
                        <div>
                            <h2 class="text-base font-black text-slate-900">Pilih dokumen sumber</h2>
                            <p class="text-xs text-slate-400 font-medium">Pilih berkas dari perangkat atau masukkan teks materi.</p>
                        </div>
                    </div>

                    <!-- Input Mode Toggle -->
                    <div class="inline-flex p-1 rounded-full bg-slate-100 text-xs font-bold" id="toggle-input-mode">
                        <button type="button" onclick="setInputMode('file')" id="btn-mode-file"
                                class="px-4 py-1.5 rounded-full transition-all bg-white text-slate-900 shadow-sm border border-slate-200/80 font-black">
                            Unggah file
                        </button>
                        <button type="button" onclick="setInputMode('text')" id="btn-mode-text"
                                class="px-4 py-1.5 rounded-full transition-all text-slate-500 font-bold hover:text-slate-900">
                            Tempel teks
                        </button>
                    </div>
                </div>

                <!-- 1. MODE FILE UPLOAD -->
                <div id="mode-file-container" class="space-y-3">
                    <input type="file" id="document_file" name="document_file" accept=".pdf,.docx,.doc,.txt,.md,.csv,.json"
                           style="display: none !important;">

                    <!-- Dropzone Area -->
                    <div id="dropzone" onclick="document.getElementById('document_file').click()"
                         class="border-2 border-dashed border-amber-200/90 hover:border-blue-400 bg-amber-50/15 hover:bg-blue-50/20 rounded-[22px] p-8 sm:p-10 text-center transition-all cursor-pointer">
                        <div class="w-14 h-14 mx-auto rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500 mb-3 shadow-xs">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                        </div>
                        <p class="font-extrabold text-slate-900 text-sm sm:text-base">
                            Klik untuk memilih berkas atau seret & lepas ke sini
                        </p>
                        <p class="text-xs text-slate-400 font-medium mt-1">
                            PDF, Word (DOCX), TXT, Markdown · maks. 20 MB
                        </p>
                        <div class="mt-4">
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-extrabold bg-white border border-slate-200 text-slate-700 shadow-xs hover:bg-slate-50 transition-all">
                                Pilih berkas
                            </span>
                        </div>
                    </div>

                    <!-- Selected File Preview Card -->
                    <div id="file-preview-card" class="hidden bg-slate-50/80 border border-slate-200 rounded-2xl p-4 shadow-xs transition-all">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <div class="w-12 h-12 rounded-xl bg-blue-600 text-white flex flex-col items-center justify-center font-black shadow-xs flex-shrink-0" id="file-ext-badge-container">
                                    <span class="text-base leading-none" id="file-icon-emoji">📄</span>
                                    <span class="text-[9px] font-black uppercase mt-0.5 tracking-wider" id="file-ext-badge">PDF</span>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="font-black text-slate-800 text-sm truncate" id="file-name-label">document.pdf</span>
                                        <span class="hidden sm:inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700">
                                            ✓ Berkas Siap
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2 text-xs text-slate-500 font-medium mt-0.5">
                                        <span id="file-size-label" class="font-bold text-slate-600">1.2 MB</span>
                                        <span>•</span>
                                        <button type="button" onclick="document.getElementById('document_file').click()" class="text-blue-600 hover:text-blue-800 font-bold hover:underline cursor-pointer">
                                            Ganti Berkas
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" onclick="clearSelectedFile()" class="p-2.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all flex-shrink-0 cursor-pointer" title="Hapus Berkas">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 2. MODE DIRECT TEXT INPUT -->
                <div id="mode-text-container" class="hidden space-y-2">
                    <div class="relative">
                        <textarea id="direct_text" name="direct_text" rows="6"
                                  placeholder="Salin atau ketik teks materi pembelajaran di sini (minimal 20 karakter)..."
                                  class="w-full rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 p-4 text-sm font-medium text-slate-800 placeholder-slate-400 resize-y transition-all"
                                  oninput="updateTextCounter(this)"></textarea>
                        <div class="absolute bottom-3 right-3 text-[11px] font-bold px-2 py-0.5 rounded-md bg-slate-100 text-slate-500" id="text-char-count">
                            0 kata
                        </div>
                    </div>
                </div>

                <!-- Judul Dokumen (Opsional) -->
                <div class="space-y-1.5 pt-1">
                    <label for="custom_title" class="block text-xs font-extrabold text-slate-700">
                        Judul dokumen / topik <span class="text-slate-400 font-normal">(opsional)</span>
                    </label>
                    <input type="text" id="custom_title" name="custom_title"
                           placeholder="Contoh: Modul Pecahan Sederhana Kelas 4 SD"
                           class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                </div>
            </div>

            <!-- ================= LANGKAH 2 ================= -->
            <div class="space-y-4 pt-2">
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-full bg-[#E0F2FE] text-[#0284C7] text-xs font-black flex items-center justify-center flex-shrink-0">
                        2
                    </span>
                    <div>
                        <h3 class="text-base font-black text-slate-900">
                            Instruksi soal <span class="text-slate-400 font-normal text-xs">· opsional</span>
                        </h3>
                        <p class="text-xs text-slate-400 font-medium">Beri arahan khusus kepada AI tentang soal yang diinginkan.</p>
                    </div>
                </div>

                <div class="space-y-2.5">
                    <textarea id="custom_prompt" name="custom_prompt" rows="3"
                              placeholder='Contoh: "Buatkan 10 soal cerita tingkat HOTS tentang pecahan untuk kelas 4 SD."'
                              class="w-full rounded-2xl border border-slate-200 p-4 text-sm font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 resize-y transition-all"></textarea>

                    <!-- Quick Suggestion Chips -->
                    <div class="flex items-center gap-2 flex-wrap pt-1">
                        <button type="button" onclick="setPromptTemplate('Buatkan 5 butir soal pesan / uraian beserta pedoman kunci jawaban dan langkah penyelesaiannya.')"
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full border border-slate-200 bg-white hover:bg-slate-50 text-xs font-bold text-slate-700 shadow-2xs transition-all cursor-pointer">
                            <span class="w-2 h-2 rounded-full bg-[#EF4444]"></span>
                            <span>5 soal pesan / uraian</span>
                        </button>
                        <button type="button" onclick="setPromptTemplate('Buatkan 5 soal HOTS (Higher Order Thinking Skills) dengan analisis penalaran mendalam dan studi kasus cerita.')"
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full border border-slate-200 bg-white hover:bg-slate-50 text-xs font-bold text-slate-700 shadow-2xs transition-all cursor-pointer">
                            <span class="w-2 h-2 rounded-full bg-[#F59E0B]"></span>
                            <span>5 soal HOTS</span>
                        </button>
                        <button type="button" onclick="setPromptTemplate('Buatkan 5 butir soal cerita kontekstual anak SD dengan nama tokoh ramah dan situasi sehari-hari.')"
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full border border-slate-200 bg-white hover:bg-slate-50 text-xs font-bold text-slate-700 shadow-2xs transition-all cursor-pointer">
                            <span class="w-2 h-2 rounded-full bg-[#10B981]"></span>
                            <span>5 soal cerita</span>
                        </button>
                        <button type="button" onclick="setPromptTemplate('Buatkan 10 butir soal kuis evaluasi komprehensif mencakup semua bab materi pada dokumen.')"
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full border border-slate-200 bg-white hover:bg-slate-50 text-xs font-bold text-slate-700 shadow-2xs transition-all cursor-pointer">
                            <span class="w-2 h-2 rounded-full bg-[#3B82F6]"></span>
                            <span>10 soal evaluasi</span>
                        </button>
                    </div>

                    <!-- Note Default Mode -->
                    <p class="text-xs text-slate-500 font-medium pt-1">
                        <strong class="font-bold text-slate-700">Mode default:</strong> jika instruksi kosong, AI membuat 5 soal pilihan ganda (A, B, C, D) lengkap dengan kunci jawaban dan pembahasan.
                    </p>
                </div>
            </div>

            <!-- ================= FOOTER AKSI ================= -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-slate-100">
                <div class="flex items-center gap-2.5">
                    <input type="checkbox" id="save_history" name="save_history" value="1" checked
                           class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500 border-slate-300 cursor-pointer">
                    <label for="save_history" class="text-xs font-bold text-slate-700 select-none cursor-pointer">
                        Simpan paket soal ke riwayat dokumen
                    </label>
                </div>

                <button type="submit" id="btn-submit-summarize"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl font-black text-sm text-white bg-gradient-to-r from-[#FF3B30] to-[#FF4B3A] hover:from-[#E02E24] hover:to-[#EB3E2D] shadow-lg shadow-red-500/30 hover:shadow-red-500/40 active:scale-[0.98] transition-all cursor-pointer">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l2.4 7.2L22 12l-7.6 2.8L12 22l-2.4-7.2L2 12l7.6-2.8z"/>
                    </svg>
                    <span>Mulai buat soal dengan AI</span>
                </button>
            </div>

        </form>

    </div>

    <!-- ======================================================== -->
    <!-- LOADING STATE & HASIL CONTAINER (DITAMPILKAN SAAT DIPROSES) -->
    <!-- ======================================================== -->
    <div id="result-loading" class="hidden bg-white rounded-3xl p-8 border-2 border-blue-200 text-center flex flex-col items-center justify-center min-h-[300px] shadow-sm space-y-4">
        <div class="relative">
            <div class="w-16 h-16 rounded-full border-4 border-blue-100 border-t-blue-600 animate-spin"></div>
            <div class="absolute inset-0 flex items-center justify-center text-xl">🤖</div>
        </div>
        <div class="space-y-1">
            <h3 class="text-base font-black text-slate-900" id="loading-stage-text">Membaca berkas dokumen...</h3>
            <p class="text-xs text-slate-500 font-medium">Model AI sedang membaca materi dan merancang butir soal berkualitas.</p>
            <p class="text-[11px] text-amber-600 font-bold mt-1">⏳ Harap ditunggu beberapa saat!</p>
        </div>
        <div class="w-full max-w-xs bg-slate-100 h-2 rounded-full overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-full w-2/3 animate-pulse"></div>
        </div>
    </div>

    <!-- RESULT SUCCESS CARD -->
    <div id="result-container" class="hidden bg-white rounded-3xl p-5 sm:p-7 border border-emerald-300 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-2xl bg-emerald-50/80 border border-emerald-200">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-2xl bg-emerald-500 flex items-center justify-center text-white text-lg flex-shrink-0 shadow-xs">
                    ✅
                </div>
                <div>
                    <h3 class="font-black text-emerald-950 text-sm sm:text-base" id="res-title">Paket Soal Siap!</h3>
                    <p class="text-xs text-emerald-700 font-medium mt-0.5">
                        <span id="res-badge-type" class="font-bold"></span>
                        <span class="mx-1">•</span>
                        <span id="res-provider" class="text-blue-700 font-bold"></span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="openResultPopup()"
                        class="px-4 py-2.5 rounded-xl font-black text-xs text-white bg-blue-600 hover:bg-blue-700 shadow-xs transition-all">
                    Buka Hasil Soal (Layar Penuh)
                </button>
                <button type="button" onclick="openPublishModal()"
                        class="px-4 py-2.5 rounded-xl font-black text-xs text-white bg-emerald-600 hover:bg-emerald-700 shadow-xs transition-all">
                    Kirim ke Siswa 🚀
                </button>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
            <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-3">
                <div class="text-[10px] font-bold text-slate-400 uppercase">Kata Sumber</div>
                <div class="font-black text-slate-800 text-sm mt-0.5" id="res-words-original">—</div>
            </div>
            <div class="bg-blue-50 border border-blue-200/80 rounded-2xl p-3">
                <div class="text-[10px] font-bold text-blue-800/70 uppercase">Panjang Soal</div>
                <div class="font-black text-blue-700 text-sm mt-0.5" id="res-words-summary">—</div>
            </div>
            <div class="col-span-2 sm:col-span-1 bg-indigo-50 border border-indigo-200/80 rounded-2xl p-3">
                <div class="text-[10px] font-bold text-indigo-800/70 uppercase">Efisiensi AI</div>
                <div class="font-black text-indigo-700 text-sm mt-0.5" id="res-compression">0%</div>
            </div>
        </div>

        <!-- Hidden containers needed for full modal & actions -->
        <div id="res-markdown-body" class="hidden"></div>
        <div id="res-key-points-box" class="hidden"><ul id="res-key-points-list"></ul></div>
        <span id="res-date" class="hidden"></span>
    </div>

    <!-- ======================================================== -->
    <!-- 5. RIWAYAT PAKET SOAL DOKUMEN (ARSIP) -->
    <!-- ======================================================== -->
    <div class="bg-white rounded-[28px] sm:rounded-[32px] border border-slate-100 shadow-sm p-6 sm:p-8 space-y-6">
        
        <!-- Table Header with Search -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-base sm:text-lg font-black text-slate-900 flex items-center gap-2">
                    <span>Riwayat Paket Soal Dokumen</span>
                    <span class="text-xs font-black px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">{{ $summaries->total() }}</span>
                </h2>
                <p class="text-xs text-slate-400 font-medium mt-0.5">Daftar arsip paket soal yang pernah dibuat dari dokumen materi.</p>
            </div>

            <form method="GET" action="{{ route('admin.document-summarizer.index') }}" class="relative w-full sm:w-64">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama dokumen..."
                       class="w-full pl-9 pr-4 py-2 rounded-full border border-slate-200 text-xs font-semibold text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                @if(request('q'))
                    <a href="{{ route('admin.document-summarizer.index') }}" class="absolute inset-y-0 right-0 flex items-center pr-3 text-xs text-slate-400 hover:text-slate-600">
                        ×
                    </a>
                @endif
            </form>
        </div>

        <div class="border-t border-slate-100"></div>

        @if($summaries->isEmpty())
            <div class="py-12 text-center text-slate-400 space-y-3">
                <div class="w-16 h-16 mx-auto rounded-3xl bg-amber-50 border border-amber-200/80 flex items-center justify-center text-amber-500 shadow-xs">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="text-sm font-extrabold text-slate-700">Belum ada riwayat paket soal dari dokumen.</div>
                <p class="text-xs text-slate-400 max-w-sm mx-auto">Gunakan formulir di atas untuk membuat paket soal pertama Anda.</p>
            </div>
        @else
            <!-- Table View -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 font-extrabold uppercase tracking-wider">
                            <th class="py-3 px-4">Nama Dokumen</th>
                            <th class="py-3 px-3">Jenis Pembuatan</th>
                            <th class="py-3 px-3">Kata Dokumen / Soal</th>
                            <th class="py-3 px-3">Tanggal</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @foreach($summaries as $item)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 font-black text-[10px] flex items-center justify-center uppercase flex-shrink-0">
                                            {{ $item->file_type }}
                                        </span>
                                        <div class="min-w-0">
                                            <div class="font-extrabold text-slate-800 text-sm truncate max-w-[240px]" title="{{ $item->title }}">
                                                {{ $item->title }}
                                            </div>
                                            <div class="text-[11px] text-slate-400 truncate max-w-[240px]" title="{{ $item->original_filename }}">
                                                {{ $item->original_filename }} ({{ $item->formatted_file_size }})
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-3">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200/60">
                                        🎯 Soal & Kuis Latihan
                                    </span>
                                </td>
                                <td class="py-3.5 px-3 whitespace-nowrap">
                                    <div class="font-bold text-slate-800">{{ number_format($item->word_count_original) }} <span class="text-slate-400 font-normal">kata sumber</span></div>
                                    <div class="text-[11px] text-blue-600 font-bold">↳ {{ number_format($item->word_count_summary) }} kata soal</div>
                                </td>
                                <td class="py-3.5 px-3 whitespace-nowrap text-slate-500">
                                    <div>{{ $item->created_at->translatedFormat('d M Y') }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $item->created_at->format('H:i') }} WIB</div>
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Lihat Modal -->
                                        <button type="button" onclick="viewSummaryModal({{ $item->id }})"
                                                class="px-2.5 py-1.5 rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-100 font-bold text-xs transition-all" title="Baca Soal">
                                            Lihat
                                        </button>

                                        <!-- Unduh -->
                                        <a href="{{ route('admin.document-summarizer.download', $item->id) }}"
                                           class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all" title="Unduh File">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        </a>

                                        <!-- Hapus -->
                                        <form action="{{ route('admin.document-summarizer.destroy', $item->id) }}" method="POST"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus arsip soal ini?');" class="inline m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-xl text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all" title="Hapus Riwayat">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pt-4 border-t border-slate-100">
                {{ $summaries->links() }}
            </div>
        @endif

    </div>

</div>

<!-- ======================================================== -->
<!-- MODAL POPUP: LIHAT RINGKASAN LENGKAP (Riwayat) -->
<!-- ======================================================== -->
<div id="summary-modal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-3xl w-full max-h-[90vh] flex flex-col shadow-2xl border-2 border-amber-200 overflow-hidden">
        
        <!-- Modal Top Bar -->
        <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between bg-[#FAF6EF]/60">
            <div class="min-w-0 pr-4">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-purple-100 text-purple-800 uppercase" id="modal-badge-style">
                    Intisari & Poin Penting
                </span>
                <h3 class="text-lg font-black text-slate-900 truncate mt-1" id="modal-title">Judul Dokumen</h3>
                <p class="text-xs text-slate-500 font-medium" id="modal-meta">Berkas: - • Dibuat: -</p>
            </div>

            <button type="button" onclick="closeSummaryModal()" class="w-9 h-9 rounded-2xl bg-white border border-slate-200 text-slate-400 hover:text-slate-800 flex items-center justify-center transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Modal Body Content -->
        <div class="p-5 sm:p-6 overflow-y-auto flex-1 space-y-5">
            <div id="modal-markdown-body" class="prose prose-purple max-w-none text-slate-700 text-sm leading-relaxed">
                <!-- Modal Markdown content rendered here -->
            </div>

            <div id="modal-key-points-box" class="bg-indigo-50/70 border border-indigo-200 rounded-2xl p-4 space-y-2">
                <div class="text-xs font-black text-indigo-900 uppercase tracking-wider">📌 Poin Kunci Utama</div>
                <ul class="text-xs font-medium text-slate-700 space-y-1.5 list-disc pl-4" id="modal-key-points-list"></ul>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-4 sm:p-5 border-t border-slate-100 bg-slate-50 flex items-center justify-between">
            <button type="button" onclick="copyModalContent()" class="px-4 py-2 rounded-xl text-xs font-bold bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                <span>Salin Teks Ringkasan</span>
            </button>

            <button type="button" onclick="closeSummaryModal()" class="px-5 py-2 rounded-xl text-xs font-black bg-purple-600 text-white hover:bg-purple-700 shadow-md">
                Tutup
            </button>
        </div>

    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL POPUP BESAR: HASIL SOAL AI (Setelah Generate) -->
<!-- ======================================================== -->
<div id="result-popup-modal" class="fixed inset-0 z-[60] hidden" style="background: rgba(15,15,35,0.75); backdrop-filter: blur(6px);">
    <div class="flex items-stretch justify-center min-h-screen p-3 sm:p-5">
        <div class="bg-white rounded-3xl w-full max-w-6xl flex flex-col shadow-2xl border border-white/20 overflow-hidden" style="max-height: calc(100vh - 24px);">

            <!-- Popup Header Bar -->
            <div class="flex items-center justify-between gap-4 px-6 sm:px-8 py-4 border-b border-slate-100 bg-gradient-to-r from-purple-700 via-indigo-700 to-indigo-600 flex-shrink-0">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-2xl bg-white/20 flex items-center justify-center text-white font-black text-lg flex-shrink-0">🤖</div>
                    <div class="min-w-0">
                        <h2 class="text-base sm:text-lg font-black text-white truncate" id="popup-res-title">Paket Soal AI</h2>
                        <div class="flex items-center gap-2 text-purple-200 text-xs font-semibold mt-0.5">
                            <span id="popup-res-badge-type"></span>
                            <span>•</span>
                            <span id="popup-res-provider" class="text-amber-300"></span>
                            <span>•</span>
                            <span id="popup-res-date"></span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <!-- Action buttons in header -->
                    <button type="button" onclick="openPublishModal()" title="Kirim ke Siswa"
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-black bg-emerald-500 hover:bg-emerald-400 text-white shadow-md transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        <span class="hidden sm:inline">Kirim ke Siswa</span>
                    </button>
                    <button type="button" onclick="copySummaryText()" title="Salin Semua"
                            class="p-2 rounded-xl bg-white/15 hover:bg-white/25 text-white transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </button>
                    <button type="button" onclick="downloadCurrentSummary()" title="Unduh .MD"
                            class="p-2 rounded-xl bg-white/15 hover:bg-white/25 text-white transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </button>
                    <button type="button" onclick="printSummary()" title="Cetak"
                            class="p-2 rounded-xl bg-white/15 hover:bg-white/25 text-white transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    </button>
                    <button type="button" onclick="closeResultPopup()"
                            class="w-8 h-8 rounded-xl bg-white/20 hover:bg-white/30 text-white flex items-center justify-center font-black text-lg transition-all ml-1">&times;</button>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="flex items-center gap-6 px-6 sm:px-8 py-3 bg-slate-50 border-b border-slate-100 flex-shrink-0 text-xs overflow-x-auto">
                <div>
                    <span class="text-slate-400 font-bold uppercase tracking-wider">Kata Sumber</span>
                    <span class="font-black text-slate-800 ml-2" id="popup-res-words-original">—</span>
                </div>
                <div class="w-px h-4 bg-slate-200"></div>
                <div>
                    <span class="text-slate-400 font-bold uppercase tracking-wider">Panjang Soal</span>
                    <span class="font-black text-purple-700 ml-2" id="popup-res-words-summary">—</span>
                </div>
                <div class="w-px h-4 bg-slate-200"></div>
                <div>
                    <span class="text-slate-400 font-bold uppercase tracking-wider">AI Score</span>
                    <span class="font-black text-indigo-700 ml-2" id="popup-res-compression">—</span>
                </div>
                <div class="ml-auto flex-shrink-0">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Soal Siap Digunakan
                    </span>
                </div>
            </div>

            <!-- Main Body: Questions Scroll Area -->
            <div class="flex-1 overflow-y-auto p-5 sm:p-8 space-y-0" id="popup-res-markdown-body" style="scroll-behavior: smooth;">
                <!-- Dynamic question cards rendered here -->
            </div>

            <!-- Key Points (if any) -->
            <div id="popup-res-key-points-box" class="hidden mx-5 sm:mx-8 mb-5 bg-indigo-50/70 border border-indigo-200 rounded-2xl p-4 space-y-2.5 flex-shrink-0">
                <div class="text-xs font-black text-indigo-900 uppercase tracking-wider flex items-center gap-1.5">
                    <span>📌 Rangkuman Konsep Soal</span>
                </div>
                <ul class="text-xs font-medium text-slate-700 space-y-2 list-none" id="popup-res-key-points-list"></ul>
            </div>

            <!-- Footer -->
            <div class="px-6 sm:px-8 py-4 border-t border-slate-100 bg-slate-50/60 flex items-center justify-between gap-3 flex-shrink-0">
                <p class="text-[11px] text-slate-400 font-medium">Dibuat oleh <span class="font-bold text-purple-700">RoboMath AI</span> • Klik soal untuk menyembunyikan/tampilkan pembahasan</p>
                <button type="button" onclick="closeResultPopup()"
                        class="px-5 py-2 rounded-xl text-xs font-black bg-slate-800 text-white hover:bg-slate-700 shadow-md transition-all">
                    Tutup Popup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL: PUBLISH SOAL KE SISWA -->
<!-- ============================================================ -->
<div id="publish-modal" class="hidden fixed inset-0 z-[70] flex items-center justify-center p-4" style="background: rgba(0,0,0,0.55); backdrop-filter: blur(4px);">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-6 sm:p-8 space-y-6 relative border-2 border-emerald-200">
        <!-- Modal Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-emerald-100 flex items-center justify-center text-2xl flex-shrink-0">
                    🚀
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-900">Kirim Soal ke Siswa</h3>
                    <p class="text-xs text-slate-500 font-medium">Pilih topik tujuan dan konfigurasi soal</p>
                </div>
            </div>
            <button type="button" onclick="closePublishModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center font-black text-lg transition-all">&times;</button>
        </div>

        <!-- Preview: jumlah soal yang terdeteksi -->
        <div id="publish-preview" class="hidden p-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-sm font-bold text-emerald-800 flex items-center gap-2">
            <span class="text-lg">✅</span>
            <span id="publish-preview-text"></span>
        </div>

        <!-- Form Fields -->
        <div class="space-y-4">
            <!-- Pilih Topik -->
            <div>
                <label class="block text-xs font-black text-slate-700 mb-1.5 uppercase tracking-wider">Topik Tujuan <span class="text-rose-500">*</span></label>
                <select id="publish-topic-id" class="w-full px-4 py-2.5 rounded-2xl border-2 border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 font-bold text-slate-800 text-sm outline-none transition-all">
                    <option value="">-- Pilih Topik --</option>
                    @foreach(\App\Models\Topic::where('is_active', true)->orderBy('kelas_level')->orderBy('order')->get() as $t)
                        <option value="{{ $t->id }}">{{ $t->icon }} {{ $t->title }} (Kelas {{ $t->kelas_level }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Tingkat Kesulitan -->
            <div>
                <label class="block text-xs font-black text-slate-700 mb-1.5 uppercase tracking-wider">Tingkat Kesulitan</label>
                <div class="grid grid-cols-3 gap-2">
                    <label class="flex items-center justify-center gap-1.5 px-3 py-2 rounded-2xl border-2 border-slate-200 text-xs font-extrabold cursor-pointer transition-all has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 has-[:checked]:text-emerald-800">
                        <input type="radio" name="publish-difficulty" value="mudah" class="sr-only" checked> 🌱 Mudah
                    </label>
                    <label class="flex items-center justify-center gap-1.5 px-3 py-2 rounded-2xl border-2 border-slate-200 text-xs font-extrabold cursor-pointer transition-all has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50 has-[:checked]:text-amber-800">
                        <input type="radio" name="publish-difficulty" value="sedang" class="sr-only"> ⚡ Sedang
                    </label>
                    <label class="flex items-center justify-center gap-1.5 px-3 py-2 rounded-2xl border-2 border-slate-200 text-xs font-extrabold cursor-pointer transition-all has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50 has-[:checked]:text-rose-800">
                        <input type="radio" name="publish-difficulty" value="sulit" class="sr-only"> 🔥 Sulit
                    </label>
                </div>
            </div>

            <!-- Poin per Soal -->
            <div>
                <label class="block text-xs font-black text-slate-700 mb-1.5 uppercase tracking-wider">Poin per Soal</label>
                <div class="flex items-center gap-3">
                    <input type="range" id="publish-points" min="5" max="50" step="5" value="10"
                           oninput="document.getElementById('publish-points-label').textContent = this.value"
                           class="flex-1 accent-emerald-500">
                    <span class="w-14 text-center font-black text-emerald-700 text-sm bg-emerald-50 border border-emerald-200 px-2 py-1 rounded-xl">
                        <span id="publish-points-label">10</span> Poin
                    </span>
                </div>
            </div>
        </div>

        <!-- Modal Actions -->
        <div class="flex items-center gap-3 pt-2">
            <button type="button" onclick="closePublishModal()" class="flex-1 px-4 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold text-sm transition-all">
                Batal
            </button>
            <button type="button" onclick="submitPublishToStudents()" id="btn-publish-confirm"
                    class="flex-1 flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white font-black text-sm shadow-md transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                <span>Kirim ke Siswa!</span>
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dompurify/dist/purify.min.js"></script>
<script>
    // ============================================================
    // INIT FUNCTION — called on first load & after every SPA swap
    // ============================================================
    function initSummarizerPage() {
        console.log('🤖 initSummarizerPage() executed');

    // State variables — stored on window so they survive re-init on SPA navigation
    if (typeof window._summarizerState === 'undefined') {
        window._summarizerState = {
            inputMode: 'file',
            activeSummaryMarkdown: '',
            activeSummaryTitle: '',
            modalMarkdownCache: '',
        };
    }
    const state = window._summarizerState;

    // Switch Input Mode between Upload File and Paste Text
    window.setInputMode = function(mode) {
        console.log('🔄 Switched input mode to:', mode);
        state.inputMode = mode;
        const fileContainer = document.getElementById('mode-file-container');
        const textContainer = document.getElementById('mode-text-container');
        const btnFile = document.getElementById('btn-mode-file');
        const btnText = document.getElementById('btn-mode-text');

        if (mode === 'file') {
            if (fileContainer) fileContainer.classList.remove('hidden');
            if (textContainer) textContainer.classList.add('hidden');
            if (btnFile) btnFile.className = 'px-4 py-1.5 rounded-full transition-all bg-white text-slate-900 shadow-sm border border-slate-200/80 font-black';
            if (btnText) btnText.className = 'px-4 py-1.5 rounded-full transition-all text-slate-500 font-bold hover:text-slate-900';
        } else {
            if (fileContainer) fileContainer.classList.add('hidden');
            if (textContainer) textContainer.classList.remove('hidden');
            if (btnText) btnText.className = 'px-4 py-1.5 rounded-full transition-all bg-white text-slate-900 shadow-sm border border-slate-200/80 font-black';
            if (btnFile) btnFile.className = 'px-4 py-1.5 rounded-full transition-all text-slate-500 font-bold hover:text-slate-900';
        }
    };

    // Handle File Selection from Input
    window.handleFileSelected = function(input) {
        console.log('📁 handleFileSelected triggered with files:', input.files);
        if (!input.files || !input.files[0]) {
            console.warn('⚠️ No file selected');
            return;
        }
        const file = input.files[0];
        displaySelectedFile(file);
    };

    // Display Selected File Details in Preview Card
    window.displaySelectedFile = function(file) {
        console.log('👁️ displaySelectedFile for:', file.name, 'size:', file.size);
        if (!file) return;
        const ext = file.name.split('.').pop().toUpperCase();
        const sizeFormatted = formatFileSize(file.size);

        const badgeContainer = document.getElementById('file-ext-badge-container');
        const badgeText = document.getElementById('file-ext-badge');
        const iconEmoji = document.getElementById('file-icon-emoji');
        const nameLabel = document.getElementById('file-name-label');
        const sizeLabel = document.getElementById('file-size-label');
        const dropzone = document.getElementById('dropzone');
        const previewCard = document.getElementById('file-preview-card');

        if (badgeText) badgeText.textContent = ext;
        if (nameLabel) nameLabel.textContent = file.name;
        if (sizeLabel) sizeLabel.textContent = sizeFormatted;

        // Dynamic badge style based on file type
        if (badgeContainer && iconEmoji) {
            if (['PDF'].includes(ext)) {
                badgeContainer.className = 'w-12 h-12 rounded-xl bg-rose-600 text-white flex flex-col items-center justify-center font-black shadow-md flex-shrink-0';
                iconEmoji.textContent = '📄';
            } else if (['DOC', 'DOCX'].includes(ext)) {
                badgeContainer.className = 'w-12 h-12 rounded-xl bg-blue-600 text-white flex flex-col items-center justify-center font-black shadow-md flex-shrink-0';
                iconEmoji.textContent = '📑';
            } else if (['TXT', 'MD', 'MARKDOWN', 'LOG'].includes(ext)) {
                badgeContainer.className = 'w-12 h-12 rounded-xl bg-emerald-600 text-white flex flex-col items-center justify-center font-black shadow-md flex-shrink-0';
                iconEmoji.textContent = '📝';
            } else if (['CSV', 'JSON'].includes(ext)) {
                badgeContainer.className = 'w-12 h-12 rounded-xl bg-amber-600 text-white flex flex-col items-center justify-center font-black shadow-md flex-shrink-0';
                iconEmoji.textContent = '📊';
            } else {
                badgeContainer.className = 'w-12 h-12 rounded-xl bg-purple-600 text-white flex flex-col items-center justify-center font-black shadow-md flex-shrink-0';
                iconEmoji.textContent = '📁';
            }
        }

        if (dropzone) {
            dropzone.style.display = 'none';
            dropzone.classList.add('hidden');
        }
        if (previewCard) {
            previewCard.style.display = 'block';
            previewCard.classList.remove('hidden');
        }

        // Auto-fill custom title if empty
        const titleInput = document.getElementById('custom_title');
        if (titleInput && !titleInput.value) {
            const rawName = file.name.substring(0, file.name.lastIndexOf('.')) || file.name;
            titleInput.value = rawName.replace(/[-_]/g, ' ');
        }
    };

    // Clear Selected File
    window.clearSelectedFile = function() {
        const input = document.getElementById('document_file');
        if (input) input.value = '';
        const dropzone = document.getElementById('dropzone');
        const previewCard = document.getElementById('file-preview-card');
        if (dropzone) {
            dropzone.style.display = 'block';
            dropzone.classList.remove('hidden');
        }
        if (previewCard) {
            previewCard.style.display = 'none';
            previewCard.classList.add('hidden');
        }
    };

    // Live Word Counter for Text Input
    window.updateTextCounter = function(textarea) {
        const text = textarea.value.trim();
        const words = text ? text.split(/\s+/).length : 0;
        const counterElem = document.getElementById('text-char-count');
        if (counterElem) counterElem.textContent = `${words} kata`;
    };

    // Set Prompt Template
    window.setPromptTemplate = function(text) {
        const promptInput = document.getElementById('custom_prompt');
        if (promptInput) {
            promptInput.value = text;
            promptInput.focus();
        }
    };

    // Format Bytes helper
    function formatFileSize(bytes) {
        if (!bytes || bytes <= 0) return '0 B';
        if (bytes >= 1048576) return (bytes / 1048576).toFixed(1) + ' MB';
        if (bytes >= 1024) return (bytes / 1024).toFixed(1) + ' KB';
        return bytes + ' B';
    }

    // Kid-Friendly Quiz & Markdown Parser
    function parseMarkdownToHtml(markdownText) {
        if (!markdownText) return '';

        // Clean initial conversational intro if present
        let cleaned = markdownText.replace(/^(?:Sebagai\s+Asisten|Tentu|Halo|Berikut\s+adalah|Saya\s+telah|Baiklah|Berikut\s+ini).*?(?=(##|###|📝|Soal\s+1))/is, '');
        cleaned = cleaned.trim();

        try {
            // Check if text contains structured questions (e.g. ### Soal 1 or ### 📝 Soal 1)
            const questionRegex = /###\s*(?:📝|🌟)?\s*Soal\s*(\d+)[\s\S]*?(?=(?:###\s*(?:📝|🌟)?\s*Soal\s*\d+|###\s*📌|$))/gi;
            const matches = [...cleaned.matchAll(questionRegex)];

            if (matches.length > 0) {
                let finalHtml = '';

                // Extract any main title (## ...) before first question
                const headerMatch = cleaned.match(/^##\s*(.+)$/m);
                if (headerMatch) {
                    const cleanTitle = headerMatch[1].replace(/[*#]/g, '').trim();
                    finalHtml += `
                        <div class="p-4 sm:p-5 rounded-3xl bg-gradient-to-r from-purple-100 via-indigo-50 to-purple-50 border-2 border-purple-200/80 mb-6 shadow-sm">
                            <div class="flex items-center gap-2.5">
                                <span class="text-2xl">🎯</span>
                                <div>
                                    <h2 class="text-base sm:text-lg font-black text-purple-950">${cleanTitle}</h2>
                                    <p class="text-xs font-semibold text-purple-700/80 mt-0.5">Paket latihan soal dan kuis edukatif untuk siswa SD.</p>
                                </div>
                            </div>
                        </div>
                    `;
                }

                // Process each question
                matches.forEach((m, idx) => {
                    const block = m[0];
                    const numMatch = block.match(/###\s*(?:📝|🌟)?\s*Soal\s*(\d+)/i);
                    const qNum = numMatch ? numMatch[1] : (idx + 1);

                    // Extract options A, B, C, D strictly from dedicated lines (e.g. "- A. ...", "A. ...")
                    const options = [];
                    const lines = block.split('\n');
                    lines.forEach(line => {
                        const trimmed = line.trim();
                        // Only match lines explicitly formatted as option items (not blockquotes or headers)
                        if (!trimmed.startsWith('>') && !trimmed.startsWith('###') && !trimmed.includes('Kunci Jawaban') && !trimmed.includes('Pembahasan')) {
                            const optLineMatch = trimmed.match(/^(?:[-*]\s*)?([A-D])[\.\)]\s+(.+)$/i);
                            if (optLineMatch) {
                                const letter = optLineMatch[1].toUpperCase();
                                const optText = optLineMatch[2].replace(/[*_]/g, '').trim();
                                if (optText && !options.some(o => o.letter === letter)) {
                                    options.push({ letter, text: optText });
                                }
                            }
                        }
                    });

                    // A question is ONLY Multiple Choice if it has at least 2 valid option items (e.g. A and B)
                    const isMultipleChoice = options.length >= 2;

                    // Extract question body: lines before options or blockquotes
                    const bodyLines = [];
                    for (const line of lines) {
                        const trimmed = line.trim();
                        if (/^###\s*(?:📝|🌟)?\s*Soal/i.test(trimmed)) {
                            continue;
                        }
                        if (trimmed.startsWith('>') || /^(?:[-*]\s*)?[A-D][\.\)]\s+/i.test(trimmed) || /^(?:🔑|💡|\*\*🔑|\*\*💡)/i.test(trimmed)) {
                            break;
                        }
                        if (trimmed) {
                            bodyLines.push(trimmed);
                        }
                    }
                    const questionBody = bodyLines.join(' ').replace(/[*_#]/g, '').trim();

                    // Extract Answer Key & Explanation
                    let keyAnswer = '';
                    let explanation = '';
                    const keyMatch = block.match(/(?:🔑\s*Kunci\s*Jawaban|Kunci\s*Jawaban)\s*:\s*\*?\*?\s*([^\n]+)/i);
                    if (keyMatch) {
                        keyAnswer = keyMatch[1].replace(/[*_>]/g, '').trim();
                    }

                    const expMatch = block.match(/(?:💡\s*Pembahasan(?:\s*Seru|\s*&\s*Langkah)?|Pembahasan(?:\s*&\s*Langkah)?)\s*:\s*\*?\*?\s*([^\n\r#]+)/i);
                    if (expMatch) {
                        explanation = expMatch[1].replace(/[*_>]/g, '').trim();
                    }

                    // Badge colors per letter
                    const badgeStyles = {
                        'A': 'bg-blue-100 text-blue-800 border-blue-200',
                        'B': 'bg-purple-100 text-purple-800 border-purple-200',
                        'C': 'bg-amber-100 text-amber-800 border-amber-200',
                        'D': 'bg-emerald-100 text-emerald-800 border-emerald-200'
                    };

                    let optionsHtml = '';
                    if (isMultipleChoice) {
                        optionsHtml = `
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-2">
                                ${options.map(opt => `
                                    <div class="flex items-start gap-3 p-3 rounded-2xl bg-slate-50/80 hover:bg-purple-50/60 border-2 border-slate-200 hover:border-purple-300 transition-all text-xs sm:text-sm font-bold text-slate-700 group cursor-default">
                                        <span class="w-6 h-6 rounded-xl ${badgeStyles[opt.letter] || 'bg-purple-100 text-purple-800'} border flex items-center justify-center font-black text-xs flex-shrink-0 shadow-xs">
                                            ${opt.letter}
                                        </span>
                                        <span class="leading-relaxed mt-0.5 group-hover:text-purple-950">${opt.text}</span>
                                    </div>
                                `).join('')}
                            </div>
                        `;
                    }

                    let answerBoxHtml = '';
                    if (keyAnswer || explanation) {
                        answerBoxHtml = `
                            <div class="mt-3.5 p-4 rounded-2xl bg-emerald-50/90 border-2 border-emerald-200 text-emerald-950 space-y-1.5 shadow-xs">
                                ${keyAnswer ? `
                                    <div class="flex items-center gap-1.5 text-xs font-black text-emerald-900 uppercase tracking-wider">
                                        <span>🔑 Kunci Jawaban:</span>
                                        <span class="text-emerald-950 bg-emerald-100 px-2 py-0.5 rounded-lg border border-emerald-300">${keyAnswer}</span>
                                    </div>
                                ` : ''}
                                ${explanation ? `
                                    <div class="text-xs font-semibold text-emerald-900/90 leading-relaxed flex items-start gap-2 pt-0.5">
                                        <span class="text-base leading-none flex-shrink-0">💡</span>
                                        <div><strong class="font-extrabold text-emerald-950">Pembahasan:</strong> ${explanation}</div>
                                    </div>
                                ` : ''}
                            </div>
                        `;
                    }

                    finalHtml += `
                        <div class="quiz-question-card bg-white rounded-3xl p-5 sm:p-6 border-2 border-purple-200/90 shadow-sm hover:shadow-md transition-all space-y-3.5 mb-5 relative">
                            <div class="flex items-center justify-between gap-3 border-b border-slate-100 pb-3">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-gradient-to-r from-purple-600 via-indigo-600 to-indigo-700 text-white shadow-xs">
                                    🌟 Soal #${qNum}
                                </span>
                                <span class="text-[10px] font-black uppercase tracking-wider ${isMultipleChoice ? 'text-purple-700 bg-purple-50 border-purple-200/70' : 'text-rose-700 bg-rose-50 border-rose-200/70'} px-2.5 py-0.5 rounded-full border">
                                    ${isMultipleChoice ? 'Pilihan Ganda' : '✍️ Soal Essay / Uraian'}
                                </span>
                            </div>

                            <div class="text-sm sm:text-base font-extrabold text-slate-800 leading-relaxed pt-1">
                                ${questionBody || 'Pertanyaan materi latihan'}
                            </div>

                            ${optionsHtml}
                            ${answerBoxHtml}
                        </div>
                    `;
                });

                // Check for concept summary section at the end
                const conceptMatch = cleaned.match(/###\s*📌\s*Rangkuman Konsep Kunci([\s\S]*)$/i);
                if (conceptMatch) {
                    const bulletLines = conceptMatch[1].split('\n')
                        .map(l => l.replace(/^[-*]\s*/, '').replace(/[*_#]/g, '').trim())
                        .filter(l => l.length > 5);

                    if (bulletLines.length > 0) {
                        finalHtml += `
                            <div class="mt-6 p-5 sm:p-6 rounded-3xl bg-amber-50/90 border-2 border-amber-200/90 shadow-sm space-y-3">
                                <div class="text-xs sm:text-sm font-black text-amber-950 uppercase tracking-wider flex items-center gap-2">
                                    <span class="text-lg">📌</span>
                                    <span>Rangkuman Konsep Kunci</span>
                                </div>
                                <ul class="space-y-2 text-xs sm:text-sm font-bold text-amber-900/90 list-none pl-0">
                                    ${bulletLines.map(line => `
                                        <li class="flex items-start gap-2">
                                            <span class="text-amber-600 font-black text-base leading-none">•</span>
                                            <span class="leading-relaxed">${line}</span>
                                        </li>
                                    `).join('')}
                                </ul>
                            </div>
                        `;
                    }
                }

                if (typeof DOMPurify !== 'undefined') {
                    finalHtml = DOMPurify.sanitize(finalHtml);
                }

                return finalHtml;
            }
        } catch (err) {
            console.warn('Custom quiz parser error, using standard parser fallback:', err);
        }

        // Standard Fallback with Marked.js
        if (typeof marked !== 'undefined' && typeof marked.parse === 'function') {
            try {
                let parsedHtml = marked.parse(cleaned);
                if (typeof DOMPurify !== 'undefined') {
                    parsedHtml = DOMPurify.sanitize(parsedHtml);
                }
                return parsedHtml;
            } catch (e) {
                console.warn('Marked.parse fallback error:', e);
            }
        }

        // Basic Regex Fallback
        let html = cleaned
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/^### (.*$)/gim, '<h3 class="text-base font-bold text-slate-900 mt-4 mb-2">$1</h3>')
            .replace(/^## (.*$)/gim, '<h2 class="text-lg font-black text-purple-900 mt-5 mb-2 pb-1 border-b border-purple-100">$1</h2>')
            .replace(/^\> (.*$)/gim, '<blockquote class="border-l-4 border-purple-500 bg-purple-50/60 p-3 my-3 rounded-r-xl text-slate-700 italic">$1</blockquote>')
            .replace(/\*\*(.*?)\*\*/gim, '<strong class="font-bold text-slate-900">$1</strong>')
            .replace(/^\- (.*$)/gim, '<li class="ml-4 list-disc text-slate-700 my-1">$1</li>')
            .replace(/\n\n+/g, '</p><p class="my-2 leading-relaxed">')
            .replace(/\n/g, '<br>');

        return `<p class="my-2 leading-relaxed">${html}</p>`;
    }

    // Initialize Dropzone and Drag-Drop
    function initDropzoneEvents() {
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('document_file');
        
        console.log('🎯 initDropzoneEvents() - dropzone:', !!dropzone, 'fileInput:', !!fileInput);

        if (fileInput) {
            // Remove old listener to avoid duplicates
            fileInput.removeEventListener('change', window._fileInputHandler);
            
            // Define handler
            window._fileInputHandler = function(e) {
                console.log('📎 File input change event fired');
                handleFileSelected(this);
            };
            
            // Attach new listener
            fileInput.addEventListener('change', window._fileInputHandler);
        }

        if (dropzone && fileInput) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }, false);
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, function() {
                    dropzone.classList.add('border-purple-600', 'bg-purple-100', 'scale-[1.01]', 'ring-4', 'ring-purple-500/20');
                }, false);
            });

            ['dragleave', 'dragend', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, function() {
                    dropzone.classList.remove('border-purple-600', 'bg-purple-100', 'scale-[1.01]', 'ring-4', 'ring-purple-500/20');
                }, false);
            });

            dropzone.addEventListener('drop', function(e) {
                console.log('👇 File dropped into dropzone');
                const dt = e.dataTransfer;
                const files = dt.files;
                if (files && files.length > 0) {
                    fileInput.files = files;
                    displaySelectedFile(files[0]);
                }
            }, false);
        }
    }

    initDropzoneEvents();

    // Handle Form Submit
    const formElement = document.getElementById('summarizer-form');
    if (formElement && !formElement.dataset.listenerAttached) {
        formElement.dataset.listenerAttached = '1';

        formElement.addEventListener('submit', async function(e) {
            e.preventDefault();
            e.stopPropagation();

            const fileInput = document.getElementById('document_file');
            const textInput = document.getElementById('direct_text');

            if (state.inputMode === 'file' && (!fileInput || !fileInput.files || !fileInput.files[0])) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Berkas Belum Dipilih',
                    text: 'Silakan pilih atau seret berkas dokumen (PDF, DOCX, TXT) terlebih dahulu!',
                    confirmButtonColor: '#7C3AED'
                });
                return;
            }

            if (state.inputMode === 'text' && (!textInput || !textInput.value || textInput.value.trim().length < 10)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Teks Terlalu Singkat',
                    text: 'Silakan masukkan teks materi minimal 10 karakter agar AI dapat menganalisisnya!',
                    confirmButtonColor: '#7C3AED'
                });
                return;
            }

            // Show Loading State
            showLoadingState(true);

            const formData = new FormData(formElement);
            if (state.inputMode === 'file') {
                formData.delete('direct_text');
                // Make sure the file is included
                if (fileInput && fileInput.files[0]) {
                    formData.set('document_file', fileInput.files[0]);
                }
            } else {
                formData.delete('document_file');
            }

            try {
                // AbortController: 210s timeout (slightly > PHP's 180s LM Studio timeout)
                const controller = new AbortController();
                const fetchTimeout = setTimeout(() => controller.abort(), 210000);

                const response = await fetch("{{ route('admin.document-summarizer.summarize') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    body: formData,
                    signal: controller.signal
                });
                clearTimeout(fetchTimeout);

                const data = await response.json();

                if (!response.ok || !data.success) {
                    let errorMsg = data.message || 'Terjadi kesalahan saat memproses ringkasan dokumen.';
                    if (data.errors) {
                        const validationMessages = Object.values(data.errors).flat();
                        if (validationMessages.length > 0) {
                            errorMsg = validationMessages.join('\n');
                        }
                    }
                    throw new Error(errorMsg);
                }

                // Render Output into Result Card
                renderSummaryResult(data);

                Swal.fire({
                    icon: 'success',
                    title: 'Paket Soal Berhasil Dibuat!',
                    text: `AI telah berhasil merangkum materi dan merancang butir-butir soal latihan.`,
                    timer: 2500,
                    showConfirmButton: false
                });

            } catch (error) {
                console.error(error);
                const isTimeout = error.name === 'AbortError';
                Swal.fire({
                    icon: 'error',
                    title: isTimeout ? 'Waktu Habis (Timeout)' : 'Gagal Membuat Soal',
                    text: isTimeout
                        ? 'Model AI lokal terlalu lama merespons. Pastikan LM Studio aktif dan model sudah dimuat, lalu coba lagi.'
                        : (error.message || 'Terjadi gangguan koneksi pada server AI.'),
                    confirmButtonColor: '#7C3AED'
                });
            } finally {
                showLoadingState(false);
            }
        });
    }

    // Toggle Loading State
    function showLoadingState(isLoading) {
        const placeholder = document.getElementById('result-placeholder');
        const loading = document.getElementById('result-loading');
        const container = document.getElementById('result-container');
        const submitBtn = document.getElementById('btn-submit-summarize');

        if (isLoading) {
            if (placeholder) { placeholder.style.display = 'none'; placeholder.classList.add('hidden'); }
            if (container) { container.style.display = 'none'; container.classList.add('hidden'); }
            if (loading) { loading.style.display = 'flex'; loading.classList.remove('hidden'); }
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            }

            const messages = [
                'Membaca berkas dokumen...',
                'Mengekstrak materi dokumen...',
                'Mengirim materi ke model AI...',
                'AI sedang menganalisis materi dan merancang butir soal... (1-3 menit untuk AI lokal)',
                'Menyusun opsi jawaban, kunci, dan pembahasan...',
                'Hampir selesai, memformat paket soal...'
            ];
            let step = 0;
            const textElem = document.getElementById('loading-stage-text');
            window.loadingInterval = setInterval(() => {
                step = (step + 1) % messages.length;
                if (textElem) textElem.textContent = messages[step];
            }, 2000);

        } else {
            if (loading) { loading.style.display = 'none'; loading.classList.add('hidden'); }
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
            }
            if (window.loadingInterval) clearInterval(window.loadingInterval);
        }
    }

    // Render Summary Result into UI — shows compact card + opens popup automatically
    function renderSummaryResult(data) {
        const placeholder = document.getElementById('result-placeholder');
        const loading = document.getElementById('result-loading');
        const container = document.getElementById('result-container');

        if (placeholder) { placeholder.style.display = 'none'; placeholder.classList.add('hidden'); }
        if (loading) { loading.style.display = 'none'; loading.classList.add('hidden'); }
        if (container) { container.style.display = 'block'; container.classList.remove('hidden'); }

        state.activeSummaryMarkdown = data.summary_markdown;
        state.activeSummaryTitle = data.title;

        // Populate compact card fields
        const badgeType = document.getElementById('res-badge-type');
        const resTitle = document.getElementById('res-title');
        const resDate = document.getElementById('res-date');
        const resProvider = document.getElementById('res-provider');
        const resCompression = document.getElementById('res-compression');
        const resWordsOrig = document.getElementById('res-words-original');
        const resWordsSum = document.getElementById('res-words-summary');
        const markdownBody = document.getElementById('res-markdown-body');

        if (badgeType) badgeType.textContent = `${data.file_type} • ${data.file_size_formatted}`;
        if (resTitle) resTitle.textContent = data.title;
        if (resDate) resDate.textContent = data.created_at;
        if (resProvider) resProvider.textContent = data.provider;
        if (resCompression) resCompression.textContent = `${data.compression_ratio}%`;
        if (resWordsOrig) resWordsOrig.textContent = `${(data.word_count_original || 0).toLocaleString()} Kata`;
        if (resWordsSum) resWordsSum.textContent = `${(data.word_count_summary || 0).toLocaleString()} Kata`;

        // Store rendered HTML in hidden container
        if (markdownBody) {
            markdownBody.innerHTML = parseMarkdownToHtml(data.summary_markdown);
        }

        // Render Key Points (hidden compact version)
        const keyPointsBox = document.getElementById('res-key-points-box');
        const keyPointsList = document.getElementById('res-key-points-list');
        if (keyPointsList) {
            keyPointsList.innerHTML = '';
            if (data.key_points && data.key_points.length > 0) {
                if (keyPointsBox) keyPointsBox.classList.remove('hidden');
                data.key_points.forEach(point => {
                    const li = document.createElement('li');
                    li.className = 'flex items-start gap-2 text-xs font-medium text-slate-700';
                    li.innerHTML = `<span class="text-indigo-600 font-black flex-shrink-0">•</span><span>${point}</span>`;
                    keyPointsList.appendChild(li);
                });
            } else {
                if (keyPointsBox) keyPointsBox.classList.add('hidden');
            }
        }

        // Scroll compact card into view then auto-open popup
        if (container) {
            container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
        setTimeout(() => openResultPopup(), 400);
    }

    // Open the large AI result popup modal
    window.openResultPopup = function() {
        const popupModal = document.getElementById('result-popup-modal');
        if (!popupModal) return;

        // Sync header data from compact card
        const fields = [
            ['popup-res-title',          'res-title'],
            ['popup-res-badge-type',     'res-badge-type'],
            ['popup-res-provider',       'res-provider'],
            ['popup-res-date',           'res-date'],
            ['popup-res-compression',    'res-compression'],
            ['popup-res-words-original', 'res-words-original'],
            ['popup-res-words-summary',  'res-words-summary'],
        ];
        fields.forEach(([popupId, srcId]) => {
            const src = document.getElementById(srcId);
            const dst = document.getElementById(popupId);
            if (src && dst) dst.textContent = src.textContent;
        });

        // Clone rendered question cards into popup body
        const srcBody = document.getElementById('res-markdown-body');
        const dstBody = document.getElementById('popup-res-markdown-body');
        if (srcBody && dstBody) {
            dstBody.innerHTML = srcBody.innerHTML;
        }

        // Clone key points
        const srcKpList = document.getElementById('res-key-points-list');
        const dstKpList = document.getElementById('popup-res-key-points-list');
        const dstKpBox  = document.getElementById('popup-res-key-points-box');
        if (dstKpList && srcKpList) {
            dstKpList.innerHTML = srcKpList.innerHTML;
            if (dstKpBox) {
                if (srcKpList.children.length > 0) dstKpBox.classList.remove('hidden');
                else dstKpBox.classList.add('hidden');
            }
        }

        popupModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };

    // Close the large result popup
    window.closeResultPopup = function() {
        const popupModal = document.getElementById('result-popup-modal');
        if (popupModal) popupModal.classList.add('hidden');
        document.body.style.overflow = '';
    };

    // Close popup when clicking backdrop
    document.getElementById('result-popup-modal')?.addEventListener('click', function(e) {
        if (e.target === this || e.target === this.firstElementChild) closeResultPopup();
    });

    // Copy Summary Text
    window.copySummaryText = function() {
        if (!state.activeSummaryMarkdown) return;
        navigator.clipboard.writeText(state.activeSummaryMarkdown).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Teks Berhasil Disalin!',
                text: 'Ringkasan dokumen telah disalin ke papan klip Anda.',
                timer: 1800,
                showConfirmButton: false
            });
        });
    };

    // Download Current Summary as Markdown
    window.downloadCurrentSummary = function() {
        if (!state.activeSummaryMarkdown) return;
        const blob = new Blob([state.activeSummaryMarkdown], { type: 'text/markdown;charset=utf-8' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        const safeTitle = (state.activeSummaryTitle || 'Ringkasan_Dokumen').replace(/[^a-zA-Z0-9_-]/g, '_');
        a.href = url;
        a.download = `Ringkasan_RoboMath_${safeTitle}.md`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    };

    // Print Summary
    window.printSummary = function() {
        const bodyElem = document.getElementById('res-markdown-body');
        const printContent = bodyElem ? bodyElem.innerHTML : '';
        const title = state.activeSummaryTitle || 'Ringkasan Dokumen RoboMath';
        const win = window.open('', '_blank', 'height=700,width=900');
        if (!win) {
            Swal.fire({
                icon: 'warning',
                title: 'Pop-up Diblokir',
                text: 'Harap izinkan pop-up di peramban Anda untuk mencetak dokumen.',
                confirmButtonColor: '#7C3AED'
            });
            return;
        }

        win.document.title = title;
        
        const style = win.document.createElement('style');
        style.textContent = `
            body { font-family: 'Segoe UI', Arial, sans-serif; padding: 40px; color: #1e293b; line-height: 1.6; }
            h1, h2, h3 { color: #4338ca; }
            blockquote { border-left: 4px solid #6366f1; padding-left: 15px; color: #475569; font-style: italic; background: #f8fafc; padding: 10px 15px; border-radius: 4px; }
            hr { border: 0; border-top: 1px solid #e2e8f0; margin: 20px 0; }
        `;
        win.document.head.appendChild(style);

        const container = win.document.createElement('div');
        const headerTitle = win.document.createElement('h1');
        headerTitle.textContent = title;
        
        const subtitle = win.document.createElement('p');
        subtitle.style.color = '#64748b';
        subtitle.style.fontSize = '12px';
        subtitle.textContent = 'Diringkas oleh RoboMath AI Document Summarizer';
        
        const divider = win.document.createElement('hr');
        const contentDiv = win.document.createElement('div');
        contentDiv.innerHTML = printContent;
        
        container.appendChild(headerTitle);
        container.appendChild(subtitle);
        container.appendChild(divider);
        container.appendChild(contentDiv);
        
        win.document.body.appendChild(container);
        
        win.focus();
        setTimeout(() => {
            win.print();
        }, 300);
    };

    // View Summary Modal (from History Table)
    window.viewSummaryModal = async function(summaryId) {
        try {
            const response = await fetch(`{{ url('admin/document-summarizer') }}/${summaryId}`, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await response.json();

            state.modalMarkdownCache = data.summary;
            const titleElem = document.getElementById('modal-title');
            const styleElem = document.getElementById('modal-badge-style');
            const metaElem = document.getElementById('modal-meta');
            const modalBody = document.getElementById('modal-markdown-body');

            if (titleElem) titleElem.textContent = data.title;
            if (styleElem) styleElem.textContent = data.summary_style;
            if (metaElem) metaElem.textContent = `Berkas: ${data.original_filename} (${data.file_size}) • Hemat: ${data.compression_ratio}% kata • Oleh: ${data.user_name} • ${data.created_at}`;

            if (modalBody) {
                modalBody.innerHTML = parseMarkdownToHtml(data.summary);
            }

            const pointsList = document.getElementById('modal-key-points-list');
            const pointsBox = document.getElementById('modal-key-points-box');

            if (pointsList) {
                pointsList.innerHTML = '';
                if (data.key_points && data.key_points.length > 0) {
                    if (pointsBox) pointsBox.classList.remove('hidden');
                    data.key_points.forEach(point => {
                        const li = document.createElement('li');
                        li.textContent = point;
                        pointsList.appendChild(li);
                    });
                } else {
                    if (pointsBox) pointsBox.classList.add('hidden');
                }
            }

            const modal = document.getElementById('summary-modal');
            if (modal) modal.classList.remove('hidden');
        } catch (err) {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Gagal Membuka Ringkasan',
                text: 'Tidak dapat memuat detail dokumen.',
                confirmButtonColor: '#7C3AED'
            });
        }
    };

    window.closeSummaryModal = function() {
        const modal = document.getElementById('summary-modal');
        if (modal) modal.classList.add('hidden');
    };

    window.copyModalContent = function() {
        if (!state.modalMarkdownCache) return;
        navigator.clipboard.writeText(state.modalMarkdownCache).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Teks Berhasil Disalin!',
                timer: 1500,
                showConfirmButton: false
            });
        });
    };

    // ============================================================
    // PUBLISH TO STUDENTS
    // ============================================================

    window.openPublishModal = function() {
        if (!state.activeSummaryMarkdown) {
            Swal.fire({ icon: 'warning', title: 'Belum Ada Soal', text: 'Buat soal terlebih dahulu sebelum mengirim ke siswa.', confirmButtonColor: '#059669' });
            return;
        }

        // Check if markdown has MCQ options or is essay
        const hasOptions = /(?:^|\n)\s*[-*]?\s*[A-D][\.\)]\s+/i.test(state.activeSummaryMarkdown);
        const questionBlocks = (state.activeSummaryMarkdown.match(/###\s*(?:📝|🌟)?\s*Soal\s*\d+/gi) || []).length;
        const previewEl = document.getElementById('publish-preview');
        const previewText = document.getElementById('publish-preview-text');
        if (previewEl && previewText) {
            if (questionBlocks > 0) {
                if (hasOptions) {
                    previewText.textContent = `${questionBlocks} butir soal pilihan ganda siap dikirim ke bank soal siswa.`;
                } else {
                    previewText.textContent = `${questionBlocks} butir soal terdeteksi. AI akan otomatis melengkapi dengan opsi jawaban interaktif agar siswa dapat langsung mengerjakan kuis ini di akun mereka.`;
                }
                previewEl.classList.remove('hidden');
            } else {
                previewEl.classList.add('hidden');
            }
        }

        const modal = document.getElementById('publish-modal');
        if (modal) modal.classList.remove('hidden');
    };

    window.closePublishModal = function() {
        const modal = document.getElementById('publish-modal');
        if (modal) modal.classList.add('hidden');
    };

    window.submitPublishToStudents = async function() {
        const topicId = document.getElementById('publish-topic-id')?.value;
        const difficulty = document.querySelector('input[name="publish-difficulty"]:checked')?.value || 'sedang';
        const points = document.getElementById('publish-points')?.value || '10';

        if (!topicId) {
            Swal.fire({ icon: 'warning', title: 'Pilih Topik', text: 'Silakan pilih topik tujuan untuk soal-soal ini.', confirmButtonColor: '#059669' });
            return;
        }

        const confirmBtn = document.getElementById('btn-publish-confirm');
        if (confirmBtn) {
            confirmBtn.disabled = true;
            confirmBtn.innerHTML = '<span class="animate-spin mr-2">⏳</span> Mengirim...';
        }

        try {
            const res = await fetch("{{ route('admin.document-summarizer.publish-to-students') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    topic_id: topicId,
                    markdown: state.activeSummaryMarkdown,
                    difficulty: difficulty,
                    points: parseInt(points),
                })
            });

            const data = await res.json();

            closePublishModal();

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: `🎉 ${data.count} Soal Terkirim!`,
                    html: `<div style="font-size:14px;">${data.message}<br><br><strong>Siswa sudah bisa mengerjakan soal baru ini sekarang.</strong></div>`,
                    confirmButtonColor: '#059669',
                    confirmButtonText: 'Lihat Bank Soal →',
                    showCancelButton: true,
                    cancelButtonText: 'Tutup',
                    cancelButtonColor: '#94a3b8',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('admin.quizzes.index') }}";
                    }
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal Mengirim', text: data.message || 'Terjadi kesalahan.', confirmButtonColor: '#059669' });
            }
        } catch (err) {
            console.error(err);
            Swal.fire({ icon: 'error', title: 'Gagal Mengirim', text: 'Terjadi gangguan koneksi.', confirmButtonColor: '#059669' });
        } finally {
            if (confirmBtn) {
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg><span>Kirim ke Siswa!</span>';
            }
        }
    };

    // Close publish modal when clicking outside
    document.getElementById('publish-modal')?.addEventListener('click', function(e) {
        if (e.target === this) closePublishModal();
    });

    } // end initSummarizerPage()

    // Run on first load
    initSummarizerPage();

    // Re-run after every SPA navigation (SPA replaces DOM content)
    document.addEventListener('robomath:spa-loaded', function() {
        if (document.getElementById('summarizer-form')) {
            initSummarizerPage();
        }
    });
</script>

@endsection
