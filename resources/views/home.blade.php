@extends('layouts.public')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Accueil - JCI Carthage')

@push('styles')
<style>
    .hero-section {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.4)), 
                    url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        margin-top: 64px;
    }
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at center, transparent 0%, rgba(0, 0, 0, 0.3) 100%);
    }
    .social-bar {
        position: fixed;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        z-index: 45;
        background-color: rgba(31, 41, 55, 0.9);
        padding: 20px 10px;
        border-radius: 0 10px 10px 0;
        display: flex;
        flex-direction: column;
        gap: 15px;
        box-shadow: 2px 0 10px rgba(0,0,0,0.3);
    }
    .social-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.3s;
    }
    .social-icon:hover {
        transform: scale(1.1);
    }
    .hero-content {
        text-align: left;
        color: white;
        z-index: 10;
        padding: 0 80px;
        animation: fadeInUp 1s ease-out;
        max-width: 1200px;
        width: 100%;
        position: relative;
    }
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .hero-title {
        font-size: 4.5rem;
        font-weight: 800;
        margin-bottom: 1.5rem;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.8), 0 0 30px rgba(0,0,0,0.4);
        letter-spacing: -0.03em;
        line-height: 1.15;
        text-align: left;
        max-width: 800px;
    }
    .hero-subtitle {
        font-size: 1.5rem;
        margin-bottom: 2.5rem;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.8);
        font-weight: 400;
        letter-spacing: 0.01em;
        text-align: left;
        max-width: 700px;
        line-height: 1.6;
        opacity: 0.95;
    }
    .hero-buttons {
        display: flex;
        gap: 20px;
        justify-content: flex-start;
        flex-wrap: wrap;
        align-items: center;
    }
    .btn-hero-primary {
        background: linear-gradient(135deg, var(--jci-blue) 0%, var(--jci-navy) 100%);
        color: white;
        padding: 18px 48px;
        border-radius: 50px;
        font-weight: 800;
        font-size: 1.1rem;
        text-decoration: none;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        box-shadow: 0 10px 25px rgba(0, 151, 215, 0.4);
        letter-spacing: 0.8px;
        text-transform: uppercase;
        display: inline-block;
        position: relative;
        overflow: hidden;
    }
    .btn-hero-primary::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    .btn-hero-primary:hover::before {
        width: 300px;
        height: 300px;
    }
    .btn-hero-primary:hover {
        background: linear-gradient(135deg, var(--jci-navy) 0%, #16366b 100%);
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(31, 71, 137, 0.5);
    }
    .btn-hero-primary span {
        position: relative;
        z-index: 1;
    }
    .btn-hero-secondary {
        background-color: transparent;
        color: white;
        padding: 16px 42px;
        border-radius: 50px;
        font-weight: 700;
        text-decoration: none;
        border: 2px solid white;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        letter-spacing: 0.5px;
        backdrop-filter: blur(10px);
    }
    .btn-hero-secondary:hover {
        background-color: white;
        color: var(--jci-black);
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(255, 255, 255, 0.3);
    }
    @media (max-width: 768px) {
        .hero-content {
            padding: 0 20px;
            text-align: center;
        }
        .hero-title {
            font-size: 2.5rem;
            text-align: center;
            max-width: 100%;
        }
        .hero-subtitle {
            font-size: 1.2rem;
            text-align: center;
            max-width: 100%;
        }
        .social-bar {
            display: none;
        }
        .hero-buttons {
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .btn-hero-primary, .btn-hero-secondary {
            width: 100%;
            max-width: 300px;
        }
    }
    @media (max-width: 1024px) {
        .hero-content {
            padding: 0 40px;
        }
        .hero-title {
            font-size: 3.5rem;
        }
    }
    .carousel-slide {
        transition: opacity 1s ease-in-out;
    }
    /* Facebook Posts Styles */
    .fb-container {
        display: flex;
        justify-content: space-between;
        max-width: 1200px;
        margin: 0 auto;
        gap: 20px;
    }
    .fb-post {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        text-align: center;
        padding: 0;
        width: 32%;
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    .fb-post:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    }
    .fb-post img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        display: block;
    }
    .fb-post-content {
        padding: 20px;
    }
    .fb-post .fb-message {
        font-size: 14px;
        color: #333;
        text-align: left;
        line-height: 1.6em;
        margin-bottom: 12px;
        min-height: 100px;
        max-height: 120px;
        overflow: hidden;
    }
    .fb-post .fb-created-time {
        font-size: 12px;
        color: #888;
        text-align: left;
        margin-bottom: 15px;
    }
    .fb-link {
        display: inline-block;
        background: linear-gradient(135deg, #1877F2 0%, #1358A3 100%);
        color: #fff;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(24, 119, 242, 0.3);
    }
    .fb-link:hover {
        background: linear-gradient(135deg, #1358A3 0%, #0d3d7a 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(24, 119, 242, 0.4);
    }
    @media (max-width: 900px) {
        .fb-container {
            flex-wrap: wrap;
        }
        .fb-post {
            width: 48%;
        }
    }
    @media (max-width: 600px) {
        .fb-post {
            width: 100%;
        }
        .fb-post .fb-message {
            min-height: auto;
            max-height: none;
        }
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const slides = document.querySelectorAll('.carousel-slide');
        const indicators = document.querySelectorAll('.carousel-indicator');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        let currentSlide = 0;
        
        if (slides.length <= 1) return;
        
        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.toggle('opacity-100', i === index);
                slide.classList.toggle('opacity-0', i !== index);
            });
            indicators.forEach((indicator, i) => {
                indicator.classList.toggle('bg-white', i === index);
                indicator.classList.toggle('bg-white/50', i !== index);
            });
        }
        
        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }
        
        function prevSlide() {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(currentSlide);
        }
        
        if (nextBtn) nextBtn.addEventListener('click', nextSlide);
        if (prevBtn) prevBtn.addEventListener('click', prevSlide);
        
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                currentSlide = index;
                showSlide(currentSlide);
            });
        });
        
        // Auto-play carousel
        setInterval(nextSlide, 5000);
    });
