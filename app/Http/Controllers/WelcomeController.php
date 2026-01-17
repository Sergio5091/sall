<?php

namespace App\Http\Controllers;

use App\Models\Salle;
use App\Models\Event;
use App\Models\StandaloneEvent;
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
        $popularRooms = Salle::where('valide', 1)
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
                    'rating' => $salle->note_moyenne && $salle->note_moyenne > 0 ? number_format($salle->note_moyenne, 1) : '4.5',
                    'image' => $salle->image_url,
                    'slug' => $salle->slug,
                    'description' => $salle->description ? substr($salle->description, 0, 100) . '...' : '',
                    'equipements' => $salle->services ?? [],
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

        // Récupérer les événements ponctuels (créés par l'admin)
        $standaloneEvents = StandaloneEvent::where('status', 'active')
            ->where('event_date', '>', now())
            ->orderBy('event_date', 'asc')
            ->take(3)
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description ? substr($event->description, 0, 120) . '...' : '',
                    'image' => $event->image,
                    'type' => 'Événement Ponctuel',
                    'date' => $event->event_date->format('d/m/Y H:i'),
                    'location' => $event->location . ', ' . $event->country,
                    'price' => $event->price ? number_format($event->price, 0, ',', ' ') . ' FCFA' : 'Gratuit',
                    'organizer' => $event->organizer_name,
                ];
            });

        // Récupérer les événements promoteurs (publiés et à venir)
        $promoterEvents = Event::where('statut', 'publie')
            ->where('date_debut', '>', now())
            ->with(['salle', 'promoter'])
            ->orderBy('date_debut', 'asc')
            ->take(3)
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->titre,
                    'description' => $event->description ? substr($event->description, 0, 120) . '...' : '',
                    'image' => $event->image_affiche ? 'events/affiches/' . $event->image_affiche : null,
                    'type' => $event->categorie_texte,
                    'date' => $event->date_debut->format('d/m/Y H:i'),
                    'location' => $event->lieu ?? $event->salle->nom ?? 'En ligne',
                    'price' => $event->prix_formatte,
                    'organizer' => $event->promoter->nom_complet ?? 'Organisateur',
                ];
            });

        // Récupérer les nouveautés (News) créées par l'admin
        $newsItems = News::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get()
            ->map(function ($news) {
                return [
                    'id' => $news->id,
                    'title' => $news->title,
                    'description' => $news->description ? substr($news->description, 0, 120) . '...' : '',
                    'image' => $news->image,
                    'type' => 'Nouveauté',
                    'date' => $news->created_at->format('d/m/Y'),
                    'location' => 'Actualité',
                    'price' => null,
                ];
            });

        // Récupérer les nouveautés (salles récemment validées)
        $newRooms = Salle::where('valide', 1)
            ->where('statut', 'actif')
            ->whereNotNull('validated_at')
            ->where('validated_at', '>', now()->subDays(30))
            ->with(['promoter'])
            ->orderBy('validated_at', 'desc')
            ->take(2)
            ->get()
            ->map(function ($salle) {
                return [
                    'id' => $salle->id,
                    'title' => $salle->nom,
                    'description' => $salle->description ? substr($salle->description, 0, 100) . '...' : '',
                    'image' => $salle->image_url,
                    'type' => 'Nouveau',
                    'date' => $salle->validated_at?->format('d/m/Y'),
                    'location' => $salle->ville,
                    'price' => $salle->prix_heure ? number_format($salle->prix_heure, 2, ',', '') . '€/h' : 'Prix sur demande',
                ];
            });

        // Combiner tous les éléments pour la section "À la une"
        $featuredItems = collect()
            ->merge($newsItems)        // Nouveautés (News) - priorité 1
            ->merge($standaloneEvents)  // Événements ponctuels (admin)
            ->merge($promoterEvents)   // Événements promoteurs
            ->merge($newRooms)          // Nouvelles salles
            ->take(6);

        // Statistiques réelles
        $stats = [
            'usersCount' => User::count(),
            'roomsCount' => Salle::where('valide', 1)->count(),
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
        $salles = Salle::where('valide', 1)
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
                    'rating' => $salle->note_moyenne && $salle->note_moyenne > 0 ? number_format($salle->note_moyenne, 1) : '4.5',
                    'image' => $salle->image_url,
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
