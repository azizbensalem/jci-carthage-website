<?php

namespace App\Http\Controllers;

use App\Models\Carousel;
use App\Models\Event;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Display the home page
     */
    public function index()
    {
        $carousels = Carousel::where('is_active', true)->orderBy('order')->get();
        // Get only projects (type='project') that are visible on website
        $projects = Event::projects()->where('is_featured', true)->orderBy('order')->limit(3)->get();
        $partners = Partner::active()->orderBy('order')->get();
        $facebookPosts = $this->fetchFacebookPosts();
        
        return view('home', compact('carousels', 'projects', 'partners', 'facebookPosts'));
    }

    /**
     * Fetch Facebook posts from Graph API
     */
    private function fetchFacebookPosts()
    {
        // Cache for 1 hour to avoid rate limiting
        return Cache::remember('facebook_posts', 3600, function () {
            $pageAccessToken = "EAAR3ZA2VXuucBO1HdjhmcU0HZAt9tckZCqZAn2NfrbxsfvsGKCXfIpyTw1pberAQNPTFHgJZCgB0ATJi9COqccRZAddR1GNPh6c3HV1fMZBepYLqKsQhNAqdTfEJTSSch88RSdcRl5uqEfLkVuw9wUaHXr8vYPFDg4eZCpb2Vwo9rYZCW81pBMCwXzjquUCOqoriX";
            $pageId = "730406217027533";
            $graphUrl = "https://graph.facebook.com/v22.0/{$pageId}/posts?fields=id,message,created_time,full_picture&limit=3&access_token={$pageAccessToken}";

            try {
                // Disable SSL verification for development (Windows SSL certificate issue)
                $response = Http::timeout(10)
                    ->withOptions([
                        'verify' => false, // Disable SSL verification for development
                        'curl' => [
                            CURLOPT_SSL_VERIFYPEER => false,
                            CURLOPT_SSL_VERIFYHOST => false,
                        ]
                    ])
                    ->get($graphUrl);
                
                // Log the response for debugging
                if (!$response->successful()) {
                    \Log::error('Facebook API Error: ' . $response->status() . ' - ' . $response->body());
                }
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    // Log for debugging
                    \Log::info('Facebook API Response: ', ['data_count' => isset($data['data']) ? count($data['data']) : 0]);
                    
                    if (isset($data['data']) && count($data['data']) > 0) {
                        return array_slice($data['data'], 0, 3);
                    }
                    
                    // Check for error in response
                    if (isset($data['error'])) {
                        \Log::error('Facebook API Error: ' . json_encode($data['error']));
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Facebook API Exception: ' . $e->getMessage());
                \Log::error('Stack trace: ' . $e->getTraceAsString());
            }
            
            return [];
        });
    }


    /**
     * Display the about page
     */
    public function about()
    {
        return view('about');
    }

    /**
     * Display the activities page
     */
    public function activities(Request $request)
    {
        $selectedType = $request->get('type', 'all');
        
        // Get all visible events
        $query = Event::visible()->orderBy('order')->orderBy('created_at', 'desc');
        
        // Filter by type if specified
        if ($selectedType !== 'all') {
            $query->where('type', $selectedType);
        }
        
        $events = $query->paginate(9);
        
        // Get all event types for filter
        $eventTypes = Event::getTypes();
        
        // Get counts for each type
        $typeCounts = [];
        foreach ($eventTypes as $key => $label) {
            $typeCounts[$key] = Event::visible()->where('type', $key)->count();
        }
        $typeCounts['all'] = Event::visible()->count();
        
        return view('activities', compact('events', 'eventTypes', 'selectedType', 'typeCounts'));
    }
}
