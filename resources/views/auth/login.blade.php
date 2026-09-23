@extends('layouts.guest')

@section('title', 'Sign In & Sign Up - RoboMath')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4 sm:p-6 bg-[#FAF6EF] relative overflow-hidden">

    {{-- Ambient Background Glows --}}
    <div class="fixed top-0 left-10 w-[450px] h-[450px] bg-gradient-to-tr from-amber-200/50 via-orange-200/40 to-red-100/30 rounded-full blur-[110px] pointer-events-none z-0"></div>
    <div class="fixed bottom-0 right-10 w-[450px] h-[450px] bg-gradient-to-br from-amber-200/40 via-yellow-200/30 to-orange-100/20 rounded-full blur-[120px] pointer-events-none z-0"></div>

    {{-- Floating Math Symbols in Background --}}
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden select-none">
        <span class="absolute text-4xl opacity-15 font-black text-amber-500 animate-float-slow" style="top: 10%; left: 8%;">+</span>
        <span class="absolute text-3xl opacity-15 font-black text-orange-500 animate-float-reverse" style="top: 22%; right: 10%;">×</span>
        <span class="absolute text-4xl opacity-15 font-black text-red-400 animate-float-slow" style="bottom: 18%; left: 12%;">÷</span>
        <span class="absolute text-3xl opacity-15 font-black text-amber-600 animate-float-reverse" style="bottom: 25%; right: 14%;">=</span>
        <span class="absolute text-2xl opacity-15 animate-float-slow" style="top: 65%; left: 6%;">🤖</span>
        <span class="absolute text-2xl opacity-15 animate-float-reverse" style="top: 12%; right: 28%;">📐</span>
    </div>

    {{-- MAIN SLIDING AUTH CONTAINER (Exact Florin Pop / Reference Layout) --}}
    <div class="auth-container relative w-full max-w-[880px] min-h-[580px] bg-white rounded-[32px] shadow-[0_20px_50px_rgba(249,115,22,0.12)] border border-amber-100/70 overflow-hidden z-10 {{ request()->routeIs('register') || request()->get('mode') === 'register' || ($errors->any() && old('is_signup') === '1') ? 'right-panel-active' : '' }}" id="authContainer">

        {{-- ======================================================== --}}
        {{-- 1. SIGN UP FORM CONTAINER (Left in DOM, Slides in to Right) --}}
        {{-- ======================================================== --}}
        <div class="form-container sign-up-container absolute top-0 left-0 w-full md:w-1/2 h-full flex flex-col justify-center items-center px-6 sm:px-12 py-8 text-center bg-white transition-all duration-700 ease-in-out">
            <form action="{{ route('register') }}" method="POST" class="w-full max-w-sm flex flex-col items-center">
                @csrf
                <input type="hidden" name="is_signup" value="1">

                <a href="{{ route('home') }}" class="mb-2 inline-block">
                    <img src="{{ asset('images/logo.png') }}" alt="RoboMath Logo" class="h-8 w-auto hover:scale-105 transition-transform">
                </a>

                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mb-2">Create Account</h1>

                {{-- Social Icons (Matching Reference: G+, f, gh, in) --}}
                <div class="flex items-center justify-center gap-2.5 mb-2">
                    <a href="#" title="Google" class="w-9 h-9 rounded-xl border border-slate-200 bg-white flex items-center justify-center text-slate-700 hover:border-orange-500 hover:text-orange-500 hover:shadow-sm transition-all text-xs font-bold">
                        G+
                    </a>
                    <a href="#" title="Facebook" class="w-9 h-9 rounded-xl border border-slate-200 bg-white flex items-center justify-center text-slate-700 hover:border-orange-500 hover:text-orange-500 hover:shadow-sm transition-all text-xs font-bold">
                        f
                    </a>
                    <a href="#" title="GitHub" class="w-9 h-9 rounded-xl border border-slate-200 bg-white flex items-center justify-center text-slate-700 hover:border-orange-500 hover:text-orange-500 hover:shadow-sm transition-all">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                    </a>
                    <a href="#" title="LinkedIn" class="w-9 h-9 rounded-xl border border-slate-200 bg-white flex items-center justify-center text-slate-700 hover:border-orange-500 hover:text-orange-500 hover:shadow-sm transition-all text-xs font-bold">
                        in
                    </a>
                </div>

                <p class="text-[11px] text-slate-400 font-medium mb-3">or use your email for registration</p>

                @if($errors->any() && old('is_signup') === '1')
                    <div class="w-full mb-2 p-2 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 text-[11px] font-bold text-left">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="w-full space-y-2">
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="Name"
                           class="w-full bg-[#f1f3f6] text-slate-800 placeholder-slate-400 px-4 py-2.5 rounded-xl border border-transparent focus:border-orange-400 focus:bg-white focus:outline-none text-xs sm:text-sm font-semibold transition">

                    <input type="email" name="email" value="{{ old('email') }}" required
                           placeholder="Email"
                           class="w-full bg-[#f1f3f6] text-slate-800 placeholder-slate-400 px-4 py-2.5 rounded-xl border border-transparent focus:border-orange-400 focus:bg-white focus:outline-none text-xs sm:text-sm font-semibold transition">

                    {{-- Role & Kelas Selector --}}
                    <div class="grid grid-cols-2 gap-2" id="roleSelectorContainer">
                        <select name="role" id="registerRole" required onchange="handleRoleChange(this.value)"
                                class="w-full bg-[#f1f3f6] text-slate-800 px-3 py-2.5 rounded-xl border border-transparent focus:border-orange-400 focus:bg-white focus:outline-none text-xs font-bold transition">
                            <option value="siswa" {{ old('role', 'siswa') == 'siswa' ? 'selected' : '' }}>Siswa SD</option>
                            <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                            <option value="orangtua" {{ old('role') == 'orangtua' ? 'selected' : '' }}>Orang Tua</option>
                        </select>

                        <select name="kelas" id="registerKelas"
                                class="w-full bg-[#f1f3f6] text-slate-800 px-3 py-2.5 rounded-xl border border-transparent focus:border-orange-400 focus:bg-white focus:outline-none text-xs font-bold transition">
                            <option value="1" {{ old('kelas', '1') == '1' ? 'selected' : '' }}>Kelas 1 SD</option>
                            <option value="2" {{ old('kelas') == '2' ? 'selected' : '' }}>Kelas 2 SD</option>
                            <option value="3" {{ old('kelas') == '3' ? 'selected' : '' }}>Kelas 3 SD</option>
                            <option value="4" {{ old('kelas') == '4' ? 'selected' : '' }}>Kelas 4 SD</option>
                            <option value="5" {{ old('kelas') == '5' ? 'selected' : '' }}>Kelas 5 SD</option>
                            <option value="6" {{ old('kelas') == '6' ? 'selected' : '' }}>Kelas 6 SD</option>
                        </select>
                    </div>

                    <input type="password" name="password" required
                           placeholder="Password"
                           class="w-full bg-[#f1f3f6] text-slate-800 placeholder-slate-400 px-4 py-2.5 rounded-xl border border-transparent focus:border-orange-400 focus:bg-white focus:outline-none text-xs sm:text-sm font-semibold transition">

                    <input type="password" name="password_confirmation" required
                           placeholder="Confirm Password"
                           class="w-full bg-[#f1f3f6] text-slate-800 placeholder-slate-400 px-4 py-2.5 rounded-xl border border-transparent focus:border-orange-400 focus:bg-white focus:outline-none text-xs sm:text-sm font-semibold transition">
                </div>

                <button type="submit" class="mt-4 px-10 py-3 rounded-xl bg-gradient-to-r from-[#FF5722] via-[#FF7A00] to-[#FFA000] hover:from-[#E64A19] hover:to-[#FF8F00] text-white font-bold text-xs uppercase tracking-wider shadow-md hover:shadow-orange-500/25 transition-all duration-300 transform active:scale-95">
                    SIGN UP
                </button>

                {{-- Mobile Switch to Sign In --}}
                <div class="mt-3 md:hidden text-xs text-slate-500">
                    Already have an account?
                    <button type="button" onclick="switchToSignIn()" class="text-orange-600 font-bold hover:underline">Sign In</button>
                </div>
            </form>
        </div>


        {{-- ======================================================== --}}
        {{-- 2. SIGN IN FORM CONTAINER (Left in DOM, Slides out on active) --}}
        {{-- ======================================================== --}}
        <div class="form-container sign-in-container absolute top-0 left-0 w-full md:w-1/2 h-full flex flex-col justify-center items-center px-6 sm:px-12 py-8 text-center bg-white transition-all duration-700 ease-in-out z-20">
            <form action="{{ route('login') }}" method="POST" class="w-full max-w-sm flex flex-col items-center">
                @csrf
                <input type="hidden" name="is_signup" value="0">

                <a href="{{ route('home') }}" class="mb-3 inline-block">
                    <img src="{{ asset('images/logo.png') }}" alt="RoboMath Logo" class="h-9 w-auto hover:scale-105 transition-transform">
                </a>

                <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-3">Sign In</h1>

                {{-- Social Icons (Matching Reference: G+, f, gh, in) --}}
                <div class="flex items-center justify-center gap-3 mb-3">
                    <a href="#" title="Google" class="w-10 h-10 rounded-xl border border-slate-200 bg-white flex items-center justify-center text-slate-700 hover:border-orange-500 hover:text-orange-500 hover:shadow-sm transition-all text-xs font-bold">
                        G+
                    </a>
                    <a href="#" title="Facebook" class="w-10 h-10 rounded-xl border border-slate-200 bg-white flex items-center justify-center text-slate-700 hover:border-orange-500 hover:text-orange-500 hover:shadow-sm transition-all text-xs font-bold">
                        f
                    </a>
                    <a href="#" title="GitHub" class="w-10 h-10 rounded-xl border border-slate-200 bg-white flex items-center justify-center text-slate-700 hover:border-orange-500 hover:text-orange-500 hover:shadow-sm transition-all">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                    </a>
                    <a href="#" title="LinkedIn" class="w-10 h-10 rounded-xl border border-slate-200 bg-white flex items-center justify-center text-slate-700 hover:border-orange-500 hover:text-orange-500 hover:shadow-sm transition-all text-xs font-bold">
                        in
                    </a>
                </div>

                <p class="text-xs text-slate-400 font-medium mb-4">or use your email password</p>

                @if($errors->any() && old('is_signup') !== '1')
                    <div class="w-full mb-3 p-2.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 text-xs font-bold text-left">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="w-full space-y-3">
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="Email"
                           class="w-full bg-[#f1f3f6] text-slate-800 placeholder-slate-400 px-4 py-3 rounded-xl border border-transparent focus:border-orange-400 focus:bg-white focus:outline-none text-sm font-semibold transition">

                    <input type="password" name="password" required
                           placeholder="Password"
                           class="w-full bg-[#f1f3f6] text-slate-800 placeholder-slate-400 px-4 py-3 rounded-xl border border-transparent focus:border-orange-400 focus:bg-white focus:outline-none text-sm font-semibold transition">
                </div>

                <div class="py-2.5 w-full text-center">
                    <a href="#" class="text-xs text-slate-400 hover:text-orange-500 font-medium transition">
                        Forget Your Password?
                    </a>
                </div>

                <button type="submit" class="mt-2 px-12 py-3 rounded-xl bg-gradient-to-r from-[#FF5722] via-[#FF7A00] to-[#FFA000] hover:from-[#E64A19] hover:to-[#FF8F00] text-white font-bold text-xs uppercase tracking-wider shadow-md hover:shadow-orange-500/25 transition-all duration-300 transform active:scale-95">
                    SIGN IN
                </button>

                {{-- Mobile Switch to Sign Up --}}
                <div class="mt-4 md:hidden text-xs text-slate-500">
                    Don't have an account?
                    <button type="button" onclick="switchToSignUp()" class="text-orange-600 font-bold hover:underline">Sign Up</button>
                </div>
            </form>
        </div>


        {{-- ======================================================== --}}
        {{-- 3. SLIDING OVERLAY CONTAINER (Warm RoboMath Palette & Curve) --}}
        {{-- ======================================================== --}}
        <div class="overlay-container hidden md:block absolute top-0 left-1/2 w-1/2 h-full overflow-hidden transition-all duration-700 ease-in-out z-50">
            
            {{-- Sliding Background Gradient: Warm RoboMath Sunset/Fire, NOT PURPLE --}}
            <div class="overlay relative -left-full h-full w-[200%] text-white transition-all duration-700 ease-in-out bg-gradient-to-br from-[#FF453A] via-[#FF7A00] to-[#FFA000]">
                
                {{-- Decorative background elements inside overlay --}}
                <div class="absolute -right-10 -top-10 w-44 h-44 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
                <div class="absolute -left-10 -bottom-10 w-52 h-52 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                
                {{-- Floating subtle math signs --}}
                <span class="absolute text-5xl text-white/10 font-black" style="top: 12%; left: 22%;">+</span>
                <span class="absolute text-4xl text-white/10 font-black" style="bottom: 15%; left: 28%;">×</span>
                <span class="absolute text-4xl text-white/10 font-black" style="top: 20%; right: 22%;">÷</span>
                <span class="absolute text-3xl text-white/10 font-black" style="bottom: 20%; right: 28%;">=</span>

                {{-- Left Overlay Panel (Shown when in Sign Up Mode -> invites to Sign In) --}}
                <div class="overlay-panel overlay-left absolute top-0 left-0 w-1/2 h-full flex flex-col justify-center items-center px-10 text-center transition-all duration-700 ease-in-out">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mb-4 border border-white/30 shadow-md">
                        <span class="text-3xl">🤖</span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight mb-3">Welcome Back!</h2>
                    <p class="text-sm text-white/90 font-medium leading-relaxed max-w-xs mb-6">
                        To keep connected with us please login with your personal info
                    </p>
                    <button type="button" onclick="switchToSignIn()" class="px-10 py-3 rounded-xl border-2 border-white text-white font-bold text-xs uppercase tracking-wider hover:bg-white hover:text-[#FF5722] transition-all duration-300 transform active:scale-95 shadow-sm">
                        SIGN IN
                    </button>
                </div>

                {{-- Right Overlay Panel (Shown when in Sign In Mode -> invites to Sign Up) --}}
                <div class="overlay-panel overlay-right absolute top-0 right-0 w-1/2 h-full flex flex-col justify-center items-center px-10 text-center transition-all duration-700 ease-in-out">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mb-4 border border-white/30 shadow-md">
                        <span class="text-3xl">🚀</span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight mb-3">Hello, Friend!</h2>
                    <p class="text-sm text-white/90 font-medium leading-relaxed max-w-xs mb-6">
                        Register with your personal details to use all of site features
                    </p>
                    <button type="button" onclick="switchToSignUp()" class="px-10 py-3 rounded-xl border-2 border-white text-white font-bold text-xs uppercase tracking-wider hover:bg-white hover:text-[#FF5722] transition-all duration-300 transform active:scale-95 shadow-sm">
                        SIGN UP
                    </button>
                </div>

            </div>
        </div>

    </div>

</div>

{{-- DOUBLE SLIDER STYLES & INTERACTION SCRIPT --}}
<style>
/* Base Florin Pop sliding mechanics with custom curve & RoboMath aesthetics */
.auth-container {
    transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Sign In container defaults to visible on the left */
.auth-container .sign-in-container {
    transform: translateX(0%);
    opacity: 1;
    z-index: 20;
    pointer-events: auto;
}

/* Sign Up container defaults to hidden underneath */
.auth-container .sign-up-container {
    transform: translateX(0%);
    opacity: 0;
    z-index: 10;
    pointer-events: none;
}

/* Desktop Sliding Overlay Curve (Reference image curvature: curve on left side of right panel) */
@media (min-width: 768px) {
    .overlay-container {
        border-top-left-radius: 170px;
        border-bottom-left-radius: 110px;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        transform: translateX(0%);
        transition: transform 0.7s cubic-bezier(0.77, 0, 0.175, 1),
                    border-radius 0.7s cubic-bezier(0.77, 0, 0.175, 1);
    }

    /* RIGHT PANEL ACTIVE (SIGN UP MODE) */
    .auth-container.right-panel-active .sign-in-container {
        transform: translateX(100%);
        opacity: 0;
        z-index: 10;
        pointer-events: none;
    }

    .auth-container.right-panel-active .sign-up-container {
        transform: translateX(100%);
        opacity: 1;
        z-index: 30;
        pointer-events: auto;
        animation: fadeInPanel 0.7s ease-in-out;
    }

    .auth-container.right-panel-active .overlay-container {
        transform: translateX(-100%);
        /* Curve flips smoothly to the right edge */
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border-top-right-radius: 170px;
        border-bottom-right-radius: 110px;
    }

    .auth-container.right-panel-active .overlay {
        transform: translateX(50%);
    }

    .overlay-left {
        transform: translateX(-20%);
    }

    .auth-container.right-panel-active .overlay-left {
        transform: translateX(0);
    }

    .overlay-right {
        transform: translateX(0);
    }

    .auth-container.right-panel-active .overlay-right {
        transform: translateX(20%);
    }
}

@keyframes fadeInPanel {
    0%, 49.99% {
        opacity: 0;
        z-index: 10;
    }
    50%, 100% {
        opacity: 1;
        z-index: 30;
    }
}

/* Mobile responsive single card fallback */
@media (max-width: 767px) {
    .auth-container {
        min-height: 520px;
    }
    .auth-container .sign-in-container,
    .auth-container .sign-up-container {
        position: relative !important;
        width: 100% !important;
        transform: none !important;
    }
    .auth-container:not(.right-panel-active) .sign-up-container {
        display: none !important;
    }
    .auth-container.right-panel-active .sign-in-container {
        display: none !important;
    }
    .auth-container.right-panel-active .sign-up-container {
        display: flex !important;
        opacity: 1 !important;
        pointer-events: auto !important;
    }
}
</style>

<script>
function switchToSignUp() {
    const container = document.getElementById('authContainer');
    if (container) {
        container.classList.add('right-panel-active');
        // Update URL query state without page reload
        const url = new URL(window.location);
        url.searchParams.set('mode', 'register');
        window.history.replaceState({}, '', url);
    }
}

function switchToSignIn() {
    const container = document.getElementById('authContainer');
    if (container) {
        container.classList.remove('right-panel-active');
        // Update URL query state without page reload
        const url = new URL(window.location);
        url.searchParams.delete('mode');
        window.history.replaceState({}, '', url);
    }
}

function handleRoleChange(role) {
    const kelasSelect = document.getElementById('registerKelas');
    if (kelasSelect) {
        if (role === 'siswa') {
            kelasSelect.style.display = 'block';
            kelasSelect.required = true;
        } else {
            kelasSelect.style.display = 'none';
            kelasSelect.required = false;
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('registerRole');
    if (roleSelect) {
        handleRoleChange(roleSelect.value);
    }
});
</script>
@endsection
