<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\StandaloneEvent;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventController extends Controller
{
    /**
     * Afficher la liste des événements publics
     */
    public function index()
    {
        // Récupérer uniquement les événements publiés (promoteurs)
        $regularEvents = Event::where('statut', 'publie')
            ->orderBy('date_debut', 'desc')
            ->get()
            ->map(function ($event) {
                // Charger les relations seulement si disponibles
                $salle = null;
                try {
                    $salle = $event->salle;
                } catch (\Exception $e) {
                    // Ignorer si la relation n'existe pas
                }
                
                return [
                    'id' => $event->id,
                    'name' => $event->titre,
                    'description' => $event->description,
                    'date' => $event->date_debut->toISOString(),
                    'location' => $salle ? $salle->nom . ', ' . $salle->ville : 'Lieu à définir',
                    'game_type' => $event->categorie,
                    'prize_pool' => $event->gratuit ? 0 : $event->prix_base,
                    'max_participants' => $event->capacite_max ?: 100,
                    'current_participants' => $event->places_disponibles ?: 0,
                    'status' => $event->date_debut > now() ? 'upcoming' : 'completed',
                    'category_id' => 1,
                    'image' => $event->url_image_banniere,
                    'category' => $event->categorie,
                    'statut' => $event->statut,
                    'type' => 'regular' // Pour distinguer des événements ponctuels
                ];
            });

        // Récupérer les événements ponctuels (admin)
        $standaloneEvents = StandaloneEvent::where('status', 'active')
            ->orderBy('event_date', 'desc')
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'name' => $event->title,
                    'description' => $event->description,
                    'date' => $event->event_date->toISOString(),
                    'location' => $event->location . ', ' . $event->country,
                    'game_type' => 'Événement ponctuel',
                    'prize_pool' => $event->price ?: 0,
                    'max_participants' => 100, // Valeur par défaut
                    'current_participants' => 0, // Pas de suivi pour les événements ponctuels
                    'status' => $event->event_date > now() ? 'upcoming' : 'completed',
                    'category_id' => 5, // Catégorie spéciale pour événements ponctuels
                    'image' => $event->image ? 'storage/' . $event->image : null,
                    'category' => 'Événement ponctuel',
                    'type' => 'standalone', // Pour distinguer
                    'organizer_name' => $event->organizer_name,
                    'organizer_email' => $event->organizer_email,
                    'organizer_phone' => $event->organizer_phone,
                    'price' => $event->price
                ];
            });

        // Fusionner les deux types d'événements
        $allEvents = $regularEvents->concat($standaloneEvents);

        $categories = [
            ['id' => 0, 'name' => 'Tous'],
            ['id' => 1, 'name' => 'Tournoi'],
            ['id' => 2, 'name' => 'Soirée'],
            ['id' => 3, 'name' => 'Atelier'],
            ['id' => 4, 'name' => 'Lancement'],
            ['id' => 5, 'name' => 'Événements ponctuels']
        ];

        return Inertia::render('Events', [
            'events' => $allEvents,
            'categories' => $categories
        ]);
    }

    /**
     * Inscrire un utilisateur à un événement
     */
    public function register(Request $request, Event $event)
    {
        \Log::info('Tentative d\'inscription', [
            'event_id' => $event->id,
            'user_id' => auth()->id(),
            'request_data' => $request->all()
        ]);
        
        $user = auth()->user();
        
        // Vérifier si l'utilisateur est déjà inscrit
        if ($event->inscriptions()->where('user_id', $user->id)->exists()) {
            \Log::info('Utilisateur déjà inscrit');
            return redirect('/client/evenements')->withErrors(['message' => 'Vous êtes déjà inscrit à cet événement.']);
        }
        
        // Vérifier si l'événement est complet
        if ($event->places_disponibles <= 0) {
            \Log::info('Événement complet');
            return redirect('/client/evenements')->withErrors(['message' => 'Cet événement est complet.']);
        }
        
        // Vérifier si l'événement est publié
        if ($event->statut !== 'publie') {
            \Log::info('Événement non publié', ['statut' => $event->statut]);
            return redirect('/client/evenements')->withErrors(['message' => 'Cet événement n\'est pas encore publié.']);
        }
        
        // Créer l'inscription avec les données du formulaire
        $inscription = \App\Models\Inscription::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'date_inscription' => now(),
            'statut' => 'confirme',
            'nom' => $request->input('nom', $user->name),
            'whatsapp' => $request->input('whatsapp', $user->telephone),
            'email' => $request->input('email', $user->email)
        ]);
        
        \Log::info('Inscription créée', ['inscription_id' => $inscription->id]);
        
        // Mettre à jour le nombre de places disponibles
        $event->decrement('places_disponibles');
        
        return redirect('/client/evenements')->with('success', 'Inscription réussie !');
    }
    public function show(Event $event)
    {
        $event->load(['salle.promoter', 'promoter']);

        return Inertia::render('EventDetails', [
            'event' => [
                'id' => $event->id,
                'name' => $event->titre,
                'description' => $event->description,
                'date' => $event->date_debut->toISOString(),
                'location' => $event->salle ? $event->salle->nom . ', ' . $event->salle->ville : 'En ligne',
                'game_type' => $event->categorie,
                'prize_pool' => $event->gratuit ? 0 : $event->prix_base,
                'max_participants' => $event->capacite_max ?: 100,
                'current_participants' => $event->places_disponibles ?: 0,
                'status' => $event->statut === 'publie' ? 'upcoming' : ($event->statut === 'termine' ? 'completed' : 'cancelled'),
                'category_id' => 1,
                'image' => $event->url_image_banniere,
                'category' => $event->categorie,
                'salle' => $event->salle ? [
                    'id' => $event->salle->id,
                    'nom' => $event->salle->nom,
                    'adresse' => $event->salle->adresse,
                    'ville' => $event->salle->ville,
                    'pays' => $event->salle->pays,
                    'image_url' => $event->salle->image_url,
                    'description' => $event->salle->description,
                    'capacite' => $event->salle->capacite,
                    'promoter' => $event->salle->promoter ? [
                        'id' => $event->salle->promoter->id,
                        'name' => $event->salle->promoter->name,
                        'email' => $event->salle->promoter->email
                    ] : null
                ] : null,
                'prix_base' => $event->prix_base,
                'prix_vip' => $event->prix_vip,
                'gratuit' => $event->gratuit,
                'contact_email' => $event->contact_email,
                'contact_telephone' => $event->contact_telephone,
                'date_fin' => $event->date_fin ? $event->date_fin->toISOString() : null,
                'date_limite_inscription' => $event->date_limite_inscription ? $event->date_limite_inscription->toISOString() : null
            ]
        ]);
    }
}
