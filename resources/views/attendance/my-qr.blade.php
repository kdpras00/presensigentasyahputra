@extends('layouts.app')

@section('content')
<div class="min-h-screen py-12 px-4 bg-[#F0F2F5] dark:bg-gray-950">
    <div class="max-w-4xl mx-auto flex flex-col items-center">
        
        <!-- Tab Navigation (Compact & Modern) -->
        <div class="flex justify-center mb-10">
            <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md p-1 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 flex gap-1">
                <button onclick="switchSide('front')" id="btn-front" class="px-8 py-2.5 rounded-xl text-xs font-black transition-all duration-300 bg-[#148C64] text-white shadow-lg shadow-[#148C64]/20 uppercase tracking-widest">
                    Sisi Depan
                </button>
                <button onclick="switchSide('back')" id="btn-back" class="px-8 py-2.5 rounded-xl text-xs font-black transition-all duration-300 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 uppercase tracking-widest">
                    Sisi Belakang
                </button>
            </div>
        </div>

        <!-- CARD WRAPPER -->
        <div class="relative w-full max-w-[360px] aspect-[1/1.6] perspective-1000">
            
            <!-- FRONT SIDE -->
            <div id="side-front" class="card-side w-full h-full bg-white rounded-[2rem] shadow-[0_40px_100px_rgba(0,0,0,0.12)] overflow-hidden flex flex-col transition-all duration-500 relative">
                <!-- Green Header with Wave -->
                <div class="h-[55%] bg-gradient-to-br from-[#148C64] to-[#0A5D42] relative overflow-hidden">
                    <!-- Decorative Circles (Canva/IDN Style) -->
                    <div class="absolute top-0 right-0 w-40 h-40 border-[0.5px] border-white/20 rounded-full -mr-20 -mt-20"></div>
                    <div class="absolute top-0 right-0 w-60 h-60 border-[0.5px] border-white/10 rounded-full -mr-30 -mt-30"></div>
                    
                    <div class="p-8 flex justify-between items-start relative z-10">
                        <div class="flex flex-col">
                            <span class="text-white font-black text-xl tracking-tighter drop-shadow-md">KARTU</span>
                            <span class="text-white/90 text-xs font-bold uppercase tracking-widest -mt-1 drop-shadow-md">Pelajar Digital</span>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="text-white/80 text-[10px] font-black uppercase tracking-[0.2em] drop-shadow-md">SMA GENTA</span>
                            <span class="text-white font-black text-base -mt-1 drop-shadow-md">SYAPUTRA</span>
                        </div>
                    </div>

                    <!-- White Wave Bottom -->
                    <div class="absolute bottom-[-2px] left-0 w-full">
                        <svg viewBox="0 0 1440 320" preserveAspectRatio="none" class="h-32 w-full fill-white">
                            <path d="M0,160 C480,0 960,320 1440,160 L1440,320 L0,320 Z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Avatar (Overlapping) -->
                <div class="absolute top-[44%] left-1/2 -translate-x-1/2 -translate-y-1/2 z-20">
                    <div class="w-32 h-32 rounded-full border-8 border-white shadow-xl overflow-hidden bg-gray-100">
                        @if($student->user->avatar)
                            <img src="{{ asset($student->user->avatar) }}" class="w-full h-full object-cover" alt="Avatar">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-[#148C64]">
                                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Content -->
                <div class="flex-1 pt-8 px-8 pb-10 flex flex-col items-center justify-between text-center">
                    <div>
                        <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight leading-tight">{{ $student->user->name }}</h3>
                        <p class="text-sm font-bold text-gray-500 mt-2 font-mono tracking-widest">{{ $student->user->username }}</p>
                    </div>

                    <div class="w-full pt-8 border-t border-gray-100 flex justify-between items-center">
                        <div class="text-left">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Unit Sekolah</p>
                            <p class="text-sm font-black text-gray-800">SMA Genta Syaputra</p>
                        </div>
                        <img src="{{ asset('images/gentalogoico.png') }}" class="w-12 h-12 object-contain drop-shadow-md" alt="Logo">
                    </div>
                </div>
            </div>

            <!-- BACK SIDE -->
            <div id="side-back" class="card-side hidden w-full h-full bg-gradient-to-br from-[#0A5D42] to-[#063b2a] rounded-[2rem] shadow-[0_40px_100px_rgba(0,0,0,0.2)] overflow-hidden flex flex-col relative transition-all duration-500">
                <!-- Background Decoration -->
                <div class="absolute inset-0 opacity-10">
                    <svg width="100%" height="100%" fill="none" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="40" stroke="white" stroke-width="0.2"/>
                        <circle cx="50" cy="50" r="30" stroke="white" stroke-width="0.2"/>
                        <circle cx="50" cy="50" r="20" stroke="white" stroke-width="0.2"/>
                    </svg>
                </div>

                <div class="p-8 flex justify-between items-start relative z-10">
                    <div class="flex flex-col">
                        <span class="text-white font-black text-xl tracking-tighter drop-shadow-md">KARTU</span>
                        <span class="text-white/80 text-xs font-bold uppercase tracking-widest -mt-1 drop-shadow-md">Pelajar Digital</span>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="text-white/60 text-[10px] font-black uppercase tracking-[0.2em] drop-shadow-md">IDENTITAS</span>
                        <span class="text-white font-black text-base -mt-1 drop-shadow-md">VERIFIED</span>
                    </div>
                </div>

                <div class="flex-1 flex flex-col items-center justify-center p-8 relative z-10">
                    <!-- QR Section: using pre-validated $qrContent from controller -->
                    <div id="qr-container" class="bg-white p-5 rounded-[2.5rem] shadow-2xl">
                        {!! QrCode::size(160)->margin(1)->generate($qrContent) !!}
                    </div>
                </div>

                <div class="p-8 border-t border-white/10 flex flex-col items-center relative z-10">
                    <p class="text-[8px] text-white/40 font-medium text-center uppercase tracking-widest leading-relaxed">
                        Gunakan kode ini untuk akses presensi harian.<br>Kartu ini adalah properti resmi sekolah.
                    </p>
                    <div class="mt-4 flex gap-4 text-[8px] font-black text-white/20 tracking-tighter">
                        <span>WWW.GENTASYAPUTRA.SCH.ID</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- PRINT / DOWNLOAD BUTTON -->
        <button onclick="window.print()" class="mt-12 px-10 py-4 bg-[#148C64] text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Kartu Pelajar
        </button>
    </div>
</div>

<script>
    let currentSide = 'front';

    function switchSide(side) {
        const front = document.getElementById('side-front');
        const back = document.getElementById('side-back');
        const btnFront = document.getElementById('btn-front');
        const btnBack = document.getElementById('btn-back');

        if (side === 'front') {
            front.classList.remove('hidden');
            back.classList.add('hidden');
            btnFront.classList.add('bg-[#148C64]', 'text-white', 'shadow-lg');
            btnFront.classList.remove('text-gray-400');
            btnBack.classList.remove('bg-[#148C64]', 'text-white', 'shadow-lg');
            btnBack.classList.add('text-gray-400');
            currentSide = 'front';
        } else {
            front.classList.add('hidden');
            back.classList.remove('hidden');
            btnBack.classList.add('bg-[#148C64]', 'text-white', 'shadow-lg');
            btnBack.classList.remove('text-gray-400');
            btnFront.classList.remove('bg-[#148C64]', 'text-white', 'shadow-lg');
            btnFront.classList.add('text-gray-400');
            currentSide = 'back';
        }
    }
</script>

<style>
    .perspective-1000 { perspective: 1000px; }
    
    @media print {
        /* Force browser to print background colors and graphics */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        @page {
            margin: 0;
            size: portrait;
        }

        body {
            background-color: white !important;
            margin: 0;
            padding: 0;
        }

        body * { visibility: hidden; }
        
        .card-side:not(.hidden), .card-side:not(.hidden) * { visibility: visible; }
        
        /* Center the card cleanly for printing */
        .card-side:not(.hidden) { 
            position: absolute !important; 
            left: 50% !important; 
            top: 2cm !important; 
            transform: translateX(-50%) !important;
            width: 360px !important; 
            height: 576px !important; 
            border: 1px solid #CBD5E1 !important; /* Garis potong / border luar */
            border-radius: 2rem !important; 
            box-shadow: none !important;
        }

        /* Hide UI elements */
        button, .flex.justify-center.mb-10, .mt-12 { 
            display: none !important; 
        }
    }
</style>
@endsection
