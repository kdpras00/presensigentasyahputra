@extends('layouts.app')

@section('header')
    <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Edit Data Guru') }}
    </h2>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-3xl shadow-lg border-transparent p-10">
        
        <form method="POST" action="{{ route('teachers.update', $teacher) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label for="name" class="block mb-2 text-sm font-bold text-gray-700">Nama Lengkap</label>
                <input type="text" id="name" name="name" class="bg-[#F3F4F6] border-transparent text-gray-900 text-sm rounded-xl focus:ring-4 focus:ring-[#345344]/20 focus:border-[#345344] block w-full p-4 transition-all placeholder-gray-400" required value="{{ old('name', $teacher->name) }}">
                @error('name')
                    <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Username -->
            <div>
                <label for="username" class="block mb-2 text-sm font-bold text-gray-700">Username</label>
                <input type="text" id="username" name="username" class="bg-[#F3F4F6] border-transparent text-gray-900 text-sm rounded-xl focus:ring-4 focus:ring-[#345344]/20 focus:border-[#345344] block w-full p-4 transition-all placeholder-gray-400" required value="{{ old('username', $teacher->username) }}">
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
                        <option value="{{ $class }}" {{ old('assigned_class', optional($teacher->teacher)->assigned_class) == $class ? 'selected' : '' }}>{{ $class }}</option>
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
                <input type="email" id="email" name="email" class="bg-[#F3F4F6] border-transparent text-gray-900 text-sm rounded-xl focus:ring-4 focus:ring-[#345344]/20 focus:border-[#345344] block w-full p-4 transition-all placeholder-gray-400" required value="{{ old('email', $teacher->email) }}">
                @error('email')
                    <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="pt-6 mt-6 border-t border-gray-100">
                <h3 class="text-sm font-bold text-[#345344] mb-4 uppercase tracking-wider">Ubah Password (Opsional)</h3>
                
                <div class="mb-5">
                    <label for="password" class="block mb-2 text-sm font-bold text-gray-700">Password Baru</label>
                    <input type="password" id="password" name="password" class="bg-[#F3F4F6] border-transparent text-gray-900 text-sm rounded-xl focus:ring-4 focus:ring-[#345344]/20 focus:border-[#345344] block w-full p-4 transition-all placeholder-gray-400" autocomplete="new-password">
                    <p class="mt-2 text-xs font-semibold text-gray-400">Kosongkan jika tidak ingin mengubah password.</p>
                    @error('password')
                        <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block mb-2 text-sm font-bold text-gray-700">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="bg-[#F3F4F6] border-transparent text-gray-900 text-sm rounded-xl focus:ring-4 focus:ring-[#345344]/20 focus:border-[#345344] block w-full p-4 transition-all placeholder-gray-400">
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4 pt-6 mt-6 border-t border-gray-100">
                <a href="{{ route('teachers.index') }}" class="text-gray-500 hover:text-gray-900 font-bold px-4 py-2 transition-colors">
                    Batal
                </a>
                <button type="submit" class="text-[#345344] bg-[#DFFF00] hover:bg-[#cbe600] focus:ring-4 focus:outline-none focus:ring-[#DFFF00]/50 font-bold rounded-xl text-sm px-8 py-3.5 text-center transition-all shadow-md">
                    Update Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