</script>
@endpush

@section('content')
<!-- Social Media Bar -->
<div class="social-bar">
    <a href="https://facebook.com" target="_blank" class="social-icon" style="background-color: #1877F2;">
        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
        </svg>
    </a>
    <a href="https://twitter.com" target="_blank" class="social-icon" style="background-color: #1DA1F2;">
        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
        </svg>
    </a>
    <a href="https://linkedin.com" target="_blank" class="social-icon" style="background-color: #0077B5;">
        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
        </svg>
    </a>
    <a href="https://instagram.com" target="_blank" class="social-icon" style="background: linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%);">
        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
        </svg>
    </a>
    <a href="https://tiktok.com" target="_blank" class="social-icon" style="background-color: #000000;">
        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 005 20.1a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z"/>
        </svg>
    </a>
    <a href="https://youtube.com" target="_blank" class="social-icon" style="background-color: #FF0000;">
        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
        </svg>
    </a>
</div>

<!-- Hero Section with Carousel -->
@if($carousels->count() > 0)
<section class="hero-section" style="margin-top: 64px; position: relative;">
    <div id="carousel" class="relative w-full min-h-screen overflow-hidden">
        @foreach($carousels as $index => $carousel)
        <div class="carousel-slide absolute inset-0 transition-opacity duration-1000 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}" data-slide="{{ $index }}">
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $carousel->image ? Storage::url($carousel->image) : 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80' }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-black/75 via-black/55 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
            <div class="hero-content relative z-10 flex items-center min-h-screen">
                <div>
                    <h1 class="hero-title">{{ $carousel->title }}</h1>
                    @if($carousel->description)
                    <p class="hero-subtitle">{{ $carousel->description }}</p>
                    @endif
                    @if($carousel->link)
                    <div class="hero-buttons">
                        <a href="{{ $carousel->link }}" class="btn-hero-primary">
                            <span>{{ $carousel->button_text ?: __('website.home.discover_more') }}</span>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
        
        @if($carousels->count() > 1)
        <!-- Carousel Controls -->
        <button id="prevBtn" class="absolute left-6 top-1/2 transform -translate-y-1/2 bg-white/10 backdrop-blur-md hover:bg-white/25 text-white p-4 rounded-full transition-all duration-300 z-20 shadow-lg border border-white/20 hover:scale-110">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        <button id="nextBtn" class="absolute right-6 top-1/2 transform -translate-y-1/2 bg-white/10 backdrop-blur-md hover:bg-white/25 text-white p-4 rounded-full transition-all duration-300 z-20 shadow-lg border border-white/20 hover:scale-110">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
        
        <!-- Carousel Indicators -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 flex space-x-3 z-20">
            @foreach($carousels as $index => $carousel)
            <button class="carousel-indicator w-3 h-3 rounded-full {{ $index === 0 ? 'bg-white w-8' : 'bg-white/50' }} transition-all duration-300 hover:bg-white/80" data-slide="{{ $index }}"></button>
            @endforeach
        </div>
        @endif
    </div>
