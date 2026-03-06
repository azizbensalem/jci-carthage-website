<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Carousel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarouselController extends Controller
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
        $carousels = Carousel::orderBy('order')->orderBy('created_at', 'desc')->get();
        return view('admin.carousels.index', compact('carousels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.carousels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'link' => 'nullable|url|max:255',
            'button_text' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('carousels', 'public');
            $validated['image'] = $imagePath;
        }

        Carousel::create($validated);

        return redirect()->route('admin.carousels.index')
            ->with('success', 'Élément du carrousel créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Carousel $carousel)
    {
        return view('admin.carousels.show', compact('carousel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Carousel $carousel)
    {
        return view('admin.carousels.edit', compact('carousel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Carousel $carousel)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'link' => 'nullable|url|max:255',
            'button_text' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($carousel->image && Storage::disk('public')->exists($carousel->image)) {
                Storage::disk('public')->delete($carousel->image);
            }
            $imagePath = $request->file('image')->store('carousels', 'public');
            $validated['image'] = $imagePath;
        }

        $carousel->update($validated);

        return redirect()->route('admin.carousels.index')
            ->with('success', 'Élément du carrousel mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Carousel $carousel)
    {
        // Supprimer l'image si elle existe
        if ($carousel->image && Storage::disk('public')->exists($carousel->image)) {
            Storage::disk('public')->delete($carousel->image);
        }
        
        $carousel->delete();
        return redirect()->route('admin.carousels.index')
            ->with('success', 'Élément du carrousel supprimé avec succès.');
    }
}
