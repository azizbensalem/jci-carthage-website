<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Carousel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // KPI Statistics
        $stats = [
            'total_members' => User::count(),
            'active_members' => User::where('role', '!=', 'admin')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'vice_presidents' => User::where('role', 'vice-president')->count(),
            'regular_members' => User::where('role', 'member')->count(),
            'total_events' => Event::count(),
            'active_events' => Event::where('is_active', true)->count(),
            'featured_events' => Event::where('is_featured', true)->count(),
            'total_carousels' => Carousel::count(),
            'active_carousels' => Carousel::where('is_active', true)->count(),
        ];
        
        // Recent members (last 5)
        $recentMembers = User::orderBy('created_at', 'desc')->limit(5)->get();
        
        // Recent events (last 5)
        $recentEvents = Event::orderBy('created_at', 'desc')->limit(5)->get();
        
        return view('dashboard', compact('user', 'stats', 'recentMembers', 'recentEvents'));
    }
}
