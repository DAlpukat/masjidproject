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
            background-image: url('https://media2.giphy.com/media/v1.Y2lkPTc5MGI3NjExN2xnYzFnazVvZ3RyZDA2azFtOTN2NzltMTNrazM2NmduYzV6OHNscSZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/hVEBWRInEvNOEVS18i/giphy.gif');
            background-size: cover; background-position: center;
        }
        .text-gradient-mono {
            background: linear-gradient(to right, #ffffff, #a3a3a3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="antialiased text-white min-h-screen relative overflow-x-hidden">
    <div class="fixed inset-0 bg-register z-[-1]"></div>
    <div class="bg-overlay"></div>

    <div class="min-h-screen flex flex-col items-center justify-center p-6 lg:p-12">
        <div class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
            
            <div class="space-y-6 lg:pr-10">
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
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold">Buat Akun</h2>
                        <p class="text-sm text-gray-400 mt-1">Lengkapi data diri Anda di bawah ini.</p>
                    </div>

                    @if($errors->any())
                    <div class="mb-4 p-3 rounded-xl bg-red-500/10 border border-red-500/50 text-red-200 text-xs">
                        @foreach ($errors->all() as $error)
                            <p>• {{ $error }}</p>
                        @endforeach
                    </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" id="registerForm" class="space-y-4" novalidate>
                        @csrf
                        
                        <div class="group">
                            <label class="text-xs font-medium text-gray-400 ml-1">Username</label>
                            <input type="text" name="name" id="username" required 
                                class="w-full bg-black/40 border border-white/10 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-white/20 outline-none transition-all" 
                                placeholder="Username panggilan/samaran">
                            <p id="usernameError" class="error-message">Gunakan 3-15 karakter. Hanya huruf, angka, titik, atau underscore tanpa spasi.</p>
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-400 ml-1">Email</label>
                            <input type="email" name="email" id="email" required 
                                class="w-full bg-black/40 border border-white/10 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-white/20 outline-none transition-all" 
                                placeholder="nama@gmail.com">
                            <p id="emailError" class="error-message">Format email tidak valid. Wajib menggunakan @gmail.com</p>
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-400 ml-1">Password</label>
                            <div class="relative">
                                <input id="password" type="password" name="password" required 
                                    class="w-full bg-black/40 border border-white/10 text-white rounded-xl px-4 py-3 pr-12 focus:ring-2 focus:ring-white/20 outline-none transition-all" 
                                    placeholder="••••••••">
                                <button type="button" onclick="togglePassword('password', this)" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </button>
                            </div>
                            <div class="strength-meter">
                                <div id="strengthBar" class="strength-bar"></div>
                            </div>
                            <p id="strengthText" class="text-[10px] mt-1 text-gray-500 text-right italic">Gunakan kombinasi Kapital, Angka, & Karakter Unik</p>
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-400 ml-1">Konfirmasi Password</label>
                            <div class="relative">
                                <input id="password_confirmation" type="password" name="password_confirmation" required 
                                    class="w-full bg-black/40 border border-white/10 text-white rounded-xl px-4 py-3 pr-12 focus:ring-2 focus:ring-white/20 outline-none transition-all" 
                                    placeholder="Ulangi password">
                                <button type="button" onclick="togglePassword('password_confirmation', this)" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </button>
                            </div>
                            <p id="matchError" class="error-message">Password tidak sama. Pastikan input identik dengan password di atas.</p>
                        </div>

                        <button type="submit" class="w-full btn-monochrome py-4 mt-4 shadow-lg">Daftar Sekarang</button>
                        
                        <p class="text-center text-sm text-gray-400 mt-4">
                            Sudah punya akun? <a href="{{ route('login') }}" class="text-white font-semibold hover:underline">Masuk</a>
                        </p>
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

        const form = document.getElementById('registerForm');
        const username = document.getElementById('username');
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const confirm = document.getElementById('password_confirmation');

        // Logic Visualisasi Kesalahan (Tampilkan Pesan & Border Merah)
        const showError = (input, errorId, isVisible) => {
            const errorElement = document.getElementById(errorId);
            if (isVisible) {
                input.classList.add('input-error');
                errorElement.style.display = 'block';
            } else {
                input.classList.remove('input-error');
                errorElement.style.display = 'none';
            }
        };

        // Indikator Kekuatan Password Real-time
        password.addEventListener('input', () => {
            const val = password.value;
            const bar = document.getElementById('strengthBar');
            const text = document.getElementById('strengthText');
            let score = 0;

            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            bar.className = 'strength-bar';
            if (val.length === 0) {
                text.innerText = "Gunakan kombinasi Kapital, Angka, & Karakter Unik";
            } else if (score <= 2) {
                bar.classList.add('strength-weak');
                text.innerText = "Kekuatan: Lemah";
            } else if (score === 3) {
                bar.classList.add('strength-medium');
                text.innerText = "Kekuatan: Sedang";
            } else {
                bar.classList.add('strength-strong');
                text.innerText = "Kekuatan: Sangat Kuat";
            }
        });

        // Validasi saat Submit
        form.addEventListener('submit', (e) => {
            let isValid = true;

            // 1. Validasi Username (3-15 Karakter, Alphanumeric/dot/underscore)
            const userRegex = /^[a-zA-Z0-9._]{3,15}$/;
            if (!userRegex.test(username.value)) {
                showError(username, 'usernameError', true);
                isValid = false;
            } else {
                showError(username, 'usernameError', false);
            }

            // 2. Validasi Email Gmail
            if (!email.value.toLowerCase().endsWith('@gmail.com')) {
                showError(email, 'emailError', true);
                isValid = false;
            } else {
                showError(email, 'emailError', false);
            }

            // 3. Validasi Kecocokan Password
            if (confirm.value !== password.value || confirm.value === "") {
                showError(confirm, 'matchError', true);
                isValid = false;
            } else {
                showError(confirm, 'matchError', false);
            }

            if (!isValid) e.preventDefault();
        });
    </script>
</body>
</html>