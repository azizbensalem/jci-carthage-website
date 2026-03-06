@extends('layouts.public')

@section('title', __('website.contact.page_title'))

@section('content')
<!-- Hero Section -->
<section class="jci-gradient text-white pt-48 pb-36">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl md:text-5xl font-bold text-center">{{ __('website.contact.hero_title') }}</h1>
        <p class="text-xl text-center mt-4 text-blue-100">{{ __('website.contact.hero_subtitle') }}</p>
    </div>
</section>

<!-- Contact Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div>
                <h2 class="text-3xl font-bold jci-primary-text mb-6">{{ __('website.contact.send_message') }}</h2>
                <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('website.contact.full_name') }}</label>
                        <input type="text" name="name" id="name" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0097D7] focus:border-[#0097D7] @error('name') border-red-500 @enderror"
                               value="{{ old('name') }}">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">{{ __('website.contact.email') }}</label>
                        <input type="email" name="email" id="email" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                               value="{{ old('email') }}">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">{{ __('website.contact.subject') }}</label>
                        <input type="text" name="subject" id="subject" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('subject') border-red-500 @enderror"
                               value="{{ old('subject') }}">
                        @error('subject')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">{{ __('website.contact.message') }}</label>
                        <textarea name="message" id="message" rows="6" required
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full jci-btn-primary py-3">
                        {{ __('website.contact.send') }}
                    </button>
                </form>
            </div>

            <!-- Contact Information -->
            <div>
                <h2 class="text-3xl font-bold jci-primary-text mb-6">{{ __('website.contact.contact_info') }}</h2>
                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="bg-[#0097D7]/10 rounded-lg p-3 mr-4">
                            <svg class="w-6 h-6 text-[#0097D7]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg text-gray-900 mb-1">{{ __('website.contact.email') }}</h3>
                            <p class="text-gray-600">jcicarthage.olm@gmail.com</p>
                        </div>
                    </div>


                    <div class="flex items-start">
                        <div class="bg-[#0097D7]/10 rounded-lg p-3 mr-4">
                            <svg class="w-6 h-6 text-[#0097D7]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg text-gray-900 mb-1">{{ __('website.contact.address') }}</h3>
                            <p class="text-gray-600">Carthage, Tunis, Tunisia</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
