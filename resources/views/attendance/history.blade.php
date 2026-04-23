@extends('layouts.app')

@section('header')
    <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
        Riwayat Absensi Saya
    </h2>
@endsection

@section('content')
<div class="bg-white rounded-3xl shadow-lg overflow-hidden border-transparent p-8">
    <h3 class="text-2xl font-extrabold text-[#345344] mb-6">Riwayat Kehadiran</h3>
    <div class="overflow-x-auto rounded-2xl border border-gray-100">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="text-xs text-white uppercase bg-[#345344]">
                <tr>
                    <th scope="col" class="px-6 py-5 font-bold tracking-wider">Tanggal</th>
                    <th scope="col" class="px-6 py-5 font-bold tracking-wider">Jam Masuk</th>
                    <th scope="col" class="px-6 py-5 font-bold tracking-wider">Status Masuk</th>
                    <th scope="col" class="px-6 py-5 font-bold tracking-wider">Jam Keluar</th>
                    <th scope="col" class="px-6 py-5 font-bold tracking-wider">Status Keluar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $attendance)
                <tr class="bg-white border-b border-gray-100 hover:bg-[#F3F4F6] transition-colors duration-200">
                    <td class="px-6 py-4 font-bold text-gray-900 whitespace-nowrap">
                        {{ $attendance->check_in_at ? \Carbon\Carbon::parse($attendance->check_in_at)->translatedFormat('d F Y') : ($attendance->scanned_at ? \Carbon\Carbon::parse($attendance->scanned_at)->translatedFormat('d F Y') : '-') }}
                    </td>
                    <td class="px-6 py-4 font-mono font-medium text-gray-500">
                        {{ $attendance->check_in_at ? \Carbon\Carbon::parse($attendance->check_in_at)->format('H:i') : '-' }}
                    </td>
                    <td class="px-6 py-4">
                        @if($attendance->check_in_status == 'present')
                            <span class="bg-[#DFFF00]/20 text-[#345344] font-bold px-3 py-1 rounded-lg text-xs">Tepat Waktu</span>
                        @elseif($attendance->check_in_status == 'late')
                            <span class="bg-yellow-100 border-yellow-200 border text-yellow-800 font-bold px-3 py-1 rounded-lg text-xs">Terlambat</span>
                        @else
                            <span class="text-gray-400 text-xs">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-mono font-medium text-gray-500">
                        {{ $attendance->check_out_at ? \Carbon\Carbon::parse($attendance->check_out_at)->format('H:i') : '-' }}
                    </td>
                    <td class="px-6 py-4">
                        @if($attendance->check_out_status == 'present')
                            <span class="bg-blue-100 border-blue-200 border text-blue-800 font-bold px-3 py-1 rounded-lg text-xs">Pulang</span>
                        @elseif($attendance->check_out_status == 'early_leave')
                            <span class="bg-orange-100 border-orange-200 border text-orange-800 font-bold px-3 py-1 rounded-lg text-xs">Pulang Awal</span>
                        @else
                            <span class="text-gray-400 text-xs">Belum</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </div>
                            <p class="text-lg font-bold text-gray-500 mb-1">Belum ada data absensi</p>
                            <p class="text-sm">Riwayat absensi Anda akan muncul di sini.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pt-6">
        {{ $attendances->links() }}
    </div>
</div>
@endsection
