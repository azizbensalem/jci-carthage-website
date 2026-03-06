@extends('layouts.admin')

@section('title', 'Google Account Settings - JCI Carthage')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold jci-primary-text">Google Account Settings</h1>
        <p class="mt-2 text-gray-600">Connect a Google account to enable email and calendar features</p>
    </div>

    <!-- Admin-only warning -->
    <div class="jci-card p-4 mb-6 bg-yellow-50 border-l-4 border-yellow-400">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    <strong>Admin access required</strong> - Only organization administrators can connect or change the Google account used for sending emails and adding calendar events.
                </p>
            </div>
        </div>
    </div>

    <!-- Configuration Info -->
    <div class="jci-card p-4 mb-6 bg-blue-50 border-l-4 border-blue-400">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-semibold text-blue-900 mb-2">Redirect URI Configuration</h3>
                <div class="text-sm text-blue-800 space-y-2">
                    <p><strong>Current Redirect URI:</strong></p>
                    <code class="block bg-blue-100 px-3 py-2 rounded text-xs break-all">{{ $redirectUri }}</code>
                    <p class="mt-2"><strong>⚠️ Important:</strong> Copy the URI above and add it EXACTLY (including http/https, port, and no trailing slash) to your Google Cloud Console:</p>
                    <ol class="list-decimal list-inside mt-2 space-y-1 text-xs">
                        <li>Go to <a href="https://console.cloud.google.com/apis/credentials" target="_blank" class="underline">Google Cloud Console → APIs & Services → Credentials</a></li>
                        <li>Click on your OAuth 2.0 Client ID</li>
                        <li>Under "Authorized redirect URIs", click "ADD URI"</li>
                        <li>Paste: <code class="bg-blue-200 px-1 rounded">{{ $redirectUri }}</code></li>
                        <li>Click "SAVE"</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="jci-card p-6">
        @if($settings->isGoogleConnected())
            <!-- Connected State -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-full p-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-xl font-semibold text-gray-900">Google Account Connected</h2>
                            <p class="text-sm text-gray-500">Your organization's Google account is linked</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Connected Email</p>
                            <p class="text-base text-gray-900 mt-1">{{ $settings->google_email }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Status</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-1">
                                Active
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <h3 class="text-sm font-semibold text-blue-900 mb-2">Enabled Features</h3>
                    <ul class="space-y-2 text-sm text-blue-800">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Send emails from the connected Gmail account (e.g., invitations, notifications)
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Create and manage events in Google Calendar (e.g., meetings, reminders)
                        </li>
                    </ul>
                </div>

                <form action="{{ route('admin.google-account.disconnect') }}" method="POST" onsubmit="return confirm('Are you sure you want to disconnect the Google account? This will disable email and calendar features.');">
                    @csrf
                    @method('POST')
                    <button type="submit" class="jci-btn-primary bg-red-600 hover:bg-red-700">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Disconnect Google Account
                    </button>
                </form>
            </div>
        @else
            <!-- Not Connected State -->
            <div class="text-center py-8">
                <div class="flex justify-center mb-4">
                    <div class="bg-gray-100 rounded-full p-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                
                <h2 class="text-xl font-semibold text-gray-900 mb-2">No Google Account Connected</h2>
                <p class="text-gray-600 mb-6 max-w-md mx-auto">
                    Link a Google account to enable email and calendar features across the platform. This allows the app to send emails from the connected Gmail account and create events in Google Calendar.
                </p>

                <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left max-w-md mx-auto">
                    <h3 class="text-sm font-semibold text-gray-900 mb-2">What you can do:</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Send emails from the connected Gmail account (e.g., invitations, notifications)
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Create and manage events in Google Calendar (e.g., meetings, reminders)
                        </li>
                    </ul>
                </div>

                <div class="space-y-4">
                    <a href="{{ route('admin.google-account.connect') }}" class="jci-btn-primary inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        Link Google Account
                    </a>

                    @if(str_contains(session('error') ?? '', 'access_denied') || str_contains(session('error') ?? '', '403'))
                    <!-- Access Denied Error - Test Users -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <h3 class="text-sm font-semibold text-yellow-800 mb-2">Add Yourself as a Test User</h3>
                                <p class="text-sm text-yellow-700 mb-3">
                                    Your application is in "Testing" mode. You need to add your Google account email as a test user to access it.
                                </p>
                                <ol class="list-decimal list-inside text-sm text-yellow-700 space-y-2">
                                    <li>Go to <a href="https://console.cloud.google.com/apis/credentials/consent" target="_blank" class="underline font-medium">Google Cloud Console → OAuth consent screen</a></li>
                                    <li>Scroll down to the <strong>"Test users"</strong> section</li>
                                    <li>Click <strong>"ADD USERS"</strong> button</li>
                                    <li>Enter your Google account email address (the one you're trying to connect with)</li>
                                    <li>Click <strong>"ADD"</strong></li>
                                    <li>Wait a few seconds, then try connecting again</li>
                                </ol>
                                <p class="text-xs text-yellow-600 mt-3">
                                    <strong>Note:</strong> You can add up to 100 test users. For production use, you'll need to submit your app for Google verification.
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Connection Failed</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p>{{ session('error') }}</p>
                                </div>
                                <div class="mt-3 text-sm text-red-600">
                                    <p class="font-medium">Troubleshooting tips:</p>
                                    <ul class="list-disc list-inside mt-1 space-y-1">
                                        @if(str_contains(session('error'), 'access_denied') || str_contains(session('error'), '403'))
                                        <li><strong>Access Denied (403):</strong> Your Google account is not in the test users list. See instructions below to add yourself as a test user.</li>
                                        <li><strong>Redirect URI Mismatch:</strong> The redirect URI must EXACTLY match what's in Google Cloud Console. Current URI: <code class="bg-red-100 px-1 rounded">{{ $redirectUri }}</code></li>
                                        @else
                                        <li><strong>Redirect URI Mismatch:</strong> The redirect URI must EXACTLY match what's in Google Cloud Console. Current URI: <code class="bg-red-100 px-1 rounded">{{ $redirectUri }}</code></li>
                                        @endif
                                        <li>Go to <a href="https://console.cloud.google.com/apis/credentials" target="_blank" class="underline">Google Cloud Console → Credentials</a> and add the URI above to "Authorized redirect URIs"</li>
                                        <li>Check that Gmail API and Google Calendar API are enabled in Google Cloud Console</li>
                                        <li>Ensure your OAuth consent screen is configured correctly</li>
                                        <li>After updating, wait a few seconds for changes to propagate, then try again</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <p class="text-xs text-gray-500 mt-4">
                    You can disconnect this account at any time.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection

