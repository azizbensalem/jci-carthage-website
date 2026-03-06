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
