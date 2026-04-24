@extends('layouts.app')

@section('header')
    <h2 class="font-extrabold text-3xl text-gray-800 dark:text-gray-200 leading-tight tracking-tight">
        {{ __('Laporan Absensi Siswa') }}
    </h2>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Class Info (for Guru) -->
    @if($assignedClass)
    <div class="bg-[#345344] rounded-2xl p-5 flex items-center gap-4 shadow-lg">
        <div class="w-10 h-10 bg-[#DFFF00]/20 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-[#DFFF00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        </div>
        <div>
            <p class="text-white/60 text-[10px] font-bold uppercase tracking-widest">Laporan Kelas</p>
            <h3 class="text-white text-lg font-extrabold">{{ $assignedClass }}</h3>
        </div>
    </div>
    @endif

    <!-- Filter Section -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <form method="GET" action="{{ route('reports.index') }}" class="flex flex-col md:flex-row md:items-end gap-4">
            <div class="flex-1">
                <label for="date" class="block mb-2 text-xs font-bold text-gray-500 uppercase tracking-wider">Pilih Tanggal</label>
                <input type="date" id="date" name="date" value="{{ $date }}" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-[#345344]/20 focus:border-[#345344] block w-full p-3.5 transition-all">
            </div>
            <div>
                <button type="submit" class="w-full md:w-auto text-[#345344] bg-[#DFFF00] hover:bg-[#cbe600] focus:ring-4 focus:outline-none focus:ring-[#DFFF00]/30 font-bold rounded-xl text-sm px-8 py-3.5 text-center transition-all shadow-sm">
                    Filter Laporan
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 text-center">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Siswa</p>
            <h3 class="text-4xl font-extrabold text-[#345344]">{{ $totalStudents }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 text-center">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Hadir</p>
            <h3 class="text-4xl font-extrabold text-green-500">{{ $presentCount }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 text-center">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Terlambat</p>
            <h3 class="text-4xl font-extrabold text-yellow-500">{{ $lateCount }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 text-center">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Sudah Pulang</p>
            <h3 class="text-4xl font-extrabold text-blue-500">{{ $checkedOutCount }}</h3>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-extrabold text-gray-800">Detail Kehadiran</h3>
            <span class="text-xs font-bold text-gray-400 bg-gray-50 px-3 py-1.5 rounded-lg">{{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider">No</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Nama Siswa</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Username</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Kelas</th>
                        <th class="text-center px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Masuk</th>
                        <th class="text-center px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Keluar</th>
                        <th class="text-center px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($attendances as $index => $attendance)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-gray-400 font-bold text-xs">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-[#345344]/10 flex items-center justify-center text-[#345344] text-xs font-extrabold shrink-0">
                                        {{ substr(optional($attendance->student->user)->name ?? '?', 0, 1) }}
                                    </div>
                                    <span class="font-bold text-gray-800 text-sm">{{ optional($attendance->student->user)->name ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-mono text-gray-400 text-xs font-bold">
                                {{ optional($attendance->student->user)->username ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-gray-100 text-gray-600 font-bold text-xs px-2.5 py-1 rounded-md">{{ $attendance->student->class ?? '-' }}</span>
                            </td>
                            <!-- Masuk Column -->
                            <td class="px-6 py-4 text-center">
                                @if($attendance->check_in_at)
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="font-mono text-xs font-bold text-gray-700">{{ \Carbon\Carbon::parse($attendance->check_in_at)->format('H:i') }}</span>
                                        @if($attendance->check_in_status == 'present')
                                            <span class="bg-green-50 text-green-600 font-bold text-[10px] px-2 py-0.5 rounded-md">Tepat Waktu</span>
                                        @elseif($attendance->check_in_status == 'late')
                                            <span class="bg-yellow-50 text-yellow-600 font-bold text-[10px] px-2 py-0.5 rounded-md">Terlambat</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>
                            <!-- Keluar Column -->
                            <td class="px-6 py-4 text-center">
                                @if($attendance->check_out_at)
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="font-mono text-xs font-bold text-gray-700">{{ \Carbon\Carbon::parse($attendance->check_out_at)->format('H:i') }}</span>
                                        @if($attendance->check_out_status == 'present')
                                            <span class="bg-blue-50 text-blue-600 font-bold text-[10px] px-2 py-0.5 rounded-md">Pulang</span>
                                        @elseif($attendance->check_out_status == 'early_leave')
                                            <span class="bg-orange-50 text-orange-600 font-bold text-[10px] px-2 py-0.5 rounded-md">Pulang Awal</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>
                            <!-- Overall Status -->
                            <td class="px-6 py-4 text-center">
                                @if($attendance->check_in_at && $attendance->check_out_at)
                                    <div class="inline-flex items-center gap-1 bg-green-50 text-green-700 font-bold text-[10px] px-2.5 py-1 rounded-md">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        Lengkap
                                    </div>
                                @elseif($attendance->check_in_at)
                                    <div class="inline-flex items-center gap-1 bg-yellow-50 text-yellow-700 font-bold text-[10px] px-2.5 py-1 rounded-md">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3"></path></svg>
                                        Belum Keluar
                                    </div>
                                @else
                                    <div class="inline-flex items-center gap-1 bg-red-50 text-red-600 font-bold text-[10px] px-2.5 py-1 rounded-md">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        Absen
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-14 h-14 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                        <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    </div>
                                    <p class="text-sm font-bold text-gray-500 mb-0.5">Tidak ada data absensi</p>
                                    <p class="text-xs text-gray-400">Tanggal {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
