<?php

namespace App\Http\Controllers\Promoter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Salle;
use App\Models\Event;

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
        
        // Sample notifications data
        $notifications = [
            [
                'id' => 1,
                'type' => 'alert',
                'title' => 'Alerte Admin',
                'message' => 'Votre salle "Pixel Palace" a été désactivée pour informations incomplètes. Veuillez mettre à jour votre profil.',
                'created_at' => now()->subMinutes(15),
                'read' => false,
                'icon' => 'exclamation-circle',
                'color' => 'red'
            ],
            [
                'id' => 2,
                'type' => 'message',
                'title' => 'Nouveau message',
                'message' => 'Alice Martin vous a envoyé un message concernant "CyberZone Arena".',
                'created_at' => now()->subHours(2),
                'read' => false,
                'icon' => 'comment',
                'color' => 'blue'
            ],
            [
                'id' => 3,
                'type' => 'reservation',
                'title' => 'Réservation récente',
                'message' => 'Nouvelle réservation pour "Tournoi Super Smash" par Bob Johnson.',
                'created_at' => now()->subDay(),
                'read' => true,
                'icon' => 'ticket-alt',
                'color' => 'green'
            ],
            [
                'id' => 4,
                'type' => 'comment',
                'title' => 'Nouveau commentaire',
                'message' => 'Charlie Brown a commenté votre événement "Soirée Découverte VR".',
                'created_at' => now()->subDays(3),
                'read' => true,
                'icon' => 'comment-dots',
                'color' => 'yellow'
            ]
        ];
        
        return Inertia::render('Promoter/Dashboard', [
            'salles' => $salles,
            'salle' => $salles->first(), // Pour la compatibilité avec l'affichage existant
            'stats' => $stats,
            'events' => $events,
            'notifications' => $notifications
        ]);
    }
}
