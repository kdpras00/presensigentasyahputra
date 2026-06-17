@extends('layouts.app')

@section('header')
    {{-- Header text removed for clean look --}}
@endsection

@section('content')
<div class="min-h-[85vh] flex flex-col items-center py-6 px-4 sm:px-6 lg:px-8">

    @if($error)
    <div class="w-full max-w-lg bg-white rounded-2xl border border-gray-100 p-10 text-center">
        <h3 class="text-lg font-bold text-gray-800 mb-2">Kelas Belum Ditugaskan</h3>
        <p class="text-sm text-gray-500">{{ $error }}</p>
    </div>
    @else

    <div class="w-full max-w-5xl">
        <!-- Top Bar -->
        <div class="bg-[#345344] rounded-2xl p-5 mb-5">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <h2 class="text-white text-lg font-bold">{{ $assignedClass }}</h2>
                    <span class="text-white/20">|</span>
                    <span id="live-clock" class="text-white/50 text-sm font-mono"></span>
                </div>

                <div class="flex items-center gap-3">
                    <button id="mode-toggle" onclick="toggleMode()" class="relative flex items-center p-1 rounded-xl bg-white/10 border border-white/10 transition-all duration-300 hover:bg-white/15 cursor-pointer">
                        <div id="mode-pill" class="absolute top-1 h-[calc(100%-8px)] rounded-lg transition-all duration-300 ease-in-out
                            {{ $mode === 'masuk' ? 'bg-white/20' : 'bg-orange-500/25' }}"
                            style="width: calc(50% - 4px); left: {{ $mode === 'masuk' ? '4px' : 'calc(50%)' }};"></div>
                        <div id="mode-opt-masuk" class="relative z-10 px-4 py-2 rounded-lg text-xs font-bold transition-colors duration-300
                            {{ $mode === 'masuk' ? 'text-white' : 'text-white/30' }}">
                            MASUK
                        </div>
                        <div id="mode-opt-keluar" class="relative z-10 px-4 py-2 rounded-lg text-xs font-bold transition-colors duration-300
                            {{ $mode === 'keluar' ? 'text-orange-300' : 'text-white/30' }}">
                            KELUAR
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-5">
            
            <!-- Scanner Area -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden relative min-h-[400px] flex flex-col items-center justify-center">
                    
                    <div class="p-10 text-center w-full">
                        <!-- Scanner Input -->
                        <div class="max-w-sm mx-auto">
                            <div id="status-indicator" class="mb-4 text-[10px] font-bold uppercase tracking-[0.2em] flex items-center justify-center gap-2 py-2 px-4 rounded-full border mx-auto w-fit transition-all duration-500 bg-gray-50 text-gray-400 border-gray-100">
                                <span id="session-dot" class="flex h-2 w-2 rounded-full bg-gray-400 animate-pulse"></span>
                                <span id="session-label">Memuat jadwal...</span>
                            </div>

                            <!-- Hardware Scanner Connection Status -->
                            <div id="hw-status" class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl border text-xs font-semibold mb-4 transition-all duration-500 bg-yellow-50 border-yellow-200 text-yellow-700">
                                <span id="hw-status-dot" class="w-2 h-2 rounded-full shrink-0 bg-yellow-500 animate-pulse"></span>
                                <svg id="hw-status-icon" class="w-4 h-4 shrink-0 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span id="hw-status-text" class="truncate">Mendeteksi scanner...</span>
                            </div>

                            <div class="relative group">
                                <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                                    <div id="input-dot" class="w-2.5 h-2.5 rounded-full bg-yellow-500 animate-pulse transition-colors duration-500"></div>
                                </div>
                                <input type="text" id="scanner-input" autocomplete="off" autofocus
                                    class="w-full pl-10 pr-4 py-5 bg-gray-100 border-2 border-transparent rounded-xl text-center font-mono text-2xl font-bold text-[#345344] focus:bg-white focus:border-[#345344] focus:ring-4 focus:ring-[#345344]/5 transition-all duration-300 placeholder:text-gray-300"
                                    placeholder="WAITING...">
                            </div>
                        </div>
                    </div>

                    <!-- Status Feedback Overlay -->
                    <div id="status-overlay" class="absolute inset-0 z-30 flex flex-col items-center justify-center bg-white/98 backdrop-blur-md opacity-0 pointer-events-none transition-all duration-500 scale-95" tabindex="-1">
                        <div id="status-icon-wrapper" class="w-20 h-20 rounded-2xl flex items-center justify-center mb-6 transition-transform duration-700 scale-50"></div>
                        <h3 id="status-title" class="text-3xl font-bold text-gray-800 mb-2 tracking-tight"></h3>
                        <p id="status-desc" class="text-base text-gray-500 text-center px-12 font-medium max-w-md"></p>
                        <div id="status-type-badge" class="mt-6 px-5 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-[0.2em]"></div>
                    </div>
                </div>
            </div>

            <!-- Scan History -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-gray-100 h-full flex flex-col">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/60">
                        <h3 class="text-sm font-bold text-gray-700">Riwayat Scan</h3>
                        <div class="bg-white px-3 py-1 rounded-lg border border-gray-200">
                            <span id="scan-count" class="text-sm font-bold text-[#345344]">0</span>
                        </div>
                    </div>
                    <div id="scan-history" class="p-4 space-y-3 flex-grow overflow-y-auto max-h-[500px]">
                        <div id="history-empty" class="text-center py-20">
                            <p class="text-xs text-gray-400 font-medium">Belum ada scan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Floating Notification Area -->
