<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Salle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalleController extends Controller
{
    /**
     * Afficher la liste de toutes les salles disponibles pour les clients
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

        // Récupérer les villes uniques pour le filtre (cache)
        $villes = cache()->remember('villes_disponibles', 3600, function () {
            return Salle::where('statut', 'actif')
                ->distinct()
                ->pluck('ville')
                ->filter()
                ->sort()
                ->values();
        });

        // Récupérer les réservations du client (limité)
        $reservations = [];
        if (Auth::check()) {
            $reservations = Auth::user()->reservations()
                ->with('salle:id,nom')
                ->where('date_heure', '>=', now())
                ->orderBy('date_heure', 'asc')
                ->take(3)
                ->get(['id', 'salle_id', 'date_heure', 'duree', 'statut']);
        }

        return inertia('Client/Salles', [
            'salles' => $salles,
            'villes' => $villes,
            'filters' => $request->only(['search', 'ville', 'capacite_min']),
            'reservations' => $reservations
        ]);
    }

    /**
     * Afficher les détails d'une salle spécifique pour les clients
     */
    public function show(Salle $salle)
    {
        // Vérifier que la salle est active
        if ($salle->statut !== 'actif') {
            abort(404);
        }

        // Charger la salle avec tous les champs nécessaires pour l'affichage
        $salle->load([
            'promoter:id,name',
            'evenements' => function($query) {
                $query->where('statut', 'publie')
                      ->where('date_fin', '>=', now())
                      ->orderBy('date_debut', 'asc')
                      ->take(5)
                      ->select('id', 'salle_id', 'titre', 'description', 'date_debut', 'date_fin', 'statut');
            }
        ]);

        // S'assurer que tous les champs nécessaires sont chargés
        $salleArray = $salle->toArray();
        // Ajouter les champs manquants s'ils ne sont pas déjà présents
        $requiredFields = [
            'wifi_gratuit', 'parking', 'climatisation', 'surveillance_24h',
            'ecrans', 'consoles', 'systeme_audio', 'surface', 'air_conditionne',
            'type', 'categorie', 'telephone', 'whatsapp', 'email', 'site_web',
            'machines_arcade', 'casques_vr', 'pc_gaming', 'consoles_retro',
            'flippers', 'tables_bowling', 'tables_billard', 'snack_bar',
            'restaurant', 'bar', 'terrasse', 'vestiaires', 'accessibilite_pmr',
            'point_repere', 'region', 'departement', 'quartier',
            'image_couverture', 'images_galerie', 'images'
        ];
        
        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $salleArray)) {
                $salleArray[$field] = null;
            }
        }

        // Salles similaires limitées
        $sallesSimilaires = Salle::where('statut', 'actif')
            ->where('ville', $salle->ville)
            ->where('id', '!=', $salle->id)
            ->select('id', 'nom', 'ville', 'prix_heure', 'capacite_max')
            ->take(3)
            ->get();

        // Vérifier si le client a déjà réservé cette salle
        $aReserve = false;
        if (Auth::check()) {
            $aReserve = Auth::user()->reservations()
                ->where('salle_id', $salle->id)
                ->exists();
        }

        return inertia('Client/SalleDetails', [
            'salle' => $salleArray,
            'sallesSimilaires' => $sallesSimilaires,
            'aReserve' => $aReserve
        ]);
    }

    /**
     * Créer une réservation pour une salle
     */
    public function reserver(Request $request, Salle $salle)
    {
        $validated = $request->validate([
            'date_heure' => 'required|date|after:now',
            'duree' => 'required|integer|min:1|max:8',
            'nombre_personnes' => 'required|integer|min:1|max:' . $salle->capacite_max,
            'message' => 'nullable|string|max:500'
        ]);

        // Calculer le prix total
        $prix_total = $salle->prix_heure * $validated['duree'];

        // Créer la réservation
        $reservation = Auth::user()->reservations()->create([
            'salle_id' => $salle->id,
            'date_heure' => $validated['date_heure'],
            'duree' => $validated['duree'],
            'nombre_personnes' => $validated['nombre_personnes'],
            'prix_total' => $prix_total,
            'statut' => 'en_attente',
            'message' => $validated['message'] ?? null
        ]);

        // Créer la conversation automatiquement
        $conversation = \App\Models\Conversation::create([
            'reservation_id' => $reservation->id,
            'user_id' => Auth::id(),
            'promoter_id' => $salle->promoter_id,
        ]);

        // Notifier le promoteur
        \App\Models\Notification::createForUser(
            $salle->promoter_id,
            'Nouvelle réservation',
            "Une réservation a été faite pour votre salle '{$salle->nom}'",
            'info',
            'reservation',
            $reservation->id
        );

        return redirect()->route('client.salles.show', $salle->id)
            ->with('success', 'Réservation créée avec succès ! En attente de confirmation.');
    }
}
