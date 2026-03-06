<div>
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Google Account</h2>
    <p class="text-gray-600 mb-6">Connect a Google account to enable email and calendar features</p>

    <!-- Configuration Info -->
    <!-- <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
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
                </div>
            </div>
        </div>
    </div> -->

    @if($settings->isGoogleConnected())
        <!-- Connected State -->
        <div class="mb-6">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0 bg-green-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-xl font-semibold text-gray-900">Google Account Connected</h3>
                    <p class="text-sm text-gray-500">Your organization's Google account is linked</p>
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
                        Send emails from the connected Gmail account
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Create and manage events in Google Calendar
                    </li>
                </ul>
            </div>

            <form action="{{ route('admin.google-account.disconnect') }}" method="POST" onsubmit="return confirm('Are you sure you want to disconnect the Google account?');">
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
            
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Google Account Connected</h3>
            <p class="text-gray-600 mb-6 max-w-md mx-auto">
                Link a Google account to enable email and calendar features across the platform.
            </p>

            <a href="{{ route('admin.google-account.connect') }}" class="jci-btn-primary inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Link Google Account
            </a>
        </div>
    @endif
</div>

