@extends('layouts.guest')

@section('content')
<div class="relative min-h-screen flex items-center justify-center overflow-hidden bg-[#F3F4F6] selection:bg-[#DFFF00]">
    
    <!-- Login Container -->
    <div class="relative z-10 w-full max-w-5xl flex flex-col md:flex-row bg-white rounded-[2rem] shadow-2xl overflow-hidden mx-4 sm:mx-8">
        
        <!-- Left Side: Branding & Welcome -->
        <div class="w-full md:w-5/12 p-10 lg:p-14 flex flex-col items-start justify-center bg-[#345344] text-white relative overflow-hidden group">
            
            <div class="relative z-10">
                <h1 class="text-3xl lg:text-4xl font-extrabold tracking-tight mb-4 text-white leading-tight">SMA Genta Syaputra.</h1>
                <p class="text-white/70 font-medium leading-relaxed">Sistem Presensi Digital Terpadu.</p>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="w-full md:w-7/12 p-10 lg:p-14 sm:pl-16 flex flex-col justify-center bg-white">
            <div class="max-w-md w-full mx-auto md:mx-0">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Masuk ke Akun</h2>
                <p class="text-gray-500 mb-8 font-medium">Silakan masukkan kredensial Anda untuk melanjutkan.</p>

                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Input Email/NIS -->
                    <div class="space-y-1.5">
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-[#DFFF00] transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input id="email" name="email" type="text" autocomplete="username" required 
                                class="block w-full pl-11 pr-4 py-4 bg-[#345344] border-transparent rounded-xl text-white placeholder-gray-400 italic focus:ring-4 focus:ring-[#DFFF00]/30 focus:border-[#DFFF00] transition-all duration-300" 
                                placeholder="Email/NIS/NIP">
                        </div>
                        @error('email')
                            <p class="text-sm text-red-500 ml-1 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Input Password -->
                    <div class="space-y-1.5">
                        
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-[#DFFF00] transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" name="password" type="password" autocomplete="current-password" required 
                                class="block w-full pl-11 pr-4 py-4 bg-[#345344] border-transparent rounded-xl text-white placeholder-gray-400 italic focus:ring-4 focus:ring-[#DFFF00]/30 focus:border-[#DFFF00] transition-all duration-300" 
                                placeholder="Password">
                        </div>
                        <div class="flex items-center justify-end">
                            <a href="{{ route('password.request') }}" class="text-sm font-semibold text-[#345344] transition-colors">Lupa sandi?</a>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" class="w-full flex justify-center items-center gap-2 py-4 px-4 rounded-xl text-sm font-bold text-white bg-[#345344] hover:bg-gray-800 focus:outline-none focus:ring-4 focus:ring-[#DFFF00]/30 transition-all duration-300">
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
