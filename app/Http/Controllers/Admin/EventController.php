<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
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
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::orderBy('order')->orderBy('created_at', 'desc')->get();
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = Event::getTypes();
        return view('admin.events.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:project,assembly,meeting,development,training-session,forum-session',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'icon' => 'nullable|string|max:255',
            'icon_color' => 'nullable|string|max:50',
            'link' => 'nullable|url|max:255',
            'order' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'show_on_website' => 'boolean',
        ]);

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');
        $validated['show_on_website'] = $request->has('show_on_website');

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events', 'public');
            $validated['image'] = $imagePath;
        }

        Event::create($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Événement créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return view('admin.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $types = Event::getTypes();
        return view('admin.events.edit', compact('event', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'type' => 'required|in:project,assembly,meeting,development,training-session,forum-session',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'icon' => 'nullable|string|max:255',
            'icon_color' => 'nullable|string|max:50',
            'link' => 'nullable|url|max:255',
            'order' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'show_on_website' => 'boolean',
        ]);

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');
        $validated['show_on_website'] = $request->has('show_on_website');

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($event->image && Storage::disk('public')->exists($event->image)) {
                Storage::disk('public')->delete($event->image);
            }
            $imagePath = $request->file('image')->store('events', 'public');
            $validated['image'] = $imagePath;
        }

        $event->update($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Événement mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        // Supprimer l'image si elle existe
        if ($event->image && Storage::disk('public')->exists($event->image)) {
            Storage::disk('public')->delete($event->image);
        }
        
        $event->delete();
        return redirect()->route('admin.events.index')
            ->with('success', 'Événement supprimé avec succès.');
    }
}

