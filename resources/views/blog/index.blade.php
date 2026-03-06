@extends('layouts.public')

@section('title', __('blog.blog') . ' - JCI Carthage')

@section('content')
<!-- Hero Section -->
<div class="jci-gradient text-white py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ __('blog.blog') }} JCI Carthage</h1>
        <p class="text-xl text-blue-100">{{ __('Discover our latest news, events and projects') }}</p>
    </div>
</div>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Featured Posts -->
    @if($featuredPosts->count() > 0)
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('blog.featured_posts') }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($featuredPosts as $featured)
            <a href="{{ route('blog.show', $featured->slug) }}" class="group">
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                    @if($featured->featured_image)
                    <img src="{{ Storage::url($featured->featured_image) }}" alt="{{ $featured->title }}" class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
                    @else
                    <div class="w-full h-48 bg-gradient-to-br from-[#0097D7] to-[#1F4789]"></div>
                    @endif
                    <div class="p-6">
                        <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full mb-3">
                            {{ __('blog.featured') }}
                        </span>
                        <h3 class="text-xl font-bold text-gray-900 group-hover:text-[#0097D7] transition mb-2">{{ $featured->title }}</h3>
                        <p class="text-gray-600 mb-4">{{ Str::limit($featured->excerpt ?? strip_tags($featured->content), 100) }}</p>
                        <div class="flex items-center text-sm text-gray-500">
                            <span>{{ $featured->formatted_date }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $featured->reading_time }} {{ __('blog.reading_time') }}</span>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <div class="lg:grid lg:grid-cols-3 lg:gap-8">
        <!-- Blog Posts -->
        <div class="lg:col-span-2">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">{{ __('blog.all_posts') }}</h2>
            </div>

            <div class="space-y-6">
                @forelse($posts as $post)
                <article class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition">
                    <div class="md:flex">
                        @if($post->featured_image)
                        <div class="md:w-1/3">
                            <a href="{{ route('blog.show', $post->slug) }}">
                                <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                            </a>
                        </div>
                        @endif
                        <div class="p-6 {{ $post->featured_image ? 'md:w-2/3' : 'w-full' }}">
                            @if($post->category)
                            <a href="{{ route('blog.category', $post->category) }}" class="inline-block px-3 py-1 bg-[#0097D7] text-white text-xs rounded-full mb-3 hover:bg-[#1F4789] transition">
                                {{ $post->category }}
                            </a>
                            @endif
                            
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">
                                <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-[#0097D7] transition">{{ $post->title }}</a>
                            </h2>
                            
                            <div class="flex items-center text-sm text-gray-500 mb-3">
                                <span>{{ __('blog.by_author') }} {{ $post->author->name }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $post->formatted_date }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $post->reading_time }} {{ __('blog.reading_time') }}</span>
                            </div>
                            
                            <p class="text-gray-600 mb-4">{{ Str::limit($post->excerpt ?? strip_tags($post->content), 150) }}</p>
                            
                            <a href="{{ route('blog.show', $post->slug) }}" class="text-[#0097D7] font-semibold hover:text-[#1F4789] transition">
                                {{ __('blog.read_more') }} →
                            </a>
                        </div>
                    </div>
                </article>
                @empty
                <div class="text-center py-12">
                    <p class="text-gray-500">{{ __('blog.no_posts') }}</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($posts->hasPages())
            <div class="mt-8">
                {{ $posts->links() }}
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="mt-12 lg:mt-0">
            <!-- Categories -->
            @if($categories->count() > 0)
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('blog.categories') }}</h3>
                <div class="space-y-2">
                    @foreach($categories as $category => $count)
                    <a href="{{ route('blog.category', $category) }}" class="flex justify-between items-center text-gray-700 hover:text-[#0097D7] transition">
                        <span>{{ $category }}</span>
                        <span class="text-sm text-gray-500">{{ $count }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Recent Posts -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('blog.recent_posts') }}</h3>
                <div class="space-y-4">
                    @foreach($recentPosts as $recent)
                    <a href="{{ route('blog.show', $recent->slug) }}" class="block group">
                        <h4 class="font-medium text-gray-900 group-hover:text-[#0097D7] transition">{{ $recent->title }}</h4>
                        <p class="text-sm text-gray-500 mt-1">{{ $recent->formatted_date }}</p>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
