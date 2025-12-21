<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Show the notification form for promoters.
     */
    public function createPromoterNotification()
    {
        $promoters = User::where('role', 'promoter')->orderBy('name')->get();
        
        return Inertia::render('Admin/Notifications/PromoterNotification', [
            'promoters' => $promoters
        ]);
    }

    /**
     * Show the notification form for clients.
     */
    public function createClientNotification()
    {
        $clients = User::where('role', 'client')->orderBy('name')->get();
        
        return Inertia::render('Admin/Notifications/ClientNotification', [
            'clients' => $clients
        ]);
    }

    /**
     * Send notification to promoters.
     */
    public function sendPromoterNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'type' => 'required|in:info,warning,success,error',
            'send_to' => 'required|in:all,specific',
            'promoter_ids' => 'required_if:send_to,specific|array',
            'promoter_ids.*' => 'exists:users,id'
        ]);

        $promoters = [];
        
        if ($request->send_to === 'all') {
            $promoters = User::where('role', 'promoter')->get();
        } else {
            $promoters = User::whereIn('id', $request->promoter_ids)->where('role', 'promoter')->get();
        }

        foreach ($promoters as $promoter) {
            Notification::create([
                'user_id' => $promoter->id,
                'title' => $request->title,
                'message' => $request->message,
                'type' => $request->type,
                'is_read' => false
            ]);
        }

        return redirect()->route('admin.promoteurs')
            ->with('success', 'Notification envoyée avec succès à ' . count($promoters) . ' promoteur(s).');
    }

    /**
     * Send notification to clients.
     */
    public function sendClientNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'type' => 'required|in:info,warning,success,error',
            'send_to' => 'required|in:all,specific',
            'client_ids' => 'required_if:send_to,specific|array',
            'client_ids.*' => 'exists:users,id'
        ]);

        $clients = [];
        
        if ($request->send_to === 'all') {
            $clients = User::where('role', 'client')->get();
        } else {
            $clients = User::whereIn('id', $request->client_ids)->where('role', 'client')->get();
        }

        foreach ($clients as $client) {
            Notification::create([
                'user_id' => $client->id,
                'title' => $request->title,
                'message' => $request->message,
                'type' => $request->type,
                'is_read' => false
            ]);
        }

        return redirect()->route('admin.clients')
            ->with('success', 'Notification envoyée avec succès à ' . count($clients) . ' client(s).');
    }

    /**
     * Send notification to a specific promoter.
     */
    public function sendToPromoter(Request $request, User $user)
    {
        if ($user->role !== 'promoter') {
            abort(404);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'type' => 'required|in:info,warning,success,error'
        ]);

        Notification::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'is_read' => false
        ]);

        return back()->with('success', 'Notification envoyée avec succès au promoteur.');
    }

    /**
     * Send notification to a specific client.
     */
    public function sendToClient(Request $request, User $user)
    {
        if ($user->role !== 'client') {
            abort(404);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'type' => 'required|in:info,warning,success,error'
        ]);

        Notification::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'is_read' => false
        ]);

        return back()->with('success', 'Notification envoyée avec succès au client.');
    }
}
