@extends('layouts.app')

@section('title', 'Buat Kelas Baru - Guru RoboMath')

@section('content')
<div
    x-data="{
        className: '{{ old('name', '') }}',
        kelasLevel: '{{ old('kelas_level', '') }}',
        selectedTopics: {{ json_encode(old('topic_ids', [])) }},
        toggleTopic(id) {
            const idx = this.selectedTopics.indexOf(id);
            if (idx === -1) {
                this.selectedTopics.push(id);
            } else {
                this.selectedTopics.splice(idx, 1);
            }
        },
        isSelected(id) {
            return this.selectedTopics.includes(id);
        },
        get topikTerpilih() {
            return this.selectedTopics.length;
        },
        get kelasLabel() {
            return this.kelasLevel ? 'Kelas ' + this.kelasLevel + ' SD' : '-';
        },
        get classNameLabel() {
            return this.className.trim() ? this.className.trim() : '-';
        }
    }"
    class="max-w-5xl mx-auto space-y-6">

    {{-- Back + Title --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('guru.classes.index') }}" class="text-sm text-slate-500 hover:text-slate-800 transition font-medium">
            &larr; Kembali
        </a>
        <h1 class="text-2xl font-black text-slate-900">Buat Kelas Baru</h1>
    </div>

    <form action="{{ route('guru.classes.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Main Card --}}
            <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">

                {{-- Row: Nama kelas + Tingkatan --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-bold text-slate-700 mb-1.5">Nama kelas</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            x-model="className"
                            value="{{ old('name') }}"
                            placeholder="Contoh: Matematika Ceria 1-A"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-[#056B48] focus:ring-2 focus:ring-emerald-100 outline-none text-sm font-medium text-slate-800"
                            required>
                        @error('name')
                            <p class="text-rose-500 text-xs font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="kelas_level" class="block text-sm font-bold text-slate-700 mb-1.5">Tingkatan kelas (SD)</label>
                        <select
                            id="kelas_level"
                            name="kelas_level"
                            x-model="kelasLevel"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-[#056B48] focus:ring-2 focus:ring-emerald-100 outline-none text-sm font-medium text-slate-800 bg-white"
                            required>
                            <option value="">Pilih Tingkat Kelas</option>
                            @for($i = 1; $i <= 6; $i++)
                                <option value="{{ $i }}" {{ old('kelas_level') == $i ? 'selected' : '' }}>Kelas {{ $i }} SD</option>
                            @endfor
                        </select>
                        @error('kelas_level')
                            <p class="text-rose-500 text-xs font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Topik Section --}}
                <div>
                    <p class="text-sm font-bold text-slate-700 mb-1">Topik materi yang ditugaskan</p>
                    <p class="text-xs text-slate-400 mb-4">Siswa di kelas ini akan fokus pada topik yang kamu pilih.</p>

                    @error('topic_ids')
                        <p class="text-rose-500 text-xs font-bold mb-3">{{ $message }}</p>
                    @enderror

                    @if($topics->isEmpty())
                        <p class="text-sm text-slate-400">Belum ada topik materi yang tersedia.</p>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($topics as $topic)
                            <label
                                @click.prevent="toggleTopic({{ $topic->id }})"
                                :class="isSelected({{ $topic->id }})
                                    ? 'border-[#056B48] bg-[#E1F8EC]'
                                    : 'border-slate-200 bg-white hover:border-slate-300'"
                                class="flex items-center gap-3 p-3.5 rounded-xl border-2 cursor-pointer transition">
                                <span
                                    :class="isSelected({{ $topic->id }})
                                        ? 'bg-[#056B48] border-[#056B48]'
                                        : 'border-slate-300 bg-white'"
                                    class="w-5 h-5 rounded flex items-center justify-center border-2 flex-shrink-0 transition">
                                    <svg x-show="isSelected({{ $topic->id }})" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <input
                                    type="checkbox"
                                    name="topic_ids[]"
                                    value="{{ $topic->id }}"
                                    :checked="isSelected({{ $topic->id }})"
                                    class="hidden">
                                <div>
                                    <span class="text-sm font-bold text-slate-800">{{ $topic->title }}</span>
                                    <span class="ml-1.5 text-xs text-slate-400 font-medium">Kelas {{ $topic->kelas_level }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

            {{-- Right: Ringkasan --}}
            <div class="lg:col-span-1">
                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm sticky top-6 space-y-4">
                    <h3 class="text-base font-black text-slate-800">Ringkasan</h3>

                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-slate-400 text-xs font-medium">Nama kelas</p>
                            <p class="font-bold text-slate-800 truncate" x-text="classNameLabel"></p>
                        </div>
                        <div>
                            <p class="text-slate-400 text-xs font-medium">Tingkatan</p>
                            <p class="font-bold text-slate-800" x-text="kelasLabel"></p>
                        </div>
                        <div>
                            <p class="text-slate-400 text-xs font-medium">Topik terpilih</p>
                            <p class="font-bold text-slate-800" x-text="topikTerpilih + ' topik'"></p>
                        </div>
                    </div>

                    <div class="bg-[#FFF8EC] border border-amber-200 rounded-xl p-3">
                        <p class="text-xs text-amber-700 font-medium">Kode gabung siswa dibuat otomatis setelah kelas disimpan.</p>
                    </div>

                    <button
                        type="submit"
                        class="w-full py-3 bg-[#056B48] hover:bg-[#044d34] text-white text-sm font-bold rounded-xl transition">
                        Buat kelas &amp; dapatkan kode
                    </button>

                    <a href="{{ route('guru.classes.index') }}"
                       class="block w-full py-3 border border-slate-200 text-slate-600 text-sm font-bold rounded-xl text-center hover:bg-slate-50 transition">
                        Batal
                    </a>
                </div>
            </div>

        </div>
    </form>

</div>
@endsection
