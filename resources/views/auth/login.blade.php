@extends('layouts.guest')

@section('title', 'Masuk – RoboMath')

@section('content')
<div class="min-h-screen bg-slate-50 flex">

    {{-- ====================================================== --}}
    {{-- LEFT: Branding Panel                                    --}}
    {{-- ====================================================== --}}
    <div class="hidden lg:flex lg:w-[52%] flex-col bg-slate-950 relative overflow-hidden">

        {{-- Background decoration --}}
        <div class="absolute inset-0 pointer-events-none">
            {{-- Grid dots --}}
            <div class="absolute inset-0"
                 style="background-image: radial-gradient(circle, rgba(255,255,255,0.06) 1px, transparent 1px); background-size: 28px 28px;"></div>
            {{-- Color blobs --}}
            <div class="absolute -top-20 -right-20 w-80 h-80 bg-[#E03200]/25 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 -left-20 w-96 h-96 bg-[#FF7A1A]/15 rounded-full blur-3xl"></div>
        </div>

        {{-- Content --}}
        <div class="relative flex flex-col h-full px-12 py-10">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="inline-block">
                <img src="{{ asset('images/logo.png') }}" alt="RoboMath" class="h-9 w-auto brightness-0 invert">
            </a>

            {{-- Center block --}}
            <div class="flex-1 flex flex-col justify-center">

                {{-- Mascot --}}
                <div class="relative w-56 h-56 mx-auto mb-8">
                    <div class="absolute inset-0 bg-[#E03200]/20 rounded-full blur-2xl scale-90"></div>
                    <img src="{{ asset('images/maskot.png') }}" alt="RoboBot"
                         class="w-full h-full object-contain relative z-10 drop-shadow-2xl">
                </div>

                <h2 class="text-3xl sm:text-4xl font-black text-white leading-tight mb-4">
                    Halo! Saya <span class="text-[#FF7A1A]">RoboBot</span>,<br>
                    teman belajar matematikamu 🤖
                </h2>

                <p class="text-slate-400 text-sm leading-relaxed max-w-sm">
                    Masuk dan kita mulai petualangan belajar matematika yang seru — soal adaptif, poin, badge, dan banyak lagi!
                </p>

                {{-- Feature pills --}}
                <div class="flex flex-wrap gap-2 mt-7">
                    <span class="rm-pill">🎮 Gamifikasi</span>
                    <span class="rm-pill">🤖 AI Adaptif</span>
                    <span class="rm-pill">📊 Laporan Real-time</span>
                    <span class="rm-pill">🏆 Leaderboard</span>
                </div>

            </div>

            {{-- Bottom text --}}
            <p class="text-slate-600 text-xs font-medium relative">
                © {{ date('Y') }} RoboMath · Platform Belajar Matematika SD
            </p>

        </div>

    </div>

    {{-- ====================================================== --}}
    {{-- RIGHT: Login Form                                        --}}
    {{-- ====================================================== --}}
    <div class="flex-1 flex flex-col justify-center px-6 sm:px-10 py-10 lg:py-0">

        <div class="w-full max-w-sm mx-auto">

            {{-- Mobile logo --}}
            <div class="lg:hidden text-center mb-8">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="RoboMath" class="h-9 w-auto mx-auto">
                </a>
            </div>

            {{-- Heading --}}
            <div class="mb-8">
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mb-1.5">Selamat datang! 👋</h1>
                <p class="text-slate-500 text-sm">Masuk untuk lanjutkan belajar matematika.</p>
            </div>

            {{-- Error --}}
            @if(isset($errors) && $errors->any())
                <div class="mb-5 bg-red-50 border border-red-200 text-red-700 text-sm rounded-2xl px-4 py-3 flex items-start gap-3">
                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium">{{ $errors->first() }}</span>
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           required autofocus autocomplete="email"
                           placeholder="contoh@email.com"
                           class="rm-input">
                </div>

                {{-- Password --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-bold text-slate-700">Kata Sandi</label>
                        <a href="#" class="text-xs font-semibold text-[#E03200] hover:underline">Lupa kata sandi?</a>
                    </div>
                    <div class="relative">
                        <input type="password" id="password" name="password"
                               required autocomplete="current-password"
                               placeholder="Masukkan kata sandi"
                               class="rm-input pr-12">
                        <button type="button" onclick="rmTogglePassword()"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors" aria-label="Tampilkan kata sandi">
                            <svg id="rm-eye-off" class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <svg id="rm-eye-on" class="w-4.5 h-4.5 hidden" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Remember me --}}
                <label class="flex items-center gap-2.5 cursor-pointer select-none group">
                    <input type="checkbox" name="remember"
                           class="w-4 h-4 rounded text-[#E03200] border-slate-300 focus:ring-[#E03200] focus:ring-offset-0 transition">
                    <span class="text-xs font-medium text-slate-600 group-hover:text-slate-800 transition-colors">Ingat saya di perangkat ini</span>
                </label>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full mt-2 bg-[#E03200] hover:bg-[#c42b00] active:scale-[0.98] text-white font-black text-sm rounded-2xl px-5 py-4 transition-all duration-150 shadow-md shadow-red-200">
                    Masuk Sekarang
                </button>

            </form>

            {{-- Back to home --}}
            <div class="mt-7 text-center">
                <a href="{{ route('home') }}"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400 hover:text-slate-700 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>

        </div>

    </div>

</div>

<style>
.rm-input {
    display: block;
    width: 100%;
    background: #fff;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    padding: 13px 16px;
    font-size: 0.875rem;
    font-weight: 500;
    color: #1e293b;
    outline: none;
    transition: border-color 0.15s, box-shadow 0.15s;
}
.rm-input::placeholder { color: #94a3b8; }
.rm-input:focus {
    border-color: #E03200;
    box-shadow: 0 0 0 3px rgba(224, 50, 0, 0.08);
}

.rm-pill {
    display: inline-block;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.12);
    color: rgba(255,255,255,0.75);
    font-size: 0.7rem;
    font-weight: 700;
    border-radius: 999px;
    padding: 5px 11px;
}
</style>

<script>
function rmTogglePassword() {
    const pwd = document.getElementById('password');
    const off = document.getElementById('rm-eye-off');
    const on  = document.getElementById('rm-eye-on');
    if (!pwd) return;
    const isHidden = pwd.type === 'password';
    pwd.type = isHidden ? 'text' : 'password';
    off.classList.toggle('hidden', isHidden);
    on.classList.toggle('hidden', !isHidden);
}
</script>

@endsection
