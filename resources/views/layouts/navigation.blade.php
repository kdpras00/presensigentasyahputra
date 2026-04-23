<!-- Sidebar Style -->
<aside id="logo-sidebar" class="fixed top-0 left-0 z-50 w-64 h-screen transition-all duration-500 -translate-x-full sm:translate-x-0" aria-label="Sidebar">
    
    <!-- Sidebar Container -->
    <div class="h-full bg-[#345344] flex flex-col relative overflow-hidden shadow-2xl">
        
        <!-- Brand / Logo Section -->
        <div class="px-6 pt-8 pb-6 mb-2 relative z-10 flex items-center group border-b border-white/10">
            <div class="flex flex-col">
                <span class="text-2xl font-extrabold text-white tracking-tight leading-none group-hover:text-[#DFFF00] transition-colors">Presensi<span class="text-[#DFFF00]">Genta</span></span>
                <span class="text-[10px] uppercase tracking-widest text-[#DFFF00]/70 font-bold mt-1.5 flex items-center gap-1.5">
                    Sistem Sekolah
                </span>
            </div>
        </div>

        <!-- Menu Navigation -->
        <div class="flex-1 overflow-y-auto px-6 custom-scrollbar relative z-10 pb-4 mt-4">
            <ul class="space-y-4 font-medium">
                
                <!-- Main Section -->
                <li class="pt-2 pb-1">
                    <span class="text-[11px] font-bold text-white/50 uppercase tracking-widest">Menu Utama</span>
                </li>

                <!-- Dashboard Item -->
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3.5 rounded-2xl group transition-all duration-200 
                        {{ request()->routeIs('dashboard') 
                            ? 'bg-[#DFFF00] text-black font-bold shadow-md' 
                            : 'text-white hover:bg-white/10' 
                        }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-black' : 'text-white/70 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        <span class="tracking-wide">Beranda</span>
                    </a>
                </li>

                @auth
                
                {{-- ADMIN MENU --}}
                @if(Auth::user()->isAdmin())
                <li class="pt-4 pb-1">
                    <span class="text-[11px] font-bold text-white/50 uppercase tracking-widest">Administrator</span>
                </li>
                
                <li>
                    <a href="{{ route('teachers.index') }}" class="flex items-center px-4 py-3.5 rounded-2xl group transition-all duration-200 
                        {{ request()->routeIs('teachers.*') 
                            ? 'bg-[#DFFF00] text-black font-bold shadow-md' 
                            : 'text-white hover:bg-white/10' 
                        }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('teachers.*') ? 'text-black' : 'text-white/70 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span class="tracking-wide">Data Guru</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('students.index') }}" class="flex items-center px-4 py-3.5 rounded-2xl group transition-all duration-200 
                        {{ request()->routeIs('students.*') 
                            ? 'bg-[#DFFF00] text-black font-bold shadow-md' 
                            : 'text-white hover:bg-white/10' 
                        }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('students.*') ? 'text-black' : 'text-white/70 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span class="tracking-wide">Data Siswa</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('reports.index') }}" class="flex items-center px-4 py-3.5 rounded-2xl group transition-all duration-200 
                        {{ request()->routeIs('reports.*') 
                            ? 'bg-[#DFFF00] text-black font-bold shadow-md' 
                            : 'text-white hover:bg-white/10' 
                        }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('reports.*') ? 'text-black' : 'text-white/70 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span class="tracking-wide">Rekap Kehadiran</span>
                    </a>
                </li>
                @endif
                
                {{-- GURU MENU --}}
                @if(Auth::user()->isGuru())
                <li class="pt-4 pb-1">
                    <span class="text-[11px] font-bold text-white/50 uppercase tracking-widest">Akses Guru</span>
                </li>
                 <li>
                    <a href="{{ route('attendance.scan') }}" class="flex items-center px-4 py-3.5 rounded-2xl group transition-all duration-200 
                        {{ request()->routeIs('attendance.scan') 
                            ? 'bg-[#DFFF00] text-black font-bold shadow-md' 
                            : 'text-white hover:bg-white/10' 
                        }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('attendance.scan') ? 'text-black' : 'text-white/70 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h2v-4zM6 6h12v12H6V6z"></path></svg>
                        <span class="tracking-wide">Pindai QR Absensi</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('reports.index') }}" class="flex items-center px-4 py-3.5 rounded-2xl group transition-all duration-200 
                        {{ request()->routeIs('reports.*') 
                            ? 'bg-[#DFFF00] text-black font-bold shadow-md' 
                            : 'text-white hover:bg-white/10' 
                        }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('reports.*') ? 'text-black' : 'text-white/70 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span class="tracking-wide">Rekap Kehadiran</span>
                    </a>
                </li>
                @endif
                
                {{-- STUDENT MENU --}}
                @if(Auth::user()->isStudent())
                <li class="pt-4 pb-1">
                    <span class="text-[11px] font-bold text-white/50 uppercase tracking-widest">Akses Siswa</span>
                </li>
                <li>
                    <a href="{{ route('attendance.my-qr') }}" class="flex items-center px-4 py-3.5 rounded-2xl group transition-all duration-200 
                        {{ request()->routeIs('attendance.my-qr') 
                            ? 'bg-[#DFFF00] text-black font-bold shadow-md' 
                            : 'text-white hover:bg-white/10' 
                        }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('attendance.my-qr') ? 'text-black' : 'text-white/70 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .854.546 1.549 1.325 1.773A2.003 2.003 0 0013 8"></path></svg>
                        <span class="tracking-wide">ID Card Digital</span>
                    </a>
                </li>
                @endif
                @endauth
            </ul>
        </div>

    </div>
</aside>
