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
<body class="font-sans antialiased text-gray-800 bg-[#F3F4F6] dark:bg-[#0f172a] dark:text-gray-100 selection:bg-[#DFFF00]">
    <div class="h-screen flex overflow-hidden relative">

        <!-- Include Sidebar -->
        @include('layouts.navigation')

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 sm:ml-72 relative z-10 h-full overflow-hidden transition-all duration-300">
            
            <!-- Top App Bar (Clean Style) -->
            <header class="w-full px-4 sm:px-8 pt-4 pb-2 z-40 transition-all duration-300 sticky top-0">
                <nav class="bg-white dark:bg-gray-800 border-t-[8px] border-t-[#DFFF00] border-b border-gray-200 dark:border-gray-700 px-8 py-4 flex justify-between items-center gap-4 shadow-sm w-full">
                    
                    <!-- Mobile Toggle -->
                    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-xl sm:hidden hover:bg-gray-100/50 focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-colors">
                        <span class="sr-only">Buka menu</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path></svg>
                    </button>

                    <!-- Page Header (Dynamic) -->
                    <div class="flex-1 ml-2 sm:ml-0 overflow-hidden flex items-center">
                        @hasSection('header')
                            <div class="truncate">@yield('header')</div>
                        @else
                            <h2 class="font-extrabold text-xl text-gray-800 dark:text-white capitalize tracking-tight truncate">
                                {{ str_replace('.', ' ', Route::currentRouteName()) }}
                            </h2>
                        @endif
                    </div>

                    <!-- Right Side Actions & User -->
                    <div class="flex items-center space-x-2 md:space-x-4">
                        <!-- Notification Bubble -->
                        <div class="relative" id="notification-wrapper">
                            <button id="notification-btn" class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300 hover:bg-gray-200 transition-colors relative flex items-center justify-center">
                                <span id="notification-dot" class="{{ Auth::user()->unreadNotifications->count() > 0 ? '' : 'hidden' }} absolute top-2 right-2.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white dark:border-gray-800"></span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            </button>

                            <!-- Notification Dropdown -->
                            <div id="notification-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden">
                                <div class="px-4 py-3 border-b border-gray-50 flex justify-between items-center">
                                    <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Notifikasi</h3>
                                    <button id="mark-read-btn" class="text-[10px] font-bold text-blue-600 hover:text-blue-700 uppercase tracking-tighter">Tandai sudah baca</button>
                                </div>
                                <div id="notification-list" class="max-h-80 overflow-y-auto divide-y divide-gray-50">
                                    <!-- Items will be injected here -->
                                    <div class="px-4 py-8 text-center text-gray-400 text-xs italic">
                                        Memuat notifikasi...
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- User Profile Pill -->
                        <div class="relative">
                            <button type="button" class="flex items-center gap-3 pl-2 pr-4 py-1.5 bg-white dark:bg-gray-700 border border-gray-100 dark:border-gray-600 rounded-full focus:ring-4 focus:ring-gray-200 transition-all hover:bg-gray-50 shadow-sm" data-dropdown-toggle="dropdown-user">
                                <img class="w-8 h-8 rounded-full object-cover shadow-sm bg-gray-100" src="{{ Auth::user()->avatar ? asset(Auth::user()->avatar) : asset('images/avatars/default-avatar.svg') }}" alt="user photo">
                                <div class="hidden md:flex flex-col items-start overflow-hidden">
                                    <span class="text-xs font-bold text-gray-900 dark:text-white truncate w-24 text-left">{{ explode(' ', Auth::user()->name)[0] ?? 'Guest' }}</span>
                                    <span class="text-[10px] text-gray-500 font-medium capitalize">{{ Auth::user()->role ?? 'User' }}</span>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            
                            <!-- Dropdown -->
                            <div class="z-50 hidden my-4 w-56 text-base list-none bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl rounded-2xl shadow-[0_10px_40px_rgb(0,0,0,0.1)] border border-white/50 dark:border-gray-700/50" id="dropdown-user">
                                <div class="px-4 py-4 border-b border-gray-100 dark:border-gray-700/50">
                                    <p class="text-sm font-bold text-gray-900 dark:text-white" role="none">{{ Auth::user()->name ?? 'Guest' }}</p>
                                    <p class="text-xs font-medium text-gray-500 truncate mt-0.5" role="none">{{ Auth::user()->email ?? 'guest@example.com' }}</p>
                                </div>
                                <ul class="p-2 space-y-1" role="none">
                                    <li>
                                        <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 font-medium rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            Update Profile
                                        </a>
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="w-full flex items-center px-4 py-2.5 text-sm text-red-600 font-medium rounded-xl hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                                Keluar
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>

            <!-- Scrollable Page Content -->
            <main class="flex-1 overflow-y-auto w-full">
                <div class="p-8 h-full">
                    @yield('content')
                </div>
            </main>
            
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('notification-btn');
            const dropdown = document.getElementById('notification-dropdown');
            const list = document.getElementById('notification-list');
            const dot = document.getElementById('notification-dot');
            const markBtn = document.getElementById('mark-read-btn');

            // Toggle Dropdown
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdown.classList.toggle('hidden');
                if (!dropdown.classList.contains('hidden')) {
                    fetchNotifications();
                }
            });

            // Close on click outside
            document.addEventListener('click', function() {
                dropdown.classList.add('hidden');
            });

            dropdown.addEventListener('click', (e) => e.stopPropagation());

            function fetchNotifications() {
                fetch('/api/notifications')
                    .then(res => res.json())
                    .then(data => {
                        renderNotifications(data.notifications);
                        if (data.unreadCount > 0) {
                            dot.classList.remove('hidden');
                        } else {
                            dot.classList.add('hidden');
                        }
                    });
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
                    dot.classList.add('hidden');
                    fetchNotifications();
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
        });
    </script>
</body>
</html>
