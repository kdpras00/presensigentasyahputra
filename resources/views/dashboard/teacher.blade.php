@extends('layouts.app')

@section('header')
    {{-- Header text removed as requested --}}
@endsection

@section('content')
<div class="space-y-6">

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        @if($assignedClass)
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Total Siswa</p>
            <p class="text-3xl font-bold text-gray-900 tracking-tight">{{ $totalClassStudents }}</p>
            <p class="text-xs text-gray-400 mt-1">Terdaftar</p>
        </div>
        @endif

        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Hadir</p>
            <p class="text-3xl font-bold text-gray-900 tracking-tight">{{ $presentToday }}</p>
            <p class="text-xs text-gray-400 mt-1">Tepat waktu</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Terlambat</p>
            <p class="text-3xl font-bold text-gray-900 tracking-tight">{{ $lateToday }}</p>
            <p class="text-xs text-gray-400 mt-1">Lewat batas</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Absen</p>
            <p class="text-3xl font-bold text-gray-900 tracking-tight">{{ $absentToday }}</p>
            <p class="text-xs text-gray-400 mt-1">Belum hadir</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Pulang</p>
            <p class="text-3xl font-bold text-gray-900 tracking-tight">{{ $checkedOutToday }}</p>
            <p class="text-xs text-gray-400 mt-1">Sudah checkout</p>
        </div>
    </div>

    {{-- Activity Log Table --}}
    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h4 class="text-sm font-semibold text-gray-900">Log Aktivitas Kelas</h4>
            <a href="{{ route('reports.index') }}" class="text-xs font-semibold text-[#345344] hover:text-[#2a4336] transition-colors">Lihat Laporan</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Kelas</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Jam Masuk</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Ket. Masuk</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Jam Keluar</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Ket. Keluar</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recentActivity as $activity)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 text-sm font-semibold shrink-0">
                                        {{ substr(optional($activity->student->user)->name ?? '?', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ optional($activity->student->user)->name ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm text-gray-500">{{ $activity->student->class ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($activity->check_in_at)
                                    <span class="font-mono text-sm font-semibold text-gray-700">{{ \Carbon\Carbon::parse($activity->check_in_at)->format('H:i') }}</span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($activity->check_in_at)
                                    @if($activity->check_in_status == 'present')
                                        <span class="text-sm font-semibold text-green-600">Tepat Waktu</span>
                                    @else
                                        <span class="text-sm font-semibold text-red-600">Terlambat</span>
                                    @endif
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($activity->check_out_at)
                                    <span class="font-mono text-sm font-semibold text-gray-700">{{ \Carbon\Carbon::parse($activity->check_out_at)->format('H:i') }}</span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($activity->check_out_at)
                                    @if($activity->check_out_status == 'present')
                                        <span class="text-sm font-semibold text-blue-600">Pulang Normal</span>
                                    @else
                                        <span class="text-sm font-semibold text-orange-600">Pulang Awal</span>
                                    @endif
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs font-semibold text-gray-600">
                                    @if($activity->check_in_at && $activity->check_out_at)
                                        Lengkap
                                    @elseif($activity->check_in_at)
                                        Di Kelas
                                    @else
                                        Absen
                                    @endif
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <p class="text-sm text-gray-400 font-medium">Belum ada aktivitas absensi hari ini</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
