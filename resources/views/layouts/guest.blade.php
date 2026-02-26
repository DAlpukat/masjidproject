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
    <body class="font-sans text-gray-900 antialiased bg-app-theme">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-10 sm:pt-0 px-4">
            
            <!-- Logo / Branding -->
            <div class="mb-8 text-center sm:mb-0">
                <a href="/" class="inline-flex items-center justify-center w-20 h-20 bg-white/80 backdrop-blur-md rounded-3xl shadow-xl border border-white/50 hover:scale-105 transition-transform duration-300">
                    <!-- Kamu bisa mengganti ini dengan <x-application-logo /> jika ingin logo SVG asli -->
                    <span class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-purple-600">
                        ML
                    </span>
                </a>
                <h1 class="mt-6 text-3xl font-extrabold text-gray-800 tracking-tight">
                    Selamat Datang
                </h1>
                <p class="mt-2 text-sm text-gray-500">
                    Masuk untuk mengelola tempat layananmu.
                </p>
            </div>

            <!-- Card Login/Register (Glass Style) -->
            <div class="w-full sm:max-w-md mt-6 glass-card rounded-3xl p-8 sm:p-10 shadow-2xl border border-white">
                {{ $slot }}
            </div>
            
            <!-- Footer -->
            <p class="mt-8 text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} Sistem Management Layanan
            </p>
        </div>
    </body>
</html>