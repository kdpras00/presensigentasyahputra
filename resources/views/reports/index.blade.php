@extends('layouts.app')

@section('header')
    {{-- Header text removed for clean look --}}
@endsection

@section('content')
<div class="space-y-8">

    <!-- Premium Report Banner -->
    <div class="bg-[#345344] rounded-[2.5rem] p-10 relative overflow-hidden shadow-2xl shadow-[#345344]/20">
        <div class="relative z-10">
            <h2 class="text-4xl font-black text-white leading-tight tracking-tighter mb-2">Laporan Absensi</h2>
            <p class="text-white/60 text-sm font-medium">Data kehadiran siswa berdasarkan tanggal pilihan Anda.</p>
        </div>
        
        <!-- Decoration -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white/5 rounded-full pointer-events-none"></div>
    </div>

    <!-- Summary Stats (Minimalist Style) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-[2rem] p-8 shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-50 text-center">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Total Siswa</p>
            <h3 class="text-4xl font-black text-[#345344] tracking-tighter">{{ $totalStudents }}</h3>
        </div>
        <div class="bg-white rounded-[2rem] p-8 shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-50 text-center">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Hadir</p>
            <h3 class="text-4xl font-black text-green-500 tracking-tighter">{{ $presentCount }}</h3>
        </div>
        <div class="bg-white rounded-[2rem] p-8 shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-50 text-center">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Terlambat</p>
            <h3 class="text-4xl font-black text-yellow-500 tracking-tighter">{{ $lateCount }}</h3>
        </div>
        <div class="bg-white rounded-[2rem] p-8 shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-50 text-center">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Pulang</p>
            <h3 class="text-4xl font-black text-blue-500 tracking-tighter">{{ $checkedOutCount }}</h3>
        </div>
    </div>

    <!-- Data Table (Ghost Style) -->
    <div class="bg-white rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-50 overflow-hidden">
        <div class="px-10 py-4 border-b border-gray-200 flex flex-col lg:flex-row justify-between items-center gap-4">
            <div class="mb-4 lg:mb-0">
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Detail Kehadiran Siswa</h4>
                <p class="text-sm font-semibold text-[#345344]">{{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</p>
            </div>
            <form method="GET" action="{{ route('reports.index') }}" class="flex flex-col md:flex-row items-center gap-3 w-full lg:w-auto">
                {{-- Search --}}
                <div class="relative group w-full md:w-64">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa..." 
                        class="w-full h-[42px] bg-white border border-gray-200 text-sm font-medium rounded-xl px-10 focus:ring-0 focus:border-[#345344]/30 transition-all">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#345344] transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="relative group flex-1 md:flex-none">
                        <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()"
                            class="w-full md:w-44 h-[42px] bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-xl px-4 focus:ring-0 focus:border-[#345344]/30 transition-all cursor-pointer">
                    </div>
                </div>
            </form>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-10 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Siswa</th>
                        <th class="px-10 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Kelas</th>
                        <th class="px-10 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Waktu Masuk</th>
                        <th class="px-10 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Waktu Keluar</th>
                        <th class="px-10 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Status Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($attendances as $attendance)
                        <tr>
                            <td class="px-10 py-3">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center text-[#345344] text-sm font-bold">
                                        {{ substr(optional($attendance->student->user)->name ?? '?', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800 leading-none mb-1.5">{{ optional($attendance->student->user)->name ?? '-' }}</p>
                                        <p class="text-xs font-medium text-gray-400">Username: {{ optional($attendance->student->user)->username ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-3 text-center">
                                <span class="text-sm font-medium text-gray-800">
                                    {{ $attendance->student->class ?? '-' }}
                                </span>
                            </td>
                            <td class="px-10 py-3 text-center">
                                @if($attendance->check_in_at)
                                    <div class="flex flex-col items-center">
                                        <span class="font-mono text-sm font-bold text-gray-700 mb-0.5">{{ \Carbon\Carbon::parse($attendance->check_in_at)->format('H:i') }}</span>
                                        @if($attendance->check_in_status == 'present')
                                            <span class="text-green-500 text-[10px] font-bold">Tepat Waktu</span>
                                        @else
                                            <span class="text-yellow-500 text-[10px] font-bold">Terlambat</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-200">—</span>
                                @endif
                            </td>
                            <td class="px-10 py-3 text-center">
                                @if($attendance->check_out_at)
                                    <div class="flex flex-col items-center">
                                        <span class="font-mono text-sm font-bold text-gray-700 mb-0.5">{{ \Carbon\Carbon::parse($attendance->check_out_at)->format('H:i') }}</span>
                                        @if($attendance->check_out_status == 'present')
                                            <span class="text-blue-500 text-[10px] font-bold">Pulang Normal</span>
                                        @else
                                            <span class="text-orange-500 text-[10px] font-bold">Pulang Awal</span>
                                        @endif
                                    </div>v>
                                @else
                                    <span class="text-gray-200">—</span>
                                @endif
                            </td>
                            <td class="px-10 py-3 text-center">
                                @if($attendance->check_in_at && $attendance->check_out_at)
                                    <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 font-black text-[9px] uppercase px-3 py-1.5 rounded-xl border border-green-100">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        Lengkap
                                    </span>
                                @elseif($attendance->check_in_at)
                                    <span class="inline-flex items-center gap-1.5 bg-yellow-50 text-yellow-700 font-black text-[9px] uppercase px-3 py-1.5 rounded-xl border border-yellow-100">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3"></path></svg>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 font-black text-[9px] uppercase px-3 py-1.5 rounded-xl border border-red-100">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        Absen
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-10 py-24 text-center">
                                <p class="text-[10px] font-black text-gray-300 uppercase tracking-[0.2em]">Tidak ada data absensi untuk tanggal ini</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
