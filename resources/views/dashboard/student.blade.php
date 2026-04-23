@extends('layouts.app')

@section('header')
    <h2 class="font-bold text-xl text-gray-800 leading-tight">
        Dashboard Siswa
    </h2>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Welcome -->
    <div class="bg-[#345344] rounded-2xl p-5 flex items-center justify-between">
        <div>
            <p class="text-white/50 text-xs font-medium">Selamat datang,</p>
            <h2 class="text-white text-lg font-bold mt-0.5">{{ Auth::user()->name }}</h2>
        </div>
        <div class="text-right">
            <p class="text-white/50 text-[10px] font-bold uppercase tracking-widest">Kelas</p>
            <p class="text-[#DFFF00] text-sm font-bold">{{ Auth::user()->student->class ?? '-' }}</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white rounded-xl p-5 border border-gray-100">
            <p class="text-xs text-gray-400 font-medium mb-3">Total Hadir</p>
            <h3 class="text-3xl font-bold text-green-600">{{ $attendanceStats['present'] }}</h3>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-100">
            <p class="text-xs text-gray-400 font-medium mb-3">Terlambat</p>
            <h3 class="text-3xl font-bold text-yellow-500">{{ $attendanceStats['late'] }}</h3>
        </div>
    </div>

    <!-- History -->
    <div class="bg-white rounded-xl border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-800">Riwayat Kehadiran</h3>
        </div>

        @if($myAttendance->count() > 0)
        <div class="divide-y divide-gray-50">
            @foreach($myAttendance as $attendance)
            <div class="px-5 py-3.5 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-800">
                        {{ $attendance->check_in_at ? \Carbon\Carbon::parse($attendance->check_in_at)->translatedFormat('d M Y') : ($attendance->scanned_at ? \Carbon\Carbon::parse($attendance->scanned_at)->translatedFormat('d M Y') : '-') }}
                    </p>
                    <div class="flex items-center gap-3 mt-1">
                        @if($attendance->check_in_status == 'present')
                            <span class="text-green-600 bg-green-50 text-[10px] font-medium px-2 py-0.5 rounded">Tepat Waktu</span>
                        @elseif($attendance->check_in_status == 'late')
                            <span class="text-yellow-600 bg-yellow-50 text-[10px] font-medium px-2 py-0.5 rounded">Terlambat</span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-4 text-right">
                    <div>
                        <p class="text-[10px] text-gray-400 font-medium">Masuk</p>
                        <p class="text-sm font-mono font-medium text-gray-700">{{ $attendance->check_in_at ? \Carbon\Carbon::parse($attendance->check_in_at)->format('H:i') : '-' }}</p>
                    </div>
                    <div class="w-px h-6 bg-gray-100"></div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-medium">Keluar</p>
                        <p class="text-sm font-mono font-medium text-gray-700">{{ $attendance->check_out_at ? \Carbon\Carbon::parse($attendance->check_out_at)->format('H:i') : '-' }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <p class="text-sm text-gray-400">Belum ada riwayat kehadiran.</p>
        </div>
        @endif
    </div>
</div>
@endsection
