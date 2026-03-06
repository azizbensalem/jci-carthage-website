@extends('layouts.admin')

@section('title', __('website.admin.carousels.title') . ' - JCI Carthage')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold jci-primary-text">{{ __('website.admin.carousels.title') }}</h1>
            <p class="mt-2 text-gray-600">{{ __('website.admin.carousels.description') }}</p>
        </div>
        <a href="{{ route('admin.carousels.create') }}" class="jci-btn-primary">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            {{ __('website.admin.carousels.add') }}
        </a>
    </div>

    <div class="jci-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#130F2D]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('website.admin.order') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('website.admin.carousels.title_field') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('website.admin.carousels.image') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('website.admin.carousels.button') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('website.admin.status') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-white uppercase tracking-wider">{{ __('website.admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($carousels as $carousel)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $carousel->order }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $carousel->title }}</div>
                            @if($carousel->description)
                            <div class="text-sm text-gray-500">{{ Str::limit($carousel->description, 50) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($carousel->image)
                            <img src="{{ Storage::url($carousel->image) }}" alt="{{ $carousel->title }}" class="h-12 w-20 object-cover rounded">
                            @else
                            <span class="text-sm text-gray-400">{{ __('website.admin.carousels.no_image') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($carousel->link)
                            <div class="text-sm text-gray-900">
                                <div class="font-medium">{{ $carousel->button_text ?: __('website.home.discover_more') }}</div>
                                <div class="text-xs text-gray-500 truncate max-w-xs">{{ $carousel->link }}</div>
                            </div>
                            @else
                            <span class="text-sm text-gray-400">{{ __('website.admin.carousels.no_button') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $carousel->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $carousel->is_active ? __('website.admin.active') : __('website.admin.inactive') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.carousels.edit', $carousel) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">{{ __('website.admin.edit') }}</a>
                            <form action="{{ route('admin.carousels.destroy', $carousel) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('website.admin.carousels.confirm_delete') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">{{ __('website.admin.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            {!! __('website.admin.carousels.no_items', ['link' => route('admin.carousels.create')]) !!}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

