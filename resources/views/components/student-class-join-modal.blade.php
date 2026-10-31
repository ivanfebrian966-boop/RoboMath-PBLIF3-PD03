<div
    x-data="{
        open: {{ $errors->has('code') || session()->has('info') ? 'true' : 'false' }},
        code: @js(old('code', '')).toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 6).split(''),
        classPreview: null,
        previewStatus: 'idle',
        previewMessage: '',
        pasteError: '',
        previewRequest: 0,
        init() {
            this.code = [...this.code, ...Array(6 - this.code.length).fill('')];
            this.$watch('open', isOpen => {
                if (isOpen) this.$nextTick(() => this.focusInput(0));
            });
            if (this.open) this.$nextTick(() => this.focusInput(0));
            this.refreshPreview();
        },
        focusInput(index) {
            const input = this.$root.querySelector('#class-code-' + index);
            if (input) input.focus();
        },
        setCode(event, index) {
            const character = event.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(-1);
            event.target.value = character;
            this.code[index] = character;
            if (character && index < 5) this.focusInput(index + 1);
            this.refreshPreview();
        },
        backspace(event, index) {
            if (!this.code[index] && index > 0) this.focusInput(index - 1);
        },
        fillCode(value) {
            const characters = value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 6).split('');
            this.code = [...characters, ...Array(6 - characters.length).fill('')];
            this.pasteError = '';
            this.focusInput(Math.min(characters.length, 5));
            this.refreshPreview();
        },
        handlePaste(event) {
            const pastedCode = event.clipboardData.getData('text');
            if (pastedCode) {
                event.preventDefault();
                this.fillCode(pastedCode);
            }
        },
        async pasteFromClipboard() {
            try {
                this.fillCode(await navigator.clipboard.readText());
            } catch {
                this.pasteError = 'Tempel kode dengan Ctrl+V pada kotak pertama.';
                this.focusInput(0);
            }
        },
        async refreshPreview() {
            const request = ++this.previewRequest;
            const classCode = this.code.join('');
            this.classPreview = null;
            this.previewMessage = '';
            this.previewStatus = classCode.length === 6 ? 'loading' : 'idle';

            if (classCode.length !== 6) return;

            try {
                const response = await fetch('{{ route('siswa.kelas.join.preview') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                    body: JSON.stringify({ code: classCode }),
                });
                const result = await response.json();

                if (request !== this.previewRequest) return;

                if (!response.ok) {
                    this.previewStatus = response.status === 404 ? 'invalid' : 'failed';
                    this.previewMessage = result.message || 'Kode kelas belum dapat diperiksa. Coba lagi.';
                    return;
                }

                this.classPreview = result;
                this.previewStatus = 'ready';
            } catch {
                if (request !== this.previewRequest) return;
                this.previewStatus = 'failed';
                this.previewMessage = 'Koneksi terputus. Kamu tetap bisa mencoba masuk ke kelas.';
            }
        },
    }"
    @open-class-join-modal.window="open = true"
    @keydown.escape.window="open = false"
