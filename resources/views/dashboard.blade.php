@extends('layouts.app')

@section('header')
    <h2 class="font-bold text-2xl text-white tracking-tighter">
        Dashboard
    </h2>
@endsection

@section('content')
<div class="space-y-6">
    <div class="bg-[#345344] rounded-2xl p-6">
        <p class="text-white/50 text-xs font-medium">Selamat datang kembali,</p>
        <h2 class="text-white text-xl font-bold mt-1">{{ Auth::user()->name }}</h2>
        <p class="text-white/40 text-xs mt-1">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 p-6 text-center">
        <p class="text-sm text-gray-500">Anda login sebagai <span class="font-bold text-gray-700 uppercase">{{ Auth::user()->role }}</span></p>
    </div>
</div>
@endsection
