@extends('layouts.guest')

@section('content')
<div class="relative min-h-screen flex items-center justify-center overflow-hidden bg-[#F3F4F6] selection:bg-[#345344] selection:text-white">
    
    <!-- Login Container -->
    <div class="relative z-10 w-full max-w-5xl flex flex-col md:flex-row bg-white rounded-[2rem] shadow-2xl overflow-hidden mx-4 sm:mx-8">
        
        <!-- Left Side: Branding & Welcome -->
        <div class="w-full md:w-5/12 p-10 lg:p-14 flex flex-col items-center justify-center bg-[#345344] text-white relative overflow-hidden group text-center">
            
            <!-- Background Decorations -->
            <div class="absolute top-0 -left-4 w-72 h-72 bg-white/5 rounded-full mix-blend-overlay filter blur-3xl opacity-70 animate-blob"></div>
            <div class="absolute -bottom-8 -right-4 w-72 h-72 bg-white/5 rounded-full mix-blend-overlay filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-white/5 rounded-full mix-blend-overlay filter blur-[100px] opacity-50"></div>

            <div class="relative z-10 flex flex-col items-center">
                <div class="mb-8 relative group/logo">
                    <div class="absolute inset-0 bg-white/10 blur-2xl rounded-full scale-110 group-hover/logo:scale-150 transition-transform duration-700"></div>
                    <img src="{{ asset('images/gentalogoico.png') }}" alt="Logo" class="relative z-10 w-28 h-28 object-contain drop-shadow-[0_20px_50px_rgba(0,0,0,0.3)] transform transition-transform duration-500 group-hover/logo:scale-110" width="112" height="112">
                </div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight mb-4 text-white leading-none whitespace-nowrap">SMA Genta Syaputra.</h1>
                <p class="text-white/70 font-medium leading-relaxed max-w-xs mx-auto">Sistem Presensi Digital Terpadu.</p>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="w-full md:w-7/12 p-10 lg:p-14 sm:pl-16 flex flex-col justify-center bg-white">
            <div class="max-w-md w-full mx-auto md:mx-0">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Masuk ke Akun</h2>
                <p class="text-gray-500 mb-8 font-medium">Silakan masukkan kredensial Anda untuk melanjutkan.</p>

                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Input Username -->
                    <div class="space-y-1.5">
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-white transition-colors">
                                <svg class="h-5 w-5" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input id="username" name="username" type="text" aria-label="NISN atau NIP" required 
                                class="block w-full pl-11 pr-4 py-4 bg-[#345344] border-transparent rounded-xl text-white placeholder-gray-400 focus:ring-4 focus:ring-white/10 focus:border-white/20 transition-all duration-300" 
                                placeholder="NISN / NIP">
                        </div>
                        @error('username')
                            <p class="text-sm text-red-500 ml-1 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Input Password -->
                    <div class="space-y-1.5">
                        
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-white transition-colors">
                                <svg class="h-5 w-5" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" name="password" type="password" aria-label="Kata Sandi" autocomplete="current-password" required 
                                class="block w-full pl-11 pr-12 py-4 bg-[#345344] border-transparent rounded-xl text-white placeholder-gray-400 focus:ring-4 focus:ring-white/10 focus:border-white/20 transition-all duration-300" 
                                placeholder="Password">
                            <button type="button" onclick="togglePassword()" aria-label="Tampilkan kata sandi" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-white transition-colors focus:outline-none">
                                <svg id="eye-icon" aria-hidden="true" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eye-off-icon" aria-hidden="true" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                        <div class="flex items-center justify-end">
                            <a href="{{ route('password.request') }}" class="text-sm font-semibold text-[#345344] transition-colors">Lupa sandi?</a>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" class="w-full flex justify-center items-center gap-2 py-4 px-4 rounded-xl text-sm font-bold text-white bg-[#345344] hover:bg-gray-800 focus:outline-none focus:ring-4 focus:ring-white/10 transition-all duration-300">
                            MASUK
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes blob {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
    }
    .animate-blob {
        animation: blob 10s infinite alternate;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    .animation-delay-4000 {
        animation-delay: 4s;
    }
</style>
@endsection

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');
        const eyeOffIcon = document.getElementById('eye-off-icon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.add('hidden');
            eyeOffIcon.classList.remove('hidden');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('hidden');
            eyeOffIcon.classList.add('hidden');
        }
    }
</script>
