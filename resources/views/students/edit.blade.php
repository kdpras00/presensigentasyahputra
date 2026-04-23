@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Edit Data Siswa: {{ $student->user->name }}
    </h2>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-3xl shadow-lg border-transparent p-10">
        <form action="{{ route('students.update', $student) }}" method="POST" class="space-y-6">
        @csrf
        @method('PATCH')
        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="name" class="block mb-2 text-sm font-bold text-gray-700">Nama Lengkap</label>
                <input type="text" id="name" name="name" class="bg-[#F3F4F6] border-transparent text-gray-900 text-sm rounded-xl focus:ring-4 focus:ring-[#345344]/20 focus:border-[#345344] block w-full p-4 transition-all placeholder-gray-400" placeholder="John Doe" required value="{{ old('name', $student->user->name) }}">
                @error('name') <p class="text-red-500 font-medium text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="email" class="block mb-2 text-sm font-bold text-gray-700">Email Address</label>
                <input type="email" id="email" name="email" class="bg-[#F3F4F6] border-transparent text-gray-900 text-sm rounded-xl focus:ring-4 focus:ring-[#345344]/20 focus:border-[#345344] block w-full p-4 transition-all placeholder-gray-400" placeholder="john@example.com" required value="{{ old('email', $student->user->email) }}">
                @error('email') <p class="text-red-500 font-medium text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="nis" class="block mb-2 text-sm font-bold text-gray-700">NIS</label>
                <input type="text" id="nis" name="nis" class="bg-[#F3F4F6] border-transparent text-gray-900 text-sm rounded-xl focus:ring-4 focus:ring-[#345344]/20 focus:border-[#345344] block w-full p-4 transition-all placeholder-gray-400" placeholder="12345" required value="{{ old('nis', $student->nis) }}">
                @error('nis') <p class="text-red-500 font-medium text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="class" class="block mb-2 text-sm font-bold text-gray-700">Kelas</label>
                <select id="class" name="class" class="bg-[#F3F4F6] border-transparent text-gray-900 text-sm rounded-xl focus:ring-4 focus:ring-[#345344]/20 focus:border-[#345344] block w-full p-4 transition-all pr-10">
                    <option value="10 IPA 1" {{ old('class', $student->class) == '10 IPA 1' ? 'selected' : '' }}>10 IPA 1</option>
                    <option value="10 IPA 2" {{ old('class', $student->class) == '10 IPA 2' ? 'selected' : '' }}>10 IPA 2</option>
                    <option value="10 IPS 1" {{ old('class', $student->class) == '10 IPS 1' ? 'selected' : '' }}>10 IPS 1</option>
                    <option value="11 IPA 1" {{ old('class', $student->class) == '11 IPA 1' ? 'selected' : '' }}>11 IPA 1</option>
                </select>
                @error('class') <p class="text-red-500 font-medium text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="generation" class="block mb-2 text-sm font-bold text-gray-700">Angkatan</label>
                <input type="text" id="generation" name="generation" class="bg-[#F3F4F6] border-transparent text-gray-900 text-sm rounded-xl focus:ring-4 focus:ring-[#345344]/20 focus:border-[#345344] block w-full p-4 transition-all placeholder-gray-400" placeholder="2023/2024" value="{{ old('generation', $student->generation) }}">
                @error('generation') <p class="text-red-500 font-medium text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="password" class="block mb-2 text-sm font-bold text-gray-700">Password (Kosongkan jika tidak ingin diubah)</label>
                <input type="password" id="password" name="password" class="bg-[#F3F4F6] border-transparent text-gray-900 text-sm rounded-xl focus:ring-4 focus:ring-[#345344]/20 focus:border-[#345344] block w-full p-4 transition-all placeholder-gray-400">
                @error('password') <p class="text-red-500 font-medium text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="password_confirmation" class="block mb-2 text-sm font-bold text-gray-700">Konfirmasi Password Baru</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="bg-[#F3F4F6] border-transparent text-gray-900 text-sm rounded-xl focus:ring-4 focus:ring-[#345344]/20 focus:border-[#345344] block w-full p-4 transition-all placeholder-gray-400">
            </div>
        </div>

        <div class="flex items-center justify-end space-x-4 pt-6 mt-6 border-t border-gray-100">
            <a href="{{ route('students.index') }}" class="text-gray-500 hover:text-gray-900 font-bold px-4 py-2 transition-colors">Batal</a>
            <button type="submit" class="text-white bg-[#345344] hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-[#345344]/30 font-bold rounded-xl text-sm px-8 py-3.5 text-center transition-all shadow-md">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
