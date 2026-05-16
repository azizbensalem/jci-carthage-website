@extends('layouts.public')

@section('title', $aboutContent['presidents']['page_title'])

@push('styles')
<style>
    .presidents-hero {
        background:
            radial-gradient(circle at top left, rgba(87, 188, 188, 0.22), transparent 26%),
            radial-gradient(circle at bottom right, rgba(239, 196, 15, 0.15), transparent 24%),
            linear-gradient(135deg, #130f2d 0%, #1f4789 48%, #0097d7 100%);
    }
    .presidents-card {
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
    }
</style>
@endpush

@section('content')
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

<section class="bg-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($presidents->isNotEmpty())
        <div class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-6">
            @foreach($presidents as $president)
            <article class="presidents-card overflow-hidden rounded-[1.75rem] shadow-sm ring-1 ring-[#0097D7]/10 transition hover:-translate-y-1 hover:shadow-xl">
                <div class="aspect-square overflow-hidden bg-[#130F2D]/5">
                    <img src="{{ $president->photo_url }}" alt="{{ $president->name }}" class="h-full w-full object-cover transition duration-500 hover:scale-105">
                </div>
                <div class="p-4">
                    <span class="inline-flex rounded-full bg-[#0097D7]/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-[#0097D7]">
                        {{ $president->presidency_year }}
                    </span>
                    <h2 class="mt-3 text-lg font-bold leading-snug text-[#130F2D]">{{ $president->name }}</h2>
                    <p class="mt-2 text-sm text-gray-500">{{ str_replace(':year', $president->presidency_year, $aboutContent['presidents']['term_label']) }}</p>
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
