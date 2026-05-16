@extends('layouts.public')

@section('title', $aboutContent['page_title'])

@push('styles')
<style>
    .about-hero {
        background:
            radial-gradient(circle at top left, rgba(87, 188, 188, 0.28), transparent 28%),
            radial-gradient(circle at bottom right, rgba(239, 196, 15, 0.14), transparent 22%),
            linear-gradient(135deg, #130f2d 0%, #1f4789 42%, #0097d7 100%);
    }
    .about-glass {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.18);
    }
    .about-outline {
        position: relative;
    }
    .about-outline::before {
        content: '';
        position: absolute;
        inset: -1px;
        border-radius: inherit;
        padding: 1px;
        background: linear-gradient(135deg, rgba(0, 151, 215, 0.4), rgba(87, 188, 188, 0.15), rgba(239, 196, 15, 0.28));
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
    }
    .about-dot {
        box-shadow: 0 0 0 8px rgba(0, 151, 215, 0.12);
    }
</style>
@endpush

@section('content')
<section class="about-hero text-white pt-36 pb-20 overflow-hidden relative">
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-24 left-8 h-40 w-40 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute bottom-8 right-0 h-56 w-56 rounded-full bg-[#EFC40F]/20 blur-3xl"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-[1.1fr_0.9fr] gap-10 items-end">
            <div class="max-w-3xl">
                <span class="inline-flex items-center rounded-full bg-white/10 px-4 py-1.5 text-sm font-semibold tracking-[0.24em] uppercase text-white/80">
                    {{ $aboutContent['hero']['eyebrow'] }}
                </span>
                <h1 class="mt-6 text-4xl md:text-6xl font-extrabold leading-tight">{{ $aboutContent['hero']['title'] }}</h1>
                <p class="mt-5 text-xl md:text-2xl text-blue-50 max-w-2xl">{{ $aboutContent['hero']['subtitle'] }}</p>
                <p class="mt-6 text-base md:text-lg text-white/80 max-w-2xl leading-8">{{ $aboutContent['hero']['description'] }}</p>

                <div class="mt-8 flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('contact') }}" class="inline-flex items-center justify-center rounded-2xl bg-white px-6 py-3.5 text-base font-semibold text-[#130F2D] transition hover:-translate-y-0.5 hover:bg-[#EFC40F]">
                        {{ $aboutContent['hero']['primary_cta'] }}
                    </a>
                    <a href="{{ route('presidents') }}" class="inline-flex items-center justify-center rounded-2xl border border-white/30 px-6 py-3.5 text-base font-semibold text-white transition hover:border-white hover:bg-white/10">
                        {{ $aboutContent['hero']['secondary_cta'] }}
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                @foreach($aboutContent['stats'] as $stat)
                <div class="about-glass rounded-3xl p-5 shadow-2xl">
                    <p class="text-3xl md:text-4xl font-extrabold">{{ $stat['value'] }}</p>
                    <p class="mt-2 text-sm md:text-base font-semibold text-white">{{ $stat['label'] }}</p>
                    <p class="mt-1 text-sm text-blue-100/80 leading-6">{{ $stat['context'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="bg-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <span class="text-sm font-semibold uppercase tracking-[0.24em] text-[#0097D7]">{{ $aboutContent['story']['eyebrow'] }}</span>
            <h2 class="mt-3 text-3xl md:text-5xl font-extrabold text-[#130F2D]">{{ $aboutContent['story']['title'] }}</h2>
            <p class="mt-5 text-lg leading-8 text-gray-600">{{ $aboutContent['story']['description'] }}</p>
        </div>

        <div class="mt-12 grid gap-6 lg:grid-cols-3">
            @foreach($aboutContent['story']['timeline'] as $index => $item)
            <div class="about-outline rounded-[2rem] bg-white p-8 shadow-[0_18px_60px_rgba(19,15,45,0.08)]">
                <div class="flex items-center gap-4">
                    <div class="about-dot flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#0097D7] text-lg font-bold text-white">
                        {{ $index + 1 }}
                    </div>
                    <h3 class="text-xl font-bold text-[#130F2D]">{{ $item['title'] }}</h3>
                </div>
                <p class="mt-5 text-base leading-7 text-gray-600">{{ $item['text'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-[#F8FBFF] py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <span class="text-sm font-semibold uppercase tracking-[0.24em] text-[#0097D7]">{{ $aboutContent['principles']['eyebrow'] }}</span>
            <h2 class="mt-3 text-3xl md:text-5xl font-extrabold text-[#130F2D]">{{ $aboutContent['principles']['title'] }}</h2>
        </div>

        <div class="mt-12 grid gap-6 lg:grid-cols-12">
            <div class="lg:col-span-4 rounded-[2rem] bg-white p-8 shadow-sm">
                <span class="inline-flex rounded-full bg-[#0097D7]/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-[#0097D7]">{{ $aboutContent['principles']['mission']['title'] }}</span>
                <h3 class="mt-5 text-2xl font-bold text-[#130F2D]">{{ $aboutContent['principles']['mission']['title'] }}</h3>
                <p class="mt-4 text-base leading-7 text-gray-600">{{ $aboutContent['principles']['mission']['text'] }}</p>
            </div>

            <div class="lg:col-span-4 rounded-[2rem] bg-[#130F2D] p-8 text-white shadow-sm">
                <span class="inline-flex rounded-full bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-white/80">{{ $aboutContent['principles']['vision']['title'] }}</span>
                <h3 class="mt-5 text-2xl font-bold">{{ $aboutContent['principles']['vision']['title'] }}</h3>
                <p class="mt-4 text-base leading-7 text-blue-50/85">{{ $aboutContent['principles']['vision']['text'] }}</p>
            </div>

            <div class="lg:col-span-4 rounded-[2rem] bg-gradient-to-br from-[#0097D7] to-[#57BCBC] p-8 text-white shadow-sm">
                <span class="inline-flex rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-white/85">{{ $aboutContent['principles']['credo']['title'] }}</span>
                <h3 class="mt-5 text-2xl font-bold">{{ $aboutContent['principles']['credo']['title'] }}</h3>
                <ul class="mt-4 space-y-3 text-sm md:text-base text-white/90 leading-7">
                    @foreach($aboutContent['principles']['credo']['items'] as $credoItem)
                    <li class="flex items-start gap-3">
                        <span class="mt-2 h-2 w-2 shrink-0 rounded-full bg-[#EFC40F]"></span>
                        <span>{{ $credoItem }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#130F2D] py-20 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid gap-12 lg:grid-cols-[0.9fr_1.1fr] items-start">
            <div>
                <span class="text-sm font-semibold uppercase tracking-[0.24em] text-[#57BCBC]">{{ $aboutContent['rise']['eyebrow'] }}</span>
                <h2 class="mt-3 text-3xl md:text-5xl font-extrabold">{{ $aboutContent['rise']['title'] }}</h2>
                <p class="mt-5 text-lg leading-8 text-blue-50/80">{{ $aboutContent['rise']['description'] }}</p>
            </div>

            <div class="grid gap-5 md:grid-cols-3">
                @foreach($aboutContent['rise']['pillars'] as $pillar)
                <div class="rounded-[2rem] border border-white/10 bg-white/5 p-6">
                    <div class="h-12 w-12 rounded-2xl bg-[#EFC40F] text-[#130F2D] flex items-center justify-center text-lg font-bold">
                        {{ strtoupper(substr($pillar['title'], 0, 1)) }}
                    </div>
                    <h3 class="mt-5 text-xl font-bold">{{ $pillar['title'] }}</h3>
                    <p class="mt-4 text-sm leading-7 text-blue-50/80">{{ $pillar['text'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="bg-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid gap-16 lg:grid-cols-[1.05fr_0.95fr]">
            <div>
                <span class="text-sm font-semibold uppercase tracking-[0.24em] text-[#0097D7]">{{ $aboutContent['leaders']['eyebrow'] }}</span>
                <h2 class="mt-3 text-3xl md:text-5xl font-extrabold text-[#130F2D]">{{ $aboutContent['leaders']['title'] }}</h2>
                <div class="mt-10 grid gap-5">
                    @foreach($aboutContent['leaders']['items'] as $leader)
                    <div class="rounded-[2rem] border border-gray-100 bg-[#F8FBFF] p-6 shadow-sm">
                        <div class="flex items-start gap-4">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#130F2D] text-lg font-bold text-white">
                                {{ strtoupper(substr($leader['name'], 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-[#130F2D]">{{ $leader['name'] }}</h3>
                                <p class="mt-2 text-base leading-7 text-gray-600">{{ $leader['role'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-[2.2rem] bg-gradient-to-br from-[#130F2D] to-[#1F4789] p-8 text-white shadow-2xl">
                <span class="text-sm font-semibold uppercase tracking-[0.24em] text-[#57BCBC]">{{ $aboutContent['board']['eyebrow'] }}</span>
                <h2 class="mt-3 text-3xl font-extrabold">{{ $aboutContent['board']['title'] }}</h2>
                <p class="mt-5 text-base leading-7 text-blue-50/80">{{ $aboutContent['board']['description'] }}</p>

                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                    @foreach($aboutContent['board']['items'] as $member)
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                        <p class="text-lg font-bold">{{ $member['name'] }}</p>
                        <p class="mt-2 text-sm uppercase tracking-[0.2em] text-[#EFC40F]">{{ $member['role'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#F8FBFF] py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <span class="text-sm font-semibold uppercase tracking-[0.24em] text-[#0097D7]">{{ $aboutContent['actions']['eyebrow'] }}</span>
            <h2 class="mt-3 text-3xl md:text-5xl font-extrabold text-[#130F2D]">{{ $aboutContent['actions']['title'] }}</h2>
            <p class="mt-5 text-lg leading-8 text-gray-600">{{ $aboutContent['actions']['description'] }}</p>
        </div>

        <div class="mt-12 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            @foreach($aboutContent['actions']['items'] as $action)
            <div class="rounded-[2rem] bg-white p-7 shadow-sm ring-1 ring-[#0097D7]/10">
                <div class="h-12 w-12 rounded-2xl bg-[#0097D7]/10 flex items-center justify-center text-[#0097D7] font-bold">
                    {{ strtoupper(mb_substr($action['title'], 0, 1)) }}
                </div>
                <h3 class="mt-5 text-2xl font-bold text-[#130F2D]">{{ $action['title'] }}</h3>
                <p class="mt-4 text-base leading-7 text-gray-600">{{ $action['text'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-[#130F2D] py-20 text-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-flex rounded-full bg-white/10 px-4 py-1.5 text-sm font-semibold uppercase tracking-[0.24em] text-white/75">JCI Carthage</span>
        <h2 class="mt-5 text-3xl md:text-5xl font-extrabold">{{ $aboutContent['cta']['title'] }}</h2>
        <p class="mt-5 text-lg leading-8 text-blue-50/80">{{ $aboutContent['cta']['description'] }}</p>
        <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('contact') }}" class="inline-flex items-center justify-center rounded-2xl bg-[#EFC40F] px-6 py-3.5 text-base font-semibold text-[#130F2D] transition hover:-translate-y-0.5 hover:bg-[#f7d94f]">
                {{ $aboutContent['cta']['primary_cta'] }}
            </a>
            <a href="{{ route('activities') }}" class="inline-flex items-center justify-center rounded-2xl border border-white/20 px-6 py-3.5 text-base font-semibold text-white transition hover:border-white hover:bg-white/10">
                {{ $aboutContent['cta']['secondary_cta'] }}
            </a>
        </div>
    </div>
</section>
@endsection
