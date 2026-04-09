<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Salle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Afficher la liste des réservations du client
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = $user->reservations()
            ->with(['salle' => function($query) {
                $query->select('id', 'nom', 'ville', 'pays', 'image_url');
            }])
            ->orderBy('date_heure', 'desc');

        // Filtrage par statut
        if ($request->has('statut') && $request->statut) {
            $query->where('statut', $request->statut);
        }

        // Filtrage par date
        if ($request->has('date') && $request->date) {
            $query->whereDate('date_heure', $request->date);
        }

        $reservations = $query->paginate(10);

        $reservations->getCollection()->transform(function ($reservation) {
            return [
                'id' => $reservation->id,
                'venue_name' => $reservation->salle->nom,
                'salle_id' => $reservation->salle->id,
                'date_heure' => $reservation->date_heure?->toISOString(),
                'duree' => $reservation->duree,
                'nombre_personnes' => $reservation->nombre_personnes,
                'montant_total' => $reservation->prix_total,
                'statut' => $reservation->statut,
                'message' => $reservation->message,
                'created_at' => $reservation->created_at?->toISOString(),
                'type_evenement' => $reservation->type_evenement ?? null,
                'besoins_speciaux' => $reservation->besoins_speciaux ?? null,
            ];
        });

        // Statistiques
        $stats = [
            'total' => $user->reservations()->count(),
            'en_attente' => $user->reservations()->where('statut', 'en_attente')->count(),
            'confirmee' => $user->reservations()->where('statut', 'confirmee')->count(),
            'annulee' => $user->reservations()->where('statut', 'annulee')->count(),
            'terminee' => $user->reservations()->where('statut', 'terminee')->count(),
        ];

        return inertia('Client/Reservations', [
            'reservations' => $reservations,
            'stats' => $stats,
            'filters' => $request->only(['statut', 'date'])
        ]);
    }

    /**
     * Afficher les détails d'une réservation
     */
    public function show(Reservation $reservation)
    {
        // Vérifier que la réservation appartient à l'utilisateur
        if ($reservation->user_id !== Auth::id()) {
            abort(403);
        }

        $reservation->load([
            'salle' => function($query) {
                $query->select('id', 'nom', 'ville', 'pays', 'adresse', 'description', 'image_url', 'prix_heure', 'capacite_max', 'promoter_id')
                      ->with('promoter:id,name,email,phone');
            }
        ]);

        return inertia('Client/ReservationDetails', [
            'reservation' => $reservation
        ]);
    }

    /**
     * Annuler une réservation
     */
    public function cancel(Reservation $reservation, Request $request)
    {
        // Vérifier que la réservation appartient à l'utilisateur
        if ($reservation->user_id !== Auth::id()) {
            abort(403);
        }

        // Vérifier que la réservation peut être annulée
        if ($reservation->statut === 'annulee' || $reservation->statut === 'terminee') {
            return back()->with('error', 'Cette réservation ne peut pas être annulée.');
        }

        // Vérifier que la réservation est dans le futur (au moins 24h avant)
        if ($reservation->date_heure->subHours(24) < now()) {
            return back()->with('error', 'Les réservations doivent être annulées au moins 24 heures avant la date prévue.');
        }

        $reservation->update([
            'statut' => 'annulee',
            'motif_annulation' => $request->motif_annulation ?? 'Annulation par le client'
        ]);

        return back()->with('success', 'Réservation annulée avec succès.');
    }
}
