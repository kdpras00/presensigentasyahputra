@extends('layouts.app')

@section('header')
    {{-- Header text removed as requested --}}
@endsection

@section('content')
<div class="space-y-8">

    <!-- Simplified Banner -->
    <div class="bg-[#345344] rounded-[2.5rem] p-10 relative overflow-hidden shadow-2xl shadow-[#345344]/20">
        <div class="relative z-10">
            <h2 class="text-4xl font-black text-white leading-tight tracking-tighter mb-2">Dashboard Admin</h2>
            <p class="text-white/60 text-sm font-medium">Selamat datang admin, kelola sistem dengan bijak dan efisien.</p>
        </div>
        
        <!-- Decoration -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white/5 rounded-full pointer-events-none"></div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-3xl p-6 shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-50">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Total Siswa</p>
            <h3 class="text-3xl font-bold text-[#345344]">{{ $totalStudents }}</h3>
            <p class="text-[10px] font-semibold text-gray-300 uppercase tracking-wider mt-1">Terdaftar</p>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-50">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Total Guru</p>
            <h3 class="text-3xl font-bold text-[#345344]">{{ $totalTeachers }}</h3>
            <p class="text-[10px] font-semibold text-gray-300 uppercase tracking-wider mt-1">Aktif</p>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-50">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Kehadiran</p>
            <h3 class="text-3xl font-bold text-green-600">{{ $presentToday }}</h3>
            <p class="text-[10px] font-semibold text-gray-300 uppercase tracking-wider mt-1">Hari Ini</p>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-50">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Terlambat</p>
            <h3 class="text-3xl font-bold text-yellow-600">{{ $lateToday }}</h3>
            <p class="text-[10px] font-semibold text-gray-300 uppercase tracking-wider mt-1">Hari Ini</p>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="bg-white rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-50 overflow-hidden">
        <div class="px-8 py-4 border-b border-gray-200 flex justify-between items-center">
            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Log Aktivitas Terbaru</h4>
            <a href="{{ route('reports.index') }}" class="text-xs font-bold text-[#345344] hover:underline">Lihat Semua</a>
        </div>
        
        @if($recentActivity->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-8 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-8 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Waktu</th>
                        <th class="px-8 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($recentActivity as $activity)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-4">
                            <p class="text-sm font-bold text-gray-800 leading-none mb-1">{{ optional($activity->student->user)->name ?? '-' }}</p>
                            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Siswa</p>
                        </td>
                        <td class="px-8 py-4 text-center font-mono text-sm text-gray-500">
                            {{ $activity->check_in_at ? \Carbon\Carbon::parse($activity->check_in_at)->format('H:i') : '-' }}
                        </td>
                        <td class="px-8 py-4 text-center">
                            @if($activity->check_in_status == 'present')
                                <span class="text-green-600 bg-green-50 text-[10px] font-bold px-3 py-1 rounded-full border border-green-100">Hadir</span>
                            @else
                                <span class="text-yellow-600 bg-yellow-50 text-[10px] font-bold px-3 py-1 rounded-full border border-yellow-100">Terlambat</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-20 bg-gray-50/50">
            <p class="text-[10px] font-black text-gray-300 uppercase tracking-[0.2em]">Belum ada aktivitas hari ini</p>
        </div>
        @endif
    </div>
</div>
@endsection
