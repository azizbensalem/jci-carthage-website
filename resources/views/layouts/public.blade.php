<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
@php($enableHomeTransition = request()->routeIs('home'))
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $metaTitle = trim($__env->yieldContent('title', 'JCI Carthage - Junior Chamber International'));
        $metaDescription = trim($__env->yieldContent('meta_description', config('seo.default_description')));
        $canonicalUrl = trim($__env->yieldContent('canonical', request()->fullUrl()));
        $metaImage = trim($__env->yieldContent('meta_image', config('seo.default_image')));
        $metaType = trim($__env->yieldContent('meta_type', 'website'));
        $robots = trim($__env->yieldContent('meta_robots', 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1'));
        $metaLocale = app()->getLocale() === 'fr' ? 'fr_TN' : 'en_US';
        $resolvedMetaImage = \App\Support\Schema::absoluteUrl($metaImage);
    @endphp

    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="robots" content="{{ $robots }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">

    <meta property="og:locale" content="{{ $metaLocale }}">
    <meta property="og:type" content="{{ $metaType }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:site_name" content="{{ config('seo.organization.name') }}">
    @if($resolvedMetaImage)
    <meta property="og:image" content="{{ $resolvedMetaImage }}">
    @endif

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    @if($resolvedMetaImage)
    <meta name="twitter:image" content="{{ $resolvedMetaImage }}">
    @endif

    @if(config('services.google.site_verification'))
    <meta name="google-site-verification" content="{{ config('services.google.site_verification') }}">
    @endif

    @yield('meta')
    @include('partials.seo.json-ld', ['schemas' => [\App\Support\Schema::organization(), \App\Support\Schema::website()]])
    @stack('structured-data')

    @if(config('services.google.analytics_id'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google.analytics_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ config('services.google.analytics_id') }}', {
            anonymize_ip: true
        });
    </script>
    @endif

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    @if($enableHomeTransition)
    <script>
        document.documentElement.classList.add('page-transition-enabled', 'page-transition-loading');
    </script>
    @endif
    
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
        @if($enableHomeTransition)
        .page-transition-overlay {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background:
                radial-gradient(circle at 20% 20%, rgba(87, 188, 188, 0.28), transparent 32%),
                radial-gradient(circle at 78% 78%, rgba(239, 196, 15, 0.22), transparent 28%),
                linear-gradient(145deg, rgba(19, 15, 45, 0.98), rgba(31, 71, 137, 0.96));
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 0.55s ease, visibility 0.55s ease;
        }
        .page-transition-enabled.page-transition-loading .page-transition-overlay,
        .page-transition-enabled.page-transition-leaving .page-transition-overlay {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }
        .page-transition-enabled.page-transition-loading body,
        .page-transition-enabled.page-transition-leaving body {
            overflow: hidden;
        }
        .page-transition-shell {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.15rem;
            text-align: center;
            transform: translateY(18px) scale(0.94);
            opacity: 0;
            transition: transform 0.65s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.45s ease;
        }
        .page-transition-enabled.page-transition-loading .page-transition-shell,
        .page-transition-enabled.page-transition-leaving .page-transition-shell {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
        .page-transition-mark {
            position: relative;
            width: clamp(7.5rem, 16vw, 11rem);
            aspect-ratio: 1;
            border-radius: 999px;
            display: grid;
            place-items: center;
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.16), rgba(255, 255, 255, 0.04));
            border: 1px solid rgba(255, 255, 255, 0.14);
            box-shadow:
                0 22px 60px rgba(0, 0, 0, 0.28),
                inset 0 1px 0 rgba(255, 255, 255, 0.12);
            overflow: hidden;
            isolation: isolate;
        }
        .page-transition-mark::before {
            content: '';
            position: absolute;
            inset: -10px;
            border-radius: inherit;
            border: 2px solid rgba(255, 255, 255, 0.08);
            border-top-color: rgba(0, 151, 215, 0.95);
            border-right-color: rgba(239, 196, 15, 0.8);
            animation: jciOrbit 1.4s linear infinite;
        }
        .page-transition-mark::after {
            content: '';
            position: absolute;
            inset: 12%;
            border-radius: inherit;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.22) 0%, rgba(255, 255, 255, 0) 70%);
            animation: jciPulse 1.8s ease-in-out infinite;
        }
        .page-transition-logo {
            position: relative;
            z-index: 1;
            width: 72%;
            object-fit: contain;
            filter: drop-shadow(0 10px 18px rgba(0, 0, 0, 0.24));
            animation: jciFloat 1.8s ease-in-out infinite;
        }
        .page-transition-wordmark {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            color: white;
        }
        .page-transition-title {
            font-size: clamp(1.3rem, 2vw, 1.8rem);
            font-weight: 800;
            letter-spacing: 0.22em;
            text-transform: uppercase;
        }
        .page-transition-subtitle {
            font-size: 0.82rem;
            font-weight: 600;
            letter-spacing: 0.28em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.72);
        }
        .page-transition-bar {
            width: min(13rem, 72vw);
            height: 0.28rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            overflow: hidden;
        }
        .page-transition-bar::after {
            content: '';
            display: block;
            width: 42%;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, var(--jci-blue), var(--jci-teal), var(--jci-yellow));
            animation: jciProgress 1.2s ease-in-out infinite;
        }
        @keyframes jciOrbit {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
        @keyframes jciFloat {
            0%, 100% {
                transform: translateY(0) scale(1);
            }
            50% {
                transform: translateY(-6px) scale(1.03);
            }
        }
        @keyframes jciPulse {
            0%, 100% {
                transform: scale(0.94);
                opacity: 0.45;
            }
            50% {
                transform: scale(1.08);
                opacity: 0.95;
            }
        }
        @keyframes jciProgress {
            0% {
                transform: translateX(-115%);
            }
            100% {
                transform: translateX(255%);
            }
        }
        @media (prefers-reduced-motion: reduce) {
            .page-transition-overlay,
            .page-transition-shell {
                transition-duration: 0.18s;
            }
            .page-transition-mark::before,
            .page-transition-mark::after,
            .page-transition-logo,
            .page-transition-bar::after {
                animation: none;
            }
        }
        @endif
    </style>
    @stack('styles')
</head>
<body class="antialiased">
    @if($enableHomeTransition)
    <div class="page-transition-overlay" aria-hidden="true">
        <div class="page-transition-shell">
            <div class="page-transition-mark">
                <img src="{{ asset('images/jci-carthage.png') }}" alt="" class="page-transition-logo">
            </div>
            <div class="page-transition-wordmark">
                <span class="page-transition-title">JCI Carthage</span>
                <span class="page-transition-subtitle">Young Active Citizens</span>
            </div>
            <div class="page-transition-bar"></div>
        </div>
    </div>
    @endif

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
                            {{ \Illuminate\Support\Str::upper(__('website.nav.home')) }}
                        </a>
                        <a href="{{ route('about') }}" class="nav-link text-sm font-semibold tracking-wide {{ request()->routeIs('about') || request()->routeIs('presidents') ? 'text-white active' : 'text-gray-300 hover:text-white' }}">
                            {{ \Illuminate\Support\Str::upper(__('website.nav.about')) }}
                        </a>
                        <a href="{{ route('events') }}" class="nav-link text-sm font-semibold tracking-wide {{ request()->routeIs('events') ? 'text-white active' : 'text-gray-300 hover:text-white' }}">
                            {{ \Illuminate\Support\Str::upper(__('website.nav.activities')) }}
                        </a>
                        <a href="{{ route('blog.index') }}" class="nav-link text-sm font-semibold tracking-wide {{ request()->routeIs('blog.*') ? 'text-white active' : 'text-gray-300 hover:text-white' }}">
                            BLOG
                        </a>
                        <a href="{{ route('contact') }}" class="nav-link text-sm font-semibold tracking-wide {{ request()->routeIs('contact') ? 'text-white active' : 'text-gray-300 hover:text-white' }}">
                            {{ \Illuminate\Support\Str::upper(__('website.nav.contact')) }}
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
                <a href="{{ route('home') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-white hover:bg-gray-700">{{ \Illuminate\Support\Str::upper(__('website.nav.home')) }}</a>
                <a href="{{ route('about') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-300 hover:bg-gray-700">{{ \Illuminate\Support\Str::upper(__('website.nav.about')) }}</a>
                <a href="{{ route('events') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-300 hover:bg-gray-700">{{ \Illuminate\Support\Str::upper(__('website.nav.activities')) }}</a>
                <a href="{{ route('blog.index') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-300 hover:bg-gray-700">BLOG</a>
                <a href="{{ route('contact') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-300 hover:bg-gray-700">{{ \Illuminate\Support\Str::upper(__('website.nav.contact')) }}</a>
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
                        <li><a href="{{ route('events') }}" class="text-gray-200 hover:text-white">{{ __('website.nav.activities') }}</a></li>
                        <li><a href="{{ route('blog.index') }}" class="text-gray-200 hover:text-white">Blog</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-200 hover:text-white">{{ __('website.nav.contact') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">{{ __('website.footer.contact') }}</h3>
                    <ul class="space-y-2 text-sm text-gray-200">
                        <li>Email: jcicarthage.olm@gmail.com</li>
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
        @if($enableHomeTransition)
        (function () {
            const html = document.documentElement;
            const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
            const enterDelay = reducedMotion.matches ? 120 : 780;
            const leaveDelay = reducedMotion.matches ? 0 : 360;

            const releaseEntrance = function () {
                window.setTimeout(function () {
                    html.classList.remove('page-transition-loading');
                }, enterDelay);
            };

            if (document.readyState === 'complete') {
                releaseEntrance();
            } else {
                window.addEventListener('load', releaseEntrance, { once: true });
            }

            window.addEventListener('pageshow', function (event) {
                if (event.persisted) {
                    html.classList.remove('page-transition-loading', 'page-transition-leaving');
                }
            });

            document.addEventListener('click', function (event) {
                const link = event.target.closest('a[href]');

                if (!link || event.defaultPrevented) {
                    return;
                }

                if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
                    return;
                }

                if (link.target && link.target !== '_self') {
                    return;
                }

                if (link.hasAttribute('download') || link.hasAttribute('data-no-transition')) {
                    return;
                }

                const destination = new URL(link.href, window.location.href);

                if (destination.origin !== window.location.origin) {
                    return;
                }

                if (destination.href === window.location.href) {
                    return;
                }

                if (destination.pathname === window.location.pathname
                    && destination.search === window.location.search
                    && destination.hash) {
                    return;
                }

                html.classList.remove('page-transition-loading');
                html.classList.add('page-transition-leaving');

                if (leaveDelay === 0) {
                    return;
                }

                event.preventDefault();

                window.setTimeout(function () {
                    window.location.href = destination.href;
                }, leaveDelay);
            });
        })();
        @endif
    </script>
    @stack('scripts')
</body>
</html>

