@extends('layouts.app')

@section('header')
    {{-- Header text removed as requested --}}
@endsection

@section('content')
<div class="space-y-6">

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Total Siswa</p>
            <p class="text-3xl font-bold text-gray-900 tracking-tight">{{ $totalStudents }}</p>
            <p class="text-xs text-gray-400 mt-1">Terdaftar</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Total Guru</p>
            <p class="text-3xl font-bold text-gray-900 tracking-tight">{{ $totalTeachers }}</p>
            <p class="text-xs text-gray-400 mt-1">Aktif</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Kehadiran</p>
            <p class="text-3xl font-bold text-gray-900 tracking-tight">{{ $presentToday }}</p>
            <p class="text-xs text-gray-400 mt-1">Hari ini</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Terlambat</p>
            <p class="text-3xl font-bold text-gray-900 tracking-tight">{{ $lateToday }}</p>
            <p class="text-xs text-gray-400 mt-1">Hari ini</p>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h4 class="text-sm font-semibold text-gray-900">Log Aktivitas Terbaru</h4>
            <a href="{{ route('reports.index') }}" class="text-xs font-semibold text-[#345344] hover:text-[#2a4336] transition-colors">Lihat Semua</a>
        </div>

        @if($recentActivity->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Waktu</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($recentActivity as $activity)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="text-sm font-semibold text-gray-900">{{ optional($activity->student->user)->name ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4 text-center font-mono text-sm text-gray-500">
                            {{ $activity->check_in_at ? \Carbon\Carbon::parse($activity->check_in_at)->format('H:i') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-xs font-semibold text-gray-600">{{ $activity->check_in_status == 'present' ? 'Hadir' : 'Terlambat' }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-16">
            <p class="text-sm text-gray-400 font-medium">Belum ada aktivitas hari ini</p>
        </div>
        @endif
    </div>
</div>
@endsection
