<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <title>@yield('title', '3Gites-1975') — Class of 1975</title>

    {{-- Google Fonts: Plus Jakarta Sans (body) + Cormorant Garamond (display) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="font-body text-slate-700 bg-cream antialiased min-h-screen flex flex-col">

    {{-- Skip to content --}}
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[100] focus:bg-purple-900 focus:text-white focus:px-4 focus:py-2 focus:rounded-lg focus:shadow-lg text-sm font-semibold">
        Skip to content
    </a>

    {{-- Navigation --}}
    <x-nav />

    {{-- Flash messages (below fixed nav) --}}
    <div class="pt-16">
        <x-flash />
    </div>

    {{-- Main content --}}
    <main id="main-content" class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <x-footer />

    @stack('scripts')
</body>
</html>
