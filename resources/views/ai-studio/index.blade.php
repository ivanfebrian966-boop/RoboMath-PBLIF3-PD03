@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.app')

@section('title', 'AI Math Question Studio - RoboMath')

@section('content')
<div class="space-y-6 sm:space-y-8">

    <!-- ======================================================== -->
    <!-- HEADER HERO BANNER -->
    <!-- ======================================================== -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-700 via-indigo-600 to-purple-600 p-6 sm:p-8 text-white shadow-xl shadow-indigo-500/10 border border-white/20" style="background: linear-gradient(135deg, #4338CA 0%, #6366F1 50%, #8B5CF6 100%) !important; color: #FFFFFF !important;">
        <!-- Background decorative glows -->
        <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
        <div class="absolute right-1/3 -bottom-20 w-80 h-80 rounded-full bg-fuchsia-500/20 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-3 max-w-2xl">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-white/20 backdrop-blur-md text-white border border-white/30 uppercase tracking-wider shadow-sm">
                        <svg class="w-3.5 h-3.5 text-amber-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        AI Math Studio
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-400/90 text-emerald-950 backdrop-blur-sm shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-100 animate-ping"></span>
                        <span class="w-2 h-2 rounded-full bg-emerald-950"></span>
                        LLM Aktif (Gemini)
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight text-white">
                    Laboratorium AI Soal Matematika SD
                </h1>
                <p class="text-indigo-100 text-xs sm:text-sm font-medium leading-relaxed">
                    Eksplorasi dataset soal matematika beranotasi taksonomi kognitif Bloom, analisis tingkat berpikir soal secara real-time, dan sintesis butir soal kontekstual ramah anak.
                </p>
            </div>

            <!-- Quick Stat Pill Counters -->
            <div class="flex items-center gap-3 sm:gap-4 flex-wrap">
                <div class="flex-1 sm:flex-initial rounded-2xl p-4 border border-white/30 text-center min-w-[120px] sm:min-w-[140px] shadow-sm" style="background-color: rgba(255, 255, 255, 0.15) !important;">
                    <div class="text-2xl sm:text-3xl font-black text-white">{{ $totalQuestions }}</div>
                    <div class="text-[11px] font-bold text-indigo-100 uppercase tracking-wider mt-0.5">Dataset Soal</div>
                </div>
                <div class="flex-1 sm:flex-initial rounded-2xl p-4 border border-white/30 text-center min-w-[120px] sm:min-w-[140px] shadow-sm" style="background-color: rgba(255, 255, 255, 0.15) !important;">
                    <div class="text-2xl sm:text-3xl font-black text-amber-300">1 – 6</div>
                    <div class="text-[11px] font-bold text-indigo-100 uppercase tracking-wider mt-0.5">Jenjang SD</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODERN SEGMENTED TAB NAVIGATION -->
    <!-- ======================================================== -->
    <div class="bg-amber-100/70 p-1.5 rounded-2xl border border-amber-200/80 shadow-inner flex flex-wrap gap-1.5" id="studio-tabs">
        <button onclick="switchTab('dataset-tab')" id="btn-dataset-tab"
            class="tab-btn flex-1 sm:flex-initial flex items-center justify-center gap-2 px-5 py-3 rounded-xl font-black text-xs sm:text-sm transition-all duration-200 bg-white text-indigo-700 shadow-md border border-indigo-100">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            <span>Dataset Soal SD</span>
            <span class="px-2 py-0.5 text-[10px] rounded-full bg-indigo-100 text-indigo-800 font-extrabold">{{ $totalQuestions }}</span>
        </button>

        <button onclick="switchTab('analyzer-tab')" id="btn-analyzer-tab"
            class="tab-btn flex-1 sm:flex-initial flex items-center justify-center gap-2 px-5 py-3 rounded-xl font-black text-xs sm:text-sm transition-all duration-200 text-slate-600 hover:text-slate-900 hover:bg-white/60">
            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <span>Analisis Level Kognitif</span>
        </button>

        <button onclick="switchTab('generator-tab')" id="btn-generator-tab"
            class="tab-btn flex-1 sm:flex-initial flex items-center justify-center gap-2 px-5 py-3 rounded-xl font-black text-xs sm:text-sm transition-all duration-200 text-slate-600 hover:text-slate-900 hover:bg-white/60">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            <span>AI Question Generator</span>
        </button>
    </div>

    <!-- ======================================================== -->
    <!-- TAB 1: DATASET VIEWER -->
    <!-- ======================================================== -->
    <div id="dataset-tab" class="tab-content space-y-6">
        
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl p-4 sm:p-5 border-2 border-amber-200/80 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-black text-xl flex-shrink-0">
                    📚
                </div>
                <div>
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Cakupan Jenjang</div>
                    <div class="text-base sm:text-lg font-black text-slate-800">Kelas 1 s.d. 6 SD</div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-4 sm:p-5 border-2 border-amber-200/80 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 font-black text-xl flex-shrink-0">
                    🎯
                </div>
                <div>
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Taksonomi Kognitif</div>
                    <div class="text-base sm:text-lg font-black text-slate-800">Bloom & Kontekstual</div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-4 sm:p-5 border-2 border-amber-200/80 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 font-black text-xl flex-shrink-0">
                    💾
                </div>
                <div>
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Sumber Dataset</div>
                    <div class="text-xs sm:text-sm font-black text-slate-800 truncate max-w-[200px]" title="math_questions_dataset.json">
                        math_questions_dataset.json
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Card with Live Search & Grade Filters -->
        <div class="bg-white rounded-3xl border-2 border-amber-200/80 shadow-sm overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-amber-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg sm:text-xl font-black text-slate-800 flex items-center gap-2">
                        <span>📋</span> Koleksi Dataset Soal Teranotasi
                    </h2>
                    <p class="text-xs font-semibold text-slate-500 mt-0.5">
                        Daftar butir soal matematika sekolah dasar lengkap dengan label kognitif dan konteks.
                    </p>
                </div>

                <!-- Clean Search and Filter Controls -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 w-full lg:w-auto">
                    <!-- Live Search Input -->
                    <div class="relative flex-1 sm:w-56">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="text" id="datasetSearch" oninput="filterDataset()" placeholder="Cari soal / materi..."
                            class="w-full pl-9 pr-3 py-2 text-xs font-bold bg-amber-50/70 border border-amber-200 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    </div>

                    <!-- Grade Selector -->
                    <div class="sm:w-36">
                        <select id="gradeFilter" onchange="filterDataset()"
                            class="w-full bg-amber-50/70 border border-amber-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            <option value="all">Semua Kelas</option>
                            @for($i = 1; $i <= 6; $i++)
                                <option value="{{ $i }}">Kelas {{ $i }} SD</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Cognitive Filter -->
                    <div class="sm:w-36">
                        <select id="cogFilter" onchange="filterDataset()"
                            class="w-full bg-amber-50/70 border border-amber-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            <option value="all">Semua Level</option>
                            <option value="Remembering">Remembering</option>
                            <option value="Understanding">Understanding</option>
                            <option value="Applying">Applying</option>
                            <option value="Analyzing">Analyzing</option>
                            <option value="Evaluating">Evaluating</option>
                            <option value="Creating">Creating</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm" id="datasetTable">
                    <thead class="bg-amber-50/60 text-slate-600 font-extrabold text-xs uppercase border-b border-amber-100">
                        <tr>
                            <th class="px-5 py-3.5">ID & Kelas</th>
                            <th class="px-5 py-3.5">Materi</th>
                            <th class="px-5 py-3.5">Teks Soal</th>
                            <th class="px-5 py-3.5">Level Kognitif</th>
                            <th class="px-5 py-3.5">Konteks & Bentuk</th>
                            <th class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-amber-100 font-medium text-slate-700" id="datasetTableBody">
                        @forelse($dataset as $item)
                        <tr class="hover:bg-amber-50/40 transition dataset-row"
                            data-grade="{{ $item['grade'] ?? 1 }}"
                            data-cog="{{ strtolower($item['labels']['cognitive_level'] ?? '') }}"
                            data-text="{{ strtolower(($item['question_text'] ?? '').' '.($item['material'] ?? '').' '.($item['id'] ?? '')) }}">
                            
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="bg-indigo-100/80 text-indigo-700 text-xs font-black px-2.5 py-1 rounded-lg">
                                    {{ $item['id'] ?? 'N/A' }}
                                </span>
                                <div class="text-[11px] font-bold text-slate-500 mt-1">Kelas {{ $item['grade'] ?? '-' }} SD</div>
                            </td>

                            <td class="px-5 py-4 font-bold text-slate-800">
                                <div class="text-sm font-extrabold">{{ $item['material'] ?? '-' }}</div>
                                <div class="text-[11px] text-slate-400 font-medium mt-0.5">Sumber: {{ $item['page_source'] ?? 'Buku Tematik' }}</div>
                            </td>

                            <td class="px-5 py-4 max-w-sm">
                                <p class="text-xs font-semibold text-slate-800 leading-relaxed line-clamp-2" title="{{ $item['question_text'] ?? '' }}">
                                    {{ $item['question_text'] ?? '-' }}
                                </p>
                            </td>

                            <td class="px-5 py-4 whitespace-nowrap">
                                @php
                                    $cog = $item['labels']['cognitive_level'] ?? 'Remembering';
                                    $color = match(strtolower($cog)) {
                                        'remembering' => 'bg-sky-50 text-sky-700 border-sky-200',
                                        'understanding' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'applying' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'analyzing' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'evaluating', 'creating' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        default => 'bg-slate-50 text-slate-700 border-slate-200'
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1 border px-2.5 py-1 rounded-full text-xs font-black {{ $color }}">
                                    <span>●</span> {{ $cog }}
                                </span>
                                <div class="text-[11px] text-slate-400 font-medium mt-1">{{ $item['labels']['question_form'] ?? '' }}</div>
                            </td>

                            <td class="px-5 py-4 whitespace-nowrap text-xs">
                                <span class="bg-amber-100/80 text-amber-900 font-bold px-2 py-0.5 rounded-md">
                                    {{ $item['labels']['question_context'] ?? 'Contextual' }}
                                </span>
                                <div class="text-[11px] text-slate-400 mt-1">{{ $item['labels']['answer_form'] ?? 'Closed-ended' }}</div>
                            </td>

                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                <button onclick="populateAnalyzer('{{ addslashes($item['question_text'] ?? '') }}', {{ $item['grade'] ?? 1 }})"
                                    class="inline-flex items-center gap-1.5 bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white font-black text-xs px-3.5 py-2 rounded-xl border border-indigo-200 hover:border-indigo-600 transition shadow-sm active:scale-95">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    <span>Analisis AI</span>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyRow">
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-bold">
                                Tidak ada data butir soal di dataset.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer count -->
            <div class="p-4 border-t border-amber-100 bg-amber-50/30 text-xs font-bold text-slate-500 flex items-center justify-between">
                <span id="filteredCountText">Menampilkan {{ count($dataset) }} butir soal</span>
                <span class="text-[11px] text-slate-400">Taksonomi Bloom Terstandar</span>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- TAB 2: AI QUESTION CLASSIFIER & ANALYZER -->
    <!-- ======================================================== -->
    <div id="analyzer-tab" class="tab-content space-y-6 hidden">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- Left Form Input -->
            <div class="lg:col-span-6 bg-white rounded-3xl p-6 sm:p-7 border-2 border-amber-200/80 shadow-sm space-y-5">
                <div class="border-b border-amber-100 pb-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black bg-purple-50 text-purple-700 border border-purple-200 mb-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        Taksonomi Bloom & Konteks
                    </div>
                    <h2 class="text-xl font-black text-slate-800 flex items-center gap-2">
                        Analisis Level Kognitif Soal
                    </h2>
                    <p class="text-xs font-semibold text-slate-500 mt-1">
                        Ketikkan atau pilih contoh butir soal matematika untuk didiagnosis oleh Gemini LLM secara otomatis.
                    </p>
                </div>

                <!-- Quick Example Chips -->
                <div class="space-y-1.5">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-wider">💡 Contoh Soal Cepat (Klik untuk Coba):</label>
                    <div class="flex flex-wrap gap-1.5">
                        <button type="button" onclick="setAnalyzerExample(1, '10 tambah 2')"
                            class="px-2.5 py-1 rounded-lg bg-amber-50 hover:bg-amber-100 border border-amber-200 text-xs font-bold text-slate-700 transition">
                            ➕ 10 + 2 (Kls 1)
                        </button>
                        <button type="button" onclick="setAnalyzerExample(2, 'Siti memiliki 12 apel. Ia memberikan 5 apel kepada Budi. Berapa sisa apel Siti?')"
                            class="px-2.5 py-1 rounded-lg bg-amber-50 hover:bg-amber-100 border border-amber-200 text-xs font-bold text-slate-700 transition">
                            🍎 12 Apel Siti (Kls 2)
                        </button>
                        <button type="button" onclick="setAnalyzerExample(4, 'Ibu memotong kue bolu menjadi 8 potong sama besar. Doni memakan 2 potong. Tentukan bentuk pecahan bagian yang dimakan Doni dalam bentuk paling sederhana!')"
                            class="px-2.5 py-1 rounded-lg bg-amber-50 hover:bg-amber-100 border border-amber-200 text-xs font-bold text-slate-700 transition">
                            🍰 Pecahan Kue (Kls 4)
                        </button>
                        <button type="button" onclick="setAnalyzerExample(5, 'Pak Budi memiliki kebun persegi panjang berukuran panjang 24 m dan lebar 15 m. Berapakah keliling dan luas kebun Pak Budi?')"
                            class="px-2.5 py-1 rounded-lg bg-amber-50 hover:bg-amber-100 border border-amber-200 text-xs font-bold text-slate-700 transition">
                            📐 Luas Kebun (Kls 5)
                        </button>
                    </div>
                </div>

                <form id="analyzerForm" onsubmit="handleAnalyze(event)" class="space-y-4">
                    <div>
                        <label class="block text-xs font-black text-slate-700 uppercase mb-1.5">Jenjang Target Siswa</label>
                        <select id="analyzerGrade" class="w-full bg-amber-50/50 border-2 border-amber-200 rounded-2xl px-4 py-2.5 font-bold text-sm text-slate-800 focus:outline-none focus:border-indigo-500 transition">
                            @for($i = 1; $i <= 6; $i++)
                                <option value="{{ $i }}">Kelas {{ $i }} SD</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-black text-slate-700 uppercase">Teks Soal Matematika</label>
                            <span id="charCount" class="text-[11px] font-bold text-slate-400">0 karakter</span>
                        </div>
                        <textarea id="analyzerQuestionText" rows="5" required
                            oninput="document.getElementById('charCount').innerText = this.value.length + ' karakter'"
                            placeholder="Tuliskan butir soal matematika di sini, baik soal hitungan langsung maupun soal cerita..."
                            class="w-full bg-amber-50/50 border-2 border-amber-200 rounded-2xl p-4 font-semibold text-sm text-slate-800 focus:outline-none focus:border-indigo-500 transition resize-none"></textarea>
                    </div>

                    <button type="submit" id="btnAnalyzeSubmit"
                        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-black py-3.5 px-6 rounded-2xl shadow-lg shadow-indigo-500/20 transition duration-200 flex items-center justify-center gap-2 transform active:scale-98">
                        <svg class="w-5 h-5 text-indigo-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span>Mulai Analisis AI</span>
                    </button>
                </form>
            </div>

            <!-- Right Result Box -->
            <div class="lg:col-span-6 bg-white rounded-3xl p-6 sm:p-7 border-2 border-amber-200/80 shadow-sm flex flex-col min-h-[460px]">
                <div>
                    <div class="border-b border-amber-100 pb-4 flex items-center justify-between">
                        <h3 class="text-xl font-black text-slate-800 flex items-center gap-2">
                            <span>📊</span> Hasil Diagnostik AI
                        </h3>
                        <span id="analyzerBadgeStatus" class="hidden text-xs font-black px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200 shadow-sm">
                            ✓ Selesai Dianalisis
                        </span>
                    </div>

                    <!-- Placeholder State -->
                    <div id="analyzerPlaceholder" class="py-20 text-center space-y-3">
                        <div class="w-16 h-16 bg-amber-100/80 text-amber-600 rounded-2xl flex items-center justify-center mx-auto text-3xl shadow-sm">
                            🤖
                        </div>
                        <h4 class="text-base font-black text-slate-800">Menunggu Input Soal</h4>
                        <p class="text-xs font-semibold text-slate-500 max-w-xs mx-auto leading-relaxed">
                            Pilih salah satu contoh cepat atau ketik soal di sebelah kiri, lalu klik <strong>"Mulai Analisis AI"</strong>.
                        </p>
                    </div>

                    <!-- Loading State -->
                    <div id="analyzerLoading" class="py-20 text-center space-y-4 hidden">
                        <div class="relative w-14 h-14 mx-auto">
                            <div class="animate-spin rounded-full h-14 w-14 border-4 border-indigo-200 border-t-indigo-600"></div>
                            <div class="absolute inset-0 flex items-center justify-center text-lg">✨</div>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm font-black text-indigo-700">Gemini LLM sedang berpikir...</p>
                            <p class="text-xs font-medium text-slate-400">Mengkaji taksonomi Bloom, tingkat kesulitan & konteks soal</p>
                        </div>
                    </div>

                    <!-- Result Content -->
                    <div id="analyzerResultContent" class="space-y-4 pt-4 hidden">
                        <!-- 4 Core Metric Widgets -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-indigo-50/80 p-3.5 rounded-2xl border border-indigo-100">
                                <div class="text-[10px] font-black uppercase text-indigo-500 tracking-wider">Cognitive Level</div>
                                <div id="resCognitive" class="text-base font-black text-indigo-900 mt-0.5">-</div>
                                <div class="text-[10px] font-bold text-indigo-400 mt-0.5">Taksonomi Bloom</div>
                            </div>
                            
                            <div class="bg-amber-50/80 p-3.5 rounded-2xl border border-amber-100">
                                <div class="text-[10px] font-black uppercase text-amber-600 tracking-wider">Question Context</div>
                                <div id="resContext" class="text-base font-black text-amber-900 mt-0.5">-</div>
                                <div class="text-[10px] font-bold text-amber-400 mt-0.5">Skenario Soal</div>
                            </div>

                            <div class="bg-emerald-50/80 p-3.5 rounded-2xl border border-emerald-100">
                                <div class="text-[10px] font-black uppercase text-emerald-600 tracking-wider">Question Form</div>
                                <div id="resForm" class="text-base font-black text-emerald-900 mt-0.5">-</div>
                                <div class="text-[10px] font-bold text-emerald-400 mt-0.5">Bentuk Pertanyaan</div>
                            </div>

                            <div class="bg-rose-50/80 p-3.5 rounded-2xl border border-rose-100">
                                <div class="text-[10px] font-black uppercase text-rose-600 tracking-wider">Tingkat Kesulitan</div>
                                <div id="resDifficulty" class="text-base font-black text-rose-900 mt-0.5 capitalize">-</div>
                                <div class="text-[10px] font-bold text-rose-400 mt-0.5">Estimasi Siswa SD</div>
                            </div>
                        </div>

                        <!-- Analysis Summary -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-black uppercase text-slate-700 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                                <span>Ulasan & Alasan Klasifikasi</span>
                            </label>
                            <div id="resSummary" class="bg-slate-50 border border-slate-200 rounded-2xl p-4 text-xs font-semibold text-slate-700 leading-relaxed">
                                -
                            </div>
                        </div>

                        <!-- Pedagogical Suggestions -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-black uppercase text-emerald-800 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                                <span>Saran Peningkatan Pedagogis</span>
                            </label>
                            <div id="resSuggestion" class="bg-emerald-50/70 border border-emerald-200 rounded-2xl p-4 text-xs font-semibold text-emerald-900 leading-relaxed">
                                -
                            </div>
                        </div>

                        <!-- Action Toolbar -->
                        <div class="pt-2 flex items-center justify-end gap-2">
                            <button type="button" onclick="copyAnalysisResult()"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span>Salin Hasil</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- TAB 3: AI QUESTION GENERATOR -->
    <!-- ======================================================== -->
    <div id="generator-tab" class="tab-content space-y-6 hidden">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- Left Generator Form -->
            <div class="lg:col-span-5 bg-white rounded-3xl p-6 sm:p-7 border-2 border-amber-200/80 shadow-sm space-y-5">
                <div class="border-b border-amber-100 pb-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black bg-emerald-50 text-emerald-700 border border-emerald-200 mb-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Sintesis Soal Cerdas
                    </div>
                    <h2 class="text-xl font-black text-slate-800 flex items-center gap-2">
                        Generator Soal Matematika
                    </h2>
                    <p class="text-xs font-semibold text-slate-500 mt-1">
                        Atur parameter materi dan kognitif untuk menghasilkan butir soal cerita baru ramah anak.
                    </p>
                </div>

                <form id="generatorForm" onsubmit="handleGenerate(event)" class="space-y-4">
                    <div>
                        <label class="block text-xs font-black text-slate-700 uppercase mb-1.5">Target Jenjang Kelas</label>
                        <select id="genGrade" class="w-full bg-amber-50/50 border-2 border-amber-200 rounded-2xl px-4 py-2.5 font-bold text-sm text-slate-800 focus:outline-none focus:border-indigo-500 transition">
                            @for($i = 1; $i <= 6; $i++)
                                <option value="{{ $i }}">Kelas {{ $i }} SD</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-700 uppercase mb-1.5">Materi / Topik Pembelajaran</label>
                        <input type="text" id="genMaterial" required
                            placeholder="Contoh: Pecahan, Geometri Bangun Datar, dsb."
                            value="Pecahan dan Desimal"
                            class="w-full bg-amber-50/50 border-2 border-amber-200 rounded-2xl px-4 py-2.5 font-bold text-sm text-slate-800 focus:outline-none focus:border-indigo-500 transition">
                        
                        <!-- Quick Topic Tags -->
                        <div class="flex flex-wrap gap-1 mt-2">
                            <span class="text-[10px] font-bold text-slate-400 py-0.5">Rekomendasi:</span>
                            <button type="button" onclick="document.getElementById('genMaterial').value = 'Penjumlahan & Pengurangan'" class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded hover:bg-indigo-100">Penjumlahan</button>
                            <button type="button" onclick="document.getElementById('genMaterial').value = 'Pecahan Senilai'" class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded hover:bg-indigo-100">Pecahan</button>
                            <button type="button" onclick="document.getElementById('genMaterial').value = 'Keliling & Luas Bangun Datar'" class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded hover:bg-indigo-100">Geometri</button>
                            <button type="button" onclick="document.getElementById('genMaterial').value = 'Operasi Hitung Perkalian'" class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded hover:bg-indigo-100">Perkalian</button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-700 uppercase mb-1.5">Tingkat Kognitif (Bloom)</label>
                        <select id="genCognitive" class="w-full bg-amber-50/50 border-2 border-amber-200 rounded-2xl px-4 py-2.5 font-bold text-sm text-slate-800 focus:outline-none focus:border-indigo-500 transition">
                            <option value="Remembering">Remembering (Mengingat Konsep / Rumus)</option>
                            <option value="Understanding">Understanding (Memahami & Menginterpretasikan)</option>
                            <option value="Applying" selected>Applying (Menerapkan ke Kasus Kehidupan)</option>
                            <option value="Analyzing">Analyzing (Menganalisis & Mengurai Pola)</option>
                            <option value="Evaluating">Evaluating (Menalar & Mengambil Kesimpulan)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-700 uppercase mb-1.5">Tipe Konteks</label>
                        <select id="genContext" class="w-full bg-amber-50/50 border-2 border-amber-200 rounded-2xl px-4 py-2.5 font-bold text-sm text-slate-800 focus:outline-none focus:border-indigo-500 transition">
                            <option value="Contextual" selected>Contextual (Cerita Sehari-hari Anak SD)</option>
                            <option value="Applicative/Authentic">Applicative / Authentic (Masalah Nyata)</option>
                            <option value="Non-contextual">Non-contextual (Simbolis / Matematis Murni)</option>
                        </select>
                    </div>

                    <button type="submit" id="btnGenSubmit"
                        class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-black py-3.5 px-6 rounded-2xl shadow-lg shadow-emerald-500/20 transition duration-200 flex items-center justify-center gap-2 transform active:scale-98">
                        <svg class="w-5 h-5 text-emerald-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span>Generate Soal Baru ✨</span>
                    </button>
                </form>
            </div>

            <!-- Right Generator Output -->
            <div class="lg:col-span-7 bg-white rounded-3xl p-6 sm:p-7 border-2 border-amber-200/80 shadow-sm flex flex-col min-h-[460px] justify-between">
                <div>
                    <div class="border-b border-amber-100 pb-4 flex items-center justify-between">
                        <h3 class="text-xl font-black text-slate-800 flex items-center gap-2">
                            <span>📝</span> Soal Hasil Sintesis AI
                        </h3>
                        <span id="genBadge" class="hidden text-xs font-black px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200 shadow-sm">
                            ✓ Berhasil Disintesis
                        </span>
                    </div>

                    <!-- Placeholder State -->
                    <div id="genPlaceholder" class="py-20 text-center space-y-3">
                        <div class="w-16 h-16 bg-emerald-100/80 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto text-3xl shadow-sm">
                            💡
                        </div>
                        <h4 class="text-base font-black text-slate-800">Belum Ada Soal Disintesis</h4>
                        <p class="text-xs font-semibold text-slate-500 max-w-xs mx-auto leading-relaxed">
                            Tentukan materi dan tingkat kognitif di sebelah kiri, lalu klik <strong>"Generate Soal Baru"</strong>.
                        </p>
                    </div>

                    <!-- Loading State -->
                    <div id="genLoading" class="py-20 text-center space-y-4 hidden">
                        <div class="relative w-14 h-14 mx-auto">
                            <div class="animate-spin rounded-full h-14 w-14 border-4 border-emerald-200 border-t-emerald-600"></div>
                            <div class="absolute inset-0 flex items-center justify-center text-lg">🤖</div>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm font-black text-emerald-700">Gemini LLM sedang menyusun soal...</p>
                            <p class="text-xs font-medium text-slate-400">Menyusun skenario kontekstual dan opsi pilihan ganda</p>
                        </div>
                    </div>

                    <!-- Output Content -->
                    <div id="genResultContent" class="space-y-4 pt-4 hidden">
                        <div class="bg-amber-50/70 border-2 border-amber-200 rounded-2xl p-5 shadow-inner">
                            <div class="text-[10px] font-black uppercase text-amber-800 tracking-wider mb-1.5 flex items-center gap-1.5">
                                <span>📖</span> Butir Pertanyaan:
                            </div>
                            <p id="genQuestionText" class="text-sm sm:text-base font-bold text-slate-900 leading-relaxed">-</p>
                        </div>

                        <div>
                            <div class="text-[11px] font-black uppercase text-slate-600 mb-2 flex items-center gap-1.5">
                                <span>🔘</span> Pilihan Jawaban:
                            </div>
                            <div id="genOptionsList" class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 text-xs font-bold text-slate-800">
                                <!-- Populated dynamically -->
                            </div>
                        </div>

                        <div class="bg-emerald-50/80 border border-emerald-200 rounded-2xl p-4 space-y-1.5">
                            <div class="text-[11px] font-black uppercase text-emerald-900 flex items-center gap-1.5">
                                <span>🎯</span> Kunci Jawaban & Pembahasan Pedagogis:
                            </div>
                            <div id="genCorrectAnswer" class="text-xs font-black text-emerald-950 bg-white/80 px-2.5 py-1 rounded-lg inline-block border border-emerald-200">-</div>
                            <p id="genExplanation" class="text-xs font-semibold text-emerald-800 leading-relaxed pt-1">-</p>
                        </div>
                    </div>
                </div>

                <!-- Footer Save Action -->
                <div id="genActionFooter" class="pt-5 border-t border-amber-100 mt-6 hidden">
                    <div class="flex flex-col sm:flex-row items-center gap-3">
                        <select id="targetTopicId" class="w-full sm:w-auto flex-1 bg-amber-50/70 border border-amber-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                            @foreach($topics as $t)
                                <option value="{{ $t->id }}">Simpan ke Topik: {{ $t->title }} (Kelas {{ $t->kelas_level }})</option>
                            @endforeach
                        </select>
                        <button type="button" onclick="saveGeneratedToBank()" id="btnSaveBank"
                            class="w-full sm:w-auto bg-[#10B981] hover:bg-[#059669] text-white text-xs font-black px-5 py-2.5 rounded-xl shadow-md transition transform active:scale-95 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                            <span>Simpan ke Bank Soal</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Scripts -->
@push('scripts')
<script>
    let currentGeneratedQuestion = null;

    // Support URL Hash navigation (e.g. #analyzer-tab)
    document.addEventListener('DOMContentLoaded', () => {
        const hash = window.location.hash.replace('#', '');
        if (hash && document.getElementById(hash + '-tab')) {
            switchTab(hash + '-tab');
        }
    });

    function switchTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-white', 'text-indigo-700', 'shadow-md', 'border', 'border-indigo-100');
            btn.classList.add('text-slate-600', 'hover:text-slate-900', 'hover:bg-white/60');
        });

        const activeContent = document.getElementById(tabId);
        if (activeContent) {
            activeContent.classList.remove('hidden');
        }

        const activeBtn = document.getElementById('btn-' + tabId);
        if (activeBtn) {
            activeBtn.classList.add('bg-white', 'text-indigo-700', 'shadow-md', 'border', 'border-indigo-100');
            activeBtn.classList.remove('text-slate-600', 'hover:text-slate-900', 'hover:bg-white/60');
        }

        // Update URL hash without jumping
        const cleanName = tabId.replace('-tab', '');
        history.replaceState(null, null, '#' + cleanName);
    }

    function filterDataset() {
        const gradeVal = document.getElementById('gradeFilter').value;
        const cogVal = document.getElementById('cogFilter').value.toLowerCase();
        const searchVal = document.getElementById('datasetSearch').value.toLowerCase().trim();

        const rows = document.querySelectorAll('.dataset-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const grade = row.getAttribute('data-grade');
            const cog = row.getAttribute('data-cog');
            const text = row.getAttribute('data-text');

            const matchGrade = (gradeVal === 'all' || grade === gradeVal);
            const matchCog = (cogVal === 'all' || cog === cogVal);
            const matchSearch = (!searchVal || text.includes(searchVal));

            if (matchGrade && matchCog && matchSearch) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        const counterEl = document.getElementById('filteredCountText');
        if (counterEl) {
            counterEl.innerText = `Menampilkan ${visibleCount} dari ${rows.length} butir soal`;
        }
    }

    function setAnalyzerExample(grade, text) {
        document.getElementById('analyzerGrade').value = grade;
        document.getElementById('analyzerQuestionText').value = text;
        document.getElementById('charCount').innerText = text.length + ' karakter';
    }

    function populateAnalyzer(questionText, grade) {
        switchTab('analyzer-tab');
        setAnalyzerExample(grade, questionText);
        handleAnalyze(new Event('submit'));
    }

    async function handleAnalyze(e) {
        if (e && e.preventDefault) e.preventDefault();

        const grade = document.getElementById('analyzerGrade').value;
        const text = document.getElementById('analyzerQuestionText').value.trim();

        if (!text) {
            Swal.fire('Perhatian', 'Silakan ketikkan teks soal terlebih dahulu.', 'warning');
            return;
        }

        const btnSubmit = document.getElementById('btnAnalyzeSubmit');
        btnSubmit.disabled = true;
        btnSubmit.classList.add('opacity-75', 'cursor-not-allowed');

        document.getElementById('analyzerPlaceholder').classList.add('hidden');
        document.getElementById('analyzerResultContent').classList.add('hidden');
        document.getElementById('analyzerBadgeStatus').classList.add('hidden');
        document.getElementById('analyzerLoading').classList.remove('hidden');

        try {
            const response = await fetch("{{ route('ai-studio.analyze') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ grade: grade, question_text: text })
            });

            document.getElementById('analyzerLoading').classList.add('hidden');
            btnSubmit.disabled = false;
            btnSubmit.classList.remove('opacity-75', 'cursor-not-allowed');

            if (!response.ok) {
                const errText = await response.text();
                console.error('[AI Analyzer] HTTP ' + response.status, errText);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal (HTTP ' + response.status + ')',
                    text: 'Koneksi ke AI terputus. Silakan coba lagi sebentar.',
                    confirmButtonColor: '#4F46E5'
                });
                document.getElementById('analyzerPlaceholder').classList.remove('hidden');
                return;
            }

            const data = await response.json();

            if (data.error) {
                Swal.fire('Info AI', data.error, 'warning');
                document.getElementById('analyzerPlaceholder').classList.remove('hidden');
                return;
            }

            document.getElementById('resCognitive').innerText = data.cognitive_level || '-';
            document.getElementById('resContext').innerText = data.question_context || '-';
            document.getElementById('resForm').innerText = data.question_form || '-';
            document.getElementById('resDifficulty').innerText = data.difficulty || 'Sedang';
            document.getElementById('resSummary').innerText = data.analysis_summary || '-';
            document.getElementById('resSuggestion').innerText = data.improvement_suggestion || '-';

            document.getElementById('analyzerBadgeStatus').classList.remove('hidden');
            document.getElementById('analyzerResultContent').classList.remove('hidden');

        } catch (err) {
            document.getElementById('analyzerLoading').classList.add('hidden');
            document.getElementById('analyzerPlaceholder').classList.remove('hidden');
            btnSubmit.disabled = false;
            btnSubmit.classList.remove('opacity-75', 'cursor-not-allowed');

            console.error('[AI Analyzer] Exception:', err);
            Swal.fire('Kesalahan', 'Gagal memproses analisis: ' + err.message, 'error');
        }
    }

    function copyAnalysisResult() {
        const cog = document.getElementById('resCognitive').innerText;
        const ctx = document.getElementById('resContext').innerText;
        const form = document.getElementById('resForm').innerText;
        const diff = document.getElementById('resDifficulty').innerText;
        const summary = document.getElementById('resSummary').innerText;
        const suggestion = document.getElementById('resSuggestion').innerText;

        const textToCopy = `[Hasil Analisis AI RoboMath]\n• Cognitive Level: ${cog}\n• Question Context: ${ctx}\n• Question Form: ${form}\n• Kesulitan: ${diff}\n\nUlasan:\n${summary}\n\nSaran Pedagogis:\n${suggestion}`;

        navigator.clipboard.writeText(textToCopy).then(() => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Hasil analisis disalin ke clipboard!',
                showConfirmButton: false,
                timer: 2000
            });
        });
    }

    async function handleGenerate(e) {
        if (e && e.preventDefault) e.preventDefault();

        const grade = document.getElementById('genGrade').value;
        const material = document.getElementById('genMaterial').value.trim();
        const cognitive = document.getElementById('genCognitive').value;
        const context = document.getElementById('genContext').value;

        if (!material) {
            Swal.fire('Perhatian', 'Silakan masukkan nama topik/materi terlebih dahulu.', 'warning');
            return;
        }

        const btnGen = document.getElementById('btnGenSubmit');
        btnGen.disabled = true;
        btnGen.classList.add('opacity-75', 'cursor-not-allowed');

        document.getElementById('genPlaceholder').classList.add('hidden');
        document.getElementById('genResultContent').classList.add('hidden');
        document.getElementById('genActionFooter').classList.add('hidden');
        document.getElementById('genBadge').classList.add('hidden');
        document.getElementById('genLoading').classList.remove('hidden');

        try {
            const response = await fetch("{{ route('ai-studio.generate') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    grade: grade,
                    material: material,
                    cognitive_level: cognitive,
                    context_type: context
                })
            });

            document.getElementById('genLoading').classList.add('hidden');
            btnGen.disabled = false;
            btnGen.classList.remove('opacity-75', 'cursor-not-allowed');

            if (!response.ok) {
                const errText = await response.text();
                console.error('[AI Generator] HTTP ' + response.status, errText);
                Swal.fire('Gagal (HTTP ' + response.status + ')', 'Layanan AI sedang sibuk. Silakan coba kembali.', 'error');
                document.getElementById('genPlaceholder').classList.remove('hidden');
                return;
            }

            const data = await response.json();

            if (data.error) {
                Swal.fire('Info AI', data.error, 'warning');
                document.getElementById('genPlaceholder').classList.remove('hidden');
                return;
            }

            currentGeneratedQuestion = data;

            document.getElementById('genQuestionText').innerText = data.question_text || '-';
            document.getElementById('genCorrectAnswer').innerText = 'Kunci: ' + (data.correct_answer || '-');
            document.getElementById('genExplanation').innerText = data.explanation || '-';

            const optsContainer = document.getElementById('genOptionsList');
            optsContainer.innerHTML = '';
            if (data.options && Array.isArray(data.options)) {
                data.options.forEach((opt, idx) => {
                    const isCorrect = (data.correct_answer && String(opt).trim().toLowerCase() === String(data.correct_answer).trim().toLowerCase());
                    const div = document.createElement('div');
                    div.className = `p-3 rounded-2xl border transition flex items-center gap-2.5 ${isCorrect ? 'bg-emerald-50/80 border-emerald-300 ring-1 ring-emerald-300' : 'bg-slate-50/80 border-slate-200'}`;
                    div.innerHTML = `
                        <span class="w-7 h-7 rounded-xl ${isCorrect ? 'bg-emerald-600 text-white' : 'bg-white border border-slate-300 text-slate-700'} flex items-center justify-center text-xs font-black shadow-sm flex-shrink-0">
                            ${String.fromCharCode(65 + idx)}
                        </span>
                        <span class="text-xs font-bold ${isCorrect ? 'text-emerald-950 font-black' : 'text-slate-800'}">${opt}</span>
                        ${isCorrect ? '<span class="ml-auto text-emerald-600 text-xs font-black">✓ Benar</span>' : ''}
                    `;
                    optsContainer.appendChild(div);
                });
            }

            document.getElementById('genBadge').classList.remove('hidden');
            document.getElementById('genResultContent').classList.remove('hidden');
            document.getElementById('genActionFooter').classList.remove('hidden');

        } catch (err) {
            document.getElementById('genLoading').classList.add('hidden');
            document.getElementById('genPlaceholder').classList.remove('hidden');
            btnGen.disabled = false;
            btnGen.classList.remove('opacity-75', 'cursor-not-allowed');

            Swal.fire('Kesalahan', 'Gagal menghasilkan soal AI: ' + err.message, 'error');
        }
    }

    async function saveGeneratedToBank() {
        if (!currentGeneratedQuestion) return;

        const topicId = document.getElementById('targetTopicId').value;
        if (!topicId) {
            Swal.fire('Peringatan', 'Silakan pilih topik terlebih dahulu.', 'warning');
            return;
        }

        const btnSave = document.getElementById('btnSaveBank');
        btnSave.disabled = true;
        btnSave.classList.add('opacity-75');

        try {
            const response = await fetch("{{ route('ai-studio.save') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    topic_id: topicId,
                    question: currentGeneratedQuestion.question_text,
                    options: currentGeneratedQuestion.options || [],
                    correct_answer: currentGeneratedQuestion.correct_answer,
                    explanation: currentGeneratedQuestion.explanation,
                    difficulty: 'sedang'
                })
            });

            btnSave.disabled = false;
            btnSave.classList.remove('opacity-75');

            const res = await response.json();
            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil Disimpan!',
                    text: res.message || 'Butir soal telah ditambahkan ke Bank Soal.',
                    confirmButtonColor: '#10B981'
                });
            } else {
                Swal.fire('Gagal', res.message || 'Gagal menyimpan soal.', 'error');
            }
        } catch (err) {
            btnSave.disabled = false;
            btnSave.classList.remove('opacity-75');
            Swal.fire('Kesalahan', 'Terjadi kesalahan sistem saat menyimpan soal.', 'error');
        }
    }
</script>
@endpush
@endsection