</section>
@else
<!-- Default Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title">{{ __('website.home.title') }}</h1>
        <p class="hero-subtitle">{{ __('website.home.welcome') }}</p>
        <div class="hero-buttons">
            <a href="{{ route('about') }}" class="btn-hero-primary">{{ __('website.home.who_are_we') }}</a>
            <a href="{{ route('activities') }}" class="btn-hero-secondary">{{ __('website.home.our_projects') }}</a>
        </div>
    </div>
</section>
@endif

<!-- About Section -->
<section class="py-16 bg-white" style="margin-top: 0;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-12">
            <h2 class="text-3xl md:text-4xl font-bold jci-primary-text mb-6">{{ __('website.about.title') }}</h2>
            <div class="max-w-4xl">
                <p class="text-lg text-gray-700 mb-4">
                    {{ __('website.about.description') }}
                </p>
                <p class="text-lg text-gray-700 mb-4">
                    {{ __('website.about.description2') }}
                </p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
            <div class="text-center p-8 rounded-xl bg-gradient-to-br from-blue-50 to-white hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-blue-100">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl w-20 h-20 flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold jci-primary-text mb-3">{{ __('website.home.innovative_solutions') }}</h3>
                <p class="text-gray-600 leading-relaxed">
                    {{ __('website.home.innovative_solutions_text') }}
                </p>
            </div>
            <div class="text-center p-8 rounded-xl bg-gradient-to-br from-blue-50 to-white hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-blue-100">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl w-20 h-20 flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold jci-primary-text mb-3">{{ __('website.home.collaboration') }}</h3>
                <p class="text-gray-600 leading-relaxed">
                    {{ __('website.home.collaboration_text') }}
                </p>
            </div>
            <div class="text-center p-8 rounded-xl bg-gradient-to-br from-blue-50 to-white hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-blue-100">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl w-20 h-20 flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold jci-primary-text mb-3">{{ __('website.home.courage_passion') }}</h3>
                <p class="text-gray-600 leading-relaxed">
                    {{ __('website.home.courage_passion_text') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Projects Section -->
<section class="py-20 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold jci-primary-text mb-4">{{ __('website.home.our_projects') }}</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                {{ __('website.home.our_projects_description') }}
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($projects as $project)
            <div class="bg-white p-10 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                <div class="flex items-center mb-6">
                    @php
                        $colorClasses = [
                            'blue' => 'from-[#0097D7] to-[#1F4789]',
                            'yellow' => 'from-[#FDE047] to-[#EFC40F]',
                            'green' => 'from-[#57BCBC] to-[#3a8585]',
                            'purple' => 'from-purple-400 to-purple-600',
                            'red' => 'from-red-400 to-red-600',
                        ];
                        $colorClass = $colorClasses[$project->icon_color] ?? 'from-[#0097D7] to-[#1F4789]';
                    @endphp
                    <div class="bg-gradient-to-br {{ $colorClass }} rounded-xl p-4 mr-5 shadow-lg">
                        @if($project->icon)
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $project->icon }}"></path>
                        </svg>
                        @else
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        @endif
                    </div>
                    <h3 class="text-2xl font-bold jci-primary-text">{{ $project->title }}</h3>
                </div>
                <p class="text-gray-700 mb-6 leading-relaxed text-lg">
                    {{ $project->description }}
                </p>
                @if($project->link)
                <a href="{{ $project->link }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-bold text-lg group">
                    {{ __('website.home.learn_more') }}
                    <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                @else
                <a href="{{ route('activities') }}" class="inline-flex items-center text-[#0097D7] hover:text-[#1F4789] font-bold text-lg group">
                    {{ __('website.home.learn_more') }}
                    <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                @endif
            </div>
            @empty
            <!-- Default Projects if none in database -->
            <div class="bg-white p-10 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                <div class="flex items-center mb-6">
                    <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl p-4 mr-5 shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold jci-primary-text">{{ __('website.home.default_project_title') }}</h3>
                </div>
                <p class="text-gray-700 mb-6 leading-relaxed text-lg">
                    {{ __('website.home.default_project_text') }}
                </p>
                <a href="{{ route('activities') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-bold text-lg group">
                    {{ __('website.home.learn_more') }}
                    <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
            <div class="bg-white p-10 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                <div class="flex items-center mb-6">
                    <div class="bg-gradient-to-br from-green-400 to-green-600 rounded-xl p-4 mr-5 shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold jci-primary-text">{{ __('website.home.community_projects') }}</h3>
                </div>
                <p class="text-gray-700 mb-6 leading-relaxed text-lg">
                    {{ __('website.home.community_projects_description') }}
                </p>
                <a href="{{ route('activities') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-bold text-lg group">
                    {{ __('website.home.view_our_projects') }}
                    <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Facebook Posts Section -->
