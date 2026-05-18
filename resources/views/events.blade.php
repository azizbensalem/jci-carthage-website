@extends('layouts.public')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Événements - JCI Carthage')
@section('meta_description', __('website.activities.subtitle'))

@push('structured-data')
@php
    $activityItems = $events->getCollection()->values()->map(function ($event, $index) use ($eventTypes) {
        $itemType = $event->starts_at ? 'Event' : 'CreativeWork';

        return [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'item' => array_filter([
                '@type' => $itemType,
                'name' => $event->title,
                'description' => $event->description,
                'url' => $event->link ?: route('events', ['type' => $event->type]),
                'image' => $event->image ? \App\Support\Schema::absoluteUrl(Storage::url($event->image)) : null,
                'eventAttendanceMode' => $event->starts_at ? 'https://schema.org/OfflineEventAttendanceMode' : null,
                'startDate' => $event->starts_at ? $event->starts_at->toIso8601String() : null,
                'endDate' => $event->ends_at ? $event->ends_at->toIso8601String() : null,
                'location' => $event->location_name ? [
                    '@type' => 'Place',
                    'name' => $event->location_name,
                ] : null,
                'organizer' => \App\Support\Schema::organizationReference(),
                'keywords' => $eventTypes[$event->type] ?? $event->type,
            ]),
        ];
    })->all();

    $eventsSchemas = [
        \App\Support\Schema::page('CollectionPage', 'Événements - JCI Carthage', __('website.activities.subtitle'), route('events', request()->query()), [
            'about' => \App\Support\Schema::organizationReference(),
            'mainEntity' => [
                '@type' => 'ItemList',
                'numberOfItems' => count($activityItems),
                'itemListElement' => $activityItems,
            ],
        ]),
        \App\Support\Schema::breadcrumb([
            ['name' => __('website.nav.home'), 'url' => route('home')],
            ['name' => __('website.nav.activities'), 'url' => route('events')],
        ]),
    ];
@endphp
@include('partials.seo.json-ld', ['schemas' => $eventsSchemas])
@endpush

@section('content')
<!-- Hero Section -->
<section class="jci-gradient text-white pt-48 pb-36">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl md:text-5xl font-bold text-center">{{ __('website.activities.title') }}</h1>
        <p class="text-xl text-center mt-4 text-blue-100">{{ __('website.activities.subtitle') }}</p>
    </div>
</section>

<!-- Filter Section -->
<section class="py-8 bg-gray-50 border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-center gap-3">
            <a href="{{ route('events', ['type' => 'all']) }}" 
               class="px-6 py-2 rounded-full font-medium transition {{ $selectedType === 'all' ? 'bg-[#0097D7] text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                {{ __('website.activities.all') }} ({{ $typeCounts['all'] ?? 0 }})
            </a>
            @foreach($eventTypes as $key => $label)
            <a href="{{ route('events', ['type' => $key]) }}" 
               class="px-6 py-2 rounded-full font-medium transition {{ $selectedType === $key ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                {{ $label }} ({{ $typeCounts[$key] ?? 0 }})
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Events Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($events->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($events as $event)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition group">
                <!-- Image -->
                <div class="h-48 overflow-hidden bg-gradient-to-r from-[#0097D7] to-[#1F4789] relative">
                    @if($event->image)
                    <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        @if($event->icon)
                        <svg class="w-24 h-24 text-white opacity-50" fill="currentColor" viewBox="0 0 24 24">
                            <path d="{{ $event->icon }}"/>
                        </svg>
                        @else
                        <svg class="w-24 h-24 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        @endif
                    </div>
                    @endif
                    <!-- Type Badge -->
                    <div class="absolute top-4 right-4">
                        <span class="px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-xs font-semibold text-[#130F2D]">
                            {{ $eventTypes[$event->type] ?? $event->type }}
                        </span>
                    </div>
                    @if($event->is_featured)
                    <div class="absolute top-4 left-4">
                        <span class="px-3 py-1 bg-[#EFC40F] rounded-full text-xs font-semibold text-[#130F2D]">
                            ⭐ {{ __('website.activities.featured') }}
                        </span>
                    </div>
                    @endif
                </div>
                
                <!-- Content -->
                <div class="p-6">
                    @if($event->starts_at)
                    <div class="mb-3 flex flex-wrap items-center gap-2 text-sm">
                        <span class="rounded-full bg-blue-100 px-3 py-1 font-semibold text-[#1F4789]">
                            {{ $event->starts_at->locale(app()->getLocale())->isoFormat(app()->getLocale() === 'fr' ? 'D MMM YYYY' : 'MMM D, YYYY') }}
                        </span>
                        <span class="rounded-full bg-yellow-100 px-3 py-1 font-semibold text-[#7A5A00]">
                            {{ $event->starts_at->format('H:i') }}
                        </span>
                    </div>
                    @endif
                    <h3 class="text-xl font-bold jci-primary-text mb-2">{{ $event->title }}</h3>
                    @if($event->location_name)
                    <p class="text-sm font-medium text-gray-500 mb-3">{{ $event->location_name }}</p>
                    @endif
                    <p class="text-gray-600 mb-4 line-clamp-3">
                        {{ Str::limit($event->description, 150) }}
                    </p>
                    
                    @if($event->link)
                    <a href="{{ $event->link }}" target="_blank" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-semibold text-sm">
                        {{ __('website.activities.read_more') }}
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h3 class="text-2xl font-semibold text-gray-900 mb-2">{{ __('website.activities.no_events') }}</h3>
            <p class="text-gray-600 mb-6">
                @if($selectedType !== 'all')
                {{ __('website.activities.no_events_type', ['type' => $eventTypes[$selectedType] ?? $selectedType]) }}
                @else
                {{ __('website.activities.no_events_all') }}
                @endif
            </p>
            <a href="{{ route('events', ['type' => 'all']) }}" class="inline-block px-6 py-3 bg-[#0097D7] text-white rounded-lg font-semibold hover:bg-[#1F4789] transition">
                {{ __('website.activities.view_all') }}
            </a>
        </div>
        @endif
    </div>
</section>

@if($events->hasPages())
<!-- Pagination -->
<section class="py-8 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-center">
            {{ $events->appends(request()->query())->links() }}
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="jci-gradient text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">{{ __('website.activities.cta_title') }}</h2>
        <p class="text-xl mb-8 text-blue-100">
            {{ __('website.activities.cta_description') }}
        </p>
        <a href="{{ route('contact') }}" class="bg-white text-[#130F2D] px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition inline-block">
            {{ __('website.common.contact_us') }}
        </a>
    </div>
</section>

<style>
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection
