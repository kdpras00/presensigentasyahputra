@extends('layouts.app')

@section('header')
    {{-- Header text removed for clean look --}}
@endsection

@section('content')
<div class="space-y-6">

    <!-- Summary Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-100 p-5 text-center">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Total Siswa</p>
            <p class="text-3xl font-bold text-gray-900 tracking-tight">{{ $totalStudents }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-5 text-center">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Hadir</p>
            <p class="text-3xl font-bold text-gray-900 tracking-tight">{{ $presentCount }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-5 text-center">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Terlambat</p>
            <p class="text-3xl font-bold text-gray-900 tracking-tight">{{ $lateCount }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-5 text-center">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Pulang</p>
            <p class="text-3xl font-bold text-gray-900 tracking-tight">{{ $checkedOutCount }}</p>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col lg:flex-row justify-between items-center gap-4 bg-gray-50/60">
            <div class="mb-4 lg:mb-0">
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Detail Kehadiran Siswa</h4>
                <p class="text-sm font-semibold text-gray-700">{{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</p>
            </div>
            <form method="GET" action="{{ route('reports.index') }}" class="flex flex-col md:flex-row items-center gap-3 w-full lg:w-auto">
                <div class="relative group w-full md:w-64">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa..." 
                        class="w-full h-[42px] bg-gray-100 border border-gray-100 text-sm font-medium rounded-xl px-10 focus:ring-0 focus:border-[#345344]/30 transition-all">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#345344] transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
                <div class="relative group flex-1 md:flex-none">
                    <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()"
                        class="w-full md:w-44 h-[42px] bg-gray-100 border border-gray-100 text-gray-700 text-sm font-medium rounded-xl px-4 focus:ring-0 focus:border-[#345344]/30 transition-all cursor-pointer">
                </div>
            </form>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Siswa</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Kelas</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Waktu Masuk</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Waktu Keluar</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($attendances as $attendance)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 text-sm font-semibold shrink-0">
                                        {{ substr(optional($attendance->student->user)->name ?? '?', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ optional($attendance->student->user)->name ?? '-' }}</p>
                                        <p class="text-xs text-gray-400">{{ optional($attendance->student->user)->username ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <span class="text-sm text-gray-700">{{ $attendance->student->class ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-3 text-center">
                                @if($attendance->check_in_at)
                                    <div class="flex flex-col items-center">
                                        <span class="font-mono text-sm font-semibold text-gray-700">{{ \Carbon\Carbon::parse($attendance->check_in_at)->format('H:i') }}</span>
                                        <span class="text-xs text-gray-500">{{ $attendance->check_in_status == 'present' ? 'Tepat Waktu' : 'Terlambat' }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-center">
                                @if($attendance->check_out_at)
                                    <div class="flex flex-col items-center">
                                        <span class="font-mono text-sm font-semibold text-gray-700">{{ \Carbon\Carbon::parse($attendance->check_out_at)->format('H:i') }}</span>
                                        <span class="text-xs text-gray-500">{{ $attendance->check_out_status == 'present' ? 'Pulang Normal' : 'Pulang Awal' }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-center">
                                <span class="text-xs font-semibold text-gray-600">
                                    @if($attendance->check_in_at && $attendance->check_out_at)
                                        Lengkap
                                    @elseif($attendance->check_in_at)
                                        Aktif
                                    @else
                                        Absen
                                    @endif
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <p class="text-sm text-gray-400 font-medium">Tidak ada data absensi untuk tanggal ini</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
