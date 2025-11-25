<?php

namespace App\Http\Controllers\Promoter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord du promoteur.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Ici, vous pouvez ajouter la logique pour récupérer les données du tableau de bord
        // Par exemple :
        // $salles = $user->salles()->count();
        // $evenements = $user->evenements()->count();
        // $reservations = $user->reservations()->count();
        
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
            'stats' => [
                'salles' => 0, // Remplacer par $salles
                'evenements' => 0, // Remplacer par $evenements
                'reservations' => 0, // Remplacer par $reservations
            ],
            'notifications' => $notifications
        ]);
    }
}
