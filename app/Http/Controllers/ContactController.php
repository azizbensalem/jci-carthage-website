<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display the contact page
     */
    public function index()
    {
        return view('contact');
    }

    /**
     * Handle contact form submission
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Here you can add email sending logic
        // For now, we'll just return with a success message
        
        return redirect()->route('contact')->with('success', 'Merci pour votre message! Nous vous répondrons dans les plus brefs délais.');
    }
}
