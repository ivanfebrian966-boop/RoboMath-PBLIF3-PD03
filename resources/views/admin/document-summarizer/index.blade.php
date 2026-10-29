@extends('layouts.admin')

@section('title', 'Ringkasan Dokumen Jadi Soal AI - RoboMath')

@section('content')
<div class="space-y-5 sm:space-y-6">

    <!-- ======================================================== -->
    <!-- 1. HEADER HERO BANNER -->
    <!-- ======================================================== -->
    <div class="rounded-[28px] bg-[#2563EB] p-6 sm:p-8 text-white shadow-md shadow-blue-500/10">
        <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
            Ringkas Dokumen Jadi Soal AI
        </h1>
        <p class="text-blue-100 text-xs sm:text-sm font-medium mt-2 max-w-2xl leading-relaxed">
            Unggah modul, silabus, lembar kerja, atau masukkan teks materi. AI langsung mengubahnya menjadi butir soal latihan lengkap dengan kunci dan pembahasan.
        </p>
    </div>

    <!-- ======================================================== -->
    <!-- 2. METRICS CARD (DOKUMEN DIPROSES | KATA DIANALISIS | SOAL DIBUAT) -->
    <!-- ======================================================== -->
    <div class="bg-white rounded-2xl sm:rounded-3xl p-5 shadow-xs border border-slate-100 grid grid-cols-3 divide-x divide-slate-100 text-center">
        <div class="px-2">
            <div class="text-2xl sm:text-3xl font-black text-[#EF4444]" id="stat-total-summaries">{{ $totalSummaries }}</div>
            <div class="text-[11px] sm:text-xs font-semibold text-slate-400 mt-0.5">Dokumen diproses</div>
        </div>
        <div class="px-2">
            <div class="text-2xl sm:text-3xl font-black text-slate-900">{{ number_format($totalWordsAnalyzed) }}</div>
            <div class="text-[11px] sm:text-xs font-semibold text-slate-400 mt-0.5">Kata dianalisis</div>
        </div>
        <div class="px-2">
            <div class="text-2xl sm:text-3xl font-black text-[#2563EB]">5</div>
            <div class="text-[11px] sm:text-xs font-semibold text-slate-400 mt-0.5">Soal dibuat</div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- 3. STATUS / "MENUNGGU DOKUMEN ANDA" CARD -->
    <!-- ======================================================== -->
    <div class="bg-white rounded-2xl sm:rounded-3xl p-5 sm:p-6 shadow-xs border border-slate-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-[#FEF3C7] text-[#D97706] flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-black text-slate-900 text-sm sm:text-base">Menunggu dokumen Anda</h3>
                <p class="text-xs font-medium text-slate-400 mt-0.5">Dokumen akan dibaca AI setelah Anda menekan tombol mulai.</p>
            </div>
        </div>

        <div class="space-y-1.5 md:text-right">
            <div class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-[#D97706]">HASIL YANG DIBUAT</div>
            <div class="flex flex-wrap items-center gap-3 sm:gap-4 text-xs font-bold text-slate-700">
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-[#EF4444]"></span>
                    Soal otomatis
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-[#F59E0B]"></span>
                    Pilihan ganda A-D
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-[#10B981]"></span>
                    Kunci jawaban
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-[#2563EB]"></span>
                    Pembahasan
                </span>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- 4. MAIN FORM CARD (STEP 1 & STEP 2) -->
    <!-- ======================================================== -->
    <div class="bg-white rounded-3xl sm:rounded-[32px] p-6 sm:p-8 shadow-xs border border-slate-100">
        <form id="summarizer-form" enctype="multipart/form-data" method="POST"
              action="{{ route('admin.document-summarizer.summarize') }}"
              data-no-spa class="space-y-6">
            @csrf

            <!-- Hidden fields for format compatibility -->
            <input type="hidden" name="summary_style" value="kuis_latihan">
            <input type="hidden" name="summary_length" id="summary_length" value="5_soal">

            <!-- STEP 1: PILIH DOKUMEN SUMBER -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-full bg-red-50 text-red-500 border border-red-200 flex items-center justify-center font-bold text-xs flex-shrink-0">1</span>
                        <div>
                            <h2 class="text-sm sm:text-base font-black text-slate-800">Pilih dokumen sumber</h2>
                            <p class="text-xs text-slate-400 font-medium">Pilih berkas dari perangkat atau masukkan teks materi.</p>
                        </div>
                    </div>

                    <!-- Input Mode Toggle -->
                    <div class="inline-flex p-1 rounded-full bg-slate-100 text-xs font-bold">
                        <button type="button" onclick="setInputMode('file')" id="btn-mode-file"
                                class="px-4 py-1.5 rounded-full transition-all bg-white text-slate-800 border border-slate-200 shadow-xs">
                            Unggah file
                        </button>
                        <button type="button" onclick="setInputMode('text')" id="btn-mode-text"
                                class="px-4 py-1.5 rounded-full transition-all text-slate-500 hover:text-slate-800">
                            Tempel teks
                        </button>
                    </div>
                </div>

                <!-- 1. MODE FILE UPLOAD -->
                <div id="mode-file-container" class="space-y-3">
                    <input type="file" id="document_file" name="document_file" accept=".pdf,.docx,.doc,.txt,.md,.csv,.json"
                           style="display: none !important;">

                    <!-- Dropzone -->
                    <div id="dropzone" onclick="document.getElementById('document_file').click()"
                         class="border-2 border-dashed border-slate-200 hover:border-blue-400 bg-white hover:bg-blue-50/20 rounded-2xl p-8 sm:p-10 text-center transition-all cursor-pointer">
                        <div class="w-12 h-12 mx-auto rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                        <p class="text-xs sm:text-sm font-bold text-slate-800 mt-3">
                            Klik untuk memilih berkas atau seret & lepas ke sini
                        </p>
                        <p class="text-[11px] sm:text-xs text-slate-400 font-medium mt-1">
                            PDF, Word (DOCX), TXT, Markdown • maks. 20 MB
                        </p>
                        <span class="mt-4 inline-block px-5 py-1.5 text-xs font-bold text-slate-700 bg-white border border-slate-200 rounded-full shadow-xs hover:bg-slate-50">
                            Pilih berkas
                        </span>
                    </div>

                    <!-- Selected File Preview Card -->
                    <div id="file-preview-card" class="hidden bg-slate-50/80 border border-slate-200 rounded-2xl p-4 transition-all">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex flex-col items-center justify-center font-black shadow-xs flex-shrink-0" id="file-ext-badge-container">
                                    <span class="text-[9px] font-black uppercase tracking-wider" id="file-ext-badge">PDF</span>
                                </div>
                                <div class="min-w-0">
                                    <div class="font-bold text-slate-800 text-xs sm:text-sm truncate" id="file-name-label">document.pdf</div>
                                    <div class="text-[11px] text-slate-400 font-medium mt-0.5">
                                        <span id="file-size-label">1.2 MB</span>
                                        <span class="mx-1">•</span>
                                        <button type="button" onclick="document.getElementById('document_file').click()" class="text-blue-600 hover:underline font-bold">
                                            Ganti Berkas
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" onclick="clearSelectedFile()" class="p-2 text-slate-400 hover:text-red-500 rounded-lg hover:bg-red-50 transition-colors" title="Hapus Berkas">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 2. MODE DIRECT TEXT INPUT -->
                <div id="mode-text-container" class="hidden space-y-2">
                    <div class="relative">
                        <textarea id="direct_text" name="direct_text" rows="5"
                                  placeholder="Salin atau ketik teks materi pembelajaran di sini (minimal 20 karakter)..."
                                  class="w-full rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 p-4 text-xs sm:text-sm font-medium text-slate-800 placeholder-slate-400 resize-y transition-all"
                                  oninput="updateTextCounter(this)"></textarea>
                        <div class="absolute bottom-3 right-3 text-[10px] font-bold px-2 py-0.5 rounded-md bg-slate-100 text-slate-500" id="text-char-count">
                            0 kata
                        </div>
                    </div>
                </div>

                <!-- Input Judul Dokumen (Opsional) -->
                <div class="space-y-1.5 pt-1">
                    <label for="custom_title" class="block text-xs font-bold text-slate-700">
                        Judul dokumen / topik <span class="font-normal text-slate-400">(opsional)</span>
                    </label>
                    <input type="text" id="custom_title" name="custom_title" placeholder="Contoh: Modul Pecahan Sederhana Kelas 4 SD"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 text-xs sm:text-sm font-medium text-slate-800 placeholder-slate-400 transition-all">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="kelas_level" class="block text-xs font-bold text-slate-700 mb-1.5">Tingkat kelas topik</label>
                        <select id="kelas_level" name="kelas_level" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-medium text-slate-800">
                            @for($level = 1; $level <= 6; $level++)
                                <option value="{{ $level }}">Kelas {{ $level }} SD</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label for="topic_category" class="block text-xs font-bold text-slate-700 mb-1.5">Kategori topik</label>
                        <select id="topic_category" name="topic_category" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-medium text-slate-800">
                            <option value="urutan">Urutan</option>
                            <option value="percabangan">Percabangan</option>
                            <option value="pengulangan">Pengulangan</option>
                            <option value="pola">Pola</option>
                            <option value="dekomposisi" selected>Dekomposisi</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- STEP 2: INSTRUKSI SOAL -->
            <div class="space-y-3 pt-2">
                <div class="flex items-center gap-2.5">
                    <span class="w-7 h-7 rounded-full bg-blue-50 text-blue-600 border border-blue-200 flex items-center justify-center font-bold text-xs flex-shrink-0">2</span>
                    <div>
                        <h2 class="text-sm sm:text-base font-black text-slate-800">
                            Instruksi soal <span class="text-xs font-normal text-slate-400">- opsional</span>
                        </h2>
                        <p class="text-xs text-slate-400 font-medium">Beri arahan khusus kepada AI tentang soal yang diinginkan.</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <textarea id="custom_prompt" name="custom_prompt" rows="3"
                              placeholder='Contoh: "Buatkan 10 soal cerita tingkat HOTS tentang pecahan untuk kelas 4 SD."'
                              class="w-full rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 p-4 text-xs sm:text-sm font-medium text-slate-800 placeholder-slate-400 resize-y transition-all"></textarea>

                    <!-- Prompt Suggestion Chips -->
                    <div class="flex items-center gap-2 flex-wrap pt-1">
                        <button type="button" onclick="setPromptTemplate('Buatkan 5 butir soal pesan / uraian dengan pembahasan detail dan langkah pengerjaannya.')"
                                class="px-3.5 py-1.5 rounded-full text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:border-red-300 hover:bg-red-50/50 flex items-center gap-1.5 shadow-xs transition-all cursor-pointer">
                            <span class="w-2 h-2 rounded-full bg-[#EF4444]"></span> 5 soal pesan / uraian
                        </button>
                        <button type="button" onclick="setPromptTemplate('Buatkan 5 butir soal HOTS (Higher Order Thinking Skills) dengan analisis penalaran tingkat tinggi.')"
                                class="px-3.5 py-1.5 rounded-full text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:border-amber-300 hover:bg-amber-50/50 flex items-center gap-1.5 shadow-xs transition-all cursor-pointer">
                            <span class="w-2 h-2 rounded-full bg-[#F59E0B]"></span> 5 soal HOTS
                        </button>
                        <button type="button" onclick="setPromptTemplate('Buatkan 5 butir soal cerita kontekstual matematika ramah anak SD dengan skenario sehari-hari.')"
                                class="px-3.5 py-1.5 rounded-full text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:border-emerald-300 hover:bg-emerald-50/50 flex items-center gap-1.5 shadow-xs transition-all cursor-pointer">
                            <span class="w-2 h-2 rounded-full bg-[#10B981]"></span> 5 soal cerita
                        </button>
                        <button type="button" onclick="setPromptTemplate('Buatkan 10 butir soal kuis evaluasi komprehensif mencakup semua konsep pada dokumen.')"
                                class="px-3.5 py-1.5 rounded-full text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:border-blue-300 hover:bg-blue-50/50 flex items-center gap-1.5 shadow-xs transition-all cursor-pointer">
                            <span class="w-2 h-2 rounded-full bg-[#2563EB]"></span> 10 soal evaluasi
                        </button>
                    </div>

                    <p class="text-[11px] sm:text-xs text-slate-400 font-medium pt-1">
                        <strong>Mode default:</strong> jika instruksi kosong, AI membuat 5 soal pilihan ganda (A, B, C, D) lengkap dengan kunci jawaban dan pembahasan.
                    </p>
                </div>
            </div>

            <!-- FORM FOOTER -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-slate-100">
                <p class="text-xs font-semibold text-slate-600">
                    Dokumen, potongan teks, topik, dan soal akan disimpan untuk proses tinjauan admin.
                </p>

                <button type="submit" id="btn-submit-summarize"
                        class="w-full sm:w-auto px-7 py-3 rounded-full font-bold text-white bg-[#FF3B30] hover:bg-[#E0352B] shadow-lg shadow-red-500/25 active:scale-95 transition-all flex items-center justify-center gap-2 text-sm cursor-pointer">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <span>Mulai buat soal dengan AI</span>
                </button>
            </div>
        </form>
    </div>

    <!-- ======================================================== -->
    <!-- 5. RIWAYAT PAKET SOAL DOKUMEN TABLE CARD -->
    <!-- ======================================================== -->
    <div class="bg-white rounded-3xl sm:rounded-[32px] p-6 sm:p-8 shadow-xs border border-slate-100 space-y-5">
        
        <!-- Header with Search -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-base sm:text-lg font-black text-slate-800">Riwayat Paket Soal Dokumen</h2>
                    <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-600 text-xs font-black flex items-center justify-center">{{ $summaries->total() }}</span>
                </div>
                <p class="text-xs text-slate-400 font-medium mt-0.5">Daftar arsip paket soal yang pernah dibuat dari dokumen materi</p>
            </div>

            <form method="GET" action="{{ route('admin.document-summarizer.index') }}" class="relative w-full sm:w-64">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama dokumen..."
                       class="w-full pl-9 pr-4 py-2 rounded-full border border-slate-200 text-xs font-medium text-slate-800 placeholder-slate-400 focus:border-blue-500 focus:outline-none transition-all">
                @if(request('q'))
                    <a href="{{ route('admin.document-summarizer.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs font-bold">&times;</a>
                @endif
            </form>
        </div>

        @if($summaries->isEmpty())
            <div class="py-12 text-center text-slate-400 space-y-2">
                <div class="text-4xl">📂</div>
                <div class="text-sm font-bold text-slate-600">Belum ada riwayat paket soal dari dokumen.</div>
                <p class="text-xs text-slate-400">Gunakan formulir di atas untuk membuat paket soal pertama Anda.</p>
            </div>
        @else
            <!-- Table View -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                            <th class="py-3 px-3">Nama Dokumen</th>
                            <th class="py-3 px-3">Jenis Pembuatan</th>
                            <th class="py-3 px-3">Kata Sumber / Soal</th>
                            <th class="py-3 px-3">Tanggal</th>
                            <th class="py-3 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 font-medium text-slate-700">
                        @foreach($summaries as $item)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <!-- Nama Dokumen -->
                                <td class="py-4 px-3">
                                    <div class="flex items-center gap-3">
                                        <span class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 font-black text-[10px] flex items-center justify-center uppercase flex-shrink-0">
                                            {{ $item->file_type ?: 'DOCX' }}
                                        </span>
                                        <div class="min-w-0">
                                            <div class="font-bold text-slate-800 text-xs sm:text-sm truncate max-w-[240px]" title="{{ $item->title }}">
                                                {{ $item->title }}
                                            </div>
                                            <div class="text-[11px] text-slate-400 truncate max-w-[240px] mt-0.5" title="{{ $item->original_filename }}">
                                                {{ $item->original_filename }} • {{ $item->formatted_file_size }}
                                            </div>
                                            <span class="mt-1 inline-flex rounded-full px-2 py-0.5 text-[10px] font-black {{ $item->status === 'selesai' ? 'bg-emerald-100 text-emerald-800' : ($item->status === 'gagal' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Jenis Pembuatan -->
                                <td class="py-4 px-3 whitespace-nowrap">
                                    <span class="bg-[#FEF3C7] text-[#92400E] border border-[#FDE68A] px-3 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-[#F59E0B]"></span>
                                        Soal & Kuis Latihan
                                    </span>
                                </td>

                                <!-- Kata Sumber / Soal -->
                                <td class="py-4 px-3 whitespace-nowrap space-y-1">
                                    <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-700">
                                        <span class="w-2 h-2 rounded-full bg-[#2563EB]"></span>
                                        {{ number_format($item->word_count_original) }} kata sumber
                                    </div>
                                    <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-700">
                                        <span class="w-2 h-2 rounded-full bg-[#EF4444]"></span>
                                        {{ number_format($item->word_count_summary) }} kata soal
                                    </div>
                                </td>

                                <!-- Tanggal -->
                                <td class="py-4 px-3 whitespace-nowrap text-slate-500">
                                    <div class="font-bold text-slate-800 text-xs">{{ $item->created_at->translatedFormat('d M Y') }}</div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">{{ $item->created_at->format('H:i') }} WIB</div>
                                </td>

                                <!-- Aksi -->
                                <td class="py-4 px-3 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Lihat Modal -->
                                        <button type="button" onclick="viewSummaryModal({{ $item->id }})"
                                                class="px-4 py-1.5 rounded-full bg-[#2563EB] hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition-all">
                                            Lihat
                                        </button>

                                        <!-- Unduh -->
                                        <a href="{{ route('admin.document-summarizer.download', $item->id) }}"
                                           class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 hover:bg-emerald-100 flex items-center justify-center transition-all" title="Unduh File">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        </a>

                                        <!-- Hapus -->
                                        <form action="{{ route('admin.document-summarizer.destroy', $item->id) }}" method="POST"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus arsip soal ini?');" class="inline m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded-full bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center transition-all" title="Hapus Riwayat">
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
<!-- HIDDEN DATA CONTAINERS (Preserves JS functionality) -->
<!-- ======================================================== -->
<div id="result-placeholder" class="hidden"></div>
<div id="result-container" class="hidden">
    <span id="res-badge-type"></span>
    <span id="res-title"></span>
    <span id="res-date"></span>
    <span id="res-provider"></span>
    <span id="res-compression"></span>
    <span id="res-words-original"></span>
    <span id="res-words-summary"></span>
    <div id="res-markdown-body"></div>
    <div id="res-key-points-box"><ul id="res-key-points-list"></ul></div>
</div>

<!-- ======================================================== -->
<!-- LOADING MODAL OVERLAY -->
<!-- ======================================================== -->
<div id="result-loading" class="fixed inset-0 z-[80] hidden bg-[#FAF6EF] flex items-center justify-center overflow-y-auto p-5 sm:p-10">
    <div class="w-full max-w-5xl py-8 text-center space-y-8 sm:space-y-10">
        <div class="relative w-44 h-44 sm:w-48 sm:h-48 mx-auto">
            <div class="absolute inset-0 rounded-full border-[10px] border-[#E8E1D5]"></div>
            <div class="absolute inset-0 rounded-full border-[10px] border-transparent border-t-[#2563EB] border-r-[#2563EB] -rotate-45 animate-spin"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <svg class="w-24 h-24" viewBox="0 0 80 80" fill="none" aria-hidden="true">
                    <path d="M40 11v10" stroke="#17182F" stroke-width="5" stroke-linecap="round"/>
                    <circle cx="40" cy="10" r="6" fill="#EF342B"/>
                    <rect x="16" y="22" width="48" height="42" rx="15" fill="#2563EB" stroke="#17182F" stroke-width="5"/>
                    <circle cx="31" cy="41" r="5" fill="white"/>
                    <circle cx="49" cy="41" r="5" fill="white"/>
                    <path d="M31 54h18" stroke="white" stroke-width="5" stroke-linecap="round"/>
                </svg>
            </div>
        </div>
        <div class="space-y-5">
            <h3 class="text-3xl sm:text-5xl font-black text-[#17182F] tracking-tight" id="loading-stage-text">Mengekstrak materi dokumen...</h3>
            <p class="text-lg sm:text-2xl text-slate-600 font-medium max-w-3xl mx-auto leading-relaxed">RoboMath sedang membaca isi dokumen dan menyusun butir soal.</p>
            <p class="inline-flex items-center justify-center px-6 py-3 rounded-full border-2 border-[#FFDE67] bg-[#FFFBEB] text-base sm:text-xl text-amber-950 font-bold">
                AI lokal bisa butuh 1–3 menit. Mohon ditunggu.
            </p>
        </div>
        <div class="space-y-3 max-w-4xl mx-auto">
            <div class="w-full bg-[#E8E1D5] h-5 rounded-full overflow-hidden">
                <div id="loading-progress-bar" class="bg-[#2563EB] h-full w-0 rounded-full transition-all duration-700"></div>
            </div>
            <div class="flex items-center justify-between text-base sm:text-xl font-bold text-slate-600">
                <span id="loading-progress-label">Menyiapkan dokumen</span>
                <span id="loading-progress-percent">0%</span>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL POPUP: LIHAT DETAIL RINGKASAN LENGKAP (Riwayat) -->
<!-- ======================================================== -->
<div id="summary-modal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-3xl w-full max-h-[90vh] flex flex-col shadow-2xl border border-slate-100 overflow-hidden">
        
        <!-- Modal Top Bar -->
        <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/60">
            <div class="min-w-0 pr-4">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-blue-100 text-blue-800 uppercase" id="modal-badge-style">
                    Soal & Kuis Latihan
                </span>
                <h3 class="text-base sm:text-lg font-black text-slate-900 truncate mt-1" id="modal-title">Judul Dokumen</h3>
                <p class="text-xs text-slate-400 font-medium" id="modal-meta">Berkas: - • Dibuat: -</p>
            </div>

            <button type="button" onclick="closeSummaryModal()" class="w-8 h-8 rounded-full bg-white border border-slate-200 text-slate-400 hover:text-slate-800 flex items-center justify-center transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Modal Body Content -->
        <div class="p-5 sm:p-6 overflow-y-auto flex-1 space-y-5">
            <div id="modal-markdown-body" class="prose prose-blue max-w-none text-slate-700 text-sm leading-relaxed"></div>

            <div id="modal-key-points-box" class="bg-amber-50 border border-amber-200 rounded-2xl p-4 space-y-2">
                <div class="text-xs font-black text-amber-900 uppercase tracking-wider">Poin Kunci Utama</div>
                <ul class="text-xs font-medium text-slate-700 space-y-1.5 list-disc pl-4" id="modal-key-points-list"></ul>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-4 sm:p-5 border-t border-slate-100 bg-slate-50 flex items-center justify-between">
            <button type="button" onclick="copyModalContent()" class="px-4 py-2 rounded-full text-xs font-bold bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                <span>Salin Teks Ringkasan</span>
            </button>

            <button type="button" onclick="closeSummaryModal()" class="px-5 py-2 rounded-full text-xs font-bold bg-[#2563EB] text-white hover:bg-blue-700 shadow-xs">
                Tutup
            </button>
        </div>

    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL POPUP BESAR: HASIL SOAL AI (Setelah Generate) -->
<!-- ======================================================== -->
<div id="result-popup-modal" class="fixed inset-0 z-[60] hidden bg-slate-900/60 backdrop-blur-sm">
    <div class="flex items-stretch justify-center min-h-screen p-0 sm:p-4">
        <div class="bg-[#FAF6EF] rounded-none sm:rounded-[30px] w-full max-w-5xl flex flex-col shadow-2xl overflow-hidden" style="max-height: 100vh;">

            <!-- Popup Header Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 sm:px-7 py-4 bg-white border-b border-[#E9E0D4] flex-shrink-0">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 3.75h7.5L19.25 8.5v11.75H7a2 2 0 0 1-2-2v-12.5a2 2 0 0 1 2-2Zm7.5 0V8.5h4.75M9 13h6m-6 3.5h6"/></svg>
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-base sm:text-xl font-black text-slate-900 truncate" id="popup-res-title">Paket Soal AI</h2>
                        <p class="text-[10px] sm:text-xs text-slate-500 font-semibold mt-0.5 truncate">
                            <span id="popup-res-badge-type"></span> · <span id="popup-res-provider"></span> · <span id="popup-res-date"></span>
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <!-- Action buttons in header -->
                    <button type="button" onclick="openPublishModal()" title="Ajukan soal ke Review AI"
                            class="flex items-center gap-1.5 px-4 py-2 rounded-full text-[10px] sm:text-xs font-black bg-slate-900 hover:bg-slate-700 text-white shadow-xs transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        <span>Ajukan ke Review AI</span>
                    </button>
                    <button type="button" onclick="copySummaryText()" title="Salin Semua"
                            class="p-2 rounded-full border border-[#E9E0D4] hover:bg-slate-50 text-slate-700 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </button>
                    <button type="button" onclick="downloadCurrentSummary()" title="Unduh .MD"
                            class="p-2 rounded-full border border-[#E9E0D4] hover:bg-slate-50 text-slate-700 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </button>
                    <button type="button" onclick="closeResultPopup()"
                            class="w-10 h-10 rounded-full border border-[#E9E0D4] bg-white hover:bg-slate-50 text-slate-700 flex items-center justify-center font-black text-base transition-all" aria-label="Tutup">&times;</button>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 px-5 sm:px-7 py-4 flex-shrink-0">
                <div class="rounded-2xl bg-white border border-[#E9E0D4] p-3.5">
                    <span class="block text-[9px] text-slate-500 font-black uppercase tracking-wider">Kata sumber</span>
                    <span class="block text-lg font-black text-slate-900 mt-0.5" id="popup-res-words-original">—</span>
                </div>
                <div class="rounded-2xl bg-white border border-[#E9E0D4] p-3.5">
                    <span class="block text-[9px] text-slate-500 font-black uppercase tracking-wider">Panjang soal</span>
                    <span class="block text-lg font-black text-slate-900 mt-0.5" id="popup-res-words-summary">—</span>
                </div>
                <div class="rounded-2xl bg-[#FFF3D9] border border-amber-100 p-3.5">
                    <span class="block text-[9px] text-amber-800 font-black uppercase tracking-wider">Jumlah soal</span>
                    <span class="block text-lg font-black text-amber-800 mt-0.5" id="popup-res-question-count">—</span>
                </div>
                <div class="rounded-2xl bg-[#FFFBEB] border border-[#FFDE67] p-3.5">
                    <span class="block text-[10px] font-black text-slate-900">Perlu ditinjau guru</span>
                    <span class="block text-[9px] text-amber-950 font-semibold mt-1 leading-relaxed">Tinjau dan setujui soal sebelum dipakai siswa.</span>
                </div>
            </div>

            <!-- Main Body: Questions Scroll Area -->
            <div class="flex-1 overflow-y-auto px-5 sm:px-7 pb-5 space-y-3" id="popup-res-markdown-body" style="scroll-behavior: smooth;">
                <!-- Dynamic question cards rendered here -->
            </div>

            <!-- Footer -->
            <div class="px-5 sm:px-7 py-3 border-t border-[#E9E0D4] bg-[#FAF6EF] flex items-center justify-between gap-3 flex-shrink-0">
                <p class="text-[10px] text-slate-500 font-medium">Dibuat oleh RoboMath AI</p>
                <button type="button" onclick="closeResultPopup()"
                        class="px-5 py-2 rounded-full text-xs font-bold bg-white border border-[#E9E0D4] text-slate-900 hover:bg-slate-50 shadow-xs transition-all">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL: PUBLISH SOAL KE REVIEW AI -->
<!-- ============================================================ -->
<div id="publish-modal" class="hidden fixed inset-0 z-[70] flex items-center justify-center p-3 sm:p-5 overflow-y-auto bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-[#FAF6EF] rounded-[30px] shadow-2xl w-full max-w-3xl max-h-[94vh] overflow-y-auto p-6 sm:p-10 space-y-5 relative my-auto">
        <!-- Modal Header -->
        <div class="flex items-center justify-between gap-3">
            <div>
                <h3 class="text-2xl sm:text-3xl font-black tracking-tight text-[#17182F]">Ajukan soal ke Review AI</h3>
            </div>
            <button type="button" onclick="closePublishModal()" aria-label="Tutup" class="w-12 h-12 rounded-full bg-white border-2 border-[#E9E0D4] hover:bg-slate-100 text-slate-900 flex items-center justify-center font-black text-2xl transition-all">&times;</button>
        </div>

        <!-- Info Card -->
        <div class="p-4 sm:p-5 rounded-3xl bg-[#DBEAFE] text-sm sm:text-base font-medium text-blue-900 leading-relaxed">
            Soal disimpan dengan status <strong>“Menunggu tinjauan”</strong> di halaman Review AI sebelum dikerjakan siswa.
        </div>

        <!-- Preview -->
        <div id="publish-preview" class="hidden flex p-4 sm:p-5 rounded-3xl bg-[#ECFDF5] border-2 border-emerald-300 text-base sm:text-xl font-black text-slate-900 items-center gap-4">
            <svg class="w-8 h-8 text-emerald-700 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m9 12.75 2.25 2.25L15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            <span id="publish-preview-text"></span>
        </div>

        <!-- Form Fields -->
        <div class="space-y-5">
            <!-- Pilih Topik -->
            <div>
                <label class="block text-xs sm:text-sm font-black text-slate-600 mb-2 uppercase tracking-wider">Topik tujuan <span class="text-[#EF342B]">*</span></label>
                <select id="publish-topic-id" class="w-full px-5 py-4 rounded-2xl border-2 border-[#E9E0D4] focus:border-[#2563EB] bg-white font-bold text-slate-800 text-base outline-none transition-all">
                    <option value="">Pilih topik</option>
                    @foreach(\App\Models\Topic::where('is_active', true)->orderBy('kelas_level')->orderBy('order')->get() as $t)
                        <option value="{{ $t->id }}">{{ $t->title }} (Kelas {{ $t->kelas_level }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Format Soal -->
            <div>
                <label class="block text-xs sm:text-sm font-black text-slate-600 mb-2 uppercase tracking-wider">Format soal</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="flex items-center justify-center gap-1.5 px-4 py-4 rounded-2xl border-2 border-[#E8E1D5] bg-white text-sm sm:text-base font-black text-[#17182F] cursor-pointer transition-all has-[:checked]:border-emerald-300 has-[:checked]:bg-[#CFFAE9] has-[:checked]:text-emerald-950">
                        <input type="radio" name="publish-type" value="essay" id="publish-type-essay" class="sr-only" checked>
                        <span>Essay / Uraian</span>
                    </label>
                    <label class="flex items-center justify-center gap-1.5 px-4 py-4 rounded-2xl border-2 border-[#E8E1D5] bg-white text-sm sm:text-base font-black text-[#17182F] cursor-pointer transition-all has-[:checked]:border-[#2563EB] has-[:checked]:bg-[#2563EB] has-[:checked]:text-white">
                        <input type="radio" name="publish-type" value="pilihan_ganda" id="publish-type-mcq" class="sr-only">
                        <span>Pilihan Ganda (A-D)</span>
                    </label>
                </div>
            </div>

            <!-- Tingkat Kesulitan -->
            <div>
                <label class="block text-xs sm:text-sm font-black text-slate-600 mb-2 uppercase tracking-wider">Tingkat kesulitan</label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <label class="flex items-center justify-center gap-1.5 px-4 py-4 rounded-2xl border-2 border-[#E8E1D5] bg-white text-sm sm:text-base font-black text-[#17182F] cursor-pointer transition-all has-[:checked]:border-emerald-300 has-[:checked]:bg-[#CFFAE9] has-[:checked]:text-emerald-950">
                        <input type="radio" name="publish-difficulty" value="mudah" class="sr-only" checked> Mudah
                    </label>
                    <label class="flex items-center justify-center gap-1.5 px-4 py-4 rounded-2xl border-2 border-[#E8E1D5] bg-white text-sm sm:text-base font-black text-[#17182F] cursor-pointer transition-all has-[:checked]:border-amber-300 has-[:checked]:bg-amber-100">
                        <input type="radio" name="publish-difficulty" value="sedang" class="sr-only"> Sedang
                    </label>
                    <label class="flex items-center justify-center gap-1.5 px-4 py-4 rounded-2xl border-2 border-[#E8E1D5] bg-white text-sm sm:text-base font-black text-[#17182F] cursor-pointer transition-all has-[:checked]:border-rose-300 has-[:checked]:bg-rose-100">
                        <input type="radio" name="publish-difficulty" value="sulit" class="sr-only"> Sulit
                    </label>
                </div>
            </div>

            <!-- Poin per Soal -->
            <div>
                <label class="block text-xs sm:text-sm font-black text-slate-600 mb-2 uppercase tracking-wider">Poin per soal</label>
                <div class="flex items-center gap-4">
                    <input type="range" id="publish-points" min="5" max="50" step="5" value="10"
                           oninput="document.getElementById('publish-points-label').textContent = this.value"
                           class="flex-1 accent-[#2563EB]">
                    <span class="w-24 shrink-0 text-center font-black text-amber-800 text-base sm:text-lg bg-[#FEF3C7] px-4 py-3 rounded-2xl">
                        <span id="publish-points-label">10</span> Poin
                    </span>
                </div>
            </div>
        </div>

        <!-- Modal Actions -->
        <div class="flex flex-col-reverse sm:flex-row items-center gap-3 pt-2">
            <button type="button" onclick="closePublishModal()" class="flex-1 w-full px-6 py-4 rounded-full bg-white border-2 border-[#E9E0D4] hover:bg-slate-100 text-slate-900 font-black text-base transition-all">
                Batal
            </button>
            <button type="button" onclick="submitPublishToStudents()" id="btn-publish-confirm"
                    class="flex-1 w-full flex items-center justify-center gap-2 px-6 py-4 rounded-full bg-slate-900 hover:bg-slate-700 text-white font-black text-base shadow-xs transition-all">
                <span>Ajukan ke Review AI</span>
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dompurify/dist/purify.min.js"></script>
<script>
    function initSummarizerPage() {
        console.log('🤖 initSummarizerPage() executed');

        if (typeof window._summarizerState === 'undefined') {
            window._summarizerState = {
                inputMode: 'file',
                activeSummaryMarkdown: '',
                activeSummaryTitle: '',
                activeReviewUrl: null,
                modalMarkdownCache: '',
            };
        }
        const state = window._summarizerState;

        // Switch Input Mode between Upload File and Paste Text
        window.setInputMode = function(mode) {
            state.inputMode = mode;
            const fileContainer = document.getElementById('mode-file-container');
            const textContainer = document.getElementById('mode-text-container');
            const btnFile = document.getElementById('btn-mode-file');
            const btnText = document.getElementById('btn-mode-text');

            if (mode === 'file') {
                if (fileContainer) fileContainer.classList.remove('hidden');
                if (textContainer) textContainer.classList.add('hidden');
                if (btnFile) btnFile.className = 'px-4 py-1.5 rounded-full transition-all bg-white text-slate-800 border border-slate-200 shadow-xs font-bold';
                if (btnText) btnText.className = 'px-4 py-1.5 rounded-full transition-all text-slate-500 hover:text-slate-800 font-medium';
            } else {
                if (fileContainer) fileContainer.classList.add('hidden');
                if (textContainer) textContainer.classList.remove('hidden');
                if (btnText) btnText.className = 'px-4 py-1.5 rounded-full transition-all bg-white text-slate-800 border border-slate-200 shadow-xs font-bold';
                if (btnFile) btnFile.className = 'px-4 py-1.5 rounded-full transition-all text-slate-500 hover:text-slate-800 font-medium';
            }
        };

        // Handle File Selection
        window.handleFileSelected = function(input) {
            if (!input.files || !input.files[0]) return;
            const file = input.files[0];
            displaySelectedFile(file);
        };

        // Display Selected File Details
        window.displaySelectedFile = function(file) {
            if (!file) return;
            const ext = file.name.split('.').pop().toUpperCase();
            const sizeFormatted = formatFileSize(file.size);

            const badgeText = document.getElementById('file-ext-badge');
            const nameLabel = document.getElementById('file-name-label');
            const sizeLabel = document.getElementById('file-size-label');
            const dropzone = document.getElementById('dropzone');
            const previewCard = document.getElementById('file-preview-card');

            if (badgeText) badgeText.textContent = ext;
            if (nameLabel) nameLabel.textContent = file.name;
            if (sizeLabel) sizeLabel.textContent = sizeFormatted;

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

        // Live Word Counter
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

        function formatFileSize(bytes) {
            if (!bytes || bytes <= 0) return '0 B';
            if (bytes >= 1048576) return (bytes / 1048576).toFixed(1) + ' MB';
            if (bytes >= 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return bytes + ' B';
        }

        // Markdown / Question Parser
        function parseMarkdownToHtml(markdownText) {
            if (!markdownText) return '';

            let cleaned = markdownText.replace(/^(?:Sebagai\s+Asisten|Tentu|Halo|Berikut\s+adalah|Saya\s+telah|Baiklah|Berikut\s+ini).*?(?=(##|###|📝|Soal\s+1))/is, '').trim();

            try {
                const questionRegex = /###\s*(?:📝|🌟)?\s*Soal\s*(\d+)[\s\S]*?(?=(?:###\s*(?:📝|🌟)?\s*Soal\s*\d+|###\s*📌|$))/gi;
                const matches = [...cleaned.matchAll(questionRegex)];

                if (matches.length > 0) {
                    let finalHtml = '';

                    const headerMatch = cleaned.match(/^##\s*(.+)$/m);
                    if (headerMatch) {
                        const cleanTitle = headerMatch[1].replace(/[*#]/g, '').trim();
                        finalHtml += `
                            <div class="p-4 sm:p-5 rounded-2xl bg-[#CFFAE9] mb-3">
                                <div>
                                    <h2 class="text-base sm:text-lg font-black text-emerald-950">${cleanTitle}</h2>
                                    <p class="text-xs font-semibold text-emerald-800 mt-0.5">Dibuat dari “${state.activeSummaryTitle || 'dokumen materi'}”.</p>
                                </div>
                            </div>
                        `;
                    }

                    matches.forEach((m, idx) => {
                        const block = m[0];
                        const numMatch = block.match(/###\s*(?:📝|🌟)?\s*Soal\s*(\d+)/i);
                        const qNum = numMatch ? numMatch[1] : (idx + 1);

                        const options = [];
                        const lines = block.split('\n');
                        lines.forEach(line => {
                            const trimmed = line.trim();
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

                        const isMultipleChoice = options.length >= 2;

                        const bodyLines = [];
                        for (const line of lines) {
                            const trimmed = line.trim();
                            if (/^###\s*(?:📝|🌟)?\s*Soal/i.test(trimmed)) continue;
                            if (trimmed.startsWith('>') || /^(?:[-*]\s*)?[A-D][\.\)]\s+/i.test(trimmed) || /^(?:🔑|💡|\*\*🔑|\*\*💡)/i.test(trimmed)) break;
                            if (trimmed) bodyLines.push(trimmed);
                        }
                        const questionBody = bodyLines.join(' ').replace(/[*_#]/g, '').trim();

                        let keyAnswer = '';
                        let explanation = '';
                        const keyMatch = block.match(/(?:🔑\s*Kunci\s*Jawaban|Kunci\s*Jawaban)\s*:\s*\*?\*?\s*([^\n]+)/i);
                        if (keyMatch) keyAnswer = keyMatch[1].replace(/[*_>]/g, '').trim();

                        const expMatch = block.match(/(?:💡\s*Pembahasan(?:\s*Seru|\s*&\s*Langkah)?|Pembahasan(?:\s*&\s*Langkah)?)\s*:\s*\*?\*?\s*([^\n\r#]+)/i);
                        if (expMatch) explanation = expMatch[1].replace(/[*_>]/g, '').trim();

                        let optionsHtml = '';
                        if (isMultipleChoice) {
                            optionsHtml = `
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1">
                                    ${options.map(opt => `
                                        <div class="flex items-start gap-2.5 p-2.5 rounded-2xl bg-white border-2 border-[#E9E0D4] text-xs font-semibold text-slate-700">
                                            <span class="w-5 h-5 rounded-full bg-[#F2EDE4] text-slate-800 flex items-center justify-center font-black text-[10px] flex-shrink-0">
                                                ${opt.letter}
                                            </span>
                                            <span class="leading-relaxed">${opt.text}</span>
                                        </div>
                                    `).join('')}
                                </div>
                            `;
                        }

                        let answerBoxHtml = '';
                        if (keyAnswer || explanation) {
                            answerBoxHtml = `
                                <div class="mt-1 p-3 rounded-2xl bg-[#ECFDF5] border border-emerald-300 text-slate-800 space-y-1">
                                    ${keyAnswer ? `
                                        <div class="text-xs leading-relaxed">
                                            <strong>Kunci jawaban:</strong> ${keyAnswer}
                                        </div>
                                    ` : ''}
                                    ${explanation ? `
                                        <div class="text-xs leading-relaxed">
                                            <strong>Pembahasan:</strong> ${explanation}
                                        </div>
                                    ` : ''}
                                </div>
                            `;
                        }

                        finalHtml += `
                            <div class="bg-white rounded-3xl p-4 sm:p-5 border border-[#E9E0D4] shadow-[0_8px_24px_rgba(15,23,42,0.05)] space-y-3">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black bg-[#FFE4E1] text-[#C92A20]">
                                        Soal ${qNum}
                                    </span>
                                    <span class="text-[10px] font-black ${isMultipleChoice ? 'text-blue-800' : 'text-slate-700'} bg-slate-100 px-2.5 py-1 rounded-full">
                                        ${isMultipleChoice ? 'Pilihan ganda' : 'Essay / uraian'}
                                    </span>
                                </div>

                                <div class="text-sm font-black text-slate-900 leading-relaxed">
                                    ${questionBody || 'Pertanyaan materi latihan'}
                                </div>

                                ${optionsHtml}
                                ${answerBoxHtml}
                            </div>
                        `;
                    });

                    if (typeof DOMPurify !== 'undefined') {
                        finalHtml = DOMPurify.sanitize(finalHtml);
                    }
                    return finalHtml;
                }
            } catch (err) {
                console.warn('Custom parser fallback error:', err);
            }

            if (typeof marked !== 'undefined' && typeof marked.parse === 'function') {
                try {
                    let parsedHtml = marked.parse(cleaned);
                    if (typeof DOMPurify !== 'undefined') parsedHtml = DOMPurify.sanitize(parsedHtml);
                    return parsedHtml;
                } catch (e) {
                    console.warn(e);
                }
            }

            return `<p class="my-2 leading-relaxed">${cleaned.replace(/\n/g, '<br>')}</p>`;
        }

        // Initialize Dropzone Events
        function initDropzoneEvents() {
            const dropzone = document.getElementById('dropzone');
            const fileInput = document.getElementById('document_file');

            if (fileInput) {
                fileInput.removeEventListener('change', window._fileInputHandler);
                window._fileInputHandler = function() { handleFileSelected(this); };
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
                        dropzone.classList.add('border-blue-500', 'bg-blue-50/30');
                    }, false);
                });

                ['dragleave', 'dragend', 'drop'].forEach(eventName => {
                    dropzone.addEventListener(eventName, function() {
                        dropzone.classList.remove('border-blue-500', 'bg-blue-50/30');
                    }, false);
                });

                dropzone.addEventListener('drop', function(e) {
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

        // Form Submit
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
                        confirmButtonColor: '#2563EB'
                    });
                    return;
                }

                if (state.inputMode === 'text' && (!textInput || !textInput.value || textInput.value.trim().length < 10)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Teks Terlalu Singkat',
                        text: 'Silakan masukkan teks materi minimal 10 karakter agar AI dapat menganalisisnya!',
                        confirmButtonColor: '#2563EB'
                    });
                    return;
                }

                showLoadingState(true);

                const formData = new FormData(formElement);
                if (state.inputMode === 'file') {
                    formData.delete('direct_text');
                    if (fileInput && fileInput.files[0]) {
                        formData.set('document_file', fileInput.files[0]);
                    }
                } else {
                    formData.delete('document_file');
                }

                try {
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
                            if (validationMessages.length > 0) errorMsg = validationMessages.join('\n');
                        }
                        throw new Error(errorMsg);
                    }

                    renderSummaryResult(data);

                    Swal.fire({
                        icon: 'success',
                        title: 'Paket Soal Berhasil Dibuat!',
                        text: `${data.quiz_count} soal untuk topik "${data.topic_title}" tersimpan dan menunggu persetujuan admin.`,
                        confirmButtonText: 'Tinjau hasil AI',
                        showCancelButton: true,
                        cancelButtonText: 'Tetap di sini',
                        confirmButtonColor: '#2563EB'
                    }).then((result) => {
                        if (result.isConfirmed && data.review_url) {
                            window.location.href = data.review_url;
                        }
                    });

                } catch (error) {
                    console.error(error);
                    const isTimeout = error.name === 'AbortError';
                    Swal.fire({
                        icon: 'error',
                        title: isTimeout ? 'Waktu Habis (Timeout)' : 'Gagal Membuat Soal',
                        text: isTimeout
                            ? 'Model AI terlalu lama merespons. Pastikan server AI aktif, lalu coba lagi.'
                            : (error.message || 'Terjadi gangguan koneksi pada server AI.'),
                        confirmButtonColor: '#2563EB'
                    });
                } finally {
                    showLoadingState(false);
                }
            });
        }

        function showLoadingState(isLoading) {
            const loading = document.getElementById('result-loading');
            const submitBtn = document.getElementById('btn-submit-summarize');
            const progressBar = document.getElementById('loading-progress-bar');
            const progressPercent = document.getElementById('loading-progress-percent');
            const progressLabel = document.getElementById('loading-progress-label');

            if (isLoading) {
                if (loading) { loading.style.display = 'flex'; loading.classList.remove('hidden'); }
                state.loadingProgress = 4;
                if (progressBar) progressBar.style.width = `${state.loadingProgress}%`;
                if (progressPercent) progressPercent.textContent = `${state.loadingProgress}%`;
                if (progressLabel) progressLabel.textContent = 'Menyiapkan dokumen';
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                }

                const messages = [
                    'Membaca berkas dokumen...',
                    'Mengekstrak materi dokumen...',
                    'Mengirim materi ke model AI...',
                    'AI sedang menganalisis materi dan merancang butir soal...',
                    'Menyusun opsi jawaban, kunci, dan pembahasan...',
                    'Hampir selesai, memformat paket soal...'
                ];
                let step = 0;
                const textElem = document.getElementById('loading-stage-text');
                if (window.loadingInterval) clearInterval(window.loadingInterval);
                window.loadingInterval = setInterval(() => {
                    state.loadingProgress = Math.min(92, state.loadingProgress + 2);
                    step = Math.min(messages.length - 1, Math.floor(state.loadingProgress / 18));
                    if (textElem) textElem.textContent = messages[step];
                    if (progressBar) progressBar.style.width = `${state.loadingProgress}%`;
                    if (progressPercent) progressPercent.textContent = `${state.loadingProgress}%`;
                    if (progressLabel) {
                        progressLabel.textContent = [
                            'Menyiapkan dokumen',
                            'Membaca dokumen',
                            'Menganalisis materi',
                            'Menyusun butir soal',
                            'Memeriksa kunci dan pembahasan',
                            'Menyelesaikan paket soal',
                        ][step];
                    }
                }, 1200);
            } else {
                if (loading) { loading.style.display = 'none'; loading.classList.add('hidden'); }
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                }
                if (window.loadingInterval) clearInterval(window.loadingInterval);
                window.loadingInterval = null;
            }
        }

        function renderSummaryResult(data) {
            state.activeSummaryMarkdown = data.summary_markdown;
            state.activeSummaryTitle = data.title;
            state.activeSummaryId = data.summary_id || null;
            state.activeReviewUrl = data.review_url || null;

            const resTitle = document.getElementById('res-title');
            const badgeType = document.getElementById('res-badge-type');
            const resDate = document.getElementById('res-date');
            const resProvider = document.getElementById('res-provider');
            const resCompression = document.getElementById('res-compression');
            const resWordsOrig = document.getElementById('res-words-original');
            const resWordsSum = document.getElementById('res-words-summary');
            const markdownBody = document.getElementById('res-markdown-body');
            const popupQuestionCount = document.getElementById('popup-res-question-count');
            const questionCount = (data.summary_markdown.match(/###\s*(?:📝|🌟)?\s*Soal\s*\d+/gi) || []).length;
            state.activeQuizCount = questionCount;

            if (resTitle) resTitle.textContent = data.title;
            if (badgeType) badgeType.textContent = `${data.file_type} • ${data.file_size_formatted}`;
            if (resDate) resDate.textContent = data.created_at;
            if (resProvider) resProvider.textContent = data.provider;
            if (resCompression) resCompression.textContent = `${data.compression_ratio}%`;
            if (resWordsOrig) resWordsOrig.textContent = `${(data.word_count_original || 0).toLocaleString()} Kata`;
            if (resWordsSum) resWordsSum.textContent = `${(data.word_count_summary || 0).toLocaleString()} Kata`;
            if (popupQuestionCount) popupQuestionCount.textContent = `${questionCount} soal`;

            if (markdownBody) {
                markdownBody.innerHTML = parseMarkdownToHtml(data.summary_markdown);
            }

            openResultPopup();
        }

        window.openResultPopup = function() {
            const popupModal = document.getElementById('result-popup-modal');
            if (!popupModal) return;

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

            const srcBody = document.getElementById('res-markdown-body');
            const dstBody = document.getElementById('popup-res-markdown-body');
            if (srcBody && dstBody) {
                dstBody.innerHTML = srcBody.innerHTML;
            }

            popupModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        };

        window.closeResultPopup = function() {
            const popupModal = document.getElementById('result-popup-modal');
            if (popupModal) popupModal.classList.add('hidden');
            document.body.style.overflow = '';
        };

        document.getElementById('result-popup-modal')?.addEventListener('click', function(e) {
            if (e.target === this || e.target === this.firstElementChild) closeResultPopup();
        });

        window.copySummaryText = function() {
            if (!state.activeSummaryMarkdown) return;
            navigator.clipboard.writeText(state.activeSummaryMarkdown).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Teks Berhasil Disalin!',
                    timer: 1500,
                    showConfirmButton: false
                });
            });
        };

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
                if (metaElem) metaElem.textContent = `Berkas: ${data.original_filename} (${data.file_size}) • Hemat: ${data.compression_ratio}% kata • ${data.created_at}`;

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
                    title: 'Gagal Membuka Detail',
                    confirmButtonColor: '#2563EB'
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

        // Publish to Review AI
        window.openPublishModal = function() {
            if (!state.activeSummaryMarkdown) {
                Swal.fire({ icon: 'warning', title: 'Belum Ada Antrian Tinjauan', text: 'Buat paket soal dari dokumen terlebih dahulu.', confirmButtonColor: '#2563EB' });
                return;
            }

            const preview = document.getElementById('publish-preview');
            const previewText = document.getElementById('publish-preview-text');
            const modal = document.getElementById('publish-modal');

            if (previewText) {
                previewText.textContent = `${state.activeQuizCount || 0} butir soal siap diajukan`;
            }
            if (preview) preview.classList.remove('hidden');
            if (modal) modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        };

        window.closePublishModal = function() {
            const modal = document.getElementById('publish-modal');
            if (modal) modal.classList.add('hidden');
            const resultPopup = document.getElementById('result-popup-modal');
            if (!resultPopup || resultPopup.classList.contains('hidden')) {
                document.body.style.overflow = '';
            }
        };

        window.submitPublishToStudents = async function() {
            const topicId = document.getElementById('publish-topic-id')?.value;
            const difficulty = document.querySelector('input[name="publish-difficulty"]:checked')?.value || 'sedang';
            const points = document.getElementById('publish-points')?.value || '10';
            const selectedType = document.querySelector('input[name="publish-type"]:checked')?.value || 'essay';

            if (!topicId) {
                Swal.fire({ icon: 'warning', title: 'Pilih topik', text: 'Silakan pilih topik tujuan untuk soal-soal ini.', background: '#FAF6EF', color: '#17182F', confirmButtonColor: '#2563EB' });
                return;
            }

            const confirmBtn = document.getElementById('btn-publish-confirm');
            if (confirmBtn) {
                confirmBtn.disabled = true;
                confirmBtn.textContent = 'Mengajukan ke Review AI...';
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
                        summary_id: state.activeSummaryId,
                        markdown: state.activeSummaryMarkdown,
                        difficulty: difficulty,
                        points: parseInt(points),
                        as_essay: (selectedType === 'essay'),
                        type: selectedType,
                    })
                });

                const data = await res.json();
                closePublishModal();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: `${data.count} soal berhasil diajukan`,
                        text: 'Soal tersimpan dengan status “Menunggu tinjauan” di halaman Review AI.',
                        background: '#FAF6EF',
                        color: '#17182F',
                        iconColor: '#07835E',
                        buttonsStyling: false,
                        customClass: {
                            popup: 'rounded-[32px] !border-0 !shadow-2xl !px-6 !py-8 sm:!px-10 sm:!py-10',
                            icon: '!rounded-[28px] !border-0 !bg-[#CFFAE9] after:!text-[#07835E]',
                            title: '!text-2xl sm:!text-3xl !font-black !text-[#17182F]',
                            htmlContainer: '!text-sm sm:!text-lg !leading-relaxed !text-slate-600',
                            confirmButton: '!px-6 !py-4 !rounded-full !bg-slate-900 hover:!bg-slate-700 !text-white !font-black',
                            cancelButton: '!px-6 !py-4 !rounded-full !bg-white hover:!bg-slate-50 !border-2 !border-[#E9E0D4] !text-slate-900 !font-black',
                            actions: '!gap-3',
                        },
                        confirmButtonText: 'Buka halaman Review AI',
                        showCancelButton: true,
                        cancelButtonText: 'Tetap di sini',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = data.review_url || "{{ route('admin.ai-recommendations.index') }}";
                        }
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal mengajukan', text: data.message || 'Terjadi kesalahan.', background: '#FAF6EF', color: '#17182F', confirmButtonColor: '#EF342B' });
                }
            } catch (err) {
                console.error(err);
                Swal.fire({ icon: 'error', title: 'Gagal mengajukan', text: 'Terjadi gangguan koneksi.', background: '#FAF6EF', color: '#17182F', confirmButtonColor: '#EF342B' });
            } finally {
                if (confirmBtn) {
                    confirmBtn.disabled = false;
                    confirmBtn.textContent = 'Ajukan ke Review AI';
                }
            }
        };

        document.getElementById('publish-modal')?.addEventListener('click', function(e) {
            if (e.target === this) closePublishModal();
        });

    } // end initSummarizerPage

    initSummarizerPage();

    document.addEventListener('robomath:spa-loaded', function() {
        if (document.getElementById('summarizer-form')) {
            initSummarizerPage();
        }
    });
</script>

@endsection
