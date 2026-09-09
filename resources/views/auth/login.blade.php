@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-md bg-white rounded-3xl p-8 border-4 border-indigo-100 shadow-2xl space-y-6">
        
        <!-- Logo -->
        <div class="text-center space-y-2">
            <a href="{{ route('home') }}" class="inline-block">
                <span class="text-4xl font-black">
                    <span class="text-[#FF4757]">A</span><span class="text-[#70A1FF]">l</span><span class="text-[#FFA502]">g</span><span class="text-[#ECCC68]">o</span><span class="text-[#FF6B81]">K</span><span class="text-[#84CC16]">i</span><span class="text-[#2ED573]">d</span><span class="text-[#1E90FF]">s</span>
                </span>
            </a>
            <h2 class="text-2xl font-black text-slate-800">Selamat Datang Kembali! 👋</h2>
            <p class="text-slate-500 font-medium text-sm">Masuk ke akunmu untuk melanjutkan belajar</p>
        </div>

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold space-y-1">
                @foreach($errors->all() as $error)
                    <p>⚠️ {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-black uppercase text-slate-600 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full px-4 py-3 rounded-2xl border-2 border-slate-200 focus:border-indigo-500 focus:outline-none font-bold text-slate-800"
                       placeholder="contoh: siswa@algokids.id">
            </div>

            <div>
                <label class="block text-xs font-black uppercase text-slate-600 mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full px-4 py-3 rounded-2xl border-2 border-slate-200 focus:border-indigo-500 focus:outline-none font-bold text-slate-800"
                       placeholder="••••••••">
            </div>

            <div class="flex items-center justify-between text-sm font-bold text-slate-600">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-indigo-600 rounded">
                    <span>Ingat Saya</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-extrabold py-3.5 rounded-2xl shadow-lg transition-all text-base">
                Masuk Akun 🚀
            </button>
        </form>

        <div class="pt-4 border-t text-center text-sm font-semibold text-slate-600 space-y-2">
            <p>Belum punya akun? <a href="{{ route('register') }}" class="text-indigo-600 font-extrabold hover:underline">Daftar Sekarang</a></p>
            <div class="bg-amber-50 p-3 rounded-2xl text-xs text-amber-900 border border-amber-200 font-medium text-left">
                <p class="font-bold mb-1">💡 Akun Demo Siap Pakai:</p>
                <ul class="space-y-0.5 font-mono text-[11px]">
                    <li>• Siswa: siswa@algokids.id (pass: password)</li>
                    <li>• Guru: guru@algokids.id (pass: password)</li>
                    <li>• OrangTua: orangtua@algokids.id (pass: password)</li>
                </ul>
            </div>
        </div>

    </div>
</div>
@endsection
