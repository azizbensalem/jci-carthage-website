<?php

namespace App\Http\Controllers;

use App\Models\Carousel;
use App\Models\Event;
use App\Models\Partner;
use App\Models\President;
use App\Services\FacebookImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    protected $facebookImportService;

    public function __construct(FacebookImportService $facebookImportService)
    {
        $this->facebookImportService = $facebookImportService;
    }

    /**
     * Display the home page
     */
    public function index()
    {
        $carousels = Carousel::where('is_active', true)->orderBy('order')->get();
        // Get only projects (type='project') that are visible on website
        $projects = Event::projects()->where('is_featured', true)->orderBy('order')->limit(3)->get();
        $partners = Partner::active()->orderBy('order')->get();
        $facebookEvents = $this->fetchFacebookEvents();
        $aboutContent = trans('about');

        return view('home', compact('carousels', 'projects', 'partners', 'facebookEvents', 'aboutContent'));
    }

    /**
     * Fetch Facebook events from Graph API
     */
    private function fetchFacebookEvents()
    {
        return Cache::remember('facebook_events', 3600, function () {
            try {
                return $this->facebookImportService->fetchEvents(3);
            } catch (\Exception $e) {
                \Log::error('Facebook events API Exception: ' . $e->getMessage());
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
        $aboutContent = trans('about');

        return view('about', compact('aboutContent'));
    }

    /**
     * Display the presidents page.
     */
    public function presidents()
    {
        $aboutContent = trans('about');
        $presidents = $this->getChronologicalPresidents();

        return view('presidents', compact('aboutContent', 'presidents'));
    }

    /**
     * Get active presidents in chronological order.
     */
    private function getChronologicalPresidents()
    {
        return President::active()
            ->get()
            ->sortBy(function ($president) {
                return [
                    $this->extractPresidencyYear($president->presidency_year),
                    $president->order,
                    $president->id,
                ];
            })
            ->values();
    }

    /**
     * Extract the first year from labels such as "2026" or "2025/2026".
     */
    private function extractPresidencyYear($label)
    {
        if (preg_match('/\d{4}/', (string) $label, $matches)) {
            return (int) $matches[0];
        }

        return PHP_INT_MAX;
    }

    /**
     * Display the activities page
     */
    public function activities(Request $request)
    {
        $selectedType = $request->get('type', 'all');
        
        // Get all visible events
        $query = Event::visible()->orderedForActivities();
        
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
