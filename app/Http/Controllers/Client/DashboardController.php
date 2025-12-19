<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Salle;
use App\Models\Event;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Afficher le dashboard du client
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user->referral_code) {
            $user->referral_code = User::generateReferralCodeIfExists();
            $user->save();
        }

        $referralLink = url('/register?ref=' . $user->referral_code);

        $directReferralsCount = User::query()
            ->where('parent_id', $user->id)
            ->count();

        $communitySize = 0;
        $currentIds = [$user->id];
        $level = 1;
        while (true) {
            $ids = User::query()
                ->whereIn('parent_id', $currentIds)
                ->pluck('id');

            if ($ids->isEmpty()) {
                break;
            }

            $communitySize += $ids->count();
            $currentIds = $ids->all();
            $level++;

            if ($level > 50) {
                break;
            }
        }
        
        // Statistiques du client
        $stats = [
            'next_reservations' => $user->reservations()
                ->where('date_heure', '>=', now())
                ->count(),
            'invitations_pending' => $directReferralsCount,
            'credits' => 150, // À implémenter plus tard
            'direct_referrals' => $directReferralsCount,
            'community_size' => $communitySize,
        ];

        // Activité récente (réservations)
        $activity = $user->reservations()
            ->with('salle:id,nom,ville')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($reservation) {
                return [
                    'type' => 'reservation',
                    'title' => 'Réservation ' . $reservation->salle->nom,
                    'subtitle' => 'Le ' . $reservation->date_heure->format('d/m/Y à H:i'),
                    'location' => $reservation->salle->ville,
                    'image' => $reservation->salle->image_url ?? null,
                ];
            });

        // Nouvelles salles disponibles
        $newVenues = Salle::where('statut', 'actif')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get(['id', 'nom', 'description', 'ville', 'image_url'])
            ->map(function ($salle) {
                return [
                    'title' => $salle->nom,
                    'subtitle' => $salle->description,
                    'location' => $salle->ville,
                    'image' => $salle->image_url,
                ];
            });

        // Événements à venir
        $upcoming = Event::with('salle:id,nom,ville')
            ->where('statut', 'publie')
            ->where('date_debut', '>=', now())
            ->orderBy('date_debut', 'asc')
            ->take(5)
            ->get()
            ->map(function ($event) {
                return [
                    'dayShort' => strtoupper($event->date_debut->locale('fr')->format('M')),
                    'dayNum' => $event->date_debut->format('d'),
                    'title' => $event->titre,
                    'time' => $event->date_debut->format('H:i') . ' - ' . $event->date_fin->format('H:i'),
                    'location' => $event->salle->nom,
                    'avatars' => rand(2, 8), // Simulé
                ];
            });

        // Salles à proximité (simulées)
        $nearby = Salle::where('statut', 'actif')
            ->inRandomOrder()
            ->take(3)
            ->get(['id', 'nom', 'ville', 'prix_heure'])
            ->map(function ($salle) {
                return [
                    'title' => $salle->nom,
                    'location' => $salle->ville,
                    'distance' => rand(1, 10) . '.' . rand(0, 9) . ' km',
                    'price' => $salle->prix_heure,
                ];
            });

        // Notifications (simulées)
        $notifications = [
            [
                'id' => 1,
                'title' => 'Nouvel événement disponible',
                'message' => 'Tournoi Super Smash Bros ce week-end',
                'time' => 'Il y a 2 heures',
                'read' => false,
            ],
            [
                'id' => 2,
                'title' => 'Votre réservation est confirmée',
                'message' => 'Salle Pixel Play - Demain 20h00',
                'time' => 'Il y a 1 jour',
                'read' => true,
            ],
        ];

        return inertia('Client/Dashboard', [
            'user' => $user,
            'stats' => $stats,
            'activity' => $activity,
            'newVenues' => $newVenues,
            'upcoming' => $upcoming,
            'nearby' => $nearby,
            'notifications' => $notifications,
            'referral' => $referralLink
        ]);
    }
}
