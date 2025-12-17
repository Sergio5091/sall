<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventController extends Controller
{
    /**
     * Afficher la liste des événements publics
     */
    public function index()
    {
        // Récupérer tous les événements pour déboguer
        $allEvents = Event::get();
        \Log::info('Total events: ' . $allEvents->count());
        
        foreach ($allEvents as $event) {
            \Log::info('Event: ' . $event->titre . ', Statut: ' . $event->statut);
        }

        $events = Event::orderBy('date_debut', 'desc')
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
                    'category' => $event->categorie
                ];
            });

        $categories = [
            ['id' => 0, 'name' => 'Tous'],
            ['id' => 1, 'name' => 'Tournoi'],
            ['id' => 2, 'name' => 'Soirée'],
            ['id' => 3, 'name' => 'Atelier'],
            ['id' => 4, 'name' => 'Lancement']
        ];

        return Inertia::render('Events', [
            'events' => $events,
            'categories' => $categories
        ]);
    }

    /**
     * Afficher les détails d'un événement
     */
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
