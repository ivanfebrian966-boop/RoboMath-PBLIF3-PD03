@extends('layouts.app')

@section('content')
<div x-data="quizPlayer()" class="max-w-4xl mx-auto space-y-6">

    <!-- Top Navigation & Topic Header -->
    <div class="bg-white p-5 sm:p-6 rounded-3xl border-2 border-slate-200 shadow-sm flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('siswa.quiz') }}" 
               class="w-10 h-10 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center font-black transition-all flex-shrink-0" 
               title="Kembali ke Daftar Soal">
                ←
            </a>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-3xl flex-shrink-0 border-2 border-amber-200">
                {{ $topic->icon }}
            </div>
            <div>
                <h2 class="font-black text-lg sm:text-xl text-slate-800 leading-tight">{{ $topic->title }}</h2>
                <p class="text-xs font-bold text-slate-400 mt-0.5">
                    Soal <span x-text="currentIndex + 1" class="text-indigo-600 font-black"></span> dari <span x-text="quizzes.length"></span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3 justify-between sm:justify-end">
            <a href="{{ route('siswa.quiz') }}" 
               class="text-xs font-black text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 px-3.5 py-2 rounded-xl transition-all">
                📋 Lihat Semua Soal
            </a>
            <div class="bg-amber-100 text-amber-900 font-black px-4 py-2 rounded-2xl text-xs sm:text-sm border border-amber-300 flex items-center gap-1.5 shadow-sm">
                <span>🏆</span> <span x-text="totalSessionPoints">0</span> Poin Sesi Ini
            </div>
        </div>
    </div>

    <!-- Question Numbers Selector / Navigation Bar -->
    <div class="bg-white p-4 rounded-3xl border-2 border-slate-200 shadow-sm space-y-2" x-show="!isFinished">
        <div class="flex items-center justify-between text-xs font-bold text-slate-500 px-1">
            <span>Pilih Nomor Soal:</span>
            <span class="text-slate-400">Klik nomor untuk berpindah soal langsung</span>
        </div>
        <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-thin">
            <template x-for="(quiz, index) in quizzes" :key="quiz.id">
                <button type="button" 
                        @click="goToQuestion(index)" 
                        :class="{
                            'bg-indigo-600 text-white font-black shadow-md ring-4 ring-indigo-100 scale-105 border-indigo-600': currentIndex === index,
                            'bg-emerald-50 text-emerald-800 border-emerald-300 font-black hover:bg-emerald-100': currentIndex !== index && getQuizStatus(quiz.id) === 'correct',
                            'bg-rose-50 text-rose-800 border-rose-300 font-black hover:bg-rose-100': currentIndex !== index && getQuizStatus(quiz.id) === 'wrong',
                            'bg-slate-50 text-slate-700 border-slate-200 font-bold hover:bg-slate-100': currentIndex !== index && getQuizStatus(quiz.id) === 'unattempted'
                        }"
                        class="min-w-10 h-10 px-3 rounded-2xl border-2 text-xs transition-all flex items-center justify-center gap-1 flex-shrink-0">
                    <span x-text="index + 1"></span>
                    <template x-if="getQuizStatus(quiz.id) === 'correct'">
                        <span class="text-[10px]">✓</span>
                    </template>
                </button>
            </template>
        </div>
    </div>

    <!-- Active Question Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border-4 border-indigo-100 shadow-xl space-y-6" x-show="!isFinished">
        
        <div class="space-y-3">
            <div class="flex items-center justify-between gap-2 flex-wrap">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-black uppercase px-3 py-1 rounded-xl bg-indigo-100 text-indigo-700" x-text="currentQuiz.type_label || 'Pilihan Ganda'"></span>
                    <span class="text-xs font-black uppercase px-3 py-1 rounded-xl"
                          :class="{
                              'bg-emerald-100 text-emerald-700': currentQuiz.difficulty === 'mudah',
                              'bg-amber-100 text-amber-700': currentQuiz.difficulty === 'sedang',
                              'bg-rose-100 text-rose-700': currentQuiz.difficulty === 'sulit'
                          }"
                          x-text="currentQuiz.difficulty ? (currentQuiz.difficulty.charAt(0).toUpperCase() + currentQuiz.difficulty.slice(1)) : 'Mudah'">
                    </span>
                    <template x-if="getQuizStatus(currentQuiz.id) === 'correct'">
                        <span class="text-xs font-black px-2.5 py-1 rounded-xl bg-emerald-100 text-emerald-800 border border-emerald-300">
                            ✅ Sudah Benar
                        </span>
                    </template>
                </div>
                <span class="text-xs font-black text-amber-600 bg-amber-50 px-3.5 py-1 rounded-full border border-amber-200 shadow-sm">
                    +<span x-text="currentQuiz.points"></span> Poin
                </span>
            </div>
            
            <h3 class="text-xl sm:text-2xl font-black text-slate-900 leading-snug pt-1" x-text="currentQuiz.question"></h3>
        </div>

        <!-- Options List -->
        <div class="space-y-3 pt-2">
            <template x-for="(optionText, key) in currentQuiz.options" :key="key">
                <button @click="selectAnswer(key)" 
                        :disabled="isAnswered"
                        :class="{
                            'border-indigo-600 bg-indigo-50 text-indigo-900': selectedKey === key && !isAnswered,
                            'border-emerald-500 bg-emerald-50 text-emerald-900 font-black shadow-md': isAnswered && key === currentQuiz.correct_answer,
                            'border-rose-500 bg-rose-50 text-rose-900': isAnswered && selectedKey === key && key !== currentQuiz.correct_answer,
                            'border-slate-200 hover:border-indigo-400 hover:bg-slate-50 cursor-pointer': !isAnswered,
                            'opacity-75': isAnswered && key !== currentQuiz.correct_answer && selectedKey !== key
                        }"
                        class="w-full text-left p-4 rounded-2xl border-2 transition-all font-extrabold flex items-center justify-between text-sm sm:text-base">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 font-black flex items-center justify-center text-sm border flex-shrink-0" x-text="key"></span>
                        <span x-text="optionText"></span>
                    </div>
                    <template x-if="isAnswered && key === currentQuiz.correct_answer">
                        <span class="text-xl">✅</span>
                    </template>
                    <template x-if="isAnswered && selectedKey === key && key !== currentQuiz.correct_answer">
                        <span class="text-xl">❌</span>
                    </template>
                </button>
            </template>
        </div>

        <!-- Explanation Alert box -->
        <div x-show="isAnswered" x-transition class="p-5 rounded-2xl bg-amber-50 border-2 border-amber-300 text-amber-900 space-y-2">
            <p class="font-black text-sm flex items-center gap-2">
                <span>💡 Penjelasan Logika:</span>
            </p>
            <p class="text-xs sm:text-sm font-semibold leading-relaxed" x-text="currentQuiz.explanation || 'Pembahasan logika soal ini.'"></p>
        </div>

        <!-- Navigation Buttons Bottom Row -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-3 flex-wrap">
            <div>
                <button type="button" 
                        x-show="currentIndex > 0" 
                        @click="prevQuestion()" 
                        class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-black px-5 py-3 rounded-2xl text-xs sm:text-sm transition-all flex items-center gap-2">
                    <span>⬅️</span> Soal Sebelumnya
                </button>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" 
                        x-show="isAnswered" 
                        @click="retryCurrentQuiz()" 
                        class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-black px-4 py-3 rounded-2xl text-xs sm:text-sm transition-all">
                    Coba Ulang 🔄
                </button>

                <button x-show="isAnswered && currentIndex < quizzes.length - 1" 
                        @click="nextQuestion()" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-black px-6 py-3 rounded-2xl shadow-lg hover:shadow-xl transition-all text-xs sm:text-sm flex items-center gap-2">
                    Soal Selanjutnya ➡️
                </button>

                <button x-show="isAnswered && currentIndex === quizzes.length - 1" 
                        @click="finishQuiz()" 
                        class="bg-emerald-600 hover:bg-emerald-700 text-white font-black px-7 py-3 rounded-2xl shadow-lg hover:shadow-xl transition-all text-xs sm:text-sm flex items-center gap-2">
                    Selesai & Lihat Hasil 🎉
                </button>
            </div>
        </div>

    </div>

    <!-- Finished Screen -->
    <div class="bg-white rounded-3xl p-8 border-4 border-emerald-200 shadow-2xl text-center space-y-6" x-show="isFinished" style="display: none;">
        <div class="text-6xl animate-bounce">🎉</div>
        <div class="space-y-2">
            <h2 class="text-3xl font-black text-slate-900">Hebat Sekali! Kuis Selesai!</h2>
            <p class="text-slate-600 font-semibold text-base">Kamu telah menyelesaikan latihan logika topik {{ $topic->title }}!</p>
        </div>

        <div class="p-6 bg-emerald-50 rounded-2xl border-2 border-emerald-200 max-w-sm mx-auto space-y-2 shadow-inner">
            <p class="text-xs uppercase font-black text-emerald-800 tracking-wider">Total Poin yang Didapat Sesi Ini:</p>
            <p class="text-4xl font-black text-emerald-600">+<span x-text="totalSessionPoints"></span> Poin</p>
        </div>

        <div class="flex flex-wrap justify-center gap-4 pt-2">
            <a href="{{ route('siswa.quiz.result', $topic) }}" class="bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-black px-6 py-3.5 rounded-2xl shadow-md transition text-sm">
                📊 Lihat Nilai & Pembahasan
            </a>
            <a href="{{ route('siswa.quiz') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-800 font-extrabold px-6 py-3.5 rounded-2xl text-sm transition">
                Pilih Soal Lain 📋
            </a>
            <a href="{{ route('siswa.dashboard') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold px-8 py-3.5 rounded-2xl shadow-lg text-sm transition">
                Dashboard 🏠
            </a>
        </div>
    </div>

