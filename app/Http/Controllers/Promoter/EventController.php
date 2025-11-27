<?php

namespace App\Http\Controllers\Promoter;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Salle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Inertia\Inertia;

class EventController extends Controller
{
    /**
     * Afficher la liste des événements du promoteur
     */
    public function index()
    {
        $user = Auth::user();
        
        // Récupérer les événements du promoteur avec leurs URLs d'images
        $events = Event::where('promoter_id', $user->id)
            ->with('salle')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Ajouter les URLs complètes des images
        $events->getCollection()->transform(function ($event) {
            $event->image_affiche_url = $event->image_affiche ? asset('storage/events/affiches/' . $event->image_affiche) : null;
            $event->image_banniere_url = $event->image_banniere ? asset('storage/events/bannieres/' . $event->image_banniere) : null;
            return $event;
        });

        // Statistiques
        $stats = [
            'total' => $events->count(),
            'publies' => Event::where('promoter_id', $user->id)->where('statut', 'publie')->count(),
            'brouillons' => Event::where('promoter_id', $user->id)->where('statut', 'brouillon')->count(),
            'avenir' => Event::where('promoter_id', $user->id)->aVenir()->count(),
            'en_cours' => Event::where('promoter_id', $user->id)->enCours()->count(),
            'passes' => Event::where('promoter_id', $user->id)->passe()->count(),
        ];

        return Inertia::render('Promoter/Events', [
            'events' => $events,
            'stats' => $stats
        ]);
    }

    /**
     * Afficher le formulaire de création d'événement
     */
    public function create()
    {
        $user = Auth::user();
        
        // Vérifier si le promoteur a une salle
        $salle = Salle::where('promoter_id', $user->id)->first();
        
        if (!$salle) {
            return redirect()->route('promoter.venues.index')
                ->with('error', 'Vous devez d\'abord créer une salle avant de pouvoir organiser des événements.');
        }

        return Inertia::render('Promoter/CreateEvent', [
            'salle' => $salle,
            'categories' => Event::categories(),
            'types' => Event::types(),
            'statuts' => Event::statuts(),
            'visibilites' => Event::visibilites(),
            'devises' => Event::devises(),
            'publics_cibles' => Event::publicsCibles(),
        ]);
    }

