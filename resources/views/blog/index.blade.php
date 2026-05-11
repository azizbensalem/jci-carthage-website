@extends('layouts.public')

@section('title', __('blog.blog') . ' - JCI Carthage')

@push('styles')
<style>
    .blog-hero-pattern {
        background-image: radial-gradient(rgba(255, 255, 255, 0.18) 1px, transparent 1px);
        background-size: 22px 22px;
    }

    .blog-card:hover .blog-card-image {
        transform: scale(1.05);
    }
</style>
@endpush

@section('content')
<section class="relative overflow-hidden pb-16 pt-28 text-white">
    <div class="absolute inset-0 bg-gradient-to-br from-[#0097D7] via-[#1F4789] to-[#130F2D]"></div>
    <div class="absolute -left-10 -top-16 h-72 w-72 rounded-full bg-cyan-300/25 blur-3xl"></div>
    <div class="absolute right-0 top-10 h-96 w-96 rounded-full bg-blue-500/25 blur-3xl"></div>
    <div class="absolute inset-0 blog-hero-pattern opacity-20"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <span class="inline-flex rounded-full border border-white/20 bg-white/10 px-4 py-1 text-sm font-semibold uppercase tracking-[0.24em] text-blue-50">
                {{ __('blog.hero_badge') }}
            </span>
            <h1 class="mt-6 max-w-3xl text-4xl font-extrabold leading-tight md:text-5xl lg:text-6xl">
                {{ __('blog.hero_title') }}
            </h1>
            <p class="mt-5 max-w-2xl text-lg leading-8 text-blue-50/90">
                {{ __('blog.hero_description') }}
            </p>

            @if($hasActiveFilters)
            <div class="mt-6 inline-flex items-center rounded-full border border-cyan-200/30 bg-slate-950/20 px-4 py-2 text-sm text-blue-50">
                {{ __('blog.active_filters') }}
            </div>
            @endif
        </div>
    </div>
</section>

