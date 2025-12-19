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
            ->where('valide', true)
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
            ->where('valide', true)
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

    /**
     * Rechercher les salles à proximité par coordonnées GPS
     */
    public function searchNearby(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'radius' => 'nullable|numeric|min:1|max:100' // rayon en km, max 100km
        ]);

        $lat = $request->lat;
        $lng = $request->lng;
        $radius = $request->radius ?? 50; // 50km par défaut

        // Récupérer toutes les salles actives et validées (avec ou sans coordonnées)
        $salles = Salle::where('statut', 'actif')
            ->where('valide', true)
            ->select('id', 'nom', 'ville', 'pays', 'adresse', 'capacite_max', 'prix_heure', 'description', 'image_url', 'promoter_id', 'latitude', 'longitude')
            ->with(['promoter:id,name'])
            ->get();

        // Calculer la distance pour chaque salle
        $sallesWithDistance = $salles->map(function($salle) use ($lat, $lng) {
            if ($salle->latitude && $salle->longitude) {
                // Si la salle a des coordonnées GPS, calculer la distance réelle
                $distance = $this->calculateDistance($lat, $lng, $salle->latitude, $salle->longitude);
                $salle->distance = round($distance, 1);
            } else {
                // Sinon, mettre une distance par défaut et indiquer que la localisation est approximative
                $salle->distance = null;
                $salle->location_approx = true;
            }
            return $salle;
        })->filter(function($salle) use ($radius) {
            // Garder les salles avec distance calculée dans le rayon OU celles sans coordonnées
            return $salle->distance === null || $salle->distance <= $radius;
        })->sortBy(function($salle) {
            // Mettre les salles avec distance exacte en premier
            return $salle->distance === null ? 999999 : $salle->distance;
        })->values();

        return response()->json([
            'salles' => $sallesWithDistance,
            'total' => $sallesWithDistance->count()
        ]);
    }

    /**
     * Calculer la distance entre deux points GPS (formule de Haversine)
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $R = 6371; // Rayon de la Terre en km
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        
        $a = sin($dLat/2) * sin($dLat/2) + 
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * 
             sin($dLon/2) * sin($dLon/2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $R * $c;
        
        return $distance;
    }
}
