@extends('layouts.guest')

@section('content')
<div class="relative min-h-screen flex items-center justify-center overflow-hidden bg-[#F3F4F6] selection:bg-[#345344] selection:text-white">
    
    <!-- Container -->
    <div class="relative z-10 w-full max-w-5xl flex flex-col md:flex-row bg-white rounded-[2rem] shadow-2xl overflow-hidden mx-4 sm:mx-8">
        
        <!-- Left Side: Branding -->
        <div class="w-full md:w-5/12 p-10 lg:p-14 flex flex-col items-start justify-center bg-[#345344] text-white relative overflow-hidden group">
            <div class="relative z-10">
                <h1 class="text-3xl lg:text-4xl font-extrabold tracking-tight mb-4 text-white leading-tight">Lupa Sandi?</h1>
                <p class="text-white/70 font-medium leading-relaxed">Jangan khawatir. Masukkan email Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.</p>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="w-full md:w-7/12 p-10 lg:p-14 sm:pl-16 flex flex-col justify-center bg-white">
            <div class="max-w-md w-full mx-auto md:mx-0">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Atur Ulang Sandi</h2>
                <p class="text-gray-500 mb-8 font-medium">Tautan reset akan dikirimkan ke email terdaftar.</p>

                @if (session('status'))
                    <div class="mb-6 p-4 text-sm text-[#345344] bg-green-50 rounded-xl font-bold flex items-start border border-green-100">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0 text-[#345344]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Email Address -->
                    <div class="space-y-1.5">
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-white transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required autofocus
                                class="block w-full pl-11 pr-4 py-4 bg-[#345344] border-transparent rounded-xl text-white placeholder-gray-400 focus:ring-4 focus:ring-white/10 focus:border-white/20 transition-all duration-300" 
                                placeholder="Email Terdaftar" value="{{ old('email') }}">
                        </div>
                        @error('email')
                            <p class="text-sm text-red-500 ml-1 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 flex flex-col space-y-4">
                        <button type="submit" class="w-full flex justify-center items-center py-4 px-4 rounded-xl text-sm font-bold text-white bg-[#345344] hover:bg-gray-800 focus:outline-none focus:ring-4 focus:ring-white/10 transition-all duration-300">
                            KIRIM TAUTAN RESET
                        </button>
                        <a href="{{ route('login') }}" class="w-full flex justify-center items-center py-4 px-4 rounded-xl text-sm font-bold text-[#345344] bg-[#F3F4F6] hover:bg-gray-200 transition-all duration-300">
                            KEMBALI KE LOGIN
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
