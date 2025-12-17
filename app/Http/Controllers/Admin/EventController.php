<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::with(['salle', 'promoter'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Admin/Events', [
            'events' => $events
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load(['salle', 'promoter', 'reservations']);

        return Inertia::render('Admin/EventDetails', [
            'event' => $event
        ]);
    }
}
