<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Presensi Genta Syaputra') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Tom Select -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    
    <style>
        /* Tom Select Premium Customization */
        .ts-control {
            border: transparent !important;
            background-color: #F3F4F6 !important;
            padding: 1rem !important;
            border-radius: 0.75rem !important;
            font-size: 0.875rem !important;
            transition: all 0.3s ease !important;
            font-weight: 500 !important;
            color: #111827 !important;
        }
        .ts-wrapper.focus .ts-control {
            box-shadow: 0 0 0 4px rgba(52, 83, 68, 0.1) !important;
            border-color: #345344 !important;
            background-color: white !important;
        }
        .ts-dropdown {
            border-radius: 1rem !important;
            border: 1px solid #F3F4F6 !important;
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1) !important;
            padding: 0.5rem !important;
            margin-top: 0.5rem !important;
            overflow: hidden !important;
            background: white !important;
        }
        .ts-dropdown .active {
            background-color: #345344 !important;
            color: white !important;
            border-radius: 0.5rem !important;
        }
        .ts-dropdown .option {
            padding: 0.75rem 1rem !important;
            cursor: pointer;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
        }
        /* Custom Arrow Animation */
        .ts-wrapper .ts-control::after {
            border-color: #345344 transparent transparent transparent !important;
            transition: transform 0.3s ease !important;
            margin-top: -2px !important;
            right: 20px !important;
        }
        .ts-wrapper.dropdown-active .ts-control::after {
            transform: rotate(180deg) !important;
            margin-top: -2px !important;
        }
        .ts-wrapper .ts-control, .ts-wrapper .ts-control input {
            cursor: pointer !important;
        }
    </style>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/gentalogoico.png') }}">
    
    <style>
        /* Smooth scrolling for the whole app */
        html { scroll-behavior: smooth; }
        /* Custom scrollbar for webkit */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .dark ::-webkit-scrollbar-thumb { background: #475569; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="font-sans antialiased text-gray-800 bg-[#F3F4F6] dark:bg-[#0f172a] dark:text-gray-100 selection:bg-[#345344] selection:text-white">
    <div class="h-screen flex overflow-hidden relative">

        <!-- Include Sidebar -->
        @include('layouts.navigation')

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 sm:ml-60 relative z-10 h-full overflow-hidden transition-all duration-300">
            
            <!-- Main Scrollable Area -->
            <main class="flex-1 overflow-y-auto w-full bg-[#F1F5F9] relative scroll-smooth">
                
                <!-- Navbar Wrapper (Non-sticky, scrolls with content) -->
                <div class="w-full px-8 pt-8 pb-4">
                    <header class="w-full px-8 py-5 bg-[#345344] border border-white/10 flex justify-between items-center rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.1)]">
                        
                        <!-- Page Header -->
                        <div class="flex items-center gap-4">
                            <!-- Mobile Toggle -->
                            <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-white/50 rounded-xl sm:hidden hover:bg-white/10 transition-colors">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path></svg>
                            </button>
                            
                            @hasSection('header')
                                <div class="truncate text-white">@yield('header')</div>
                            @else
                                <h2 class="font-black text-xl text-white capitalize tracking-tighter truncate">
                                    {{ str_replace('.', ' ', Route::currentRouteName()) }}
                                </h2>
                            @endif
                        </div>

                        <!-- Right Side Actions & User -->
                        <div class="flex items-center gap-8">
                            <!-- Notification Button -->
                            <div class="relative" id="notification-wrapper">
                                <button id="notification-btn" class="relative p-3 text-white/50 hover:bg-white/10 active:bg-white/20 rounded-2xl transition-all duration-300 group">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                </button>
                                
                                <!-- Notification Dropdown -->
                                <div id="notification-dropdown" class="hidden absolute -right-10 top-full mt-4 w-80 bg-white/90 backdrop-blur-xl rounded-[2.5rem] shadow-[0_30px_100px_rgba(0,0,0,0.2)] border border-white/20 z-50 overflow-hidden origin-top-right">
                                    <div class="px-6 py-5 border-b border-gray-100/50 flex justify-between items-center bg-gray-50/50">
                                        <h3 class="text-[10px] font-black text-[#345344] uppercase tracking-[0.2em]">Notifikasi</h3>
                                        <button id="mark-read-btn" class="text-[9px] font-black text-blue-600 hover:text-blue-700 uppercase tracking-widest">Tandai Baca</button>
                                    </div>
                                    <div id="notification-list" class="max-h-80 overflow-y-auto divide-y divide-gray-50/50">
                                        <div class="px-4 py-12 text-center text-gray-400 text-[11px] font-bold italic">Memuat...</div>
                                    </div>
                                </div>
                            </div>

                            <!-- User Profile Dropdown -->
                            <div class="relative" id="user-wrapper">
                                <button type="button" id="user-menu-btn" class="flex items-center hover:opacity-80 transition-opacity">
                                    <img class="w-10 h-10 rounded-full object-cover border border-white/20 shadow-sm" src="{{ Auth::user()->avatar ? asset(Auth::user()->avatar) : asset('images/avatars/default-avatar.svg') }}" alt="user photo">
                                </button>
                                
                                <div class="z-50 hidden absolute -right-14 top-full mt-4 w-64 bg-white/90 backdrop-blur-xl rounded-[2.5rem] shadow-[0_30px_100px_rgba(0,0,0,0.2)] border border-white/20 origin-top-right" id="dropdown-user">
                                    <div class="px-6 py-5 border-b border-gray-100/50">
                                        <p class="text-base font-bold text-[#345344] leading-none mb-1">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-400 truncate tracking-tight">{{ Auth::user()->email }}</p>
                                    </div>
                                    <ul class="p-3 space-y-1" role="none">
                                        <li>
                                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-[#345344]/5 hover:text-[#345344] rounded-2xl transition-all group" role="menuitem">
                                                <div class="w-8 h-8 rounded-xl bg-gray-50 flex items-center justify-center mr-3 group-hover:bg-[#345344]/10 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                </div>
                                                Profil Saya
                                            </a>
                                        </li>
                                        <li>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="w-full flex items-center px-4 py-3 text-sm font-medium text-red-500 hover:bg-red-50 active:bg-red-100 rounded-2xl transition-all group" role="menuitem">
                                                    <div class="w-8 h-8 rounded-xl bg-red-50 flex items-center justify-center mr-3 group-hover:bg-red-100 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                                    </div>
                                                    Keluar
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </header>
                </div>

                <!-- Page Content -->
                <div class="px-8 pt-2 pb-12 w-full">
                    @yield('content')
                </div>
            </main>
            
        </div>
    </div>
    <!-- Global Notification Container -->
    <div id="global-notif-container" class="fixed top-8 right-8 z-[100] w-full max-w-sm flex flex-col gap-3 pointer-events-none"></div>

    <style>
        .global-notif-toast {
            animation: toast-slide-in 0.5s cubic-bezier(0.19, 1, 0.22, 1) forwards;
            pointer-events: auto;
        }
        .toast-slide-out {
            animation: toast-slide-out 0.5s cubic-bezier(0.19, 1, 0.22, 1) forwards;
        }
        @keyframes toast-slide-in {
            from { opacity: 0; transform: translateY(-20px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes toast-slide-out {
            from { opacity: 1; transform: translateY(0) scale(1); }
            to { opacity: 0; transform: translateY(-20px) scale(0.95); }
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('notification-btn');
            const dropdown = document.getElementById('notification-dropdown');
            const list = document.getElementById('notification-list');
            const dot = document.getElementById('notification-dot');
            const markBtn = document.getElementById('mark-read-btn');
            let lastNotifId = null;
            let isFirstLoad = true;

            // Toggle Dropdown
            if (btn) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdown.classList.toggle('hidden');
                    if (!dropdown.classList.contains('hidden')) {
                        fetchNotifications(false);
                        if (userDropdown) userDropdown.classList.add('hidden'); // Tutup profil jika notif dibuka
                    }
                });
            }

            // Profile Dropdown Toggle
            const userBtn = document.getElementById('user-menu-btn');
            const userDropdown = document.getElementById('dropdown-user');

            if (userBtn) {
                userBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userDropdown.classList.toggle('hidden');
                    if (dropdown) dropdown.classList.add('hidden'); // Close other
                });
            }

            // Close on click outside
            document.addEventListener('click', function() {
                if (dropdown) dropdown.classList.add('hidden');
                if (userDropdown) userDropdown.classList.add('hidden');
            });

            if (dropdown) dropdown.addEventListener('click', (e) => e.stopPropagation());
            if (userDropdown) userDropdown.addEventListener('click', (e) => e.stopPropagation());

            function fetchNotifications(showToast = true) {
                fetch('/api/notifications')
                    .then(res => res.json())
                    .then(data => {
                        const notifications = data.notifications;
                        
                        // Update UI
                        if (list) renderNotifications(notifications);
                        
                        if (data.unreadCount > 0) {
                            if (dot) dot.classList.remove('hidden');
                        } else {
                            if (dot) dot.classList.add('hidden');
                        }

                        // Check for new notifications to show toast
                        if (notifications.length > 0) {
                            const newest = notifications[0];
                            if (lastNotifId && newest.id !== lastNotifId && showToast) {
                                // New notification detected
                                showGlobalToast(newest.data);
                            }
                            lastNotifId = newest.id;
                        }
                        
                        isFirstLoad = false;
                    });
            }

            function showGlobalToast(data) {
                const id = 'toast-' + Date.now();
                const type = data.type || 'info';
                
                // Icon mapping based on type
                let iconSvg = '<svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                if (type === 'success') iconSvg = '<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>';
                if (type === 'warning') iconSvg = '<svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>';
                if (type === 'error') iconSvg = '<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>';

                const html = `
                    <div id="${id}" class="global-notif-toast flex items-start p-5 rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.1)] bg-white w-full max-w-sm border border-gray-100">
                        <div class="flex-shrink-0 w-10 h-10 rounded-2xl bg-gray-50 flex items-center justify-center mr-4">
                            ${iconSvg}
                        </div>
                        <div class="flex-grow">
                            <h4 class="text-[10px] font-black uppercase tracking-widest mb-1 text-gray-400">${data.title}</h4>
                            <p class="text-xs font-bold text-gray-800 leading-tight">${data.message}</p>
                            <p class="text-[9px] text-gray-400 font-bold mt-2 uppercase tracking-tighter">Sistem Presensi • Baru Saja</p>
                        </div>
                        <button onclick="$(this).closest('.global-notif-toast').remove()" class="ml-4 text-gray-300 hover:text-gray-500 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                `;

                $('#global-notif-container').prepend(html);
                
                // Auto hide
                setTimeout(() => {
                    $(`#${id}`).addClass('toast-slide-out');
                    setTimeout(() => $(`#${id}`).remove(), 500);
                }, 8000);
            }

            function renderNotifications(notifications) {
                if (notifications.length === 0) {
                    list.innerHTML = `<div class="px-4 py-8 text-center text-gray-400 text-xs italic">Tidak ada notifikasi.</div>`;
                    return;
                }

                list.innerHTML = notifications.map(n => `
                    <div class="px-4 py-3 hover:bg-gray-50/50 transition-colors ${!n.read_at ? 'bg-blue-50/40 border-l-4 border-blue-500' : ''}">
                        <div class="flex justify-between items-start mb-1">
                            <span class="text-[10px] font-bold text-gray-900 uppercase tracking-tight">${n.data.title}</span>
                            <span class="text-[9px] font-medium text-gray-400 font-mono">${n.data.time || ''}</span>
                        </div>
                        <p class="text-xs text-gray-600 leading-snug">${n.data.message}</p>
                    </div>
                `).join('');
            }

            // Initial load
            fetchNotifications(false);

            // Poll for new notifications every 10 seconds
            setInterval(() => fetchNotifications(true), 10000);

            if (markBtn) {
                markBtn.addEventListener('click', function() {
                    const originalText = markBtn.innerText;
                    markBtn.innerText = 'Memproses...';
                    markBtn.disabled = true;
                    markBtn.classList.add('opacity-50', 'cursor-not-allowed');

                    fetch('/api/notifications/read', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Gagal menandai sudah baca');
                        return res.json();
                    })
                    .then(() => {
                        if (dot) dot.classList.add('hidden');
                        fetchNotifications(false);
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Gagal menandai notifikasi. Silakan coba lagi.');
                    })
                    .finally(() => {
                        markBtn.innerText = originalText;
                        markBtn.disabled = false;
                        markBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    });
                });
            }
        });
    </script>
</body>
</html>
