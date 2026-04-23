@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Manajemen Siswa
    </h2>
@endsection

@section('content')
<div class="bg-white rounded-3xl shadow-lg overflow-hidden border-transparent p-8">
    <div class="flex justify-between items-center mb-8">
        <h3 class="text-2xl font-extrabold text-[#345344]">Daftar Siswa</h3>
        <a href="{{ route('students.create') }}" class="text-[#345344] bg-[#DFFF00] hover:bg-[#cbe600] focus:ring-4 focus:ring-[#DFFF00]/50 font-bold rounded-xl text-sm px-6 py-3 transition-colors duration-300 flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Siswa
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            <span class="font-medium">Berhasil!</span> {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto rounded-2xl border border-gray-100">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="text-xs text-white uppercase bg-[#345344]">
                <tr>
                    <th scope="col" class="px-6 py-5 font-bold tracking-wider whitespace-nowrap">Nama</th>
                    <th scope="col" class="px-6 py-5 font-bold tracking-wider whitespace-nowrap">NIS</th>
                    <th scope="col" class="px-6 py-5 font-bold tracking-wider whitespace-nowrap">Kelas</th>
                    <th scope="col" class="px-6 py-5 font-bold tracking-wider whitespace-nowrap">Angkatan</th>
                    <th scope="col" class="px-6 py-5 font-bold tracking-wider whitespace-nowrap">Email</th>
                    <th scope="col" class="px-6 py-5 font-bold tracking-wider whitespace-nowrap text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                <tr class="bg-white border-b border-gray-100 transition-colors duration-200">
                    <td class="px-6 py-4 font-bold text-gray-900 whitespace-nowrap">
                        <div class="flex items-center">
                            <img src="{{ $student->user->avatar ? asset($student->user->avatar) : asset('images/avatars/default-avatar.svg') }}" alt="Foto {{ $student->user->name }}" class="w-10 h-10 rounded-xl object-cover border-2 border-white shadow-sm mr-4 bg-[#F3F4F6]">
                            {{ $student->user->name }}
                        </div>
                    </td>
                    <td class="px-6 py-4 font-mono text-gray-500 font-medium whitespace-nowrap">{{ $student->nis }}</td>
                    <td class="px-6 py-4 font-medium text-gray-600">
                        <span class="text-[#345344] font-bold whitespace-nowrap">{{ $student->class }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $student->generation ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-500 whitespace-nowrap">{{ $student->user->email }}</td>
                    <td class="px-6 py-4 text-center whitespace-nowrap">
                        <div class="flex items-center justify-center space-x-3">
                            <a href="{{ route('students.edit', $student) }}" class="font-bold text-[#345344] hover:underline px-3 py-1.5 transition-colors text-xs">Edit</a>
                            <form action="{{ route('students.destroy', $student) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-bold text-red-600 hover:underline px-3 py-1.5 transition-colors text-xs">Hapus</button>
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
                            <p class="text-lg font-bold text-gray-500 mb-1">Belum ada data siswa</p>
                            <p class="text-sm">Silakan klik tombol "Tambah Siswa" untuk memulai.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pt-6">
        {{ $students->links() }}
    </div>
</div>
@endsection
