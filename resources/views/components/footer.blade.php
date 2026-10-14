<footer class="bg-white/90 backdrop-blur-md border-t-2 border-amber-200/70 mt-auto py-4 text-slate-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
        
        <!-- Left: Brand Logo & Subtitle -->
        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}" class="flex items-center hover:opacity-85 transition">
                <img src="{{ asset('images/logo.png') }}" alt="RoboMath" class="h-6 w-auto">
            </a>
            <span class="text-slate-300 hidden md:inline">|</span>
            <span class="text-slate-500 font-semibold hidden md:inline">Pembelajaran Matematika Cerdas Berbasis AI untuk Siswa SD</span>
        </div>

        <!-- Right: Status Indicator & Copyright -->
        <div class="flex flex-wrap items-center justify-center sm:justify-end gap-3 text-slate-600 font-medium">
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-800 text-[11px] font-black border border-emerald-200/80">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span>Sistem Aktif</span>
            </div>
            <span>&copy; {{ date('Y') }} <strong class="text-slate-700 font-bold">RoboMath</strong></span>
            <span class="text-[10px] font-black text-amber-800 bg-amber-100/70 px-2 py-0.5 rounded-md border border-amber-200">
                PBL IF3-PD03
            </span>
        </div>

    </div>
</footer>
