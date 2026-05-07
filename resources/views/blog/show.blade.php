@extends('layouts.public')

@section('title', ($post->meta_title ?? $post->title) . ' - JCI Carthage')

@section('meta')
<!-- SEO Meta Tags -->
<meta name="description" content="{{ $post->meta_description ?? $post->excerpt ?? Str::limit(strip_tags($post->content), 160) }}">
<meta name="keywords" content="{{ $post->meta_keywords ?? ($post->tags ? implode(', ', $post->tags) : '') }}">
<meta name="author" content="{{ $post->author->name }}">
<link rel="canonical" href="{{ route('blog.show', $post->slug) }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="article">
<meta property="og:url" content="{{ route('blog.show', $post->slug) }}">
<meta property="og:title" content="{{ $post->title }}">
<meta property="og:description" content="{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 200) }}">
@if($post->featured_image)
<meta property="og:image" content="{{ url(Storage::url($post->featured_image)) }}">
@endif
<meta property="og:site_name" content="JCI Carthage">
<meta property="article:published_time" content="{{ $post->published_at->toIso8601String() }}">
<meta property="article:modified_time" content="{{ $post->updated_at->toIso8601String() }}">
<meta property="article:author" content="{{ $post->author->name }}">
@if($post->category)
<meta property="article:section" content="{{ $post->category }}">
@endif
@if($post->tags)
    @foreach($post->tags as $tag)
<meta property="article:tag" content="{{ $tag }}">
    @endforeach
@endif

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ route('blog.show', $post->slug) }}">
<meta name="twitter:title" content="{{ $post->title }}">
<meta name="twitter:description" content="{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 200) }}">
@if($post->featured_image)
<meta name="twitter:image" content="{{ url(Storage::url($post->featured_image)) }}">
@endif

<!-- Schema.org JSON-LD -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "headline": "{{ $post->title }}",
  "image": "{{ $post->featured_image ? url(Storage::url($post->featured_image)) : asset('images/logo.png') }}",
  "datePublished": "{{ $post->published_at->toIso8601String() }}",
  "dateModified": "{{ $post->updated_at->toIso8601String() }}",
  "author": {
    "@type": "Person",
    "name": "{{ $post->author->name }}"
  },
  "publisher": {
    "@type": "Organization",
    "name": "JCI Carthage",
    "logo": {
      "@type": "ImageObject",
      "url": "{{ asset('images/logo.png') }}"
    }
  },
  "description": "{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 200) }}",
  "articleBody": {{ json_encode(strip_tags($post->content)) }},
  "wordCount": "{{ str_word_count(strip_tags($post->content)) }}",
  "timeRequired": "PT{{ $post->reading_time }}M",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "{{ route('blog.show', $post->slug) }}"
  }
}
</script>
@endsection

@section('content')
<!-- Hero Section with Featured Image -->
@if($post->featured_image)
<div class="relative h-96 bg-gray-900">
    <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover opacity-60">
    <div class="absolute inset-0 flex items-center justify-center">
        <div class="text-center text-white max-w-4xl px-4">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $post->title }}</h1>
        </div>
    </div>
</div>
@else
<div class="jci-gradient text-white py-24">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold">{{ $post->title }}</h1>
    </div>
</div>
@endif

<!-- Article Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="lg:grid lg:grid-cols-3 lg:gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <article class="bg-white rounded-lg shadow-sm p-8">
                @php
                    $contentText = trim((string) $post->content);
                    $excerptText = trim((string) $post->excerpt);
                    $showExcerpt = $excerptText !== '' && !Str::startsWith($contentText, $excerptText);
                    $contentParagraphs = collect(preg_split("/\R{2,}/u", $contentText))
                        ->map(fn ($paragraph) => trim($paragraph))
                        ->filter();
                @endphp

                <!-- Meta Information -->
                <div class="flex items-center text-sm text-gray-600 mb-6 pb-6 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-[#0097D7] rounded-full flex items-center justify-center text-white font-bold mr-3">
                            {{ substr($post->author->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $post->author->name }}</p>
                            <div class="flex items-center space-x-4 text-gray-500">
                                <span>{{ $post->formatted_date }}</span>
                                <span>•</span>
                                <span>{{ $post->reading_time }} {{ __('blog.reading_time') }}</span>
                                <span>•</span>
                                <span>{{ $post->views_count }} {{ __('blog.views') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Category & Tags -->
                <div class="mb-6">
                    @if($post->category)
                    <a href="{{ route('blog.category', $post->category) }}" class="inline-block px-3 py-1 bg-[#0097D7] text-white text-sm rounded-full hover:bg-[#1F4789] transition">
                        {{ $post->category }}
                    </a>
                    @endif
                </div>

                @if($post->featured_image)
                <figure class="mb-8 overflow-hidden rounded-2xl shadow-sm">
                    <img
                        src="{{ Storage::url($post->featured_image) }}"
                        alt="{{ $post->title }}"
                        class="w-full max-h-[32rem] object-cover"
                    >
                </figure>
                @endif

                <!-- Excerpt -->
                @if($showExcerpt)
                <div class="text-xl text-gray-600 mb-8 font-light italic border-l-4 border-[#0097D7] pl-4">
                    {{ $post->excerpt }}
                </div>
                @endif

                <!-- Content -->
                <div class="prose prose-lg max-w-none">
                    @foreach($contentParagraphs as $paragraph)
                    <p class="leading-8 text-gray-700">{!! nl2br(e($paragraph)) !!}</p>
                    @endforeach
                </div>

                <!-- Tags -->
                @if($post->tags && count($post->tags) > 0)
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">{{ __('blog.tags') }}</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($post->tags as $tag)
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Share Buttons -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">{{ __('Share this article') }}</h3>
                    <div class="flex space-x-4">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $post->slug)) }}" target="_blank" class="text-blue-600 hover:text-blue-700">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $post->slug)) }}&text={{ urlencode($post->title) }}" target="_blank" class="text-blue-400 hover:text-blue-500">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(route('blog.show', $post->slug)) }}&title={{ urlencode($post->title) }}" target="_blank" class="text-blue-700 hover:text-blue-800">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                    </div>
                </div>
            </article>

            <!-- Related Posts -->
            @if($relatedPosts->count() > 0)
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('blog.related_posts') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedPosts as $related)
                    <a href="{{ route('blog.show', $related->slug) }}" class="group">
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition">
                            @if($related->featured_image)
                            <img src="{{ Storage::url($related->featured_image) }}" alt="{{ $related->title }}" class="w-full h-48 object-cover group-hover:scale-105 transition">
                            @endif
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 group-hover:text-[#0097D7] transition">{{ $related->title }}</h3>
                                <p class="text-sm text-gray-500 mt-2">{{ $related->formatted_date }}</p>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="mt-12 lg:mt-0">
            <!-- Recent Posts -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
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

            <!-- Back to Blog -->
            <a href="{{ route('blog.index') }}" class="block bg-[#0097D7] text-white text-center py-3 px-6 rounded-lg hover:bg-[#1F4789] transition">
                {{ __('blog.back_to_list') }}
            </a>
        </div>
    </div>
</div>
@endsection
