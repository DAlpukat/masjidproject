<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Override font default */
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
        </style>
    </head>
    
    <body class="font-sans antialiased text-white/90 min-h-screen relative overflow-x-hidden">
        
        <div class="bg-monochrome-gif"></div>
        <div class="bg-overlay"></div>

        <div class="fixed top-0 w-full z-50 nav-glass shadow-sm transition-all duration-300">
            @include('layouts.navigation')
        </div>

        <div class="h-20"></div>

        <div class="relative z-10 flex flex-col min-h-[calc(100vh-5rem)]">
            
            @isset($header)
                <header class="mb-8">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <div class="glass-card px-6 py-4 rounded-2xl flex items-center justify-between">
                            {{ $header }}
                        </div>
                    </div>
                </header>
            @endisset

            <main class="flex-grow px-4 sm:px-6 lg:px-8">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                    {{ $slot ?? '' }}
                </div>
            </main>

            <footer class="py-6 text-center text-xs text-gray-400 mt-10 border-t border-white/10">
                &copy; {{ date('Y') }} Sistem Management Layanan | AllStarCmp
            </footer>
        </div>

    </body>
</html>