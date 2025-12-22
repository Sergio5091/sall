<?php

namespace App\Http\Controllers\Promoter;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Salle;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promoter = Auth::user();
        $salles = Salle::where('promoter_id', $promoter->id)->pluck('id');
        
        $messages = Message::with(['salle', 'sender'])
            ->whereIn('salle_id', $salles)
            ->latest()
            ->get();

        return Inertia::render('Promoter/Messages/Index', [
            'messages' => $messages
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $promoter = Auth::user();
        $salles = Salle::where('promoter_id', $promoter->id)->get();
        
        return Inertia::render('Promoter/Messages/Create', [
            'salles' => $salles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'salle_id' => 'required|exists:salles,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:general,event,promotion',
        ]);

        // Check if the salle belongs to the authenticated promoter
        $salle = Salle::findOrFail($validated['salle_id']);
        if ($salle->promoter_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $message = Message::create([
            'sender_id' => Auth::id(),
            'salle_id' => $validated['salle_id'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'type' => $validated['type'],
            'status' => 'draft',
        ]);

        return redirect()->route('promoter.messages.index')
            ->with('success', 'Message créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        // Check if the message belongs to the authenticated promoter
        if ($message->sender_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $message->load(['salle', 'sender']);

        // Get subscribers count
        $subscribersCount = Subscription::where('salle_id', $message->salle_id)
            ->where('status', 'active')
            ->count();

        return Inertia::render('Promoter/Messages/Show', [
            'message' => $message,
            'subscribersCount' => $subscribersCount
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        // Check if the message belongs to the authenticated promoter
        if ($message->sender_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow editing draft messages
        if ($message->status === 'sent') {
            return redirect()->route('promoter.messages.show', $message)
                ->with('error', 'Les messages déjà envoyés ne peuvent pas être modifiés.');
        }

        $promoter = Auth::user();
        $salles = Salle::where('promoter_id', $promoter->id)->get();

        $message->load(['salle', 'sender']);

        return Inertia::render('Promoter/Messages/Edit', [
            'message' => $message,
            'salles' => $salles
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        // Check if the message belongs to the authenticated promoter
        if ($message->sender_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow updating draft messages
        if ($message->status === 'sent') {
            return redirect()->route('promoter.messages.show', $message)
                ->with('error', 'Les messages déjà envoyés ne peuvent pas être modifiés.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:general,event,promotion',
        ]);

        $message->update($validated);

        return redirect()->route('promoter.messages.index')
            ->with('success', 'Message mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        // Check if the message belongs to the authenticated promoter
        if ($message->sender_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow deleting draft messages
        if ($message->status === 'sent') {
            return back()->with('error', 'Les messages déjà envoyés ne peuvent pas être supprimés.');
        }

        $message->delete();

        return redirect()->route('promoter.messages.index')
            ->with('success', 'Message supprimé avec succès.');
    }

    /**
     * Send message to subscribers
     */
    public function send(Message $message)
    {
        // Check if the message belongs to the authenticated promoter
        if ($message->sender_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow sending draft messages
        if ($message->status === 'sent') {
            return back()->with('error', 'Ce message a déjà été envoyé.');
        }

        // Get subscribers
        $subscribers = Subscription::where('salle_id', $message->salle_id)
            ->where('status', 'active')
            ->with('user')
            ->get();

        // Send message to all subscribers
        foreach ($subscribers as $subscription) {
            // Here you would implement the actual sending logic
            // For example: email, notification, etc.
            // You can use Laravel's notification system or email
            
            // For now, we'll just mark as sent
        }

        // Mark message as sent
        $message->markAsSent();

        return back()->with('success', 'Message envoyé à ' . $subscribers->count() . ' abonné(s).');
    }

    /**
     * Get subscribers count for a specific salle
     */
    public function getSubscribersCount(Request $request)
    {
        $validated = $request->validate([
            'salle_id' => 'required|exists:salles,id',
        ]);

        // Check if the salle belongs to the authenticated promoter
        $salle = Salle::findOrFail($validated['salle_id']);
        if ($salle->promoter_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $count = Subscription::where('salle_id', $validated['salle_id'])
            ->where('status', 'active')
            ->count();

        return response()->json(['count' => $count]);
    }
}
