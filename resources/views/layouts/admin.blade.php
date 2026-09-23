<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Panel - RoboMath' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-slate-950 font-sans text-slate-100 antialiased min-h-screen flex">

    {{-- Sidebar --}}
    <aside class="w-64 flex-shrink-0 bg-slate-900 border-r border-slate-800 min-h-screen flex flex-col">
        {{-- Logo --}}
        <div class="p-6 border-b border-slate-800">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="RoboMath" class="h-8 w-auto">
                <div>
                    <div class="text-xs font-black text-amber-400 uppercase tracking-widest">Admin Panel</div>
                    <div class="text-sm font-bold text-white">RoboMath</div>
                </div>
            </a>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 p-4 space-y-1">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-amber-500 text-slate-900' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <span class="text-lg">📊</span> Dashboard
            </a>
            <a href="{{ route('admin.quizzes.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('admin.quizzes*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <span class="text-lg">🎯</span> Manajemen Soal
            </a>
            <a href="{{ route('admin.lessons.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('admin.lessons*') ? 'bg-emerald-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <span class="text-lg">📚</span> Manajemen Materi
            </a>
            <a href="{{ route('admin.reports.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('admin.reports*') ? 'bg-purple-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <span class="text-lg">📈</span> Laporan Performa
            </a>
            <a href="{{ route('admin.ai-recommendations.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('admin.ai-recommendations*') ? 'bg-rose-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <span class="text-lg">🤖</span> Rekomendasi AI
                @php $pendingAI = \App\Models\AiRecommendation::where('status','pending')->count(); @endphp
                @if($pendingAI > 0)
                    <span class="ml-auto bg-rose-500 text-white text-xs font-black px-2 py-0.5 rounded-full">{{ $pendingAI }}</span>
                @endif
            </a>
            <a href="{{ route('admin.users.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('admin.users*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <span class="text-lg">👥</span> Manajemen Pengguna
            </a>
        </nav>

        {{-- User info --}}
        <div class="p-4 border-t border-slate-800">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-full bg-amber-500 flex items-center justify-center font-black text-slate-900 text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-slate-500">Administrator</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left flex items-center gap-2 px-3 py-2 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-rose-400 text-xs font-semibold transition-all">
                    <span>🚪</span> Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col min-h-screen">
        {{-- Top bar --}}
        <header class="bg-slate-900 border-b border-slate-800 px-8 py-4 flex items-center justify-between">
            <div>
                <h1 class="text-lg font-black text-white">@yield('page-title', 'Dashboard')</h1>
                <p class="text-xs text-slate-400">@yield('page-subtitle', 'Panel Administrator RoboMath')</p>
            </div>
            <div class="text-sm text-slate-400">{{ now()->format('d M Y, H:i') }} WIB</div>
        </header>

        <main class="flex-1 p-8">
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-900/50 border border-emerald-700 text-emerald-300 font-semibold flex items-center gap-3">
                    <span class="text-xl">✅</span> {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-900/50 border border-rose-700 text-rose-300 font-semibold space-y-1">
                    @foreach($errors->all() as $error)
                        <div class="flex items-center gap-2"><span>❌</span> {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
