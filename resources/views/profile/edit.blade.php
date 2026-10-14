@extends('layouts.app')

@section('title', 'Edit Profil - RoboMath')

@section('content')
<div class="max-w-xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900">✏️ Edit Profil</h1>
            <p class="text-sm text-slate-500 mt-0.5">Perbarui informasi akun Anda</p>
        </div>
        <a href="{{ route('profil.show') }}" class="text-sm text-amber-600 font-bold hover:underline">← Kembali</a>
    </div>

    <div class="bg-white border-2 border-amber-200/70 rounded-3xl p-8 shadow-sm">
        <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-black text-slate-700 mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                       class="w-full px-4 py-3 border-2 border-amber-200 rounded-2xl focus:outline-none focus:border-amber-400 bg-amber-50/30 font-medium text-slate-800 transition-colors @error('name') border-rose-400 bg-rose-50 @enderror">
                @error('name')<p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-black text-slate-700 mb-1.5">Email <span class="text-rose-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                       class="w-full px-4 py-3 border-2 border-amber-200 rounded-2xl focus:outline-none focus:border-amber-400 bg-amber-50/30 font-medium text-slate-800 transition-colors @error('email') border-rose-400 bg-rose-50 @enderror">
                @error('email')<p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-black text-slate-700 mb-1.5">Foto Profil</label>
                <input type="file" name="avatar" accept="image/*"
                       class="w-full px-4 py-2.5 border-2 border-amber-200 rounded-2xl focus:outline-none focus:border-amber-400 bg-amber-50/30 text-slate-700 text-sm transition-colors">
                <p class="text-xs text-slate-400 mt-1">Format: JPG, PNG, GIF. Maks 2MB.</p>
                @error('avatar')<p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>@enderror
            </div>

            <div class="border-t border-amber-100 pt-4">
                <p class="text-xs font-black text-slate-500 uppercase tracking-wider mb-3">Ubah Password (opsional)</p>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-1.5">Password Baru</label>
                        <input type="password" name="password"
                               placeholder="Kosongkan jika tidak ingin mengubah"
                               class="w-full px-4 py-3 border-2 border-amber-200 rounded-2xl focus:outline-none focus:border-amber-400 bg-amber-50/30 font-medium text-slate-800 placeholder-slate-300 transition-colors @error('password') border-rose-400 bg-rose-50 @enderror">
                        @error('password')<p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-1.5">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation"
                               placeholder="Ulangi password baru"
                               class="w-full px-4 py-3 border-2 border-amber-200 rounded-2xl focus:outline-none focus:border-amber-400 bg-amber-50/30 font-medium text-slate-800 placeholder-slate-300 transition-colors">
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="flex-1 py-3 bg-amber-400 hover:bg-amber-500 text-amber-900 font-black rounded-2xl shadow-sm transition-all duration-200 hover:scale-[1.02]">
                    Simpan Perubahan
                </button>
                <a href="{{ route('profil.show') }}"
                   class="flex-1 py-3 text-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-2xl transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
