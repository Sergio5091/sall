<?php

namespace App\Http\Controllers\Promoter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Afficher la liste des notifications du promoteur.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc');
            
        // Filtrer par type si spécifié
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }
        
        // Filtrer par statut lu/non lu si spécifié
        if ($request->has('read') && $request->read !== null) {
            $query->where('is_read', $request->read === 'true');
        }
        
        $notifications = $query->paginate(20)->withQueryString();
        
        // Statistiques
        $stats = [
            'total' => Notification::where('user_id', $user->id)->count(),
            'unread' => Notification::where('user_id', $user->id)->where('is_read', false)->count(),
            'success' => Notification::where('user_id', $user->id)->where('type', 'success')->count(),
            'warning' => Notification::where('user_id', $user->id)->where('type', 'warning')->count(),
            'error' => Notification::where('user_id', $user->id)->where('type', 'error')->count(),
            'info' => Notification::where('user_id', $user->id)->where('type', 'info')->count(),
        ];
        
        return Inertia::render('Promoter/Notifications', [
            'notifications' => $notifications,
            'stats' => $stats,
            'filters' => $request->only(['type', 'read']),
            'unreadCount' => $stats['unread']
        ]);
    }
    
    /**
     * Marquer une notification comme lue.
     */
    public function markAsRead(Request $request, Notification $notification)
    {
        // Vérifier que la notification appartient à l'utilisateur
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }
        
        $notification->markAsRead();
        
        return back()->with('success', 'Notification marquée comme lue.');
    }
    
    /**
     * Marquer toutes les notifications comme lues.
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        
        Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Toutes les notifications ont été marquées comme lues.']);
        }
        
        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }
    
    /**
     * Supprimer une notification.
     */
    public function destroy(Notification $notification)
    {
        // Vérifier que la notification appartient à l'utilisateur
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }
        
        $notification->delete();
        
        return back()->with('success', 'Notification supprimée.');
    }
    
    /**
     * Vider toutes les notifications.
     */
    public function clearAll()
    {
        $user = Auth::user();
        
        Notification::where('user_id', $user->id)->delete();
        
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Toutes les notifications ont été supprimées.']);
        }
        
        return redirect()->route('promoter.notifications')
            ->with('success', 'Toutes les notifications ont été supprimées.');
    }
}
