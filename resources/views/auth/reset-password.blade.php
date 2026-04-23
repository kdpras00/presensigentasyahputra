@extends('layouts.guest')

@section('content')
<div class="relative min-h-screen flex items-center justify-center overflow-hidden bg-[#F3F4F6] selection:bg-[#DFFF00]">
    
    <!-- Container -->
    <div class="relative z-10 w-full max-w-5xl flex flex-col md:flex-row bg-white rounded-[2rem] shadow-2xl overflow-hidden mx-4 sm:mx-8">
        
        <!-- Left Side: Branding -->
        <div class="w-full md:w-5/12 p-10 lg:p-14 flex flex-col items-start justify-center bg-[#345344] text-white relative overflow-hidden group">
            <div class="relative z-10">
                <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center mb-8 shadow-sm">
                    <svg class="w-7 h-7 text-[#DFFF00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl lg:text-4xl font-extrabold tracking-tight mb-4 text-white leading-tight">Buat Sandi Baru.</h1>
                <p class="text-white/70 font-medium leading-relaxed">Silakan masukkan kata sandi baru Anda untuk mengamankan akun.</p>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="w-full md:w-7/12 p-10 lg:p-14 sm:pl-16 flex flex-col justify-center bg-white">
            <div class="max-w-md w-full mx-auto md:mx-0">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Pembaruan Sandi</h2>

                <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email Address -->
                    <div class="space-y-1.5">
                        <label for="email" class="block text-sm font-bold text-gray-700 ml-1">Email Anda</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-[#DFFF00] transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" required readonly
                                class="block w-full pl-11 pr-4 py-4 bg-gray-100 border-transparent rounded-xl text-gray-500 font-medium focus:outline-none cursor-not-allowed" 
                                value="{{ old('email', $email) }}">
                        </div>
                        @error('email')
                            <p class="text-sm text-red-500 ml-1 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="space-y-1.5">
                        <label for="password" class="block text-sm font-bold text-gray-700 ml-1">Sandi Baru</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-[#DFFF00] transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" name="password" type="password" required autofocus
                                class="block w-full pl-11 pr-4 py-4 bg-[#345344] border-transparent rounded-xl text-white placeholder-gray-400 italic focus:ring-4 focus:ring-[#DFFF00]/30 focus:border-[#DFFF00] transition-all duration-300" 
                                placeholder="••••••••">
                        </div>
                        @error('password')
                            <p class="text-sm text-red-500 ml-1 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-1.5">
                        <label for="password_confirmation" class="block text-sm font-bold text-gray-700 ml-1">Konfirmasi Sandi Baru</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-[#DFFF00] transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                class="block w-full pl-11 pr-4 py-4 bg-[#345344] border-transparent rounded-xl text-white placeholder-gray-400 italic focus:ring-4 focus:ring-[#DFFF00]/30 focus:border-[#DFFF00] transition-all duration-300" 
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full flex justify-center items-center py-4 px-4 rounded-xl text-sm font-bold text-white bg-[#345344] hover:bg-gray-800 focus:outline-none focus:ring-4 focus:ring-[#DFFF00]/30 transition-all duration-300 shadow-md">
                            SIMPAN SANDI BARU
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