<div class="bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">{{ __('blog.filters_title') }}</h2>
                    <p class="mt-2 text-sm text-slate-600">{{ __('blog.filters_description') }}</p>
                </div>
            </div>

            <form method="GET" action="{{ route('blog.index') }}" class="mt-6">
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div>
                        <label for="search" class="mb-2 block text-sm font-semibold text-slate-700">{{ __('blog.keyword') }}</label>
                        <input
                            id="search"
                            type="text"
                            name="search"
                            value="{{ $filters['search'] }}"
                            placeholder="{{ __('blog.keyword_placeholder') }}"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-[#0097D7] focus:bg-white focus:ring-4 focus:ring-cyan-100"
                        >
                    </div>

                    <div>
                        <label for="category" class="mb-2 block text-sm font-semibold text-slate-700">{{ __('blog.category') }}</label>
                        <select
                            id="category"
                            name="category"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-[#0097D7] focus:bg-white focus:ring-4 focus:ring-cyan-100"
                        >
                            <option value="">{{ __('blog.all_categories') }}</option>
                            @foreach($categories as $category => $count)
                            <option value="{{ $category }}" @selected($filters['category'] === $category)>{{ $category }} ({{ $count }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="date_from" class="mb-2 block text-sm font-semibold text-slate-700">{{ __('blog.date_from') }}</label>
                        <input
                            id="date_from"
                            type="date"
                            name="date_from"
                            value="{{ $filters['date_from'] }}"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-[#0097D7] focus:bg-white focus:ring-4 focus:ring-cyan-100"
                        >
                    </div>

                    <div>
                        <label for="date_to" class="mb-2 block text-sm font-semibold text-slate-700">{{ __('blog.date_to') }}</label>
                        <input
                            id="date_to"
                            type="date"
                            name="date_to"
                            value="{{ $filters['date_to'] }}"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-[#0097D7] focus:bg-white focus:ring-4 focus:ring-cyan-100"
                        >
                    </div>
                </div>

                <div class="mt-5 flex flex-col gap-4 border-t border-slate-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-wrap gap-3">
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-2xl bg-[#0097D7] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#1F4789] hover:shadow-lg"
                        >
                            {{ __('blog.filter') }}
                        </button>

                        @if($hasActiveFilters)
                        <a
                            href="{{ route('blog.index') }}"
                            class="inline-flex items-center justify-center rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50"
                        >
                            {{ __('blog.reset_filters') }}
                        </a>
                        @endif
                    </div>

                    @if($hasActiveFilters)
                    <div class="flex flex-wrap items-center gap-2 text-sm">
                        <span class="font-semibold text-slate-600">{{ __('blog.active_filters') }}:</span>

                        @if($filters['search'] !== '')
                        <span class="rounded-full bg-cyan-50 px-3 py-1 text-cyan-700">{{ $filters['search'] }}</span>
                        @endif

                        @if($filters['category'] !== '')
                        <span class="rounded-full bg-cyan-50 px-3 py-1 text-cyan-700">{{ $filters['category'] }}</span>
                        @endif

                        @if($filters['date_from'] !== '')
                        <span class="rounded-full bg-cyan-50 px-3 py-1 text-cyan-700">{{ __('blog.date_from') }} {{ $filters['date_from'] }}</span>
                        @endif

                        @if($filters['date_to'] !== '')
                        <span class="rounded-full bg-cyan-50 px-3 py-1 text-cyan-700">{{ __('blog.date_to') }} {{ $filters['date_to'] }}</span>
                        @endif
                    </div>
                    @endif
                </div>
            </form>
        </div>

        @if(!$hasActiveFilters && $featuredPosts->count() > 0)
        <section class="mt-12">
            <div class="mb-6 flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">{{ __('blog.featured_posts') }}</h2>
                    <p class="mt-2 text-sm text-slate-600">{{ __('blog.hero_description') }}</p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                @foreach($featuredPosts as $featured)
                <article class="blog-card group flex h-full flex-col overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-2xl">
                    <a href="{{ route('blog.show', $featured->slug) }}" class="relative block overflow-hidden">
                        @if($featured->featured_image)
                        <img src="{{ Storage::url($featured->featured_image) }}" alt="{{ $featured->title }}" class="blog-card-image h-56 w-full object-cover transition duration-500">
                        @else
                        <div class="blog-card-image flex h-56 items-end bg-gradient-to-br from-[#0097D7] via-[#1F4789] to-[#130F2D] p-5 transition duration-500">
                            <span class="rounded-full bg-white/15 px-3 py-1 text-sm font-semibold text-white">JCI Carthage</span>
                        </div>
                        @endif
                        <div class="absolute inset-x-0 top-4 flex justify-between px-4">
                            <span class="rounded-full bg-[#EFC40F] px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-slate-900">
                                {{ __('blog.featured') }}
                            </span>
                        </div>
                    </a>

                    <div class="flex flex-1 flex-col p-6">
                        <div class="flex items-center gap-2 text-sm text-slate-500">
                            <span>{{ $featured->formatted_date }}</span>
                            <span>•</span>
                            <span>{{ $featured->reading_time }} {{ __('blog.reading_time') }}</span>
                        </div>

                        <h3 class="mt-4 text-xl font-bold leading-snug text-slate-900 transition group-hover:text-[#0097D7]">
                            <a href="{{ route('blog.show', $featured->slug) }}">{{ $featured->title }}</a>
                        </h3>

                        <p class="mt-3 flex-1 text-sm leading-7 text-slate-600">
                            {{ Str::limit($featured->excerpt ?? strip_tags($featured->content), 120) }}
                        </p>

                        <a href="{{ route('blog.show', $featured->slug) }}" class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-[#0097D7] transition hover:text-[#1F4789]">
                            {{ __('blog.read_more') }}
                            <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </article>
                @endforeach
            </div>
        </section>
        @endif

        <div class="mt-12 grid gap-8 lg:grid-cols-3">
            <section class="lg:col-span-2">
                <div class="mb-6 flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900">{{ __('blog.all_posts') }}</h2>
                    </div>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    @forelse($posts as $post)
                    @php
                        $postCategoryUrl = $post->category
                            ? route('blog.index', array_merge(request()->except(['page', 'category']), ['category' => $post->category]))
                            : null;
                    @endphp
                    <article class="blog-card group flex h-full flex-col overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-2xl">
                        <a href="{{ route('blog.show', $post->slug) }}" class="relative block overflow-hidden">
                            @if($post->featured_image)
                            <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="blog-card-image h-56 w-full object-cover transition duration-500">
                            @else
                            <div class="blog-card-image flex h-56 items-end bg-gradient-to-br from-[#0097D7] via-[#1F4789] to-[#130F2D] p-5 transition duration-500">
                                <span class="rounded-full bg-white/15 px-3 py-1 text-sm font-semibold text-white">JCI Carthage</span>
                            </div>
                            @endif

                            @if($post->category)
                            <div class="absolute inset-x-0 bottom-0 p-4">
                                <span class="inline-flex rounded-full bg-slate-950/70 px-3 py-1 text-xs font-semibold text-white backdrop-blur">
                                    {{ $post->category }}
                                </span>
                            </div>
                            @endif
                        </a>

                        <div class="flex flex-1 flex-col p-6">
                            <div class="flex flex-wrap items-center gap-2 text-sm text-slate-500">
                                <span>{{ $post->formatted_date }}</span>
                                <span>•</span>
                                <span>{{ $post->reading_time }} {{ __('blog.reading_time') }}</span>
                            </div>

                            <h3 class="mt-4 text-2xl font-bold leading-snug text-slate-900 transition group-hover:text-[#0097D7]">
                                <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                            </h3>

                            <p class="mt-3 flex-1 text-sm leading-7 text-slate-600">
                                {{ Str::limit($post->excerpt ?? strip_tags($post->content), 150) }}
                            </p>

                            <div class="mt-6 flex items-center justify-between gap-4 border-t border-slate-100 pt-5">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-cyan-100 text-sm font-bold text-[#1F4789]">
                                            {{ strtoupper(substr($post->author->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-semibold text-slate-900">{{ $post->author->name }}</p>
                                            @if($postCategoryUrl)
                                            <a href="{{ $postCategoryUrl }}" class="text-xs font-medium text-[#0097D7] transition hover:text-[#1F4789]">
                                                {{ $post->category }}
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <a href="{{ route('blog.show', $post->slug) }}" class="flex-shrink-0 text-sm font-semibold text-[#0097D7] transition hover:text-[#1F4789]">
                                    {{ __('blog.read_more') }} →
                                </a>
                            </div>
                        </div>
                    </article>
                    @empty
                    <div class="rounded-[1.75rem] border border-dashed border-slate-300 bg-white p-10 text-center md:col-span-2">
                        <p class="text-lg font-semibold text-slate-900">{{ __('blog.no_results') }}</p>
                        <p class="mt-2 text-sm text-slate-600">{{ __('blog.no_results_hint') }}</p>
                    </div>
                    @endforelse
                </div>

                @if($posts->hasPages())
                <div class="mt-10">
                    {{ $posts->links() }}
                </div>
                @endif
            </section>

            <aside class="space-y-6 lg:sticky lg:top-24 lg:self-start">
                @if($categories->count() > 0)
                <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">{{ __('blog.categories') }}</h3>
                    <div class="mt-4 space-y-2">
                        @foreach($categories as $category => $count)
                        <a
                            href="{{ route('blog.index', array_merge(request()->except(['page', 'category']), ['category' => $category])) }}"
                            class="flex items-center justify-between rounded-2xl px-3 py-3 text-sm text-slate-700 transition hover:bg-slate-50 hover:text-[#0097D7]"
                        >
                            <span>{{ $category }}</span>
                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-500">{{ $count }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">{{ __('blog.recent_posts') }}</h3>
                    <div class="mt-4 space-y-4">
                        @foreach($recentPosts as $recent)
                        <a href="{{ route('blog.show', $recent->slug) }}" class="group block rounded-2xl border border-transparent p-3 transition hover:border-slate-200 hover:bg-slate-50">
                            <h4 class="font-medium text-slate-900 transition group-hover:text-[#0097D7]">{{ $recent->title }}</h4>
                            <p class="mt-2 text-sm text-slate-500">{{ $recent->formatted_date }}</p>
                        </a>
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection
