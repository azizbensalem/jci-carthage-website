@extends('layouts.admin')

@section('title', 'Gestion des Partenaires - JCI Carthage')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold jci-primary-text">{{ __('website.admin.partners.title') }}</h1>
            <p class="mt-2 text-gray-600">{{ __('website.admin.partners.description') }}</p>
        </div>
        <a href="{{ route('admin.partners.create') }}" class="jci-btn-primary">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            {{ __('website.admin.partners.add') }}
        </a>
    </div>

    <div class="jci-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="jci-primary">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('website.admin.order') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('website.admin.partners.name') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('website.admin.partners.logo') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('website.admin.partners.website') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('website.admin.status') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-white uppercase tracking-wider">{{ __('website.admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($partners as $partner)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $partner->order }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $partner->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($partner->logo)
                            <img src="{{ Storage::url($partner->logo) }}" alt="{{ $partner->name }}" class="h-16 w-auto object-contain">
                            @else
                            <span class="text-sm text-gray-400">{{ __('website.admin.partners.no_logo') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($partner->website)
                            <a href="{{ $partner->website }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 truncate max-w-xs block">
                                {{ $partner->website }}
                            </a>
                            @else
                            <span class="text-sm text-gray-400">{{ __('website.admin.partners.no_website') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $partner->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $partner->is_active ? __('website.admin.active') : __('website.admin.inactive') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.partners.edit', $partner) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">{{ __('website.admin.edit') }}</a>
                            <form action="{{ route('admin.partners.destroy', $partner) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('website.admin.partners.confirm_delete') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">{{ __('website.admin.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            {!! __('website.admin.partners.no_partners', ['link' => route('admin.partners.create')]) !!}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

