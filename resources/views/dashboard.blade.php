@extends('layouts.admin')

@section('title', 'Dashboard - JCI Carthage')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold jci-primary-text">{{ __('website.dashboard.title') }}</h1>
        <p class="mt-2 text-gray-600">{{ __('website.dashboard.welcome', ['name' => $user->name]) }}</p>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Members -->
        <div class="jci-card p-6 bg-gradient-to-br from-[#0097D7] to-[#1F4789] text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">{{ __('website.dashboard.total_members') }}</p>
                    <p class="text-3xl font-bold">{{ $stats['total_members'] }}</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-blue-100">{{ $stats['active_members'] }} {{ __('website.dashboard.active') }}</span>
            </div>
        </div>

        <!-- Admins -->
        <div class="jci-card p-6 bg-gradient-to-br from-[#1F4789] to-[#130F2D] text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium mb-1">{{ __('website.dashboard.admins') }}</p>
                    <p class="text-3xl font-bold">{{ $stats['admins'] }}</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Events -->
        <div class="jci-card p-6 bg-gradient-to-br from-[#57BCBC] to-[#3a8585] text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">{{ __('website.dashboard.events') }}</p>
                    <p class="text-3xl font-bold">{{ $stats['total_events'] }}</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-green-100">{{ $stats['active_events'] }} {{ __('website.dashboard.active') }}</span>
            </div>
        </div>

        <!-- Carousels -->
        <div class="jci-card p-6 bg-gradient-to-br from-[#FDE047] to-[#EFC40F] text-gray-900">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-700 text-sm font-medium mb-1">{{ __('website.dashboard.carousels') }}</p>
                    <p class="text-3xl font-bold">{{ $stats['total_carousels'] }}</p>
                </div>
                <div class="bg-black/10 rounded-lg p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-gray-700">{{ $stats['active_carousels'] }} {{ __('website.dashboard.active') }}</span>
            </div>
        </div>
    </div>

    @if($user->isAdmin())
    <!-- Detailed Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Members Breakdown -->
        <div class="jci-card p-6">
            <h2 class="text-xl font-semibold jci-primary-text mb-4">{{ __('website.dashboard.members_breakdown') }}</h2>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-[#0097D7] rounded-full mr-3"></div>
                        <span class="text-sm text-gray-700">{{ __('website.dashboard.regular_members') }}</span>
                    </div>
                    <span class="text-lg font-semibold text-gray-900">{{ $stats['regular_members'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-[#EFC40F] rounded-full mr-3"></div>
                        <span class="text-sm text-gray-700">{{ __('website.dashboard.vice_presidents') }}</span>
                    </div>
                    <span class="text-lg font-semibold text-gray-900">{{ $stats['vice_presidents'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-[#130F2D] rounded-full mr-3"></div>
                        <span class="text-sm text-gray-700">{{ __('website.dashboard.admins') }}</span>
                    </div>
                    <span class="text-lg font-semibold text-gray-900">{{ $stats['admins'] }}</span>
                </div>
            </div>
        </div>

        <!-- Events Stats -->
        <div class="jci-card p-6">
            <h2 class="text-xl font-semibold jci-primary-text mb-4">{{ __('website.dashboard.events_stats') }}</h2>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">{{ __('website.dashboard.total_events') }}</span>
                    <span class="text-lg font-semibold text-gray-900">{{ $stats['total_events'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">{{ __('website.dashboard.active_events') }}</span>
                    <span class="text-lg font-semibold text-green-600">{{ $stats['active_events'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">{{ __('website.dashboard.featured_events') }}</span>
                    <span class="text-lg font-semibold text-yellow-600">{{ $stats['featured_events'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Members -->
        <div class="jci-card p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold jci-primary-text">{{ __('website.dashboard.recent_members') }}</h2>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-[#0097D7] hover:text-[#1F4789]">{{ __('website.dashboard.view_all') }}</a>
            </div>
            <div class="space-y-3">
                @forelse($recentMembers as $member)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-[#0097D7] font-bold">{{ substr($member->name, 0, 1) }}</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $member->name }}</p>
                            <p class="text-xs text-gray-500">{{ $member->email }}</p>
                        </div>
                    </div>
                    <span class="text-xs text-gray-500">{{ $member->created_at->diffForHumans() }}</span>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-4">{{ __('website.dashboard.no_recent_members') }}</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Events -->
        <div class="jci-card p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold jci-primary-text">{{ __('website.dashboard.recent_events') }}</h2>
                <a href="{{ route('admin.events.index') }}" class="text-sm text-[#0097D7] hover:text-[#1F4789]">{{ __('website.dashboard.view_all') }}</a>
            </div>
            <div class="space-y-3">
                @forelse($recentEvents as $event)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $event->title }}</p>
                            <p class="text-xs text-gray-500">{{ \App\Models\Event::getTypes()[$event->type] ?? $event->type }}</p>
                        </div>
                    </div>
                    <span class="text-xs text-gray-500">{{ $event->created_at->diffForHumans() }}</span>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-4">{{ __('website.dashboard.no_recent_events') }}</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="jci-card p-6 mt-6">
        <h2 class="text-xl font-semibold jci-primary-text mb-4">{{ __('website.dashboard.quick_actions') }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.users.create') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="font-medium text-gray-900">{{ __('website.dashboard.new_member') }}</span>
            </a>
            <a href="{{ route('admin.events.create') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="font-medium text-gray-900">{{ __('website.dashboard.new_event') }}</span>
            </a>
            <a href="{{ route('admin.carousels.create') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                <svg class="w-6 h-6 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="font-medium text-gray-900">{{ __('website.dashboard.new_carousel') }}</span>
            </a>
            <a href="{{ route('admin.partners.create') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                <svg class="w-6 h-6 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="font-medium text-gray-900">{{ __('website.dashboard.new_partner') }}</span>
            </a>
            <a href="{{ route('admin.settings.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                <svg class="w-6 h-6 text-orange-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="font-medium text-gray-900">{{ __('website.dashboard.settings') }}</span>
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
