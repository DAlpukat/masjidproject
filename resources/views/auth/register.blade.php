<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun - Transparansi Dana</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-register {
            background-image: url('https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExODh0ZzNqOHp2enZtcXByazhqOGR1bjNrNHJyYnFyMXB3MGZxdWd6MyZlcD12MV9naWZzX3NlYXJjaCZjdD1n/igyfXHSWGHZME0PLvr/giphy.gif');
            background-size: cover; background-position: center;
        }
    </style>
</head>
<body class="antialiased text-white min-h-screen relative overflow-x-hidden">
    <div class="fixed inset-0 bg-register z-[-1]"></div>
    <div class="bg-overlay"></div>

    <div class="min-h-screen flex flex-col items-center justify-center p-6 lg:p-12">
        <div class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
            
            <div class="space-y-6 lg:pr-10 reveal active">
                <span class="px-3 py-1 rounded-full border border-white/20 bg-white/5 text-[10px] lg:text-xs font-bold tracking-widest uppercase text-gray-300">
                    Bergabunglah Bersama Kami
                </span>
                <h1 class="text-3xl lg:text-5xl font-bold leading-tight">
                    Mulai Langkah <br>
                    <span class="text-gradient-mono">Transparansi</span>
                </h1>
                <p class="text-base lg:text-lg text-gray-400 font-light leading-relaxed max-w-md">
                    Daftarkan diri Anda untuk mengakses data kas secara real-time dan akuntabel.
                </p>
            </div>

            <div class="w-full">
                <div class="glass-card p-6 md:p-10 rounded-3xl border-t border-white/20 relative shadow-2xl">
                    <div class="mb-6 text-center lg:text-left">
                        <h2 class="text-2xl font-bold">Buat Akun</h2>
                        <p class="text-sm text-gray-400 mt-1">Lengkapi data diri Anda.</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="text-xs font-medium text-gray-400 ml-1">Username</label>
                            <input type="text" name="name" required class="w-full bg-black/40 border border-white/10 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-white/20 outline-none transition-all" placeholder="Username">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-400 ml-1">Email</label>
                            <input type="email" name="email" required class="w-full bg-black/40 border border-white/10 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-white/20 outline-none transition-all" placeholder="email@anda.com">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-400 ml-1">Password</label>
                            <div class="relative">
                                <input id="password" type="password" name="password" required class="w-full bg-black/40 border border-white/10 text-white rounded-xl px-4 py-3 pr-12 focus:ring-2 focus:ring-white/20 outline-none transition-all" placeholder="••••••••">
                                <button type="button" onclick="togglePassword('password', this)" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-400 ml-1">Konfirmasi Password</label>
                            <div class="relative">
                                <input id="password_confirmation" type="password" name="password_confirmation" required class="w-full bg-black/40 border border-white/10 text-white rounded-xl px-4 py-3 pr-12 focus:ring-2 focus:ring-white/20 outline-none transition-all" placeholder="••••••••">
                                <button type="button" onclick="togglePassword('password_confirmation', this)" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="w-full btn-monochrome py-4 mt-4 shadow-lg">Daftar Sekarang</button>
                        <p class="text-center text-sm text-gray-400 mt-4">Sudah punya akun? <a href="{{ route('login') }}" class="text-white font-semibold hover:underline">Masuk</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('svg');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }
    </script>
    
    <a href="/" class="fixed top-6 left-6 z-[100] group flex items-center gap-3 px-4 py-2 rounded-full glass-card border border-white/10 hover:bg-white hover:text-black transition-all duration-300">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
    </svg>
    <span class="text-sm font-medium">Kembali</span>
</a>
</body>
</html>