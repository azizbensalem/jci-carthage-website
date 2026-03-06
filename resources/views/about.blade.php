@extends('layouts.public')

@section('title', __('website.about.page_title'))

@section('content')
<!-- Hero Section -->
<section class="jci-gradient text-white pt-48 pb-36">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl md:text-5xl font-bold text-center">{{ __('website.about.hero_title') }}</h1>
    </div>
</section>

<!-- About Content -->
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="prose prose-lg max-w-none">
            <h2 class="text-3xl font-bold jci-primary-text mb-6">{{ __('website.about.our_story') }}</h2>
            <p class="text-gray-700 mb-6 text-lg">
                {{ __('website.about.our_story_text') }}
            </p>
            
            <h2 class="text-3xl font-bold jci-primary-text mb-6 mt-12">{{ __('website.about.what_is_jci') }}</h2>
            <p class="text-gray-700 mb-6 text-lg">
                {{ __('website.about.what_is_jci_text') }}
            </p>
            
            <h2 class="text-3xl font-bold jci-primary-text mb-6 mt-12">{{ __('website.about.our_vision') }}</h2>
            <p class="text-gray-700 mb-6 text-lg">
                {{ __('website.about.our_vision_text') }}
            </p>
            
            <h2 class="text-3xl font-bold jci-primary-text mb-6 mt-12">{{ __('website.about.our_mission') }}</h2>
            <p class="text-gray-700 mb-6 text-lg">
                {{ __('website.about.our_mission_text') }}
            </p>
            
            <h2 class="text-3xl font-bold jci-primary-text mb-6 mt-12">{{ __('website.about.core_values') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div class="bg-[#0097D7]/10 p-6 rounded-lg">
                    <h3 class="text-xl font-semibold jci-primary-text mb-3">{{ __('website.about.faith') }}</h3>
                    <p class="text-gray-700">
                        {{ __('website.about.faith_text') }}
                    </p>
                </div>
                <div class="bg-[#0097D7]/10 p-6 rounded-lg">
                    <h3 class="text-xl font-semibold jci-primary-text mb-3">{{ __('website.about.brotherhood') }}</h3>
                    <p class="text-gray-700">
                        {{ __('website.about.brotherhood_text') }}
                    </p>
                </div>
                <div class="bg-[#0097D7]/10 p-6 rounded-lg">
                    <h3 class="text-xl font-semibold jci-primary-text mb-3">{{ __('website.about.freedom') }}</h3>
                    <p class="text-gray-700">
                        {{ __('website.about.freedom_text') }}
                    </p>
                </div>
                <div class="bg-[#0097D7]/10 p-6 rounded-lg">
                    <h3 class="text-xl font-semibold jci-primary-text mb-3">{{ __('website.about.justice') }}</h3>
                    <p class="text-gray-700">
                        {{ __('website.about.justice_text') }}
                    </p>
                </div>
            </div>
            
            <h2 class="text-3xl font-bold jci-primary-text mb-6 mt-12">{{ __('website.about.what_we_do') }}</h2>
            <ul class="list-disc list-inside text-gray-700 text-lg space-y-3 mb-6">
                <li>{{ __('website.about.what_we_do_1') }}</li>
                <li>{{ __('website.about.what_we_do_2') }}</li>
                <li>{{ __('website.about.what_we_do_3') }}</li>
                <li>{{ __('website.about.what_we_do_4') }}</li>
                <li>{{ __('website.about.what_we_do_5') }}</li>
            </ul>
            
            <div class="mt-12 text-center">
                <a href="{{ route('contact') }}" class="jci-btn-primary inline-block">
                    {{ __('website.about.join_today') }}
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
