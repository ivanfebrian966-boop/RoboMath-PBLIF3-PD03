@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-md bg-white rounded-3xl p-8 border-4 border-indigo-100 shadow-2xl space-y-6">
        
        <div class="text-center space-y-2">
            <a href="{{ route('home') }}" class="inline-block">
                <span class="text-4xl font-black">
                    <span class="text-[#FF4757]">A</span><span class="text-[#70A1FF]">l</span><span class="text-[#FFA502]">g</span><span class="text-[#ECCC68]">o</span><span class="text-[#FF6B81]">K</span><span class="text-[#84CC16]">i</span><span class="text-[#2ED573]">d</span><span class="text-[#1E90FF]">s</span>
                </span>
            </a>
            <h2 class="text-2xl font-black text-slate-800">Buat Akun Baru 🌟</h2>
            <p class="text-slate-500 font-medium text-sm">Pilih peranmu dan mulai perjalanan belajarmu!</p>
        </div>

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold space-y-1">
                @foreach($errors->all() as $error)
                    <p>⚠️ {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-4" x-data="{ role: 'siswa' }">
            @csrf
            <div>
                <label class="block text-xs font-black uppercase text-slate-600 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-3 rounded-2xl border-2 border-slate-200 focus:border-indigo-500 focus:outline-none font-bold text-slate-800"
                       placeholder="contoh: Budi Pratama">
            </div>

            <div>
                <label class="block text-xs font-black uppercase text-slate-600 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-4 py-3 rounded-2xl border-2 border-slate-200 focus:border-indigo-500 focus:outline-none font-bold text-slate-800"
                       placeholder="nama@email.com">
            </div>

            <div>
                <label class="block text-xs font-black uppercase text-slate-600 mb-1">Peran Akun</label>
                <select name="role" x-model="role" class="w-full px-4 py-3 rounded-2xl border-2 border-slate-200 focus:border-indigo-500 focus:outline-none font-bold text-slate-800">
                    <option value="siswa">Siswa SD 🎒</option>
                    <option value="guru">Guru / Pengajar 👨‍🏫</option>
                    <option value="orangtua">Orang Tua 👨‍👩‍👧‍👦</option>
                </select>
            </div>

            <div x-show="role === 'siswa'">
                <label class="block text-xs font-black uppercase text-slate-600 mb-1">Kelas SD (1-6)</label>
                <select name="kelas" class="w-full px-4 py-3 rounded-2xl border-2 border-slate-200 focus:border-indigo-500 focus:outline-none font-bold text-slate-800">
                    <option value="1">Kelas 1 SD</option>
                    <option value="2">Kelas 2 SD</option>
                    <option value="3">Kelas 3 SD</option>
                    <option value="4">Kelas 4 SD</option>
                    <option value="5">Kelas 5 SD</option>
                    <option value="6">Kelas 6 SD</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-black uppercase text-slate-600 mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full px-4 py-3 rounded-2xl border-2 border-slate-200 focus:border-indigo-500 focus:outline-none font-bold text-slate-800"
                       placeholder="••••••••">
            </div>

            <div>
                <label class="block text-xs font-black uppercase text-slate-600 mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required
                       class="w-full px-4 py-3 rounded-2xl border-2 border-slate-200 focus:border-indigo-500 focus:outline-none font-bold text-slate-800"
                       placeholder="••••••••">
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-extrabold py-3.5 rounded-2xl shadow-lg transition-all text-base">
                Daftar Akun 🚀
            </button>
        </form>

        <div class="pt-4 border-t text-center text-sm font-semibold text-slate-600">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-indigo-600 font-extrabold hover:underline">Masuk Di Sini</a>
        </div>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
