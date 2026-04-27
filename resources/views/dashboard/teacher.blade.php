@extends('layouts.app')

@section('header')
    {{-- Header text removed as requested --}}
@endsection

@section('content')
<div class="space-y-8">

    <!-- Premium Banner -->
    <div class="bg-[#345344] rounded-[2.5rem] p-10 relative overflow-hidden shadow-2xl shadow-[#345344]/20">
        <div class="relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <h2 class="text-4xl font-black text-white leading-tight tracking-tighter mb-2">Dashboard Guru</h2>
                    <p class="text-white/60 text-sm font-medium">Selamat datang, kelola kehadiran siswa Anda dengan mudah.</p>
                </div>
                {{-- Server Clock Removed --}}
            </div>
            
            @if($assignedClass)
            <div class="mt-8 pt-8 border-t border-white/10 flex items-center gap-10">
                <div class="flex flex-col">
                    <span class="text-white/40 text-[10px] font-black uppercase tracking-[0.2em] mb-2">Kelas Pengampu</span>
                    <span class="text-white font-black text-2xl tracking-tighter">{{ $assignedClass }}</span>
                </div>
                <div class="w-[1px] h-12 bg-white/10"></div>
                <div class="flex flex-col">
                    <span class="text-white/40 text-[10px] font-black uppercase tracking-[0.2em] mb-2">Total Siswa</span>
                    <span class="text-white font-black text-2xl tracking-tighter">{{ $totalClassStudents }} <span class="text-white/30 text-xs font-normal ml-1">Siswa Terdaftar</span></span>
                </div>
            </div>
            @endif
        </div>
        
        <!-- Decoration -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white/5 rounded-full pointer-events-none"></div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-[2rem] p-8 shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-50">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Hadir</p>
            <h3 class="text-4xl font-black text-green-500 tracking-tighter">{{ $presentToday }}</h3>
            <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest mt-2">Tepat Waktu</p>
        </div>

        <div class="bg-white rounded-[2rem] p-8 shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-50">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Terlambat</p>
            <h3 class="text-4xl font-black text-yellow-500 tracking-tighter">{{ $lateToday }}</h3>
            <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest mt-2">Lewat Batas</p>
        </div>

        <div class="bg-white rounded-[2rem] p-8 shadow-[0_10px_40_rgba(0,0,0,0.03)] border border-gray-50">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Absen</p>
            <h3 class="text-4xl font-black text-red-500 tracking-tighter">{{ $absentToday }}</h3>
            <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest mt-2">Belum Hadir</p>
        </div>

        <div class="bg-white rounded-[2rem] p-8 shadow-[0_10px_40_rgba(0,0,0,0.03)] border border-gray-50">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Pulang</p>
            <h3 class="text-4xl font-black text-blue-500 tracking-tighter">{{ $checkedOutToday }}</h3>
            <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest mt-2">Sudah Checkout</p>
        </div>
    </div>

    <!-- Data Table (Ghost Style) -->
    <div class="bg-white rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-50 overflow-hidden">
        <div class="px-10 py-8 border-b border-gray-50 flex justify-between items-center">
            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Log Aktivitas Kelas</h4>
            {{-- Live Updates Removed --}}
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-10 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Siswa</th>
                        <th class="px-10 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Check In</th>
                        <th class="px-10 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Check Out</th>
                        <th class="px-10 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($recentActivity as $activity)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-10 py-3">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center text-[#345344] text-xs font-black group-hover:bg-[#345344] group-hover:text-white transition-all">
                                        {{ substr(optional($activity->student->user)->name ?? '?', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-gray-800 leading-none mb-1">{{ optional($activity->student->user)->name ?? '-' }}</p>
                                        <p class="text-[10px] font-bold text-gray-400 tracking-tight">{{ $activity->student->class ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-3 text-center">
                                @if($activity->check_in_at)
                                    <div class="flex flex-col items-center">
                                        <span class="font-mono text-xs font-black text-gray-700">{{ \Carbon\Carbon::parse($activity->check_in_at)->format('H:i') }}</span>
                                        <span class="text-[9px] font-black uppercase tracking-tighter {{ $activity->check_in_status == 'present' ? 'text-green-500' : 'text-yellow-500' }}">
                                            {{ $activity->check_in_status == 'present' ? 'Tepat Waktu' : 'Terlambat' }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-gray-200">—</span>
                                @endif
                            </td>
                            <td class="px-10 py-3 text-center">
                                @if($activity->check_out_at)
                                    <div class="flex flex-col items-center">
                                        <span class="font-mono text-xs font-black text-gray-700">{{ \Carbon\Carbon::parse($activity->check_out_at)->format('H:i') }}</span>
                                        <span class="text-[9px] font-black uppercase tracking-tighter text-blue-500">
                                            Selesai
                                        </span>
                                    </div>
                                @else
                                    <span class="text-gray-200">—</span>
                                @endif
                            </td>
                            <td class="px-10 py-3 text-center">
                                @if($activity->check_in_at && $activity->check_out_at)
                                    <span class="bg-green-50 text-green-700 text-[9px] font-black uppercase px-3 py-1.5 rounded-xl border border-green-100">Lengkap</span>
                                @elseif($activity->check_in_at)
                                    <span class="bg-yellow-50 text-yellow-700 text-[9px] font-black uppercase px-3 py-1.5 rounded-xl border border-yellow-100">Di Kelas</span>
                                @else
                                    <span class="bg-red-50 text-red-600 text-[9px] font-black uppercase px-3 py-1.5 rounded-xl border border-red-100">Absen</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-10 py-24 text-center">
                                <p class="text-[10px] font-black text-gray-300 uppercase tracking-[0.2em]">Belum ada aktivitas absensi hari ini</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
