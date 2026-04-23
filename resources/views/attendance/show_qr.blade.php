@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        QR Code Absensi Hari Ini
    </h2>
@endsection

@section('content')
<div class="max-w-md mx-auto bg-white dark:bg-gray-800 shadow rounded-lg p-6 text-center">
    <div class="mb-6">
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Scan untuk Presensi</h3>
        <p class="text-gray-600 dark:text-gray-400">{{ \Carbon\Carbon::now()->format('d F Y') }}</p>
    </div>

    <div class="flex justify-center mb-6 p-4 bg-white rounded-lg">
        <!-- Generate QR Code -->
        {!! QrCode::size(300)->generate($token) !!}
    </div>

    <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
        <span class="font-medium">Info:</span> Minta siswa untuk membuka menu "Scan Absensi" dan arahkan kamera ke kode di atas.
    </div>

    <div class="mt-6">
        <button onclick="window.print()" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
            Cetak / Print
        </button>
    </div>
</div>
@endsection
