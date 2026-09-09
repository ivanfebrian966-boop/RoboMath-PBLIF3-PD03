<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'AlgoKids - Pemikiran Logika & Algoritma Anak SD' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-indigo-50 via-sky-50 to-pink-50 font-sans text-slate-800 antialiased min-h-screen flex flex-col justify-between">

    @yield('content')

    <footer class="py-6 text-center text-sm text-slate-500 font-medium">
        <p>© {{ date('Y') }} <strong>AlgoKids</strong> - Dikembangkan untuk Pembelajaran Logika & Algoritma Interaktif SD 🌈</p>
    </footer>

</body>
</html>
