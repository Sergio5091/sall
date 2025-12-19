<?php

namespace App\Http\Controllers\Promoter;

use App\Http\Controllers\Controller;
use App\Models\Salle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class SalleController extends Controller
{
    /**
     * Afficher la salle du promoteur
     */
    public function index()
    {
        $user = Auth::user();
        $salle = Salle::where('promoter_id', $user->id)->first();

        // Ajouter les données de notifications pour le sidebar
        $unreadCount = \App\Models\Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();
            
        // DEBUG
        \Log::info('DEBUG SalleController index() - unreadCount: ' . $unreadCount);
        \Log::info('DEBUG SalleController index() - user_id: ' . $user->id);

        // Toujours afficher Venues.vue avec les données appropriées
        return Inertia::render('Promoter/Venues', [
            'salle' => $salle,
            'coordinates' => $salle ? [
                'lat' => (float) $salle->latitude,
                'lng' => (float) $salle->longitude
            ] : null,
            'unreadCount' => $unreadCount
        ]);
    }

    /**
     * Afficher une salle spécifique
     */
    public function show(Salle $salle)
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur est le propriétaire de la salle
        if ($salle->promoter_id !== $user->id) {
            return redirect()->route('promoter.venues')
                ->with('error', 'Vous n\'êtes pas autorisé à voir cette salle.');
        }

        // Ajouter les données de notifications pour le sidebar
        $unreadCount = \App\Models\Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        return Inertia::render('Promoter/Venues', [
            'salle' => $salle,
            'coordinates' => [
                'lat' => (float) $salle->latitude,
                'lng' => (float) $salle->longitude
            ],
            'unreadCount' => $unreadCount
        ]);
    }

    /**
     * Afficher le formulaire de création de salle
     */
    public function create()
    {
        $user = Auth::user();
        
        // Vérifier si l'utilisateur a déjà une salle
        if ($user->salle) {
            return redirect()->route('promoter.venues')
                ->with('error', 'Vous avez déjà une salle. Vous ne pouvez en avoir qu\'une seule.');
        }

        // Ajouter les données de notifications pour le sidebar
        $unreadCount = \App\Models\Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        return Inertia::render('Promoter/CreateSalle', [
            'defaultCoordinates' => [
                'lat' => 14.6928, // Dakar par défaut
                'lng' => -17.4467
            ],
            'pays_africains' => $this->getAfricanCountries(),
            'types_salle' => $this->getVenueTypes(),
            'categories_salle' => $this->getVenueCategories(),
            'unreadCount' => $unreadCount
        ]);
    }

    /**
     * Enregistrer une nouvelle salle
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Vérifier si l'utilisateur a déjà une salle
        if ($user->salle) {
            return redirect()->back()
                ->with('error', 'Vous avez déjà une salle. Vous ne pouvez en avoir qu\'une seule.');
        }

        $validated = $request->validate([
            // Informations de base
            'nom' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'type' => 'required|string',
            'categorie' => 'required|string',
            
            // Adresse
            'adresse' => 'required|string|max:255',
            'code_postal' => 'nullable|string|max:20',
            'ville' => 'required|string|max:100',
            'pays' => 'required|string|max:100',
            'quartier' => 'nullable|string|max:100',
            
            // Coordonnées GPS
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            
            // Contact
            'telephone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'site_web' => 'nullable|url|max:255',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            
            // Capacité
            'capacite' => 'required|integer|min:1',
            
            // Tarifs
            'prix_heure' => 'nullable|numeric|min:0',
            'prix_journee' => 'nullable|numeric|min:0',
            
            // Horaires
            'horaires' => 'nullable|string',
            
            // Services
            'services' => 'nullable|string',
            
            // Médias
            'image_url' => 'nullable|string|max:255',
            'images' => 'nullable|array',
            'logo' => 'nullable|string|max:255',
        ]);

        // Gérer les fichiers uploadés
        if ($request->hasFile('banniere_file')) {
            $bannierePath = $request->file('banniere_file')->store('salles/bannieres', 'public');
            $validated['image_url'] = $bannierePath;
        }
        
        if ($request->hasFile('logo_file')) {
            $logoPath = $request->file('logo_file')->store('salles/logos', 'public');
            $validated['logo'] = $logoPath;
        }
        
        // Gérer les images de la galerie
        $galerieImages = [];
        if ($request->hasFile('galerie_files')) {
            foreach ($request->file('galerie_files') as $index => $file) {
                if ($file) {
                    $imagePath = $file->store('salles/galerie', 'public');
                    $galerieImages[] = $imagePath;
                }
            }
            $validated['images'] = $galerieImages;
        }

        // Gérer les objets JSON
        if ($request->has('horaires')) {
            $horaires = $request->input('horaires');
            if (is_string($horaires)) {
                $validated['horaires'] = json_decode($horaires, true);
            } else {
                $validated['horaires'] = $horaires;
            }
        }
        
        if ($request->has('services')) {
            $services = $request->input('services');
            if (is_string($services)) {
                $validated['services'] = json_decode($services, true);
            } else {
                $validated['services'] = $services;
            }
        }

        // Générer le slug
        $validated['slug'] = Salle::generateUniqueSlug($validated['nom']);
        $validated['promoter_id'] = $user->id;
        
        // Valeurs par défaut
        $validated['statut'] = 'actif';
        $validated['valide'] = false;
        $validated['nombre_vues'] = 0;
        $validated['nombre_favoris'] = 0;
        $validated['note_moyenne'] = 0;
        $validated['nombre_avis'] = 0;
        
        // Debug logs
        \Log::info('Création de salle avec les données:', $validated);
        \Log::info('Statut valide:', ['valide' => $validated['valide']]);

        // Créer la salle
        $salle = Salle::create($validated);
        
        // Envoyer une notification au promoteur
        \App\Models\Notification::createForUser(
            $user->id,
            'Salle en attente de validation',
            "Votre salle '{$salle->nom}' a été créée avec succès et est en attente de validation par notre équipe d'administration.",
            'warning',
            'salle',
            $salle->id
        );
        
        // Vérifier après création
        \Log::info('Salle créée:', ['id' => $salle->id, 'valide' => $salle->valide, 'statut' => $salle->statut]);

        return redirect()->route('promoter.venues')
            ->with('success', 'Votre salle a été créée avec succès ! Elle est en attente de validation par notre équipe.');
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit()
    {
        $user = Auth::user();
        $salle = $user->salle;

        if (!$salle) {
            return redirect()->route('promoter.venues.create')
                ->with('error', 'Vous n\'avez pas encore de salle.');
        }

        // Ajouter les données de notifications pour le sidebar
        $unreadCount = \App\Models\Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        return Inertia::render('Promoter/EditSalle', [
            'salle' => $salle,
            'coordinates' => [
                'lat' => (float) $salle->latitude,
                'lng' => (float) $salle->longitude
            ],
            'pays_africains' => $this->getAfricanCountries(),
            'types_salle' => $this->getVenueTypes(),
            'categories_salle' => $this->getVenueCategories(),
            'unreadCount' => $unreadCount
        ]);
    }

    /**
     * Mettre à jour la salle
     */
    public function update(Request $request, Salle $salle)
    {
        $user = Auth::user();
        
        if ($salle->promoter_id !== $user->id) {
            return redirect()->route('promoter.venues')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier cette salle.');
        }

        // Debug: voir ce que Laravel reçoit
        \Log::info('Update request data', $request->all());
        \Log::info('Files', $request->allFiles());
        \Log::info('Logo file exists', ['has_file' => $request->hasFile('logo_file')]);
        \Log::info('Logo field', ['logo' => $request->input('logo')]);

        $validated = $request->validate([
            // Informations de base
            'nom' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'type' => 'required|string',
            'categorie' => 'required|string',
            
            // Adresse
            'adresse' => 'required|string|max:255',
            'code_postal' => 'nullable|string|max:20',
            'ville' => 'required|string|max:100',
            'pays' => 'required|string|max:100',
            'quartier' => 'nullable|string|max:100',
            
            // Coordonnées GPS
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            
            // Contact
            'telephone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'site_web' => 'nullable|url|max:255',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            
            // Capacité
            'capacite' => 'required|integer|min:1',
            
            // Tarifs
            'prix_heure' => 'nullable|numeric|min:0',
            'prix_journee' => 'nullable|numeric|min:0',
            
            // Horaires
            'horaires' => 'nullable|string',
            
            // Services
            'services' => 'nullable|string',
            
            // Médias
            'image_url' => 'nullable|string|max:255',
            'images' => 'nullable|array',
            'logo' => 'nullable|string|max:255',
        ]);

        // Gérer les fichiers uploadés
        if ($request->hasFile('banniere_file')) {
            $bannierePath = $request->file('banniere_file')->store('salles/bannieres', 'public');
            $validated['image_url'] = $bannierePath;
        }
        
        if ($request->hasFile('logo_file')) {
            $logoPath = $request->file('logo_file')->store('salles/logos', 'public');
            $validated['logo'] = $logoPath;
        }
        
        // Gérer les images de la galerie
        $galerieImages = [];
        if ($request->hasFile('galerie_files')) {
            foreach ($request->file('galerie_files') as $index => $file) {
                if ($file) {
                    $imagePath = $file->store('salles/galerie', 'public');
                    $galerieImages[] = $imagePath;
                }
            }
            // Conserver les images existantes si aucune nouvelle image n'est uploadée
if (empty($galerieImages)) {
    $validated['images'] = $salle->images ?? [];
} else {
    $validated['images'] = $galerieImages;
}
        }

        // Gérer les objets JSON
        if ($request->has('horaires')) {
            $horaires = $request->input('horaires');
            if (is_string($horaires)) {
                $validated['horaires'] = json_decode($horaires, true);
            } else {
                $validated['horaires'] = $horaires;
            }
        }
        
        if ($request->has('services')) {
            $services = $request->input('services');
            if (is_string($services)) {
                $validated['services'] = json_decode($services, true);
            } else {
                $validated['services'] = $services;
            }
        }

        $salle->update($validated);

        return redirect()->route('promoter.venues')
            ->with('success', 'Votre salle a été mise à jour avec succès !');
    }

    /**
     * Supprimer la salle
     */
    public function destroy()
    {
        $user = Auth::user();
        $salle = $user->salle;

        if (!$salle) {
            return redirect()->back()
                ->with('error', 'Vous n\'avez pas de salle à supprimer.');
        }

        // Vérifier s'il y a des événements associés
        if ($salle->evenements()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer cette salle car elle contient des événements.');
        }

        $salle->delete();

        return redirect()->route('promoter.dashboard')
            ->with('success', 'Votre salle a été supprimée avec succès.');
    }

    /**
     * Obtenir la liste des pays africains
     */
    private function getAfricanCountries(): array
    {
        return [
            'SN' => 'Sénégal',
            'CI' => 'Côte d\'Ivoire',
            'ML' => 'Mali',
            'BF' => 'Burkina Faso',
            'NE' => 'Niger',
            'TG' => 'Togo',
            'BJ' => 'Bénin',
            'GN' => 'Guinée',
            'GW' => 'Guinée-Bissau',
            'SL' => 'Sierra Leone',
            'LR' => 'Libéria',
            'GH' => 'Ghana',
            'NG' => 'Nigeria',
            'CM' => 'Cameroun',
            'TD' => 'Tchad',
            'CF' => 'Centrafrique',
            'CG' => 'Congo-Brazzaville',
            'CD' => 'Congo-Kinshasa',
            'GA' => 'Gabon',
            'GQ' => 'Guinée Équatoriale',
            'AO' => 'Angola',
            'ZM' => 'Zambie',
            'MW' => 'Malawi',
            'MZ' => 'Mozambique',
            'ZW' => 'Zimbabwe',
            'BW' => 'Botswana',
            'ZA' => 'Afrique du Sud',
            'NA' => 'Namibie',
            'SZ' => 'Eswatini',
            'LS' => 'Lesotho',
            'MG' => 'Madagascar',
            'MU' => 'Maurice',
            'SC' => 'Seychelles',
            'KM' => 'Comores',
            'ET' => 'Éthiopie',
            'ER' => 'Érythrée',
            'DJ' => 'Djibouti',
            'SO' => 'Somalie',
            'KE' => 'Kenya',
            'UG' => 'Ouganda',
            'TZ' => 'Tanzanie',
            'RW' => 'Rwanda',
            'BI' => 'Burundi',
            'EG' => 'Égypte',
            'LY' => 'Libye',
            'TN' => 'Tunisie',
            'DZ' => 'Algérie',
            'MA' => 'Maroc',
            'SD' => 'Soudan',
            'SS' => 'Soudan du Sud',
        ];
    }

    /**
     * Obtenir les types de salles
     */
    private function getVenueTypes(): array
    {
        return [
            'arcade' => "Salle d'arcade",
            'vr' => 'Centre VR',
            'retro' => 'Rétro gaming',
            'esports' => 'Arène e-sport',
            'pc_gaming' => 'PC gaming',
            'simulateur' => 'Simulateur',
            'bowling' => 'Bowling',
            'escape' => 'Escape game',
            'karaoke' => 'Karaoké',
            'barcade' => 'Barcade',
        ];
    }

    /**
     * Obtenir les catégories de salles
     */
    private function getVenueCategories(): array
    {
        return [
            'action' => 'Action',
            'aventure' => 'Aventure',
            'rpg' => 'Jeu de rôle (RPG)',
            'puzzle' => 'Réflexion / Puzzle',
            'simulation' => 'Simulation','strategie' => 'Stratégie',
            'sport_course' => 'Sport et Course',
            'horreur' => 'Horreur',
            'jeux_societe' => 'Jeux de société',
            'jeux_cartes' => 'Jeux de cartes',
            'rpg_papier' => 'Jeux de rôle (papier)',
            'jeux_traditionnels' => 'Jeux traditionnels',
        ];
    }
}
