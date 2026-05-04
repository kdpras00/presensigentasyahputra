@extends('layouts.app')

@section('content')
<div class="space-y-8">

    <!-- Simplified Banner -->
    <div class="bg-[#345344] rounded-[2.5rem] p-10 relative overflow-hidden shadow-2xl shadow-[#345344]/20">
        <div class="relative z-10">
            <h2 class="text-4xl font-black text-white leading-tight tracking-tighter mb-2">Kelola Jadwal</h2>
            <p class="text-white/60 text-sm font-medium">Atur waktu operasional presensi untuk setiap hari.</p>
        </div>
        
        <!-- Decoration -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white/5 rounded-full pointer-events-none"></div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-3xl text-sm font-bold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Schedule Form -->
    <div class="bg-white rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-50 overflow-hidden">
        <form action="{{ route('schedules.update') }}" method="POST" class="p-8">
            @csrf
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-8 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Hari</th>
                            <th class="px-8 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Mulai Masuk</th>
                            <th class="px-8 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Batas Tepat Waktu</th>
                            <th class="px-8 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Mulai Pulang</th>
                            <th class="px-8 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Batas Pulang</th>
                            <th class="px-8 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Libur</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($schedules as $schedule)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-8 py-6">
                                <span class="text-sm font-bold text-gray-800">{{ $schedule->day }}</span>
                            </td>
                            <td class="px-4 py-6">
                                <input type="time" name="schedules[{{ $schedule->id }}][start_time]" value="{{ $schedule->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '' }}" 
                                    class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-[#345344] focus:border-[#345344] block w-full p-2.5 transition-all duration-300 {{ $schedule->is_off ? 'opacity-50 pointer-events-none' : '' }}" id="start_{{ $schedule->id }}">
                            </td>
                            <td class="px-4 py-6">
                                <input type="time" name="schedules[{{ $schedule->id }}][late_time]" value="{{ $schedule->late_time ? \Carbon\Carbon::parse($schedule->late_time)->format('H:i') : '' }}" 
                                    class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-[#345344] focus:border-[#345344] block w-full p-2.5 transition-all duration-300 {{ $schedule->is_off ? 'opacity-50 pointer-events-none' : '' }}" id="late_{{ $schedule->id }}">
                            </td>
                            <td class="px-4 py-6">
                                <input type="time" name="schedules[{{ $schedule->id }}][checkout_start_time]" value="{{ $schedule->checkout_start_time ? \Carbon\Carbon::parse($schedule->checkout_start_time)->format('H:i') : '' }}" 
                                    class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-[#345344] focus:border-[#345344] block w-full p-2.5 transition-all duration-300 {{ $schedule->is_off ? 'opacity-50 pointer-events-none' : '' }}" id="checkout_start_{{ $schedule->id }}">
                            </td>
                            <td class="px-4 py-6">
                                <input type="time" name="schedules[{{ $schedule->id }}][end_time]" value="{{ $schedule->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '' }}" 
                                    class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-[#345344] focus:border-[#345344] block w-full p-2.5 transition-all duration-300 {{ $schedule->is_off ? 'opacity-50 pointer-events-none' : '' }}" id="end_{{ $schedule->id }}">
                            </td>
                            <td class="px-8 py-6 text-center">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="schedules[{{ $schedule->id }}][is_off]" value="1" class="sr-only peer" {{ $schedule->is_off ? 'checked' : '' }} onchange="toggleInputs({{ $schedule->id }}, this)">
                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#345344]/20 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#345344]"></div>
                                </label>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-[#345344] hover:bg-gray-800 text-white font-bold py-4 px-8 rounded-2xl shadow-xl shadow-[#345344]/10 transition-all duration-300">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleInputs(id, checkbox) {
        const startInput = document.getElementById('start_' + id);
        const lateInput = document.getElementById('late_' + id);
        const checkoutStartInput = document.getElementById('checkout_start_' + id);
        const endInput = document.getElementById('end_' + id);
        
        const inputs = [startInput, lateInput, checkoutStartInput, endInput];
        
        inputs.forEach(input => {
            if (checkbox.checked) {
                input.classList.add('opacity-50', 'pointer-events-none');
            } else {
                input.classList.remove('opacity-50', 'pointer-events-none');
            }
        });
    }
</script>
@endsection