<div id="notification-container" class="fixed top-8 right-8 z-[100] w-full max-w-sm flex flex-col gap-3 pointer-events-none"></div>

<style>
    #scan-history::-webkit-scrollbar { width: 4px; }
    #scan-history::-webkit-scrollbar-track { background: transparent; }
    #scan-history::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

    .notif-entrance { animation: notif-slide-in 0.5s cubic-bezier(0.19, 1, 0.22, 1) forwards; }
    .notif-exit { animation: notif-slide-out 0.4s cubic-bezier(0.19, 1, 0.22, 1) forwards; }
    @keyframes notif-slide-in {
        from { opacity: 0; transform: translateY(-10px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    @keyframes notif-slide-out {
        from { opacity: 1; transform: translateY(0) scale(1); }
        to { opacity: 0; transform: translateY(-10px) scale(0.98); }
    }

    .fade-in-up { animation: fade-in-up 0.4s ease-out forwards; }
    @keyframes fade-in-up {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
        function updateClock() {
            const now = new Date();
            $('#live-clock').text(now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }));
        }
        setInterval(updateClock, 1000);
        updateClock();

        @if(!$error)
        // ========================================
        // REAL-TIME SESSION STATUS (from server timeRules)
        // ========================================
        const timeRules = @json($timeRules ?? null);

        function parseHHMM(str) {
            if (!str) return null;
            const [h, m] = str.split(':').map(Number);
            return h * 60 + m;
        }

        function updateSessionStatus() {
            if (!timeRules) return;
            const now = new Date();
            const cur = now.getHours() * 60 + now.getMinutes();

            const masukStart  = parseHHMM(timeRules.masuk_start);
            const masukEnd    = parseHHMM(timeRules.masuk_end);
            const keluarStart = parseHHMM(timeRules.keluar_start);
            const keluarEnd   = parseHHMM(timeRules.keluar_end);

            let label, dotClass, containerClass;

            if (cur >= masukStart && cur < masukEnd) {
                label = 'Sesi Masuk: Tepat Waktu';
                dotClass = 'bg-green-500';
                containerClass = 'bg-green-50 text-green-600 border-green-100';
            } else if (cur >= masukEnd && cur < keluarStart) {
                label = 'Sesi Masuk: Terlambat';
                dotClass = 'bg-yellow-500';
                containerClass = 'bg-yellow-50 text-yellow-600 border-yellow-100';
            } else if (cur >= keluarStart && cur <= keluarEnd) {
                label = 'Sesi Pulang: Dibuka';
                dotClass = 'bg-orange-500';
                containerClass = 'bg-orange-50 text-orange-600 border-orange-100';
            } else if (cur > keluarEnd) {
                label = 'Sesi Presensi Hari Ini Berakhir';
                dotClass = 'bg-red-500';
                containerClass = 'bg-red-50 text-red-600 border-red-100';
            } else {
                label = 'Sesi Presensi Belum Dimulai';
                dotClass = 'bg-red-500';
                containerClass = 'bg-red-50 text-red-600 border-red-100';
            }

            const indicator = document.getElementById('status-indicator');
            const dot = document.getElementById('session-dot');
            const lbl = document.getElementById('session-label');

            indicator.className = 'mb-4 text-[10px] font-bold uppercase tracking-[0.2em] flex items-center justify-center gap-2 py-2 px-4 rounded-full border mx-auto w-fit transition-all duration-500 ' + containerClass;
            dot.className = 'flex h-2 w-2 rounded-full animate-pulse ' + dotClass;
            lbl.textContent = label;
        }

        updateSessionStatus();
        setInterval(updateSessionStatus, 30000); // re-check every 30s
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        let currentMode = '{{ $mode }}';
        let scanTotal = 0;
        let isProcessing = false;
        // Use native DOM for scanner input (faster than jQuery wrapper)
        const scannerInput = document.getElementById('scanner-input');

        // ========================================
        // SCANNER HARDWARE CONNECTION DETECTION
        // ========================================
        let scannerState = 'waiting';
        let lastScanTime = null;
        let connectionCheckTimer = null;
        let inactivityTimer = null;

        const INITIAL_DETECT_TIMEOUT = 8000;   // 8s — mark disconnected if no scan received
        const INACTIVITY_TIMEOUT = 180000;      // 3min — mark inactive if scanner goes idle

        function updateScannerStatus(state, message) {
            scannerState = state;
            const container = document.getElementById('hw-status');
            const dot = document.getElementById('hw-status-dot');
            const text = document.getElementById('hw-status-text');
            const inputDot = document.getElementById('input-dot');
            const icon = document.getElementById('hw-status-icon');

            // Update container style
            container.className = 'flex items-center gap-2.5 px-4 py-2.5 rounded-xl border text-xs font-semibold mb-4 transition-all duration-500 ' +
                (state === 'connected'    ? 'bg-green-50 border-green-200 text-green-700' :
                 state === 'waiting'      ? 'bg-yellow-50 border-yellow-200 text-yellow-700' :
                                            'bg-red-50 border-red-200 text-red-700');

            // Update status dot
            dot.className = 'w-2 h-2 rounded-full shrink-0 transition-colors duration-500 ' +
                (state === 'connected'    ? 'bg-green-500 animate-pulse' :
                 state === 'waiting'      ? 'bg-yellow-500 animate-pulse' :
                                            'bg-red-500');

            // Update input field dot color
            inputDot.className = 'w-2.5 h-2.5 rounded-full transition-colors duration-500 ' +
                (state === 'connected'    ? 'bg-green-500 animate-pulse' :
                 state === 'waiting'      ? 'bg-yellow-500 animate-pulse' :
                                            'bg-red-500 animate-pulse');

            // Update icon: checkmark / spinner / x
            if (state === 'connected') {
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />';
            } else if (state === 'waiting') {
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />';
            } else {
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />';
            }

            text.textContent = message || state;
        }

        // Initial state: waiting for scanner
        updateScannerStatus('waiting', 'Mendeteksi scanner...');

        // After timeout, if no scan has been received, mark as disconnected
        connectionCheckTimer = setTimeout(function() {
            if (!lastScanTime) {
                updateScannerStatus('disconnected', 'Scanner tidak terdeteksi — periksa koneksi USB');
            }
        }, INITIAL_DETECT_TIMEOUT);

        function onScannerActivity() {
            lastScanTime = Date.now();
            if (connectionCheckTimer) { clearTimeout(connectionCheckTimer); connectionCheckTimer = null; }
            if (inactivityTimer) clearTimeout(inactivityTimer);

            updateScannerStatus('connected', 'Scanner terhubung');

            // Start inactivity timer
            inactivityTimer = setTimeout(function() {
                updateScannerStatus('waiting', 'Scanner tidak aktif — coba scan ulang');
            }, INACTIVITY_TIMEOUT);
        }

        // ========================================
        // OPTIMIZED SCANNER INPUT (ZERO-DELAY)
        // ========================================
        // Old approach: keypress (deprecated) + per-keystroke avg interval calc = SLOW
        // New approach: keydown (native) + single buffer timestamp = INSTANT
        let bufferStartTime = 0;
        let autoClearTimer = null;

        // Lightweight focus management (old code had 4+ listeners fighting each other)
        function refocus() {
            if (!isProcessing && document.activeElement !== scannerInput) {
                scannerInput.focus({ preventScroll: true });
            }
        }
        setInterval(refocus, 1500);
        scannerInput.addEventListener('blur', function() {
            if (!isProcessing) setTimeout(refocus, 100);
        });
        document.addEventListener('click', refocus);

        // ========================================
        // MODE TOGGLE
        // ========================================
        window.toggleMode = function() {
            currentMode = currentMode === 'masuk' ? 'keluar' : 'masuk';
            updateModeUI();
        };

        function updateModeUI() {
            const pill = $('#mode-pill');
            const optMasuk = $('#mode-opt-masuk');
            const optKeluar = $('#mode-opt-keluar');

            if (currentMode === 'masuk') {
                pill.css('left', '4px').removeClass('bg-orange-500/25').addClass('bg-white/20');
                optMasuk.removeClass('text-white/30').addClass('text-white');
                optKeluar.removeClass('text-orange-300').addClass('text-white/30');
            } else {
                pill.css('left', 'calc(50%)').removeClass('bg-white/20').addClass('bg-orange-500/25');
                optKeluar.removeClass('text-white/30').addClass('text-orange-300');
                optMasuk.removeClass('text-white').addClass('text-white/30');
            }
            scannerInput.focus({ preventScroll: true });
        }

        // ========================================
        // FAST KEYDOWN HANDLER (replaces slow keypress)
        // ========================================
        scannerInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                e.stopImmediatePropagation();
                if (autoClearTimer) { clearTimeout(autoClearTimer); autoClearTimer = null; }

                const code = this.value.trim();
                this.value = '';

                if (!code || isProcessing) {
                    bufferStartTime = 0;
                    return;
                }

                const elapsed = Date.now() - bufferStartTime;

                // Scanner sends ALL characters within ~100-300ms total
                // Human typing the same number of chars takes 1000ms+
                // Threshold: 500ms for the entire barcode string
                if (code.length >= 3 && elapsed < 500) {
                    onScannerActivity();
                    executeScanRequest(code);
                } else {
                    showNotification('error', 'Input manual tidak diperbolehkan. Gunakan scanner hardware.');
                }

                bufferStartTime = 0;
                return;
            }

            // Record timestamp of FIRST character only (no per-char tracking overhead)
            if (e.key.length === 1 && !bufferStartTime) {
                bufferStartTime = Date.now();
            }
        });

        // Auto-clear stale partial input (handles failed/interrupted scans)
        scannerInput.addEventListener('input', function() {
            if (autoClearTimer) clearTimeout(autoClearTimer);
            autoClearTimer = setTimeout(function() {
                if (scannerInput.value.trim()) {
                    scannerInput.value = '';
                    bufferStartTime = 0;
                }
            }, 2000);
        });

        // ========================================
        // NOTIFICATIONS
        // ========================================
        function showNotification(type, message) {
            const id = 'notif-' + Date.now();
            const dotColor = type === 'success' ? 'bg-green-500' : (type === 'warning' ? 'bg-yellow-500' : 'bg-red-500');

            const html = `
                <div id="${id}" class="notif-entrance flex items-center gap-3 p-4 rounded-xl bg-white pointer-events-auto border border-gray-100 shadow-lg shadow-black/5">
                    <div class="shrink-0 w-2 h-2 rounded-full ${dotColor}"></div>
                    <p class="text-sm font-semibold text-gray-800 leading-snug">${message}</p>
                </div>
            `;

            $('#notification-container').prepend(html);
            setTimeout(() => {
                $(`#${id}`).removeClass('notif-entrance').addClass('notif-exit');
                setTimeout(() => $(`#${id}`).remove(), 500);
            }, 5000);
        }

        // ========================================
        // SCAN REQUEST (AJAX)
        // ========================================
        function executeScanRequest(code) {
            isProcessing = true;
            scannerInput.disabled = true;
            $('#status-indicator').html('<span class="flex h-2 w-2 rounded-full bg-blue-500 animate-ping"></span> Memproses...');

            $.ajax({
                url: "{{ route('attendance.store') }}",
                method: "POST",
                data: JSON.stringify({ qr_code: code, mode: currentMode }),
                contentType: "application/json",
                headers: { "X-CSRF-TOKEN": csrfToken, "Accept": "application/json" },
                success: function(res) { processResponse(res); },
                error: function(xhr) {
                    const res = xhr.responseJSON || { status: 'error', message: 'Koneksi server bermasalah.' };
                    processResponse(res);
                },
                complete: function() {
                    isProcessing = false;
                    scannerInput.disabled = false;
                    scannerInput.value = '';
                    scannerInput.focus({ preventScroll: true });
                    bufferStartTime = 0;
                    $('#status-indicator').html('<span class="flex h-2 w-2 rounded-full bg-green-500 animate-pulse"></span> Siap scan');

                    setTimeout(function() {
                        $('#status-overlay').removeClass('opacity-100 scale-100').addClass('opacity-0 scale-95');
                        $('#status-icon-wrapper').removeClass('scale-100').addClass('scale-50');
                    }, 800);
                }
            });
        }

        function processResponse(data) {
            const titleMap = { success: 'Berhasil', warning: 'Perhatian', error: 'Gagal' };
            showLargeFeedback(data.status, titleMap[data.status] || 'Error', data.message, data.type);
            showNotification(data.status, data.message);
            if (data.status !== 'error') updateHistoryList(data.status, data.message, data.type);
        }

        function showLargeFeedback(status, title, message, type) {
            const overlay = $('#status-overlay');
            const wrapper = $('#status-icon-wrapper');
            const badge = $('#status-type-badge');

            overlay.removeClass('opacity-0 scale-95').addClass('opacity-100 scale-100');
            wrapper.removeClass('scale-50 bg-green-500 bg-yellow-500 bg-red-500').addClass('scale-100');
            
            const colorMap = { success: 'green', warning: 'yellow', error: 'red' };
            const color = colorMap[status] || 'gray';
            wrapper.addClass(`bg-${color}-500`);

            $('#status-title').text(title);
            $('#status-desc').text(message);
            badge.removeClass().addClass(`mt-6 px-5 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-[0.2em] bg-${color}-50 text-${color}-600 border border-${color}-100`);
            badge.text(type === 'masuk' ? 'Absen Masuk' : (type === 'keluar' ? 'Absen Keluar' : 'Info'));
        }

        function updateHistoryList(status, message, type) {
            $('#history-empty').hide();
            scanTotal++;
            $('#scan-count').text(scanTotal);

            const now = new Date();
            const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            const modeName = type === 'masuk' ? 'Masuk' : (type === 'keluar' ? 'Keluar' : 'Info');

            const item = `
                <div class="p-4 rounded-xl border border-gray-100 bg-gray-50 fade-in-up">
                    <div class="flex justify-between items-center mb-1.5">
                        <span class="text-[9px] font-bold uppercase tracking-wider text-gray-500">${modeName}</span>
                        <span class="text-[10px] font-mono text-gray-400">${timeStr}</span>
                    </div>
                    <p class="text-xs font-semibold text-gray-700 leading-relaxed">${message}</p>
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
