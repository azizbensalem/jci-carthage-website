<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\President;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PresidentController extends Controller
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
        $presidents = President::orderBy('order')
            ->orderBy('presidency_year', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.presidents.index', compact('presidents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.presidents.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'presidency_year' => 'required|string|max:50',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('presidents', 'public');
        }

        President::create($validated);

        return redirect()->route('admin.presidents.index')
            ->with('success', 'Président ajouté avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(President $president)
    {
        return view('admin.presidents.edit', compact('president'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, President $president)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'presidency_year' => 'required|string|max:50',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        if ($request->hasFile('photo')) {
            if ($president->photo && Storage::disk('public')->exists($president->photo)) {
                Storage::disk('public')->delete($president->photo);
            }

            $validated['photo'] = $request->file('photo')->store('presidents', 'public');
        } else {
            $validated['photo'] = $president->photo;
        }

        $president->update($validated);

        return redirect()->route('admin.presidents.index')
            ->with('success', 'Président mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(President $president)
    {
        if ($president->photo && Storage::disk('public')->exists($president->photo)) {
            Storage::disk('public')->delete($president->photo);
        }

        $president->delete();

        return redirect()->route('admin.presidents.index')
            ->with('success', 'Président supprimé avec succès.');
    }
}
