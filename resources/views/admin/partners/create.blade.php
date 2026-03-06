@extends('layouts.admin')

@section('title', __('website.admin.partners.create_title') . ' - JCI Carthage')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.partners.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
            ← {{ __('website.admin.back') }}
        </a>
        <h1 class="text-3xl font-bold jci-primary-text">{{ __('website.admin.partners.create_title') }}</h1>
    </div>

    <div class="jci-card p-6">
        <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('website.admin.partners.name_field') }}</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">{{ __('website.admin.partners.logo') }} *</label>
                <input type="file" name="logo" id="logo" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/svg+xml" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('logo') border-red-500 @enderror">
                <p class="mt-1 text-sm text-gray-500">{{ __('website.admin.partners.logo_hint') }}</p>
                @error('logo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <div id="logoPreview" class="mt-4 hidden">
                    <img id="previewImg" src="" alt="{{ __('website.admin.partners.preview') }}" class="max-w-xs h-24 object-contain rounded-lg shadow-md bg-gray-50 p-2">
                </div>
            </div>

            <div class="mb-4">
                <label for="website" class="block text-sm font-medium text-gray-700 mb-2">{{ __('website.admin.partners.website') }} (URL)</label>
                <input type="url" name="website" id="website" value="{{ old('website') }}" placeholder="https://example.com"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('website') border-red-500 @enderror">
                @error('website')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="order" class="block text-sm font-medium text-gray-700 mb-2">{{ __('website.admin.order') }} d'affichage</label>
                <input type="number" name="order" id="order" value="{{ old('order', 0) }}" min="0"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('order') border-red-500 @enderror">
                @error('order')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">{{ __('website.admin.partners.active_hint') }}</span>
                </label>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.partners.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    {{ __('website.admin.cancel') }}
                </a>
                <button type="submit" class="jci-btn-primary">
                    {{ __('website.admin.create') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('logo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('logoPreview').classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            document.getElementById('logoPreview').classList.add('hidden');
        }
    });
</script>
@endsection

