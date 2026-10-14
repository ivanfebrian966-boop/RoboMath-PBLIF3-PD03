@extends('layouts.admin')

@section('title', 'Edit Sekolah - RoboMath')

@section('content')
<div class="max-w-xl mx-auto space-y-6">

    <div>
        <h1 class="text-2xl font-black text-slate-900">✏️ Edit Sekolah</h1>
        <p class="text-sm text-slate-500 mt-0.5">Perbarui data sekolah: <strong>{{ $sekolah->nama_sekolah }}</strong></p>
    </div>

    <div class="bg-white border-2 border-amber-200/70 rounded-3xl p-8 shadow-sm">
        <form action="{{ route('admin.sekolah.update', $sekolah) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-black text-slate-700 mb-1.5">Nama Sekolah <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_sekolah" value="{{ old('nama_sekolah', $sekolah->nama_sekolah) }}"
                       class="w-full px-4 py-3 border-2 border-amber-200 rounded-2xl focus:outline-none focus:border-amber-400 bg-amber-50/30 font-medium text-slate-800 placeholder-slate-300 transition-colors @error('nama_sekolah') border-rose-400 bg-rose-50 @enderror">
                @error('nama_sekolah')<p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-black text-slate-700 mb-1.5">Kota</label>
                <input type="text" name="nama_kota" value="{{ old('nama_kota', $sekolah->nama_kota) }}"
                       class="w-full px-4 py-3 border-2 border-amber-200 rounded-2xl focus:outline-none focus:border-amber-400 bg-amber-50/30 font-medium text-slate-800 placeholder-slate-300 transition-colors">
            </div>

            <div>
                <label class="block text-sm font-black text-slate-700 mb-1.5">Status</label>
                <select name="status" class="w-full px-4 py-3 border-2 border-amber-200 rounded-2xl focus:outline-none focus:border-amber-400 bg-amber-50/30 font-medium text-slate-800 transition-colors">
                    <option value="A" {{ old('status', $sekolah->status) === 'A' ? 'selected' : '' }}>✅ Aktif</option>
                    <option value="N" {{ old('status', $sekolah->status) === 'N' ? 'selected' : '' }}>⛔ Nonaktif</option>
                </select>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="flex-1 py-3 bg-amber-400 hover:bg-amber-500 text-amber-900 font-black rounded-2xl shadow-sm transition-all duration-200 hover:scale-[1.02]">
                    Perbarui
                </button>
                <a href="{{ route('admin.sekolah.index') }}"
                   class="flex-1 py-3 text-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-2xl transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