    /**
     * Enregistrer un nouvel événement
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Validation
        $validated = $request->validate([
            // Informations de base
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            
            // Dates
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'date_limite_inscription' => 'nullable|date|before_or_equal:date_debut',
            
            // Tarifs
            'prix_base' => 'required|numeric|min:0',
            'prix_vip' => 'nullable|numeric|min:0',
            'prix_groupe' => 'nullable|numeric|min:0',
            'devise' => 'required|string',
            'gratuit' => 'boolean',
            
            // Capacité
            'capacite_max' => 'nullable|integer|min:1',
            'limite_inscription' => 'boolean',
            
            // Catégorie et type
            'categorie' => 'required|string',
            'type' => 'required|string',
            'tags' => 'nullable|array',
            
            // Public cible
            'public_cible' => 'nullable|string',
            'age_minimum' => 'nullable|integer|min:0|max:100',
            
            // Programme et activités
            'programme' => 'nullable|array',
            'activites' => 'nullable|array',
            
            // Contact
            'contact_email' => 'nullable|email',
            'contact_telephone' => 'nullable|string|max:20',
            'contact_whatsapp' => 'nullable|string|max:20',
            'reseaux_sociaux' => 'nullable|array',
            
            // Configuration
            'inscription_obligatoire' => 'boolean',
            'paiement_en_ligne' => 'boolean',
            'certificat_participation' => 'boolean',
            'streaming' => 'boolean',
            'url_streaming' => 'nullable|url',
            
            // Visibilité
            'visibilite' => 'required|string',
            
            // SEO
            'meta_titre' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'mots_cles' => 'nullable|array',
            
            // Images
            'image_affiche' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_banniere' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        // Gérer les checkboxes qui ne sont pas envoyées quand non cochées
        $validated['gratuit'] = $request->has('gratuit') ? filter_var($request->input('gratuit'), FILTER_VALIDATE_BOOLEAN) : false;
        $validated['limite_inscription'] = $request->has('limite_inscription') ? filter_var($request->input('limite_inscription'), FILTER_VALIDATE_BOOLEAN) : false;
        $validated['inscription_obligatoire'] = $request->has('inscription_obligatoire') ? filter_var($request->input('inscription_obligatoire'), FILTER_VALIDATE_BOOLEAN) : true;
        $validated['paiement_en_ligne'] = $request->has('paiement_en_ligne') ? filter_var($request->input('paiement_en_ligne'), FILTER_VALIDATE_BOOLEAN) : false;
        $validated['certificat_participation'] = $request->has('certificat_participation') ? filter_var($request->input('certificat_participation'), FILTER_VALIDATE_BOOLEAN) : false;
        $validated['streaming'] = $request->has('streaming') ? filter_var($request->input('streaming'), FILTER_VALIDATE_BOOLEAN) : false;

        // Récupérer la salle du promoteur
        $salle = Salle::where('promoter_id', $user->id)->first();
        
        if (!$salle) {
            return back()->with('error', 'Vous devez avoir une salle pour créer un événement.');
        }

        // Gérer les images
        $imageAffichePath = null;
        $imageBannierePath = null;

        if ($request->hasFile('image_affiche')) {
            $imageAffiche = $request->file('image_affiche');
            $imageAffichePath = time() . '_' . Str::random(10) . '.' . $imageAffiche->getClientOriginalExtension();
            $imageAffiche->storeAs('events/affiches', $imageAffichePath, 'public');
        }

        if ($request->hasFile('image_banniere')) {
            $imageBanniere = $request->file('image_banniere');
            $imageBannierePath = time() . '_' . Str::random(10) . '.' . $imageBanniere->getClientOriginalExtension();
            $imageBanniere->storeAs('events/bannieres', $imageBannierePath, 'public');
        }

        // Créer l'événement
        $event = Event::create([
            'salle_id' => $salle->id,
            'promoter_id' => $user->id,
            
            // Informations de base
            'titre' => $validated['titre'],
            'description' => $validated['description'],
            'slug' => Str::slug($validated['titre']) . '-' . time(),
            'image_affiche' => $imageAffichePath,
            'image_banniere' => $imageBannierePath,
            
            // Dates
            'date_debut' => $validated['date_debut'],
            'date_fin' => $validated['date_fin'],
            'date_limite_inscription' => $validated['date_limite_inscription'] ?? null,
            
            // Tarifs
            'prix_base' => $validated['prix_base'],
            'prix_vip' => $validated['prix_vip'] ?? null,
            'prix_groupe' => $validated['prix_groupe'] ?? null,
            'devise' => $validated['devise'],
            'gratuit' => $validated['gratuit'],
            
            // Capacité
            'capacite_max' => $validated['capacite_max'] ?? null,
            'places_disponibles' => $validated['limite_inscription'] ? ($validated['capacite_max'] ?? null) : null,
            'limite_inscription' => $validated['limite_inscription'],
            
            // Catégorie et type
            'categorie' => $validated['categorie'],
            'type' => $validated['type'],
            'tags' => $validated['tags'] ?? [],
            
            // Public cible
            'public_cible' => $validated['public_cible'] ?? null,
            'age_minimum' => $validated['age_minimum'] ?? null,
            
            // Programme et activités
            'programme' => $validated['programme'] ?? [],
            'activites' => $validated['activites'] ?? [],
            
            // Contact
            'contact_email' => $validated['contact_email'] ?? null,
            'contact_telephone' => $validated['contact_telephone'] ?? null,
            'contact_whatsapp' => $validated['contact_whatsapp'] ?? null,
            'reseaux_sociaux' => $validated['reseaux_sociaux'] ?? [],
            
            // Configuration
            'inscription_obligatoire' => $validated['inscription_obligatoire'],
            'paiement_en_ligne' => $validated['paiement_en_ligne'],
            'certificat_participation' => $validated['certificat_participation'],
            'streaming' => $validated['streaming'],
            'url_streaming' => $validated['url_streaming'] ?? null,
            
            // Visibilité
            'visibilite' => $validated['visibilite'],
            
            // SEO
            'meta_titre' => $validated['meta_titre'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'mots_cles' => $validated['mots_cles'] ?? [],
            
            // Statut
            'statut' => 'brouillon',
        ]);

        return redirect()->route('promoter.events')
            ->with('success', 'Événement créé avec succès ! Il est maintenant en brouillon.');
    }

    /**
     * Afficher un événement spécifique
     */
    public function show(Event $event)
    {
        // Vérifier que l'événement appartient au promoteur
        if ($event->promoter_id !== Auth::id()) {
            abort(403);
        }

        $event->load(['salle', 'inscriptions' => function($query) {
            $query->orderBy('created_at', 'desc')->limit(10);
        }]);

        // Statistiques de l'événement
        $stats = [
            'inscriptions_total' => $event->inscriptions()->count(),
            'inscriptions_confirmees' => $event->inscriptions()->where('statut', 'confirme')->count(),
            'inscriptions_en_attente' => $event->inscriptions()->where('statut', 'en_attente')->count(),
            'places_restantes' => $event->places_restantes,
            'revenus_estimes' => $event->inscriptions()->where('statut', 'confirme')->sum('montant_paye'),
        ];

        return Inertia::render('Promoter/ShowEvent', [
            'event' => $event,
            'stats' => $stats
        ]);
    }

