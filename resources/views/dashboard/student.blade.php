@extends('layouts.app')

@section('header')
    {{-- Header text removed as requested --}}
@endsection

@section('content')
<div class="space-y-8">

    <!-- Simplified Banner -->
    <div class="bg-[#345344] rounded-[2.5rem] p-10 relative overflow-hidden shadow-2xl shadow-[#345344]/20">
        <div class="relative z-10">
            <h2 class="text-4xl font-black text-white leading-tight tracking-tighter mb-2">Dashboard Siswa</h2>
            <p class="text-white/60 text-sm font-medium">Selamat datang, jangan lupa scan absensi tepat waktu hari ini.</p>
            
            <div class="mt-8 pt-8 border-t border-white/10 flex items-center gap-8">
                <div class="flex flex-col">
                    <span class="text-white/40 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Status Absensi</span>
                    <span class="text-white font-black text-xl tracking-tight">
                        @if($todayAttendance)
                            @if($todayAttendance->check_in_status == 'present')
                                <span class="text-green-400">Hadir</span>
                            @else
                                <span class="text-yellow-400">Terlambat</span>
                            @endif
                        @else
                            <span class="text-white/30 italic">Belum Scan</span>
                        @endif
                    </span>
                </div>
                {{-- Server Time Removed --}}
            </div>
        </div>
        
        <!-- Decoration -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white/5 rounded-full pointer-events-none"></div>
    </div>

    <div class="grid grid-cols-1 gap-8">
        <!-- Attendance Logs (Simplified) -->
        <div class="bg-white rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-50 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center">
                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Riwayat Absensi Terbaru</h4>
            </div>
            
            @if($recentLogs->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Tanggal</th>
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Masuk</th>
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Pulang</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($recentLogs as $log)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-8 py-4 text-center">
                                <span class="text-xs font-black text-gray-800 leading-none">{{ \Carbon\Carbon::parse($log->date)->translatedFormat('d M Y') }}</span>
                            </td>
                            <td class="px-8 py-4 text-center font-mono text-xs text-gray-500">
                                {{ $log->check_in_at ? \Carbon\Carbon::parse($log->check_in_at)->format('H:i') : '-' }}
                            </td>
                            <td class="px-8 py-4 text-center">
                                @if($log->check_in_status == 'present')
                                    <span class="text-green-600 bg-green-50 text-[9px] font-black uppercase px-2.5 py-1 rounded-full border border-green-100">Hadir</span>
                                @elseif($log->check_in_status == 'late')
                                    <span class="text-yellow-600 bg-yellow-50 text-[9px] font-black uppercase px-2.5 py-1 rounded-full border border-yellow-100">Terlambat</span>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                            <td class="px-8 py-4 text-center font-mono text-xs text-gray-500">
                                {{ $log->check_out_at ? \Carbon\Carbon::parse($log->check_out_at)->format('H:i') : '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-20 bg-gray-50/50">
                <p class="text-[10px] font-black text-gray-300 uppercase tracking-[0.2em]">Belum ada riwayat absensi</p>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
