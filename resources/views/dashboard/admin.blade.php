@extends('layouts.app')

@section('header')
    <h2 class="font-bold text-xl text-gray-800 leading-tight">
        Beranda Utama
    </h2>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Welcome -->
    <div class="bg-[#345344] rounded-2xl p-6 flex items-center justify-between">
        <div>
            <p class="text-white/50 text-xs font-medium">Selamat datang kembali,</p>
            <h2 class="text-white text-xl font-bold mt-0.5">{{ Auth::user()->name }}</h2>
            <p class="text-white/40 text-xs mt-1">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
        </div>
        <div class="hidden md:block text-right">
            <p class="text-white/40 text-[10px] font-bold uppercase tracking-widest">Role</p>
            <p class="text-[#DFFF00] text-sm font-bold">Administrator</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-5 border border-gray-100">
            <p class="text-xs text-gray-400 font-medium mb-3">Total Siswa</p>
            <div class="flex items-baseline gap-2">
                <h3 class="text-3xl font-bold text-gray-800">{{ $totalStudents ?? 0 }}</h3>
                <span class="text-[10px] font-bold text-[#345344] bg-[#DFFF00] px-2 py-0.5 rounded-full">Aktif</span>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-100">
            <p class="text-xs text-gray-400 font-medium mb-3">Total Guru</p>
            <div class="flex items-baseline gap-2">
                <h3 class="text-3xl font-bold text-gray-800">{{ $totalTeachers ?? 0 }}</h3>
                <span class="text-[10px] font-bold text-white bg-[#345344] px-2 py-0.5 rounded-full">Staf</span>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-100">
            <p class="text-xs text-gray-400 font-medium mb-3">Hadir Hari Ini</p>
            <div class="flex items-baseline gap-2">
                <h3 class="text-3xl font-bold text-green-600">{{ $presentToday ?? 0 }}</h3>
                <span class="text-xs text-gray-400 font-medium">/ {{ $totalStudents ?? 0 }}</span>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-100">
            <p class="text-xs text-gray-400 font-medium mb-3">Belum Absen</p>
            <h3 class="text-3xl font-bold text-red-500">{{ $absentToday ?? 0 }}</h3>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('students.index') }}" class="bg-white rounded-xl p-5 border border-gray-100 hover:border-gray-200 transition-colors group">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-800">Data Siswa</p>
                    <p class="text-xs text-gray-400">Kelola data siswa</p>
                </div>
            </div>
        </a>
        <a href="{{ route('teachers.index') }}" class="bg-white rounded-xl p-5 border border-gray-100 hover:border-gray-200 transition-colors group">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-green-50 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-800">Data Guru</p>
                    <p class="text-xs text-gray-400">Kelola data guru</p>
                </div>
            </div>
        </a>
        <a href="{{ route('reports.index') }}" class="bg-white rounded-xl p-5 border border-gray-100 hover:border-gray-200 transition-colors group">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-purple-50 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-800">Rekap Kehadiran</p>
                    <p class="text-xs text-gray-400">Lihat laporan absensi</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
