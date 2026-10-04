@extends('layouts.admin')

@section('title', 'Ringkasan Dokumen AI - RoboMath')

@section('content')
<div class="space-y-6 sm:space-y-8">

    <!-- ======================================================== -->
    <!-- HEADER HERO BANNER -->
    <!-- ======================================================== -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-purple-800 via-indigo-700 to-indigo-600 p-6 sm:p-8 text-white shadow-xl shadow-purple-900/15 border border-white/20"
         style="background: linear-gradient(135deg, #6B21A8 0%, #4F46E5 50%, #3B82F6 100%) !important; color: #FFFFFF !important;">
        <!-- Background decorative glows -->
        <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
        <div class="absolute right-1/4 -bottom-20 w-80 h-80 rounded-full bg-fuchsia-400/20 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-3 max-w-2xl">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-white/20 backdrop-blur-md text-white border border-white/30 uppercase tracking-wider shadow-sm">
                        <svg class="w-3.5 h-3.5 text-amber-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        AI Document Summarizer
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-400/90 text-emerald-950 backdrop-blur-sm shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-100 animate-ping"></span>
                        <span class="w-2 h-2 rounded-full bg-emerald-950"></span>
                        AI Siap Digunakan
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight text-white">
                    Ringkasan Dokumen Berbasis AI
                </h1>
                <p class="text-purple-100 text-xs sm:text-sm font-medium leading-relaxed">
                    Unggah dokumen materi, silabus kurikulum, lembar kerja (PDF, DOCX, TXT), atau tempel teks langsung untuk diekstraksi dan diringkas secara otomatis dengan pemahaman mendalam AI.
                </p>
            </div>

            <!-- Quick Stat Counters -->
            <div class="flex items-center gap-3 sm:gap-4 flex-wrap">
                <div class="flex-1 sm:flex-initial rounded-2xl p-4 border border-white/30 text-center min-w-[120px] sm:min-w-[140px] shadow-sm" style="background-color: rgba(255, 255, 255, 0.15) !important;">
                    <div class="text-2xl sm:text-3xl font-black text-white" id="stat-total-summaries">{{ $totalSummaries }}</div>
                    <div class="text-[11px] font-bold text-purple-100 uppercase tracking-wider mt-0.5">Dokumen Diringkas</div>
                </div>
                <div class="flex-1 sm:flex-initial rounded-2xl p-4 border border-white/30 text-center min-w-[120px] sm:min-w-[140px] shadow-sm" style="background-color: rgba(255, 255, 255, 0.15) !important;">
                    <div class="text-2xl sm:text-3xl font-black text-amber-300">{{ number_format($totalWordsAnalyzed) }}</div>
                    <div class="text-[11px] font-bold text-purple-100 uppercase tracking-wider mt-0.5">Total Kata Dianalisis</div>
                </div>
                <div class="flex-1 sm:flex-initial rounded-2xl p-4 border border-white/30 text-center min-w-[120px] sm:min-w-[140px] shadow-sm" style="background-color: rgba(255, 255, 255, 0.15) !important;">
                    <div class="text-2xl sm:text-3xl font-black text-emerald-300">{{ round($avgCompression) }}%</div>
                    <div class="text-[11px] font-bold text-purple-100 uppercase tracking-wider mt-0.5">Efisiensi Baca</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- MAIN WORKSPACE: UPLOAD & CONFIGURATION -->
    <!-- ======================================================== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8">

        <!-- LEFT COLUMN: INPUT DOKUMEN & PENGATURAN (7 COLS) -->
        <div class="lg:col-span-7 space-y-6">
            <div class="bg-white rounded-3xl p-5 sm:p-7 border-2 border-amber-200/80 shadow-sm space-y-6">
                
                <!-- Section Header -->
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-purple-100 text-purple-700 flex items-center justify-center font-black">
                            1
                        </div>
                        <div>
                            <h2 class="text-lg font-black text-slate-800">Pilih Dokumen Sumber</h2>
                            <p class="text-xs text-slate-500 font-medium">Pilih berkas dari perangkat Anda atau masukkan teks secara manual.</p>
                        </div>
                    </div>

                    <!-- Input Mode Toggle -->
                    <div class="inline-flex p-1 rounded-xl bg-slate-100 border border-slate-200 text-xs font-bold">
                        <button type="button" onclick="setInputMode('file')" id="btn-mode-file"
                                class="px-3 py-1.5 rounded-lg transition-all bg-white text-purple-700 shadow-sm">
                            Unggah File
                        </button>
                        <button type="button" onclick="setInputMode('text')" id="btn-mode-text"
                                class="px-3 py-1.5 rounded-lg transition-all text-slate-600 hover:text-slate-900">
                            Tempel Teks
                        </button>
                    </div>
                </div>

                <!-- FORM UPLOAD / INPUT -->
                <form id="summarizer-form" enctype="multipart/form-data" method="POST"
                      action="{{ route('admin.document-summarizer.summarize') }}"
                      data-no-spa class="space-y-6">
                    @csrf

                    <!-- 1. MODE FILE UPLOAD -->
                    <div id="mode-file-container" class="space-y-3">
                        <!-- Hidden Persistent File Input -->
                        <input type="file" id="document_file" name="document_file" accept=".pdf,.docx,.doc,.txt,.md,.csv,.json"
                               style="display: none !important;">

                        <!-- Dropzone Area (Visible when no file selected) -->
                        <div id="dropzone" onclick="document.getElementById('document_file').click()"
                             class="border-2 border-dashed border-purple-300 hover:border-purple-500 bg-purple-50/40 hover:bg-purple-50/80 rounded-2xl p-6 sm:p-8 text-center transition-all duration-200 cursor-pointer relative group">
                            
                            <div class="space-y-3 pointer-events-none" id="dropzone-idle-content">
                                <div class="w-16 h-16 mx-auto rounded-2xl bg-white shadow-md border border-purple-100 flex items-center justify-center text-purple-600 group-hover:scale-110 transition-transform">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-extrabold text-slate-800 text-sm sm:text-base">
                                        Klik untuk memilih berkas atau seret & lepas (drag & drop) ke sini
                                    </p>
                                    <p class="text-xs text-slate-500 font-medium mt-1">
                                        Mendukung format <span class="font-bold text-purple-600">PDF, Word (DOCX), TXT, Markdown</span> (Maks. 20MB)
                                    </p>
                                </div>
                                <div class="pt-1">
                                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-black bg-purple-600 text-white shadow-md group-hover:bg-purple-700 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                        Pilih Berkas Dokumen
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Selected File Preview Card (Visible when file is selected) -->
                        <div id="file-preview-card" class="hidden bg-gradient-to-r from-purple-50 via-white to-slate-50 border-2 border-purple-300 rounded-2xl p-4 shadow-sm transition-all">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-12 h-12 rounded-xl bg-purple-600 text-white flex flex-col items-center justify-center font-black shadow-md flex-shrink-0" id="file-ext-badge-container">
                                        <span class="text-base leading-none" id="file-icon-emoji">📄</span>
                                        <span class="text-[9px] font-black uppercase mt-0.5 tracking-wider" id="file-ext-badge">PDF</span>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="font-black text-slate-800 text-sm sm:text-base truncate" id="file-name-label">document.pdf</span>
                                            <span class="hidden sm:inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                Dokumen Siap Diringkas
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2 text-xs text-slate-500 font-medium mt-0.5">
                                            <span id="file-size-label" class="font-bold text-slate-600">1.2 MB</span>
                                            <span>•</span>
                                            <button type="button" onclick="document.getElementById('document_file').click()" class="text-purple-600 hover:text-purple-800 font-bold hover:underline cursor-pointer">
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
                        <label for="direct_text" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">
                            Teks Materi / Dokumen
                        </label>
                        <div class="relative">
                            <textarea id="direct_text" name="direct_text" rows="7"
                                      placeholder="Salin atau ketik teks materi pembelajaran matematika di sini (minimal 20 karakter)..."
                                      class="w-full rounded-2xl border-2 border-slate-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 p-4 text-sm font-medium text-slate-800 placeholder-slate-400 resize-y transition-all"
                                      oninput="updateTextCounter(this)"></textarea>
                            <div class="absolute bottom-3 right-3 text-[11px] font-bold px-2 py-0.5 rounded-md bg-slate-100 text-slate-500" id="text-char-count">
                                0 kata
                            </div>
                        </div>
                    </div>

                    <!-- Judul Kustom (Opsional) -->
                    <div class="space-y-1.5">
                        <label for="custom_title" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">
                            Judul Dokumen (Opsional)
                        </label>
                        <input type="text" id="custom_title" name="custom_title" placeholder="Contoh: Modul Pecahan Kelas 4 Semester 1"
                               class="w-full px-4 py-3 rounded-2xl border-2 border-slate-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 text-sm font-bold text-slate-800 transition-all">
                    </div>

                    <!-- ======================================================== -->
                    <!-- 2. PENGATURAN GAYA RINGKASAN -->
                    <!-- ======================================================== -->
                    <div class="border-t border-slate-100 pt-5 space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-black">
                                2
                            </div>
                            <div>
                                <h3 class="text-base font-black text-slate-800">Format & Gaya Ringkasan AI</h3>
                                <p class="text-xs text-slate-500 font-medium">Sesuaikan bagaimana AI menyajikan intisari dokumen.</p>
                            </div>
                        </div>

                        <!-- Style Radio Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="style-selector-group">
                            
                            <!-- Style 1: Eksekutif / Poin Inti -->
                            <label class="style-card relative flex items-start gap-3 p-3.5 rounded-2xl border-2 border-purple-500 bg-purple-50/50 cursor-pointer hover:border-purple-500 transition-all">
                                <input type="radio" name="summary_style" value="ringkasan_eksekutif" checked class="mt-1 text-purple-600 focus:ring-purple-500" onchange="highlightStyleCard(this)">
                                <div>
                                    <div class="font-black text-sm text-slate-800 flex items-center gap-1.5">
                                        <span>⚡ Intisari & Poin Kunci</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 font-medium mt-0.5 leading-relaxed">
                                        Ringkasan padat berupa poin-poin gagasan pokok, solusi, dan intisari esensial.
                                    </p>
                                </div>
                            </label>

                            <!-- Style 2: Lengkap Komprehensif -->
                            <label class="style-card relative flex items-start gap-3 p-3.5 rounded-2xl border-2 border-slate-200 bg-white cursor-pointer hover:border-purple-300 transition-all">
                                <input type="radio" name="summary_style" value="ringkasan_lengkap" class="mt-1 text-purple-600 focus:ring-purple-500" onchange="highlightStyleCard(this)">
                                <div>
                                    <div class="font-black text-sm text-slate-800 flex items-center gap-1.5">
                                        <span>📑 Rangkuman Komprehensif</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 font-medium mt-0.5 leading-relaxed">
                                        Ulasan mendalam terstruktur per bab/topik dengan penjelasan konsep rinci.
                                    </p>
                                </div>
                            </label>

                            <!-- Style 3: Ramah Anak SD -->
                            <label class="style-card relative flex items-start gap-3 p-3.5 rounded-2xl border-2 border-slate-200 bg-white cursor-pointer hover:border-purple-300 transition-all">
                                <input type="radio" name="summary_style" value="anak_sd" class="mt-1 text-purple-600 focus:ring-purple-500" onchange="highlightStyleCard(this)">
                                <div>
                                    <div class="font-black text-sm text-slate-800 flex items-center gap-1.5">
                                        <span>🧒 Bahasa Ramah Anak SD</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 font-medium mt-0.5 leading-relaxed">
                                        Bahasa sederhana, ceria, dengan analogi ramah anak yang mudah dipahami siswa.
                                    </p>
                                </div>
                            </label>

                            <!-- Style 4: Peta Konsep & Glosarium -->
                            <label class="style-card relative flex items-start gap-3 p-3.5 rounded-2xl border-2 border-slate-200 bg-white cursor-pointer hover:border-purple-300 transition-all">
                                <input type="radio" name="summary_style" value="peta_konsep" class="mt-1 text-purple-600 focus:ring-purple-500" onchange="highlightStyleCard(this)">
                                <div>
                                    <div class="font-black text-sm text-slate-800 flex items-center gap-1.5">
                                        <span>🧭 Peta Konsep & Istilah</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 font-medium mt-0.5 leading-relaxed">
                                        Daftar istilah kata kunci, definisi pokok, serta hubungan antar ide materi.
                                    </p>
                                </div>
                            </label>

                            <!-- Style 5: Kuis Latihan Evaluasi -->
                            <label class="style-card sm:col-span-2 relative flex items-start gap-3 p-3.5 rounded-2xl border-2 border-slate-200 bg-white cursor-pointer hover:border-purple-300 transition-all">
                                <input type="radio" name="summary_style" value="kuis_latihan" class="mt-1 text-purple-600 focus:ring-purple-500" onchange="highlightStyleCard(this)">
                                <div>
                                    <div class="font-black text-sm text-slate-800 flex items-center gap-1.5">
                                        <span>🎯 Soal & Kuis Latihan dari Dokumen</span>
                                        <span class="text-[10px] font-black px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">Rekomendasi Guru</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 font-medium mt-0.5 leading-relaxed">
                                        AI otomatis merancang 5 butir soal pilihan ganda, kunci jawaban, dan pembahasan berdasarkan isi dokumen.
                                    </p>
                                </div>
                            </label>

                        </div>

                        <!-- Panjang Ringkasan & Fokus -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                                    Panjang Ringkasan
                                </label>
                                <div class="grid grid-cols-3 gap-2">
                                    <label class="cursor-pointer text-center">
                                        <input type="radio" name="summary_length" value="singkat" class="peer sr-only">
                                        <div class="py-2.5 px-2 rounded-xl border-2 border-slate-200 peer-checked:border-purple-600 peer-checked:bg-purple-50 peer-checked:text-purple-700 text-slate-600 text-xs font-bold transition-all">
                                            Singkat
                                        </div>
                                    </label>
                                    <label class="cursor-pointer text-center">
                                        <input type="radio" name="summary_length" value="sedang" checked class="peer sr-only">
                                        <div class="py-2.5 px-2 rounded-xl border-2 border-slate-200 peer-checked:border-purple-600 peer-checked:bg-purple-50 peer-checked:text-purple-700 text-slate-600 text-xs font-bold transition-all">
                                            Sedang
                                        </div>
                                    </label>
                                    <label class="cursor-pointer text-center">
                                        <input type="radio" name="summary_length" value="mendalam" class="peer sr-only">
                                        <div class="py-2.5 px-2 rounded-xl border-2 border-slate-200 peer-checked:border-purple-600 peer-checked:bg-purple-50 peer-checked:text-purple-700 text-slate-600 text-xs font-bold transition-all">
                                            Mendalam
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label for="focus_topic" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                                    Fokus Tambahan (Opsional)
                                </label>
                                <input type="text" id="focus_topic" name="focus_topic" placeholder="Misal: Fokus pada rumus luas..."
                                       class="w-full px-4 py-2.5 rounded-xl border-2 border-slate-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 text-xs font-bold text-slate-800 transition-all">
                            </div>
                        </div>

                        <!-- Checkbox Simpan ke Riwayat -->
                        <div class="flex items-center gap-2.5 pt-2">
                            <input type="checkbox" id="save_history" name="save_history" value="1" checked
                                   class="w-4 h-4 rounded text-purple-600 focus:ring-purple-500 border-slate-300">
                            <label for="save_history" class="text-xs font-bold text-slate-700 select-none cursor-pointer">
                                Simpan hasil ringkasan ke riwayat dokumen
                            </label>
                        </div>

                    </div>

                    <!-- TOMBOL SUBMIT EKSEKUSI -->
                    <div class="pt-2">
                        <button type="submit" id="btn-submit-summarize"
                                class="w-full flex items-center justify-center gap-3 py-4 px-6 rounded-2xl font-black text-white bg-gradient-to-r from-purple-600 via-indigo-600 to-indigo-700 hover:from-purple-700 hover:to-indigo-800 shadow-lg shadow-purple-500/25 active:scale-[0.99] transition-all duration-200 cursor-pointer">
                            <svg class="w-5 h-5 text-amber-300 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span>Mulai Ringkas Dokumen dengan AI</span>
                        </button>
                    </div>

                </form>

            </div>
        </div>

        <!-- RIGHT COLUMN: HASIL RINGKASAN AI (5 COLS) -->
        <div class="lg:col-span-5 space-y-6">

            <!-- STATE 1: KOTAK PLACEHOLDER KOSONG (Belum Ada Ringkasan) -->
            <div id="result-placeholder" class="bg-white rounded-3xl p-8 border-2 border-dashed border-amber-200/90 text-center flex flex-col items-center justify-center min-h-[460px] shadow-sm">
                <div class="w-20 h-20 rounded-3xl bg-amber-50 border-2 border-amber-200/80 flex items-center justify-center text-amber-600 mb-4 shadow-inner">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-black text-slate-800">Menunggu Dokumen Anda</h3>
                <p class="text-xs text-slate-500 font-medium max-w-sm mt-1 leading-relaxed">
                    Pilih dokumen di sebelah kiri lalu klik tombol <strong>Mulai Ringkas</strong>. Hasil ringkasan pintar AI akan langsung ditampilkan di sini.
                </p>
                <div class="mt-6 flex flex-wrap justify-center gap-2">
                    <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-[11px] font-bold">PDF Reader</span>
                    <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-[11px] font-bold">Word DOCX</span>
                    <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-[11px] font-bold">Ekstraksi Teks</span>
                    <span class="px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-[11px] font-bold">Gemini AI</span>
                </div>
            </div>

            <!-- STATE 2: LOADING PROGRESS INDICATOR -->
            <div id="result-loading" class="hidden bg-white rounded-3xl p-8 border-2 border-purple-200 text-center flex flex-col items-center justify-center min-h-[460px] shadow-sm space-y-5">
                <div class="relative">
                    <div class="w-20 h-20 rounded-full border-4 border-purple-100 border-t-purple-600 animate-spin"></div>
                    <div class="absolute inset-0 flex items-center justify-center text-xl">🤖</div>
                </div>
                <div class="space-y-1">
                    <h3 class="text-lg font-black text-slate-800" id="loading-stage-text">Membaca berkas dokumen...</h3>
                    <p class="text-xs text-slate-500 font-medium">Model AI sedang membaca dan menyusun intisari materi secara optimal.</p>
                    <p class="text-[11px] text-amber-600 font-bold mt-1">⏳ AI lokal mungkin membutuhkan 1–3 menit. Harap ditunggu!</p>
                </div>
                <div class="w-full max-w-xs bg-slate-100 h-2 rounded-full overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 h-full w-2/3 animate-pulse"></div>
                </div>
            </div>

            <!-- STATE 3: RESULT CONTENT CARD -->
            <div id="result-container" class="hidden bg-white rounded-3xl p-5 sm:p-7 border-2 border-amber-200/80 shadow-md space-y-6">
                
                <!-- Result Header & Metrics -->
                <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                    <div>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-purple-100 text-purple-800 uppercase tracking-wider mb-1" id="res-badge-type">
                            PDF • 1.2 MB
                        </span>
                        <h3 class="text-base sm:text-lg font-black text-slate-900 leading-snug" id="res-title">
                            Judul Dokumen
                        </h3>
                        <div class="flex items-center gap-3 text-xs text-slate-400 font-semibold mt-1">
                            <span id="res-date">Hari ini, 15:30</span>
                            <span>•</span>
                            <span class="text-indigo-600 font-bold" id="res-provider">Gemini Flash</span>
                        </div>
                    </div>

                    <!-- Compression Metric Badge -->
                    <div class="text-right flex-shrink-0">
                        <div class="text-xl sm:text-2xl font-black text-emerald-600" id="res-compression">78%</div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pemadatan Kata</div>
                    </div>
                </div>

                <!-- Word Count Comparison Strip -->
                <div class="grid grid-cols-2 gap-3 bg-amber-50/60 p-3 rounded-2xl border border-amber-200/70 text-xs">
                    <div>
                        <div class="text-[10px] font-bold text-amber-900/60 uppercase">Kata Dokumen Asli</div>
                        <div class="font-black text-slate-800 text-sm" id="res-words-original">1,240 Kata</div>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-amber-900/60 uppercase">Hasil Ringkasan</div>
                        <div class="font-black text-purple-700 text-sm" id="res-words-summary">280 Kata</div>
                    </div>
                </div>

                <!-- Action Buttons: Copy, Download, Print -->
                <div class="flex items-center gap-2 flex-wrap">
                    <button type="button" onclick="copySummaryText()"
                            class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold bg-purple-50 text-purple-700 hover:bg-purple-100 border border-purple-200 transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <span>Salin Teks</span>
                    </button>

                    <button type="button" onclick="downloadCurrentSummary()"
                            class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200 transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>Unduh .MD</span>
                    </button>

                    <button type="button" onclick="printSummary()"
                            class="p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 border border-slate-200 transition-all cursor-pointer" title="Cetak Ringkasan">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    </button>
                </div>

                <!-- Markdown Content Render Container -->
                <div class="space-y-4">
                    <div id="res-markdown-body" class="prose prose-purple prose-sm max-w-none text-slate-700 leading-relaxed max-h-[500px] overflow-y-auto pr-2 border-t border-slate-100 pt-4">
                        <!-- Dynamic Markdown HTML goes here -->
                    </div>
                </div>

                <!-- Highlight Key Points Strip -->
                <div id="res-key-points-box" class="hidden bg-indigo-50/70 border border-indigo-200 rounded-2xl p-4 space-y-2.5">
                    <div class="text-xs font-black text-indigo-900 uppercase tracking-wider flex items-center gap-1.5">
                        <span>📌 Poin Kunci Dokumen</span>
                    </div>
                    <ul class="text-xs font-medium text-slate-700 space-y-2 list-none" id="res-key-points-list">
                        <!-- Bullet points appended dynamically -->
                    </ul>
                </div>

            </div>

        </div>

    </div>

    <!-- ======================================================== -->
    <!-- RIWAYAT DOKUMEN RINGKASAN TERAKHIR (HISTORY TABLE) -->
    <!-- ======================================================== -->
    <div class="bg-white rounded-3xl p-5 sm:p-7 border-2 border-amber-200/80 shadow-sm space-y-5">
        
        <!-- Table Header with Search & Filter -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100 pb-4">
            <div>
                <h2 class="text-lg font-black text-slate-800 flex items-center gap-2">
                    <span>Riwayat Ringkasan Dokumen</span>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">{{ $summaries->total() }}</span>
                </h2>
                <p class="text-xs text-slate-500 font-medium">Daftar arsip dokumen yang pernah diunggah dan dianalisis oleh AI.</p>
            </div>

            <form method="GET" action="{{ route('admin.document-summarizer.index') }}" class="flex items-center gap-2 flex-wrap">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama dokumen..."
                       class="px-3 py-2 rounded-xl border-2 border-slate-200 text-xs font-bold text-slate-800 placeholder-slate-400 focus:border-purple-500 transition-all">
                
                <select name="style" onchange="this.form.submit()" class="px-3 py-2 rounded-xl border-2 border-slate-200 text-xs font-bold text-slate-700 focus:border-purple-500 transition-all">
                    <option value="">Semua Format</option>
                    <option value="ringkasan_eksekutif" {{ request('style') == 'ringkasan_eksekutif' ? 'selected' : '' }}>Intisari</option>
                    <option value="ringkasan_lengkap" {{ request('style') == 'ringkasan_lengkap' ? 'selected' : '' }}>Komprehensif</option>
                    <option value="anak_sd" {{ request('style') == 'anak_sd' ? 'selected' : '' }}>Ramah Anak SD</option>
                    <option value="peta_konsep" {{ request('style') == 'peta_konsep' ? 'selected' : '' }}>Peta Konsep</option>
                    <option value="kuis_latihan" {{ request('style') == 'kuis_latihan' ? 'selected' : '' }}>Kuis Latihan</option>
                </select>

                @if(request('q') || request('style'))
                    <a href="{{ route('admin.document-summarizer.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold hover:bg-slate-200">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        @if($summaries->isEmpty())
            <div class="py-12 text-center text-slate-400 space-y-2">
                <div class="text-4xl">📂</div>
                <div class="text-sm font-bold text-slate-600">Belum ada riwayat dokumen yang diringkas.</div>
                <p class="text-xs text-slate-400">Gunakan formulir di atas untuk meringkas dokumen pertama Anda.</p>
            </div>
        @else
            <!-- Table View -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 font-extrabold uppercase tracking-wider">
                            <th class="py-3 px-4">Nama Dokumen</th>
                            <th class="py-3 px-3">Gaya Ringkasan</th>
                            <th class="py-3 px-3">Kata Asli / Ringkas</th>
                            <th class="py-3 px-3">Efisiensi</th>
                            <th class="py-3 px-3">Tanggal</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @foreach($summaries as $item)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <span class="w-8 h-8 rounded-lg bg-purple-100 text-purple-700 font-black text-[10px] flex items-center justify-center uppercase flex-shrink-0">
                                            {{ $item->file_type }}
                                        </span>
                                        <div class="min-w-0">
                                            <div class="font-extrabold text-slate-800 text-sm truncate max-w-[220px]" title="{{ $item->title }}">
                                                {{ $item->title }}
                                            </div>
                                            <div class="text-[11px] text-slate-400 truncate max-w-[220px]" title="{{ $item->original_filename }}">
                                                {{ $item->original_filename }} ({{ $item->formatted_file_size }})
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-3">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700">
                                        {{ $item->style_label }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-3 whitespace-nowrap">
                                    <div class="font-bold text-slate-800">{{ number_format($item->word_count_original) }} <span class="text-slate-400 font-normal">kata</span></div>
                                    <div class="text-[11px] text-purple-600 font-bold">↳ {{ number_format($item->word_count_summary) }} kata ringkas</div>
                                </td>
                                <td class="py-3.5 px-3 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 font-black text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md text-xs">
                                        ↓ {{ $item->compression_ratio }}%
                                    </span>
                                </td>
                                <td class="py-3.5 px-3 whitespace-nowrap text-slate-500">
                                    <div>{{ $item->created_at->translatedFormat('d M Y') }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $item->created_at->format('H:i') }} WIB</div>
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Lihat Modal -->
                                        <button type="button" onclick="viewSummaryModal({{ $item->id }})"
                                                class="px-2.5 py-1.5 rounded-xl bg-purple-50 text-purple-700 hover:bg-purple-100 font-bold text-xs transition-all" title="Baca Ringkasan">
                                            Lihat
                                        </button>

                                        <!-- Unduh -->
                                        <a href="{{ route('admin.document-summarizer.download', $item->id) }}"
                                           class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all" title="Unduh File">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        </a>

                                        <!-- Hapus -->
                                        <form action="{{ route('admin.document-summarizer.destroy', $item->id) }}" method="POST"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus ringkasan dokumen ini?');" class="inline m-0">
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
<!-- MODAL POPUP: LIHAT RINGKASAN LENGKAP -->
<!-- ======================================================== -->
<div id="summary-modal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-3xl w-full max-h-[90vh] flex flex-col shadow-2xl border-2 border-amber-200 overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        
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
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                <span>Salin Teks Ringkasan</span>
            </button>

            <button type="button" onclick="closeSummaryModal()" class="px-5 py-2 rounded-xl text-xs font-black bg-purple-600 text-white hover:bg-purple-700 shadow-md">
                Tutup
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

    // State variables
    let currentInputMode = 'file';
    let currentActiveSummaryMarkdown = '';
    let currentActiveSummaryTitle = '';
    let modalMarkdownCache = '';

    // Switch Input Mode between Upload File and Paste Text
    window.setInputMode = function(mode) {
        console.log('🔄 Switched input mode to:', mode);
        currentInputMode = mode;
        const fileContainer = document.getElementById('mode-file-container');
        const textContainer = document.getElementById('mode-text-container');
        const btnFile = document.getElementById('btn-mode-file');
        const btnText = document.getElementById('btn-mode-text');

        if (mode === 'file') {
            if (fileContainer) fileContainer.classList.remove('hidden');
            if (textContainer) textContainer.classList.add('hidden');
            if (btnFile) btnFile.className = 'px-3 py-1.5 rounded-lg transition-all bg-white text-purple-700 shadow-sm';
            if (btnText) btnText.className = 'px-3 py-1.5 rounded-lg transition-all text-slate-600 hover:text-slate-900';
        } else {
            if (fileContainer) fileContainer.classList.add('hidden');
            if (textContainer) textContainer.classList.remove('hidden');
            if (btnText) btnText.className = 'px-3 py-1.5 rounded-lg transition-all bg-white text-purple-700 shadow-sm';
            if (btnFile) btnFile.className = 'px-3 py-1.5 rounded-lg transition-all text-slate-600 hover:text-slate-900';
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

    // Style Selector Highlight
    window.highlightStyleCard = function(radio) {
        document.querySelectorAll('.style-card').forEach(card => {
            card.classList.remove('border-purple-500', 'bg-purple-50/50');
            card.classList.add('border-slate-200', 'bg-white');
        });
        const parent = radio.closest('.style-card');
        if (parent) {
            parent.classList.remove('border-slate-200', 'bg-white');
            parent.classList.add('border-purple-500', 'bg-purple-50/50');
        }
    };

    // Format Bytes helper
    function formatFileSize(bytes) {
        if (!bytes || bytes <= 0) return '0 B';
        if (bytes >= 1048576) return (bytes / 1048576).toFixed(1) + ' MB';
        if (bytes >= 1024) return (bytes / 1024).toFixed(1) + ' KB';
        return bytes + ' B';
    }

    // Markdown Parser with Fallback
    function parseMarkdownToHtml(markdownText) {
        if (!markdownText) return '';
        
        if (typeof marked !== 'undefined' && typeof marked.parse === 'function') {
            try {
                let parsedHtml = marked.parse(markdownText);
                if (typeof DOMPurify !== 'undefined') {
                    parsedHtml = DOMPurify.sanitize(parsedHtml);
                }
                return parsedHtml;
            } catch (e) {
                console.warn('Marked.parse error, using fallback:', e);
            }
        }

        // Built-in Lightweight Markdown Parser Fallback
        let html = markdownText
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');

        // Headings
        html = html.replace(/^### (.*$)/gim, '<h3 class="text-base font-bold text-slate-900 mt-4 mb-2">$1</h3>');
        html = html.replace(/^## (.*$)/gim, '<h2 class="text-lg font-black text-purple-900 mt-5 mb-2 pb-1 border-b border-purple-100">$1</h2>');
        html = html.replace(/^# (.*$)/gim, '<h1 class="text-xl font-black text-slate-900 mt-6 mb-3">$1</h1>');

        // Blockquotes
        html = html.replace(/^\> (.*$)/gim, '<blockquote class="border-l-4 border-purple-500 bg-purple-50/60 p-3 my-3 rounded-r-xl text-slate-700 italic">$1</blockquote>');

        // Bold & Italic
        html = html.replace(/\*\*(.*?)\*\*/gim, '<strong class="font-bold text-slate-900">$1</strong>');
        html = html.replace(/\*(.*?)\*/gim, '<em class="italic">$1</em>');

        // Lists
        html = html.replace(/^\- (.*$)/gim, '<li class="ml-4 list-disc text-slate-700 my-1">$1</li>');
        html = html.replace(/^\* (.*$)/gim, '<li class="ml-4 list-disc text-slate-700 my-1">$1</li>');

        // Line breaks & paragraphs
        html = html.replace(/\n\n+/g, '</p><p class="my-2 leading-relaxed">');
        html = html.replace(/\n/g, '<br>');

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

            if (currentInputMode === 'file' && (!fileInput || !fileInput.files || !fileInput.files[0])) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Berkas Belum Dipilih',
                    text: 'Silakan pilih atau seret berkas dokumen (PDF, DOCX, TXT) terlebih dahulu!',
                    confirmButtonColor: '#7C3AED'
                });
                return;
            }

            if (currentInputMode === 'text' && (!textInput || !textInput.value || textInput.value.trim().length < 10)) {
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
            if (currentInputMode === 'file') {
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
                    title: 'Dokumen Berhasil Diringkas!',
                    text: `Ringkasan cerdas berhasil dibuat (pemadatan ${data.compression_ratio}%).`,
                    timer: 2500,
                    showConfirmButton: false
                });

            } catch (error) {
                console.error(error);
                const isTimeout = error.name === 'AbortError';
                Swal.fire({
                    icon: 'error',
                    title: isTimeout ? 'Waktu Habis (Timeout)' : 'Gagal Meringkas Dokumen',
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
                'Mengekstrak teks dari dokumen...',
                'Mengirim konten ke model AI...',
                'AI sedang menganalisis materi... (1-3 menit untuk AI lokal)',
                'Menyusun intisari & poin-poin kunci...',
                'Hampir selesai, AI merampungkan format ringkasan...'
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

    // Render Summary Result into UI
    function renderSummaryResult(data) {
        const placeholder = document.getElementById('result-placeholder');
        const loading = document.getElementById('result-loading');
        const container = document.getElementById('result-container');

        if (placeholder) { placeholder.style.display = 'none'; placeholder.classList.add('hidden'); }
        if (loading) { loading.style.display = 'none'; loading.classList.add('hidden'); }
        if (container) { container.style.display = 'block'; container.classList.remove('hidden'); }

        currentActiveSummaryMarkdown = data.summary_markdown;
        currentActiveSummaryTitle = data.title;

        // Populate header fields
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

        // Render Markdown HTML
        if (markdownBody) {
            markdownBody.innerHTML = parseMarkdownToHtml(data.summary_markdown);
        }

        // Render Key Points Box
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

        // Scroll result smoothly into view
        if (container) {
            container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }

    // Copy Summary Text
    window.copySummaryText = function() {
        if (!currentActiveSummaryMarkdown) return;
        navigator.clipboard.writeText(currentActiveSummaryMarkdown).then(() => {
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
        if (!currentActiveSummaryMarkdown) return;
        const blob = new Blob([currentActiveSummaryMarkdown], { type: 'text/markdown;charset=utf-8' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        const safeTitle = (currentActiveSummaryTitle || 'Ringkasan_Dokumen').replace(/[^a-zA-Z0-9_-]/g, '_');
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
        const title = currentActiveSummaryTitle || 'Ringkasan Dokumen RoboMath';
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

            modalMarkdownCache = data.summary;
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
        if (!modalMarkdownCache) return;
        navigator.clipboard.writeText(modalMarkdownCache).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Teks Berhasil Disalin!',
                timer: 1500,
                showConfirmButton: false
            });
        });
    };

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
