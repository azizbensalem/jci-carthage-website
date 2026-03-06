<?php

namespace App\Services;

use App\Models\OrganizationSettings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleService
{
    protected $settings;

    public function __construct()
    {
        $this->settings = OrganizationSettings::getSettings();
    }

    /**
     * Refresh the access token if needed
     */
    protected function refreshTokenIfNeeded()
    {
        if (!$this->settings->google_refresh_token) {
            return false;
        }

        // Check if token is expired or will expire in the next 5 minutes
        if ($this->settings->google_token_expires_at && $this->settings->google_token_expires_at->isFuture()) {
            return true; // Token is still valid
        }

        $httpClient = Http::asForm();
        
        // Disable SSL verification in local environment (Windows cURL issue)
        if (config('app.env') === 'local' || config('app.debug')) {
            $httpClient = $httpClient->withoutVerifying();
        }
        
        $response = $httpClient->post('https://oauth2.googleapis.com/token', [
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'refresh_token' => $this->settings->google_refresh_token,
            'grant_type' => 'refresh_token',
        ]);

        if ($response->successful()) {
            $tokenData = $response->json();
            $this->settings->google_access_token = $tokenData['access_token'];
            $this->settings->google_token_expires_at = now()->addSeconds($tokenData['expires_in'] ?? 3600);
            $this->settings->save();
            return true;
        }

        Log::error('Failed to refresh Google access token', ['response' => $response->body()]);
        return false;
    }

    /**
     * Get a valid access token
     */
    protected function getAccessToken()
    {
        if (!$this->settings->isGoogleConnected()) {
            throw new \Exception('Google account is not connected.');
        }

        $this->refreshTokenIfNeeded();
        return $this->settings->google_access_token;
    }

    /**
     * Send an email via Gmail API
     */
    public function sendEmail($to, $subject, $body, $isHtml = true)
    {
        try {
            $accessToken = $this->getAccessToken();
            $fromEmail = $this->settings->google_email;

            // Create email message
            $boundary = uniqid(rand(), true);
            $rawMessage = "To: {$to}\r\n";
            $rawMessage .= "From: {$fromEmail}\r\n";
            $rawMessage .= "Subject: {$subject}\r\n";
            $rawMessage .= "MIME-Version: 1.0\r\n";
            $rawMessage .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n";
            $rawMessage .= "\r\n";
            $rawMessage .= "--{$boundary}\r\n";
            
            if ($isHtml) {
                $rawMessage .= "Content-Type: text/html; charset=UTF-8\r\n";
            } else {
                $rawMessage .= "Content-Type: text/plain; charset=UTF-8\r\n";
            }
            $rawMessage .= "\r\n";
            $rawMessage .= $body . "\r\n";
            $rawMessage .= "--{$boundary}--\r\n";

            $encodedMessage = base64_encode($rawMessage);
            $encodedMessage = str_replace(['+', '/', '='], ['-', '_', ''], $encodedMessage); // URL-safe base64

            $httpClient = Http::withToken($accessToken);
            
            // Disable SSL verification in local environment (Windows cURL issue)
            if (config('app.env') === 'local' || config('app.debug')) {
                $httpClient = $httpClient->withoutVerifying();
            }
            
            $response = $httpClient->post('https://gmail.googleapis.com/gmail/v1/users/me/messages/send', [
                'raw' => $encodedMessage,
            ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('Failed to send email via Gmail API', [
                'response' => $response->body(),
                'to' => $to,
                'subject' => $subject,
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Exception while sending email via Gmail', [
                'message' => $e->getMessage(),
                'to' => $to,
                'subject' => $subject,
            ]);
            return false;
        }
    }

    /**
     * Create a calendar event
     */
    public function createCalendarEvent($summary, $description, $startDateTime, $endDateTime, $attendees = [])
    {
        try {
            $accessToken = $this->getAccessToken();

            $event = [
                'summary' => $summary,
                'description' => $description,
                'start' => [
                    'dateTime' => $startDateTime->toRfc3339String(),
                    'timeZone' => config('app.timezone', 'UTC'),
                ],
                'end' => [
                    'dateTime' => $endDateTime->toRfc3339String(),
                    'timeZone' => config('app.timezone', 'UTC'),
                ],
            ];

            if (!empty($attendees)) {
                $event['attendees'] = array_map(function ($email) {
                    return ['email' => $email];
                }, $attendees);
            }

            $httpClient = Http::withToken($accessToken);
            
            // Disable SSL verification in local environment (Windows cURL issue)
            if (config('app.env') === 'local' || config('app.debug')) {
                $httpClient = $httpClient->withoutVerifying();
            }
            
            $response = $httpClient->post('https://www.googleapis.com/calendar/v3/calendars/primary/events', $event);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Failed to create calendar event', [
                'response' => $response->body(),
                'summary' => $summary,
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Exception while creating calendar event', [
                'message' => $e->getMessage(),
                'summary' => $summary,
            ]);
            return false;
        }
    }

    /**
     * Check if Google account is connected
     */
    public function isConnected()
    {
        return $this->settings->isGoogleConnected();
    }
}

