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
                            {{ $mode === 'masuk' ? 'bg-white/20' : 'bg-orange-500/25' }}"
                            style="width: calc(50% - 4px); left: {{ $mode === 'masuk' ? '4px' : 'calc(50%)' }};"></div>

                        <div id="mode-opt-masuk" class="relative z-10 flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold transition-colors duration-300
                            {{ $mode === 'masuk' ? 'text-white' : 'text-white/30' }}">
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

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-5">
            
            <!-- Scanner Hardware Area -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative min-h-[450px] flex flex-col items-center justify-center">
                    
                    <div class="p-10 text-center w-full">
                        <div class="mb-8">
                            <div class="w-28 h-28 bg-[#345344]/5 rounded-[2.5rem] flex items-center justify-center mx-auto mb-6 border-2 border-dashed border-[#345344]/20 group hover:border-[#345344]/40 transition-all duration-500 shadow-inner">
                                <svg class="w-14 h-14 text-[#345344]/40 group-hover:scale-110 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                </svg>
                            </div>
                            <h2 class="text-3xl font-black text-gray-800 tracking-tight leading-tight">Scanner Hardware<br><span class="text-[#345344]/40">Siap Digunakan</span></h2>
                            <p class="text-sm text-gray-500 font-medium mt-3">Silakan scan kartu atau QR Code siswa pada alat scanner</p>
                        </div>

                        <!-- Scanner Input Field -->
                        <div class="max-w-xs mx-auto relative group">
                            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                                <div class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"></div>
                            </div>
                            <input type="text" id="scanner-input" autocomplete="off" autofocus
                                class="w-full pl-10 pr-4 py-5 bg-gray-50 border-2 border-transparent rounded-[1.5rem] text-center font-mono text-2xl font-black text-[#345344] focus:bg-white focus:border-[#345344] focus:ring-8 focus:ring-[#345344]/5 transition-all duration-300 placeholder:text-gray-300 shadow-sm"
                                placeholder="WAITING...">
                            
                            <div id="status-indicator" class="mt-6 text-[10px] font-black uppercase tracking-[0.3em] text-green-600 flex items-center justify-center gap-2 bg-green-50 py-2 px-4 rounded-full border border-green-100 mx-auto w-fit">
                                <span class="flex h-2 w-2 rounded-full bg-green-500"></span>
                                Standby & Monitoring
                            </div>
                        </div>

                        <div class="mt-12 pt-8 border-t border-gray-50">
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-2">Petunjuk Penggunaan</p>
                            <div class="flex justify-center gap-8">
                                <div class="text-center">
                                    <div class="text-[11px] font-bold text-gray-500">Auto Focus</div>
                                    <div class="text-[9px] text-gray-400">Sistem mengunci input</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-[11px] font-bold text-gray-500">Auto Process</div>
                                    <div class="text-[9px] text-gray-400">Scan & Kirim otomatis</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Feedback Overlay -->
                    <div id="status-overlay" class="absolute inset-0 z-30 flex flex-col items-center justify-center bg-white/98 backdrop-blur-md opacity-0 pointer-events-none transition-all duration-500 scale-95">
                        <div id="status-icon-wrapper" class="w-32 h-32 rounded-[2.5rem] flex items-center justify-center mb-8 transition-transform duration-700 scale-50 shadow-2xl">
                            <span id="status-icon" class="text-white"></span>
                        </div>
                        <h3 id="status-title" class="text-4xl font-black text-gray-800 mb-3 tracking-tighter"></h3>
                        <p id="status-desc" class="text-xl text-gray-500 text-center px-12 font-medium leading-relaxed max-lg"></p>
                        <div id="status-type-badge" class="mt-8 px-6 py-2 rounded-full text-xs font-black uppercase tracking-[0.25em] shadow-sm"></div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar: Scan History -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 h-full flex flex-col">
                    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/30">
                        <div>
                            <h3 class="text-sm font-black text-gray-800 uppercase tracking-tight">Log Aktivitas</h3>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Real-time History</p>
                        </div>
                        <div class="bg-white px-3 py-1.5 rounded-xl border border-gray-200 shadow-sm">
                            <span id="scan-count" class="text-sm font-black text-[#345344]">0</span>
                            <span class="text-[9px] font-black text-gray-400 uppercase ml-1">Entries</span>
                        </div>
                    </div>
                    <div id="scan-history" class="p-5 space-y-4 flex-grow overflow-y-auto max-h-[500px]">
                        <!-- Empty state -->
                        <div id="history-empty" class="text-center py-24">
                            <div class="w-20 h-20 bg-gray-50 rounded-[2rem] flex items-center justify-center mx-auto mb-5 border-2 border-dashed border-gray-200">
                                <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-xs text-gray-400 font-black uppercase tracking-[0.2em]">Belum Ada Scan</p>
                        </div>
                    </div>

                    <!-- Bottom Status Indicator -->
                    <div id="mode-indicator" class="px-6 py-5 border-t border-gray-100 flex items-center gap-4 transition-all duration-500
                        {{ $mode === 'masuk' ? 'bg-green-50/50' : 'bg-orange-50/50' }}">
                        <div id="mode-dot" class="w-3 h-3 rounded-full animate-pulse transition-all duration-500
                            {{ $mode === 'masuk' ? 'bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.5)]' : 'bg-orange-500 shadow-[0_0_10px_rgba(249,115,22,0.5)]' }}"></div>
                        <span id="mode-label" class="text-xs font-black uppercase tracking-[0.2em] transition-colors duration-500
                            {{ $mode === 'masuk' ? 'text-green-700' : 'text-orange-700' }}">
                            MODE: {{ $mode === 'masuk' ? 'ABSEN MASUK' : 'ABSEN KELUAR' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Floating Notification Area -->
<div id="notification-container" class="fixed top-8 right-8 z-[100] w-full max-w-sm flex flex-col gap-4 pointer-events-none"></div>

<style>
    /* Better Scrollbar */
    #scan-history::-webkit-scrollbar { width: 6px; }
    #scan-history::-webkit-scrollbar-track { background: transparent; }
    #scan-history::-webkit-scrollbar-thumb { background: #f1f5f9; border-radius: 10px; }
    #scan-history::-webkit-scrollbar-thumb:hover { background: #e2e8f0; }

    /* Animations */
    .notif-entrance { animation: notif-slide-in 0.6s cubic-bezier(0.19, 1, 0.22, 1) forwards; }
    .notif-exit { animation: notif-slide-out 0.5s cubic-bezier(0.19, 1, 0.22, 1) forwards; }
    @keyframes notif-slide-in {
        from { opacity: 0; transform: translateY(-20px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    @keyframes notif-slide-out {
        from { opacity: 1; transform: translateY(0) scale(1); }
        to { opacity: 0; transform: translateY(-20px) scale(0.95); }
    }

    .fade-in-up { animation: fade-in-up 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
    @keyframes fade-in-up {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
        // Clock
        function updateClock() {
            const now = new Date();
            $('#live-clock').text(now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }));
        }
        setInterval(updateClock, 1000);
        updateClock();

        @if(!$error)
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        let currentMode = '{{ $mode }}';
        let scanTotal = 0;
        let isProcessing = false;
        const scannerInput = $('#scanner-input');

        // Focus management for USB Scanner
        function refocus() {
            if (!isProcessing && !scannerInput.is(':focus')) {
                scannerInput.focus();
            }
        }
        $(document).on('click keydown', refocus);
        setInterval(refocus, 1000);

        // Mode Switching
        window.toggleMode = function() {
            currentMode = currentMode === 'masuk' ? 'keluar' : 'masuk';
            updateModeUI();
        };

        function updateModeUI() {
            const pill = $('#mode-pill');
            const optMasuk = $('#mode-opt-masuk');
            const optKeluar = $('#mode-opt-keluar');
            const indicator = $('#mode-indicator');
            const dot = $('#mode-dot');
            const label = $('#mode-label');

            if (currentMode === 'masuk') {
                pill.css('left', '4px').removeClass('bg-orange-500/25').addClass('bg-white/20');
                optMasuk.removeClass('text-white/30').addClass('text-white');
                optKeluar.removeClass('text-orange-300').addClass('text-white/30');
                indicator.removeClass('bg-orange-50/50').addClass('bg-green-50/50');
                dot.removeClass('bg-orange-500 shadow-[0_0_10px_rgba(249,115,22,0.5)]').addClass('bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.5)]');
                label.removeClass('text-orange-700').addClass('text-green-700').text('MODE: ABSEN MASUK');
            } else {
                pill.css('left', 'calc(50%)').removeClass('bg-white/20').addClass('bg-orange-500/25');
                optKeluar.removeClass('text-white/30').addClass('text-orange-300');
                optMasuk.removeClass('text-white').addClass('text-white/30');
                indicator.removeClass('bg-green-50/50').addClass('bg-orange-50/50');
                dot.removeClass('bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.5)]').addClass('bg-orange-500 shadow-[0_0_10px_rgba(249,115,22,0.5)]');
                label.removeClass('text-green-700').addClass('text-orange-700').text('MODE: ABSEN KELUAR');
            }
            scannerInput.focus();
        }

        // Notification System
        function showNotification(type, message) {
            const id = 'notif-' + Date.now();
            let iconSvg = '<svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            if (type === 'success') iconSvg = '<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>';
            if (type === 'warning') iconSvg = '<svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>';
            if (type === 'error') iconSvg = '<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>';

            const html = `
                <div id="${id}" class="notif-entrance flex items-start p-5 rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.1)] bg-white pointer-events-auto border border-gray-100">
                    <div class="flex-shrink-0 w-10 h-10 rounded-2xl bg-gray-50 flex items-center justify-center mr-4">
                        ${iconSvg}
                    </div>
                    <div class="flex-grow">
                        <p class="text-[13px] font-black text-gray-800 leading-snug">${message}</p>
                        <p class="text-[9px] text-gray-400 font-black uppercase tracking-[0.2em] mt-1.5">Sistem Presensi Genta</p>
                    </div>
                </div>
            `;

            $('#notification-container').prepend(html);
            setTimeout(() => {
                $(`#${id}`).removeClass('notif-entrance').addClass('notif-exit');
                setTimeout(() => $(`#${id}`).remove(), 600);
            }, 6000);
        }

        // Process Scan Input
        scannerInput.on('keypress', function(e) {
            if (e.which === 13) {
                const code = $(this).val().trim();
                if (code && !isProcessing) {
                    executeScanRequest(code);
                }
                $(this).val('');
            }
        });

        function executeScanRequest(code) {
            isProcessing = true;
            scannerInput.prop('disabled', true);
            $('#status-indicator').html('<span class="flex h-2 w-2 rounded-full bg-blue-500 animate-ping"></span> MENGHUBUNGI SERVER...');

            $.ajax({
                url: "{{ route('attendance.store') }}",
                method: "POST",
                data: JSON.stringify({ qr_code: code, mode: currentMode }),
                contentType: "application/json",
                headers: { "X-CSRF-TOKEN": csrfToken, "Accept": "application/json" },
                success: function(res) {
                    processResponse(res);
                },
                error: function(xhr) {
                    const res = xhr.responseJSON || { status: 'error', message: 'Koneksi Server Bermasalah.' };
                    processResponse(res);
                },
                complete: function() {
                    isProcessing = false;
                    scannerInput.prop('disabled', false).val('').focus();
                    $('#status-indicator').html('<span class="flex h-2 w-2 rounded-full bg-green-500"></span> Standby & Monitoring');
                }
            });
        }

        function processResponse(data) {
            const titleMap = { success: 'BERHASIL!', warning: 'PERHATIAN', error: 'GAGAL!' };
            const statusTitle = titleMap[data.status] || 'HMM...';

            showLargeFeedback(data.status, statusTitle, data.message, data.type);
            showNotification(data.status, data.message);
            
            if (data.status !== 'error') {
                updateHistoryList(data.status, data.message, data.type);
            }
        }

        function showLargeFeedback(status, title, message, type) {
            const overlay = $('#status-overlay');
            const wrapper = $('#status-icon-wrapper');
            const icon = $('#status-icon');
            const badge = $('#status-type-badge');

            overlay.removeClass('opacity-0 scale-95 pointer-events-none').addClass('opacity-100 scale-100');
            wrapper.removeClass('scale-50 bg-green-500 bg-yellow-500 bg-red-500 shadow-green-500/40 shadow-yellow-500/40 shadow-red-500/40').addClass('scale-100');
            
            if (status === 'success') {
                wrapper.addClass('bg-green-500 shadow-2xl shadow-green-500/40');
                icon.html('<svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>');
                badge.removeClass('bg-yellow-100 text-yellow-800 bg-red-100 text-red-800').addClass('bg-green-100 text-green-700 border border-green-200');
            } else if (status === 'warning') {
                wrapper.addClass('bg-yellow-500 shadow-2xl shadow-yellow-500/40');
                icon.html('<svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M12 9v2m0 4h.01"></path></svg>');
                badge.removeClass('bg-green-100 text-green-700 bg-red-100 text-red-700').addClass('bg-yellow-100 text-yellow-700 border border-yellow-200');
            } else {
                wrapper.addClass('bg-red-500 shadow-2xl shadow-red-500/40');
                icon.html('<svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M6 18L18 6M6 6l12 12"></path></svg>');
                badge.removeClass('bg-green-100 text-green-700 bg-yellow-100 text-yellow-700').addClass('bg-red-100 text-red-700 border border-red-200');
            }

            $('#status-title').text(title);
            $('#status-desc').text(message);
            badge.text(type === 'masuk' ? 'LOG: ABSENSI MASUK' : (type === 'keluar' ? 'LOG: ABSENSI KELUAR' : 'SYSTEM LOG'));

            setTimeout(() => {
                overlay.removeClass('opacity-100 scale-100').addClass('opacity-0 scale-95 pointer-events-none');
                wrapper.removeClass('scale-100').addClass('scale-50');
            }, 2500);
        }

        function updateHistoryList(status, message, type) {
            $('#history-empty').hide();
            scanTotal++;
            $('#scan-count').text(scanTotal);

            const now = new Date();
            const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            
            const styles = {
                success: 'border-green-100 bg-green-50/50 text-green-800',
                warning: 'border-yellow-100 bg-yellow-50/50 text-yellow-800',
                error: 'border-red-100 bg-red-50/50 text-red-800'
            };
            const s = styles[status] || styles.error;
            const modeName = type === 'masuk' ? 'MASUK' : (type === 'keluar' ? 'KELUAR' : 'INFO');

            const item = `
                <div class="p-5 rounded-3xl border ${s} fade-in-up shadow-sm">
                    <div class="flex justify-between items-center mb-2.5">
                        <span class="text-[9px] font-black uppercase tracking-[0.2em] px-2.5 py-1 rounded-full bg-white/60 border border-white/80 shadow-sm">${modeName}</span>
                        <span class="text-[10px] font-mono font-black opacity-40">${timeStr}</span>
                    </div>
                    <p class="text-xs font-black leading-relaxed tracking-tight">${message}</p>
                </div>
            `;

            $('#scan-history').prepend(item);
            if ($('#scan-history').children().length > 15) {
                $('#scan-history').children().last().remove();
            }
        }
        @endif
    });
</script>
@endsection
