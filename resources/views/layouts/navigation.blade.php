<!-- Sidebar Style (Premium Notus - Dark Green) -->
<aside id="logo-sidebar" class="fixed top-0 left-0 z-50 w-60 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
    
    <!-- Sidebar Container -->
    <div class="h-full bg-[#345344] flex flex-col relative overflow-hidden shadow-2xl border-r border-white/5">
        
        <!-- Decoration -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-48 h-48 bg-white/5 rounded-full pointer-events-none"></div>

        <!-- Sidebar Header (Precisely Aligned with Floating Navbar Text) -->
        <div class="px-8 pt-[60px] pb-10 relative z-10">
            <a href="{{ route('dashboard') }}" class="group block">
                <h1 class="text-xl font-bold text-white tracking-tighter leading-none mb-1 transition-colors">GENTA SYAPUTRA</h1>
                <p class="text-[10px] font-bold text-white/30 uppercase tracking-[0.2em]">SISTEM PRESENSI DIGITAL</p>
            </a>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 space-y-8 overflow-y-auto custom-scrollbar pb-10 relative z-10">
            
            <!-- Main Group -->
            <div>
                <p class="px-4 mb-4 text-[11px] font-bold text-white/20 uppercase tracking-[0.2em]">Utama</p>
                <div class="space-y-1.5">
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 group {{ request()->routeIs('dashboard') ? 'bg-white/10 text-white shadow-lg' : 'text-white/40 hover:bg-white/10 active:bg-white/20 hover:text-white' }}">
                        <svg class="w-6 h-6 mr-4 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-white/40 group-hover:text-white' }} transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span class="text-base font-bold tracking-tight">Beranda</span>
                    </a>
                </div>
            </div>

            <!-- Management Group (Admin Only) -->
            @if(Auth::user()->role == 'admin')
            <div>
                <p class="px-4 mb-4 text-[11px] font-bold text-white/20 uppercase tracking-[0.2em]">Administrator</p>
                <div class="space-y-1.5">
                    <a href="{{ route('teachers.index') }}" 
                       class="flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 group {{ request()->routeIs('teachers.*') ? 'bg-white/10 text-white shadow-lg' : 'text-white/40 hover:bg-white/10 active:bg-white/20 hover:text-white' }}">
                        <svg class="w-6 h-6 mr-4 {{ request()->routeIs('teachers.*') ? 'text-white' : 'text-white/40 group-hover:text-white' }} transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span class="text-base font-bold tracking-tight">Data Guru</span>
                    </a>
                    
                    <a href="{{ route('students.index') }}" 
                       class="flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 group {{ request()->routeIs('students.*') ? 'bg-white/10 text-white shadow-lg' : 'text-white/40 hover:bg-white/10 active:bg-white/20 hover:text-white' }}">
                        <svg class="w-6 h-6 mr-4 {{ request()->routeIs('students.*') ? 'text-white' : 'text-white/40 group-hover:text-white' }} transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span class="text-base font-bold tracking-tight">Data Siswa</span>
                    </a>
                    
                    <a href="{{ route('reports.index') }}" 
                       class="flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 group {{ request()->routeIs('reports.*') ? 'bg-white/10 text-white shadow-lg' : 'text-white/40 hover:bg-white/10 active:bg-white/20 hover:text-white' }}">
                        <svg class="w-6 h-6 mr-4 {{ request()->routeIs('reports.*') ? 'text-white' : 'text-white/40 group-hover:text-white' }} transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span class="text-base font-bold tracking-tight">Laporan</span>
                    </a>
                </div>
            </div>
            @endif

            <!-- Teacher Specific Group -->
            @if(Auth::user()->role == 'guru')
            <div>
                <p class="px-4 mb-4 text-[10px] font-black text-white/20 uppercase tracking-[0.2em]">Menu Guru</p>
                <div class="space-y-1.5">
                    <a href="{{ route('attendance.scan') }}" 
                       class="flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 group {{ request()->routeIs('attendance.scan') ? 'bg-white/10 text-white shadow-lg' : 'text-white/40 hover:bg-white/10 active:bg-white/20 hover:text-white' }}">
                        <svg class="w-6 h-6 mr-4 {{ request()->routeIs('attendance.scan') ? 'text-white' : 'text-white/40 group-hover:text-white' }} transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        <span class="text-base font-black tracking-tight">Scan Presensi</span>
                    </a>
                    
                    <a href="{{ route('reports.index') }}" 
                       class="flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 group {{ request()->routeIs('reports.*') ? 'bg-white/10 text-white shadow-lg' : 'text-white/40 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-6 h-6 mr-4 {{ request()->routeIs('reports.*') ? 'text-white' : 'text-white/40 group-hover:text-white' }} transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span class="text-base font-black tracking-tight">Laporan Kelas</span>
                    </a>
                </div>
            </div>
            @endif

            <!-- Student Specific Group -->
            @if(Auth::user()->role == 'student')
            <div>
                <p class="px-4 mb-4 text-[10px] font-black text-white/20 uppercase tracking-[0.2em]">Akses Siswa</p>
                <div class="space-y-1.5">
                    <a href="{{ route('attendance.my-qr') }}" 
                       class="flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 group {{ request()->routeIs('attendance.my-qr') ? 'bg-white/10 text-white shadow-lg' : 'text-white/40 hover:bg-white/10 active:bg-white/20 hover:text-white' }}">
                        <svg class="w-6 h-6 mr-4 {{ request()->routeIs('attendance.my-qr') ? 'text-white' : 'text-white/40 group-hover:text-white' }} transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        <span class="text-base font-black tracking-tight">QR Absensi</span>
                    </a>
                </div>
            </div>
            @endif

        </nav>

    </div>
</aside>
