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

            <!-- FORM FOOTER: CHECKBOX & BUTTON -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-slate-100">
                <label class="flex items-center gap-2.5 text-xs font-bold text-slate-700 cursor-pointer select-none">
                    <input type="checkbox" id="save_history" name="save_history" value="1" checked class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500 border-slate-300">
                    <span>Simpan paket soal ke riwayat dokumen</span>
                </label>

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
<div id="result-loading" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-8 max-w-md w-full text-center shadow-2xl border border-slate-100 space-y-5">
        <div class="relative w-16 h-16 mx-auto">
            <div class="w-16 h-16 rounded-full border-4 border-blue-100 border-t-[#2563EB] animate-spin"></div>
            <div class="absolute inset-0 flex items-center justify-center text-xl">🤖</div>
        </div>
        <div class="space-y-1.5">
            <h3 class="text-base font-black text-slate-800" id="loading-stage-text">Membaca berkas dokumen...</h3>
            <p class="text-xs text-slate-500 font-medium">Model AI sedang membaca materi dan merancang butir soal berkualitas.</p>
            <p class="text-[11px] text-amber-600 font-bold mt-1">⏳ AI lokal mungkin membutuhkan 1–3 menit. Harap ditunggu!</p>
        </div>
        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-full w-2/3 animate-pulse"></div>
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
                <div class="text-xs font-black text-amber-900 uppercase tracking-wider">📌 Poin Kunci Utama</div>
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
<div id="result-popup-modal" class="fixed inset-0 z-[60] hidden" style="background: rgba(15,15,35,0.75); backdrop-filter: blur(6px);">
    <div class="flex items-stretch justify-center min-h-screen p-3 sm:p-5">
        <div class="bg-white rounded-3xl w-full max-w-5xl flex flex-col shadow-2xl border border-white/20 overflow-hidden" style="max-height: calc(100vh - 24px);">

            <!-- Popup Header Bar -->
            <div class="flex items-center justify-between gap-4 px-6 sm:px-8 py-4 border-b border-slate-100 bg-[#2563EB] flex-shrink-0">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-2xl bg-white/20 flex items-center justify-center text-white font-black text-lg flex-shrink-0">🤖</div>
                    <div class="min-w-0">
                        <h2 class="text-base sm:text-lg font-black text-white truncate" id="popup-res-title">Paket Soal AI</h2>
                        <div class="flex items-center gap-2 text-blue-100 text-xs font-semibold mt-0.5">
                            <span id="popup-res-badge-type"></span>
                            <span>•</span>
                            <span id="popup-res-provider" class="text-amber-200"></span>
                            <span>•</span>
                            <span id="popup-res-date"></span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <!-- Action buttons in header -->
                    <button type="button" onclick="openPublishModal()" title="Kirim ke Siswa"
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-500 hover:bg-emerald-400 text-white shadow-xs transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        <span class="hidden sm:inline">Ajukan ke Review AI</span>
                    </button>
                    <button type="button" onclick="copySummaryText()" title="Salin Semua"
                            class="p-2 rounded-full bg-white/15 hover:bg-white/25 text-white transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </button>
                    <button type="button" onclick="downloadCurrentSummary()" title="Unduh .MD"
                            class="p-2 rounded-full bg-white/15 hover:bg-white/25 text-white transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </button>
                    <button type="button" onclick="closeResultPopup()"
                            class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 text-white flex items-center justify-center font-black text-lg transition-all ml-1">&times;</button>
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
                    <span class="font-black text-blue-600 ml-2" id="popup-res-words-summary">—</span>
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

            <!-- Footer -->
            <div class="px-6 sm:px-8 py-4 border-t border-slate-100 bg-slate-50/60 flex items-center justify-between gap-3 flex-shrink-0">
                <p class="text-[11px] text-slate-400 font-medium">Dibuat oleh <span class="font-bold text-blue-600">RoboMath AI</span></p>
                <button type="button" onclick="closeResultPopup()"
                        class="px-5 py-2 rounded-full text-xs font-bold bg-slate-800 text-white hover:bg-slate-700 shadow-xs transition-all">
                    Tutup Popup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL: PUBLISH SOAL KE REVIEW AI -->
