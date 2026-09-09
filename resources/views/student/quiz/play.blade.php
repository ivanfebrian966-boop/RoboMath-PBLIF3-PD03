@extends('layouts.app')

@section('content')
<div x-data="quizPlayer()" class="max-w-3xl mx-auto space-y-6">

    <!-- Quiz Header Progress -->
    <div class="bg-white p-6 rounded-3xl border-2 border-slate-200 shadow-sm flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-3xl">{{ $topic->icon }}</span>
            <div>
                <h2 class="font-extrabold text-lg text-slate-800">{{ $topic->title }}</h2>
                <p class="text-xs font-bold text-slate-400">Soal <span x-text="currentIndex + 1"></span> dari <span x-text="quizzes.length"></span></p>
            </div>
        </div>
        <div class="bg-amber-100 text-amber-900 font-extrabold px-4 py-2 rounded-2xl text-sm border border-amber-300">
            🏆 <span x-text="totalSessionPoints">0</span> Poin Sesi Ini
        </div>
    </div>

    <!-- Active Question Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border-4 border-indigo-100 shadow-xl space-y-6" x-show="!isFinished">
        
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold uppercase px-3 py-1 rounded-full bg-indigo-100 text-indigo-700" x-text="currentQuiz.type_label"></span>
                <span class="text-xs font-extrabold text-amber-600 bg-amber-50 px-3 py-1 rounded-full border border-amber-200">+<span x-text="currentQuiz.points"></span> Poin</span>
            </div>
            <h3 class="text-xl sm:text-2xl font-black text-slate-900 leading-snug" x-text="currentQuiz.question"></h3>
        </div>

        <!-- Options List -->
        <div class="space-y-3 pt-2">
            <template x-for="(optionText, key) in currentQuiz.options" :key="key">
                <button @click="selectAnswer(key)" 
                        :disabled="isAnswered"
                        :class="{
                            'border-indigo-600 bg-indigo-50 text-indigo-900': selectedKey === key && !isAnswered,
                            'border-emerald-500 bg-emerald-50 text-emerald-900 font-black': isAnswered && key === currentQuiz.correct_answer,
                            'border-rose-500 bg-rose-50 text-rose-900': isAnswered && selectedKey === key && key !== currentQuiz.correct_answer,
                            'border-slate-200 hover:border-indigo-400 hover:bg-slate-50': !isAnswered
                        }"
                        class="w-full text-left p-4 rounded-2xl border-2 transition-all font-extrabold flex items-center justify-between text-base">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 font-black flex items-center justify-center text-sm border" x-text="key"></span>
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
        <div x-show="isAnswered" x-transition class="p-4 rounded-2xl bg-amber-50 border-2 border-amber-300 text-amber-900 space-y-2">
            <p class="font-extrabold text-sm flex items-center gap-2">
                <span>💡 Penjelasan Logika:</span>
            </p>
            <p class="text-xs font-semibold leading-relaxed" x-text="currentQuiz.explanation"></p>
        </div>

        <!-- Next / Finish Button -->
        <div class="pt-4 flex justify-end">
            <button x-show="isAnswered" 
                    @click="nextQuestion()" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-black px-8 py-3.5 rounded-2xl shadow-lg transition-all text-base">
                <span x-text="currentIndex < quizzes.length - 1 ? 'Soal Selanjutnya ➡️' : 'Lihat Hasil Kuis 🎉'"></span>
            </button>
        </div>

    </div>

    <!-- Finished Screen -->
    <div class="bg-white rounded-3xl p-8 border-4 border-emerald-200 shadow-2xl text-center space-y-6" x-show="isFinished" style="display: none;">
        <div class="text-6xl animate-bounce">🎉</div>
        <div class="space-y-2">
            <h2 class="text-3xl font-black text-slate-900">Hebat Sekali! Kuis Selesai!</h2>
            <p class="text-slate-600 font-semibold">Kamu telah menyelesaikan latihan logika topik {{ $topic->title }}!</p>
        </div>

        <div class="p-6 bg-emerald-50 rounded-2xl border-2 border-emerald-200 max-w-sm mx-auto space-y-2">
            <p class="text-xs uppercase font-black text-emerald-800">Total Poin yang Didapat:</p>
            <p class="text-4xl font-black text-emerald-600">+<span x-text="totalSessionPoints"></span> Poin</p>
        </div>

        <div class="flex justify-center gap-4">
            <a href="{{ route('siswa.quiz') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-800 font-extrabold px-6 py-3 rounded-2xl">
                Pilih Kuis Lain 📋
            </a>
            <a href="{{ route('siswa.dashboard') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold px-8 py-3 rounded-2xl shadow-lg">
                Kembali ke Dashboard 🏠
            </a>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    function quizPlayer() {
        return {
            quizzes: @json($quizzes),
            currentIndex: 0,
            selectedKey: null,
            isAnswered: false,
            isFinished: false,
            totalSessionPoints: 0,
            
            get currentQuiz() {
                return this.quizzes[this.currentIndex] || {};
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
                        Swal.fire({
                            icon: 'success',
                            title: 'Benar! 🎉',
                            text: '+' + data.points + ' Poin!',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Hampir Benar! 😅',
                            text: 'Jawaban yang tepat adalah: ' + data.correct_answer,
                            timer: 1800,
                            showConfirmButton: false
                        });
                    }
                } catch (e) {
                    console.error(e);
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
            }
        }
    }
</script>
@endsection
