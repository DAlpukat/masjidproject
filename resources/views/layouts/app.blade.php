<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-800 bg-app-theme">
        
        <!-- NAVIGATION: Pakai style Glass (Fixed Top) -->
        <div class="fixed top-0 w-full z-50 nav-glass shadow-sm transition-all duration-300">
            @include('layouts.navigation')
        </div>

        <!-- Spacer agar konten tidak tertutup navbar fixed -->
        <div class="h-16"></div>

        <!-- Page Heading (Jika ada) -->
        @isset($header)
            <header class="bg-white/50 backdrop-blur-sm border-b border-white/50 mt-6 mb-6">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="min-h-[calc(100vh-4rem)] pb-10">
            @yield('content')
        </main>

        <!-- Footer Kecil (Optional) -->
        <footer class="py-6 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} Sistem Management Layanan
        </footer>
    </body>
</html>