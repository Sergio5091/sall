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
        
        // Base query pour les événements du promoteur
        $eventsQuery = Event::where('promoter_id', $user->id);
        
        // Récupérer seulement les 5 plus récents pour l'affichage
        $events = (clone $eventsQuery)
                      ->orderBy('date_debut', 'desc')
                      ->take(5)
                      ->get();
        
        // Calculer les statistiques sur l'ensemble des événements
        $allEvents = $eventsQuery->get();
        $stats = [
            'total' => $allEvents->count(),
            'publies' => $allEvents->where('statut', 'publie')->count(),
            'brouillons' => $allEvents->where('statut', 'brouillon')->count(),
            'annules' => $allEvents->where('statut', 'annule')->count(),
            'avenir' => $allEvents->where('date_debut', '>', now())->count(),
            'en_cours' => $allEvents->where('date_debut', '<=', now())
                               ->where('date_fin', '>=', now())->count(),
            'passes' => $allEvents->where('date_fin', '<', now())->count(),
            'total_salles' => $salles->count(),
        ];

        // Évolution de la création d'événements (7 derniers jours)
        $eventsEvolution = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = Event::where('promoter_id', $user->id)
                          ->whereDate('created_at', $date)
                          ->count();
            $eventsEvolution[] = [
                'date' => now()->subDays($i)->format('D'),
                'count' => $count,
            ];
        }

        // Répartition des statuts
        $totalEvents = $allEvents->count();
        $statusDistrib = [
            'publie' => $allEvents->where('statut', 'publie')->count(),
            'brouillon' => $allEvents->where('statut', 'brouillon')->count(),
            'annule' => $allEvents->where('statut', 'annule')->count(),
            'termine' => $allEvents->where('statut', 'termine')->count(),
        ];

        // Activité récente: événements créés / modifiés
        $recentActivity = [];
        $recentEvents = (clone $eventsQuery)
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
        foreach ($recentEvents as $evt) {
            $recentActivity[] = [
                'type' => 'event',
                'title' => 'Événement : ' . $evt->titre,
                'description' => 'Statut ' . $evt->statut,
                'time' => \Carbon\Carbon::parse($evt->updated_at)->diffForHumans(),
                'icon' => 'fa-calendar-alt',
                'color' => $evt->statut === 'publie' ? 'green' : 'blue',
            ];
        }
        
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
            'eventsEvolution' => $eventsEvolution,
            'statusDistrib' => $statusDistrib,
            'recentActivity' => $recentActivity,
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
