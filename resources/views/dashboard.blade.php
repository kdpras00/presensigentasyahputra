@extends('layouts.app')

@section('header')
    <h2 class="font-bold text-xl text-white tracking-tight">
        Dashboard
    </h2>
@endsection

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Selamat datang kembali</p>
        <h2 class="text-xl font-bold text-gray-900 tracking-tight">{{ Auth::user()->name }}</h2>
        <p class="text-sm text-gray-600 mt-1">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <p class="text-sm text-gray-600">Anda login sebagai <span class="font-semibold text-gray-900 uppercase">{{ Auth::user()->role }}</span></p>
    </div>
</div>
@endsection
