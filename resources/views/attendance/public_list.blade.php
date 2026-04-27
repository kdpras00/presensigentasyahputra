<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title }} - SMA Genta Syaputra</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .glass-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }
        </style>
    </head>
    <body class="antialiased bg-[#F1F5F9] min-h-screen overflow-hidden">
        <div id="main-content" class="blur-md pointer-events-none select-none transition-all duration-700">
        <!-- Header -->
        <div class="relative overflow-hidden {{ $theme === 'green' ? 'bg-[#345344]' : 'bg-red-900' }} py-16 lg:py-24">
            <div class="absolute top-0 left-0 w-full h-full opacity-10">
                <svg width="100%" height="100%" fill="none" viewBox="0 0 100 100">
                    <circle cx="20" cy="20" r="30" stroke="white" stroke-width="0.5"/>
                    <circle cx="80" cy="80" r="40" stroke="white" stroke-width="0.5"/>
                </svg>
            </div>
            
            <div class="max-w-7xl mx-auto px-6 relative z-10">
                <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-8">
                    <div>
                        <a href="{{ url('/') }}" class="inline-flex items-center text-white/50 hover:text-white mb-6 transition-colors text-xs font-black uppercase tracking-widest">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Kembali ke Beranda
                        </a>
                        <h1 class="text-4xl md:text-6xl font-black text-white tracking-tighter uppercase">{{ $title }}</h1>
                        <p class="text-white/60 text-lg mt-4 font-medium max-w-xl leading-relaxed">{{ $subtitle }}</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md px-8 py-4 rounded-3xl border border-white/20">
                        <span class="text-white/40 text-[10px] font-black uppercase tracking-[0.3em] block mb-1">Total Siswa</span>
                        <span class="text-white text-4xl font-black">{{ $students->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="max-w-7xl mx-auto px-6 -mt-16 pb-32 relative z-20">
            @if($students->isEmpty())
                <div class="bg-white rounded-[4rem] p-20 lg:p-32 text-center shadow-[0_40px_100px_-20px_rgba(0,0,0,0.05)] border border-white relative overflow-hidden group">
                    <!-- Background Decor for Empty State -->
                    <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-b from-gray-50/50 to-white pointer-events-none"></div>
                    <div class="absolute -top-24 -right-24 w-96 h-96 {{ $theme === 'green' ? 'bg-green-50/50' : 'bg-red-50/50' }} rounded-full blur-3xl opacity-50 group-hover:opacity-100 transition-opacity duration-1000"></div>
                    
                    <div class="relative z-10 max-w-lg mx-auto">
                        <div class="w-32 h-32 {{ $theme === 'green' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }} rounded-[3rem] flex items-center justify-center mx-auto mb-12 shadow-inner transform transition-transform duration-700 group-hover:scale-110 group-hover:rotate-6">
                            <svg class="w-16 h-16 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <h3 class="text-3xl font-black text-gray-900 uppercase tracking-tighter mb-4">Belum Ada Data</h3>
                        <p class="text-gray-400 text-lg font-medium leading-relaxed mb-10">Saat ini belum ada data siswa yang tercatat dalam kategori ini. Mohon periksa kembali nanti.</p>
                        
                        <a href="{{ url('/') }}" class="inline-flex items-center px-10 py-4 {{ $theme === 'green' ? 'bg-[#345344] hover:bg-[#2a4337]' : 'bg-red-900 hover:bg-red-800' }} text-white text-[11px] font-black uppercase tracking-[0.3em] rounded-2xl shadow-xl transition-all duration-300">
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 lg:gap-10">
                    @foreach($students as $student)
                        <div class="group relative bg-white rounded-[3rem] p-10 shadow-sm hover:shadow-[0_40px_80px_-15px_rgba(0,0,0,0.1)] transition-all duration-500 hover:-translate-y-3 border border-gray-100 overflow-hidden">
                            <!-- Background Decor -->
                            <div class="absolute top-0 right-0 -mr-6 -mt-6 w-32 h-32 {{ $theme === 'green' ? 'bg-green-50' : 'bg-red-50' }} rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                            
                            <div class="relative z-10 flex flex-col items-center">
                                <!-- Large Avatar -->
                                <div class="w-36 h-36 rounded-[2.5rem] overflow-hidden mb-8 shadow-2xl border-8 border-white transition-all duration-500 group-hover:scale-110 group-hover:rotate-3">
                                    @if($student->user->avatar)
                                        <img src="{{ asset($student->user->avatar) }}" class="w-full h-full object-cover" alt="{{ $student->user->name }}">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center {{ $theme === 'green' ? 'bg-[#345344]/5 text-[#345344]' : 'bg-red-50 text-red-900' }}">
                                            <svg class="w-20 h-20 opacity-20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="text-center min-h-[4.5rem] flex flex-col justify-center mb-1">
                                    <h3 class="text-xl lg:text-2xl font-black text-gray-900 leading-[1.1] uppercase tracking-tighter">{{ $student->user->name }}</h3>
                                </div>
                                
                                <div class="flex items-center gap-2 mb-8 bg-gray-50 px-3 py-1 rounded-full border border-gray-100">
                                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">NIS</span>
                                    <span class="text-[11px] font-black {{ $theme === 'green' ? 'text-[#345344]' : 'text-red-900' }} tracking-[0.1em]">{{ $student->nis }}</span>
                                </div>
                                
                                <div class="w-full pt-8 border-t border-gray-100 grid grid-cols-2 gap-3">
                                    <div class="px-2 py-3 rounded-2xl {{ $theme === 'green' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }} text-center border {{ $theme === 'green' ? 'border-green-100' : 'border-red-100' }}">
                                        <p class="text-[8px] font-black uppercase tracking-widest opacity-40 mb-1">Kelas</p>
                                        <p class="text-[11px] font-black uppercase">{{ $student->class }}</p>
                                    </div>
                                    <div class="px-2 py-3 rounded-2xl bg-gray-50 text-gray-500 text-center border border-gray-100">
                                        <p class="text-[8px] font-black uppercase tracking-widest opacity-40 mb-1">Angkatan</p>
                                        <p class="text-[11px] font-black uppercase">{{ $student->generation }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Bottom Accent Line -->
                            <div class="absolute bottom-0 left-0 w-full h-1.5 {{ $theme === 'green' ? 'bg-[#345344]' : 'bg-red-600' }} transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="text-center pb-12">
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.3em]">© {{ date('Y') }} SMA GENTA SYAPUTRA </p>
        </div>
        </div> <!-- End main-content -->

        <!-- Password Popup Overlay -->
        <div id="password-overlay" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity duration-500">
            <div class="bg-white rounded-[2rem] p-10 shadow-2xl w-full max-w-md mx-4 transform transition-all duration-500 border border-white/20 relative overflow-hidden">
                <!-- Decorative background -->
                <div class="absolute -top-24 -right-24 w-64 h-64 {{ $theme === 'green' ? 'bg-green-100' : 'bg-red-100' }} rounded-full blur-3xl opacity-50 pointer-events-none"></div>
                
                <div class="relative z-10 text-center">
                    <div class="w-20 h-20 mx-auto {{ $theme === 'green' ? 'bg-[#345344]' : 'bg-red-900' }} rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    
                    <h2 class="text-2xl font-black text-gray-900 mb-2 uppercase tracking-tight">Halaman Terkunci</h2>
                    <p class="text-gray-500 text-sm mb-8 font-medium">Masukkan kata sandi untuk mengakses data {{ strtolower($title) }}.</p>
                    
                    <div class="relative">
                        <input type="password" id="page-password" class="w-full bg-gray-50 border border-gray-200 {{ $theme === 'green' ? 'text-[#345344]' : 'text-red-900' }} placeholder:text-gray-300 text-center text-lg font-black tracking-widest rounded-xl px-4 py-4 focus:ring-2 {{ $theme === 'green' ? 'focus:ring-[#345344] focus:border-[#345344]' : 'focus:ring-red-900 focus:border-red-900' }} transition-all outline-none" placeholder="•••••••">
                        <p id="password-error" class="text-red-500 text-xs font-bold mt-3 opacity-0 transition-opacity">Kata sandi salah, silakan coba lagi.</p>
                    </div>
                    
                    <button id="submit-password" class="w-full mt-6 {{ $theme === 'green' ? 'bg-[#345344] hover:bg-[#2a4337]' : 'bg-red-900 hover:bg-red-800' }} text-white font-black uppercase tracking-widest text-sm py-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        Buka Kunci
                    </button>
                    
                    <a href="{{ url('/') }}" class="inline-block mt-6 text-gray-400 hover:text-gray-600 text-xs font-black uppercase tracking-widest transition-colors">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const targetPassword = "{{ $password ?? '' }}";
                const overlay = document.getElementById('password-overlay');
                const mainContent = document.getElementById('main-content');
                const passwordInput = document.getElementById('page-password');
                const submitBtn = document.getElementById('submit-password');
                const errorMsg = document.getElementById('password-error');

                function unlockPage() {
                    overlay.classList.remove('opacity-100');
                    overlay.classList.add('opacity-0', 'pointer-events-none');
                    
                    setTimeout(() => {
                        overlay.style.display = 'none';
                    }, 500);

                    mainContent.classList.remove('blur-md', 'pointer-events-none', 'select-none');
                    document.body.classList.remove('overflow-hidden');
                }

                function checkPassword() {
                    if (passwordInput.value === targetPassword) {
                        unlockPage();
                        sessionStorage.setItem('unlocked_' + targetPassword, 'true');
                    } else {
                        errorMsg.classList.remove('opacity-0');
                        passwordInput.value = '';
                        passwordInput.classList.add('border-red-500', 'bg-red-50');
                        
                        setTimeout(() => {
                            errorMsg.classList.add('opacity-0');
                            passwordInput.classList.remove('border-red-500', 'bg-red-50');
                        }, 3000);
                    }
                }

                // Check if already unlocked in session
                if (targetPassword && sessionStorage.getItem('unlocked_' + targetPassword) === 'true') {
                    overlay.style.display = 'none';
                    mainContent.classList.remove('blur-md', 'pointer-events-none', 'select-none');
                    document.body.classList.remove('overflow-hidden');
                } else if (targetPassword) {
                    passwordInput.focus();
                } else {
                    // No password set, unlock by default
                    unlockPage();
                }

                submitBtn.addEventListener('click', checkPassword);
                
                passwordInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        checkPassword();
                    }
                });
            });
        </script>
    </body>
</html>
