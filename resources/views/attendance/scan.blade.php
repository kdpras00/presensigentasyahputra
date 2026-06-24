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
                    <span id="live-clock" class="text-white/80 text-sm font-mono"></span>
                </div>

                <div class="flex items-center gap-3">
                    <button id="mode-toggle" onclick="toggleMode()" aria-label="Ubah Mode Presensi" class="relative flex items-center p-1 rounded-xl bg-white/10 border border-white/10 transition-all duration-300 hover:bg-white/15 cursor-pointer">
                        <div id="mode-pill" class="absolute top-1 h-[calc(100%-8px)] rounded-lg transition-all duration-300 ease-in-out
                            {{ $mode === 'masuk' ? 'bg-white/20' : 'bg-orange-500/25' }}"
                            style="width: calc(50% - 4px); left: {{ $mode === 'masuk' ? '4px' : 'calc(50%)' }};"></div>
                        <div id="mode-opt-masuk" class="relative z-10 px-4 py-2 rounded-lg text-xs font-bold transition-colors duration-300
                            {{ $mode === 'masuk' ? 'text-white' : 'text-white/70' }}">
                            MASUK
                        </div>
                        <div id="mode-opt-keluar" class="relative z-10 px-4 py-2 rounded-lg text-xs font-bold transition-colors duration-300
                            {{ $mode === 'keluar' ? 'text-orange-300' : 'text-white/70' }}">
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
                            <div class="mb-6 text-center">
                                <p id="session-label" class="text-sm font-semibold text-gray-500 transition-colors duration-500">Memuat jadwal...</p>
                            </div>

                            <div class="relative group">
                                <input type="text" id="scanner-input" aria-label="Scanner Input" autocomplete="off" autofocus
                                    class="w-full px-6 py-5 bg-gray-100 border-2 border-transparent rounded-xl text-center font-mono text-2xl font-bold text-[#345344] focus:bg-white focus:border-[#345344] focus:ring-4 focus:ring-[#345344]/5 transition-all duration-300 placeholder:text-gray-500"
                                    placeholder="Scan Barcode...">
                            </div>
                        </div>
                    </div>

                    <!-- Status Feedback Overlay -->
                    <div id="status-overlay" class="absolute inset-0 z-30 flex flex-col items-center justify-center bg-white/98 backdrop-blur-md opacity-0 pointer-events-none transition-all duration-500 scale-95" tabindex="-1">
                        <div id="status-icon-wrapper" class="w-20 h-20 rounded-2xl flex items-center justify-center mb-6 transition-transform duration-700 scale-50"></div>
                        <h3 id="status-title" class="text-3xl font-bold text-gray-800 mb-2 tracking-tight"></h3>
                        <p id="status-desc" class="text-base text-gray-600 text-center px-12 font-medium max-w-md"></p>
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
                            <p class="text-xs text-gray-600 font-medium">Belum ada scan</p>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function updateClock() {
            const now = new Date();
            document.getElementById('live-clock').innerText = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        }
        setInterval(updateClock, 1000);
        updateClock();

        @if(!$error)
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

            let label, textClass;

            if (cur >= masukStart && cur < masukEnd) {
                label = 'Sesi Masuk Tepat Waktu';
                textClass = 'text-gray-600';
            } else if (cur >= masukEnd && cur < keluarStart) {
                label = 'Sesi Masuk Terlambat';
                textClass = 'text-gray-600';
            } else if (cur >= keluarStart && cur <= keluarEnd) {
                label = 'Sesi Pulang Dibuka';
                textClass = 'text-gray-600';
            } else if (cur > keluarEnd) {
                label = 'Sesi Presensi Hari Ini Berakhir';
                textClass = 'text-red-500';
            } else {
                label = 'Sesi Presensi Belum Dimulai';
                textClass = 'text-gray-500';
            }

            const lbl = document.getElementById('session-label');
            if (lbl) {
                lbl.className = 'text-sm font-semibold transition-colors duration-500 ' + textClass;
                lbl.textContent = label;
            }
        }

        updateSessionStatus();
        setInterval(updateSessionStatus, 30000); // re-check every 30s
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        let currentMode = '{{ $mode }}';
        let scanTotal = 0;
        let isProcessing = false;
        const scannerInput = document.getElementById('scanner-input');

        function onScannerActivity() {
        }

        let bufferStartTime = 0;
        let autoClearTimer = null;

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

        window.toggleMode = function() {
            currentMode = currentMode === 'masuk' ? 'keluar' : 'masuk';
            updateModeUI();
        };

        function updateModeUI() {
            const pill = document.getElementById('mode-pill');
            const optMasuk = document.getElementById('mode-opt-masuk');
            const optKeluar = document.getElementById('mode-opt-keluar');

            if (currentMode === 'masuk') {
                pill.style.left = '4px';
                pill.classList.remove('bg-orange-500/25');
                pill.classList.add('bg-white/20');
                
                optMasuk.classList.remove('text-white/30');
                optMasuk.classList.add('text-white');
                
                optKeluar.classList.remove('text-orange-300');
                optKeluar.classList.add('text-white/30');
            } else {
                pill.style.left = 'calc(50%)';
                pill.classList.remove('bg-white/20');
                pill.classList.add('bg-orange-500/25');
                
                optKeluar.classList.remove('text-white/30');
                optKeluar.classList.add('text-orange-300');
                
                optMasuk.classList.remove('text-white');
                optMasuk.classList.add('text-white/30');
            }
            scannerInput.focus({ preventScroll: true });
        }

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

        function showNotification(type, message) {
            const id = 'notif-' + Date.now();
            const textColor = type === 'success' ? 'text-green-600' : (type === 'warning' ? 'text-yellow-600' : 'text-red-600');

            const container = document.getElementById('notification-container');
            container.insertAdjacentHTML('afterbegin', `
                <div id="${id}" class="notif-entrance p-4 rounded-xl bg-white pointer-events-auto border border-gray-100 shadow-lg shadow-black/5">
                    <p class="text-sm font-semibold ${textColor} leading-snug">${message}</p>
                </div>
            `);
            
            setTimeout(() => {
                const el = document.getElementById(id);
                if(el) {
                    el.classList.remove('notif-entrance');
                    el.classList.add('notif-exit');
                    setTimeout(() => el.remove(), 500);
                }
            }, 5000);
        }

        function executeScanRequest(code) {
            isProcessing = true;
            scannerInput.disabled = true;
            scannerInput.placeholder = 'Memproses...';

            fetch("{{ route('attendance.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({ qr_code: code, mode: currentMode })
            })
            .then(response => response.json().then(data => ({ ok: response.ok, data })))
            .then(result => {
                if (!result.ok) throw result.data;
                processResponse(result.data);
            })
            .catch(error => {
                const res = error.message ? error : { status: 'error', message: 'Koneksi server bermasalah.' };
                processResponse(res);
            })
            .finally(() => {
                isProcessing = false;
                scannerInput.disabled = false;
                scannerInput.value = '';
                scannerInput.placeholder = 'Scan Barcode...';
                scannerInput.focus({ preventScroll: true });
                bufferStartTime = 0;

                setTimeout(function() {
                    const overlay = document.getElementById('status-overlay');
                    const wrapper = document.getElementById('status-icon-wrapper');
                    if(overlay) {
                        overlay.classList.remove('opacity-100', 'scale-100');
                        overlay.classList.add('opacity-0', 'scale-95');
                    }
                    if(wrapper) {
                        wrapper.classList.remove('scale-100');
                        wrapper.classList.add('scale-50');
                    }
                }, 800);
            });
        }

        function processResponse(data) {
            const titleMap = { success: 'Berhasil', warning: 'Perhatian', error: 'Gagal' };
            showLargeFeedback(data.status, titleMap[data.status] || 'Error', data.message, data.type);
            showNotification(data.status, data.message);
            if (data.status !== 'error') updateHistoryList(data.status, data.message, data.type);
        }

        function showLargeFeedback(status, title, message, type) {
            const overlay = document.getElementById('status-overlay');
            const wrapper = document.getElementById('status-icon-wrapper');
            const badge = document.getElementById('status-type-badge');

            overlay.classList.remove('opacity-0', 'scale-95');
            overlay.classList.add('opacity-100', 'scale-100');
            
            wrapper.classList.remove('scale-50', 'bg-green-500', 'bg-yellow-500', 'bg-red-500');
            wrapper.classList.add('scale-100');
            
            const colorMap = { success: 'green', warning: 'yellow', error: 'red' };
            const color = colorMap[status] || 'gray';
            wrapper.classList.add(`bg-${color}-500`);

            document.getElementById('status-title').innerText = title;
            document.getElementById('status-desc').innerText = message;
            
            badge.className = `mt-6 px-5 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-[0.2em] bg-${color}-50 text-${color}-600 border border-${color}-100`;
            badge.innerText = type === 'masuk' ? 'Absen Masuk' : (type === 'keluar' ? 'Absen Keluar' : 'Info');
        }

        function updateHistoryList(status, message, type) {
            const emptyHistory = document.getElementById('history-empty');
            if (emptyHistory) emptyHistory.style.display = 'none';
            
            scanTotal++;
            document.getElementById('scan-count').innerText = scanTotal;

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

            const container = document.getElementById('scan-history');
            container.insertAdjacentHTML('afterbegin', item);
            if (container.children.length > 15) {
                container.lastElementChild.remove();
            }
        }
        @endif
    });
</script>
@endsection
