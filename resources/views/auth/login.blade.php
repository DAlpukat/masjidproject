<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - Transparansi Dana</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-login {
            background-image: url('https://media.giphy.com/media/v1.Y2lkPWVjZjA1ZTQ3anY3cXI0NHQ0MDUycjBvNmQ1bWt4Z2JhczUwc3BsYXhpZWFycWhiciZlcD12MV9naWZzX3RyZW5kaW5nJmN0PWc/tXL4FHPSnVJ0A/giphy.gif');
            background-size: cover; background-position: center;
        }
    </style>
</head>
<body class="antialiased text-white min-h-screen flex items-center justify-center p-6 relative">
    <div class="fixed inset-0 bg-login z-[-1]"></div>
    <div class="bg-overlay"></div>

    <div class="w-full max-w-md py-10">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold">Selamat Datang</h2>
            <p class="text-gray-400 mt-2">Masuk ke sistem manajemen kas</p>
        </div>

        <div class="glass-card p-8 rounded-3xl border border-white/10 relative shadow-2xl">
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                <div>
                    <label class="text-xs font-medium text-gray-400 ml-1">Email</label>
                    <input type="email" name="email" required autofocus class="w-full bg-black/40 border border-white/10 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-white/20 outline-none" placeholder="email@anda.com">
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-400 ml-1">Password</label>
                    <div class="relative">
                        <input id="password" type="password" name="password" required class="w-full bg-black/40 border border-white/10 text-white rounded-xl px-4 py-3 pr-12 focus:ring-2 focus:ring-white/20 outline-none" placeholder="••••••••">
                        <button type="button" onclick="togglePassword('password', this)" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500">
                             <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </button>
                    </div>
                </div>
                <button type="submit" class="w-full btn-monochrome py-4 shadow-lg">Masuk</button>
                <p class="text-center text-sm text-gray-400 mt-4">Belum punya akun? <a href="{{ route('register') }}" class="text-white font-semibold hover:underline">Daftar</a></p>
            </form>
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