@extends('layouts.app')

@section('header')
    {{-- Header text removed as requested --}}
@endsection

@section('content')
<div class="space-y-6">

    {{-- Attendance Status Card --}}
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Status Absensi Hari Ini</p>
                @if($todayAttendance)
                    @if($todayAttendance->check_in_status == 'present')
                        <p class="text-lg font-bold text-gray-900">Hadir</p>
                    @else
                        <p class="text-lg font-bold text-gray-900">Terlambat</p>
                    @endif
                @else
                    <p class="text-lg font-bold text-gray-600">Belum Scan</p>
                @endif
            </div>
            @if($todayAttendance && $todayAttendance->check_in_at)
                <div class="text-right">
                    <p class="text-xs text-gray-600 mb-0.5">Check-in</p>
                    <p class="font-mono text-sm font-semibold text-gray-700">{{ \Carbon\Carbon::parse($todayAttendance->check_in_at)->format('H:i') }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Attendance History --}}
    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h4 class="text-sm font-semibold text-gray-900">Riwayat Absensi Terbaru</h4>
        </div>

        @if($recentLogs->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider text-center">Tanggal</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider text-center">Masuk</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider text-center">Status</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider text-center">Pulang</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($recentLogs as $log)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-center">
                            <span class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($log->date)->translatedFormat('d M Y') }}</span>
                        </td>
                        <td class="px-6 py-4 text-center font-mono text-sm text-gray-600">
                            {{ $log->check_in_at ? \Carbon\Carbon::parse($log->check_in_at)->format('H:i') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-xs font-semibold text-gray-600">
                                @if($log->check_in_status == 'present')
                                    Hadir
                                @elseif($log->check_in_status == 'late')
                                    Terlambat
                                @else
                                    -
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center font-mono text-sm text-gray-600">
                            {{ $log->check_out_at ? \Carbon\Carbon::parse($log->check_out_at)->format('H:i') : '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-16">
            <p class="text-sm text-gray-600 font-medium">Belum ada riwayat absensi</p>
        </div>
        @endif
    </div>
</div>

@endsection
