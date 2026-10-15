{{-- resources/views/partials/footer.blade.php --}}
@php
$stripe = ['#FF3B30', '#FF9500', '#F5B21B', '#10B981', '#3478F6', '#7C3AED'];
$chips = [
    ['sym' => '+', 'bg' => '#FF3B30', 'text' => '#FFFFFF', 'rot' => '-rotate-6'],
    ['sym' => '−', 'bg' => '#FF9500', 'text' => '#FFFFFF', 'rot' => 'rotate-6'],
    ['sym' => '×', 'bg' => '#F5B21B', 'text' => '#0F172A', 'rot' => '-rotate-6'],
    ['sym' => '÷', 'bg' => '#10B981', 'text' => '#FFFFFF', 'rot' => 'rotate-6'],
    ['sym' => '=', 'bg' => '#3478F6', 'text' => '#FFFFFF', 'rot' => '-rotate-6'],
];
@endphp

<footer class="relative mt-auto border-t border-amber-200/70 bg-[#FFFDF9]/95 backdrop-blur-sm">
    {{-- Garis warna-warni presisi --}}
    <div class="flex h-1.5 w-full overflow-hidden" aria-hidden="true">
        @foreach ($stripe as $c)
            <div class="flex-1" style="background-color: {{ $c }}"></div>
        @endforeach
    </div>

    <div class="mx-auto flex max-w-4xl flex-col items-center gap-3.5 px-6 py-6 sm:py-8 text-center">
        {{-- Logo Brand --}}
        <a href="{{ route('home') }}" aria-label="RoboMath" class="inline-flex items-center transition-transform duration-200 hover:scale-105 active:scale-95">
            <img src="{{ asset('images/logo.png') }}" alt="RoboMath" class="h-8 sm:h-9 w-auto">
        </a>

        {{-- Simbol matematika ceria (Chips seragam & berpenampilan rapi) --}}
        <div class="flex items-center justify-center gap-2 sm:gap-2.5 my-0.5" aria-hidden="true">
            @foreach ($chips as $chip)
                <span 
                    class="inline-flex h-8 w-8 sm:h-9 sm:w-9 items-center justify-center rounded-xl text-sm sm:text-base font-black shadow-xs transition-all duration-200 hover:-translate-y-1 hover:scale-110 cursor-default select-none {{ $chip['rot'] }}"
                    style="background-color: {{ $chip['bg'] }}; color: {{ $chip['text'] }};"
                >
                    {{ $chip['sym'] }}
                </span>
            @endforeach
        </div>

        {{-- Teks Hak Cipta & Deskripsi --}}
        <p class="max-w-xl text-xs sm:text-[13px] font-medium leading-relaxed text-slate-500">
            &copy; {{ date('Y') }} <strong class="font-extrabold text-slate-900">RoboMath</strong>  - Aplikasi Web Berbasis AI untuk Pengembangan Pembelajaran Matematika Anak Sekolah Dasar
        </p>
    </div>
</footer>
