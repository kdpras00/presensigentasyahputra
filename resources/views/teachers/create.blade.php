@extends('layouts.app')

@section('header')
    <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Tambah Guru Baru') }}
    </h2>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-3xl shadow-lg border-transparent p-10">
        
        <form method="POST" action="{{ route('teachers.store') }}" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block mb-2 text-sm font-bold text-gray-700">Nama Lengkap</label>
                <input type="text" id="name" name="name" class="bg-[#F3F4F6] border-transparent text-gray-900 text-sm rounded-xl focus:ring-4 focus:ring-[#345344]/20 focus:border-[#345344] block w-full p-4 transition-all placeholder-gray-400" placeholder="Contoh: Budi Santoso, S.Pd" required value="{{ old('name') }}">
                @error('name')
                    <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Username -->
            <div>
                <label for="username" class="block mb-2 text-sm font-bold text-gray-700">Username</label>
                <input type="text" id="username" name="username" class="bg-[#F3F4F6] border-transparent text-gray-900 text-sm rounded-xl focus:ring-4 focus:ring-[#345344]/20 focus:border-[#345344] block w-full p-4 transition-all placeholder-gray-400" placeholder="budi_santoso" required value="{{ old('username') }}">
                @error('username')
                    <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Assigned Class -->
            <div>
                <label for="assigned_class" class="block mb-2 text-sm font-bold text-gray-700">Kelas Diampu</label>
                <select id="assigned_class" name="assigned_class" class="bg-[#F3F4F6] border-transparent text-gray-900 text-sm rounded-xl focus:ring-4 focus:ring-[#345344]/20 focus:border-[#345344] block w-full p-4 transition-all">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($availableClasses as $class)
                        <option value="{{ $class }}" {{ old('assigned_class') == $class ? 'selected' : '' }}>{{ $class }}</option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500">Guru hanya bisa menscan siswa dari kelas yang dipilih</p>
                @error('assigned_class')
                    <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block mb-2 text-sm font-bold text-gray-700">Email Address</label>
                <input type="email" id="email" name="email" class="bg-[#F3F4F6] border-transparent text-gray-900 text-sm rounded-xl focus:ring-4 focus:ring-[#345344]/20 focus:border-[#345344] block w-full p-4 transition-all placeholder-gray-400" placeholder="nama@sekolah.id" required value="{{ old('email') }}">
                @error('email')
                    <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block mb-2 text-sm font-bold text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="bg-[#F3F4F6] border-transparent text-gray-900 text-sm rounded-xl focus:ring-4 focus:ring-[#345344]/20 focus:border-[#345344] block w-full p-4 transition-all placeholder-gray-400" required autocomplete="new-password">
                @error('password')
                    <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block mb-2 text-sm font-bold text-gray-700">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="bg-[#F3F4F6] border-transparent text-gray-900 text-sm rounded-xl focus:ring-4 focus:ring-[#345344]/20 focus:border-[#345344] block w-full p-4 transition-all placeholder-gray-400" required>
            </div>

            <div class="flex items-center justify-end space-x-4 pt-6 mt-6 border-t border-gray-100">
                <a href="{{ route('teachers.index') }}" class="text-gray-500 hover:text-gray-900 font-bold px-4 py-2 transition-colors">
                    Batal
                </a>
                <button type="submit" class="text-white bg-[#345344] hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-[#345344]/30 font-bold rounded-xl text-sm px-8 py-3.5 text-center transition-all shadow-md">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
