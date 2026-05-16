@extends('layouts.admin')

@section('title', __('website.admin.presidents.create_title') . ' - JCI Carthage')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.presidents.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
            ← {{ __('website.admin.back') }}
        </a>
        <h1 class="text-3xl font-bold jci-primary-text">{{ __('website.admin.presidents.create_title') }}</h1>
    </div>

    <div class="jci-card p-6">
        <form action="{{ route('admin.presidents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('website.admin.presidents.name_field') }}</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="presidency_year" class="block text-sm font-medium text-gray-700 mb-2">{{ __('website.admin.presidents.year_field') }}</label>
                <input type="text" name="presidency_year" id="presidency_year" value="{{ old('presidency_year') }}" required placeholder="2026"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('presidency_year') border-red-500 @enderror">
                @error('presidency_year')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">{{ __('website.admin.presidents.photo') }} *</label>
                <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('photo') border-red-500 @enderror">
                <p class="mt-1 text-sm text-gray-500">{{ __('website.admin.presidents.photo_hint') }}</p>
                @error('photo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <div id="photoPreview" class="mt-4 hidden">
                    <img id="previewImg" src="" alt="{{ __('website.admin.presidents.preview') }}" class="h-28 w-28 rounded-2xl object-cover shadow-md">
                </div>
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
                    <span class="ml-2 text-sm text-gray-700">{{ __('website.admin.presidents.active_hint') }}</span>
                </label>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.presidents.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
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
    document.getElementById('photo').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (event) {
                document.getElementById('previewImg').src = event.target.result;
                document.getElementById('photoPreview').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            document.getElementById('photoPreview').classList.add('hidden');
        }
    });
</script>
@endsection
