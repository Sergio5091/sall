<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StandaloneEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class StandaloneEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = StandaloneEvent::with('creator')->latest()->get();
        
        return Inertia::render('Admin/StandaloneEvents/Index', [
            'events' => $events
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/StandaloneEvents/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'organizer_name' => 'required|string|max:255',
            'organizer_email' => 'nullable|email|max:255',
            'organizer_phone' => 'nullable|string|max:20',
            'location' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'event_date' => 'required|date|after:now',
            'price' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $validated;
        $data['created_by'] = auth()->id();

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('standalone_events', 'public');
            $data['image'] = $imagePath;
        }

        StandaloneEvent::create($data);

        return redirect()->route('admin.standalone-events.index')
            ->with('success', 'Événement ponctuel créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StandaloneEvent $standaloneEvent)
    {
        $standaloneEvent->load('creator');
        
        return Inertia::render('Admin/StandaloneEvents/Show', [
            'event' => $standaloneEvent
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StandaloneEvent $standaloneEvent)
    {
        $standaloneEvent->load('creator');
        
        return Inertia::render('Admin/StandaloneEvents/Edit', [
            'event' => $standaloneEvent
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StandaloneEvent $standaloneEvent)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'organizer_name' => 'required|string|max:255',
            'organizer_email' => 'nullable|email|max:255',
            'organizer_phone' => 'nullable|string|max:20',
            'location' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'event_date' => 'required|date|after:now',
            'price' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $validated;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($standaloneEvent->image) {
                Storage::disk('public')->delete($standaloneEvent->image);
            }
            
            $imagePath = $request->file('image')->store('standalone_events', 'public');
            $data['image'] = $imagePath;
        }

        $standaloneEvent->update($data);

        return redirect()->route('admin.standalone-events.index')
            ->with('success', 'Événement ponctuel mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StandaloneEvent $standaloneEvent)
    {
        // Delete image
        if ($standaloneEvent->image) {
            Storage::disk('public')->delete($standaloneEvent->image);
        }
        
        $standaloneEvent->delete();

        return redirect()->route('admin.standalone-events.index')
            ->with('success', 'Événement ponctuel supprimé avec succès.');
    }

    /**
     * Toggle event status
     */
    public function toggleStatus(StandaloneEvent $standaloneEvent)
    {
        $standaloneEvent->status = $standaloneEvent->status === 'active' ? 'inactive' : 'active';
        $standaloneEvent->save();

        return back()->with('success', 'Statut de l\'événement mis à jour avec succès.');
    }
}
