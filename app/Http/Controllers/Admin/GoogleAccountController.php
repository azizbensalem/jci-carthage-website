<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizationSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class GoogleAccountController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Show the Google account settings page
     */
    public function index()
    {
        $settings = OrganizationSettings::getSettings();
        
        // Generate the exact redirect URI that should be used
        $redirectUri = config('services.google.redirect');
        if (empty($redirectUri)) {
            // Fallback to current request URL if not configured
            $redirectUri = route('admin.google-account.callback', [], false);
            $redirectUri = url($redirectUri);
        }
        
        return view('admin.google-account.index', compact('settings', 'redirectUri'));
    }

    /**
     * Redirect to Google OAuth consent screen
     */
    public function connect()
    {
        $clientId = config('services.google.client_id');
        $redirectUri = config('services.google.redirect');
        
        if (empty($clientId) || empty($redirectUri)) {
            return redirect()->route('admin.google-account.index')
                ->with('error', 'Google OAuth credentials are not configured. Please set GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, and GOOGLE_REDIRECT_URI in your .env file.');
        }

        // Store redirect URI in session to verify it matches on callback
        session([
            'google_oauth_state' => Str::random(40),
            'google_oauth_redirect_uri' => $redirectUri,
        ]);

        $scopes = [
            'https://www.googleapis.com/auth/gmail.send',
            'https://www.googleapis.com/auth/calendar',
            'https://www.googleapis.com/auth/userinfo.email',
        ];

        $params = [
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => implode(' ', $scopes),
            'access_type' => 'offline',
            'prompt' => 'consent',
            'state' => session('google_oauth_state'),
        ];

        $authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);

        \Log::info('Initiating Google OAuth', [
            'redirect_uri' => $redirectUri,
            'client_id' => $clientId,
        ]);

        return redirect($authUrl);
    }

    /**
     * Handle Google OAuth callback
     */
    public function callback(Request $request)
    {
        // Check for error from Google
        if ($request->has('error')) {
            $error = $request->get('error');
            $errorDescription = $request->get('error_description', 'Unknown error');
            
            \Log::error('Google OAuth error', [
                'error' => $error,
                'description' => $errorDescription,
            ]);
            
            return redirect()->route('admin.google-account.index')
                ->with('error', 'Authorization failed: ' . $errorDescription);
        }

        // Verify state parameter
        $state = $request->get('state');
        $sessionState = session('google_oauth_state');
        
        if (!$state || $state !== $sessionState) {
            \Log::error('Google OAuth state mismatch', [
                'request_state' => $state,
                'session_state' => $sessionState,
            ]);
            
            return redirect()->route('admin.google-account.index')
                ->with('error', 'Invalid state parameter. Please try again.');
        }

        $code = $request->get('code');
        if (!$code) {
            return redirect()->route('admin.google-account.index')
                ->with('error', 'Authorization code not received. Please try again.');
        }

        // Exchange authorization code for tokens
        $redirectUri = config('services.google.redirect');
        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');

        if (empty($redirectUri) || empty($clientId) || empty($clientSecret)) {
            \Log::error('Google OAuth credentials missing');
            return redirect()->route('admin.google-account.index')
                ->with('error', 'Google OAuth credentials are not configured properly.');
        }

        // Verify redirect URI matches (Google requires exact match)
        $expectedRedirectUri = session('google_oauth_redirect_uri', $redirectUri);
        if ($expectedRedirectUri !== $redirectUri) {
            \Log::warning('Redirect URI mismatch', [
                'expected' => $expectedRedirectUri,
                'config' => $redirectUri,
            ]);
        }

        \Log::info('Exchanging Google OAuth code for token', [
            'redirect_uri' => $redirectUri,
            'has_code' => !empty($code),
        ]);

        // Configure HTTP client with SSL verification disabled for local development
        $httpClient = Http::asForm();
        
        // Disable SSL verification in local environment (Windows cURL issue)
        if (config('app.env') === 'local' || config('app.debug')) {
            $httpClient = $httpClient->withoutVerifying();
        }

        $response = $httpClient->post('https://oauth2.googleapis.com/token', [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $redirectUri,
        ]);

        if (!$response->successful()) {
            $errorBody = $response->body();
            \Log::error('Failed to exchange Google OAuth code for token', [
                'status' => $response->status(),
                'response' => $errorBody,
                'redirect_uri' => $redirectUri,
            ]);
            
            $errorMessage = 'Failed to obtain access token. ';
            $errorData = $response->json();
            if (isset($errorData['error_description'])) {
                $errorMessage .= $errorData['error_description'];
            } else {
                $errorMessage .= 'Please check your Google OAuth configuration and try again.';
            }
            
            return redirect()->route('admin.google-account.index')
                ->with('error', $errorMessage);
        }

        $tokenData = $response->json();
        
        if (!isset($tokenData['access_token'])) {
            \Log::error('Google OAuth token response missing access_token', ['response' => $tokenData]);
            return redirect()->route('admin.google-account.index')
                ->with('error', 'Invalid token response from Google. Please try again.');
        }
        
        $accessToken = $tokenData['access_token'];
        $refreshToken = $tokenData['refresh_token'] ?? null;
        $expiresIn = $tokenData['expires_in'] ?? 3600;

        // Get user email
        $userHttpClient = Http::withToken($accessToken);
        
        // Disable SSL verification in local environment (Windows cURL issue)
        if (config('app.env') === 'local' || config('app.debug')) {
            $userHttpClient = $userHttpClient->withoutVerifying();
        }
        
        $userResponse = $userHttpClient->get('https://www.googleapis.com/oauth2/v2/userinfo');
        
        if (!$userResponse->successful()) {
            \Log::error('Failed to get Google user info', [
                'status' => $userResponse->status(),
                'response' => $userResponse->body(),
            ]);
            
            return redirect()->route('admin.google-account.index')
                ->with('error', 'Failed to retrieve user information. Please try again.');
        }

        $userData = $userResponse->json();
        $email = $userData['email'] ?? null;

        if (!$email) {
            \Log::error('Google user info missing email', ['user_data' => $userData]);
            return redirect()->route('admin.google-account.index')
                ->with('error', 'Failed to retrieve email address. Please try again.');
        }

        // Save settings
        $settings = OrganizationSettings::getSettings();
        $settings->google_email = $email;
        $settings->google_access_token = $accessToken;
        $settings->google_refresh_token = $refreshToken;
        $settings->google_token_expires_at = now()->addSeconds($expiresIn);
        $settings->google_connected = true;
        $settings->save();

        session()->forget('google_oauth_state');

        return redirect()->route('admin.google-account.index')
            ->with('success', 'Google account connected successfully!');
    }

    /**
     * Disconnect Google account
     */
    public function disconnect()
    {
        $settings = OrganizationSettings::getSettings();
        
        // Optionally revoke the token
        if ($settings->google_access_token) {
            try {
                $revokeClient = Http::asForm();
                
                // Disable SSL verification in local environment (Windows cURL issue)
                if (config('app.env') === 'local' || config('app.debug')) {
                    $revokeClient = $revokeClient->withoutVerifying();
                }
                
                $revokeClient->post('https://oauth2.googleapis.com/revoke', [
                    'token' => $settings->google_access_token,
                ]);
            } catch (\Exception $e) {
                // Continue with disconnection even if revocation fails
            }
        }

        $settings->google_email = null;
        $settings->google_access_token = null;
        $settings->google_refresh_token = null;
        $settings->google_token_expires_at = null;
        $settings->google_connected = false;
        $settings->save();

        return redirect()->route('admin.google-account.index')
            ->with('success', 'Google account disconnected successfully.');
    }

    /**
     * Refresh the access token if needed
     */
    protected function refreshTokenIfNeeded(OrganizationSettings $settings)
    {
        if (!$settings->google_refresh_token) {
            return false;
        }

        // Check if token is expired or will expire in the next 5 minutes
        if ($settings->google_token_expires_at && $settings->google_token_expires_at->isFuture()) {
            return true; // Token is still valid
        }

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'refresh_token' => $settings->google_refresh_token,
            'grant_type' => 'refresh_token',
        ]);

        if ($response->successful()) {
            $tokenData = $response->json();
            $settings->google_access_token = $tokenData['access_token'];
            $settings->google_token_expires_at = now()->addSeconds($tokenData['expires_in'] ?? 3600);
            $settings->save();
            return true;
        }

        return false;
    }
}
