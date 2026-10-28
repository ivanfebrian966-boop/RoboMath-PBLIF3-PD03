@extends('layouts.app')

@section('title', 'Latihan Soal: ' . $topic->title . ' - RoboMath')

@section('content')
<div x-data="quizPlayer()" class="max-w-4xl mx-auto space-y-6 font-poppins pb-12">

    <!-- Top Navigation & Topic Header (Sesuai Mockup) -->
    <div class="bg-white p-4 sm:p-5 rounded-[26px] sm:rounded-[30px] border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 font-poppins">
        <div class="flex items-center gap-3.5">
            {{-- Circular Back Button --}}
            <a href="{{ route('siswa.quiz') }}" 
               class="w-10 h-10 rounded-full border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 flex items-center justify-center font-bold text-sm shadow-2xs transition flex-shrink-0" 
               title="Kembali ke Daftar Soal">
                ←
            </a>
            
            {{-- Topic Icon Squircle --}}
            <div class="w-12 h-12 rounded-[18px] bg-[#FFE4E6] text-[#EF4444] flex items-center justify-center text-xl flex-shrink-0 shadow-2xs">
                @if(strtolower($topic->category ?? '') === 'urutan' || str_contains(strtolower($topic->title), 'urutan'))
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <line x1="8" y1="6" x2="21" y2="6"></line>
                        <line x1="8" y1="12" x2="21" y2="12"></line>
                        <line x1="8" y1="18" x2="21" y2="18"></line>
                        <circle cx="4" cy="6" r="1" fill="currentColor"></circle>
                        <circle cx="4" cy="12" r="1" fill="currentColor"></circle>
                        <circle cx="4" cy="18" r="1" fill="currentColor"></circle>
                    </svg>
                @else
                    {{ $topic->icon ?: '🧩' }}
                @endif
            </div>

            <div class="min-w-0">
                <h2 class="font-extrabold text-lg sm:text-xl text-slate-900 leading-tight truncate font-poppins">{{ $topic->title }}</h2>
                <p class="text-xs font-bold text-slate-400 mt-0.5 font-poppins">
                    Soal <span x-text="currentIndex + 1" class="text-slate-700 font-extrabold"></span> dari <span x-text="quizzes.length"></span> · <span x-text="isFinished ? 'selesai' : (isAnswered ? 'terjawab' : 'berlangsung')"></span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2.5 justify-between sm:justify-end">
            <a href="{{ route('siswa.quiz') }}" 
               class="text-xs font-bold text-slate-800 bg-white hover:bg-slate-50 border border-slate-200 px-4 py-2 rounded-full transition shadow-2xs font-poppins">
                Lihat semua soal
            </a>
            <div class="bg-[#FEF9C3] text-[#D97706] font-extrabold px-4 py-2 rounded-full text-xs font-poppins">
                +<span x-text="totalSessionPoints">0</span> poin sesi ini
            </div>
        </div>
    </div>

    <!-- Question Numbers Selector / Navigation Bar -->
    <div class="bg-white p-4 rounded-[22px] border border-slate-200/80 shadow-xs space-y-2" x-show="!isFinished">
        <div class="flex items-center justify-between text-xs font-bold text-slate-500 px-1 font-poppins">
            <span>Pilih Nomor Soal:</span>
            <span class="text-slate-400 text-[11px]">Klik nomor untuk berpindah soal langsung</span>
        </div>
        <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-thin">
            <template x-for="(quiz, index) in quizzes" :key="quiz.id">
                <button type="button" 
                        @click="goToQuestion(index)" 
                        :class="{
                            'bg-[#0F172A] text-white font-black shadow-md scale-105 border-[#0F172A]': currentIndex === index,
                            'bg-emerald-50 text-emerald-800 border-emerald-300 font-bold hover:bg-emerald-100': currentIndex !== index && getQuizStatus(quiz.id) === 'correct',
                            'bg-rose-50 text-rose-800 border-rose-300 font-bold hover:bg-rose-100': currentIndex !== index && getQuizStatus(quiz.id) === 'wrong',
                            'bg-slate-50 text-slate-700 border-slate-200 font-bold hover:bg-slate-100': currentIndex !== index && getQuizStatus(quiz.id) === 'unattempted'
                        }"
                        class="min-w-9 h-9 sm:min-w-10 sm:h-10 px-3 rounded-2xl border text-xs transition-all flex items-center justify-center gap-1 flex-shrink-0 font-poppins">
                    <span x-text="index + 1"></span>
                    <template x-if="getQuizStatus(quiz.id) === 'correct'">
                        <span class="text-[10px]">✓</span>
                    </template>
                </button>
            </template>
        </div>
    </div>

    <!-- Active Question Card (Saat Quiz Berlangsung) -->
    <div class="bg-white rounded-[28px] sm:rounded-[32px] p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6 font-poppins" x-show="!isFinished">
        
        <div class="space-y-3">
            <div class="flex items-center justify-between gap-2 flex-wrap">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold uppercase px-3 py-1 rounded-full bg-blue-50 text-blue-700" x-text="currentQuiz.type_label || 'Pilihan Ganda'"></span>
                    <span class="text-xs font-bold uppercase px-3 py-1 rounded-full"
                          :class="{
                              'bg-emerald-50 text-emerald-700': currentQuiz.difficulty === 'mudah',
                              'bg-amber-50 text-amber-700': currentQuiz.difficulty === 'sedang',
                              'bg-rose-50 text-rose-700': currentQuiz.difficulty === 'sulit'
                          }"
                          x-text="currentQuiz.difficulty ? (currentQuiz.difficulty.charAt(0).toUpperCase() + currentQuiz.difficulty.slice(1)) : 'Mudah'">
                    </span>
                    <template x-if="getQuizStatus(currentQuiz.id) === 'correct'">
                        <span class="text-xs font-bold px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-300">
                            ✓ Sudah Benar
                        </span>
                    </template>
                </div>
                <span class="text-xs font-extrabold text-[#D97706] bg-[#FEF9C3] px-3.5 py-1 rounded-full font-poppins">
                    +<span x-text="currentQuiz.points"></span> Poin
                </span>
            </div>
            
            <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900 leading-snug pt-1 font-poppins" x-text="currentQuiz.question"></h3>
        </div>

        <!-- Options List (Untuk Pilihan Ganda) -->
        <template x-if="!isEssayQuiz(currentQuiz)">
            <div class="space-y-3 pt-2">
                <template x-for="(optionText, key) in (currentQuiz.options || {})" :key="key">
                    <button @click="selectAnswer(key)" 
                            :disabled="isAnswered"
                            :class="{
                                'border-[#0F172A] bg-slate-50 text-slate-900': selectedKey === key && !isAnswered,
                                'border-emerald-500 bg-emerald-50/70 text-emerald-900 font-extrabold shadow-xs': isAnswered && key === currentQuiz.correct_answer,
                                'border-rose-400 bg-rose-50/70 text-rose-900': isAnswered && selectedKey === key && key !== currentQuiz.correct_answer,
                                'border-slate-200/90 bg-white text-slate-800 hover:border-slate-300': !isAnswered && selectedKey !== key,
                                'opacity-50 border-slate-200': isAnswered && key !== currentQuiz.correct_answer && selectedKey !== key
                            }"
                            class="w-full p-4 rounded-[20px] border-2 text-left font-bold transition-all flex items-start gap-3.5 font-poppins">
                        <span class="w-8 h-8 rounded-xl flex items-center justify-center font-extrabold text-sm flex-shrink-0"
                              :class="{
                                  'bg-emerald-500 text-white': isAnswered && key === currentQuiz.correct_answer,
                                  'bg-rose-500 text-white': isAnswered && selectedKey === key && key !== currentQuiz.correct_answer,
                                  'bg-slate-100 text-slate-700': (!isAnswered && selectedKey !== key) || (isAnswered && key !== currentQuiz.correct_answer && selectedKey !== key),
                                  'bg-[#0F172A] text-white': selectedKey === key && !isAnswered
                              }"
                              x-text="key">
                        </span>
                        <span class="text-sm sm:text-base pt-1 font-semibold flex-1 leading-relaxed" x-text="optionText"></span>
                        <template x-if="isAnswered && key === currentQuiz.correct_answer">
                            <span class="text-emerald-600 font-extrabold text-base pt-1">✓</span>
                        </template>
                        <template x-if="isAnswered && selectedKey === key && key !== currentQuiz.correct_answer">
                            <span class="text-rose-500 font-extrabold text-base pt-1">✕</span>
                        </template>
                    </button>
                </template>
            </div>
        </template>

        <!-- Essay Input (Untuk Soal Essay / AI) -->
        <template x-if="isEssayQuiz(currentQuiz)">
            <div class="space-y-4 pt-2">
                <div>
                    <label class="block text-xs font-black uppercase text-slate-400 mb-2 font-poppins">Tuliskan Langkah/Jawabanmu:</label>
                    <textarea x-model="essayInput" 
                              :disabled="isAnswered"
                              rows="4"
                              placeholder="Tuliskan jawaban essay kamu di sini beserta langkah hitunganmu..."
                              class="w-full p-4 rounded-[20px] border-2 border-slate-200 focus:outline-none focus:border-[#2563EB] font-medium text-slate-800 bg-slate-50/50 transition-all font-poppins"></textarea>
                </div>

                <div class="flex items-center justify-between" x-show="!isAnswered">
                    <span class="text-xs text-slate-400 font-medium font-poppins">🤖 Jawaban akan dievaluasi oleh Guru AI</span>
                    <button @click="submitEssayAnswer()" 
                            :disabled="isSubmitting || !essayInput.trim()"
                            class="px-6 py-2.5 rounded-full bg-[#0F172A] hover:bg-slate-800 text-white font-extrabold text-xs shadow-md transition disabled:opacity-50 disabled:cursor-not-allowed font-poppins">
                        <span x-show="!isSubmitting">Kirim Jawaban Essay</span>
                        <span x-show="isSubmitting">Memeriksa Jawaban...</span>
                    </button>
                </div>
            </div>
        </template>

        <!-- Question Navigation Controls -->
        <div class="flex items-center justify-between pt-4 border-t border-slate-100 font-poppins">
            <div>
                <button x-show="currentIndex > 0" 
                        @click="prevQuestion()" 
                        class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold px-5 py-2.5 rounded-full text-xs transition-all flex items-center gap-2 font-poppins">
                    <span>← Soal Sebelumnya</span>
                </button>
            </div>

            <div class="flex items-center gap-2">
                <button x-show="isAnswered && currentIndex < quizzes.length - 1" 
                        @click="nextQuestion()" 
                        class="bg-[#0F172A] hover:bg-slate-800 text-white font-extrabold px-6 py-2.5 rounded-full shadow-sm transition-all text-xs flex items-center gap-2 font-poppins">
                    <span>Soal Selanjutnya →</span>
                </button>

                <button x-show="isAnswered && currentIndex === quizzes.length - 1" 
                        @click="finishQuiz()" 
                        class="bg-[#10B981] hover:bg-[#059669] text-white font-extrabold px-6 py-2.5 rounded-full shadow-sm transition-all text-xs flex items-center gap-2 font-poppins">
                    <span>Selesai & Lihat Hasil ✓</span>
                </button>
            </div>
        </div>

    </div>

    <!-- Finished Screen / Popup Sesuai Gambar 2 (Kuis Selesai!) -->
    <div class="bg-white rounded-[32px] sm:rounded-[36px] p-8 sm:p-14 border border-slate-200/80 shadow-xs text-center space-y-7 font-poppins" 
         x-show="isFinished" 
         style="display: none;">
        
        {{-- Large Mint Squircle with Checkmark Icon --}}
        <div class="w-18 h-18 sm:w-20 sm:h-20 rounded-[24px] bg-[#D1FAE5] flex items-center justify-center mx-auto text-[#059669] shadow-xs">
            <svg class="w-9 h-9 text-[#059669]" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M5 13l4 4L19 7" />
            </svg>
        </div>

        {{-- Heading & Subtitle --}}
        <div class="space-y-2">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight font-poppins">
                Kuis selesai!
            </h2>
            <p class="text-sm sm:text-base text-slate-500 font-medium max-w-xl mx-auto leading-relaxed font-poppins">
                Kamu sudah menyelesaikan latihan logika topik {{ $topic->title }}. Lihat pembahasan untuk tahu bagian mana yang masih bisa diperbaiki.
            </p>
        </div>

        {{-- 3 KPI Stat Boxes Sesuai Mockup --}}
        <div class="grid grid-cols-3 gap-3 sm:gap-4 max-w-lg mx-auto pt-2 font-poppins">
            <!-- BENAR -->
            <div class="bg-slate-50/80 border border-slate-100/90 rounded-[22px] p-4 text-center">
                <span class="text-[10px] sm:text-[11px] font-black uppercase text-slate-400 tracking-wider block font-poppins">BENAR</span>
                <div class="text-2xl sm:text-3xl font-extrabold mt-1 font-poppins">
                    <span class="text-[#10B981]" x-text="correctCount">1</span>
                    <span class="text-slate-400 font-bold text-lg sm:text-xl">/ <span x-text="quizzes.length">2</span></span>
                </div>
            </div>

            <!-- NILAI -->
            <div class="bg-slate-50/80 border border-slate-100/90 rounded-[22px] p-4 text-center">
                <span class="text-[10px] sm:text-[11px] font-black uppercase text-slate-400 tracking-wider block font-poppins">NILAI</span>
                <div class="text-2xl sm:text-3xl font-extrabold text-[#2563EB] mt-1 font-poppins">
                    <span x-text="scorePercentage">50</span>%
                </div>
            </div>

            <!-- POIN SESI INI -->
            <div class="bg-[#FEFCE8] border border-amber-100 rounded-[22px] p-4 text-center">
                <span class="text-[10px] sm:text-[11px] font-black uppercase text-amber-700 tracking-wider block font-poppins">POIN SESI INI</span>
                <div class="text-2xl sm:text-3xl font-extrabold text-[#D97706] mt-1 font-poppins">
                    +<span x-text="totalSessionPoints">10</span>
                </div>
            </div>
        </div>

        {{-- 3 Action Buttons Sesuai Mockup --}}
        <div class="flex flex-wrap items-center justify-center gap-3 pt-3 font-poppins">
            <a href="{{ route('siswa.quiz.result', $topic) }}"
               class="px-7 py-3.5 rounded-full bg-[#FF9500] hover:bg-[#EA580C] text-white font-extrabold text-sm shadow-[0_4px_14px_rgba(255,149,0,0.35)] transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] font-poppins">
                Lihat nilai & pembahasan
            </a>
            <a href="{{ route('siswa.quiz') }}"
               class="px-6 py-3.5 rounded-full bg-white hover:bg-slate-50 text-slate-800 font-extrabold text-sm border border-slate-200 shadow-2xs transition-all font-poppins">
                Pilih soal lain
            </a>
            <a href="{{ route('siswa.dashboard') }}"
               class="px-7 py-3.5 rounded-full bg-[#0F172A] hover:bg-slate-800 text-white font-extrabold text-sm shadow-md transition-all font-poppins">
                Ke dashboard
            </a>
        </div>

    </div>

