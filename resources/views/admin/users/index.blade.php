@extends('layouts.admin')

@section('title', __('website.admin.users.title') . ' - JCI Carthage')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold jci-primary-text">{{ __('website.admin.users.title') }}</h1>
            <p class="mt-2 text-gray-600">{{ __('website.admin.users.description') }}</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="jci-btn-primary">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            {{ __('website.admin.users.add') }}
        </a>
    </div>

    <div class="jci-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="jci-primary">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('website.admin.users.name') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('website.admin.users.email') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('website.admin.users.role') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('website.admin.users.created') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-white uppercase tracking-wider">{{ __('website.admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($user->role === 'admin') bg-red-100 text-red-800
                                @elseif($user->role === 'vice-president') bg-yellow-100 text-yellow-800
                                @else bg-blue-100 text-blue-800
                                @endif">
                                {{ ucfirst(str_replace('-', ' ', $user->role)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.users.show', $user) }}" class="text-[#0097D7] hover:text-[#1F4789] mr-3">{{ __('website.admin.users.view') }}</a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">{{ __('website.admin.edit') }}</a>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('website.admin.users.confirm_delete') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">{{ __('website.admin.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            {!! __('website.admin.users.no_members', ['link' => route('admin.users.create')]) !!}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

