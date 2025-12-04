<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Salle;
use Illuminate\Http\Request;

class SalleController extends Controller
{
    /**
     * Afficher la liste de toutes les salles disponibles
     */
    public function index(Request $request)
    {
        $query = Salle::where('statut', 'actif')
            ->with(['promoter'])
            ->orderBy('created_at', 'desc');

        // Filtrer par ville si spécifié
        if ($request->has('ville') && $request->ville) {
            $query->where('ville', 'like', '%' . $request->ville . '%');
        }

        // Filtrer par capacité minimale si spécifié
        if ($request->has('capacite_min') && $request->capacite_min) {
            $query->where('capacite_max', '>=', $request->capacite_min);
        }

        $salles = $query->paginate(12);

        // Récupérer les villes uniques pour le filtre
        $villes = Salle::where('statut', 'actif')
            ->where('valide', true)
            ->distinct()
            ->pluck('ville')
            ->filter()
            ->sort()
            ->values();

        return inertia('Public/Salles', [
            'salles' => $salles,
            'villes' => $villes,
            'filters' => $request->only(['ville', 'capacite_min'])
        ]);
    }

    /**
     * Afficher les détails d'une salle spécifique
     */
    public function show(Salle $salle)
    {
        // Vérifier que la salle est active
        if ($salle->statut !== 'actif') {
            abort(404);
        }

        // Charger la salle avec le promoteur et les événements à venir
        $salle->load(['promoter', 'evenements' => function($query) {
            $query->where('statut', 'publie')
                  ->where('date_fin', '>=', now())
                  ->orderBy('date_debut', 'asc')
                  ->take(5);
        }]);

        // Salles similaires (même ville)
        $sallesSimilaires = Salle::where('statut', 'actif')
            ->where('valide', true)
            ->where('ville', $salle->ville)
            ->where('id', '!=', $salle->id)
            ->take(3)
            ->get();

        return inertia('Public/SalleDetails', [
            'salle' => $salle,
            'sallesSimilaires' => $sallesSimilaires
        ]);
    }
}
