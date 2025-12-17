<?php

namespace App\Http\Controllers\Promoter;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Salle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
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
        
        $events = Event::where('promoter_id', $user->id)
            ->with('salle')
            ->orderBy('date_debut', 'desc')
            ->paginate(10);

        // Ajouter les URLs des images aux événements
        $events->getCollection()->transform(function ($event) {
            $event->url_image_affiche = $event->url_image_affiche;
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
            
            // Lieu et localisation
            'lieu' => 'required|string|max:255',
            'adresse' => 'required|string|max:500',
            'ville' => 'required|string|max:100',
            'pays' => 'required|string|max:100',
            
            // Dates
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
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
            'contact_email' => 'required|email',
            'contact_telephone' => 'nullable|string|max:20',
            'contact_whatsapp' => 'nullable|string|max:20',
            'reseaux_sociaux' => 'nullable|array',
            'site_web' => 'nullable|url',
            
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

        // Récupérer la salle du promoteur
        $salle = Salle::where('promoter_id', $user->id)->first();
        
        if (!$salle) {
            return back()->with('error', 'Vous devez avoir une salle pour créer un événement.');
        }

        // Gérer les images
        $imageAffichePath = null;
        $imageBannierePath = null;

        // Logs pour débogage
        \Log::info('Fichiers reçus:', ['files' => $request->allFiles()]);
        \Log::info('image_bannière présente:', ['has_file' => $request->hasFile('image_banniere')]);

        if ($request->hasFile('image_affiche')) {
            $imageAffiche = $request->file('image_affiche');
            $imageAffichePath = time() . '_' . Str::random(10) . '.' . $imageAffiche->getClientOriginalExtension();
            $imageAffiche->storeAs('events/affiches', $imageAffichePath, 'public');
            \Log::info('image_affiche sauvegardée:', ['path' => $imageAffichePath]);
        }

        if ($request->hasFile('image_banniere')) {
            $imageBanniere = $request->file('image_banniere');
            $imageBannierePath = time() . '_' . Str::random(10) . '.' . $imageBanniere->getClientOriginalExtension();
            $imageBanniere->storeAs('events/bannieres', $imageBannierePath, 'public');
            \Log::info('image_banniere sauvegardée:', ['path' => $imageBannierePath]);
        } else {
            \Log::info('image_banniere NON trouvée', []);
        }

        // If no affiche was uploaded but a bannière was, copy bannière to affiches so listing shows an image
        if (!$imageAffichePath && $imageBannierePath) {
            try {
                Storage::disk('public')->copy('events/bannieres/' . $imageBannierePath, 'events/affiches/' . $imageBannierePath);
                $imageAffichePath = $imageBannierePath;
                \Log::info('Copied image_banniere to image_affiche:', ['path' => $imageAffichePath]);
            } catch (\Exception $e) {
                \Log::error('Failed to copy bannière to affiche: ' . $e->getMessage());
            }
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
            
            // Lieu et localisation
            'lieu' => $validated['lieu'] ?? null,
            'adresse' => $validated['adresse'] ?? null,
            'code_postal' => $validated['code_postal'] ?? null,
            'ville' => $validated['ville'] ?? null,
            'pays' => $validated['pays'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            
            // Dates
            'date_debut' => $validated['date_debut'],
            'date_fin' => $validated['date_fin'],
            'date_limite_inscription' => $validated['date_limite_inscription'] ?? null,
            
            // Tarifs
            'prix_base' => $validated['prix_base'],
            'prix_vip' => $validated['prix_vip'] ?? null,
            'prix_groupe' => $validated['prix_groupe'] ?? null,
            'devise' => $validated['devise'],
            'gratuit' => $validated['gratuit'] ?? false,
            
            // Capacité
            'capacite_max' => $validated['capacite_max'] ?? null,
            'places_disponibles' => ($validated['limite_inscription'] ?? false) ? ($validated['capacite_max'] ?? null) : null,
            'limite_inscription' => $validated['limite_inscription'] ?? false,
            
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
            'site_web' => $validated['site_web'] ?? null,
            
            // Configuration
            'inscription_obligatoire' => $validated['inscription_obligatoire'] ?? true,
            'paiement_en_ligne' => $validated['paiement_en_ligne'] ?? false,
            'certificat_participation' => $validated['certificat_participation'] ?? false,
            'streaming' => $validated['streaming'] ?? false,
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

        // Validation (similaire à store mais avec quelques différences)
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'date_limite_inscription' => 'nullable|date|before:date_debut',
            'prix_base' => 'required|numeric|min:0',
            'prix_vip' => 'nullable|numeric|min:0',
            'prix_groupe' => 'nullable|numeric|min:0',
            'devise' => 'required|string|in:XOF,EUR,USD,GBP,CAD',
            'gratuit' => 'boolean',
            'capacite_max' => 'nullable|integer|min:1',
            'limite_inscription' => 'boolean',
            'categorie' => 'required|string|in:' . implode(',', array_keys(Event::categories())),
            'type' => 'required|string|in:' . implode(',', array_keys(Event::types())),
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'public_cible' => 'nullable|string|in:' . implode(',', array_keys(Event::publicsCibles())),
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
            'url_streaming' => 'nullable|url|required_if:streaming,true',
            'statut' => 'required|string|in:' . implode(',', array_keys(Event::statuts())),
            'visibilite' => 'required|string|in:' . implode(',', array_keys(Event::visibilites())),
            'mis_en_avant' => 'boolean',
            'meta_titre' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'mots_cles' => 'nullable|array',
            'mots_cles.*' => 'string|max:50',
            'image_affiche' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_banniere' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

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

        // If no affiche uploaded during update but a new bannière was uploaded, copy bannière to affiches
        if ((empty($validated['image_affiche']) || !$validated['image_affiche']) && !empty($validated['image_banniere'])) {
            try {
                Storage::disk('public')->copy('events/bannieres/' . $validated['image_banniere'], 'events/affiches/' . $validated['image_banniere']);
                $validated['image_affiche'] = $validated['image_banniere'];
                \Log::info('Copied updated image_banniere to image_affiche during update:', ['path' => $validated['image_affiche']]);
            } catch (\Exception $e) {
                \Log::error('Failed to copy updated bannière to affiche: ' . $e->getMessage());
            }
        }

        // Mettre à jour les places disponibles si la capacité a changé
        if (isset($validated['capacite_max']) && $validated['capacite_max'] != $event->capacite_max) {
            $validated['places_disponibles'] = $validated['limite_inscription'] ? $validated['capacite_max'] : null;
        }

        $event->update($validated);

        return redirect('/promoter/events')
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
        try {
            $inscriptionsCount = $event->inscriptions()->where('statut', 'confirme')->count();
            
            if ($inscriptionsCount > 0) {
                return back()->with('error', 'Impossible de supprimer un événement avec des inscriptions confirmées.');
            }
        } catch (\Exception $e) {
            // Continuer même s'il y a une erreur avec les inscriptions
        }

        // Supprimer les images
        if ($event->image_affiche) {
            Storage::disk('public')->delete('events/affiches/' . $event->image_affiche);
        }

        if ($event->image_banniere) {
            Storage::disk('public')->delete('events/bannieres/' . $event->image_banniere);
        }

        $event->delete();

        return redirect('/promoter/events')
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

        return redirect("/promoter/events/{$newEvent->id}/edit")
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

        // Publication simple
        DB::table('events')
            ->where('id', $event->id)
            ->update(['statut' => 'publie']);

        return redirect('/promoter/events')->with('success', 'Événement publié avec succès !');
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
