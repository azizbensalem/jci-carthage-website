@extends('layouts.admin')

@section('title', __('blog.create_post') . ' - JCI Carthage')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold jci-primary-text">{{ __('blog.create_post') }}</h1>
    </div>

    <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <!-- Main Content Card -->
        <div class="jci-card p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('blog.content') }}</h2>
            
            <!-- Title -->
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">{{ __('blog.title') }} *</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#0097D7] focus:border-[#0097D7]"
                       placeholder="{{ __('blog.title_placeholder') }}">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Slug -->
            <div class="mb-4">
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">{{ __('blog.slug') }}</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#0097D7] focus:border-[#0097D7]"
                       placeholder="auto-generated">
                @error('slug')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">{{ __('Leave empty to auto-generate from title') }}</p>
            </div>

            <!-- Excerpt -->
            <div class="mb-4">
                <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">{{ __('blog.excerpt') }}</label>
                <textarea name="excerpt" id="excerpt" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#0097D7] focus:border-[#0097D7]"
                          placeholder="{{ __('blog.excerpt_placeholder') }}">{{ old('excerpt') }}</textarea>
                @error('excerpt')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">{{ __('blog.content') }} *</label>
                <textarea name="content" id="content" rows="15" required
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#0097D7] focus:border-[#0097D7]"
                          placeholder="{{ __('blog.content_placeholder') }}">{{ old('content') }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Featured Image -->
            <div class="mb-4">
                <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">{{ __('blog.featured_image') }}</label>
                <input type="file" name="featured_image" id="featured_image" accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#0097D7] focus:border-[#0097D7]">
                @error('featured_image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Video URL -->
            <div class="mb-4">
                <label for="video_url" class="block text-sm font-medium text-gray-700 mb-2">{{ __('blog.video_url') }}</label>
                <input type="url" name="video_url" id="video_url" value="{{ old('video_url') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#0097D7] focus:border-[#0097D7]"
                       placeholder="{{ __('blog.video_url_placeholder') }}">
                @error('video_url')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Meta Information Card -->
        <div class="jci-card p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('Meta Information') }}</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">{{ __('blog.category') }}</label>
                    <select name="category" id="category"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#0097D7] focus:border-[#0097D7]">
                        <option value="">{{ __('Select category') }}</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Published Date -->
                <div>
                    <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">{{ __('blog.published_date') }}</label>
                    <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#0097D7] focus:border-[#0097D7]">
                    @error('published_at')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Tags -->
            <div class="mt-4">
                <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">{{ __('blog.tags') }}</label>
                <input type="text" name="tags" id="tags" value="{{ old('tags') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#0097D7] focus:border-[#0097D7]"
                       placeholder="{{ __('blog.tags_placeholder') }}">
                @error('tags')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Checkboxes -->
            <div class="mt-4 flex items-center space-x-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}
                           class="h-4 w-4 text-[#0097D7] focus:ring-[#0097D7] border-gray-300 rounded">
                    <span class="ml-2 text-sm text-gray-700">{{ __('blog.published') }}</span>
                </label>
                
                <label class="flex items-center">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                           class="h-4 w-4 text-[#0097D7] focus:ring-[#0097D7] border-gray-300 rounded">
                    <span class="ml-2 text-sm text-gray-700">{{ __('blog.featured') }}</span>
                </label>
            </div>
        </div>

        <!-- SEO Card -->
        <div class="jci-card p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('blog.seo_settings') }}</h2>
            
            <!-- Meta Title -->
            <div class="mb-4">
                <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">{{ __('blog.meta_title') }}</label>
                <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#0097D7] focus:border-[#0097D7]"
                       placeholder="{{ __('blog.meta_title_placeholder') }}">
                @error('meta_title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Meta Description -->
            <div class="mb-4">
                <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">{{ __('blog.meta_description') }}</label>
                <textarea name="meta_description" id="meta_description" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#0097D7] focus:border-[#0097D7]"
                          placeholder="{{ __('blog.meta_description_placeholder') }}">{{ old('meta_description') }}</textarea>
                @error('meta_description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Meta Keywords -->
            <div class="mb-4">
                <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">{{ __('blog.meta_keywords') }}</label>
                <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#0097D7] focus:border-[#0097D7]"
                       placeholder="{{ __('blog.meta_keywords_placeholder') }}">
                @error('meta_keywords')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.blog.index') }}" class="text-gray-600 hover:text-gray-900">{{ __('blog.cancel') }}</a>
            <button type="submit" class="jci-btn-primary">{{ __('blog.save') }}</button>
        </div>
    </form>
</div>
@endsection
