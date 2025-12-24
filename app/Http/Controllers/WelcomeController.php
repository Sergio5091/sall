<?php

namespace App\Http\Controllers;

use App\Models\Salle;
use App\Models\Event;
use App\Models\User;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    /**
     * Affiche la page d'accueil avec les données réelles
     */
    public function index()
    {
        // Récupérer les salles populaires (validées et actives)
        $popularRooms = Salle::where('valide', true)
            ->where('statut', 'actif')
            ->with(['promoter'])
            ->orderBy('nombre_vues', 'desc')
            ->take(8)
            ->get()
            ->map(function ($salle) {
                return [
                    'id' => $salle->id,
                    'name' => $salle->nom,
                    'location' => $salle->ville,
                    'price' => $salle->prix_heure ? number_format($salle->prix_heure, 2, ',', '') : '0',
                    'rating' => $salle->note_moyenne ? number_format($salle->note_moyenne, 1) : '4.5',
                    'image' => $salle->image_url ?? 'https://picsum.photos/seed/salle-' . $salle->id . '/400/300.jpg',
                    'slug' => $salle->slug,
                    'description' => $salle->description ? substr($salle->description, 0, 100) . '...' : '',
                    'equipements' => $salle->equipements ?? [],
                    'capacite' => $salle->capacite_max,
                ];
            });

        // Récupérer les événements à venir et mis en avant
        $featuredEvents = Event::where('statut', 'publie')
            ->where('date_debut', '>', now())
            ->where('mis_en_avant', true)
            ->with(['salle', 'promoter'])
            ->orderBy('date_debut', 'asc')
            ->take(6)
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->titre,
                    'description' => $event->description ? substr($event->description, 0, 120) . '...' : '',
                    'image' => $event->url_image_affiche,
                    'date_debut' => $event->date_debut->format('d/m/Y H:i'),
                    'lieu' => $event->lieu ?? $event->salle->nom ?? 'En ligne',
                    'prix' => $event->prix_formatte,
                    'categorie' => $event->categorie_texte,
                    'slug' => $event->slug,
                ];
            });

        // Récupérer les nouveautés (salles récemment validées)
        $newRooms = Salle::where('valide', true)
            ->where('statut', 'actif')
            ->whereNotNull('date_validation')
            ->where('date_validation', '>', now()->subDays(30))
            ->with(['promoter'])
            ->orderBy('date_validation', 'desc')
            ->take(3)
            ->get()
            ->map(function ($salle) {
                return [
                    'id' => $salle->id,
                    'title' => $salle->nom,
                    'description' => $salle->description ? substr($salle->description, 0, 100) . '...' : '',
                    'image' => $salle->image_url ?? 'https://picsum.photos/seed/salle-' . $salle->id . '/400/300.jpg',
                    'type' => 'Nouveau',
                    'date_validation' => $salle->date_validation?->format('d/m/Y'),
                    'location' => $salle->ville,
                ];
            });

        // Combiner les nouveautés avec les événements pour la section "À la une"
        $featuredItems = collect()
            ->merge($newRooms)
            ->merge($featuredEvents->take(3)->map(function ($event) {
                return [
                    'id' => $event['id'],
                    'title' => $event['title'],
                    'description' => $event['description'],
                    'image' => $event['image'],
                    'type' => $event['categorie'] === 'Tournoi' ? 'Tournoi' : 'Événement',
                    'date' => $event['date_debut'],
                    'location' => $event['lieu'],
                ];
            }))
            ->take(3);

        // Statistiques réelles
        $stats = [
            'usersCount' => User::count(),
            'roomsCount' => Salle::where('valide', true)->count(),
            'bookingsCount' => 0, // À implémenter quand il y aura un modèle Reservation
            'eventsCount' => Event::where('statut', 'publie')->count(),
        ];

        
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => app()->version(),
            'phpVersion' => PHP_VERSION,
            
            // Données réelles pour la page d'accueil
            'popularRooms' => $popularRooms,
            'featuredEvents' => $featuredEvents,
            'featuredItems' => $featuredItems,
            'stats' => $stats,
            
            // Garder pour compatibilité
            'news' => $featuredItems,
        ]);
    }

    /**
     * Recherche de salles et événements
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $location = $request->get('location', '');

        // Rechercher des salles
        $salles = Salle::where('valide', true)
            ->where('statut', 'actif')
            ->when($query, function ($q) use ($query) {
                $q->where('nom', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%");
            })
->when($location, function ($q) use ($location) {
                $q->where('ville', 'LIKE', "%{$location}%");
            })
            ->with(['promoter'])
            ->get()
            ->map(function ($salle) {
                return [
                    'id' => $salle->id,
                    'name' => $salle->nom,
                    'location' => $salle->ville,
                    'price' => $salle->prix_heure ? number_format($salle->prix_heure, 2, ',', '') : '0',
                    'rating' => $salle->note_moyenne ? number_format($salle->note_moyenne, 1) : '4.5',
                    'image' => $salle->image_url ?? 'https://picsum.photos/seed/salle-' . $salle->id . '/400/300.jpg',
                    'slug' => $salle->slug,
                ];
            });

        return response()->json([
            'salles' => $salles,
            'query' => $query,
            'location' => $location,
        ]);
    }
}
