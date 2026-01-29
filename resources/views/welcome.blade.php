<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Transparansi Dana - Integritas & Akuntabilitas</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="antialiased text-white min-h-screen flex flex-col relative overflow-x-hidden">

    <div class="bg-monochrome-gif"></div>
    <div class="bg-overlay"></div>

    <nav class="fixed w-full z-50 nav-glass top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center">
                    <span class="text-xl font-bold tracking-widest text-white uppercase border-b-2 border-transparent hover:border-white transition-all duration-300">
                        Transparansi<span class="text-gray-400">Dana</span>
                    </span>
                </div>

                <div class="flex items-center space-x-6">
                    @if (Route::has('login'))
                        <div class="hidden sm:flex items-center space-x-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="btn-monochrome text-sm">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="btn-secondary text-sm">Masuk</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn-monochrome text-sm">Daftar</a>
                                @endif
                            @endauth
                        </div>
                    @endif
                    
                    <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center border border-white/20 hover:bg-white hover:text-black transition-all duration-300 cursor-pointer group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow flex flex-col pt-20">
        
        <div class="min-h-[90vh] flex flex-col items-center justify-center px-4 text-center relative z-10">
            <div class="glass-card p-10 md:p-16 rounded-3xl max-w-4xl w-full reveal active">
                <span class="inline-block py-1 px-3 rounded-full bg-white/10 border border-white/20 text-xs font-bold tracking-widest uppercase mb-6 text-gray-300">
                    Sistem Manajemen Kas Terbuka
                </span>
                <h1 class="text-4xl md:text-6xl font-black mb-6 leading-tight">
                    Kejujuran dalam <br>
                    <span class="text-gradient-mono">Setiap Angka</span>
                </h1>
                <p class="text-lg md:text-xl text-gray-300 mb-10 max-w-2xl mx-auto leading-relaxed">
                    Kami berkomitmen untuk menyediakan platform transparansi dana yang akuntabel. 
                    Pantau arus kas, verifikasi laporan, dan bangun kepercayaan publik dengan data yang real-time.
                </p>
                
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="#about" class="btn-monochrome w-full sm:w-auto">
                        Pelajari Lebih Lanjut
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div id="about" class="py-24 px-4 relative z-10 max-w-7xl mx-auto w-full">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="glass-card p-8 rounded-2xl reveal delay-100 flex flex-col items-center text-center">
                    <div class="w-14 h-14 bg-white/10 rounded-full flex items-center justify-center mb-6 text-white text-2xl">
                        📊
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-white">Real-time Data</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Akses laporan keuangan secara langsung saat transaksi terjadi tanpa penundaan.
                    </p>
                </div>

                <div class="glass-card p-8 rounded-2xl reveal delay-200 flex flex-col items-center text-center">
                    <div class="w-14 h-14 bg-white/10 rounded-full flex items-center justify-center mb-6 text-white text-2xl">
                        🛡️
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-white">Keamanan Terjamin</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Enkripsi tingkat tinggi memastikan data integritas dana tetap terjaga dari manipulasi.
                    </p>
                </div>

                <div class="glass-card p-8 rounded-2xl reveal delay-300 flex flex-col items-center text-center">
                    <div class="w-14 h-14 bg-white/10 rounded-full flex items-center justify-center mb-6 text-white text-2xl">
                        🔍
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-white">Transparansi Total</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Publik dapat mengakses rincian penggunaan anggaran hingga ke sen terakhir.
                    </p>
                </div>
            </div>

            <div class="mt-16 reveal">
                <div class="glass-card p-10 rounded-3xl border-l-4 border-white text-center md:text-left md:flex items-center gap-8">
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold mb-4">Membangun Kepercayaan</h2>
                        <p class="text-gray-300">
                            "Transparansi bukan hanya tentang angka, tetapi tentang tanggung jawab moral kepada publik."
                        </p>
                    </div>
                    <div class="mt-6 md:mt-0 flex-shrink-0">
                         @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-monochrome">Bergabung Sekarang</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </main>

    <footer class="relative z-10 border-t border-white/10 bg-black/80 backdrop-blur-lg mt-auto">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-gray-400 text-sm">
                &copy; {{ date('Y') }} AllStarCmp. All rights reserved.
            </div>
            
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <span>Developed by</span>
                <span class="text-white font-semibold px-2 py-1 bg-white/10 rounded-md border border-white/10 hover:bg-white hover:text-black transition-colors cursor-default">
                    AllStarCmp
                </span>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function untuk mendeteksi elemen saat di-scroll
            const revealElements = document.querySelectorAll('.reveal');

            const revealOnScroll = () => {
                const windowHeight = window.innerHeight;
                const elementVisible = 150; // Jarak trigger dari bawah

                revealElements.forEach((reveal) => {
                    const elementTop = reveal.getBoundingClientRect().top;
                    if (elementTop < windowHeight - elementVisible) {
                        reveal.classList.add('active');
                    }
                });
            };

            // Jalankan saat load & scroll
            window.addEventListener('scroll', revealOnScroll);
            revealOnScroll(); // Trigger sekali di awal jika ada elemen yang sudah terlihat
        });
    </script>
</body>
</html>