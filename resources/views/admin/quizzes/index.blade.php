@extends('layouts.admin')

@section('title', 'Manajemen Bank Soal - RoboMath')

@section('content')
<div x-data="aiQuizManager()" class="space-y-6">

    <!-- Header Actions & Filter -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Filters -->
        <form action="{{ route('admin.quizzes.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <select name="topic_id" onchange="this.form.submit()" class="bg-white border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-2.5 text-xs font-bold focus:border-amber-400 focus:outline-none shadow-sm">
                <option value="">Semua Topik</option>
                @foreach($topics as $topic)
                    <option value="{{ $topic->id }}" {{ $topicFilter == $topic->id ? 'selected' : '' }}>
                        Kelas {{ $topic->kelas_level }} - {{ $topic->title }}
                    </option>
                @endforeach
            </select>

            <select name="type" onchange="this.form.submit()" class="bg-white border-2 border-amber-200 text-slate-800 rounded-2xl px-4 py-2.5 text-xs font-bold focus:border-amber-400 focus:outline-none shadow-sm">
                <option value="">Semua Tipe Soal</option>
                <option value="essay" {{ $typeFilter === 'essay' ? 'selected' : '' }}>✍️ Essay / Uraian</option>
                <option value="pilihan_ganda" {{ $typeFilter === 'pilihan_ganda' ? 'selected' : '' }}>🔘 Pilihan Ganda</option>
                <option value="drag_drop" {{ $typeFilter === 'drag_drop' ? 'selected' : '' }}>🔄 Drag & Drop</option>
                <option value="cerita_logika" {{ $typeFilter === 'cerita_logika' ? 'selected' : '' }}>📖 Cerita Logika</option>
            </select>

            @if($topicFilter || $typeFilter)
                <a href="{{ route('admin.quizzes.index') }}" class="text-xs text-rose-500 hover:underline font-bold">Reset Filter</a>
            @endif
        </form>

        <!-- Action Buttons -->
        <div class="flex items-center gap-3 w-full md:w-auto">
            <button type="button" 
                    @click="openModal = true" 
                    class="flex-1 md:flex-none px-5 py-2.5 bg-gradient-to-r from-purple-600 via-indigo-600 to-indigo-700 hover:from-purple-700 hover:to-indigo-800 text-white rounded-2xl text-xs font-black flex items-center justify-center gap-2 shadow-md transition transform active:scale-95">
                <span>✨ Generate Soal AI</span>
            </button>
            
            <a href="{{ route('admin.quizzes.create') }}" class="flex-1 md:flex-none px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white rounded-2xl text-xs font-black flex items-center justify-center gap-2 shadow-md transition transform active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Tambah Manual
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white border-2 border-amber-200/80 rounded-3xl overflow-hidden shadow-sm">
        <div class="p-6 border-b-2 border-amber-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <h3 class="font-black text-slate-800 text-base">Daftar Pertanyaan Latihan</h3>
            </div>
            <span class="text-xs text-slate-500 font-extrabold bg-amber-50 border border-amber-200 px-3 py-1 rounded-full">{{ $quizzes->total() }} Total Soal</span>
        </div>

        @if($quizzes->isEmpty())
        <div class="p-12 text-center text-slate-400 font-semibold text-sm">
            Tidak ada soal yang sesuai dengan filter pencarian.
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-amber-50/50 text-slate-500 uppercase font-black tracking-wider border-b-2 border-amber-100">
                    <tr>
                        <th class="px-6 py-4">Soal</th>
                        <th class="px-6 py-4">Topik</th>
                        <th class="px-6 py-4">Tipe</th>
                        <th class="px-6 py-4">Kunci Jawaban</th>
                        <th class="px-6 py-4">Kesulitan</th>
                        <th class="px-6 py-4">Poin</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber-100 text-slate-700">
                    @foreach($quizzes as $quiz)
                    <tr class="hover:bg-amber-50/40 transition">
                        <td class="px-6 py-4 font-bold text-slate-900 max-w-xs truncate">
                            {!! strip_tags($quiz->question) !!}
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-slate-800 font-extrabold">{{ $quiz->topic->title ?? '-' }}</span>
                            <span class="block text-[10px] text-slate-500">Kelas {{ $quiz->topic->kelas_level ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if(in_array($quiz->type, ['essay', 'uraian']))
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase bg-purple-100 text-purple-900 border border-purple-200 flex items-center gap-1 w-max">
                                    <span>✍️</span> Essay / Uraian
                                </span>
                            @elseif($quiz->type === 'pilihan_ganda')
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase bg-blue-100 text-blue-800">
                                    Pilihan Ganda
                                </span>
                            @elseif($quiz->type === 'drag_drop')
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase bg-amber-100 text-amber-800">
                                    Drag & Drop
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase bg-emerald-100 text-emerald-800">
                                    {{ str_replace('_', ' ', $quiz->type) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-mono font-bold text-indigo-700 bg-indigo-50/50 px-2 rounded max-w-xs truncate">
                            {{ $quiz->correct_answer }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase {{ $quiz->difficulty === 'mudah' ? 'bg-emerald-100 text-emerald-800' : ($quiz->difficulty === 'sedang' ? 'bg-amber-100 text-amber-900' : 'bg-rose-100 text-rose-800') }}">
                                {{ $quiz->difficulty }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-black text-amber-700">
                            {{ $quiz->points }} Poin
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="inline-block px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-900 rounded-xl font-black text-xs transition border border-amber-200">
                                Edit
                            </a>
                            <form action="{{ route('admin.quizzes.destroy', $quiz) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus soal ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl font-black text-xs transition border border-red-200">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-5 border-t-2 border-amber-100">
            {{ $quizzes->links() }}
        </div>
        @endif
    </div>

    <!-- Modal Generate Soal AI -->
    <div x-show="openModal" 
         x-transition.opacity 
         style="display: none;" 
         class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
        <div @click.away="if (!isLoading) openModal = false" 
             class="bg-white rounded-3xl border-4 border-indigo-200 shadow-2xl max-w-2xl w-full p-6 sm:p-8 space-y-6 my-8">
            
            <div class="flex items-center justify-between border-b-2 border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 border-2 border-indigo-200 flex items-center justify-center text-xl">
                        🤖
                    </div>
                    <div>
                        <h3 class="font-black text-lg text-slate-800">Generate Soal Otomatis dengan AI</h3>
                        <p class="text-xs text-slate-500 font-semibold">Buat butir soal essay uraian atau pilihan ganda dengan kurikulum SD RoboMath.</p>
                    </div>
                </div>
                <button type="button" @click="openModal = false" :disabled="isLoading" class="text-slate-400 hover:text-slate-700 font-black text-xl">✕</button>
            </div>

            <!-- Form Generate -->
            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1.5">Topik Soal <span class="text-rose-500">*</span></label>
                        <select x-model="genTopicId" class="w-full bg-slate-50 border-2 border-slate-200 rounded-2xl p-3 text-xs font-bold text-slate-800 focus:border-indigo-400 focus:outline-none">
                            <option value="">-- Pilih Topik --</option>
                            @foreach($topics as $t)
                                <option value="{{ $t->id }}">Kelas {{ $t->kelas_level }} - {{ $t->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1.5">Bentuk / Tipe Soal <span class="text-rose-500">*</span></label>
                        <select x-model="genType" class="w-full bg-slate-50 border-2 border-slate-200 rounded-2xl p-3 text-xs font-bold text-slate-800 focus:border-indigo-400 focus:outline-none">
                            <option value="essay">✍️ Soal Essay / Uraian (Rekomendasi)</option>
                            <option value="pilihan_ganda">🔘 Pilihan Ganda (A, B, C, D)</option>
                            <option value="campuran">✨ Campuran (Essay & Pilihan Ganda)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1.5">Jumlah Soal</label>
                        <select x-model="genCount" class="w-full bg-slate-50 border-2 border-slate-200 rounded-2xl p-3 text-xs font-bold text-slate-800 focus:border-indigo-400 focus:outline-none">
                            <option value="2">2 Butir Soal</option>
                            <option value="3">3 Butir Soal (Standar)</option>
                            <option value="5">5 Butir Soal (Lengkap)</option>
                            <option value="8">8 Butir Soal</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1.5">Tingkat Kesulitan</label>
                        <select x-model="genDifficulty" class="w-full bg-slate-50 border-2 border-slate-200 rounded-2xl p-3 text-xs font-bold text-slate-800 focus:border-indigo-400 focus:outline-none">
                            <option value="mudah">🟢 Mudah (Pengenalan)</option>
                            <option value="sedang">🟡 Sedang (Aplikasi)</option>
                            <option value="sulit">🔴 Sulit (Penalaran / Tantangan)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1.5">Instruksi / Catatan Khusus (Opsional)</label>
                    <textarea x-model="genPrompt" rows="2" placeholder="Contoh: Fokuskan pada soal cerita tentang pembagian kue atau belanja di toko buku..." class="w-full bg-slate-50 border-2 border-slate-200 rounded-2xl p-3 text-xs font-bold text-slate-800 focus:border-indigo-400 focus:outline-none"></textarea>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" id="auto_save" x-model="genAutoSave" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                    <label for="auto_save" class="text-xs font-bold text-slate-700 cursor-pointer">Langsung simpan soal hasil generate ke bank soal siswa</label>
                </div>
            </div>

            <!-- Preview Results Area (Jika tidak auto_save) -->
            <template x-if="generatedQuestions.length > 0">
                <div class="space-y-3 pt-3 border-t-2 border-slate-100 max-h-60 overflow-y-auto pr-1">
                    <p class="text-xs font-black text-slate-700 flex items-center justify-between">
                        <span>Pratinjau Soal yang Dihasilkan (<span x-text="generatedQuestions.length"></span> butir):</span>
                    </p>
                    <template x-for="(q, idx) in generatedQuestions" :key="idx">
                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 space-y-1.5 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="font-black text-indigo-700" x-text="'Soal #' + (idx + 1) + ' (' + (q.type === 'essay' ? 'Essay' : 'Pilihan Ganda') + ')'"></span>
                                <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-full bg-amber-100 text-amber-800" x-text="q.difficulty"></span>
                            </div>
                            <p class="font-bold text-slate-800" x-text="q.question"></p>
                            <p class="text-slate-600 font-semibold">
                                <span class="font-black text-emerald-700">Kunci:</span> <span x-text="q.correct_answer"></span>
                            </p>
                        </div>
                    </template>
                </div>
            </template>

            <!-- Buttons Action -->
            <div class="flex items-center justify-end gap-3 pt-3 border-t-2 border-slate-100">
                <button type="button" 
                        @click="openModal = false" 
                        :disabled="isLoading" 
                        class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl text-xs font-black transition">
                    Tutup
                </button>

                <template x-if="generatedQuestions.length > 0 && !genAutoSave">
                    <button type="button" 
                            @click="saveBatch()" 
                            :disabled="isLoading" 
                            class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs rounded-2xl shadow-md transition">
                        💾 Simpan Semua Soal ke Bank Soal
                    </button>
                </template>

                <button type="button" 
                        @click="generateQuestions()" 
                        :disabled="isLoading || !genTopicId" 
                        class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 disabled:opacity-50 text-white font-black text-xs rounded-2xl shadow-md transition flex items-center gap-2">
                    <span x-show="!isLoading">🚀 Mulai Generate Soal</span>
                    <span x-show="isLoading" style="display: none;">🤖 Sedang Menganalisis...</span>
                </button>
            </div>

        </div>
    </div>

</div>

<script>
    function aiQuizManager() {
        return {
            openModal: false,
            isLoading: false,
            genTopicId: '',
            genType: 'essay',
            genCount: 3,
            genDifficulty: 'sedang',
            genPrompt: '',
            genAutoSave: true,
            generatedQuestions: [],

            async generateQuestions() {
                if (!this.genTopicId) {
                    Swal.fire('Perhatian', 'Silakan pilih topik terlebih dahulu.', 'warning');
                    return;
                }

                this.isLoading = true;
                this.generatedQuestions = [];

                try {
                    const res = await fetch("{{ route('admin.quizzes.generate-ai') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            topic_id: this.genTopicId,
                            type: this.genType,
                            count: parseInt(this.genCount),
                            difficulty: this.genDifficulty,
                            prompt: this.genPrompt,
                            auto_save: this.genAutoSave
                        })
                    });

                    const data = await res.json();

                    if (data.success) {
                        this.generatedQuestions = data.questions || [];
                        if (this.genAutoSave) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil! 🎉',
                                text: data.message,
                                confirmButtonColor: '#4f46e5'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'info',
                                title: 'Pratinjau Siap! ✨',
                                text: data.message,
                                confirmButtonColor: '#4f46e5'
                            });
                        }
                    } else {
                        Swal.fire('Gagal', data.message || 'Terjadi kesalahan saat membuat soal.', 'error');
                    }
                } catch (e) {
                    console.error(e);
                    Swal.fire('Error', 'Gagal menghubungi server.', 'error');
                } finally {
                    this.isLoading = false;
                }
            },

            async saveBatch() {
                if (this.generatedQuestions.length === 0) return;

                this.isLoading = true;
                try {
                    const res = await fetch("{{ route('admin.quizzes.save-batch') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            topic_id: this.genTopicId,
                            questions: this.generatedQuestions
                        })
                    });

                    const data = await res.json();
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Tersimpan! 🎉',
                            text: data.message,
                            confirmButtonColor: '#4f46e5'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Gagal', data.message || 'Gagal menyimpan butir soal.', 'error');
                    }
                } catch (e) {
                    console.error(e);
                    Swal.fire('Error', 'Gagal menyimpan soal.', 'error');
                } finally {
                    this.isLoading = false;
                }
            }
        };
    }
</script>
@endsection

