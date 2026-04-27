<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SMA Genta Syaputra - Presensi</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased">
        <!-- Floating Buttons (Bottom Right) -->
        <div class="fixed bottom-8 right-8 z-[60] flex flex-col gap-3">
            <a href="{{ route('public.present') }}" class="px-6 py-3 bg-[#148C64] hover:bg-[#0d6b4c] text-white text-[11px] font-black uppercase tracking-[0.2em] rounded-2xl shadow-xl transition-all duration-300 flex items-center gap-3 group">
                <svg class="w-5 h-5 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                PRESENSI
                <svg class="w-4 h-4 opacity-50 group-hover:translate-x-1 transition-transform ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
            </a>
            <a href="{{ route('public.absent') }}" class="px-6 py-3 bg-[#ef4444] hover:bg-[#dc2626] text-white text-[11px] font-black uppercase tracking-[0.2em] rounded-2xl shadow-xl transition-all duration-300 flex items-center gap-3 group">
                <svg class="w-5 h-5 text-red-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                TIDAK PRESENSI
                <svg class="w-4 h-4 opacity-50 group-hover:translate-x-1 transition-transform ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
            </a>
        </div>

        <div class="relative min-h-screen flex items-center justify-center bg-[#345344] overflow-hidden">
            <!-- Background Decorations -->
            <div class="absolute top-0 -left-4 w-96 h-96 bg-white/5 rounded-full mix-blend-overlay filter blur-3xl opacity-70 animate-pulse"></div>
            <div class="absolute -bottom-8 -right-4 w-96 h-96 bg-white/5 rounded-full mix-blend-overlay filter blur-3xl opacity-70 animate-pulse" style="animation-delay: 2s;"></div>

            <div class="relative z-10 text-center px-6">
                <div class="mb-8 flex justify-center">
                    <img src="{{ asset('images/gentalogoico.png') }}" alt="Logo SMA Genta Syaputra" class="w-32 h-32 object-contain drop-shadow-2xl">
                </div>
                
                <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-4 tracking-tight">
                    SMA Genta Syaputra
                </h1>
                
                <p class="text-xl md:text-2xl text-white/80 mb-10 max-w-2xl mx-auto font-medium">
                    Sistem Presensi Digital Terpadu.
                </p>
                <div class="space-y-12">
                <div class="text-center mb-6">
                    <p id="clock-date" class="text-white/60 font-black uppercase tracking-[0.3em] text-sm mb-4"></p>
                    <div class="flex flex-wrap justify-center gap-5">
                        <div class="flex flex-col items-center group">
                            <div class="w-24 h-32 bg-white/5 backdrop-blur-xl rounded-3xl flex items-center justify-center border border-white/20 shadow-[0_20px_50px_rgba(0,0,0,0.3)] relative overflow-hidden transition-all duration-500 group-hover:border-white/50 group-hover:bg-white/10">
                                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent pointer-events-none"></div>
                                <span id="clock-hour" class="text-6xl font-black text-white tracking-tighter drop-shadow-[0_0_15px_rgba(255,255,255,0.3)]">00</span>
                                <div class="absolute w-full h-[1px] bg-white/10 top-1/2"></div>
                                <!-- Glow Effect -->
                                <div class="absolute -bottom-2 w-full h-1 bg-white blur-md opacity-0 group-hover:opacity-30 transition-opacity duration-500"></div>
                            </div>
                            <span class="text-[11px] text-white/40 font-black uppercase mt-4 tracking-[0.3em]">Jam</span>
                        </div>
                        <div class="flex flex-col items-center group">
                            <div class="w-24 h-32 bg-white/5 backdrop-blur-xl rounded-3xl flex items-center justify-center border border-white/20 shadow-[0_20px_50px_rgba(0,0,0,0.3)] relative overflow-hidden transition-all duration-500 group-hover:border-white/50 group-hover:bg-white/10">
                                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent pointer-events-none"></div>
                                <span id="clock-min" class="text-6xl font-black text-white tracking-tighter drop-shadow-[0_0_15px_rgba(255,255,255,0.3)]">00</span>
                                <div class="absolute w-full h-[1px] bg-white/10 top-1/2"></div>
                                <div class="absolute -bottom-2 w-full h-1 bg-white blur-md opacity-0 group-hover:opacity-30 transition-opacity duration-500"></div>
                            </div>
                            <span class="text-[11px] text-white/40 font-black uppercase mt-4 tracking-[0.3em]">Menit</span>
                        </div>
                        <div class="flex flex-col items-center group">
                            <div class="w-24 h-32 bg-white/5 backdrop-blur-xl rounded-3xl flex items-center justify-center border border-white/20 shadow-[0_20px_50px_rgba(0,0,0,0.3)] relative overflow-hidden transition-all duration-500 group-hover:border-white/50 group-hover:bg-white/10">
                                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent pointer-events-none"></div>
                                <span id="clock-sec" class="text-6xl font-black text-white tracking-tighter drop-shadow-[0_0_15px_rgba(255,255,255,0.3)]">00</span>
                                <div class="absolute w-full h-[1px] bg-white/10 top-1/2"></div>
                                <div class="absolute -bottom-2 w-full h-1 bg-white blur-md opacity-0 group-hover:opacity-30 transition-opacity duration-500"></div>
                            </div>
                            <span class="text-[11px] text-white/40 font-black uppercase mt-4 tracking-[0.3em]">Detik</span>
                        </div>
                    </div>
                </div>



                <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
                <script>
                    // Real-time Clock Logic
                    function updateLiveClock() {
                        const now = new Date();
                        
                        // Date formatting (Indonesian)
                        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                        $('#clock-date').text(now.toLocaleDateString('id-ID', options));

                        const h = String(now.getHours()).padStart(2, '0');
                        const m = String(now.getMinutes()).padStart(2, '0');
                        const s = String(now.getSeconds()).padStart(2, '0');
                        
                        $('#clock-hour').text(h);
                        $('#clock-min').text(m);
                        $('#clock-sec').text(s);
                    }
                    setInterval(updateLiveClock, 1000);
                    updateLiveClock();

                </script>

                <div class="mt-16 text-white/50 text-sm font-medium">
                    &copy; {{ date('Y') }} SMA Genta Syaputra. All rights reserved.
                </div>
            </div>
        </div>
    </body>
</html>
