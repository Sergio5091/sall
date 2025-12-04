<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Models\Salle;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Afficher la page de recherche avec toutes les salles disponibles
     */
    public function index(Request $request)
    {
        $query = Salle::where('statut', 'actif')
            ->select('id', 'nom', 'ville', 'pays', 'capacite_max', 'prix_heure', 'description', 'image_url', 'promoter_id')
            ->with(['promoter:id,name'])
            ->orderBy('created_at', 'desc');

        // Rechercher par nom, ville ou description
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nom', 'like', '%' . $searchTerm . '%')
                  ->orWhere('ville', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

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
            ->distinct()
            ->pluck('ville')
            ->filter()
            ->sort()
            ->values();

        return inertia('Search/Rooms', [
            'salles' => $salles,
            'villes' => $villes,
            'filters' => $request->only(['search', 'ville', 'capacite_min'])
        ]);
    }
}
