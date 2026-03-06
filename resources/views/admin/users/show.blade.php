@extends('layouts.admin')

@section('title', __('admin.users.details') . ' - JCI Carthage')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-[#0097D7] hover:text-[#1F4789] mb-4 inline-block">
            {{ __('admin.users.back') }}
        </a>
        <h1 class="text-3xl font-bold jci-primary-text">{{ __('admin.users.details') }}</h1>
    </div>

    <div class="jci-card p-6">
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold jci-primary-text">{{ $user->name }}</h2>
                <span class="px-3 py-1 text-sm font-semibold rounded-full 
                    @if($user->role === 'admin') bg-red-100 text-red-800
                    @elseif($user->role === 'vice-president') bg-yellow-100 text-yellow-800
                    @else bg-blue-100 text-blue-800
                    @endif">
                    {{ ucfirst(str_replace('-', ' ', $user->role)) }}
                </span>
            </div>
        </div>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('admin.users.email') }}</label>
                <p class="text-gray-900">{{ $user->email }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('admin.users.role') }}</label>
                <p class="text-gray-900">{{ ucfirst(str_replace('-', ' ', $user->role)) }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('admin.users.since') }}</label>
                <p class="text-gray-900">{{ $user->created_at->format('F d, Y') }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('admin.users.last_updated') }}</label>
                <p class="text-gray-900">{{ $user->updated_at->format('F d, Y') }}</p>
            </div>
        </div>

        <div class="mt-8 flex flex-wrap justify-end gap-3 border-t pt-6">
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                {{ __('admin.cancel') }}
            </a>
            <a href="{{ route('admin.users.edit', $user) }}" class="jci-btn-primary">
                {{ __('admin.users.edit_member') }}
            </a>
            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('admin.users.confirm_delete') }}');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                    {{ __('admin.delete') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

