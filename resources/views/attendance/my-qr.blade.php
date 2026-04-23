@extends('layouts.app')

@section('header')
    <h2 class="font-bold text-xl text-gray-800 leading-tight">
        {{ __('Kartu Pelajar Digital') }}
    </h2>
@endsection

@section('content')
<div class="flex justify-center py-6 px-4">
    <!-- ID Card -->
    <div class="w-full max-w-sm">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            
            <!-- Header -->
            <div class="bg-[#345344] px-6 pt-6 pb-14 text-center relative">
                <div class="flex items-center justify-center gap-2 mb-1">
                    <svg class="w-4 h-4 text-[#DFFF00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14v7z"></path></svg>
                    <p class="text-white font-bold text-sm uppercase tracking-wider">SMA Genta Syaputra</p>
                </div>
                <p class="text-white/40 text-[10px] font-medium uppercase tracking-widest">Kartu Identitas Digital</p>
            </div>

            <!-- Body -->
            <div class="px-6 pb-6 flex flex-col items-center -mt-10 relative z-10">
                
                <!-- Avatar -->
                <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center text-[#345344] border-4 border-white shadow-md mb-4 overflow-hidden">
                    @if(optional($student->user)->avatar)
                        <img src="{{ asset($student->user->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                    @else
                        <svg class="w-12 h-12 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                    @endif
                </div>

                <!-- Name & Class -->
                <h2 class="text-lg font-bold text-gray-800 text-center">{{ optional($student->user)->name ?? 'Siswa' }}</h2>
                <span class="text-xs text-blue-600 font-bold bg-blue-50 px-3 py-1 rounded-full mt-1 mb-5">{{ $student->class }}</span>

                <!-- QR Code -->
                <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm mb-5">
                    {!! QrCode::size(180)->generate($student->nis) !!}
                </div>

                <!-- NIS -->
                <div class="w-full bg-gray-50 rounded-lg p-3 flex items-center justify-between">
                    <span class="text-xs text-gray-400 font-medium">NIS / ID Siswa</span>
                    <span class="font-mono text-sm font-bold text-gray-800">{{ $student->nis }}</span>
                </div>

                <!-- Footer text -->
                <p class="text-[10px] text-gray-400 text-center mt-5 leading-relaxed">
                    Tunjukkan QR Code ini kepada Guru<br>untuk melakukan absensi harian.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
