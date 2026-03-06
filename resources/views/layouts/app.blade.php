<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'JCI Carthage - Member Management')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
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
            background-color: #F8FAFC;
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
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .jci-btn-primary:hover {
            background-color: var(--jci-navy);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 151, 215, 0.2);
        }
        .jci-card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
    </style>
    @stack('styles')
</head>
<body>
    @auth
    <nav class="bg-[#130F2D] shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-xl font-bold">JCI Carthage</h1>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium {{ request()->routeIs('dashboard') ? 'border-b-2 border-white' : 'text-gray-200 hover:text-white' }}">
                            Dashboard
                        </a>
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'border-b-2 border-white' : 'text-gray-200 hover:text-white' }}">
                            Membres
                        </a>
                        <a href="{{ route('admin.carousels.index') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium {{ request()->routeIs('admin.carousels.*') ? 'border-b-2 border-white' : 'text-gray-200 hover:text-white' }}">
                            Carrousel
                        </a>
                        <a href="{{ route('admin.projects.index') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium {{ request()->routeIs('admin.projects.*') ? 'border-b-2 border-white' : 'text-gray-200 hover:text-white' }}">
                            Projets
                        </a>
                        <a href="{{ route('admin.google-account.index') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium {{ request()->routeIs('admin.google-account.*') ? 'border-b-2 border-white' : 'text-gray-200 hover:text-white' }}">
                            Google Account
                        </a>
                        @endif
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="text-sm text-gray-200 mr-4">{{ auth()->user()->name }} ({{ ucfirst(str_replace('-', ' ', auth()->user()->role)) }})</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-200 hover:text-white">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    @endauth

    <main class="py-6">
        @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
        @endif

        @yield('content')
    </main>

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>

