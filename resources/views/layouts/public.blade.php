<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'JCI Carthage - Junior Chamber International')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        :root {
            --jci-blue: #0097D7;
            --jci-black: #130F2D;
            --jci-navy: #1F4789;
            --jci-teal: #57BCBC;
            --jci-yellow: #EFC40F;
            --jci-white: #FFFFFF;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
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
            padding: 0.75rem 1.75rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .jci-btn-primary:hover {
            background-color: var(--jci-navy);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 151, 215, 0.3);
        }
        .jci-gradient {
            background: linear-gradient(135deg, var(--jci-blue) 0%, var(--jci-navy) 100%);
        }
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: white;
            transition: width 0.3s ease;
        }
        .nav-link:hover::after {
            width: 100%;
        }
        .nav-link.active::after {
            width: 100%;
        }
    </style>
    @stack('styles')
</head>
<body class="antialiased">
    <!-- Navigation -->
    <nav class="bg-[#130F2D] shadow-2xl fixed top-0 left-0 right-0 z-50 border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                            <!-- JCI Logo PNG -->
                            <img src="{{ asset('images/jci-carthage.png') }}" alt="JCI Carthage Logo" class="w-20 h-10 object-contain">
                        </a>
                    </div>
                    <div class="hidden sm:ml-8 sm:flex sm:space-x-8">
                        <a href="{{ route('home') }}" class="nav-link text-sm font-semibold tracking-wide {{ request()->routeIs('home') ? 'text-white active' : 'text-gray-300 hover:text-white' }}">
                            {{ strtoupper(__('website.nav.home')) }}
                        </a>
                        <a href="{{ route('about') }}" class="nav-link text-sm font-semibold tracking-wide {{ request()->routeIs('about') ? 'text-white active' : 'text-gray-300 hover:text-white' }}">
                            {{ strtoupper(__('website.nav.about')) }}
                        </a>
                        <a href="{{ route('activities') }}" class="nav-link text-sm font-semibold tracking-wide {{ request()->routeIs('activities') ? 'text-white active' : 'text-gray-300 hover:text-white' }}">
                            {{ strtoupper(__('website.nav.activities')) }}
                        </a>
                        <a href="{{ route('blog.index') }}" class="nav-link text-sm font-semibold tracking-wide {{ request()->routeIs('blog.*') ? 'text-white active' : 'text-gray-300 hover:text-white' }}">
                            BLOG
                        </a>
                        <a href="{{ route('contact') }}" class="nav-link text-sm font-semibold tracking-wide {{ request()->routeIs('contact') ? 'text-white active' : 'text-gray-300 hover:text-white' }}">
                            {{ strtoupper(__('website.nav.contact')) }}
                        </a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Language Switcher -->
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('lang.switch', 'fr') }}" class="text-sm font-medium {{ app()->getLocale() == 'fr' ? 'text-white border-b-2 border-white' : 'text-gray-300 hover:text-white' }}">
                            FR
                        </a>
                        <span class="text-gray-400">|</span>
                        <a href="{{ route('lang.switch', 'en') }}" class="text-sm font-medium {{ app()->getLocale() == 'en' ? 'text-white border-b-2 border-white' : 'text-gray-300 hover:text-white' }}">
                            EN
                        </a>
                    </div>
                    @auth
                        <a href="{{ route('dashboard') }}" class="hidden sm:block text-sm text-gray-300 hover:text-white">{{ __('website.nav.dashboard') }}</a>
                        <form method="POST" action="{{ route('logout') }}" class="hidden sm:inline">
                            @csrf
                            <button type="submit" class="text-sm text-gray-300 hover:text-white">{{ __('website.nav.logout') }}</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:block text-sm text-gray-300 hover:text-white">{{ __('website.nav.login') }}</a>
                    @endauth
                    <!-- Mobile menu button -->
                    <button type="button" class="sm:hidden text-gray-300 hover:text-white focus:outline-none" onclick="toggleMobileMenu()">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div class="sm:hidden hidden" id="mobile-menu">
            <div class="pt-2 pb-3 space-y-1 border-t border-gray-700">
                <a href="{{ route('home') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-white hover:bg-gray-700">{{ strtoupper(__('website.nav.home')) }}</a>
                <a href="{{ route('about') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-300 hover:bg-gray-700">{{ strtoupper(__('website.nav.about')) }}</a>
                <a href="{{ route('activities') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-300 hover:bg-gray-700">{{ strtoupper(__('website.nav.activities')) }}</a>
                <a href="{{ route('blog.index') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-300 hover:bg-gray-700">BLOG</a>
                <a href="{{ route('contact') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-300 hover:bg-gray-700">{{ strtoupper(__('website.nav.contact')) }}</a>
                <div class="flex items-center space-x-2 pl-3 pr-4 py-2">
                    <a href="{{ route('lang.switch', 'fr') }}" class="text-sm font-medium {{ app()->getLocale() == 'fr' ? 'text-white' : 'text-gray-300' }}">FR</a>
                    <span class="text-gray-400">|</span>
                    <a href="{{ route('lang.switch', 'en') }}" class="text-sm font-medium {{ app()->getLocale() == 'en' ? 'text-white' : 'text-gray-300' }}">EN</a>
                </div>
                @auth
                    <a href="{{ route('dashboard') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-300 hover:bg-gray-700">{{ __('website.nav.dashboard') }}</a>
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left pl-3 pr-4 py-2 text-base font-medium text-gray-300 hover:bg-gray-700">{{ __('website.nav.logout') }}</button>
                    </form>
                @endauth
            </div>
        </div>
    </nav>

    <main style="padding-top: 0;">
        @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-black">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-bold mb-4">JCI Carthage</h3>
                    <p class="text-gray-200 text-sm">
                        {{ __('website.footer.description') }}
                    </p>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">{{ __('website.footer.quick_links') }}</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="text-gray-200 hover:text-white">{{ __('website.nav.home') }}</a></li>
                        <li><a href="{{ route('about') }}" class="text-gray-200 hover:text-white">{{ __('website.nav.about') }}</a></li>
                        <li><a href="{{ route('activities') }}" class="text-gray-200 hover:text-white">{{ __('website.nav.activities') }}</a></li>
                        <li><a href="{{ route('blog.index') }}" class="text-gray-200 hover:text-white">Blog</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-200 hover:text-white">{{ __('website.nav.contact') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">{{ __('website.footer.contact') }}</h3>
                    <ul class="space-y-2 text-sm text-gray-200">
                        <li>Email: jcicarthage.olm@gmail.com</li>
                        <li>{{ __('website.footer.phone') }}: +1 (555) 345 234343</li>
                        <li>Carthage, Tunis, Tunisia</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} JCI Carthage. {{ __('website.footer.all_rights') }}</p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
    </script>
    @stack('scripts')
</body>
</html>

