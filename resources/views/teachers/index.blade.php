@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Manajemen Guru') }}
    </h2>
@endsection

@section('content')
<div class="bg-white rounded-3xl shadow-lg overflow-hidden border-transparent p-8">
    <div class="flex justify-between items-center mb-8">
        <h3 class="text-2xl font-extrabold text-[#345344]">Daftar Guru</h3>
        <a href="{{ route('teachers.create') }}" class="text-[#345344] bg-[#DFFF00] hover:bg-[#cbe600] focus:ring-4 focus:ring-[#DFFF00]/50 font-bold rounded-xl text-sm px-6 py-3 transition-colors duration-300 flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Guru
        </a>
    </div>
    <div class="overflow-x-auto rounded-2xl border border-gray-100">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="text-xs text-white uppercase bg-[#345344]">
                <tr>
                    <th scope="col" class="px-6 py-5 font-bold tracking-wider">Nama Lengkap</th>
                    <th scope="col" class="px-6 py-5 font-bold tracking-wider">NIP</th>
                    <th scope="col" class="px-6 py-5 font-bold tracking-wider">Kelas Diampu</th>
                    <th scope="col" class="px-6 py-5 font-bold tracking-wider">Email</th>
                    <th scope="col" class="px-6 py-5 font-bold tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($teachers as $teacher)
                    <tr class="bg-white border-b border-gray-100 hover:bg-[#F3F4F6] transition-colors duration-200">
                        <td class="px-6 py-4 font-bold text-gray-900 whitespace-nowrap">
                            <div class="flex items-center">
                                <img src="{{ $teacher->avatar ? asset($teacher->avatar) : asset('images/avatars/default-avatar.svg') }}" alt="Foto {{ $teacher->name }}" class="w-10 h-10 rounded-xl object-cover border-2 border-white shadow-sm mr-4 bg-[#F3F4F6]">
                                {{ $teacher->name }}
                            </div>
                        </td>
                        <td class="px-6 py-4 font-mono text-gray-500 font-medium">
                            {{ optional($teacher->teacher)->nip ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @if(optional($teacher->teacher)->assigned_class)
                                <span class="bg-[#DFFF00]/20 text-[#345344] font-bold px-3 py-1.5 rounded-lg text-xs">
                                    {{ $teacher->teacher->assigned_class }}
                                </span>
                            @else
                                <span class="text-gray-400 italic text-xs">Belum ditugaskan</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-600">
                            {{ $teacher->email }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-3">
                                <a href="{{ route('teachers.edit', $teacher) }}" class="font-bold text-[#345344] bg-[#DFFF00]/50 hover:bg-[#DFFF00] px-3 py-1.5 rounded-lg transition-colors text-xs">Edit</a>
                                <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-bold text-red-600 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors text-xs">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                                <p class="text-lg font-bold text-gray-500 mb-1">Belum ada data guru</p>
                                <p class="text-sm">Silakan klik tombol "Tambah Guru" untuk memulai.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pt-6">
        {{ $teachers->links() }}
    </div>
</div>
@endsection