<!-- ============================================================ -->
<div id="publish-modal" class="hidden fixed inset-0 z-[70] flex items-center justify-center p-4" style="background: rgba(0,0,0,0.55); backdrop-filter: blur(4px);">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-6 sm:p-8 space-y-5 relative border border-slate-100">
        <!-- Modal Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl flex-shrink-0">
                    🤖
                </div>
                <div>
                    <h3 class="text-base font-black text-slate-900">Ajukan Soal ke Review AI</h3>
                    <p class="text-xs text-slate-400 font-medium">Tinjau & verifikasi soal sebelum diterbitkan ke siswa</p>
                </div>
            </div>
            <button type="button" onclick="closePublishModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center font-bold text-base transition-all">&times;</button>
        </div>

        <!-- Info Card -->
        <div class="p-3 rounded-2xl bg-blue-50/80 border border-blue-200/80 text-xs font-semibold text-blue-900 flex items-start gap-2.5">
            <span class="text-base flex-shrink-0 mt-0.5">💡</span>
            <span>Soal akan disimpan dengan status <strong>"Menunggu Tinjauan"</strong> di halaman Review AI sebelum aktif dikerjakan siswa.</span>
        </div>

        <!-- Preview -->
        <div id="publish-preview" class="hidden p-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs font-bold text-emerald-800 flex items-center gap-2">
            <span class="text-base">✅</span>
            <span id="publish-preview-text"></span>
        </div>

        <!-- Form Fields -->
        <div class="space-y-4">
            <!-- Pilih Topik -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Topik Tujuan <span class="text-rose-500">*</span></label>
                <select id="publish-topic-id" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 font-bold text-slate-800 text-xs outline-none transition-all">
                    <option value="">-- Pilih Topik --</option>
                    @foreach(\App\Models\Topic::where('is_active', true)->orderBy('kelas_level')->orderBy('order')->get() as $t)
                        <option value="{{ $t->id }}">{{ $t->icon }} {{ $t->title }} (Kelas {{ $t->kelas_level }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Format Soal -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Format Soal</label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold cursor-pointer transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 has-[:checked]:text-blue-800">
                        <input type="radio" name="publish-type" value="essay" id="publish-type-essay" class="sr-only" checked>
                        <span>✍️ Essay / Uraian</span>
                    </label>
                    <label class="flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold cursor-pointer transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 has-[:checked]:text-blue-800">
                        <input type="radio" name="publish-type" value="pilihan_ganda" id="publish-type-mcq" class="sr-only">
                        <span>🔘 Pilihan Ganda (A-D)</span>
                    </label>
                </div>
            </div>

            <!-- Tingkat Kesulitan -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Tingkat Kesulitan</label>
                <div class="grid grid-cols-3 gap-2">
                    <label class="flex items-center justify-center gap-1.5 px-2.5 py-2 rounded-xl border border-slate-200 text-xs font-bold cursor-pointer transition-all has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 has-[:checked]:text-emerald-800">
                        <input type="radio" name="publish-difficulty" value="mudah" class="sr-only" checked> 🌱 Mudah
                    </label>
                    <label class="flex items-center justify-center gap-1.5 px-2.5 py-2 rounded-xl border border-slate-200 text-xs font-bold cursor-pointer transition-all has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50 has-[:checked]:text-amber-800">
                        <input type="radio" name="publish-difficulty" value="sedang" class="sr-only"> ⚡ Sedang
                    </label>
                    <label class="flex items-center justify-center gap-1.5 px-2.5 py-2 rounded-xl border border-slate-200 text-xs font-bold cursor-pointer transition-all has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50 has-[:checked]:text-rose-800">
                        <input type="radio" name="publish-difficulty" value="sulit" class="sr-only"> 🔥 Sulit
                    </label>
                </div>
            </div>

            <!-- Poin per Soal -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Poin per Soal</label>
                <div class="flex items-center gap-3">
                    <input type="range" id="publish-points" min="5" max="50" step="5" value="10"
                           oninput="document.getElementById('publish-points-label').textContent = this.value"
                           class="flex-1 accent-blue-600">
                    <span class="w-14 text-center font-bold text-blue-700 text-xs bg-blue-50 border border-blue-200 px-2 py-1 rounded-lg">
                        <span id="publish-points-label">10</span> Poin
                    </span>
                </div>
            </div>
        </div>

        <!-- Modal Actions -->
        <div class="flex items-center gap-3 pt-2">
            <button type="button" onclick="closePublishModal()" class="flex-1 px-4 py-2.5 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all">
                Batal
            </button>
            <button type="button" onclick="submitPublishToStudents()" id="btn-publish-confirm"
                    class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-full bg-[#2563EB] hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition-all">
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
                            <div class="p-4 sm:p-5 rounded-2xl bg-blue-50/70 border border-blue-200/80 mb-5">
                                <div class="flex items-center gap-2.5">
                                    <span class="text-xl">🎯</span>
                                    <div>
                                        <h2 class="text-sm sm:text-base font-black text-blue-950">${cleanTitle}</h2>
                                        <p class="text-xs font-semibold text-blue-700/80 mt-0.5">Paket latihan soal dan kuis edukatif untuk siswa SD.</p>
                                    </div>
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
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-2">
                                    ${options.map(opt => `
                                        <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm font-semibold text-slate-700">
                                            <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-700 border border-blue-200 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                                ${opt.letter}
                                            </span>
                                            <span class="leading-relaxed mt-0.5">${opt.text}</span>
                                        </div>
                                    `).join('')}
                                </div>
                            `;
                        }

                        let answerBoxHtml = '';
                        if (keyAnswer || explanation) {
                            answerBoxHtml = `
                                <div class="mt-3 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-950 space-y-1">
                                    ${keyAnswer ? `
                                        <div class="flex items-center gap-1.5 text-xs font-bold text-emerald-900">
                                            <span>🔑 Kunci Jawaban:</span>
                                            <span class="bg-emerald-100 px-2 py-0.5 rounded border border-emerald-300">${keyAnswer}</span>
                                        </div>
                                    ` : ''}
                                    ${explanation ? `
                                        <div class="text-xs text-emerald-900/90 leading-relaxed flex items-start gap-1.5 pt-0.5">
                                            <span class="leading-none">💡</span>
                                            <div><strong>Pembahasan:</strong> ${explanation}</div>
                                        </div>
                                    ` : ''}
                                </div>
                            `;
                        }

                        finalHtml += `
                            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs space-y-3 mb-4">
                                <div class="flex items-center justify-between gap-3 border-b border-slate-100 pb-2.5">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#2563EB] text-white">
                                        Soal #${qNum}
                                    </span>
                                    <span class="text-[10px] font-bold uppercase tracking-wider ${isMultipleChoice ? 'text-blue-700 bg-blue-50 border-blue-200' : 'text-rose-700 bg-rose-50 border-rose-200'} px-2.5 py-0.5 rounded-full border">
                                        ${isMultipleChoice ? 'Pilihan Ganda' : '✍️ Essay / Uraian'}
                                    </span>
                                </div>

                                <div class="text-xs sm:text-sm font-bold text-slate-800 leading-relaxed">
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
                        text: 'AI telah berhasil merangkum materi dan merancang butir-butir soal latihan.',
                        timer: 2000,
                        showConfirmButton: false
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

            if (isLoading) {
                if (loading) { loading.style.display = 'flex'; loading.classList.remove('hidden'); }
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

        function renderSummaryResult(data) {
            state.activeSummaryMarkdown = data.summary_markdown;
            state.activeSummaryTitle = data.title;
            state.activeSummaryId = data.summary_id || null;

            const resTitle = document.getElementById('res-title');
            const badgeType = document.getElementById('res-badge-type');
            const resDate = document.getElementById('res-date');
            const resProvider = document.getElementById('res-provider');
            const resCompression = document.getElementById('res-compression');
            const resWordsOrig = document.getElementById('res-words-original');
            const resWordsSum = document.getElementById('res-words-summary');
            const markdownBody = document.getElementById('res-markdown-body');

            if (resTitle) resTitle.textContent = data.title;
            if (badgeType) badgeType.textContent = `${data.file_type} • ${data.file_size_formatted}`;
            if (resDate) resDate.textContent = data.created_at;
            if (resProvider) resProvider.textContent = data.provider;
            if (resCompression) resCompression.textContent = `${data.compression_ratio}%`;
            if (resWordsOrig) resWordsOrig.textContent = `${(data.word_count_original || 0).toLocaleString()} Kata`;
            if (resWordsSum) resWordsSum.textContent = `${(data.word_count_summary || 0).toLocaleString()} Kata`;

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
                Swal.fire({ icon: 'warning', title: 'Belum Ada Soal', text: 'Buat soal terlebih dahulu.', confirmButtonColor: '#2563EB' });
                return;
            }

            const hasOptions = /(?:^|\n)\s*[-*]?\s*[A-D][\.\)]\s+/i.test(state.activeSummaryMarkdown);
            const questionBlocks = (state.activeSummaryMarkdown.match(/###\s*(?:📝|🌟)?\s*Soal\s*\d+/gi) || []).length;
            
            const radioEssay = document.getElementById('publish-type-essay');
            const radioMcq = document.getElementById('publish-type-mcq');
            if (hasOptions) {
                if (radioMcq) radioMcq.checked = true;
            } else {
                if (radioEssay) radioEssay.checked = true;
            }

            const previewEl = document.getElementById('publish-preview');
            const previewText = document.getElementById('publish-preview-text');
            if (previewEl && previewText) {
                if (questionBlocks > 0) {
                    previewText.textContent = hasOptions 
                        ? `${questionBlocks} butir soal pilihan ganda siap diajukan ke review.`
                        : `${questionBlocks} butir soal essay terdeteksi siap diajukan ke review.`;
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
            const selectedType = document.querySelector('input[name="publish-type"]:checked')?.value || 'essay';

            if (!topicId) {
                Swal.fire({ icon: 'warning', title: 'Pilih Topik', text: 'Silakan pilih topik tujuan untuk soal-soal ini.', confirmButtonColor: '#2563EB' });
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
                        title: `🎉 ${data.count} Soal Berhasil Diajukan!`,
                        text: 'Soal tersimpan dengan status Menunggu Tinjauan di halaman Review AI.',
                        confirmButtonColor: '#2563EB',
                        confirmButtonText: 'Buka Halaman Review AI →',
                        showCancelButton: true,
                        cancelButtonText: 'Tetap di Sini',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = data.review_url || "{{ route('admin.ai-recommendations.index') }}";
                        }
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal Mengajukan', text: data.message || 'Terjadi kesalahan.', confirmButtonColor: '#2563EB' });
                }
            } catch (err) {
                console.error(err);
                Swal.fire({ icon: 'error', title: 'Gagal Mengajukan', text: 'Terjadi gangguan koneksi.', confirmButtonColor: '#2563EB' });
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
