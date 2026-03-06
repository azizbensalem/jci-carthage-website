@extends('layouts.admin')

@section('title', 'Modifier le carrousel - JCI Carthage')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('admin.carousels.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
            ← Retour à la liste
        </a>
        <h1 class="text-3xl font-bold jci-primary-text">Modifier l'élément de carrousel</h1>
    </div>

    <div class="jci-card p-6">
        <form action="{{ route('admin.carousels.update', $carousel) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Titre *</label>
                <input type="text" name="title" id="title" value="{{ old('title', $carousel->title) }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="3"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $carousel->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Image</label>
                @if($carousel->image)
                <div class="mb-3">
                    <p class="text-sm text-gray-600 mb-2">Image actuelle:</p>
                    <img src="{{ Storage::url($carousel->image) }}" alt="Image actuelle" class="max-w-xs rounded-lg shadow-md mb-2">
                </div>
                @endif
                <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('image') border-red-500 @enderror">
                <p class="mt-1 text-sm text-gray-500">Formats acceptés: JPEG, PNG, JPG, GIF, WEBP (max 2MB). Laisser vide pour conserver l'image actuelle.</p>
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <div id="imagePreview" class="mt-4 hidden">
                    <p class="text-sm text-gray-600 mb-2">Nouvelle image:</p>
                    <img id="previewImg" src="" alt="Aperçu" class="max-w-xs rounded-lg shadow-md">
                </div>
            </div>

            <div class="mb-4">
                <label for="link" class="block text-sm font-medium text-gray-700 mb-2">Lien (URL)</label>
                <input type="url" name="link" id="link" value="{{ old('link', $carousel->link) }}" placeholder="https://example.com"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('link') border-red-500 @enderror">
                <p class="mt-1 text-sm text-gray-500">Laissez vide si vous ne souhaitez pas afficher de bouton</p>
                @error('link')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="button_text" class="block text-sm font-medium text-gray-700 mb-2">Texte du bouton</label>
                <input type="text" name="button_text" id="button_text" value="{{ old('button_text', $carousel->button_text) }}" placeholder="Découvrir plus"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('button_text') border-red-500 @enderror">
                <p class="mt-1 text-sm text-gray-500">Texte affiché sur le bouton. Laissez vide pour utiliser le texte par défaut</p>
                @error('button_text')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Ordre d'affichage</label>
                <input type="number" name="order" id="order" value="{{ old('order', $carousel->order) }}" min="0"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('order') border-red-500 @enderror">
                @error('order')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $carousel->is_active) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">Actif</span>
                </label>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.carousels.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
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
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            document.getElementById('imagePreview').classList.add('hidden');
        }
    });
</script>
@endsection

