@extends('layouts.admin')

@section('title', 'Modifier un partenaire - JCI Carthage')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.partners.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
            ← Retour à la liste
        </a>
        <h1 class="text-3xl font-bold jci-primary-text">Modifier le partenaire</h1>
    </div>

    <div class="jci-card p-6">
        <form action="{{ route('admin.partners.update', $partner) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom du partenaire *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $partner->name) }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                @if($partner->logo)
                <div class="mb-3">
                    <p class="text-sm text-gray-600 mb-2">Logo actuel:</p>
                    <img src="{{ Storage::url($partner->logo) }}" alt="Logo actuel" class="h-24 w-auto object-contain bg-gray-50 p-2 rounded-lg shadow-md mb-2">
                </div>
                @endif
                <input type="file" name="logo" id="logo" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/svg+xml"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('logo') border-red-500 @enderror">
                <p class="mt-1 text-sm text-gray-500">Formats acceptés: JPEG, PNG, JPG, GIF, WEBP, SVG (max 2MB). Laisser vide pour conserver le logo actuel.</p>
                @error('logo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <div id="logoPreview" class="mt-4 hidden">
                    <p class="text-sm text-gray-600 mb-2">Nouveau logo:</p>
                    <img id="previewImg" src="" alt="Aperçu" class="max-w-xs h-24 object-contain rounded-lg shadow-md bg-gray-50 p-2">
                </div>
            </div>

            <div class="mb-4">
                <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Site Web (URL)</label>
                <input type="url" name="website" id="website" value="{{ old('website', $partner->website) }}" placeholder="https://example.com"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('website') border-red-500 @enderror">
                @error('website')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Ordre d'affichage</label>
                <input type="number" name="order" id="order" value="{{ old('order', $partner->order) }}" min="0"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('order') border-red-500 @enderror">
                @error('order')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $partner->is_active) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">Actif (affiché sur la page d'accueil)</span>
                </label>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.partners.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Annuler
                </a>
                <button type="submit" class="jci-btn-primary">
                    Mettre à jour
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

