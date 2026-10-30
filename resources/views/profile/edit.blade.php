@extends('layouts.app')

@section('title', 'Edit Profil - RoboMath')

@section('content')
<div class="max-w-xl mx-auto space-y-6 font-poppins">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">✏️ Edit Profil</h1>
            <p class="text-sm text-slate-500 mt-1">Perbarui informasi akun Anda</p>
        </div>
        <a href="{{ route('profil.show') }}" class="text-sm font-bold text-slate-600 hover:text-slate-900 transition-colors">
            ← Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border-2 border-emerald-300 text-emerald-800 rounded-2xl flex items-center gap-3 font-bold text-sm shadow-xs">
            <svg class="w-5 h-5 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white border border-slate-200/80 rounded-[28px] sm:rounded-[32px] p-6 sm:p-8 shadow-xs">
        <form action="{{ route('profil.update') }}" method="POST" class="space-y-5" id="profile-form">
            @csrf @method('PUT')

            <div>
                <label class="block text-xs font-black uppercase tracking-wider text-slate-500 mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                       class="w-full px-4 py-3.5 border border-slate-200 rounded-[18px] focus:outline-none focus:border-[#00BA88] focus:ring-4 focus:ring-[#00BA88]/10 bg-slate-50/50 font-bold text-slate-800 transition-all @error('name') border-rose-400 bg-rose-50 @enderror">
                @error('name')<p class="text-xs text-rose-500 mt-1.5 font-semibold">{{ $message }}</p>@enderror
            </div>

            @if($user->isAdmin())
            <div>
                <label class="block text-xs font-black uppercase tracking-wider text-slate-500 mb-2">Email <span class="text-rose-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                       class="w-full px-4 py-3.5 border border-slate-200 rounded-[18px] focus:outline-none focus:border-[#00BA88] focus:ring-4 focus:ring-[#00BA88]/10 bg-slate-50/50 font-bold text-slate-800 transition-all @error('email') border-rose-400 bg-rose-50 @enderror">
                @error('email')<p class="text-xs text-rose-500 mt-1.5 font-semibold">{{ $message }}</p>@enderror
            </div>

            <div class="border-t border-slate-100 pt-5 mt-5">
                <p class="text-xs font-black text-slate-400 uppercase tracking-wider mb-4">Ubah Password (opsional)</p>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-slate-500 mb-2">Password Baru</label>
                        <input type="password" name="password"
                               placeholder="Kosongkan jika tidak ingin mengubah"
                               class="w-full px-4 py-3.5 border border-slate-200 rounded-[18px] focus:outline-none focus:border-[#00BA88] focus:ring-4 focus:ring-[#00BA88]/10 bg-slate-50/50 font-bold text-slate-800 placeholder-slate-400 transition-all @error('password') border-rose-400 bg-rose-50 @enderror">
                        @error('password')<p class="text-xs text-rose-500 mt-1.5 font-semibold">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-slate-500 mb-2">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation"
                               placeholder="Ulangi password baru"
                               class="w-full px-4 py-3.5 border border-slate-200 rounded-[18px] focus:outline-none focus:border-[#00BA88] focus:ring-4 focus:ring-[#00BA88]/10 bg-slate-50/50 font-bold text-slate-800 placeholder-slate-400 transition-all">
                    </div>
                </div>
            </div>
            @else
            {{-- Email & password read-only untuk Guru dan Siswa --}}
            <div>
                <label class="block text-xs font-black uppercase tracking-wider text-slate-500 mb-2">Email</label>
                <div class="w-full px-4 py-3.5 border border-slate-200 rounded-[18px] bg-slate-100 font-bold text-slate-500 select-none">
                    {{ $user->email }}
                </div>
            </div>

            <div class="flex items-start gap-3 p-4 bg-amber-50 border border-amber-200 rounded-2xl">
                <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z"/>
                </svg>
                <p class="text-xs text-amber-800 font-medium leading-relaxed">
                    <strong class="font-bold">Email dan password</strong> hanya dapat diubah oleh admin. Hubungi admin sekolah Anda jika perlu mengubah informasi login.
                </p>
            </div>
            @endif

            <div class="flex items-center gap-3 pt-3">
                <button type="submit"
                        class="flex-1 py-3.5 bg-[#EF4444] hover:bg-[#DC2626] text-white font-extrabold rounded-full shadow-[0_4px_14px_rgba(239,68,68,0.35)] transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] cursor-pointer">
                    Simpan Perubahan
                </button>
                <a href="{{ route('profil.show') }}"
                   class="flex-1 py-3.5 text-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold rounded-full transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