>
    <div
        x-cloak
        x-show="open"
        x-transition.opacity
        @click.self="open = false"
        class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto bg-slate-950/60 p-3 backdrop-blur-sm sm:p-5"
        style="display: none;"
        role="dialog"
        aria-modal="true"
        aria-labelledby="class-join-modal-title"
    >
        <section
            x-show="open"
            x-transition
            class="relative my-auto w-full max-w-3xl overflow-hidden rounded-[34px] border-4 border-[#FFE5A3] bg-white shadow-2xl sm:rounded-[44px]"
        >
            <header class="relative bg-[#D9F5E9] px-6 pb-7 pt-6 sm:px-10 sm:pb-8 sm:pt-9">
                <div class="flex items-start justify-between gap-5">
                    <div class="flex h-[72px] w-[72px] flex-shrink-0 items-center justify-center rounded-[28px] bg-[#00815F] text-white sm:h-[90px] sm:w-[90px]">
                        <svg class="h-10 w-10 sm:h-12 sm:w-12" viewBox="0 0 48 48" fill="none" aria-hidden="true">
                            <path d="M29 9.5 38.5 19M33 14l-8 8m2-2-5.5 5.5a8 8 0 1 0 3.5 3.5L30.5 24M17 37a4 4 0 1 1 0-8 4 4 0 0 1 0 8Z" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <button
                        type="button"
                        @click="open = false"
                        class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full border border-[#E6E9E8] bg-white text-slate-800 transition hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-emerald-200"
                        aria-label="Tutup pop-up"
                    >
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
                <h2 id="class-join-modal-title" class="mt-6 text-3xl font-extrabold leading-tight tracking-tight text-[#102A23] sm:text-[40px]">
                    Gabung kelas
                </h2>
                <p class="mt-3 max-w-xl text-base font-medium leading-relaxed text-[#45645A] sm:text-xl">
                    Masukkan kode 6 karakter dari gurumu untuk membuka kelas dan soalnya.
                </p>
            </header>

            <div class="space-y-5 px-6 py-7 sm:space-y-6 sm:px-10 sm:py-8">
                @if(session('info'))
                    <div class="flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-3 text-sm font-bold text-amber-900" role="status">
                        <span class="text-xl" aria-hidden="true">!</span>
                        <span>{{ session('info') }}</span>
                    </div>
                @endif

                <form action="{{ route('siswa.kelas.join.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="code" :value="code.join('')">

                    <div class="space-y-4">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <label for="class-code-0" class="text-xs font-extrabold uppercase tracking-[0.12em] text-slate-600 sm:text-sm">
                                Kode kelas dari guru
                            </label>
                            <button
                                type="button"
                                @click="pasteFromClipboard()"
                                class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 transition hover:text-blue-800 focus:outline-none focus:underline"
                            >
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <rect x="7" y="4" width="10" height="16" rx="2" stroke="currentColor" stroke-width="2"/>
                                    <path d="M10 4.5h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                Tempel kode
                            </button>
                        </div>

                        <div class="grid grid-cols-6 gap-2 sm:gap-3" @paste="handlePaste($event)">
                            <template x-for="index in 6" :key="index">
                                <input
                                    :id="'class-code-' + (index - 1)"
                                    type="text"
                                    inputmode="text"
                                    maxlength="1"
                                    :value="code[index - 1]"
                                    @input="setCode($event, index - 1)"
                                    @keydown.backspace="backspace($event, index - 1)"
                                    @keydown.left.prevent="focusInput(Math.max(index - 2, 0))"
                                    @keydown.right.prevent="focusInput(Math.min(index, 5))"
                                    autocomplete="off"
                                    :aria-label="'Karakter ' + index + ' dari 6'"
                                    class="h-[72px] min-w-0 rounded-[22px] border-[3px] border-[#00815F] bg-[#D9F5E9] text-center font-poppins text-3xl font-extrabold uppercase text-[#006B50] outline-none transition focus:ring-4 focus:ring-emerald-100 sm:h-[104px] sm:rounded-[26px] sm:text-[42px]"
                                    required
                                >
                            </template>
                        </div>
                        <p x-cloak x-show="pasteError" x-text="pasteError" class="text-sm font-semibold text-amber-700" role="status"></p>

                        @error('code')
                            <p class="rounded-xl border border-rose-200 bg-rose-50 p-3 text-sm font-bold text-rose-700" role="alert">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div
                        x-cloak
                        x-show="previewStatus === 'loading'"
                        class="flex items-center gap-3 rounded-3xl border border-emerald-100 bg-emerald-50/80 p-4 text-sm font-semibold text-slate-600"
                        role="status"
                    >
                        <span class="h-5 w-5 animate-spin rounded-full border-2 border-emerald-700 border-t-transparent"></span>
                        Memeriksa kode kelas...
                    </div>

                    <div
                        x-cloak
                        x-show="classPreview"
                        class="flex items-center gap-4 rounded-3xl border border-emerald-200 bg-[#F0FCF7] p-4 sm:px-5 sm:py-4"
                        :class="classPreview && classPreview.can_join ? 'border-emerald-200' : 'border-amber-300 bg-amber-50'"
                    >
                        <div
                            class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-[20px] bg-[#00815F] text-white"
                            :class="classPreview && classPreview.can_join ? 'bg-[#00815F]' : 'bg-amber-600'"
                        >
                            <svg x-show="classPreview && classPreview.can_join" class="h-7 w-7" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="m5 12 4 4L19 6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <svg x-show="classPreview && !classPreview.can_join" class="h-7 w-7" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M12 8v4m0 4h.01M10.3 3.9 2.8 17a2 2 0 0 0 1.7 3h15a2 2 0 0 0 1.7-3l-7.5-13.1a2 2 0 0 0-3.4 0Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-base font-extrabold text-slate-800 sm:text-lg" x-text="classPreview ? classPreview.class.name : ''"></p>
                            <p
                                class="mt-1 text-sm font-semibold text-slate-600"
                                x-text="classPreview && classPreview.already_joined
                                    ? 'Guru: ' + classPreview.class.teacher + ' · Kamu sudah bergabung'
                                    : (classPreview && classPreview.can_join
                                        ? 'Guru: ' + classPreview.class.teacher + ' · Kode cocok'
                                        : (classPreview ? classPreview.message : ''))"
                            ></p>
                        </div>
                    </div>

                    <p
                        x-cloak
                        x-show="previewMessage"
                        x-text="previewMessage"
                        class="rounded-xl border border-amber-200 bg-amber-50 p-3 text-sm font-semibold text-amber-800"
                        role="status"
                    ></p>

                    <button
                        type="submit"
                        :disabled="code.join('').length !== 6 || previewStatus === 'loading' || (previewStatus === 'ready' && !classPreview.can_join) || previewStatus === 'invalid'"
                        class="flex w-full items-center justify-center gap-3 rounded-full bg-[#00815F] px-5 py-5 text-lg font-extrabold text-white shadow-[0_12px_22px_rgba(0,129,95,0.24)] transition hover:bg-[#006F51] focus:outline-none focus:ring-4 focus:ring-emerald-200 active:translate-y-0 disabled:cursor-not-allowed disabled:bg-slate-300 disabled:shadow-none sm:py-6 sm:text-xl"
                    >
                        Masuk ke kelas
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </form>

                <button
                    type="button"
                    @click="open = false"
                    class="block w-full text-center text-base font-extrabold text-slate-500 transition hover:text-slate-700 focus:outline-none focus:underline"
                >
                    Batal
                </button>

                <div class="flex items-center gap-3 rounded-3xl border border-amber-200 bg-[#FFF7E2] px-4 py-4 text-sm font-bold text-amber-800 sm:px-5">
                    <svg class="h-6 w-6 flex-shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                        <path d="M12 11v5m0-8h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <span>Pastikan kode sesuai dengan tingkat kelasmu.</span>
                </div>
            </div>
        </section>
    </div>
</div>
