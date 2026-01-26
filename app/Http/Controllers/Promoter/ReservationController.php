<?php

namespace App\Http\Controllers\Promoter;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Conversation;
use App\Models\Salle;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ReservationController extends Controller
{
    /**
     * Afficher la liste des réservations du promoteur
     */
    public function index(Request $request)
    {
        $promoter = Auth::user();
        
        // Récupérer les salles du promoteur
        $salleIds = Salle::where('promoter_id', $promoter->id)->pluck('id');
        
        // Récupérer les réservations pour ces salles
        $query = Reservation::with(['user', 'salle'])
            ->whereIn('salle_id', $salleIds)
            ->orderBy('created_at', 'desc');

        // Filtrage par statut
        if ($request->has('statut') && $request->statut) {
            $query->where('statut', $request->statut);
        }

        $reservations = $query->paginate(10);

        // Transformer les données pour la vue
        $reservations->getCollection()->transform(function ($reservation) {
            return [
                'id' => $reservation->id,
                'client_name' => $reservation->user->name,
                'client_email' => $reservation->user->email,
                'client_telephone' => $reservation->user->telephone,
                'venue_name' => $reservation->salle->nom,
                'venue_id' => $reservation->salle->id,
                'date_heure' => $reservation->date_heure->toISOString(),
                'duree' => $reservation->duree,
                'nombre_personnes' => $reservation->nombre_personnes,
                'montant_total' => $reservation->prix_total,
                'statut' => $reservation->statut,
                'message' => $reservation->message,
                'created_at' => $reservation->created_at->toISOString(),
                'has_conversation' => $reservation->conversation()->exists(),
            ];
        });

        // Statistiques
        $stats = [
            'total' => Reservation::whereIn('salle_id', $salleIds)->count(),
            'en_attente' => Reservation::whereIn('salle_id', $salleIds)->where('statut', 'en_attente')->count(),
            'confirme' => Reservation::whereIn('salle_id', $salleIds)->where('statut', 'confirme')->count(),
            'annule' => Reservation::whereIn('salle_id', $salleIds)->where('statut', 'annule')->count(),
            'termine' => Reservation::whereIn('salle_id', $salleIds)->where('statut', 'termine')->count(),
        ];

        // Salles pour le filtre
        $venues = Salle::where('promoter_id', $promoter->id)
            ->select('id', 'nom')
            ->get();

        return Inertia::render('Promoter/Reservations', [
            'reservations' => $reservations,
            'stats' => $stats,
            'venues' => $venues,
            'filters' => $request->only(['statut'])
        ]);
    }

    /**
     * Accepter une réservation
     */
    public function accept(Reservation $reservation)
    {
        // Vérifier que la réservation appartient à une salle du promoteur
        $this->authorizeReservation($reservation);
        
        $reservation->update(['statut' => 'confirme']);
        
        // Notifier le client
        Notification::createForUser(
            $reservation->user_id,
            'Réservation acceptée !',
            "Votre réservation pour '{$reservation->salle->nom}' a été acceptée par le propriétaire",
            'success',
            'reservation',
            $reservation->id
        );
        
        return back()->with('success', 'Réservation acceptée avec succès');
    }

    /**
     * Refuser une réservation
     */
    public function reject(Request $request, Reservation $reservation)
    {
        // Vérifier que la réservation appartient à une salle du promoteur
        $this->authorizeReservation($reservation);
        
        $validated = $request->validate([
            'motif' => 'nullable|string|max:500'
        ]);
        
        $reservation->update([
            'statut' => 'annule',
            'promoter_notes' => $validated['motif'] ?? null
        ]);
        
        // Notifier le client
        Notification::createForUser(
            $reservation->user_id,
            'Réservation refusée',
            "Votre réservation pour '{$reservation->salle->nom}' a été refusée" . 
            ($validated['motif'] ? ". Motif: {$validated['motif']}" : ""),
            'error',
            'reservation',
            $reservation->id
        );
        
        return back()->with('success', 'Réservation refusée avec succès');
    }

    /**
     * Afficher les détails d'une réservation
     */
    public function show(Reservation $reservation)
    {
        // Vérifier que la réservation appartient à une salle du promoteur
        $this->authorizeReservation($reservation);
        
        $reservation->load(['user', 'salle']);
        
        return Inertia::render('Promoter/ReservationDetails', [
            'reservation' => $reservation
        ]);
    }

    /**
     * Vérifier que la réservation appartient au promoteur
     */
    private function authorizeReservation(Reservation $reservation)
    {
        $promoter = Auth::user();
        $salle = $reservation->salle;
        
        if ($salle->promoter_id !== $promoter->id) {
            abort(403, 'Vous n\'êtes pas autorisé à gérer cette réservation');
        }
    }
}
