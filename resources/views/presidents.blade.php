@extends('layouts.public')

@section('title', $aboutContent['presidents']['page_title'])
@section('meta_description', $aboutContent['presidents']['hero_description'])

@push('structured-data')
@php
    $presidentItems = $presidents->values()->map(function ($president, $index) use ($aboutContent) {
        return [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'item' => array_filter([
                '@type' => 'Person',
                'name' => $president->name,
                'image' => $president->photo_url ? \App\Support\Schema::absoluteUrl($president->photo_url) : null,
                'jobTitle' => str_replace(':year', $president->presidency_year, $aboutContent['presidents']['term_label']),
                'memberOf' => \App\Support\Schema::organizationReference(),
            ]),
        ];
    })->all();

    $presidentsSchemas = [
        \App\Support\Schema::page('CollectionPage', $aboutContent['presidents']['page_title'], $aboutContent['presidents']['hero_description'], route('presidents'), [
            'about' => \App\Support\Schema::organizationReference(),
            'mainEntity' => [
                '@type' => 'ItemList',
                'itemListOrder' => 'https://schema.org/ItemListOrderAscending',
                'numberOfItems' => count($presidentItems),
                'itemListElement' => $presidentItems,
            ],
        ]),
        \App\Support\Schema::breadcrumb([
            ['name' => __('website.nav.home'), 'url' => route('home')],
            ['name' => __('website.nav.about'), 'url' => route('about')],
            ['name' => $aboutContent['presidents']['title'], 'url' => route('presidents')],
        ]),
    ];
@endphp
@include('partials.seo.json-ld', ['schemas' => $presidentsSchemas])
@endpush

@push('styles')
<style>
    .presidents-hero {
        background:
            radial-gradient(circle at top left, rgba(87, 188, 188, 0.22), transparent 26%),
            radial-gradient(circle at bottom right, rgba(239, 196, 15, 0.15), transparent 24%),
            linear-gradient(135deg, #130f2d 0%, #1f4789 48%, #0097d7 100%);
    }
    .presidents-overview {
        background:
            radial-gradient(circle at top center, rgba(0, 151, 215, 0.12), transparent 36%),
            linear-gradient(180deg, #ffffff 0%, #f7fbff 100%);
    }
    .presidents-stat {
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.96) 0%, rgba(248, 251, 255, 1) 100%);
        box-shadow: 0 24px 60px rgba(19, 15, 45, 0.08);
    }
    .presidents-card {
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        box-shadow: 0 18px 44px rgba(19, 15, 45, 0.08);
    }
    .presidents-card::before {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: inherit;
        padding: 1px;
        background: linear-gradient(180deg, rgba(0, 151, 215, 0.18), rgba(19, 15, 45, 0.06));
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
    }
    .presidents-card:hover .presidents-photo {
        transform: scale(1.06);
    }
</style>
@endpush

@section('content')
@php
    $presidentsCount = $presidents->count();
    $firstPresident = $presidents->first();
    $lastPresident = $presidents->last();
    $cardGradients = [
        'from-[#0097D7] via-[#1F4789] to-[#130F2D]',
        'from-[#57BCBC] via-[#0097D7] to-[#1F4789]',
        'from-[#EFC40F] via-[#D9A61A] to-[#130F2D]',
    ];
@endphp

<section class="presidents-hero text-white pt-36 pb-18 overflow-hidden relative">
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-20 left-8 h-40 w-40 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 h-52 w-52 rounded-full bg-[#EFC40F]/20 blur-3xl"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl">
            <span class="inline-flex items-center rounded-full bg-white/10 px-4 py-1.5 text-sm font-semibold tracking-[0.24em] uppercase text-white/80">
                {{ $aboutContent['presidents']['eyebrow'] }}
            </span>
            <h1 class="mt-6 text-4xl md:text-6xl font-extrabold leading-tight">{{ $aboutContent['presidents']['title'] }}</h1>
            <p class="mt-5 text-xl md:text-2xl text-blue-50 max-w-3xl">{{ $aboutContent['presidents']['hero_subtitle'] }}</p>
            <p class="mt-6 text-base md:text-lg text-white/80 max-w-3xl leading-8">{{ $aboutContent['presidents']['hero_description'] }}</p>

            <div class="mt-8 flex flex-col sm:flex-row gap-4">
                <a href="{{ route('about') }}" class="inline-flex items-center justify-center rounded-2xl bg-white px-6 py-3.5 text-base font-semibold text-[#130F2D] transition hover:-translate-y-0.5 hover:bg-[#EFC40F]">
                    {{ $aboutContent['presidents']['back_to_about'] }}
                </a>
                @if(auth()->check() && auth()->user()->isAdmin())
                <a href="{{ route('admin.presidents.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-white/30 px-6 py-3.5 text-base font-semibold text-white transition hover:border-white hover:bg-white/10">
                    {{ $aboutContent['presidents']['dashboard_cta'] }}
                </a>
                @endif
            </div>
        </div>
    </div>
</section>

<section class="presidents-overview relative -mt-10 z-10 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid gap-5 md:grid-cols-3">
            <article class="presidents-stat rounded-[1.75rem] border border-white/70 p-6 ring-1 ring-[#0097D7]/10 backdrop-blur">
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0097D7]">{{ $aboutContent['presidents']['stats_total'] }}</p>
                <div class="mt-4 flex items-end gap-3">
                    <span class="text-4xl font-extrabold text-[#130F2D]">{{ str_pad((string) $presidentsCount, 2, '0', STR_PAD_LEFT) }}</span>
                    <span class="pb-1 text-sm text-gray-500">{{ $aboutContent['presidents']['title'] }}</span>
                </div>
            </article>
            <article class="presidents-stat rounded-[1.75rem] border border-white/70 p-6 ring-1 ring-[#0097D7]/10 backdrop-blur">
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0097D7]">{{ $aboutContent['presidents']['stats_first'] }}</p>
                <div class="mt-4 text-3xl font-extrabold text-[#130F2D]">{{ $firstPresident?->presidency_year ?? '--' }}</div>
                <p class="mt-3 text-sm leading-7 text-gray-500">{{ $firstPresident?->name ?? $aboutContent['presidents']['empty'] }}</p>
            </article>
            <article class="presidents-stat rounded-[1.75rem] border border-white/70 p-6 ring-1 ring-[#0097D7]/10 backdrop-blur">
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0097D7]">{{ $aboutContent['presidents']['stats_latest'] }}</p>
                <div class="mt-4 text-3xl font-extrabold text-[#130F2D]">{{ $lastPresident?->presidency_year ?? '--' }}</div>
                <p class="mt-3 text-sm leading-7 text-gray-500">{{ $lastPresident?->name ?? $aboutContent['presidents']['empty'] }}</p>
            </article>
        </div>
    </div>
