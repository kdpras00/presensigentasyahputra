@extends('layouts.app')

@section('header')
    <h2 class="font-bold text-xl text-gray-800 leading-tight">
        Dashboard Guru
    </h2>
@endsection

@section('content')
<div class="space-y-6">

    @if($assignedClass)
    <!-- Class Banner -->
    <div class="bg-[#345344] rounded-2xl p-5 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-[#DFFF00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <p class="text-white/50 text-[10px] font-bold uppercase tracking-widest">Kelas Diampu</p>
                <h3 class="text-white text-lg font-bold">{{ $assignedClass }}</h3>
            </div>
        </div>
        <div class="text-right">
            <p class="text-white/50 text-[10px] font-bold uppercase tracking-widest">Total Siswa</p>
            <h3 class="text-[#DFFF00] text-xl font-bold">{{ $totalClassStudents }}</h3>
        </div>
    </div>
    @else
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 flex items-center gap-3">
        <svg class="w-5 h-5 text-yellow-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <p class="text-yellow-800 font-medium text-sm">Anda belum memiliki kelas yang ditugaskan. Hubungi Admin untuk pengaturan kelas.</p>
    </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-5 border border-gray-100">
            <p class="text-xs text-gray-400 font-medium mb-3">Hadir</p>
            <h3 class="text-3xl font-bold text-green-600">{{ $presentToday }}</h3>
            <p class="text-[10px] text-gray-400 mt-1">Tepat waktu</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-100">
            <p class="text-xs text-gray-400 font-medium mb-3">Terlambat</p>
            <h3 class="text-3xl font-bold text-yellow-500">{{ $lateToday }}</h3>
            <p class="text-[10px] text-gray-400 mt-1">Lewat jam 07:00</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-100">
            <p class="text-xs text-gray-400 font-medium mb-3">Belum Absen</p>
            <h3 class="text-3xl font-bold text-red-500">{{ $absentToday }}</h3>
            <p class="text-[10px] text-gray-400 mt-1">Belum scan</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-100">
            <p class="text-xs text-gray-400 font-medium mb-3">Sudah Pulang</p>
            <h3 class="text-3xl font-bold text-blue-500">{{ $checkedOutToday }}</h3>
            <p class="text-[10px] text-gray-400 mt-1">Scan keluar</p>
        </div>
    </div>

    <!-- Activity Table -->
    <div class="bg-white rounded-xl border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-800">Aktivitas Hari Ini</h3>
            <span class="text-[10px] font-medium text-gray-400 bg-gray-50 px-2.5 py-1 rounded-md">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
        </div>

        @if($recentActivity->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-50">
                        <th class="text-left px-5 py-3 text-[10px] font-medium text-gray-400 uppercase tracking-wider">Siswa</th>
                        <th class="text-left px-5 py-3 text-[10px] font-medium text-gray-400 uppercase tracking-wider">Kelas</th>
                        <th class="text-center px-5 py-3 text-[10px] font-medium text-gray-400 uppercase tracking-wider">Masuk</th>
                        <th class="text-center px-5 py-3 text-[10px] font-medium text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="text-center px-5 py-3 text-[10px] font-medium text-gray-400 uppercase tracking-wider">Keluar</th>
                        <th class="text-center px-5 py-3 text-[10px] font-medium text-gray-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($recentActivity as $activity)
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-md bg-gray-100 flex items-center justify-center text-gray-600 text-xs font-bold shrink-0">
                                    {{ substr(optional($activity->student->user)->name ?? '?', 0, 1) }}
                                </div>
                                <span class="font-medium text-gray-800 text-sm">{{ optional($activity->student->user)->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3">
                            <span class="text-xs text-gray-500">{{ $activity->student->class ?? '-' }}</span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="font-mono text-xs text-gray-600">{{ $activity->check_in_at ? \Carbon\Carbon::parse($activity->check_in_at)->format('H:i') : '—' }}</span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            @if($activity->check_in_status == 'present')
                                <span class="text-green-600 bg-green-50 text-[10px] font-medium px-2 py-0.5 rounded">Tepat Waktu</span>
                            @elseif($activity->check_in_status == 'late')
                                <span class="text-yellow-600 bg-yellow-50 text-[10px] font-medium px-2 py-0.5 rounded">Terlambat</span>
                            @else
                                <span class="text-gray-300 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="font-mono text-xs text-gray-600">{{ $activity->check_out_at ? \Carbon\Carbon::parse($activity->check_out_at)->format('H:i') : '—' }}</span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            @if($activity->check_out_status == 'present')
                                <span class="text-blue-600 bg-blue-50 text-[10px] font-medium px-2 py-0.5 rounded">Pulang</span>
                            @elseif($activity->check_out_status == 'early_leave')
                                <span class="text-orange-600 bg-orange-50 text-[10px] font-medium px-2 py-0.5 rounded">Pulang Awal</span>
                            @else
                                <span class="text-gray-300 text-xs">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <p class="text-sm text-gray-400">Belum ada data absensi hari ini.</p>
        </div>
        @endif
    </div>
</div>
@endsection
