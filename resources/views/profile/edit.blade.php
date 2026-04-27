@extends('layouts.app')

@section('header')
    <h2 class="font-bold text-2xl text-white tracking-tighter">
        Update Profile
    </h2>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-3xl shadow-lg border-transparent p-10">
        
        @if (session('success'))
            <div class="p-4 mb-6 text-sm text-green-800 rounded-xl bg-green-50 border border-green-100 font-bold" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-6" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <!-- Avatar -->
            <div class="flex flex-col items-center justify-center pt-2 pb-6 border-b border-gray-100">
                <div class="relative group cursor-pointer mb-4">
                    <img src="{{ $user->avatar ? asset($user->avatar) : asset('images/avatars/default-avatar.svg') }}" 
                         alt="Profil {{ $user->name }}" 
                         class="w-28 h-28 rounded-full object-cover border-4 border-[#F3F4F6] shadow-sm group-hover:opacity-75 transition-opacity">
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                        <svg class="w-8 h-8 text-white drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                </div>
                
                <input type="file" id="avatar" name="avatar" class="block w-full max-w-sm text-xs text-gray-400
                    file:mr-6 file:py-3 file:px-8
                    file:rounded-xl file:border-0
                    file:text-xs file:font-semibold
                    file:bg-[#345344] file:text-white
                    hover:file:bg-[#2a4337] transition-all cursor-pointer bg-gray-50 border border-gray-100 rounded-xl shadow-sm" accept="image/*">
                @error('avatar')
                    <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Name -->
            <div>
                <label for="name" class="block mb-2 text-sm font-bold text-gray-700">Nama Lengkap</label>
                <input type="text" id="name" name="name" class="bg-[#F3F4F6] border-transparent text-gray-900 text-sm rounded-xl focus:ring-4 focus:ring-[#345344]/20 focus:border-[#345344] block w-full p-4 transition-all placeholder-gray-400" required value="{{ old('name', $user->name) }}">
                @error('name')
                    <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Username -->
            <div>
                <label for="username" class="block mb-2 text-sm font-bold text-gray-700">Username</label>
                <input type="text" id="username" name="username" class="bg-[#F3F4F6] border-transparent text-gray-900 text-sm rounded-xl focus:ring-4 focus:ring-[#345344]/20 focus:border-[#345344] block w-full p-4 transition-all placeholder-gray-400" required value="{{ old('username', $user->username) }}">
                @error('username')
                    <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block mb-2 text-sm font-bold text-gray-700">Email Address</label>
                <input type="email" id="email" name="email" class="bg-[#F3F4F6] border-transparent text-gray-900 text-sm rounded-xl focus:ring-4 focus:ring-[#345344]/20 focus:border-[#345344] block w-full p-4 transition-all placeholder-gray-400" required value="{{ old('email', $user->email) }}">
                @error('email')
                    <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="pt-6 mt-6 border-t border-gray-100">
                <h3 class="text-sm font-bold text-[#345344] mb-4 uppercase tracking-wider">Ubah Password</h3>
                
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
                <button type="submit" class="text-white bg-[#345344] hover:bg-[#2a4337] focus:ring-4 focus:outline-none focus:ring-[#345344]/20 font-bold rounded-xl text-sm px-8 py-3.5 text-center transition-all shadow-lg shadow-[#345344]/10">
                    Update Profile
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
