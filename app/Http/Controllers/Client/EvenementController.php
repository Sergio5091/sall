<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Salle;
use Illuminate\Http\Request;

class EvenementController extends Controller
{
    /**
     * Afficher la liste de tous les événements de toutes les salles
     */
    public function index(Request $request)
    {
        $query = Event::with(['salle' => function($query) {
                $query->select('id', 'nom', 'ville', 'pays', 'image_url');
            }])
            ->where('statut', 'publie')
            ->orderBy('date_debut', 'asc');

        // Filtrage par date
        if ($request->has('date') && $request->date) {
            $query->whereDate('date_debut', $request->date);
        }

        // Filtrage par ville
        if ($request->has('ville') && $request->ville) {
            $query->whereHas('salle', function($q) use ($request) {
                $q->where('ville', 'like', '%' . $request->ville . '%');
            });
        }

        // Filtrage par recherche
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('titre', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        // Pagination
        $evenements = $query->paginate(12);

        // Récupérer les villes disponibles pour le filtre
        $villes = Salle::whereHas('evenements')
            ->where('statut', 'actif')
            ->distinct()
            ->pluck('ville')
            ->sort()
            ->values();

        return inertia('Client/Evenements', [
            'evenements' => $evenements,
            'villes' => $villes,
            'filters' => $request->only(['search', 'ville', 'date'])
        ]);
    }

    /**
     * Afficher les détails d'un événement spécifique
     */
    public function show(Event $evenement)
    {
        // Vérifier que l'événement est publié
        if ($evenement->statut !== 'publie') {
            abort(404);
        }

        // Charger l'événement avec la salle et le promoteur
        $evenement->load([
            'salle' => function($query) {
                $query->select('id', 'nom', 'ville', 'pays', 'adresse', 'description', 'image_url', 'prix_heure', 'capacite_max', 'promoter_id')
                      ->with('promoter:id,name');
            }
        ]);

        // Événements similaires dans la même salle
        $evenementsSimilaires = Event::where('salle_id', $evenement->salle_id)
            ->where('id', '!=', $evenement->id)
            ->where('statut', 'publie')
            ->where('date_debut', '>=', now())
            ->orderBy('date_debut', 'asc')
            ->take(3)
            ->get(['id', 'titre', 'description', 'date_debut', 'date_fin']);

        return inertia('Client/EvenementDetails', [
            'evenement' => $evenement,
            'evenementsSimilaires' => $evenementsSimilaires
        ]);
    }
}
