@extends('layouts.app')

@section('title', __('auth.reset_password') . ' - JCI Carthage')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <!-- Logo Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-[#130F2D] rounded-xl mb-4">
                <svg class="w-12 h-12 text-[#0097D7]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">
                {{ __('auth.reset_password') }}
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                {{ __('auth.enter_email') }}
            </p>
            
            <!-- Language Switcher -->
            <div class="mt-4 flex justify-center gap-2">
                <a href="{{ route('lang.switch', 'en') }}" 
                   class="px-3 py-1 rounded-md text-xs font-medium transition {{ app()->getLocale() == 'en' ? 'bg-[#0097D7] text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    EN
                </a>
                <a href="{{ route('lang.switch', 'fr') }}" 
                   class="px-3 py-1 rounded-md text-xs font-medium transition {{ app()->getLocale() == 'fr' ? 'bg-[#0097D7] text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    FR
                </a>
            </div>
        </div>

        <!-- Reset Password Form Card -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-8 py-8">
                <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('auth.email') }}
                        </label>
                        <input id="email" 
                               name="email" 
                               type="email" 
                               autocomplete="email" 
                               required 
                               autofocus
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#0097D7] focus:border-[#0097D7] sm:text-sm @error('email') border-red-300 @enderror" 
                               placeholder="{{ __('auth.email') }}" 
                               value="{{ $email ?? old('email') }}">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('auth.new_password') }}
                        </label>
                        <input id="password" 
                               name="password" 
                               type="password" 
                               autocomplete="new-password" 
                               required 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#0097D7] focus:border-[#0097D7] sm:text-sm @error('password') border-red-300 @enderror" 
                               placeholder="{{ __('auth.new_password') }}">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation Field -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('auth.confirm_password') }}
                        </label>
                        <input id="password_confirmation" 
                               name="password_confirmation" 
                               type="password" 
                               autocomplete="new-password" 
                               required 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#0097D7] focus:border-[#0097D7] sm:text-sm" 
                               placeholder="{{ __('auth.confirm_password') }}">
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" 
                                class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#0097D7] hover:bg-[#1F4789] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0097D7] transition">
                            {{ __('auth.reset_password_button') }}
                        </button>
                    </div>

                    <!-- Home Button -->
                    <div class="text-center">
                        <a href="{{ route('home') }}" class="text-sm font-medium text-gray-600 hover:text-[#0097D7] inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            {{ app()->getLocale() == 'fr' ? 'Retour à l\'accueil' : 'Back to home' }}
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <p class="mt-6 text-center text-xs text-gray-500">
            &copy; {{ date('Y') }} JCI Carthage. All rights reserved.
        </p>
    </div>
</div>
@endsection
