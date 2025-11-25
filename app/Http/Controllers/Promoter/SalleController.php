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

        if (!$salle) {
            // Rediriger vers la page de création si aucune salle n'existe
            return Inertia::render('Promoter/CreateSalle');
        }

        return Inertia::render('Promoter/Venues', [
            'salle' => $salle,
            'coordinates' => [
                'lat' => (float) $salle->latitude,
                'lng' => (float) $salle->longitude
            ]
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

        return Inertia::render('Promoter/CreateSalle', [
            'defaultCoordinates' => [
                'lat' => 14.6928, // Dakar par défaut
                'lng' => -17.4467
            ],
            'pays_africains' => $this->getAfricanCountries(),
            'types_salle' => $this->getVenueTypes(),
            'categories_salle' => $this->getVenueCategories()
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
            'type' => 'required|string|in:' . implode(',', array_keys($this->getVenueTypes())),
            'categorie' => 'required|string|in:' . implode(',', array_keys($this->getVenueCategories())),
            
            // Adresse
            'adresse' => 'required|string|max:255',
            'code_postal' => 'required|string|max:20',
            'ville' => 'required|string|max:100',
            'pays' => 'required|string|max:100',
            'region' => 'nullable|string|max:100',
            'departement' => 'nullable|string|max:100',
            'quartier' => 'nullable|string|max:100',
            
            // Coordonnées GPS
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            
            // Contact
            'telephone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'site_web' => 'nullable|url|max:255',
            'reseaux_sociaux' => 'nullable|array',
            'reseaux_sociaux.facebook' => 'nullable|url|max:255',
            'reseaux_sociaux.instagram' => 'nullable|url|max:255',
            'reseaux_sociaux.twitter' => 'nullable|url|max:255',
            'reseaux_sociaux.tiktok' => 'nullable|url|max:255',
            
            // Capacité
            'capacite' => 'required|integer|min:1',
            'surface_area' => 'nullable|integer|min:1',
            
            // Équipements
            'machines_arcade' => 'nullable|integer|min:0',
            'casques_vr' => 'nullable|integer|min:0',
            'flippers' => 'nullable|integer|min:0',
            'consoles_retro' => 'nullable|integer|min:0',
            'pc_gaming' => 'nullable|integer|min:0',
            'tables_bowling' => 'nullable|integer|min:0',
            'tables_billard' => 'nullable|integer|min:0',
            
            // Services (booléens)
            'wifi_gratuit' => 'boolean',
            'parking' => 'boolean',
            'climatisation' => 'boolean',
            'accessibilite_pmr' => 'boolean',
            'surveillance_24h' => 'boolean',
            'snack_bar' => 'boolean',
            'restaurant' => 'boolean',
            'bar' => 'boolean',
            'terrasse' => 'boolean',
            'espace_fumeur' => 'boolean',
            'vestiaires' => 'boolean',
            
            // Horaires
            'horaires_ouverture' => 'nullable|array',
            'jours_fermes' => 'nullable|array',
            
            // Médias
            'image_couverture' => 'nullable|string|max:500',
            'images_galerie' => 'nullable|array',
            'images_galerie.*' => 'string|max:500',
            'video_presentation' => 'nullable|url|max:500',
            
            // SEO
            'meta_titre' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'mots_cles' => 'nullable|array',
            'mots_cles.*' => 'string|max:50',
            
            // Point de repère
            'point_repere' => 'nullable|string|max:255',
        ]);

        // Générer le slug
        $validated['slug'] = Salle::generateUniqueSlug($validated['nom']);
        $validated['user_id'] = $user->id;
        
        // Valeurs par défaut
        $validated['statut'] = 'en_attente';
        $validated['valide_par_admin'] = false;
        $validated['nombre_vues'] = 0;
        $validated['nombre_favoris'] = 0;
        $validated['note_moyenne'] = 0;
        $validated['nombre_avis'] = 0;

        // Créer la salle
        $salle = Salle::create($validated);

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

        return Inertia::render('Promoter/EditSalle', [
            'salle' => $salle,
            'coordinates' => [
                'lat' => (float) $salle->latitude,
                'lng' => (float) $salle->longitude
            ],
            'pays_africains' => $this->getAfricanCountries(),
            'types_salle' => $this->getVenueTypes(),
            'categories_salle' => $this->getVenueCategories()
        ]);
    }

    /**
     * Mettre à jour la salle
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $salle = $user->salle;

        if (!$salle) {
            return redirect()->route('promoter.venues.create')
                ->with('error', 'Vous n\'avez pas encore de salle.');
        }

        $validated = $request->validate([
            // Informations de base
            'nom' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'type' => 'required|string|in:' . implode(',', array_keys($this->getVenueTypes())),
            'categorie' => 'required|string|in:' . implode(',', array_keys($this->getVenueCategories())),
            
            // Adresse
            'adresse' => 'required|string|max:255',
            'code_postal' => 'required|string|max:20',
            'ville' => 'required|string|max:100',
            'pays' => 'required|string|max:100',
            'region' => 'nullable|string|max:100',
            'departement' => 'nullable|string|max:100',
            'quartier' => 'nullable|string|max:100',
            
            // Coordonnées GPS
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            
            // Contact
            'telephone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'site_web' => 'nullable|url|max:255',
            'reseaux_sociaux' => 'nullable|array',
            
            // Capacité
            'capacite' => 'required|integer|min:1',
            'surface_area' => 'nullable|integer|min:1',
            
            // Équipements
            'machines_arcade' => 'nullable|integer|min:0',
            'casques_vr' => 'nullable|integer|min:0',
            'flippers' => 'nullable|integer|min:0',
            'consoles_retro' => 'nullable|integer|min:0',
            'pc_gaming' => 'nullable|integer|min:0',
            'tables_bowling' => 'nullable|integer|min:0',
            'tables_billard' => 'nullable|integer|min:0',
            
            // Services (booléens)
            'wifi_gratuit' => 'boolean',
            'parking' => 'boolean',
            'climatisation' => 'boolean',
            'accessibilite_pmr' => 'boolean',
            'surveillance_24h' => 'boolean',
            'snack_bar' => 'boolean',
            'restaurant' => 'boolean',
            'bar' => 'boolean',
            'terrasse' => 'boolean',
            'espace_fumeur' => 'boolean',
            'vestiaires' => 'boolean',
            
            // Horaires
            'horaires_ouverture' => 'nullable|array',
            'jours_fermes' => 'nullable|array',
            
            // Médias
            'image_couverture' => 'nullable|string|max:500',
            'images_galerie' => 'nullable|array',
            'video_presentation' => 'nullable|url|max:500',
            
            // SEO
            'meta_titre' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'mots_cles' => 'nullable|array',
            
            // Point de repère
            'point_repere' => 'nullable|string|max:255',
        ]);

        // Si le nom change, régénérer le slug
        if ($salle->nom !== $validated['nom']) {
            $validated['slug'] = Salle::generateUniqueSlug($validated['nom']);
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
            'arcade' => 'Salle d\'arcade',
            'vr' => 'Centre VR',
            'retro' => 'Retro gaming',
            'esports' => 'E-sport',
            'mixed' => 'Mixte',
            'bowling' => 'Bowling',
            'billard' => 'Billard',
            'laser' => 'Laser game',
            'escape' => 'Escape game',
            'karaoke' => 'Karaoke',
        ];
    }

    /**
     * Obtenir les catégories de salles
     */
    private function getVenueCategories(): array
    {
        return [
            'bar' => 'Bar',
            'restaurant' => 'Restaurant',
            'club' => 'Club',
            'centre_commercial' => 'Centre commercial',
            'hotel' => 'Hôtel',
            'complexe_sportif' => 'Complexe sportif',
            'espace_jeux' => 'Espace de jeux',
            'loisir' => 'Centre de loisirs',
            'autre' => 'Autre',
        ];
    }
}