    /**
     * Afficher le formulaire d'édition d'événement
     */
    public function edit(Event $event)
    {
        // Vérifier que l'événement appartient au promoteur
        if ($event->promoter_id !== Auth::id()) {
            abort(403);
        }

        $event->load('salle');

        return Inertia::render('Promoter/EditEvent', [
            'event' => $event,
            'categories' => Event::categories(),
            'types' => Event::types(),
            'statuts' => Event::statuts(),
            'visibilites' => Event::visibilites(),
            'devises' => Event::devises(),
            'publics_cibles' => Event::publicsCibles(),
        ]);
    }

    /**
     * Mettre à jour un événement
     */
    public function update(Request $request, Event $event)
    {
        // Vérifier que l'événement appartient au promoteur
        if ($event->promoter_id !== Auth::id()) {
            abort(403);
        }

        // Validation (plus flexible pour la modification)
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'date_limite_inscription' => 'nullable|date|before_or_equal:date_debut',
            'prix_base' => 'required|numeric|min:0',
            'prix_vip' => 'nullable|numeric|min:0',
            'prix_groupe' => 'nullable|numeric|min:0',
            'devise' => 'required|string',
            'gratuit' => 'boolean',
            'capacite_max' => 'nullable|integer|min:1',
            'limite_inscription' => 'boolean',
            'categorie' => 'required|string',
            'type' => 'required|string',
            'tags' => 'nullable|array',
            'public_cible' => 'nullable|string',
            'age_minimum' => 'nullable|integer|min:0|max:100',
            'programme' => 'nullable|array',
            'activites' => 'nullable|array',
            'contact_email' => 'nullable|email',
            'contact_telephone' => 'nullable|string|max:20',
            'contact_whatsapp' => 'nullable|string|max:20',
            'reseaux_sociaux' => 'nullable|array',
            'inscription_obligatoire' => 'boolean',
            'paiement_en_ligne' => 'boolean',
            'certificat_participation' => 'boolean',
            'streaming' => 'boolean',
            'url_streaming' => 'nullable|url',
            'statut' => 'required|string',
            'visibilite' => 'required|string',
            'mis_en_avant' => 'boolean',
            'meta_titre' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'mots_cles' => 'nullable|array',
            'image_affiche' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_banniere' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ], [
            // Messages personnalisés pour la modification
            'titre.required' => 'Le titre est obligatoire',
            'description.required' => 'La description est obligatoire',
            'date_debut.required' => 'La date de début est obligatoire',
            'date_fin.required' => 'La date de fin est obligatoire',
            'prix_base.required' => 'Le prix de base est obligatoire',
            'devise.required' => 'La devise est obligatoire',
            'categorie.required' => 'La catégorie est obligatoire',
            'type.required' => 'Le type est obligatoire',
            'statut.required' => 'Le statut est obligatoire',
            'visibilite.required' => 'La visibilité est obligatoire',
        ]);

        // Gérer les checkboxes qui ne sont pas envoyées quand non cochées
        $validated['gratuit'] = $request->has('gratuit') ? filter_var($request->input('gratuit'), FILTER_VALIDATE_BOOLEAN) : false;
        $validated['limite_inscription'] = $request->has('limite_inscription') ? filter_var($request->input('limite_inscription'), FILTER_VALIDATE_BOOLEAN) : false;
        $validated['inscription_obligatoire'] = $request->has('inscription_obligatoire') ? filter_var($request->input('inscription_obligatoire'), FILTER_VALIDATE_BOOLEAN) : true;
        $validated['paiement_en_ligne'] = $request->has('paiement_en_ligne') ? filter_var($request->input('paiement_en_ligne'), FILTER_VALIDATE_BOOLEAN) : false;
        $validated['certificat_participation'] = $request->has('certificat_participation') ? filter_var($request->input('certificat_participation'), FILTER_VALIDATE_BOOLEAN) : false;
        $validated['streaming'] = $request->has('streaming') ? filter_var($request->input('streaming'), FILTER_VALIDATE_BOOLEAN) : false;
        $validated['mis_en_avant'] = $request->has('mis_en_avant') ? filter_var($request->input('mis_en_avant'), FILTER_VALIDATE_BOOLEAN) : false;

        // Gérer les images
        if ($request->hasFile('image_affiche')) {
            // Supprimer l'ancienne image
            if ($event->image_affiche) {
                Storage::disk('public')->delete('events/affiches/' . $event->image_affiche);
            }

            $imageAffiche = $request->file('image_affiche');
            $imageAffichePath = time() . '_' . Str::random(10) . '.' . $imageAffiche->getClientOriginalExtension();
            $imageAffiche->storeAs('events/affiches', $imageAffichePath, 'public');
            $validated['image_affiche'] = $imageAffichePath;
        }

        if ($request->hasFile('image_banniere')) {
            // Supprimer l'ancienne image
            if ($event->image_banniere) {
                Storage::disk('public')->delete('events/bannieres/' . $event->image_banniere);
            }

            $imageBanniere = $request->file('image_banniere');
            $imageBannierePath = time() . '_' . Str::random(10) . '.' . $imageBanniere->getClientOriginalExtension();
            $imageBanniere->storeAs('events/bannieres', $imageBannierePath, 'public');
            $validated['image_banniere'] = $imageBannierePath;
        }

        // Mettre à jour les places disponibles si la capacité a changé
        if (isset($validated['capacite_max']) && $validated['capacite_max'] != $event->capacite_max) {
            $validated['places_disponibles'] = $validated['limite_inscription'] ? $validated['capacite_max'] : null;
        }

        $event->update($validated);

        return redirect()->route('promoter.events.show', $event)
            ->with('success', 'Événement mis à jour avec succès !');
    }

    /**
     * Supprimer un événement
     */
    public function destroy(Event $event)
    {
        // Vérifier que l'événement appartient au promoteur
        if ($event->promoter_id !== Auth::id()) {
            abort(403);
        }

        // Vérifier que l'événement n'a pas d'inscriptions confirmées
        if ($event->inscriptions()->where('statut', 'confirme')->count() > 0) {
            return back()->with('error', 'Impossible de supprimer un événement avec des inscriptions confirmées.');
        }

        // Supprimer les images
        if ($event->image_affiche) {
            Storage::disk('public')->delete('events/affiches/' . $event->image_affiche);
        }

        if ($event->image_banniere) {
            Storage::disk('public')->delete('events/bannieres/' . $event->image_banniere);
        }

        $event->delete();

        return redirect()->route('promoter.events.index')
            ->with('success', 'Événement supprimé avec succès !');
    }

    /**
     * Dupliquer un événement
     */
    public function duplicate(Event $event)
    {
        // Vérifier que l'événement appartient au promoteur
        if ($event->promoter_id !== Auth::id()) {
            abort(403);
        }

        $newEvent = $event->replicate();
        $newEvent->titre = $event->titre . ' (Copie)';
        $newEvent->slug = Str::slug($newEvent->titre) . '-' . uniqid();
        $newEvent->statut = 'brouillon';
        $newEvent->date_debut = null;
        $newEvent->date_fin = null;
        $newEvent->date_limite_inscription = null;
        $newEvent->save();

        return redirect()->route('promoter.events.edit', $newEvent)
            ->with('success', 'Événement dupliqué avec succès !');
    }

    /**
     * Publier un événement
     */
    public function publish(Event $event)
    {
        // Vérifier que l'événement appartient au promoteur
        if ($event->promoter_id !== Auth::id()) {
            abort(403);
        }

        // Validation avant publication
        if (!$event->date_debut || !$event->date_fin) {
            return back()->with('error', 'Veuillez définir les dates de début et de fin avant de publier.');
        }

        if (Carbon::now()->gt($event->date_debut)) {
            return back()->with('error', 'Impossible de publier un événement dont la date de début est déjà passée.');
        }

        $event->update(['statut' => 'publie']);

        return back()->with('success', 'Événement publié avec succès !');
    }

    /**
     * Annuler un événement
     */
    public function cancel(Event $event)
    {
        // Vérifier que l'événement appartient au promoteur
        if ($event->promoter_id !== Auth::id()) {
            abort(403);
        }

        $event->update(['statut' => 'annule']);

        // Notifier les inscrits (à implémenter)

        return back()->with('success', 'Événement annulé. Les inscrits seront notifiés.');
    }
}