</div>

<script>
    function quizPlayer() {
        return {
            quizzes: @json($quizzes),
            quizStatuses: @json($quizStatuses),
            currentIndex: {{ $startIndex ?? 0 }},
            selectedKey: null,
            isAnswered: false,
            isFinished: false,
            totalSessionPoints: 0,
            
            get currentQuiz() {
                return this.quizzes[this.currentIndex] || {};
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
                    this.selectedKey = null;
                    this.isAnswered = false;
                    this.isFinished = false;
                }
            },

            retryCurrentQuiz() {
                this.selectedKey = null;
                this.isAnswered = false;
            },

            async selectAnswer(key) {
                if (this.isAnswered) return;
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
                            answer: key
                        })
                    });
                    const data = await res.json();
                    
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
                            showConfirmButton: false
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
                            showConfirmButton: false
                        });
                    }
                } catch (e) {
                    console.error(e);
                }
            },

            prevQuestion() {
                if (this.currentIndex > 0) {
                    this.currentIndex--;
                    this.selectedKey = null;
                    this.isAnswered = false;
                }
            },

            nextQuestion() {
                if (this.currentIndex < this.quizzes.length - 1) {
                    this.currentIndex++;
                    this.selectedKey = null;
                    this.isAnswered = false;
                } else {
                    this.isFinished = true;
                }
            },

            finishQuiz() {
                this.isFinished = true;
            }
        }
    }
</script>
@endsection
