<div
    x-data="{ open: {{ $errors->has('code') || session()->has('info') ? 'true' : 'false' }} }"
    x-init="$watch('open', isOpen => { if (isOpen) $nextTick(() => $refs.classCode.focus()) }); if (open) $nextTick(() => $refs.classCode.focus())"
    @open-class-join-modal.window="open = true"
    @keydown.escape.window="open = false"
>
    <div
        x-cloak
        x-show="open"
        x-transition.opacity
        @click.self="open = false"
        class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto bg-slate-950/60 p-4 backdrop-blur-sm"
        style="display: none;"
        role="dialog"
        aria-modal="true"
        aria-labelledby="class-join-modal-title"
    >
        <section
            x-show="open"
            x-transition
            class="relative my-auto w-full max-w-md overflow-hidden rounded-[30px] border-4 border-white bg-[#FAF6EF] shadow-2xl"
        >
            <div class="relative overflow-hidden bg-gradient-to-br from-[#00A88F] via-[#059669] to-[#2563EB] px-6 pb-7 pt-6 text-white">
                <div class="pointer-events-none absolute -right-10 -top-12 h-36 w-36 rounded-full bg-amber-300/30 blur-2xl"></div>
                <div class="relative flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-2xl border-2 border-white/60 bg-white p-1 shadow-lg">
                            <img src="{{ asset('images/maskot.png') }}" alt="" class="h-full w-full object-contain">
                        </div>
                        <div>
                            <span class="inline-flex rounded-full bg-white/20 px-2.5 py-1 text-[10px] font-black uppercase tracking-wider text-white">
                                Petualangan RoboMath
                            </span>
                            <h2 id="class-join-modal-title" class="mt-1 text-xl font-black leading-tight text-white">
                                Gabung Kelas
                            </h2>
                        </div>
                    </div>
                    <button
                        type="button"
                        @click="open = false"
                        class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-white/20 text-lg font-black text-white transition hover:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white"
                        aria-label="Tutup pop-up"
                    >
                        &times;
                    </button>
                </div>
                <p class="relative mt-4 text-sm font-semibold leading-relaxed text-white/95">
                    Masukkan kode 6 karakter dari gurumu untuk membuka kelas dan soal matematika seru!
                </p>
            </div>

            <div class="space-y-5 p-6">
                @if(session('info'))
                    <div class="flex items-start gap-3 rounded-2xl border-2 border-amber-200 bg-amber-50 p-3 text-sm font-bold text-amber-900" role="status">
                        <span class="text-xl" aria-hidden="true">💡</span>
                        <span>{{ session('info') }}</span>
                    </div>
                @endif

                <form action="{{ route('siswa.kelas.join.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-2">
                        <label for="class-code" class="block text-sm font-black text-slate-800">
                            Kode Kelas dari Guru
                        </label>
                        <input
                            x-ref="classCode"
                            type="text"
                            id="class-code"
                            name="code"
                            maxlength="6"
                            placeholder="RM7A9K"
                            value="{{ old('code') }}"
                            oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')"
                            autocomplete="off"
                            class="w-full rounded-2xl border-2 border-teal-200 bg-white px-4 py-4 text-center font-mono text-3xl font-black uppercase tracking-[0.35em] text-slate-900 placeholder:text-slate-300 focus:border-[#00A88F] focus:outline-none focus:ring-4 focus:ring-teal-100"
                            aria-describedby="class-code-hint"
                            @error('code') aria-invalid="true" @enderror
                            required
                        >
                        <p id="class-code-hint" class="text-center text-xs font-semibold text-slate-500">
                            Gunakan huruf kapital dan angka yang diberikan gurumu.
                        </p>

                        @error('code')
                            <p class="rounded-xl border border-rose-200 bg-rose-50 p-3 text-xs font-bold text-rose-700" role="alert">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-[#FF8A00] to-[#FF3B30] px-5 py-4 text-sm font-black text-white shadow-[0_6px_0_rgba(194,65,12,0.22)] transition hover:-translate-y-0.5 hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-orange-200 active:translate-y-0"
                    >
                        <span aria-hidden="true">🚀</span>
                        Gabung & Mulai Belajar
                    </button>
                </form>

                <div class="flex items-center justify-center gap-2 rounded-2xl bg-blue-50 px-3 py-2.5 text-center text-xs font-bold text-blue-800">
                    <span aria-hidden="true">⭐</span>
                    <span>Pastikan kode kelas sesuai dengan tingkat kelasmu.</span>
                </div>
            </div>
        </section>
    </div>
</div>