@if(isset($facebookPosts) && is_array($facebookPosts) && count($facebookPosts) > 0)
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold jci-primary-text mb-4">{{ __('website.home.facebook_posts') }}</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                {{ __('website.home.facebook_posts_description') }}
            </p>
        </div>
        <div class="fb-container">
            @foreach($facebookPosts as $post)
            <div class="fb-post">
                @if(isset($post['full_picture']))
                <a href="https://www.facebook.com/{{ $post['id'] }}" target="_blank" rel="noopener noreferrer">
                    <img src="{{ $post['full_picture'] }}" alt="Post Image" />
                </a>
                @endif
                <div class="fb-post-content">
                    @if(isset($post['message']))
                    <p class="fb-message">
                        @php
                            $message = $post['message'];
                            if(strlen($message) > 200) {
                                $message = substr($message, 0, strrpos(substr($message, 0, 200), ' ')) . '...';
                            }
                        @endphp
                        {{ $message }}
                    </p>
                    @endif
                    @if(isset($post['created_time']))
                    <p class="fb-created-time">
                        @php
                            $date = \Carbon\Carbon::parse($post['created_time']);
                            if(app()->getLocale() == 'fr') {
                                $formattedDate = $date->locale('fr')->isoFormat('D MMMM YYYY');
                            } else {
                                $formattedDate = $date->locale('en')->isoFormat('MMMM D, YYYY');
                            }
                        @endphp
                        {{ $formattedDate }}
                    </p>
                    @endif
                    <a class="fb-link" href="https://www.facebook.com/{{ $post['id'] }}" target="_blank" rel="noopener noreferrer">
                        {{ __('website.home.view_on_facebook') }}
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Partners Section -->
@if($partners->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl md:text-4xl font-bold jci-primary-text text-center mb-12">{{ __('website.partners.title') }}</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-8 items-center">
            @foreach($partners as $partner)
            <div class="flex items-center justify-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition h-32">
                @if($partner->website)
                <a href="{{ $partner->website }}" target="_blank" rel="noopener noreferrer" class="w-full h-full flex items-center justify-center">
                    @if($partner->logo)
                    <img src="{{ Storage::url($partner->logo) }}" alt="{{ $partner->name }}" class="max-w-full max-h-full object-contain filter grayscale hover:grayscale-0 transition-all duration-300">
                    @else
                    <span class="text-gray-400 text-sm">{{ $partner->name }}</span>
                    @endif
                </a>
                @else
                <div class="w-full h-full flex items-center justify-center">
                    @if($partner->logo)
                    <img src="{{ Storage::url($partner->logo) }}" alt="{{ $partner->name }}" class="max-w-full max-h-full object-contain filter grayscale">
                    @else
                    <span class="text-gray-400 text-sm">{{ $partner->name }}</span>
                    @endif
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="py-20 jci-gradient text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-64 h-64 bg-white rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full translate-x-1/2 translate-y-1/2"></div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h2 class="text-4xl md:text-5xl font-bold mb-6 tracking-tight">{{ __('website.common.join_us') }}!</h2>
        <p class="text-xl md:text-2xl mb-10 text-blue-100 max-w-2xl mx-auto leading-relaxed">
            {{ __('website.common.ready_to_make_difference') }}
        </p>
        <a href="{{ route('contact') }}" class="bg-white text-[#130F2D] px-10 py-4 rounded-full font-extrabold text-lg hover:bg-blue-50 transition-all duration-300 inline-block shadow-2xl hover:shadow-white/20 transform hover:scale-105">
            {{ __('website.common.contact_us') }}
        </a>
    </div>
</section>
@endsection

