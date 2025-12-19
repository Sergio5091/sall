<?php

namespace App\Http\Controllers\Promoter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Salle;
use App\Models\Event;
use App\Models\Notification;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord du promoteur.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Récupérer les salles du promoteur
        $salles = Salle::where('promoter_id', $user->id)->get();
        
        // Récupérer les événements du promoteur
        $events = Event::where('promoter_id', $user->id)
                      ->orderBy('date_debut', 'desc')
                      ->take(5)
                      ->get();
        
        // Calculer les statistiques
        $stats = [
            'total' => $events->count(),
            'publies' => $events->where('statut', 'publie')->count(),
            'brouillons' => $events->where('statut', 'brouillon')->count(),
            'avenir' => $events->where('date_debut', '>', now())->count(),
            'en_cours' => $events->where('date_debut', '<=', now())
                               ->where('date_fin', '>=', now())->count(),
            'passes' => $events->where('date_fin', '<', now())->count(),
        ];
        
        // Données pour le système multi-comptes
        if ($user->role === 'promoter') {
            $mainAccount = $user->isMainPromoter() ? $user : $user->getMainAccount();
            $allAccounts = $mainAccount->getAllPromoterAccounts();
            $activeAccount = $mainAccount->getActivePromoterAccount();
        } else {
            $mainAccount = null;
            $allAccounts = collect([]);
            $activeAccount = null;
        }
        
        // Récupérer les notifications réelles
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        $unreadCount = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();
        
        return Inertia::render('Promoter/Dashboard', [
            'salles' => $salles,
            'salle' => $salles->first(), // Pour la compatibilité avec l'affichage existant
            'stats' => $stats,
            'events' => $events,
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            // Données multi-comptes - toujours définies pour les promoteurs
            'mainAccount' => $user->role === 'promoter' ? ($user->isMainPromoter() ? $user : $user->getMainAccount()) : null,
            'allAccounts' => $user->role === 'promoter' ? ($user->isMainPromoter() ? $user->getAllPromoterAccounts() : collect([$user])) : collect([]),
            'activeAccount' => $user->role === 'promoter' ? ($user->isMainPromoter() ? $user->getActivePromoterAccount() : $user) : null,
            'activeAccountId' => $user->role === 'promoter' ? ($user->isMainPromoter() ? $user->getActivePromoterAccount()->id : $user->id) : null,
            'isMainAccount' => $user->role === 'promoter' ? ($user->isMainPromoter() ? true : false) : false,
            'auth' => [
                'user' => $user
            ]
        ]);
    }
}
