@extends('layouts.app')

@section('header')
    {{-- Header text removed for clean look --}}
@endsection

@section('content')
<div class="space-y-8">


    <!-- Data Table (Ghost Style) -->
    <div class="bg-white rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-50 overflow-hidden">
        <div class="px-10 py-4 border-b border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">
            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Daftar Seluruh Siswa</h4>
            
            <div class="flex items-center gap-3 w-full md:w-auto">
                <form action="{{ route('students.index') }}" method="GET" class="relative group flex-1 md:flex-none">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari siswa..." 
                        class="w-full md:w-64 h-[42px] bg-gray-100 border border-gray-100 text-sm font-medium rounded-xl px-10 focus:ring-0 focus:border-[#345344]/30 transition-all">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#345344] transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </form>
                
                <a href="{{ route('students.create') }}" class="bg-gray-100 hover:bg-gray-200 text-[#345344] text-sm font-bold px-6 h-[42px] rounded-xl transition-all border border-gray-100 flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Siswa
                </a>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-10 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-10 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Username</th>
                        <th class="px-10 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Kelas</th>
                        <th class="px-10 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Email</th>
                        <th class="px-10 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($students as $student)
                        <tr>
                            <td class="px-10 py-3">
                                <div class="flex items-center gap-4">
                                    <div>
                                        <p class="text-sm font-bold text-gray-800 leading-none">{{ $student->user->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-3">
                                <span class="text-sm font-medium text-gray-500">{{ $student->user->username }}</span>
                            </td>
                            <td class="px-10 py-3 text-center">
                                <span class="text-sm font-medium text-gray-800">
                                    {{ $student->class ?? '-' }}
                                </span>
                            </td>
                            <td class="px-10 py-3 text-center">
                                <span class="text-sm font-medium text-gray-500">{{ $student->user->email }}</span>
                            </td>
                            <td class="px-10 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('students.edit', $student) }}" class="p-2.5 rounded-xl bg-gray-50 text-gray-400 hover:bg-[#345344]/10 hover:text-[#345344] transition-all" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form id="delete-student-{{ $student->id }}" action="{{ route('students.destroy', $student) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete('delete-student-{{ $student->id }}', '{{ $student->user->name }}')" class="p-2.5 rounded-xl bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-500 transition-all" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-10 py-24 text-center">
                                <p class="text-[10px] font-black text-gray-300 uppercase tracking-[0.2em]">Belum ada data siswa terdaftar</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="px-4">
        {{ $students->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
function confirmDelete(formId, name) {
    Swal.fire({
        title: 'Hapus Data?',
        text: 'Data ' + name + ' akan dihapus permanen.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}
</script>
@endsection
