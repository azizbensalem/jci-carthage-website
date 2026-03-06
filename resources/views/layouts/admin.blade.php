<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'JCI Carthage - Admin')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        :root {
            --jci-blue: #003366;
            --jci-dark-blue: #002244;
            --jci-light-blue: #0066CC;
            --jci-gold: #FFD700;
            --jci-white: #FFFFFF;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f7fa;
        }
        .jci-primary {
            background-color: var(--jci-blue);
            color: var(--jci-white);
        }
        .jci-primary-text {
            color: var(--jci-blue);
        }
        .jci-btn-primary {
            background-color: var(--jci-blue);
            color: var(--jci-white);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            font-weight: 500;
            transition: background-color 0.2s;
        }
        .jci-btn-primary:hover {
            background-color: var(--jci-light-blue);
        }
        .jci-card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
        .sidebar-item {
            transition: all 0.2s ease;
        }
        .sidebar-item:hover {
            transform: translateX(4px);
        }
        .sidebar-item.active {
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.05) 100%);
            border-left: 3px solid var(--jci-gold);
        }
    </style>
    @stack('styles')
</head>
<body>
    @auth
    <div class="flex h-screen bg-gray-50">
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 text-white shadow-2xl transform -translate-x-full lg:translate-x-0 sidebar-transition" style="background: linear-gradient(to bottom, var(--jci-blue), var(--jci-dark-blue));">
            <div class="flex flex-col h-full">
                <!-- Logo Section -->
                <div class="flex items-center justify-between h-20 px-6" style="border-bottom: 1px solid rgba(255,255,255,0.1); background-color: rgba(0,0,0,0.2);">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-lg flex items-center justify-center shadow-lg">
                            <span class="text-blue-900 font-bold text-lg">JC</span>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold tracking-tight">JCI Carthage</h1>
                            <p class="text-xs text-blue-300">Administration</p>
                        </div>
                    </div>
                    <button id="sidebar-close" class="lg:hidden text-white hover:text-blue-200 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}" class="sidebar-item flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('dashboard') ? 'active bg-blue-700/30' : 'hover:bg-blue-700/20' }} transition">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span class="font-medium">Dashboard</span>
                    </a>

                    @if(auth()->user()->isAdmin())
                    <!-- Administration Section -->
                    <div class="pt-6 pb-2">
                        <p class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider mb-3 flex items-center">
                            <span class="w-1 h-4 bg-yellow-400 rounded-full mr-2"></span>
                            Administration
                        </p>
                        
                        <a href="{{ route('admin.users.index') }}" class="sidebar-item flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.users.*') ? 'active bg-blue-700/30' : 'hover:bg-blue-700/20' }} transition">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <span class="font-medium">Membres</span>
                        </a>

                        <a href="{{ route('admin.carousels.index') }}" class="sidebar-item flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.carousels.*') ? 'active bg-blue-700/30' : 'hover:bg-blue-700/20' }} transition">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium">Carrousel</span>
                        </a>

                        <a href="{{ route('admin.events.index') }}" class="sidebar-item flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.events.*') ? 'active bg-blue-700/30' : 'hover:bg-blue-700/20' }} transition">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium">Événements</span>
                        </a>

                        <a href="{{ route('admin.partners.index') }}" class="sidebar-item flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.partners.*') ? 'active bg-blue-700/30' : 'hover:bg-blue-700/20' }} transition">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium">Partenaires</span>
                        </a>

                        <a href="{{ route('admin.blog.index') }}" class="sidebar-item flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.blog.*') ? 'active bg-blue-700/30' : 'hover:bg-blue-700/20' }} transition">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                            <span class="font-medium">Blog</span>
                        </a>

                        <a href="{{ route('admin.facebook.import') }}" class="sidebar-item flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.facebook.*') ? 'active bg-blue-700/30' : 'hover:bg-blue-700/20' }} transition">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"></path>
                            </svg>
                            <span class="font-medium">Import Facebook</span>
                        </a>
                    </div>

                    <!-- Settings Section -->
                    <div class="pt-6 pb-2">
                        <p class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider mb-3 flex items-center">
                            <span class="w-1 h-4 bg-yellow-400 rounded-full mr-2"></span>
                            Paramètres
                        </p>
                        
                        <a href="{{ route('admin.settings.index') }}" class="sidebar-item flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.settings.*') || request()->routeIs('admin.google-account.*') ? 'active bg-blue-700/30' : 'hover:bg-blue-700/20' }} transition">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="font-medium">Paramètres</span>
                        </a>
                    </div>
                    @endif
                </nav>

                <!-- User Section -->
                <div class="px-4 py-4" style="border-top: 1px solid rgba(255,255,255,0.1); background-color: rgba(0,0,0,0.2);">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center shadow-lg flex-shrink-0">
                            <span class="text-blue-900 font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <p class="text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-blue-300 truncate">{{ ucfirst(str_replace('-', ' ', auth()->user()->role)) }}</p>
                        </div>
                    </div>
                    
                    <!-- Language Switcher -->
                    <div class="mb-3 flex items-center justify-between rounded-lg p-2" style="background-color: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1);">
                        <span class="text-xs text-blue-300">{{ __('website.language.switch') }}:</span>
                        <div class="flex space-x-1">
                            <a href="{{ route('lang.switch', 'fr') }}" class="px-2 py-1 rounded text-xs font-medium transition {{ app()->getLocale() === 'fr' ? 'bg-yellow-400 text-blue-900' : 'text-blue-300 hover:bg-blue-700/30' }}">
                                FR
                            </a>
                            <a href="{{ route('lang.switch', 'en') }}" class="px-2 py-1 rounded text-xs font-medium transition {{ app()->getLocale() === 'en' ? 'bg-yellow-400 text-blue-900' : 'text-blue-300 hover:bg-blue-700/30' }}">
                                EN
                            </a>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center px-4 py-2.5 bg-red-600/20 hover:bg-red-600/30 border border-red-500/30 rounded-lg transition text-sm font-medium backdrop-blur-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            {{ __('website.nav.logout') }}
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Sidebar Overlay (Mobile) -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden backdrop-blur-sm"></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden lg:ml-72">
            <!-- Top Bar (Mobile) -->
            <header class="lg:hidden text-white shadow-lg sticky top-0 z-30" style="background: linear-gradient(to right, var(--jci-blue), var(--jci-dark-blue));">
                <div class="flex items-center justify-between h-16 px-4">
                    <button id="sidebar-toggle" class="text-white hover:text-blue-200 transition p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-yellow-400 rounded-lg flex items-center justify-center">
                            <span class="text-blue-900 font-bold text-sm">JC</span>
                        </div>
                        <h1 class="text-lg font-bold">JCI Carthage</h1>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('lang.switch', 'fr') }}" class="px-2 py-1 rounded text-xs font-medium transition {{ app()->getLocale() === 'fr' ? 'bg-yellow-400 text-blue-900' : 'text-blue-300 hover:bg-blue-700/30' }}">
                            FR
                        </a>
                        <a href="{{ route('lang.switch', 'en') }}" class="px-2 py-1 rounded text-xs font-medium transition {{ app()->getLocale() === 'en' ? 'bg-yellow-400 text-blue-900' : 'text-blue-300 hover:bg-blue-700/30' }}">
                            EN
                        </a>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-4 lg:p-6">
                @if(session('success'))
                <div class="mb-4 animate-fade-in">
                    <div class="bg-green-50 border-l-4 border-green-400 text-green-700 px-4 py-3 rounded shadow-sm" role="alert">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-4 animate-fade-in">
                    <div class="bg-red-50 border-l-4 border-red-400 text-red-700 px-4 py-3 rounded shadow-sm" role="alert">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium">{{ session('error') }}</span>
                        </div>
                    </div>
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Sidebar toggle for mobile
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebarClose = document.getElementById('sidebar-close');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
            document.body.style.overflow = '';
        }

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', openSidebar);
        }

        if (sidebarClose) {
            sidebarClose.addEventListener('click', closeSidebar);
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', closeSidebar);
        }

        // Close sidebar on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSidebar();
            }
        });

        // Close sidebar when clicking on a link (mobile)
        if (window.innerWidth < 1024) {
            document.querySelectorAll('#sidebar a').forEach(link => {
                link.addEventListener('click', () => {
                    setTimeout(closeSidebar, 100);
                });
            });
        }
    </script>
    @stack('scripts')
    @endauth
</body>
</html>
