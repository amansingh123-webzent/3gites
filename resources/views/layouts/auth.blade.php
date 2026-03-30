<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sign In') — 3Gites-1975</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-navy-950 font-lato antialiased">

    <div class="min-h-screen flex flex-col justify-center items-center px-4 py-12">

        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 mb-8">
            <div class="w-16 h-16 bg-gold-500 rounded-full flex items-center justify-center shadow-lg">
                <span class="font-playfair font-bold text-navy-900 text-2xl leading-none">3G</span>
            </div>
            <span class="font-playfair text-white text-2xl font-bold">3Gites-1975</span>
            <span class="text-gold-400 text-xs tracking-widest uppercase">Class of 1975</span>
        </a>

        <!-- Card -->
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">

            <!-- Gold top bar -->
            <div class="h-1.5 bg-gradient-to-r from-gold-400 via-gold-500 to-gold-400"></div>

            <div class="px-8 py-8">
                @yield('content')
            </div>
        </div>

        <!-- Back to site link -->
        <a href="{{ route('home') }}" class="mt-6 text-slate-500 hover:text-gold-400 text-sm transition-colors">
            ← Back to site
        </a>
    </div>

</body>
</html>