</section>

<section class="bg-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($presidents->isNotEmpty())
        <div class="mb-12 overflow-hidden rounded-[2rem] bg-[linear-gradient(135deg,#f8fbff_0%,#ffffff_55%,#f5f8fc_100%)] p-8 shadow-[0_22px_50px_rgba(19,15,45,0.06)] ring-1 ring-[#0097D7]/10">
            <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl">
                    <span class="inline-flex items-center rounded-full bg-[#0097D7]/10 px-4 py-1.5 text-sm font-semibold uppercase tracking-[0.22em] text-[#0097D7]">
                        {{ $aboutContent['presidents']['chronology_badge'] }}
                    </span>
                    <h2 class="mt-5 text-3xl md:text-4xl font-extrabold text-[#130F2D]">{{ $aboutContent['presidents']['grid_title'] }}</h2>
                    <p class="mt-4 text-base leading-8 text-gray-600">{{ $aboutContent['presidents']['description'] }}</p>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div class="rounded-2xl bg-white px-5 py-4 ring-1 ring-[#0097D7]/10">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#0097D7]">{{ $aboutContent['presidents']['stats_first'] }}</p>
                        <p class="mt-2 text-lg font-bold text-[#130F2D]">{{ $firstPresident->presidency_year }}</p>
                    </div>
                    <div class="rounded-2xl bg-white px-5 py-4 ring-1 ring-[#0097D7]/10">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#0097D7]">{{ $aboutContent['presidents']['stats_latest'] }}</p>
                        <p class="mt-2 text-lg font-bold text-[#130F2D]">{{ $lastPresident->presidency_year }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6">
            @foreach($presidents as $president)
            @php
                $cardGradient = $cardGradients[$loop->index % count($cardGradients)];
                $presidentInitials = collect(explode(' ', trim($president->name)))
                    ->filter()
                    ->take(2)
                    ->map(function ($part) {
                        return \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($part, 0, 1));
                    })
                    ->implode('');
            @endphp
            <article class="presidents-card group relative overflow-hidden rounded-[1.85rem] transition duration-300 hover:-translate-y-2 hover:shadow-[0_28px_58px_rgba(19,15,45,0.14)]">
                <div class="relative aspect-[4/5] overflow-hidden bg-gradient-to-br {{ $cardGradient }}">
                    <div class="absolute left-4 top-4 z-10 inline-flex rounded-full bg-white/90 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] text-[#130F2D]">
                        {{ $president->presidency_year }}
                    </div>
                    <div class="absolute right-4 top-4 z-10 inline-flex h-9 w-9 items-center justify-center rounded-full border border-white/25 bg-white/10 text-xs font-bold text-white backdrop-blur">
                        {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                    </div>

                    @if($president->photo_url)
                    <img src="{{ $president->photo_url }}" alt="{{ $president->name }}" class="presidents-photo h-full w-full object-cover transition duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#130F2D]/90 via-[#130F2D]/15 to-transparent"></div>
                    @else
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-5xl font-black tracking-[0.18em] text-white/90">{{ $presidentInitials }}</span>
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-[#130F2D]/75 via-transparent to-transparent"></div>
                    @endif

                    <div class="absolute inset-x-0 bottom-0 z-10 p-5">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-white/70">{{ $aboutContent['presidents']['legacy_label'] }}</p>
                        <h2 class="mt-2 text-xl font-bold leading-snug text-white">{{ $president->name }}</h2>
                    </div>
                </div>
                <div class="p-5">
                    <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">
                        <span>{{ $aboutContent['presidents']['chronology_badge'] }}</span>
                        <span>#{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <p class="mt-3 text-sm leading-7 text-gray-600">{{ str_replace(':year', $president->presidency_year, $aboutContent['presidents']['term_label']) }}</p>
                </div>
            </article>
            @endforeach
        </div>
        @else
        <div class="rounded-[2rem] border border-dashed border-[#0097D7]/30 bg-[#F8FBFF] p-10 text-center">
            <p class="text-lg font-medium text-[#130F2D]">{{ $aboutContent['presidents']['empty'] }}</p>
        </div>
        @endif
    </div>
</section>
@endsection
