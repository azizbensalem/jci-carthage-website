@extends('layouts.admin')

@section('title', __('facebook.import_title') . ' - JCI Carthage')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold jci-primary-text">{{ __('facebook.import_title') }}</h1>
            <p class="mt-2 text-gray-600">{{ __('facebook.facebook_posts') }}</p>
        </div>
        <form action="{{ route('admin.facebook.import.execute') }}" method="POST">
            @csrf
            <button type="submit" class="jci-btn-primary">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                {{ __('facebook.import_button') }}
            </button>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="jci-card p-6">
            <h3 class="text-sm font-medium text-gray-500 mb-2">{{ __('facebook.total_imported') }}</h3>
            <p class="text-3xl font-bold jci-primary-text">{{ $stats['total_imported'] }}</p>
        </div>
        
        <div class="jci-card p-6">
            <h3 class="text-sm font-medium text-gray-500 mb-2">{{ __('facebook.last_import') }}</h3>
            <p class="text-lg font-semibold text-gray-900">
                {{ $stats['last_import_date'] ? $stats['last_import_date']->diffForHumans() : __('website.admin.never') }}
            </p>
        </div>

        <div class="jci-card p-6 bg-blue-50">
            <h3 class="text-sm font-medium text-blue-900 mb-2">{{ __('facebook.configure_credentials') }}</h3>
            <p class="text-xs text-blue-700">{{ __('facebook.credentials_info') }}</p>
        </div>
    </div>

    <!-- Imported Posts Table -->
    <div class="jci-card overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">{{ __('facebook.facebook_posts') }}</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#130F2D]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('blog.title') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('facebook.post_date') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('facebook.import_date') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('blog.views') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('website.admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($facebookPosts as $post)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($post->featured_image)
                                <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="h-12 w-12 object-cover rounded mr-3">
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ Str::limit($post->title, 50) }}</div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        Facebook
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $post->published_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $post->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $post->views_count }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.blog.edit', $post) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">{{ __('website.admin.edit') }}</a>
                            <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="text-blue-600 hover:text-blue-900">{{ __('website.admin.view') }}</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            {{ __('facebook.no_posts') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
