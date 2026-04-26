@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] flex flex-col items-center py-6 px-4 sm:px-6 lg:px-8">

    @if($error)
    <!-- Error State -->
    <div class="w-full max-w-lg bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center">
        <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-5">
            <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <h3 class="text-xl font-extrabold text-gray-800 mb-2">Kelas Belum Ditugaskan</h3>
        <p class="text-sm text-gray-500">{{ $error }}</p>
    </div>
    @else

    <div class="w-full max-w-5xl">
        <!-- Top Bar: Info & Controls -->
        <div class="bg-[#345344] rounded-2xl p-5 mb-5 shadow-lg">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- Left: Class & Clock -->
                <div class="flex items-center gap-4">
                    
                    <div>
                        <p class="text-white/50 text-[10px] font-bold uppercase tracking-widest">Terminal Presensi</p>
                        <div class="flex items-center gap-3">
                            <h2 class="text-white text-lg font-extrabold">{{ $assignedClass }}</h2>
                            <span class="text-white/30">|</span>
                            <span id="live-clock" class="text-white/60 text-sm font-mono font-bold"></span>
                        </div>
                    </div>
                </div>

                <!-- Right: Mode Toggle -->
                <div class="flex items-center gap-3">
                    <span class="text-white/40 text-[10px] font-bold uppercase tracking-widest hidden md:block">Mode:</span>
                    <button id="mode-toggle" onclick="toggleMode()" class="relative flex items-center p-1 rounded-xl bg-white/10 border border-white/10 transition-all duration-300 hover:bg-white/15 cursor-pointer">
                        <div id="mode-pill" class="absolute top-1 h-[calc(100%-8px)] rounded-lg transition-all duration-300 ease-in-out
                            {{ $mode === 'masuk' ? 'bg-[#DFFF00]/25' : 'bg-orange-500/25' }}"
                            style="width: calc(50% - 4px); left: {{ $mode === 'masuk' ? '4px' : 'calc(50%)' }};"></div>

                        <div id="mode-opt-masuk" class="relative z-10 flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold transition-colors duration-300
                            {{ $mode === 'masuk' ? 'text-[#DFFF00]' : 'text-white/30' }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                            MASUK
                        </div>
                        <div id="mode-opt-keluar" class="relative z-10 flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold transition-colors duration-300
                            {{ $mode === 'keluar' ? 'text-orange-300' : 'text-white/30' }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            KELUAR
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content: Scanner + Info Side -->
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-5">
            
            <!-- Scanner (3/5 width on desktop) -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
                    
                    <!-- Time-Restricted Overlay -->
                    <div id="time-restriction-overlay" class="hidden absolute inset-0 z-[45] bg-white/95 backdrop-blur-md flex flex-col items-center justify-center p-8 text-center">
                        <div class="w-20 h-20 bg-orange-50 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-extrabold text-gray-800 mb-3" id="restriction-title">Sesi Absensi Belum Dibuka</h3>
                        <p class="text-gray-500 max-w-sm leading-relaxed mb-8" id="restriction-message">
                            Absensi masuk hanya diperbolehkan mulai pukul <b>{{ $timeRules['masuk_start'] }}</b> hingga <b>{{ $timeRules['masuk_end'] }}</b>.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="px-5 py-3 bg-gray-50 rounded-xl border border-gray-100 flex items-center gap-3">
                                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase leading-none mb-1">Jadwal Masuk</p>
                                    <p class="text-xs font-extrabold text-gray-700 leading-none">{{ $timeRules['masuk_start'] }} - {{ $timeRules['masuk_end'] }}</p>
                                </div>
                            </div>
                            <div class="px-5 py-3 bg-gray-50 rounded-xl border border-gray-100 flex items-center gap-3">
                                <div class="w-2 h-2 rounded-full bg-orange-500"></div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase leading-none mb-1">Jadwal Pulang</p>
                                    <p class="text-xs font-extrabold text-gray-700 leading-none">{{ $timeRules['keluar_start'] }} - {{ $timeRules['keluar_end'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Scanner Container -->
                    <div class="relative aspect-[4/3] bg-gray-900">
                        <!-- Camera Feed -->
                        <div id="reader" class="w-full h-full"></div>

                        <!-- Focus Overlay -->
                        <div class="absolute inset-0 pointer-events-none z-20 flex items-center justify-center">
                            <div class="w-[92%] h-[92%] rounded-2xl relative" style="box-shadow: 0 0 0 2000px rgba(0,0,0,0.45);">
                                <div class="absolute -top-1 -left-1 w-8 h-8 rounded-tl-2xl border-t-[3px] border-l-[3px] border-[#DFFF00]"></div>
                                <div class="absolute -top-1 -right-1 w-8 h-8 rounded-tr-2xl border-t-[3px] border-r-[3px] border-[#DFFF00]"></div>
                                <div class="absolute -bottom-1 -left-1 w-8 h-8 rounded-bl-2xl border-b-[3px] border-l-[3px] border-[#DFFF00]"></div>
                                <div class="absolute -bottom-1 -right-1 w-8 h-8 rounded-br-2xl border-b-[3px] border-r-[3px] border-[#DFFF00]"></div>
                                <div id="scan-line" class="absolute left-2 right-2 h-0.5 bg-[#DFFF00] shadow-[0_0_8px_rgba(223,255,0,0.6)] animate-scan-line"></div>
                            </div>
                        </div>

                        <!-- Status Overlay -->
                        <div id="status-overlay" class="absolute inset-0 z-30 flex flex-col items-center justify-center bg-white/95 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300 scale-95">
                            <div id="status-icon-wrapper" class="w-16 h-16 rounded-2xl flex items-center justify-center mb-4 transition-transform duration-500 scale-50">
                                <span id="status-icon" class="text-white"></span>
                            </div>
                            <h3 id="status-title" class="text-xl font-extrabold text-gray-800 mb-1"></h3>
                            <p id="status-desc" class="text-sm text-gray-500 text-center px-8 font-medium"></p>
                            <p id="status-type" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-3"></p>
                        </div>

                        <!-- Loading State -->
                        <div id="loading-state" class="absolute inset-0 z-40 flex flex-col items-center justify-center bg-gray-900 transition-opacity duration-500">
                            <div class="flex space-x-1.5 mb-4">
                                <div class="w-2.5 h-2.5 bg-[#DFFF00] rounded-full animate-bounce" style="animation-delay: 0s;"></div>
                                <div class="w-2.5 h-2.5 bg-[#DFFF00] rounded-full animate-bounce" style="animation-delay: 0.1s;"></div>
                                <div class="w-2.5 h-2.5 bg-[#DFFF00] rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
                            </div>
                            <p class="text-sm text-white/60 font-medium">Mempersiapkan kamera...</p>
                        </div>
                    </div>

                    <!-- Scanner Footer -->
                    <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                        <p class="text-[10px] text-gray-400 font-medium">Arahkan QR Code siswa ke area kotak di atas</p>
                        <button id="help-btn" class="text-[10px] font-bold text-gray-400 hover:text-gray-600 uppercase tracking-wider transition-colors flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Bantuan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar: Last 5 scans (2/5 width) -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 h-full">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h3 class="text-sm font-extrabold text-gray-800">Riwayat Scan</h3>
                        <p class="text-[10px] text-gray-400 font-medium mt-0.5">Hasil scan terakhir sesi ini</p>
                    </div>
                    <div id="scan-history" class="p-4 space-y-2 max-h-[400px] overflow-y-auto">
                        <!-- Empty state -->
                        <div id="history-empty" class="text-center py-12">
                            <div class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-2">
                                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <p class="text-xs text-gray-400 font-medium">Belum ada scan</p>
                        </div>
                    </div>

                    <!-- Mode indicator at bottom -->
                    <div id="mode-indicator" class="px-5 py-3 border-t border-gray-100 flex items-center gap-2 transition-colors duration-300
                        {{ $mode === 'masuk' ? 'bg-green-50/50' : 'bg-orange-50/50' }}">
                        <div id="mode-dot" class="w-2 h-2 rounded-full animate-pulse transition-colors duration-300
                            {{ $mode === 'masuk' ? 'bg-green-400' : 'bg-orange-400' }}"></div>
                        <span id="mode-label" class="text-[10px] font-bold uppercase tracking-widest transition-colors duration-300
                            {{ $mode === 'masuk' ? 'text-green-600' : 'text-orange-600' }}">
                            Mode {{ $mode === 'masuk' ? 'Absen Masuk' : 'Absen Keluar' }} Aktif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    @keyframes scan-line {
        0% { top: 8px; opacity: 0; }
        10% { opacity: 1; }
        90% { opacity: 1; }
        100% { top: calc(100% - 8px); opacity: 0; }
    }
    .animate-scan-line {
        animation: scan-line 2.5s ease-in-out infinite;
    }
    #reader { border: none !important; }
    #reader video {
        object-fit: cover !important;
        width: 100% !important;
        height: 100% !important;
    }
    #reader__dashboard_section_csr,
    #reader__dashboard_section_swaplink,
    #reader__scan_region > img,
    #reader__scan_region > br {
        display: none !important;
    }
    #reader__scan_region {
        border: none !important;
        min-height: unset !important;
    }
    #qr-shaded-region { border: none !important; }
    #scan-history::-webkit-scrollbar { width: 4px; }
    #scan-history::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }
</style>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Live clock
        const clockEl = document.getElementById('live-clock');
        if (clockEl) {
            function updateClock() {
                const now = new Date();
                clockEl.textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            }
            updateClock();
            setInterval(updateClock, 1000);
        }

        @if(!$error)
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let isScanning = true;
        let html5QrCode;
        let currentMode = '{{ $mode }}';
        let scanHistory = [];

        // ========== MODE TOGGLE ==========
        window.toggleMode = function() {
            currentMode = currentMode === 'masuk' ? 'keluar' : 'masuk';
            updateModeUI();
            checkTimeRestriction();
        };

        const timeRules = @json($timeRules);

        function checkTimeRestriction() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const currentTime = `${hours}:${minutes}`;

            const overlay = document.getElementById('time-restriction-overlay');
            const resTitle = document.getElementById('restriction-title');
            const resMsg = document.getElementById('restriction-message');

            let isAllowed = false;

            if (currentMode === 'masuk') {
                if (currentTime >= timeRules.masuk_start && currentTime <= timeRules.masuk_end) {
                    isAllowed = true;
                } else {
                    resTitle.innerText = currentTime < timeRules.masuk_start 
                        ? 'Sesi Absen Masuk Belum Dibuka' 
                        : 'Sesi Absen Masuk Telah Berakhir';
                    resMsg.innerHTML = `Absensi <b>MASUK</b> hanya diperbolehkan pada pukul <b>${timeRules.masuk_start}</b> hingga <b>${timeRules.masuk_end}</b>.`;
                }
            } else {
                if (currentTime >= timeRules.keluar_start && currentTime <= timeRules.keluar_end) {
                    isAllowed = true;
                } else {
                    resTitle.innerText = currentTime < timeRules.keluar_start 
                        ? 'Sesi Absen Pulang Belum Dibuka' 
                        : 'Sesi Absen Pulang Telah Berakhir';
                    resMsg.innerHTML = `Absensi <b>PULANG</b> hanya diperbolehkan pada pukul <b>${timeRules.keluar_start}</b> hingga <b>${timeRules.keluar_end}</b>.`;
                }
            }

            if (isAllowed) {
                overlay.classList.add('hidden');
                if (html5QrCode && html5QrCode.getState() === 1) { // Idle
                   // Camera was paused or something, but we let it run
                }
            } else {
                overlay.classList.remove('hidden');
            }
        }

        // Initial check
        checkTimeRestriction();
        // Periodically check every minute
        setInterval(checkTimeRestriction, 60000);

        function updateModeUI() {
            const pill = document.getElementById('mode-pill');
            const optMasuk = document.getElementById('mode-opt-masuk');
            const optKeluar = document.getElementById('mode-opt-keluar');
            const indicator = document.getElementById('mode-indicator');
            const dot = document.getElementById('mode-dot');
            const label = document.getElementById('mode-label');

            if (currentMode === 'masuk') {
                pill.style.left = '4px';
                pill.className = pill.className.replace('bg-orange-500/25', 'bg-[#DFFF00]/25');
                if (!pill.className.includes('bg-[#DFFF00]/25')) pill.className += ' bg-[#DFFF00]/25';

                optMasuk.classList.remove('text-white/30');
                optMasuk.classList.add('text-[#DFFF00]');
                optKeluar.classList.remove('text-orange-300');
                optKeluar.classList.add('text-white/30');

                indicator.classList.remove('bg-orange-50/50');
                indicator.classList.add('bg-green-50/50');
                dot.classList.remove('bg-orange-400');
                dot.classList.add('bg-green-400');
                label.classList.remove('text-orange-600');
                label.classList.add('text-green-600');
                label.textContent = 'Mode Absen Masuk Aktif';
            } else {
                pill.style.left = 'calc(50%)';
                pill.className = pill.className.replace('bg-[#DFFF00]/25', 'bg-orange-500/25');
                if (!pill.className.includes('bg-orange-500/25')) pill.className += ' bg-orange-500/25';

                optKeluar.classList.remove('text-white/30');
                optKeluar.classList.add('text-orange-300');
                optMasuk.classList.remove('text-[#DFFF00]');
                optMasuk.classList.add('text-white/30');

                indicator.classList.remove('bg-green-50/50');
                indicator.classList.add('bg-orange-50/50');
                dot.classList.remove('bg-green-400');
                dot.classList.add('bg-orange-400');
                label.classList.remove('text-green-600');
                label.classList.add('text-orange-600');
                label.textContent = 'Mode Absen Keluar Aktif';
            }
        }

        // ========== SOUND SYSTEM ==========
        const AudioCtx = window.AudioContext || window.webkitAudioContext;
        let audioCtx = null;

        function getAudioCtx() {
            if (!audioCtx) audioCtx = new AudioCtx();
            return audioCtx;
        }

        function playTone(freq, duration, type = 'sine', gainVal = 0.3) {
            const ctx = getAudioCtx();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = type;
            osc.frequency.setValueAtTime(freq, ctx.currentTime);
            gain.gain.setValueAtTime(gainVal, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + duration);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start(ctx.currentTime);
            osc.stop(ctx.currentTime + duration);
        }

        function playSoundMasuk() {
            [523.25, 659.25, 783.99, 1046.50].forEach((freq, i) => {
                setTimeout(() => playTone(freq, 0.35, 'sine', 0.25), i * 120);
            });
        }

        function playSoundKeluar() {
            [783.99, 659.25, 523.25, 392.00].forEach((freq, i) => {
                setTimeout(() => playTone(freq, 0.4, 'triangle', 0.22), i * 150);
            });
        }

        function playSoundWarning() {
            playTone(440, 0.15, 'square', 0.15);
            setTimeout(() => playTone(440, 0.15, 'square', 0.15), 200);
        }

        function playSoundError() {
            playTone(200, 0.5, 'sawtooth', 0.12);
        }

        // ========== UI ==========
        const loadingState = document.getElementById('loading-state');
        const statusOverlay = document.getElementById('status-overlay');
        const iconWrapper = document.getElementById('status-icon-wrapper');
        const statusIcon = document.getElementById('status-icon');
        const statusTitle = document.getElementById('status-title');
        const statusDesc = document.getElementById('status-desc');
        const statusType = document.getElementById('status-type');

        function addToHistory(status, message, type) {
            const container = document.getElementById('scan-history');
            const emptyState = document.getElementById('history-empty');
            if (emptyState) emptyState.remove();

            const now = new Date();
            const time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

            const colors = {
                success: { bg: 'bg-green-50', border: 'border-green-100', text: 'text-green-700', icon: 'bg-green-100 text-green-600' },
                warning: { bg: 'bg-yellow-50', border: 'border-yellow-100', text: 'text-yellow-700', icon: 'bg-yellow-100 text-yellow-600' },
                error: { bg: 'bg-red-50', border: 'border-red-100', text: 'text-red-700', icon: 'bg-red-100 text-red-600' }
            };
            const c = colors[status] || colors.error;

            const iconSvg = status === 'success'
                ? '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>'
                : status === 'warning'
                ? '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01"></path></svg>'
                : '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>';

            const modeLabel = type === 'masuk' ? 'Masuk' : type === 'keluar' ? 'Keluar' : '';

            const card = document.createElement('div');
            card.className = `${c.bg} ${c.border} border rounded-xl p-3 animate-fade-in`;
            card.innerHTML = `
                <div class="flex flex-col">
                    <p class="text-xs font-bold ${c.text} leading-relaxed">${message}</p>
                    <div class="flex items-center gap-2 mt-1.5">
                        <span class="text-[10px] font-mono font-medium text-gray-400">${time}</span>
                        ${modeLabel ? `<span class="text-[9px] font-bold text-gray-500 bg-white/50 border border-white/80 px-1.5 py-0.5 rounded-md uppercase tracking-widest">${modeLabel}</span>` : ''}
                    </div>
                </div>`;

            container.insertBefore(card, container.firstChild);

            // Keep max 10 items
            while (container.children.length > 10) {
                container.removeChild(container.lastChild);
            }
        }

        function showStatus(type, title, message, scanType) {
            statusOverlay.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
            statusOverlay.classList.add('opacity-100', 'scale-100');
            
            iconWrapper.className = 'w-16 h-16 rounded-2xl flex items-center justify-center mb-4 transition-transform duration-500 transform scale-100 shadow-lg';
            
            if (type === 'success') {
                iconWrapper.classList.add('bg-green-500');
                statusIcon.innerHTML = '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>';
            } else if (type === 'warning') {
                iconWrapper.classList.add('bg-yellow-500');
                statusIcon.innerHTML = '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>';
            } else {
                iconWrapper.classList.add('bg-red-500');
                statusIcon.innerHTML = '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>';
            }

            statusTitle.innerText = title;
            statusDesc.innerText = message;
            if (scanType) {
                statusType.innerText = scanType === 'masuk' ? 'Absen Masuk' : 'Absen Keluar';
            }

            // Play sound
            if (type === 'success' && scanType === 'masuk') playSoundMasuk();
            else if (type === 'success' && scanType === 'keluar') playSoundKeluar();
            else if (type === 'warning') playSoundWarning();
            else if (type === 'error') playSoundError();

            // Add to history
            addToHistory(type, message, scanType);
        }

        function resetStatus() {
            statusOverlay.classList.remove('opacity-100', 'scale-100');
            statusOverlay.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
            
            iconWrapper.classList.remove('scale-100', 'bg-green-500', 'bg-yellow-500', 'bg-red-500');
            iconWrapper.classList.add('scale-50');
            statusType.innerText = '';

            setTimeout(() => { isScanning = true; }, 400);
        }

        function onScanSuccess(decodedText) {
            if (!isScanning) return;
            isScanning = false;

            fetch("{{ route('attendance.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    "Accept": "application/json"
                },
                body: JSON.stringify({ qr_code: decodedText, mode: currentMode })
            })
            .then(response => response.json())
            .then(data => {
                let shortTitle = 'Hmm...';
                if (data.status === 'success') shortTitle = 'Berhasil!';
                if (data.status === 'warning') shortTitle = 'Perhatian';
                if (data.status === 'error') shortTitle = 'Gagal';

                showStatus(data.status, shortTitle, data.message, data.type || null);
                setTimeout(() => resetStatus(), 3500);
            })
            .catch(err => {
                console.error(err);
                showStatus('error', 'Koneksi Bermasalah', 'Gagal terhubung ke server. Periksa koneksi dan coba lagi.');
                setTimeout(() => resetStatus(), 3000);
            });
        }

        function onScanFailure() {}

        // Setup Scanner
        html5QrCode = new Html5Qrcode("reader");
        const config = { fps: 10, qrbox: { width: 450, height: 450 }, aspectRatio: 1.333 };

        html5QrCode.start(
            { facingMode: "user" }, config, onScanSuccess, onScanFailure
        ).then(() => {
            loadingState.classList.add('opacity-0');
            setTimeout(() => loadingState.classList.add('hidden'), 500);
        }).catch((err) => {
            console.error(err);
            loadingState.innerHTML = `
                <div class="text-center">
                    <div class="w-12 h-12 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <p class="text-sm text-white/70 font-medium mb-1">Gagal mengakses kamera</p>
                    <p class="text-xs text-white/40 mb-4">Pastikan izin kamera telah diberikan</p>
                    <button onclick="location.reload()" class="px-5 py-2 bg-white/10 text-white/80 rounded-lg text-xs font-bold hover:bg-white/20 transition">Muat Ulang</button>
                </div>`;
            loadingState.classList.remove('opacity-0');
        });

        document.getElementById('help-btn').addEventListener('click', () => {
             alert('Jika kamera tidak muncul:\n1. Pastikan Anda memberikan izin akses kamera ke browser.\n2. Coba muat ulang halaman atau hapus cache browser Anda.\n3. Hubungi petugas IT jika masih bermasalah.');
        });
        @endif
    });
</script>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in 0.3s ease-out; }
</style>
@endsection