</div>

<script>
    function quizPlayer() {
        return {
            quizzes: @json($quizzes),
            quizStatuses: @json($quizStatuses),
            sessionId: @json($session->id),
            currentIndex: {{ $startIndex ?? 0 }},
            questionStartedAt: Date.now(),
            selectedKey: null,
            essayInput: '',
            isAnswered: false,
            isSubmitting: false,
            isFinished: false,
            totalSessionPoints: 0,
            submittedAnswers: {},
            aiFeedbacks: {},

            init() {
                // Populate previous answers and feedbacks if already attempted
                for (const qId in this.quizStatuses) {
                    const st = this.quizStatuses[qId];
                    if (st.last_answer) {
                        this.submittedAnswers[qId] = st.last_answer;
                    }
                    if (st.ai_feedback) {
                        this.aiFeedbacks[qId] = st.ai_feedback;
                    }
                }
                this.syncCurrentState();
            },
            
            get currentQuiz() {
                return this.quizzes[this.currentIndex] || {};
            },

            get correctCount() {
                let count = 0;
                for (const qId in this.quizStatuses) {
                    if (this.quizStatuses[qId] && this.quizStatuses[qId].is_correct) {
                        count++;
                    }
                }
                return count;
            },

            get scorePercentage() {
                if (!this.quizzes || this.quizzes.length === 0) return 0;
                return Math.round((this.correctCount / this.quizzes.length) * 100);
            },

            isEssayQuiz(quiz) {
                if (!quiz) return false;
                if (quiz.type === 'essay' || quiz.type === 'uraian') return true;
                if (!quiz.options || (typeof quiz.options === 'object' && Object.keys(quiz.options).length === 0)) {
                    return true;
                }
                return false;
            },

            syncCurrentState() {
                const q = this.currentQuiz;
                if (!q || !q.id) return;
                this.questionStartedAt = Date.now();

                if (this.submittedAnswers[q.id]) {
                    this.essayInput = this.submittedAnswers[q.id];
                    this.selectedKey = this.submittedAnswers[q.id];
                    this.isAnswered = true;
                } else {
                    this.essayInput = '';
                    this.selectedKey = null;
                    this.isAnswered = false;
                }
            },

            getQuizStatus(quizId) {
                const s = this.quizStatuses[quizId];
                if (!s) return 'unattempted';
                if (s.is_correct) return 'correct';
                if (s.attempts_count > 0) return 'wrong';
                return 'unattempted';
            },

            goToQuestion(idx) {
                if (idx >= 0 && idx < this.quizzes.length) {
                    this.currentIndex = idx;
                    this.isFinished = false;
                    this.syncCurrentState();
                }
            },

            async selectAnswer(key) {
                if (this.isAnswered || this.isSubmitting) return;
                this.selectedKey = key;
                this.isAnswered = true;

                try {
                    const res = await fetch("{{ route('siswa.quiz.submit') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            quiz_id: this.currentQuiz.id,
                            answer: key,
                            session_id: this.sessionId,
                            time_spent: Math.floor((Date.now() - this.questionStartedAt) / 1000)
                        })
                    });
                    const data = await res.json();
                    
                    this.submittedAnswers[this.currentQuiz.id] = key;

                    if (data.correct) {
                        this.totalSessionPoints += data.points;
                        if (!this.quizStatuses[this.currentQuiz.id]) {
                            this.quizStatuses[this.currentQuiz.id] = { is_correct: true, attempts_count: 1 };
                        } else {
                            this.quizStatuses[this.currentQuiz.id].is_correct = true;
                            this.quizStatuses[this.currentQuiz.id].attempts_count++;
                        }

                        let messageText = '+' + data.points + ' Poin!';
                        if (data.already_earned) {
                            messageText = 'Jawabanmu benar! (Poin sudah didapatkan sebelumnya)';
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Benar! 🎉',
                            text: messageText,
                            timer: 1600,
                            showConfirmButton: false,
                            customClass: {
                                popup: 'rounded-[28px] font-poppins',
                                title: 'font-extrabold text-slate-900',
                            }
                        });
                    } else {
                        if (!this.quizStatuses[this.currentQuiz.id]) {
                            this.quizStatuses[this.currentQuiz.id] = { is_correct: false, attempts_count: 1 };
                        } else {
                            this.quizStatuses[this.currentQuiz.id].attempts_count++;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Hampir Benar! 😅',
                            text: 'Jawaban yang tepat adalah: ' + data.correct_answer,
                            timer: 2000,
                            showConfirmButton: false,
                            customClass: {
                                popup: 'rounded-[28px] font-poppins',
                                title: 'font-extrabold text-slate-900',
                            }
                        });
                    }
                } catch (e) {
                    console.error(e);
                }
            },

            async submitEssayAnswer() {
                if (this.isAnswered || this.isSubmitting || !this.essayInput.trim()) return;

                this.isSubmitting = true;
                const answerText = this.essayInput.trim();

                try {
                    const res = await fetch("{{ route('siswa.quiz.submit') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            quiz_id: this.currentQuiz.id,
                            answer: answerText,
                            session_id: this.sessionId,
                            time_spent: Math.floor((Date.now() - this.questionStartedAt) / 1000)
                        })
                    });
                    const data = await res.json();

                    this.isAnswered = true;
                    this.submittedAnswers[this.currentQuiz.id] = answerText;
                    if (data.ai_feedback) {
                        this.aiFeedbacks[this.currentQuiz.id] = data.ai_feedback;
                    }

                    if (data.correct) {
                        this.totalSessionPoints += data.points;
                        if (!this.quizStatuses[this.currentQuiz.id]) {
                            this.quizStatuses[this.currentQuiz.id] = { is_correct: true, attempts_count: 1, last_answer: answerText, ai_feedback: data.ai_feedback };
                        } else {
                            this.quizStatuses[this.currentQuiz.id].is_correct = true;
                            this.quizStatuses[this.currentQuiz.id].attempts_count++;
                            this.quizStatuses[this.currentQuiz.id].last_answer = answerText;
                            this.quizStatuses[this.currentQuiz.id].ai_feedback = data.ai_feedback;
                        }

                        let messageText = '+' + data.points + ' Poin!';
                        if (data.already_earned) {
                            messageText = 'Jawaban essay kamu sudah dinilai! (Poin sudah didapatkan sebelumnya)';
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Jawaban Diterima! 🌟',
                            text: data.ai_feedback ? data.ai_feedback : messageText,
                            confirmButtonColor: '#0F172A',
                            customClass: {
                                popup: 'rounded-[28px] font-poppins',
                                title: 'font-extrabold text-slate-900',
                            }
                        });
                    } else {
                        if (!this.quizStatuses[this.currentQuiz.id]) {
                            this.quizStatuses[this.currentQuiz.id] = { is_correct: false, attempts_count: 1, last_answer: answerText };
                        } else {
                            this.quizStatuses[this.currentQuiz.id].attempts_count++;
                            this.quizStatuses[this.currentQuiz.id].last_answer = answerText;
                        }

                        Swal.fire({
                            icon: 'info',
                            title: 'Periksa Kembali! 💡',
                            text: data.ai_feedback ? data.ai_feedback : 'Perhatikan penjelasan dan kunci jawaban model untuk memperbaikinya.',
                            confirmButtonColor: '#0F172A',
                            customClass: {
                                popup: 'rounded-[28px] font-poppins',
                                title: 'font-extrabold text-slate-900',
                            }
                        });
                    }
                } catch (e) {
                    console.error(e);
                    Swal.fire('Error', 'Gagal mengirimkan jawaban essay.', 'error');
                } finally {
                    this.isSubmitting = false;
                }
            },

            prevQuestion() {
                if (this.currentIndex > 0) {
                    this.currentIndex--;
                    this.syncCurrentState();
                }
            },

            nextQuestion() {
                if (this.currentIndex < this.quizzes.length - 1) {
                    this.currentIndex++;
                    this.syncCurrentState();
                } else {
                    this.isFinished = true;
                }
            },

            finishQuiz() {
                this.isFinished = true;
            }
        };
    }
</script>
@endsection
