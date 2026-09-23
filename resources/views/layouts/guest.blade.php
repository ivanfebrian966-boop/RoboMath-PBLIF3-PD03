<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'RoboMath - Aplikasi Web Berbasis AI untuk Pengembangan Pembelajaran Matematika Anak Sekolah Dasar' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF6EF] font-sans text-slate-800 antialiased min-h-screen flex flex-col justify-between selection:bg-amber-200 selection:text-amber-900">

    @yield('content')

    <footer class="py-6 text-center text-sm text-slate-500 font-medium">
        <div class="flex flex-col items-center gap-2">
            <img src="{{ asset('images/logo.png') }}" alt="RoboMath" class="h-6 w-auto hover:scale-105 transition-transform duration-200">
            <div class="flex items-center gap-1.5 text-xs text-amber-900/60 font-bold">
                <span class="w-2 h-2 rounded-full bg-red-400 inline-block"></span>
                <span class="w-2 h-2 rounded-full bg-orange-400 inline-block"></span>
                <span class="w-2 h-2 rounded-full bg-amber-400 inline-block"></span>
                <span class="w-2 h-2 rounded-full bg-emerald-400 inline-block"></span>
                <span class="w-2 h-2 rounded-full bg-teal-400 inline-block"></span>
                <span class="w-2 h-2 rounded-full bg-blue-500 inline-block"></span>
            </div>
            <p>© {{ date('Y') }} <strong>RoboMath</strong> - Aplikasi Web Berbasis AI untuk Pengembangan Pembelajaran Matematika Anak Sekolah Dasar 🤖✨</p>
        </div>
    </footer>

</body>
</html>
