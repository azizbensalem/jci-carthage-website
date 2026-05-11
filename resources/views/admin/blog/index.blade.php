@extends('layouts.admin')

@section('title', __('blog.admin_title') . ' - JCI Carthage')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold jci-primary-text">{{ __('blog.admin_title') }}</h1>
            <p class="mt-2 text-gray-600">{{ __('blog.all_posts') }}</p>
        </div>
        <a href="{{ route('admin.blog.create') }}" class="jci-btn-primary">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            {{ __('blog.create_post') }}
        </a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
        <div class="xl:col-span-2 jci-card p-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">{{ __('facebook.import_inline_title') }}</h2>
                    <p class="mt-2 text-sm text-gray-600">{{ __('facebook.import_inline_help') }}</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                    Facebook
                </span>
            </div>

            <form action="{{ route('admin.blog.import-facebook') }}" method="POST" class="grid grid-cols-1 md:grid-cols-[minmax(0,1fr)_auto] gap-4 items-end">
                @csrf

                <div>
                    <label for="since" class="block text-sm font-medium text-gray-700 mb-2">{{ __('facebook.since') }}</label>
                    <input
                        type="date"
                        name="since"
                        id="since"
                        value="{{ $defaultImportSince }}"
                        max="{{ now()->toDateString() }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#0097D7] focus:border-[#0097D7]"
                        required
                    >
                    @error('since')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="jci-btn-primary whitespace-nowrap">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    {{ __('facebook.import_button') }}
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-1 gap-4">
            <div class="jci-card p-5">
                <p class="text-sm font-medium text-gray-500">{{ __('facebook.total_imported') }}</p>
                <p class="mt-2 text-3xl font-bold jci-primary-text">{{ $facebookStats['total_imported'] }}</p>
            </div>

            <div class="jci-card p-5">
                <p class="text-sm font-medium text-gray-500">{{ __('facebook.last_import') }}</p>
                <p class="mt-2 text-base font-semibold text-gray-900">
                    {{ $facebookStats['last_import_date'] ? $facebookStats['last_import_date']->format('d/m/Y H:i') : __('website.admin.never') }}
                </p>
                @if($facebookStats['last_import_title'])
                <p class="mt-2 text-sm text-gray-600">{{ Str::limit($facebookStats['last_import_title'], 60) }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="jci-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#130F2D]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('blog.title') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('blog.author') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('blog.category') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('blog.status') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('blog.views') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('website.admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($posts as $post)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($post->featured_image)
                                <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="h-12 w-12 object-cover rounded mr-3">
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $post->title }}</div>
                                    @if($post->is_featured)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ __('blog.featured') }}
                                    </span>
                                    @endif
                                    @if($post->facebook_post_id)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                        {{ __('facebook.imported_from_facebook') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $post->author->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $post->category ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $post->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $post->is_published ? __('blog.published') : __('blog.draft') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $post->views_count }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.blog.edit', $post) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">{{ __('website.admin.edit') }}</a>
                            <form action="{{ route('admin.blog.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('blog.confirm_delete') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">{{ __('website.admin.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            {{ __('blog.no_posts') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($posts->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $posts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
